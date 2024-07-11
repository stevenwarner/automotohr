<?php defined('BASEPATH') || exit('No direct script access allowed');
loadUpModel("v1/Payroll/Base_payroll_model", "base_payroll_model");
/**
 * Employee federal form payroll model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Employee_federal_form_payroll_model extends Base_payroll_model
{

    private $employeeOldData;

    /**
     * main function
     */
    public function __construct()
    {
        $this->employeeOldData = [];
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
     * job & compensation
     *
     * @method loadEmployeeFederalFormData
     * @method gustoToStoreFederalTax
     * @method storeToGustoFederalTax
     */
    public function dataToStoreEmployeeW4FormFlow()
    {
        if (!$this->gustoEmployee["gusto_uuid"]) {
            return [
                "errors" => [
                    "Employee gusto uuid/version not found."
                ]
            ];
        }
        // load the w4 data with gusto ids
        $this->loadEmployeeFederalFormData();
        $getResponse = [];
        // check if version exists
        if (!$this->gustoIdArray["federal"]["gusto_version"]) {
            $getResponse = $this->gustoToStoreFederalTax();
        }
        // send the data to Gusto
        $updateResponse = $this->storeToGustoFederalTax();
        // check if the form exists then sign it
        $signResponse = $this->checkAndSignTheFederalDocument();
        // set the response
        return [
            "get" => $getResponse,
            "update" =>  $updateResponse,
            "sign" => $signResponse
        ];
    }

    /**
     * load federal form
     */
    private function loadEmployeeFederalFormData()
    {
        // set default
        $this->gustoIdArray["federal"] = [
            "gusto_uuid" => "",
            "gusto_version" => "",
            "data" => []
        ];
        // get the employee federal form
        $record = $this->db
            ->select("
                employer_sid,
                marriage_status,
                mjsw_status,
                claim_total_amount,
                other_income,
                other_deductions,
                additional_tax,
                company_sid
            ")
            ->where([
                "employer_sid" => $this->gustoEmployee["employee_sid"],
                "user_type" => "employee",
                "status" => 1,
                "user_consent" => 1
            ])
            ->limit(1)
            ->get("form_w4_original")
            ->row_array();
        //
        if (!$record) {
            return [
                "errors" => [
                    "Federal form (W4) not assigned or completed by employee."
                ]
            ];
        }
        //
        $data = [];
        $data["filing_status"] = "Single";
        if ($record["marriage_status"] == "jointly") {
            $data["filing_status"] = "Married";
        } elseif ($record["marriage_status"] == "head") {
            $data["filing_status"] = "Head of Household";
        }
        $data["two_jobs"] = $record["mjsw_status"] == "similar_pay" ? true : false;
        $data["dependents_amount"] = $record["claim_total_amount"];
        $data["extra_withholding"] = $record["additional_tax"];
        $data["other_income"] = $record["other_income"];
        $data["deductions"] = $record["other_deductions"];
        $data["w4_data_type"] = "rev_2020_w4";
        // add data
        $this->gustoIdArray["federal"]["data"] = $data;
        // get the gusto version and id
        $record = $this->db
            ->select("gusto_version")
            ->where(
                "employee_sid",
                $this->gustoEmployee["employee_sid"]
            )
            ->limit(1)
            ->get("gusto_employees_federal_tax")
            ->row_array();
        // check if record exists
        if ($record && $record["gusto_version"]) {
            $this->gustoIdArray["federal"]["gusto_version"] =
                $record["gusto_version"];
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
            return $errors;
        }
        if (!$response) {
            return $this
                ->lb_gusto
                ->getEmptyResponse();
        }
        //
        $this->gustoIdArray["federal"]["gusto_version"] = $response["version"];
        //
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

        return
            $this
            ->lb_gusto
            ->getSuccessResponse(
                $response
            );
    }

    /**
     * sync federal tax
     * Gusto to Store
     */
    private function storeToGustoFederalTax()
    {
        //
        if (!$this->gustoIdArray["federal"]["gusto_version"]) {
            return [
                "errors" => [
                    "Federal form gusto version not found."
                ]
            ];
        }
        //
        $request = $this->gustoIdArray["federal"]["data"];
        $request['version'] = $this->gustoIdArray["federal"]["gusto_version"];
        $request['w4_data_type'] = "rev_2020_w4";
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
            return $errors;
        }
        if (!$response) {
            return $this
                ->lb_gusto
                ->getEmptyResponse();
        }
        //
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

        return
            $this
            ->lb_gusto
            ->getSuccessResponse(
                $response
            );
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
