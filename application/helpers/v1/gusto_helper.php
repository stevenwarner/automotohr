<?php defined('BASEPATH') || exit('No direct script access allowed');

if (!function_exists('isCompanyLinkedWithGusto')) {
    /**
     * Check company already liked with Gusto
     *
     * @param int $companyId
     * @return bool
     */
    function isCompanyLinkedWithGusto(int $companyId): bool
    {
        //
        return (bool)get_instance()->db
            ->where([
                'company_sid' => $companyId
            ])
            ->count_all_results('gusto_companies');
    }
}

if (!function_exists('hasAcceptedPayrollTerms')) {
    /**
     * if company has agreed to service terms
     *
     * @param int $companyId
     * @return bool
     */
    function hasAcceptedPayrollTerms(int $companyId): bool
    {
        //
        return (bool) get_instance()->db
            ->where('is_ts_accepted is not null', null, null)
            ->where('is_ts_accepted', 1)
            ->where([
                'company_sid' => $companyId
            ])
            ->count_all_results('gusto_companies');
    }
}

if (!function_exists('checkIfSyncingInProgress')) {
    /**
     * check if payroll sync is in progress
     *
     * @param bool $redirect
     * @return bool
     */
    function checkIfSyncingInProgress(
        bool $redirect = false
    ) {
        // get the CI instance
        $CI = &get_instance();
        // get the logged in company id
        $loggedInCompanyId = $CI
            ->session
            ->userdata("logged_in")["company_detail"]["sid"];
        //
        if (
            $CI
            ->db
            ->where("is_processed", 0)
            ->where("company_sid", $loggedInCompanyId)
            ->count_all_results("payrolls.gusto_sync_queue")
        ) {
            return $redirect ? redirect("payroll/sync") : true;
        } else {
            return false;
        }
    }
}

if (!function_exists('isEmployeeOnPayroll')) {
    /**
     * Check employee on payroll
     * 
     * @param int $employeeId
     * @return int
     */
    function isEmployeeOnPayroll(int $employeeId)
    {
        // check
        return get_instance()->db
            ->where([
                'employee_sid' => $employeeId
            ])
            ->count_all_results('gusto_companies_employees');
    }
}
