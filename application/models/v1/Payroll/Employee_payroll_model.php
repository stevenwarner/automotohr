<?php defined('BASEPATH') || exit('No direct script access allowed');
loadUpModel("v1/Payroll/Base_payroll_model", "base_payroll_model");
/**
 * Employee payroll model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Employee_payroll_model extends Base_payroll_model
{

    private $employeeOldData;


    private $dataToStoreEvents;

    /**
     * main function
     */
    public function __construct()
    {
        $this->employeeOldData = [];
        $this->setDataToStoreEvents();
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
     * set employee data before update
     *
     * @param array $employeeOldData
     * @return reference
     */
    public function setEmployeeOldData(
        array $employeeOldData
    ) {
        //
        $this->employeeOldData =
            $employeeOldData;
        return $this;
    }

    /**
     * sync employees
     */
    public function syncEmployees()
    {
        //
        $employees = explode(",", $this->gustoCompany["employee_ids"]);
        //
        if ($employees) {
            foreach ($employees as $v0) {
                $this->onboardEmployee($v0);
            }
        }
    }

    /**
     * Sync employee
     * triggers from the webhook
     * Gusto To Store
     *
     * @method gustoToStoreWA
     * @method gustoToStoreHA
     * @method gustoToStoreJobsAndCompensations
     * @method gustoToStoreFederalTax
     * @method gustoToStoreStateTax
     * @method gustoToStoreFederalTax
     * @method gustoToStorePaymentMethod
     * @method gustoToStoreForms
     * @version 1.0
     */
    public function syncEmployeeFromGustoToStore(
        string $employeeGustoUUID
    ) {
        // set the employee details
        $this->getGustoLinkedEmployeeDetails(
            $employeeGustoUUID,
            [
                "employee_sid"
            ],
            true,
            "gusto_uuid"
        );
        // sync work address
        $this->gustoToStoreWA();
        // sync home address
        $this->gustoToStoreHA();
        // sync job & compensations
        $this->gustoToStoreJobsAndCompensations();
        // sync federal tax
        $this->gustoToStoreFederalTax();
        // sync state tax
        $this->gustoToStoreStateTax();
        // sync payment method
        $this->gustoToStorePaymentMethod();
        // sync forms
        $this->gustoToStoreForms();
    }

    /**
     * Set employee array
     *
     * @param int $employeeId
     */
    public function setEmployee(int $employeeId)
    {
        //
        $this->getGustoLinkedEmployeeDetails(
            $employeeId,
            [
                "employee_sid"
            ]
        );
        return $this;
    }

    /**
     * Update store to Gusto employee
     *
     * @param array $events
     * @return array
     */
    public function dataStoreToGustoEmployeeFlow(
        array $events
    ) {
        // loop through the events
        foreach ($events as $event) {
            // get the event
            $eventToBeCalled = $this->dataToStoreEvents[$event];
            // call the event
            $this->$eventToBeCalled();
        }
        return $this;
    }

    /**
     * Update store to Gusto employee
     * job & compensation
     *
     * @method dataStoreToGustoEmployeeCompensation
     * @method dataStoreToGustoEmployeeJob
     */
    public function dataStoreToGustoEmployeeJobAndCompensationFlow()
    {
        // get the job and compensation
        // gusto uuids details
        $this->loadGustoJobAndCompensationIds();
        // update compensation
        $this->dataStoreToGustoEmployeeCompensation();
        // update job title
        $this->dataStoreToGustoEmployeeJob();
    }

    /**
     * Update store to Gusto employee
     * home address
     *
     * @method dataStoreToGustoEmployeeCompensation
     * @method dataStoreToGustoEmployeeJob
     */
    public function dataStoreToGustoEmployeeHomeAddressFlow()
    {
        // get the employee
        // gusto uuids details
        $this->loadGustoEmployeeHomeAddressIds();
        // check and set employee home address
        if (!$this->gustoIdArray["employee_home_address"]) {
            $this->storeToGustoHA();
        } else {
            $this->dataStoreToGustoEmployeeHomeAddress();
        }
    }

    /**
     * Update store to Gusto employee
     * home address
     *
     * @method dataStoreToGustoEmployeeCompensation
     * @method dataStoreToGustoEmployeeJob
     */
    public function dataStoreToGustoEmployeeProfileFlow()
    {
        $this->dataStoreToGustoEmployeeProfile();
    }




    /******************************************************************* */
    // Private Events
    /******************************************************************* */

    /**
     * sync work address
     * store to Gusto
     *
     * @return string
     */
    private function getEmployeeHireDate()
    {
        $record = $this
            ->db
            ->select([
                "joined_at",
                "registration_date",
                "rehire_date"
            ])
            ->where(
                "sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->get("users")
            ->row_array();
        //
        return get_employee_latest_joined_date(
            $record["registration_date"],
            $record["joined_at"],
            $record["rehire_date"]
        );
    }

    /**
     * get the employee address
     *
     * @param int $employeeId
     * @return array
     */
    private function getEmployeeAddress(
        int $employeeId
    ): array {
        $record = $this
            ->db
            ->select([
                "users.Location_City",
                "users.Location_ZipCode",
                "users.Location_Address",
                "users.Location_Address_2",
                "users.Location_Country",
                "states.state_code",
                "states.state_name",
            ])
            ->join(
                "states",
                "states.sid = users.Location_State",
                "left"
            )
            ->where(
                "users.sid",
                $employeeId
            )
            ->limit(1)
            ->get("users")
            ->row_array();
        //
        $errors = [];
        //
        if (!$record["Location_Address"]) {
            $errors[] = '"Street 1" is missing.';
        }
        if (!$record["Location_City"]) {
            $errors[] = '"City" is missing.';
        }
        if (!$record["state_code"]) {
            $errors[] = '"State" is missing.';
        }
        if (!$record["Location_ZipCode"]) {
            $errors[] = '"Zip Code" is missing.';
        }
        // set return array
        return [
            "street_1" => $record["Location_Address"],
            "street_2" => $record["Location_Address_2"],
            "city" => $record["Location_City"],
            "state_code" => $record["state_code"],
            "zip" => $record["Location_ZipCode"],
            "country_code" => "USA",
            "errors" => $errors,
        ];
    }

    /**
     * get the employee profile
     *
     * @param int $employeeId
     * @return array
     */
    private function getEmployeeProfile(
        int $employeeId
    ): array {
        $record = $this
            ->db
            ->select([
                "users.first_name",
                "users.last_name",
                "users.middle_name",
                "users.email",
                "users.dob",
                "users.ssn",
            ])
            ->where(
                "users.sid",
                $employeeId
            )
            ->limit(1)
            ->get("users")
            ->row_array();
        //
        $record["errors"] = [];
        $record["middle_name"] = ucwords(
            substr(
                $record["middle_name"],
                0,
                1
            )
        );
        // set return array
        return $record;
    }

    /**
     * get employee details
     *
     * @return array
     */
    private function getEmployeeJobWithCompensation()
    {
        $record = $this
            ->db
            ->select([
                "job_title",
                "complynet_job_title",
                "registration_date",
                "joined_at",
                "rehire_date",
            ])
            ->limit(1)
            ->where(
                "sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->get("users")
            ->row_array();
        // set return array
        $returnArray = [
            "title" => "Automotive",
            "hire_date" => get_employee_latest_joined_date(
                $record["registration_date"],
                $record["joined_at"],
                $record["rehire_date"]
            ),
            "errors" => []
        ];
        //
        if ($record["job_title"]) {
            $returnArray["title"] = $record["job_title"];
        } elseif ($record["complynet_job_title"]) {
            $returnArray["title"] = $record["complynet_job_title"];
        }
        //
        if (!$returnArray["title"]) {
            $returnArray["errors"][] = '"Job title" is missing.';
        }
        if (!$returnArray["hire_date"]) {
            $returnArray["errors"][] = '"Hire date" is missing.';
        }
        //
        return $returnArray;
    }

    /**
     * get employee details
     *
     * @return array
     */
    private function getEmployeeCompensation()
    {
        $record = $this
            ->db
            ->select([
                "hourly_rate",
                "hourly_technician",
                "flat_rate_technician",
                'semi_monthly_salary',
                "semi_monthly_draw",
            ])
            ->limit(1)
            ->where(
                "sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->get("users")
            ->row_array();
        // set return array
        $returnArray = [
            "rate" => "",
            "flsa_status" => "",
            "payment_unit" => "",
            "adjust_for_minimum_wage" => 0,
            "minimum_wages" => [],
            "errors" => []
        ];
        //
        if ((int) $record['hourly_rate'] != 0) {
            $returnArray['rate'] = $record['hourly_rate'];
            $returnArray['flsa_status'] = "Nonexempt";
            $returnArray['payment_unit'] = "Hour";
        } elseif ((int) $record['hourly_technician'] != 0) {
            $returnArray['rate'] = $record['hourly_technician'];
            $returnArray['flsa_status'] = "Nonexempt";
            $returnArray['payment_unit'] = "Hour";
        } elseif ((int) $record['flat_rate_technician'] != 0) {
            $returnArray['rate'] = $record['flat_rate_technician'];
            $returnArray['flsa_status'] = "Nonexempt";
            $returnArray['payment_unit'] = "Hour";
        } elseif ((int) $record['semi_monthly_salary'] != 0) {
            $returnArray['rate'] = $record['semi_monthly_salary'];
            $returnArray['flsa_status'] = "Exempt";
            $returnArray['payment_unit'] = "Month";
        } elseif ((int) $record['semi_monthly_draw'] != 0) {
            $returnArray['rate'] = $record['semi_monthly_draw'];
            $returnArray['flsa_status'] = "Exempt";
            $returnArray['payment_unit'] = "Month";
        }
        //
        if (!$returnArray["rate"]) {
            $returnArray["errors"][] = '"Rate" is missing.';
        }
        if (!$returnArray["flsa_status"]) {
            $returnArray["errors"][] = '"flsa status" is missing.';
        }
        if (!$returnArray["payment_unit"]) {
            $returnArray["errors"][] = '"Payment unit" is missing.';
        }
        //
        return $returnArray;
    }

    /**
     * get employee details
     *
     * @return array
     */
    private function getEmployeeEmployments()
    {
        $records =
            $this
            ->db
            ->select([
                "sid",
                "termination_date",
                "status_change_date",
            ])
            ->where("employee_sid", $this->gustoEmployee["employee_sid"])
            ->where("payroll_version is null", null)
            ->where("gusto_uuid is null", null)
            ->where_in("employee_status", [1, 8])
            ->get("terminated_employees")
            ->result_array();
        //
        $tmp = [];
        //
        foreach ($records as $v0) {
            if ($v0["termination_date"]) {
                $tmp[] = [
                    "id" => $v0["sid"],
                    "type" => "terminated",
                    "date" => $v0["termination_date"],
                ];
            } else {
                $tmp[] = [
                    "id" => $v0["sid"],
                    "type" => "rehired",
                    "date" => $v0["status_change_date"],
                ];
            }
        }
        // sort the array in asc order by date index
        usort($tmp, "sortByDate");

        return $tmp;
    }


    // ---------------------------------------------------
    // sync
    // ---------------------------------------------------

    /**
     * Onboard employee
     *
     * @param int $employeeId
     */
    private function onboardEmployee(int $employeeId)
    {
        // check if employee already exists
        if (
            !$this
                ->db
                ->where("employee_sid", $employeeId)
                ->count_all_results("gusto_companies_employees")
        ) {
            $this->createEmployee($employeeId);
        }
        //
        $this->getGustoLinkedEmployeeDetails(
            $employeeId,
            [
                "employee_sid"
            ]
        );
        // sync work address
        $this->syncWorkAddress();
        // sync home address
        $this->syncHomeAddress();
        // // sync job & compensations
        $this->syncJobAndCompensations();
        // // sync employments
        $this->syncEmployments();
        // // sync federal tax
        $this->syncFederalTax();
        // // sync state tax
        $this->syncStateTax();
        // // sync payment method
        $this->syncPaymentMethod();
        // // sync forms
        $this->syncForms();

        return true;
    }

    /**
     * check if employee exists
     *
     * @param int $employeeId
     * @return bool
     */
    private function createEmployee(int $employeeId): bool
    {
        // get the employee information
        $record = $this
            ->db
            ->select([
                "first_name",
                "last_name",
                "middle_name",
                "dob",
                "email",
                "ssn",
            ])
            ->where("sid", $employeeId)
            ->limit(1)
            ->get("users")
            ->row_array();
        //
        if (!$record) {
            return false;
        }
        // set request
        $request = [
            "first_name" => $record["first_name"],
            "last_name" => $record["last_name"],
            "middle_initial" => ucwords(substr($record["middle_name"], 0, 1)),
            "date_of_birth" => $record["dob"],
            "email" => $record["email"],
            "ssn" => $record["ssn"],
            "self_onboarding" => false,
        ];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "employees",
                $this->gustoCompany,
                $request,
                "POST"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors || !$response) {
            return false;
        }
        //
        $ins = [
            "company_sid" => $this->gustoCompany["company_sid"],
            "employee_sid" => $employeeId,
            "gusto_uuid" => $response["uuid"],
            "gusto_version" => $response["version"],
            "is_onboarded" => $response["onboarded"],
            "created_at" => getSystemDate(),
            "updated_at" => getSystemDate(),
        ];

        $this
            ->db
            ->insert(
                "gusto_companies_employees",
                $ins
            );

        return $this->db->insert_id();
    }


    // ---------------------------------------------------
    // sync work address
    // ---------------------------------------------------
    /**
     * sync work address
     */
    private function syncWorkAddress()
    {
        //
        $this->gustoToStoreWA();
        // check if not linked
        if (
            !$this
                ->db
                ->where("employee_sid", $this->gustoEmployee["employee_sid"])
                ->where("is_work_address", 1)
                ->count_all_results("gusto_companies_employees_work_addresses")
        ) {
            $this->storeToGustoWA();
            //
            $this->gustoToStoreWA();
        }
    }

    /**
     * sync work address
     * store to Gusto
     */
    private function storeToGustoWA()
    {
        // get company work address
        $record = $this
            ->db
            ->select([
                "gusto_uuid"
            ])
            ->where(
                "company_sid",
                $this->gustoCompany["company_sid"]
            )
            ->limit(1)
            ->get("gusto_companies_locations")
            ->row_array();
        // set request
        $request = [
            "location_uuid" => $record["gusto_uuid"],
            "effective_date" => $this->getEmployeeHireDate()
        ];

        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "work_addresses",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        $ins = [
            "employee_sid" => $this->gustoEmployee["employee_sid"],
            "gusto_uuid" => $response["uuid"],
            "gusto_version" => $response["version"],
            "gusto_location_uuid" => $response["location_uuid"],
            "effective_date" => $response["effective_date"],
            "active" => $response["active"],
            "street_1" => $response["street_1"],
            "street_2" => $response["street_2"],
            "city" => $response["city"],
            "state" => $response["state"],
            "zip" => $response["zip"],
            "country" => $response["country"],
            "is_work_address" => 1,
            "created_at" => getSystemDate(),
            "updated_at" => getSystemDate(),
        ];
        //
        $this
            ->db
            ->insert(
                "gusto_companies_employees_work_addresses",
                $ins
            );
        ///
        if ($this->db->insert_id()) {
            //
            $this->updateEmployeeChecklist("work_address");
        }
    }

    /**
     * sync work address
     * Gusto to Store
     */
    private function gustoToStoreWA()
    {
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "work_addresses",
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
            return false;
        }
        //
        if (!$response) {
            return false;
        }
        //
        foreach ($response as $v0) {
            // check if already exists
            if (
                !$this
                    ->db
                    ->where(
                        "employee_sid",
                        $this->gustoEmployee["employee_sid"]
                    )
                    ->where("gusto_uuid", $v0["uuid"])
                    ->where("is_work_address", 1)
                    ->count_all_results(
                        "gusto_companies_employees_work_addresses"
                    )
            ) {
                //
                $ins = [
                    "employee_sid" => $this->gustoEmployee["employee_sid"],
                    "gusto_uuid" => $v0["uuid"],
                    "gusto_version" => $v0["version"],
                    "gusto_location_uuid" => $v0["location_uuid"],
                    "effective_date" => $v0["effective_date"],
                    "active" => $v0["active"],
                    "street_1" => $v0["street_1"],
                    "street_2" => $v0["street_2"],
                    "city" => $v0["city"],
                    "state" => $v0["state"],
                    "zip" => $v0["zip"],
                    "is_work_address" => 1,
                    "country" => $v0["country"],
                    "created_at" => getSystemDate(),
                    "updated_at" => getSystemDate(),
                ];
                //
                $this
                    ->db
                    ->insert(
                        "gusto_companies_employees_work_addresses",
                        $ins
                    );
            } else {
                //
                $ins = [
                    "gusto_version" => $v0["version"],
                    "gusto_location_uuid" => $v0["location_uuid"],
                    "effective_date" => $v0["effective_date"],
                    "active" => $v0["active"],
                    "street_1" => $v0["street_1"],
                    "street_2" => $v0["street_2"],
                    "city" => $v0["city"],
                    "state" => $v0["state"],
                    "zip" => $v0["zip"],
                    "is_work_address" => 1,
                    "country" => $v0["country"],
                    "updated_at" => getSystemDate(),
                ];
                //
                $this
                    ->db
                    ->where(
                        "employee_sid",
                        $this->gustoEmployee["employee_sid"]
                    )
                    ->where("is_work_address", 1)
                    ->where("gusto_uuid", $v0["uuid"])
                    ->update(
                        "gusto_companies_employees_work_addresses",
                        $ins
                    );
            }
        }
    }

    // ---------------------------------------------------
    // sync work address
    // ---------------------------------------------------
    /**
     * sync work address
     */
    private function syncHomeAddress()
    {
        //
        $this->gustoToStoreHA();
        // check if not linked
        if (
            !$this
                ->db
                ->where("employee_sid", $this->gustoEmployee["employee_sid"])
                ->where("is_work_address", 0)
                ->count_all_results("gusto_companies_employees_work_addresses")
        ) {
            $this->storeToGustoHA();
            //
            $this->gustoToStoreHA();
        }
    }

    /**
     * sync work address
     * store to Gusto
     */
    private function storeToGustoHA()
    {
        // get company work address
        $record = $this
            ->getEmployeeAddress(
                $this->gustoEmployee["employee_sid"]
            );
        //
        if ($record["errors"]) {
            return false;
        }
        // set request
        $request = [
            "street_1" => $record["street_1"],
            "street_2" => $record["street_2"],
            "city" => $record["city"],
            "state" => $record["state_code"],
            "zip" => $record["zip"],
            "courtesy_withholding" => 0,
            "effective_date" => $this->getEmployeeHireDate()
        ];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "home_addresses",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        $ins = [
            "employee_sid" => $this->gustoEmployee["employee_sid"],
            "gusto_uuid" => $response["uuid"],
            "gusto_version" => $response["version"],
            "effective_date" => $response["effective_date"],
            "active" => $response["active"],
            "street_1" => $response["street_1"],
            "street_2" => $response["street_2"],
            "city" => $response["city"],
            "state" => $response["state"],
            "zip" => $response["zip"],
            "courtesy_withholding" => $response["courtesy_withholding"],
            "is_work_address" => 0,
            "country" => $response["country"],
            "created_at" => getSystemDate(),
            "updated_at" => getSystemDate(),
        ];
        //
        $this
            ->db
            ->insert(
                "gusto_companies_employees_work_addresses",
                $ins
            );
        ///
        if ($this->db->insert_id()) {
            //
            $this
                ->db
                ->where(
                    "employee_sid",
                    $this->gustoEmployee["employee_sid"]
                )
                ->update(
                    "gusto_companies_employees",
                    [
                        "gusto_home_address_uuid" => $response["uuid"],
                        "gusto_home_address_version" => $response["version"],
                        "gusto_home_address_effective_date" => $response["effective_date"],
                        "gusto_home_address_courtesy_withholding" => $response["courtesy_withholding"],
                    ]
                );
            //
            $this->updateEmployeeChecklist("home_address");
        }
    }

    /**
     * sync work address
     * Gusto to Store
     */
    private function gustoToStoreHA()
    {
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "home_addresses",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        foreach ($response as $v0) {
            // check if already exists
            if (
                !$this
                    ->db
                    ->where(
                        "employee_sid",
                        $this->gustoEmployee["employee_sid"]
                    )
                    ->where("is_work_address", 0)
                    ->where("gusto_uuid", $v0["uuid"])
                    ->count_all_results(
                        "gusto_companies_employees_work_addresses"
                    )
            ) {
                //
                $ins = [
                    "employee_sid" => $this->gustoEmployee["employee_sid"],
                    "gusto_uuid" => $v0["uuid"],
                    "gusto_version" => $v0["version"],
                    "effective_date" => $v0["effective_date"],
                    "active" => $v0["active"],
                    "courtesy_withholding" => $v0["courtesy_withholding"],
                    "street_1" => $v0["street_1"],
                    "street_2" => $v0["street_2"],
                    "city" => $v0["city"],
                    "state" => $v0["state"],
                    "zip" => $v0["zip"],
                    "country" => $v0["country"],
                    "is_work_address" => 0,
                    "created_at" => getSystemDate(),
                    "updated_at" => getSystemDate(),
                ];
                //
                $this
                    ->db
                    ->insert(
                        "gusto_companies_employees_work_addresses",
                        $ins
                    );
            } else {
                //
                $ins = [
                    "gusto_version" => $v0["version"],
                    "effective_date" => $v0["effective_date"],
                    "active" => $v0["active"],
                    "courtesy_withholding" => $v0["courtesy_withholding"],
                    "street_1" => $v0["street_1"],
                    "street_2" => $v0["street_2"],
                    "city" => $v0["city"],
                    "state" => $v0["state"],
                    "zip" => $v0["zip"],
                    "country" => $v0["country"],
                    "is_work_address" => 0,
                    "updated_at" => getSystemDate(),
                ];
                //
                $this
                    ->db
                    ->where(
                        "employee_sid",
                        $this->gustoEmployee["employee_sid"]
                    )
                    ->where("gusto_uuid", $v0["uuid"])
                    ->where("is_work_address", 0)
                    ->update(
                        "gusto_companies_employees_work_addresses",
                        $ins
                    );
            }

            //
            if ($v0["active"]) {
                //
                $this
                    ->db
                    ->where(
                        "employee_sid",
                        $this->gustoEmployee["employee_sid"]
                    )
                    ->update(
                        "gusto_companies_employees",
                        [
                            "gusto_home_address_uuid" => $v0["uuid"],
                            "gusto_home_address_version" => $v0["version"],
                            "gusto_home_address_effective_date" =>
                            $v0["effective_date"],
                            "gusto_home_address_courtesy_withholding" =>
                            $v0["courtesy_withholding"],
                        ]
                    );
            }
        }
    }

    // ---------------------------------------------------
    // sync jobs and compensations
    // ---------------------------------------------------
    /**
     * sync jobs and compensations
     */
    private function syncJobAndCompensations()
    {
        //
        $this->gustoToStoreJobsAndCompensations();
        // check if not linked
        if (
            $this
            ->db
            ->where("employee_sid", $this->gustoEmployee["employee_sid"])
            ->count_all_results("gusto_employees_jobs")
        ) {
            $this->storeToGustoJobs();
            $this->gustoToStoreJobsAndCompensations();
        }
    }

    /**
     * sync work address
     * store to Gusto
     */
    private function storeToGustoJobs()
    {
        // get company work address
        $record = $this
            ->getEmployeeJobWithCompensation(
                $this->gustoEmployee["employee_sid"]
            );
        //
        if ($record["errors"]) {
            return false;
        }
        // set request
        $request = [
            "title" => $record["title"],
            "hire_date" => $record["hire_date"],
        ];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "jobs",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        $ins = [
            "employee_sid" => $this->gustoEmployee["employee_sid"],
            "gusto_uuid" => $response["uuid"],
            "gusto_version" => $response["version"],
            "current_compensation_uuid" =>
            $response["current_compensation_uuid"],
            "payment_unit" => $response["payment_unit"],
            "is_primary" => $response["primary"],
            "two_percent_shareholder" => $response["two_percent_shareholder"],
            "state_wc_covered" => $response["state_wc_covered"],
            "state_wc_class_code" => $response["state_wc_class_code"],
            "hire_date" => $response["hire_date"],
            "title" => $response["title"],
            "rate" => $response["rate"],
            "created_at" => getSystemDate(),
            "updated_at" => getSystemDate(),
        ];
        //
        $this
            ->db
            ->insert(
                "gusto_employees_jobs",
                $ins
            );
        ///
        $jobId = $this->db->insert_id();
        if ($jobId) {
            //
            $this->updateEmployeeChecklist("compensation_details");
        }

        //
        $this->gustoToStoreCompensations(
            $jobId,
            $response["compensations"]
        );
        //
        $this->storeToGustoCompensations(
            $jobId,
            $response["uuid"]
        );
    }

    /**
     * sync work address
     * Gusto to Store
     */
    private function gustoToStoreJobsAndCompensations()
    {
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "jobs",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        foreach ($response as $v0) {
            // check if job exists
            if (
                !$this
                    ->db
                    ->where(
                        "employee_sid",
                        $this->gustoEmployee["employee_sid"]
                    )
                    ->where("gusto_uuid", $v0["uuid"])
                    ->count_all_results("gusto_employees_jobs")
            ) {
                // insert
                $ins = [
                    "employee_sid" => $this->gustoEmployee["employee_sid"],
                    "gusto_uuid" => $v0["uuid"],
                    "gusto_version" => $v0["version"],
                    "current_compensation_uuid" =>
                    $v0["current_compensation_uuid"],
                    "payment_unit" => $v0["payment_unit"],
                    "is_primary" => $v0["primary"],
                    "two_percent_shareholder" => $v0["two_percent_shareholder"],
                    "state_wc_covered" => $v0["state_wc_covered"],
                    "state_wc_class_code" => $v0["state_wc_class_code"],
                    "hire_date" => $v0["hire_date"],
                    "title" => $v0["title"],
                    "rate" => $v0["rate"],
                    "created_at" => getSystemDate(),
                    "updated_at" => getSystemDate(),
                ];
                //
                $this
                    ->db
                    ->insert(
                        "gusto_employees_jobs",
                        $ins
                    );
                //
                $jobId = $this->db->insert_id();
            } else {
                // update
                $row = $this
                    ->db
                    ->select("sid")
                    ->where(
                        "employee_sid",
                        $this->gustoEmployee["employee_sid"]
                    )
                    ->limit(1)
                    ->where("gusto_uuid", $v0["uuid"])
                    ->get("gusto_employees_jobs")
                    ->row_array();
                //
                $jobId = $row["sid"];
                //
                $this
                    ->db
                    ->where("sid", $jobId)
                    ->update(
                        "gusto_employees_jobs",
                        [
                            "gusto_uuid" => $v0["uuid"],
                            "gusto_version" => $v0["version"],
                            "current_compensation_uuid" =>
                            $v0["current_compensation_uuid"],
                            "payment_unit" => $v0["payment_unit"],
                            "is_primary" => $v0["primary"],
                            "two_percent_shareholder" => $v0["two_percent_shareholder"],
                            "state_wc_covered" => $v0["state_wc_covered"],
                            "state_wc_class_code" => $v0["state_wc_class_code"],
                            "hire_date" => $v0["hire_date"],
                            "title" => $v0["title"],
                            "rate" => $v0["rate"],
                            "updated_at" => getSystemDate(),
                        ]
                    );
            }

            //
            $this->gustoToStoreCompensations(
                $jobId,
                $v0["compensations"]
            );
        }
        //
        return true;
    }

    /**
     * add and update job compensations
     *
     * @param int $jobId
     * @param array $compensations
     */
    private function gustoToStoreCompensations(
        int $jobId,
        array $compensations
    ) {
        //
        if (!$compensations) {
            return false;
        }
        //
        foreach ($compensations as $v0) {
            //
            $dataArray = [];
            $dataArray["gusto_version"] = $v0["version"];
            $dataArray["payment_unit"] = $v0["payment_unit"];
            $dataArray["flsa_status"] = $v0["flsa_status"];
            $dataArray["adjust_for_minimum_wage"] =
                $v0["adjust_for_minimum_wage"];
            $dataArray["minimum_wages"] = json_encode($v0["minimum_wages"]);
            $dataArray["effective_date"] = $v0["effective_date"];
            $dataArray["rate"] = $v0["rate"];
            $dataArray["updated_at"] = getSystemDate();

            if (
                !$this
                    ->db
                    ->where("gusto_employees_jobs_sid", $jobId)
                    ->where("gusto_uuid", $v0["uuid"])
                    ->count_all_results("gusto_employees_jobs_compensations")
            ) {
                // insert
                $dataArray["gusto_employees_jobs_sid"] = $jobId;
                $dataArray["gusto_uuid"] = $v0["uuid"];
                $dataArray["created_at"] = $dataArray["updated_at"];
                //
                $this
                    ->db
                    ->insert(
                        "gusto_employees_jobs_compensations",
                        $dataArray
                    );
            } else {
                // update
                $this
                    ->db
                    ->where("gusto_employees_jobs_sid", $jobId)
                    ->where("gusto_uuid", $v0["uuid"])
                    ->update(
                        "gusto_employees_jobs_compensations",
                        $dataArray
                    );
            }
        }
        //
        return true;
    }

    /**
     * store to gusto compensation
     *
     * @param int $jobId
     * @param string $gustoJobId
     */
    private function storeToGustoCompensations(
        int $jobId,
        string $gustoJobId
    ) {
        //
        $record = $this
            ->getEmployeeCompensation();
        //
        if ($record["errors"]) {
            return false;
        }
        //
        $request = [
            "rate" => $record["rate"],
            "payment_unit" => $record["payment_unit"],
            "flsa_status" => $record["flsa_status"],
            "adjust_for_minimum_wage" => $record["adjust_for_minimum_wage"],
            "minimum_wages" => $record["minimum_wages"],
        ];

        //
        $this->gustoCompany["other_uuid"] = $gustoJobId;
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "compensations",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        $dataArray = [];
        $dataArray["gusto_employees_jobs_sid"] = $jobId;
        $dataArray["gusto_uuid"] = $response["uuid"];
        $dataArray["gusto_version"] = $response["version"];
        $dataArray["payment_unit"] = $response["payment_unit"];
        $dataArray["flsa_status"] = $response["flsa_status"];
        $dataArray["adjust_for_minimum_wage"] =
            $response["adjust_for_minimum_wage"];
        $dataArray["minimum_wages"] = json_encode($response["minimum_wages"]);
        $dataArray["effective_date"] = $response["effective_date"];
        $dataArray["rate"] = $response["rate"];
        $dataArray["created_at"] =
            $dataArray["updated_at"] =
            getSystemDate();
        //
        $this
            ->db
            ->insert(
                "gusto_employees_jobs_compensations",
                $dataArray
            );

        return true;
    }

    // ---------------------------------------------------
    // sync employments
    // ---------------------------------------------------
    /**
     * sync employments
     */
    private function syncEmployments()
    {
        // check if not linked
        if (
            $this
            ->db
            ->where("employee_sid", $this->gustoEmployee["employee_sid"])
            ->where("payroll_version is null", null)
            ->where("gusto_uuid is null", null)
            ->where_in("employee_status", [1, 8])
            ->count_all_results("terminated_employees")
        ) {
            $this->storeToGustoEmployments();
        }
    }

    /**
     * sync employments
     * store to Gusto
     */
    private function storeToGustoEmployments()
    {
        // get the employments
        $records = $this->getEmployeeEmployments();
        //
        if (!$records) {
            return false;
        }
        // get company work address
        $record = $this
            ->db
            ->select([
                "gusto_uuid"
            ])
            ->where(
                "company_sid",
                $this->gustoCompany["company_sid"]
            )
            ->limit(1)
            ->get("gusto_companies_locations")
            ->row_array();

        foreach ($records as $v0) {
            if ($v0["type"] === 'terminated') {
                $this->terminateEmployeeOnGusto(
                    $v0["date"],
                    $v0["id"]
                );
            } else {
                $this->rehireEmployeeOnGusto(
                    $record["gusto_uuid"],
                    $v0["date"],
                    $v0["id"]
                );
            }
        }
        //
        return true;
    }

    /**
     * terminate employee on Gusto
     *
     * @param string $effectiveDate
     * @param int $id
     */
    private function terminateEmployeeOnGusto(
        string $effectiveDate,
        int $id
    ) {
        // make the request
        $request = [
            "effective_date" => $effectiveDate,
            "run_termination_payroll" => 1
        ];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "terminations",
                $this->gustoCompany,
                $request,
                "POST"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors || !$response) {
            return false;
        }

        //
        $upd = [
            "gusto_uuid" => $response["uuid"],
            "payroll_version" => $response["version"],
            "payroll_object" => json_encode($response),
        ];
        //
        $this
            ->db
            ->where("sid", $id)
            ->update(
                "terminated_employees",
                $upd
            );
    }

    /**
     * rehire employee on Gusto
     *
     * @param string $gustoLocationId
     * @param string $effectiveDate
     * @param int $id
     */
    private function rehireEmployeeOnGusto(
        string $gustoLocationId,
        string $effectiveDate,
        int $id
    ) {
        // make the request
        $request = [
            "effective_date" => $effectiveDate,
            "file_new_hire_report" => true,
            "work_location_uuid" => $gustoLocationId,
            "run_termination_payroll" => 1
        ];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "rehire",
                $this->gustoCompany,
                $request,
                "POST"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors || !$response) {
            return false;
        }
        //
        $upd = [
            "gusto_uuid" => $response["uuid"],
            "payroll_version" => $response["version"],
            "payroll_object" => json_encode($response),
        ];
        //
        $this
            ->db
            ->where("sid", $id)
            ->update(
                "terminated_employees",
                $upd
            );
    }

    // ---------------------------------------------------
    // sync federal tax
    // ---------------------------------------------------
    /**
     * sync federal tax
     */
    private function syncFederalTax()
    {
        //
        $this->gustoToStoreFederalTax();
        if (
            $this
            ->db
            ->where([
                "user_type" => "employee",
                "employer_sid" => $this->gustoEmployee["employee_sid"],
                "status" => 1,
                "user_consent" => 1
            ])
            ->count_all_results("form_w4_original")
        ) {
            $this->storeToGustoFederalTax();
        }
    }

    /**
     * sync federal tax
     * Gusto to Store
     */
    private function gustoToStoreFederalTax()
    {
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "federal_taxes",
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
            return false;
        }
        if (!$response) {
            return false;
        }

        $ins = [
            "gusto_version" => $response["version"],
            "filing_status" => $response["filing_status"],
            "extra_withholding" => $response["extra_withholding"],
            "two_jobs" => $response["two_jobs"],
            "dependents_amount" => $response["dependents_amount"],
            "other_income" => $response["other_income"],
            "deductions" => $response["deductions"],
            "w4_data_type" => $response["w4_data_type"],
            "updated_at" => getSystemDate(),
        ];

        // check if already exists
        if (
            !$this
                ->db
                ->where(
                    "employee_sid",
                    $this->gustoEmployee["employee_sid"]
                )
                ->count_all_results(
                    "gusto_employees_federal_tax"
                )
        ) {
            $ins["created_at"] = $ins["updated_at"];
            $ins["employee_sid"] = $this->gustoEmployee["employee_sid"];
            //
            $this
                ->db
                ->insert(
                    "gusto_employees_federal_tax",
                    $ins
                );
        } else {
            //
            $this
                ->db
                ->where(
                    "employee_sid",
                    $this->gustoEmployee["employee_sid"]
                )
                ->update(
                    "gusto_employees_federal_tax",
                    $ins
                );
        }

        return true;
    }

    /**
     * sync federal tax
     * Gusto to Store
     */
    private function storeToGustoFederalTax()
    {
        //
        $gusto = $this
            ->db
            ->select([
                "gusto_version"
            ])
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->limit(1)
            ->get("gusto_employees_federal_tax")
            ->row_array();
        //
        if (!$gusto) {
            return false;
        }
        //
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
            ->limit(1)
            ->where([
                "user_type" => "employee",
                "employer_sid" => $this->gustoEmployee["employee_sid"],
                "status" => 1,
                "user_consent" => 1
            ])
            ->get("form_w4_original")
            ->row_array();
        //
        $record['dependents_children'] = (int)$record['dependents_children'];
        $record['other_dependents'] = (int)$record['other_dependents'];
        //
        $request = [];
        //
        $request['filing_status'] = $record['marriage_status'] == 'jointly'
            ? "Married"
            : "Single";
        if ($record['marriage_status'] == 'head') {
            $request['filing_status'] = "Head of Household";
        } elseif ($record['marriage_status'] == 'separately') {
            $request['filing_status'] = "Single";
        }
        $request['two_jobs'] = $record['mjsw_status'] === "similar_pay" ? "yes" : "no";
        //
        $request['dependents_amount'] = $record['dependents_children'] + $record['other_dependents'];
        $request['extra_withholding'] = (int) $record['additional_tax'];
        $request['other_income'] = (int) $record['other_income'];
        $request['deductions'] = (int) $record['other_deductions'];
        $request['w4_data_type'] = "rev_2020_w4";
        $request['version'] = $gusto["gusto_version"];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];

        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "federal_taxes",
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
            return false;
        }
        if (!$response) {
            return false;
        }

        $ins = [
            "gusto_version" => $response["version"],
            "filing_status" => $response["filing_status"],
            "two_jobs" => $response["two_jobs"],
            "dependents_amount" => $response["dependents_amount"],
            "other_income" => $response["other_income"],
            "extra_withholding" => $response["extra_withholding"],
            "deductions" => $response["deductions"],
            "w4_data_type" => $response["w4_data_type"],
            "updated_at" => getSystemDate(),
        ];
        //
        $this
            ->db
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->update(
                "gusto_employees_federal_tax",
                $ins
            );

        return true;
    }

    // ---------------------------------------------------
    // sync state tax
    // ---------------------------------------------------
    /**
     * sync state tax
     */
    private function syncStateTax()
    {
        //
        $this->gustoToStoreStateTax();
        if (
            $this
            ->db
            ->where([
                "user_type" => "employee",
                "user_sid" => $this->gustoEmployee["employee_sid"],
                "status" => 1,
                "user_consent" => 1
            ])
            ->count_all_results("portal_state_form")
        ) {
            $this->storeToGustoStateTax();
            $this->gustoToStoreStateTax();
        }
    }

    /**
     * sync state tax
     * Gusto to Store
     */
    private function gustoToStoreStateTax()
    {
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "state_taxes",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        foreach ($response as $v0) {
            //
            $ins = [];
            $ins["state_code"] = strtoupper($v0["state"]);
            $ins["file_new_hire_report"] = $v0["file_new_hire_report"];
            $ins["is_work_state"] = $v0["is_work_state"];
            $ins["questions_json"] = json_encode($v0["questions"]);
            $ins["updated_at"] = getSystemDate();
            //
            if (
                !$this
                    ->db
                    ->where([
                        "employee_sid" =>
                        $this->gustoEmployee["employee_sid"],
                        "state_code" => strtoupper($v0["state"])
                    ])
                    ->count_all_results("gusto_employees_state_tax")
            ) {
                //
                $ins["employee_sid"] = $this->gustoEmployee["employee_sid"];
                $ins["created_at"] = $ins["updated_at"];
                // insert
                $this
                    ->db
                    ->insert(
                        "gusto_employees_state_tax",
                        $ins
                    );
            } else {
                $this
                    ->db
                    ->where([
                        "employee_sid" =>
                        $this->gustoEmployee["employee_sid"],
                        "state_code" => strtoupper($v0["state"])
                    ])
                    ->update(
                        "gusto_employees_state_tax",
                        $ins
                    );
            }
        }

        return true;
    }

    /**
     * sync state tax
     * Gusto to Store
     */
    private function storeToGustoStateTax()
    {
        $records = $this->getGustoStateTaxRecords();
        //
        if (!$records) {
            return false;
        }
        //
        foreach ($records as $v0) {
            //
            $gusto = $this->getGustoStateTaxRecord(
                $v0["state_code"]
            );
            //
            if (!$gusto) {
                continue;
            }
            //
            $questionsArray = json_decode(
                $gusto["questions_json"],
                true
            );
            // set tmp array
            $tmp = [];
            // convert to list
            foreach ($questionsArray as $question) {
                $tmp[$question['key']] = $question;
            }
            //
            $questionsArray = $tmp;
            //
            $record = json_decode($v0["fields_json"], true);
            // prepare data
            $data = [];
            $data["filing_status"] = "E";
            if ($record["marital_status"] == 1) {
                $data["filing_status"] = "S";
            } elseif ($record["marital_status"] == 2) {
                $data["filing_status"] = "M";
            } elseif ($record["marital_status"] == 3) {
                $data["filing_status"] = "MH";
            }
            $data["withholding_allowance"] = $record["section_1_allowances"]
                ? $record["section_1_allowances"]
                : 0;
            $data["additional_withholding"] = $record["section_1_additional_withholding"]
                ? $record["section_1_additional_withholding"]
                : 0.0;
            $data["file_new_hire_report"] = "yes";
            // set default error array
            $errorsArray = [];
            // add the answers to questions
            foreach ($data as $index => $value) {
                //
                if ($questionsArray[$index]['input_question_format']['type'] !== 'Select' && $value < 0) {
                    $errorsArray[] = '"' . ($questionsArray[$index]['label']) . '" can not be less than 0.';
                }
                //
                if ($questionsArray[$index]['input_question_format']['type'] !== 'Select' && !$value) {
                    $value = 0;
                } elseif ($questionsArray[$index]['input_question_format']['type'] === 'Select') {
                    $value = $value == 'yes' ? "true" : $value;
                    $value = $value == 'no' ? "false" : $value;
                }
                //
                if ($questionsArray[$index]['answers'][0]['value']) {
                    $questionsArray[$index]['answers'][0]['value'] = $value;
                } else {
                    $questionsArray[$index]['answers'] = [['value' => $value, 'valid_from' => '2010-01-01']];
                }
            }
            // when an error occurred
            if ($errorsArray) {
                continue;
            }
            //
            $passData = [
                'state' => $v0['state_code'],
                'questions' => array_values(
                    $questionsArray
                )
            ];

            $this->pushStateTaxToGusto(
                $passData
            );
        }
        return true;
    }

    /**
     * push the state tax to Gusto
     * Gusto to Store
     */
    private function pushStateTaxToGusto(
        array $stateData
    ) {
        // set request
        $request = [
            "states" => [
                $stateData
            ]
        ];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "state_taxes",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        return true;
    }

    /**
     * get state tax records
     */
    private function getGustoStateTaxRecords()
    {
        return $this
            ->db
            ->select([
                "states.state_code",
                "portal_state_form.fields_json"
            ])
            ->join(
                "state_forms",
                "state_forms.sid = portal_state_form.state_form_sid",
                "inner"
            )
            ->join(
                "states",
                "states.sid = state_forms.state_sid",
                "inner"
            )
            ->where([
                "portal_state_form.user_type" => "employee",
                "portal_state_form.user_sid" =>
                $this->gustoEmployee["employee_sid"],
                "portal_state_form.status" => 1,
                "portal_state_form.user_consent" => 1
            ])
            ->get("portal_state_form")
            ->result_array();
    }

    /*
     * get state tax record for gusto
     */
    private function getGustoStateTaxRecord(
        $stateCode
    ) {
        return
            $this
            ->db
            ->select([
                "questions_json"
            ])
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->where("state_code", strtoupper($stateCode))
            ->limit(1)
            ->get("gusto_employees_state_tax")
            ->row_array();
    }


    // ---------------------------------------------------
    // sync payment method
    // ---------------------------------------------------
    /**
     * sync payment method
     */
    private function syncPaymentMethod()
    {
        //
        $this->gustoToStorePaymentMethod();
        // check if dd is present
        if (
            $this
            ->db
            ->where([
                "users_type" => "employee",
                "users_sid" => $this->gustoEmployee["employee_sid"],
                "is_consent" => 1,
                "gusto_uuid is null" => null,
            ])
            ->count_all_results("bank_account_details")
        ) {
            $this->changePaymentMethodToDirectDeposit();
            $this->storeToGustoPaymentMethod();
        }
    }

    /**
     * sync payment method
     * Gusto to Store
     */
    private function gustoToStorePaymentMethod()
    {
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "payment_method",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        $ins = [];
        $ins["gusto_version"] = $response["version"];
        $ins["type"] = $response["type"];
        $ins["split_by"] = $response["split_by"];
        $ins["splits"] = json_encode($response["splits"] ?? []);
        $ins["updated_at"] = getSystemDate();
        //
        if (
            !$this
                ->db
                ->where([
                    "employee_sid" =>
                    $this->gustoEmployee["employee_sid"]
                ])
                ->count_all_results("gusto_employees_payment_method")
        ) {
            //
            $ins["employee_sid"] = $this->gustoEmployee["employee_sid"];
            $ins["created_at"] = $ins["updated_at"];
            // insert
            $this
                ->db
                ->insert(
                    "gusto_employees_payment_method",
                    $ins
                );
        } else {
            $this
                ->db
                ->where([
                    "employee_sid" =>
                    $this->gustoEmployee["employee_sid"]
                ])
                ->update(
                    "gusto_employees_payment_method",
                    $ins
                );
        }

        // when the type is check
        if (strtolower($response["type"]) === 'check') {
            $this
                ->db
                ->where([
                    "users_type" => "employee",
                    "users_sid" => $this->gustoEmployee["employee_sid"],
                ])
                ->update(
                    "bank_account_details",
                    [
                        "gusto_uuid" => null
                    ]
                );
        }

        return true;
    }

    /**
     * set payment method to direct deposit
     * Gusto to Store
     */
    private function changePaymentMethodToDirectDeposit()
    {
        // get the bank accounts
        $records =
            $this
            ->db
            ->select([
                "sid",
                "account_title",
                "routing_transaction_number",
                "account_number",
                "financial_institution_name",
                "account_type",
                "deposit_type",
                "account_percentage",
                "gusto_uuid",
            ])
            ->where([
                "users_type" => "employee",
                "users_sid" => $this->gustoEmployee["employee_sid"],
                "gusto_uuid is null" => null,
                "is_consent" => 1,
            ])
            ->get("bank_account_details")
            ->result_array();
        //
        if (!$records) {
            return false;
        }
        //
        foreach ($records as $v0) {
            //
            $this->storeToGustoBankAccount($v0);
        }

        return true;
    }

    /**
     * push a single bank account to Gusto
     */
    private function storeToGustoBankAccount(
        array $bankAccount
    ) {
        // set request
        $request = [
            "name" => $bankAccount["account_title"],
            "routing_number" => $bankAccount["routing_transaction_number"],
            "account_number" => $bankAccount["account_number"],
            "account_type" => ucwords($bankAccount["account_type"]),
        ];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "employee_bank_accounts",
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
            return false;
        }
        if (!$response) {
            return false;
        }

        //
        $this
            ->db
            ->where("sid", $bankAccount["sid"])
            ->update(
                "bank_account_details",
                [
                    "gusto_uuid" => $response["uuid"]
                ]
            );

        return true;
    }

    /**
     * set payment method to direct deposit
     * Gusto to Store
     */
    private function storeToGustoPaymentMethod()
    {
        // get the version
        $gustoPaymentMethod =
            $this
            ->db
            ->select([
                "gusto_version"
            ])
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->limit(1)
            ->get("gusto_employees_payment_method")
            ->row_array();
        //
        if (!$gustoPaymentMethod) {
            return false;
        }
        // get the bank accounts
        $records =
            $this
            ->db
            ->select([
                "deposit_type",
                "account_percentage",
                "account_title",
                "gusto_uuid",
            ])
            ->where([
                "users_type" => "employee",
                "users_sid" => $this->gustoEmployee["employee_sid"],
                "gusto_uuid is not null" => null,
                "is_consent" => 1,
            ])
            ->order_by("sid", "ASC")
            ->limit(2)
            ->get("bank_account_details")
            ->result_array();
        //
        if (!$records) {
            return false;
        }

        //
        $accountToSplits = $this
            ->lb_gusto
            ->setAndGetPaymentMethodSplits(
                $records
            );

        // set request
        $request = [];
        $request["version"] = $gustoPaymentMethod["gusto_version"];
        $request["type"] = "Direct Deposit";
        $request["split_by"] = $accountToSplits["split_by"];
        $request["splits"] = $accountToSplits["splits"];

        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "payment_method",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        $this
            ->db
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->update(
                "gusto_employees_payment_method",
                [
                    "gusto_version" => $response["version"],
                    "type" => $response["type"],
                    "split_by" => $response["split_by"],
                    "splits" => json_encode($response["splits"]),
                    "updated_at" => getSystemDate(),
                ]
            );

        return true;
    }


    // ---------------------------------------------------
    // sync forms
    // ---------------------------------------------------
    /**
     * sync forms
     */
    private function syncForms()
    {
        //
        $this->gustoToStoreForms();
    }

    /**
     * sync forms
     * Gusto to Store
     */
    private function gustoToStoreForms()
    {
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "employee_forms",
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
            return false;
        }
        if (!$response) {
            return false;
        }
        //
        foreach ($response as $v0) {
            //
            $ins = [];
            $ins['form_name'] = $v0['name'];
            $ins['form_title'] = $v0['title'];
            $ins['description'] = $v0['description'];
            $ins['requires_signing'] = $v0['requires_signing'];
            $ins['draft'] = $v0['draft'];
            $ins['updated_at'] = getSystemDate();

            // we need to check the current status of w4
            if ($v0['name'] == 'US_W-4') {
                $document = $this->getW4AssignStatus();
                $ins['status'] = $document['status'];
                $ins['document_sid'] = $document['documentId'];
            } elseif ($v0['name'] == 'employee_direct_deposit') {
                $document = $this->getDirectDepositAssignStatus();
                $ins['status'] = $document['status'];
                $ins['document_sid'] = $document['documentId'];
            }

            //
            $this->gustoCompany["other_uuid"]
                = $this->gustoEmployee["gusto_uuid"];
            //
            $this->gustoCompany["other_uuid_2"]
                = $v0["uuid"];
            //
            $gustoFormPDF =
                $this
                ->lb_gusto
                ->gustoCall(
                    "employee_form_pdf",
                    $this->gustoCompany,
                    [],
                    "GET"
                );
            //
            if ($gustoFormPDF["document_url"]) {
                // copy the employee form from Gusto
                // to store and make it private
                $fileObject = copyFileFromUrlToS3(
                    $gustoFormPDF["document_url"],
                    $v0["name"],
                    (isDevServer() ? "local_" : "") .
                        $this->gustoCompany["company_sid"] .
                        "_" .
                        $this->gustoEmployee["employee_sid"] .
                        "_",
                    "private"
                );
                // set the unsigned file
                $ins['s3_form'] = $fileObject["s3_file_name"];
            }
            //
            if (
                $this
                ->db
                ->where([
                    'employee_sid' => $this->gustoEmployee["employee_sid"],
                    'gusto_uuid' => $v0['uuid']
                ])
                ->count_all_results(
                    'gusto_employees_forms'
                )
            ) {
                $this
                    ->db
                    ->where([
                        'employee_sid' => $this->gustoEmployee["employee_sid"],
                        'gusto_uuid' => $v0['uuid']
                    ])
                    ->update('gusto_employees_forms', $ins);
            } else {
                //
                $ins['company_sid'] = $this->gustoCompany['company_sid'];
                $ins['employee_sid'] = $this->gustoEmployee["employee_sid"];
                $ins['gusto_uuid'] = $v0['uuid'];
                $ins['created_at'] = $ins["updated_at"];
                //
                $this->db
                    ->insert('gusto_employees_forms', $ins);
            }
        }

        return true;
    }

    /**
     * update employee's payment method
     */
    private function getW4AssignStatus(): array
    {
        //
        $record = $this->db
            ->select('status, sid')
            ->where([
                'employer_sid' => $this->gustoEmployee["employee_sid"],
                'user_type' => 'employee'
            ])
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
     */
    private function getDirectDepositAssignStatus(): array
    {
        //
        $record = $this->db
            ->select('status, sid')
            ->where([
                'user_sid' => $this->gustoEmployee["employee_sid"],
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
     * Update Compensation
     */
    private function dataStoreToGustoEmployeeCompensation()
    {
        //
        if (!$this->gustoIdArray["compensation"]) {
            return ["errors" => [
                "Gusto UUID / Version is not found."
            ]];
        }
        // get the latest compensation
        $compensation = $this
            ->getEmployeeCompensation();
        //
        if ($compensation["errors"]) {
            return ["errors" => $compensation["errors"]];
        }
        //
        unset($compensation["errors"]);
        // check if changed
        if (!$this->needToUpdate(
            $compensation,
            $this->gustoIdArray["compensation"]["data"]
        )) {
            return ["errors" => ["Nothing has changed."]];
        }

        // set request
        $request = [
            "version" => $this->gustoIdArray["compensation"]["gusto_version"],
            "rate" => $compensation["rate"],
            "payment_unit" => $compensation["payment_unit"],
            "flsa_status" => $compensation["flsa_status"],
            "adjust_for_minimum_wage" => (bool)$compensation["adjust_for_minimum_wage"],
            "minimum_wages" => $compensation["minimum_wages"],
        ];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoIdArray["compensation"]["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "update_compensations",
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
            if (strpos($errors["errors"][0], GUSTO_OUT_OF_DATE_VERSION) !== false) {
                $this->gustoToStoreJobsAndCompensations();
                $this->dataStoreToGustoEmployeeCompensation();
            }
            return $errors;
        }
        if (!$response) {
            return ["errors" => "No response from Gusto."];
        }
        //
        $this->gustoToStoreJobsAndCompensations();
    }

    /**
     * Update Compensation
     */
    private function dataStoreToGustoEmployeeJob()
    {
        //
        if (!$this->gustoIdArray["job"]) {
            return ["errors" => [
                "Gusto UUID / Version is not found."
            ]];
        }
        // get the latest compensation
        $record = $this
            ->getEmployeeJobWithCompensation();
        //
        if ($record["errors"]) {
            return ["errors" => $record["errors"]];
        }
        //
        unset($record["errors"]);
        // check if changed
        if (!$this->needToUpdate(
            $record,
            $this->gustoIdArray["job"]["data"]
        )) {
            return ["errors" => ["Nothing has changed."]];
        }
        // set request
        $request = [
            "version" => $this->gustoIdArray["job"]["gusto_version"],
            "title" => $record["title"],
            "hire_date" => $record["hire_date"],
        ];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoIdArray["job"]["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "update_job",
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
            if (strpos($errors["errors"][0], GUSTO_OUT_OF_DATE_VERSION) !== false) {
                $this->gustoToStoreJobsAndCompensations();
                $this->dataStoreToGustoEmployeeJob();
            }
            return $errors;
        }
        if (!$response) {
            return ["errors" => "No response from Gusto."];
        }
        //
        $this->gustoToStoreJobsAndCompensations();
    }

    /**
     * Update Home address
     */
    private function dataStoreToGustoEmployeeHomeAddress()
    {
        //
        if (!$this->gustoIdArray["employee_home_address"]) {
            return ["errors" => [
                "Gusto UUID / Version is not found."
            ]];
        }
        $record = $this->getEmployeeAddress(
            $this->gustoEmployee["employee_sid"]
        );
        //
        if ($record["errors"]) {
            return ["errors" => $record["errors"]];
        }
        //
        unset($record["errors"]);
        // check if changed
        if (!$this->needToUpdate(
            $record,
            $this->gustoIdArray["employee_home_address"]["data"]
        )) {
            return ["errors" => ["Nothing has changed."]];
        }
        // set request
        $request = [
            "version" => $this->gustoIdArray["employee_home_address"]["gusto_version"],
            "street_1" => $record["street_1"],
            "street_2" => $record["street_2"],
            "city" => $record["city"],
            "state" => $record["state_code"],
            "zip" => $record["zip"],
        ];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoIdArray["employee_home_address"]["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "update_employee_home_address",
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
            if (strpos($errors["errors"][0], GUSTO_OUT_OF_DATE_VERSION) !== false) {
                $this->gustoToStoreHA();
                $this->dataStoreToGustoEmployeeHomeAddress();
            }
            return $errors;
        }
        if (!$response) {
            return ["errors" => "No response from Gusto."];
        }
        //
        $this->gustoToStoreHA();
    }


    /**
     * Update employee profile
     */
    private function dataStoreToGustoEmployeeProfile()
    {
        //
        if (!$this->gustoEmployee["gusto_uuid"]) {
            return ["errors" => [
                "Gusto UUID / Version is not found."
            ]];
        }
        $record = $this->getEmployeeProfile(
            $this->gustoEmployee["employee_sid"]
        );
        //
        if ($record["errors"]) {
            return ["errors" => $record["errors"]];
        }
        //
        unset($record["errors"]);
        // check if changed
        if (!$this->needToUpdate(
            $record,
            $this->employeeOldData
        )) {
            return ["errors" => ["Nothing has changed."]];
        }

        // set request
        $request = $record;
        $request["version"] = $this->gustoEmployee["gusto_version"];
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "update_employee_profile",
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
            if (strpos($errors["errors"][0], GUSTO_OUT_OF_DATE_VERSION) !== false) {
                $this->gustoToStoreEmployeeProfile();
                $this->dataStoreToGustoEmployeeProfile();
            }
            return $errors;
        }
        if (!$response) {
            return ["errors" => "No response from Gusto."];
        }
        //
        $this->gustoToStoreEmployeeProfile();
    }

    /**
     * loads the job and compensation
     * Gusto UUIDs and versions
     *
     * @return bool
     */
    private function loadGustoJobAndCompensationIds(): bool
    {
        // get the job and compensation ids
        $record = $this
            ->db
            ->select([
                "gusto_uuid",
                "gusto_version",
                "hire_date",
                "title",
                "current_compensation_uuid",
            ])
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->limit(1)
            ->get("gusto_employees_jobs")
            ->row_array();
        //
        if (!$record) {
            return false;
        }
        //
        $this->gustoIdArray["job"] = [
            "gusto_uuid" => $record["gusto_uuid"],
            "gusto_version" => $record["gusto_version"],
            "data" => [
                "title" => $record["title"],
                "hire_date" => $record["hire_date"],
            ]
        ];
        // get the compensation version
        $compensation = $this
            ->db
            ->select([
                "gusto_version",
                "rate",
                "payment_unit",
                "flsa_status",
                "effective_date",
                "adjust_for_minimum_wage",
                "minimum_wages",
            ])
            ->where(
                "gusto_uuid",
                $record["current_compensation_uuid"]
            )
            ->limit(1)
            ->get("gusto_employees_jobs_compensations")
            ->row_array();
        //
        if (!$record) {
            return false;
        }
        //
        $this->gustoIdArray["compensation"] = [
            "gusto_uuid" => $record["current_compensation_uuid"],
            "gusto_version" => $compensation["gusto_version"],
            "data" => [
                "rate" => $compensation["rate"],
                "payment_unit" => $compensation["payment_unit"],
                "flsa_status" => $compensation["flsa_status"],
                "effective_date" => $compensation["effective_date"],
                "adjust_for_minimum_wage" => $compensation["adjust_for_minimum_wage"],
                "minimum_wages" => json_decode($compensation["minimum_wages"], true),
            ]
        ];
        //
        return true;
    }

    /**
     * loads employee home address
     * Gusto UUIDs and versions
     *
     * @return bool
     */
    private function loadGustoEmployeeHomeAddressIds(): bool
    {
        // get the job and compensation ids
        $record = $this
            ->db
            ->select([
                "gusto_uuid",
                "gusto_version",
                "street_1",
                "street_2",
                "city",
                "state",
                "zip",
                "country",
            ])
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->where("active", 1)
            ->where("is_work_address", 0)
            ->limit(1)
            ->get("gusto_companies_employees_work_addresses")
            ->row_array();
        //
        if (!$record) {
            return false;
        }
        //
        $this->gustoIdArray["employee_home_address"] = [
            "gusto_uuid" => $record["gusto_uuid"],
            "gusto_version" => $record["gusto_version"],
            "data" => [
                "street_1" => $record["street_1"],
                "street_2" => $record["street_2"],
                "city" => $record["city"],
                "state" => $record["state"],
                "zip" => $record["zip"],
                "country" => $record["country"],
            ]
        ];
        //
        return true;
    }


    /**
     * sync work address
     * Gusto to Store
     */
    private function gustoToStoreEmployeeProfile()
    {
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "get_employee_profile",
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
            return false;
        }
        //
        if (!$response) {
            return false;
        }
        //
        $upd = [
            "gusto_version" => $response["version"],
            "updated_at" => getSystemDate(),
        ];

        if ($response["onboarding_status"] == "onboarding_completed") {
            $upd["is_onboarded"] =
                $upd["compensation_details"] =
                $upd["personal_details"] =
                $upd["work_address"] =
                $upd["home_address"] =
                $upd["federal_tax"] =
                $upd["state_tax"] = 1;
        }
        //
        $this
            ->db
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->update(
                "gusto_companies_employees",
                $upd
            );
    }

    /**
     * set the events
     */
    private function setDataToStoreEvents()
    {
        $this->dataToStoreEvents = [
            "job_and_compensation" =>
            "dataStoreToGustoEmployeeJobAndCompensationFlow",
            "home_address" =>
            "dataStoreToGustoEmployeeHomeAddressFlow",
            "profile" => "dataStoreToGustoEmployeeProfileFlow",
        ];
    }
}


function sortByDate($a, $b)
{
    return $a["date"] > $b["date"];
}
