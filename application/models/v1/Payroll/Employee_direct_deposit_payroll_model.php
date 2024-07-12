<?php defined('BASEPATH') || exit('No direct script access allowed');
loadUpModel("v1/Payroll/Base_payroll_model", "base_payroll_model");
/**
 * Employee direct deposit payroll model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Employee_direct_deposit_payroll_model extends Base_payroll_model
{
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
     * Direct deposit
     *
     * @method loadEmployeeDirectDepositData
     */
    public function dataToStoreEmployeeDirectDepositFlow()
    {
        if (!$this->gustoEmployee["gusto_uuid"]) {
            return [
                "errors" => [
                    "Employee gusto uuid/version not found."
                ]
            ];
        }
        // load the w4 data with gusto ids
        $this->loadEmployeeDirectDepositData();
        // add as new
        if ($this->gustoIdArray["direct_deposit"]["gusto"]["event"] === "add") {
            //
            foreach ($this->gustoIdArray["direct_deposit"]["data"]["new"] as $v0) {
                // create bank account on Gusto
                $this->storeToGustoBankAccount($v0);
            }
        } else {
            // update procedure
            foreach ($this->gustoIdArray["direct_deposit"]["data"]["new"] as $v0) {
                //
                if (!$v0["gusto_uuid"]) {
                    // create bank account on Gusto
                    $this->storeToGustoBankAccount($v0);
                    continue;
                }
                // set the latest data
                $newData = $v0;
                // set the old data
                $oldData = $this->gustoIdArray["direct_deposit"]["data"]["old"][$v0["sid"]];
                // remove unnecessary indexes
                unset(
                    $newData["sid"],
                    $newData["gusto_uuid"],
                    $oldData["bank_account_details_sid"]
                );
                // compare the data
                if ($this->needToUpdate($oldData, $newData)) {
                    $this->updateEmployeeBankAccount($v0);
                }
            }
        }
        // update the payment method
        $updateResponse = $this->storeToGustoPaymentMethod();
        //
        return [
            "update" =>  $updateResponse,
        ];
    }


    /**
     * load federal form
     */
    private function loadEmployeeDirectDepositData()
    {
        // set default
        $this->gustoIdArray["direct_deposit"] = [
            "gusto" => [],
            "data" => [
                "old" => [],
                "new" => [],
            ]
        ];
        // get the employee direct deposit
        $records = $this->getLatestDirectDeposits();
        //
        if (!$records) {
            return [
                "errors" => [
                    "Direct deposit forms not assigned or completed by employee."
                ]
            ];
        }
        // extract ids
        $ids = array_column(
            $records,
            "sid"
        );
        // get the data from history
        $this->gustoIdArray["direct_deposit"]["data"]["new"] =
            $records;
        $this->gustoIdArray["direct_deposit"]["data"]["old"] =
            $this->getHistoryDirectDeposits($ids);
        //
        $this->gustoIdArray["direct_deposit"]["gusto"]["event"] = "add";
        //
        foreach ($records as $v0) {
            $this->gustoIdArray["direct_deposit"]["gusto"][$v0["sid"]] = [
                "gusto_uuid" => $v0["gusto_uuid"]
            ];
            //
            if ($v0["gusto_uuid"]) {
                $this->gustoIdArray["direct_deposit"]["gusto"]["event"] = "edit";
            }
        }
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
            return $errors;
        }
        if (!$response) {
            return $this
                ->lb_gusto
                ->getEmptyResponse();
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

        return $this
            ->lb_gusto
            ->getSuccessResponse(
                $response
            );
    }

    /**
     * push a single bank account to Gusto
     */
    private function updateEmployeeBankAccount(
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
        $this->gustoCompany["other_uuid_2"] =
            $bankAccount["gusto_uuid"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "update_employee_bank_account",
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
        if (!$response) {
            return $this
                ->lb_gusto
                ->getEmptyResponse();
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

        return $this
            ->lb_gusto
            ->getSuccessResponse(
                $response
            );
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
            //
            $this->gustoToStorePaymentMethod();
            //
            return $this->storeToGustoPaymentMethod();
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
        // make the call
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "payment_method",
                $this->gustoCompany,
                $request,
                "PUT",
                true
            );
        // check the version error
        if ($response["version_error"]) {
            // check the version error
            $this->gustoToStorePaymentMethod();
            return $this->storeToGustoPaymentMethod();
        }
        // check for actual errors
        if ($response["errors"]) {
            return $response["errors"];
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

        return $this
            ->lb_gusto
            ->getSuccessResponse(
                $response
            );
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
            return $errors;
        }
        if (!$response) {
            return $this
                ->lb_gusto
                ->getEmptyResponse();
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
        //
        return $this
            ->lb_gusto
            ->getSuccessResponse(
                $response
            );
    }

    /**
     * get the latest direct deposits
     *
     * @return array
     */
    private function getLatestDirectDeposits(): array
    {
        return $this->db
            ->select([
                "sid",
                "account_title",
                "routing_transaction_number",
                "account_number",
                "account_type",
                "gusto_uuid",
            ])
            ->where([
                "users_sid" => $this->gustoEmployee["employee_sid"],
                "users_type" => "employee",
                "is_consent" => 1
            ])
            ->limit(2)
            ->get("bank_account_details")
            ->result_array();
    }

    /**
     * get the latest direct deposits
     *
     * @param array $directDepositIds
     * @return array
     */
    private function getHistoryDirectDeposits(
        array $directDepositIds
    ): array {
        $records = $this->db
            ->select([
                "bank_account_details_sid",
                "account_title",
                "routing_transaction_number",
                "account_number",
                "account_type",
            ])
            ->where_in("bank_account_details_sid", $directDepositIds)
            ->order_by("sid", "DESC")
            ->limit(2)
            ->get("bank_account_details_history")
            ->result_array();
        //
        if ($records) {
            $tmp = [];
            foreach ($records as $v0) {
                $tmp[$v0["bank_account_details_sid"]] = $v0;
            }
            $records = $tmp;
        }
        return $records;
    }


    /**
     * check and sign form
     *
     * @return array
     */
    private function checkAndSignTheFederalDocument()
    {
        // check if form exists
        $form = $this->db
            ->select('gusto_uuid, sid, requires_signing')
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->where('form_name', 'US_W-4')
            ->get('gusto_employees_forms')
            ->row_array();
        //
        if (!$form) {
            // get the forms
            $this->gustoToStoreForms();
            return $this->checkAndSignTheFederalDocument();
        }
        if ($form["requires_signing"]) {
            //
            $this->gustoIdArray["federal_sign"] = $form;
            // sign the document on Gusto
            return $this->signFederalForm();
        } else {
            return [
                "errors" => [
                    "The federal form (W4) doesn't require signing."
                ]
            ];
        }
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
            return $errors;
        }
        if (!$response) {
            return $this
                ->lb_gusto
                ->getEmptyResponse();
        }
        //
        foreach ($response as $v0) {
            if ($v0['name'] != 'US_W-4') {
                continue;
            }
            //
            $ins = [];
            $ins['form_name'] = $v0['name'];
            $ins['form_title'] = $v0['title'];
            $ins['description'] = $v0['description'];
            $ins['requires_signing'] = $v0['requires_signing'];
            $ins['draft'] = $v0['draft'];
            $ins['year'] = $v0['year'];
            $ins['quarter'] = $v0['quarter'];
            $ins['updated_at'] = getSystemDate();

            // we need to check the current status of w4
            $document = $this->getW4AssignStatus();
            $ins['status'] = $document['status'];
            $ins['document_sid'] = $document['documentId'];
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

        return
            $this
            ->lb_gusto
            ->getSuccessResponse(
                $response
            );
    }

    /**
     * Sign the federal form on Gusto
     */
    private function signFederalForm()
    {
        // get employee name
        $userInfo = $this->db
            ->select('first_name, last_name')
            ->where(
                'sid',
                $this->gustoEmployee["employee_sid"]
            )
            ->get('users')
            ->row_array();
        //
        $this->gustoCompany['other_uuid'] =
            $this->gustoEmployee["gusto_uuid"];
        $this->gustoCompany['other_uuid_2'] =
            $this->gustoIdArray["federal_sign"]['gusto_uuid'];
        $request = [
            'signature_text' => (ucwords(trim($userInfo['first_name'] . ' ' . $userInfo['last_name']))),
            'agree' => "true",
            'signed_by_ip_address' => getUserIP(),
        ];
        // response
        $response = $this
            ->lb_gusto
            ->gustoCall(
                'sign_employee_form',
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
        if (!$response) {
            return $this
                ->lb_gusto
                ->getEmptyResponse();
        }
        //
        $this->db
            ->where('sid', $this->gustoIdArray["federal_sign"]['sid'])
            ->update(
                'gusto_employees_forms',
                [
                    'requires_signing' => $response['requires_signing'],
                    'draft' => $response['draft'],
                    'year' => $response['year'],
                    'quarter' => $response['quarter'],
                    'signed_at' => getSystemDate(),
                    'updated_at' => getSystemDate()
                ]
            );
        //
        return
            $this
            ->lb_gusto
            ->getSuccessResponse(
                $response
            );
    }
}
