<?php

use Twilio\TwiML\Voice\Record;

defined('BASEPATH') || exit('No direct script access allowed');
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
     * set company details
     * 
     * @param int $companyId
     */
    public function setCompanyDetails(
        int $companyId
    ) {
        //
        $this
            ->getGustoLinkedCompanyDetails(
                $companyId,
                [
                    "company_sid",
                    "employee_ids"
                ]
            );
        //
        $this->initialize($companyId);
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
            "country_code" => $record["country_name"] || "USA",
            "errors" => $errors,
        ];
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
        // $this->syncWorkAddress();
        // sync home address
        // $this->syncHomeAddress();
        // sync job & compensations
        // $this->syncJobAndCompensations();
        // sync employments
        $this->syncEmployments();


        die("sadas");
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
            "middle_initial" => substr($record["middle_name"], 0, 1),
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
        if ($errors) {
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
        //
        foreach ($response as $v0) {
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
                "country" => $v0["country"],
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
        //
        $this->gustoToStoreEmployments();
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
     * sync employments
     * Gusto to Store
     */
    private function gustoToStoreEmployments()
    {
        $this->gustoToStoreTerminations();
    }

    /**
     * sync terminations
     * Gusto to Store
     */
    private function gustoToStoreTerminations()
    {
        //
        $this->gustoCompany["other_uuid"] =
            $this->gustoEmployee["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "terminations",
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
}


function sortByDate($a, $b)
{
    return $a["date"] > $b["date"];
}
