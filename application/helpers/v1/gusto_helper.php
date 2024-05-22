<?php defined('BASEPATH') || exit('No direct script access allowed');

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
