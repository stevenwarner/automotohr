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
class Company_schedule_payroll_model extends Base_payroll_model
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
     * add company schedule
     *
     * @param array $request
     * @return array
     */
    public function addCompanySchedule(array $request)
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "company_pay_schedules",
                $this->gustoCompany,
                $request,
                "POST",
                true
            );
        // check for actual errors
        if ($response["errors"]) {
            return $response["errors"];
        }
        // set the insert array
        $request["gusto_uuid"] = $response["uuid"];
        $request["gusto_version"] = $response["version"];
        $request["company_sid"] = $this->gustoCompany["company_sid"];
        $request["deadline_to_run_payroll"] = formatDateToDB(
            $this->input->post("deadline_to_run_payroll", true),
            SITE_DATE
        );
        $request["active"] = 1;
        $request["updated_at"] = $request["created_at"] = getSystemDate();
        // add tp database
        $this
            ->db
            ->insert(
                "companies_pay_schedules",
                $request
            );
        // response
        return $this
            ->lb_gusto
            ->getSuccessResponse($response);
    }
}
