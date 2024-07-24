<?php defined('BASEPATH') || exit('No direct script access allowed');
loadUpModel("v1/Payroll/Base_payroll_model", "base_payroll_model");
/**
 * Regular payroll model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Regular_payroll_model extends Base_payroll_model
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
     * Sync un processed payrolls
     *
     * @return array
     */
    public function syncUnprocessedRegularPayrolls()
    {
        // make a call
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "unprocessed_payrolls",
                $this->gustoCompany,
                [
                    "processing_statuses=unprocessed",
                    "payroll_types=regular",
                    "include=totals",
                    "include=payroll_status_meta",
                    "start_date=" . getSystemDate('Y') . "-01-01",
                ],
                "GET",
                true
            );
        // check for actual errors
        if ($response["errors"]) {
            return $response["errors"];
        }
        // set company id and
        $companyId = $this->gustoCompany["company_sid"];
        // set company primary admin
        $employeeId = getCompanyAdminSid($companyId);
        // set today date
        $todayDate = getSystemDate("Y-m-d");
        // set date time
        $systemDateTime = getSystemDate();
        // iterate through it
        foreach ($response as $v0) {
            // set insert array
            $ins = [];
            $ins['check_date'] = $v0['check_date'];
            $ins['payroll_deadline'] = $v0['payroll_deadline'];
            $ins['processed'] = $v0['processed'];
            $ins['processed_date'] = $v0['processed_date'];
            $ins['calculated_at'] = $v0['calculated_at'];
            $ins['updated_at'] = $systemDateTime;
            $ins['last_changed_by'] = $employeeId;
            $ins['is_late_payroll'] = (int) (
                $v0["check_date"] > $todayDate
            );
            // check if already exists
            if (
                $this->db
                ->where('gusto_uuid', $v0['payroll_uuid'])
                ->count_all_results('payrolls.regular_payrolls')
            ) {
                // update it
                $this->db
                    ->where('gusto_uuid', $v0['payroll_uuid'])
                    ->update('payrolls.regular_payrolls', $ins);
            } else {
                //
                $ins['company_sid'] = $companyId;
                $ins['gusto_uuid'] = $v0['payroll_uuid'];
                $ins['start_date'] = $v0['pay_period']['start_date'];
                $ins['end_date'] = $v0['pay_period']['end_date'];
                $ins['gusto_pay_schedule_uuid'] = $v0['pay_period']['pay_schedule_uuid'];
                $ins['created_at'] = $ins['updated_at'];
                // insert it
                $this->db
                    ->insert('payrolls.regular_payrolls', $ins);
            }
        }
        // response
        return $this
            ->lb_gusto
            ->getSuccessResponse($response);
    }

    /**
     * get 
     */
}
