<?php

defined('BASEPATH') || exit('No direct script access allowed');
loadUpModel("payroll/Base_payroll_model", "base_payroll_model");
/**
 * Payroll model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Payrolls_model extends Base_payroll_model
{
    /**
     * set company details
     *
     * @method initialize
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
     * check the job queue progress
     *
     * @param int $companyId
     */
    public function checkSyncProgress(
        int $companyId
    ) {
        if (
            $this
            ->db
            ->where("is_processed", 0)
            ->where("company_sid", $companyId)
            ->count_all_results("payrolls.gusto_sync_queue")
        ) {
            return redirect("payrolls/sync");
        }
        //
        return false;
    }

    /**
     * check if the company is
     * linked with Gusto
     *
     * @param int $companyId
     * @return bool
     */
    public function checkIfCompanyIsLinked(
        int $companyId
    ): bool {
        return $this
            ->db
            ->where([
                'company_sid' => $companyId
            ])
            ->count_all_results('gusto_companies');
    }

    /**
     * check if there are payroll blockers
     *
     * @param int $companyId
     * @return bool
     */
    public function checkPayrollBlockers(
        int $companyId
    ): bool {
        return $this
            ->db
            ->where(
                "company_sid",
                $companyId
            )
            ->count_all_results('payrolls.payroll_blockers');
    }

    /**
     * get if there are payroll blockers
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
            ->get('payrolls.payroll_blockers')
            ->row_array();
        //
        if ($record) {
            $record["blocker_json"] = json_decode(
                $record["blocker_json"],
                true
            );
        }
        return $record;
    }

    /**
     * get company flow
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyFlowLink(
        int $companyId
    ) {
        // 
        $this->setCompanyDetails(
            $companyId
        );
        // set flow types
        $types = "add_bank_info,";
        $types .= "verify_bank_info,";
        $types .= "federal_tax_setup,";
        $types .= "state_setup,";
        $types .= "sign_all_forms";
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "flows",
                $this->gustoCompany,
                [
                    "flow_type" => $types
                ],
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

        return $response;
    }

    /**
     * syn web hooks
     * guto to store
     *
     * @param string $mode
     * @return bool
     */
    public function syncWebHooks(
        string $mode
    ) {
        // load library in mode
        $this
            ->load
            ->library(
                "Lb_gusto",
                ["mode" => $mode],
                "lb_gusto"
            );
        //
        $response = $this
            ->lb_gusto
            ->getWebHooks();
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
            return [
                "errors" => [
                    "No web hooks found."
                ]
            ];
        }
        //
        foreach ($response as $v0) {
            // set data array
            $ins = [
                "gusto_uuid" => $v0["uuid"],
                "webhook_type" => $v0["subscription_types"][0],
                "status" => $v0["status"],
                "subscription_types" => json_encode($v0["subscription_types"]),
                "url" => $v0["url"],
                "updated_at" => getSystemDate(),
            ];
            //
            if (
                $this
                ->db
                ->where(
                    "gusto_uuid",
                    $v0["uuid"]
                )
                ->count_all_results("gusto_companies_webhooks")
            ) {
                $this
                    ->db
                    ->where(
                        "gusto_uuid",
                        $v0["uuid"]
                    )
                    ->update(
                        "gusto_companies_webhooks",
                        $ins
                    );
            } else {
                //
                $ins["created_at"] = $ins["updated_at"];
                $this
                    ->db
                    ->insert(
                        "gusto_companies_webhooks",
                        $ins
                    );
            }
        }
        //
        return $response;
    }
}
