<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Base payroll model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Base_payroll_model extends CI_Model
{
    /**
     * holds the gusto company
     * @var array
     */
    protected $gustoCompany;

    /**
     * holds the gusto employee
     * @var array
     */
    protected $gustoEmployee;

    /**
     * holds the gusto extra uuids
     * @var array
     */
    protected $gustoIdArray;

    /**
     * holds the employee update events
     * @var array
     */
    protected $dataToStoreEvents;

    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
        //
        $this->gustoIdArray = [];
        //
        $this->setDataToStoreEvents();
    }

    /**
     * Get gusto company details for gusto
     *
     * @param string   $companyId
     * @param array $extra Optional
     * @param bool  $include Optional
     * @param string $whereColumn Optional
     * @return array
     */
    protected function getGustoLinkedCompanyDetails(
        string $companyId,
        array $extra = [],
        bool $include = true,
        $whereColumn = "company_sid"
    ): array {
        //
        $columns = $include ? array_merge([
            'gusto_uuid',
            'refresh_token',
            'access_token'
        ], $extra) : $extra;
        //
        return $this->gustoCompany =
            $this->db
            ->select($columns)
            ->where($whereColumn, $companyId)
            ->get('gusto_companies')
            ->row_array();
    }

    /**
     * loads the Gusto library
     *
     * @param int $companyId
     */
    protected function initialize(int $companyId)
    {
        // load library
        $this
            ->load
            ->library(
                "Lb_gusto",
                ["companyId" => $companyId],
                "lb_gusto"
            );
    }

    /**
     * check and set company onboard checklist
     *
     * @return void
     */
    protected function updateCompanyChecklist(
        string $column,
        bool $replace = false
    ) {
        // set the system date time
        $systemDateTime = getSystemDate();
        //
        if ($replace) {
            $this
                ->db
                ->where(
                    "company_sid",
                    $this->gustoCompany["company_sid"]
                )
                ->update(
                    "payrolls.gusto_company_checklist",
                    [
                        "updated_at" => $systemDateTime,
                        "{$column}" => 1
                    ]
                );
        } else {
            // insert the row
            $this
                ->db
                ->query("
                    UPDATE 
                        `payrolls`.`gusto_company_checklist`
                    SET
                        `{$column}` = {$column} + 1,
                        `updated_at` = '{$systemDateTime}'
                    WHERE
                        `company_sid` = " . ($this->gustoCompany["company_sid"]) . "");
        }
    }

    /**
     * check and set employee onboard checklist
     *
     * @return void
     */
    protected function updateEmployeeChecklist(
        string $column
    ) {
        $this
            ->db
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->update(
                "gusto_companies_employees",
                [
                    "updated_at" => getSystemDate(),
                    "{$column}" => 1
                ]
            );
    }

    /**
     * Get gusto employee details for gusto
     *
     * @param string   $employeeId
     * @param array $extra Optional
     * @param bool  $include Optional
     * @return array
     */
    protected function getGustoLinkedEmployeeDetails(
        string $employeeId,
        array $extra = [],
        bool $include = true,
        string $column = "employee_sid"
    ): array {
        //
        $columns = $include ? array_merge([
            'gusto_uuid',
            'gusto_version',
        ], $extra) : $extra;
        //
        return $this->gustoEmployee =
            $this->db
            ->select($columns)
            ->where($column, $employeeId)
            ->get('gusto_companies_employees')
            ->row_array();
    }

    /**
     * check if the data is changed or not
     * @param array $oldData
     * @param array $newArray
     * @return array
     */
    protected function needToUpdate(array $oldData, array $newData): array
    {
        return array_diff($oldData, $newData);
    }

    /**
     * set the events
     */
    protected function setDataToStoreEvents()
    {
        // set the empty
        $this->dataToStoreEvents = [];
        // Employee_payroll_model
        // set job and compensation event
        $this->dataToStoreEvents["job_and_compensation"] =
            "dataStoreToGustoEmployeeJobAndCompensationFlow";
        // set home address event
        $this->dataToStoreEvents["home_address"] =
            "dataStoreToGustoEmployeeHomeAddressFlow";
        // set profile event
        $this->dataToStoreEvents["profile"] =
            "dataStoreToGustoEmployeeProfileFlow";
        // set w4 form event
        $this->dataToStoreEvents["w4_form"] =
            "dataStoreToGustoEmployeeW4FormFlow";
    }

    /**
     * get all companies on payroll with
     * mode
     *
     * @param array $columns
     * @return array
     */
    public function getAllCompaniesOnGusto(
        array $columns = ["company_sid"]
    ): array {
        return $this
            ->db
            ->select($columns)
            ->get("gusto_companies")
            ->result_array();
    }

    /**
     * get payroll blockers
     *
     * @param int $companyId
     * @return array
     */
    public function getPayrollBlockers(
        int $companyId
    ): array {
        $record = $this
            ->db
            ->select([
                "blocker_json",
                "updated_at"
            ])
            ->where(
                "company_sid",
                $companyId
            )
            ->limit(1)
            ->get("payrolls.payroll_blockers")
            ->row_array();
        //
        if (!$record) {
            return [];
        }
        //
        $record["blocker_json"] = json_decode(
            $record["blocker_json"],
            true
        );
        return $record["blocker_json"] ? $record : [];
    }
}
