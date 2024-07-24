<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_base_model', 'Payroll_base_model');
/**
 * Regular payroll model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class W4_payroll_model extends Payroll_base_model
{
    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
    }

    /**
     * sync a company's w4
     *
     * @param int $companyId
     */
    public function syncW4s(int $companyId)
    {
        // get all signed w4s
        $records = $this->db
            ->select("
                employer_sid,
                marriage_status,
                mjsw_status,
                claim_total_amount,
                other_income,
                other_deductions,
                additional_tax,
            ")
            ->where([
                "company_sid" => $companyId,
                "user_type" => "employee",
                "status" => 1,
                "user_consent" => 1
            ])
            ->get("form_w4_original")
            ->result_array();
        //
        if (!$records) {
            exit("No w4s found!");
        }
        $this->loadPayrollLibrary($companyId);
        //
        foreach ($records as $v0) {
            //
            $dataArray = [];
            $dataArray["filing_status"] = "Single";
            if ($v0["marriage_status"] == "jointly") {
                $dataArray["filing_status"] = "Married";
            } elseif ($v0["marriage_status"] == "head") {
                $dataArray["filing_status"] = "Head of Household";
            }
            $dataArray["two_jobs"] = $v0["mjsw_status"] == "similar_pay" ? "yes" : "no";
            $dataArray["dependents_amount"] = $v0["claim_total_amount"];
            $dataArray["extra_withholding"] = $v0["additional_tax"];
            $dataArray["other_income"] = $v0["other_income"];
            $dataArray["deductions"] = $v0["other_deductions"];
            $dataArray["w4_data_type"] = "rev_2020_w4";
            //
            $gustoFederalTax = $this->db
                ->where('employee_sid', $v0["employer_sid"])
                ->count_all_results('gusto_employees_federal_tax');
            $method = !$gustoFederalTax ? 'createEmployeeFederalTax' : 'updateEmployeeFederalTax';
            // let's update employee's home address
            $this
                ->$method(
                    $v0["employer_sid"],
                    $dataArray
                );
            //
        }
        exit("Sync completed");
    }

    /**
     * sync a company's employee
     *
     * @param int $employeeId
     * @return array
     */
    public function syncW4ForEmployee(int $employeeId)
    {
        // get all signed w4s
        $records = $this->db
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
                "employer_sid" => $employeeId,
                "user_type" => "employee",
                "status" => 1,
                "user_consent" => 1
            ])
            ->get("form_w4_original")
            ->result_array();
        //
        if (!$records) {
            return [
                "errors" => [
                    "No record found."
                ]
            ];
        }
        $this->loadPayrollLibrary($records[0]["company_sid"]);
        //
        foreach ($records as $v0) {
            //
            $dataArray = [];
            $dataArray["filing_status"] = "Single";
            if ($v0["marriage_status"] == "jointly") {
                $dataArray["filing_status"] = "Married";
            } elseif ($v0["marriage_status"] == "head") {
                $dataArray["filing_status"] = "Head of Household";
            }
            $dataArray["two_jobs"] = $v0["mjsw_status"] == "similar_pay" ? true : false;
            $dataArray["dependents_amount"] = $v0["claim_total_amount"];
            $dataArray["extra_withholding"] = $v0["additional_tax"];
            $dataArray["other_income"] = $v0["other_income"];
            $dataArray["deductions"] = $v0["other_deductions"];
            $dataArray["w4_data_type"] = "rev_2020_w4";
            //
            $gustoFederalTax = $this->db
                ->where('employee_sid', $v0["employer_sid"])
                ->count_all_results('gusto_employees_federal_tax');
            $method = !$gustoFederalTax ? 'createEmployeeFederalTax' : 'updateEmployeeFederalTax';
            // let's update employee's home address
            $this
                ->$method(
                    $v0["employer_sid"],
                    $dataArray
                );
            //
        }
        return [
            "success" => [
                "Successfully synced."
            ]
        ];
    }

    /**
     * sync a company's state w4
     *
     * @param int $companyId
     */
    public function syncStateW4(int $companyId)
    {
        // get all signed w4s
        $records = $this->db
            ->select("
                portal_state_form.fields_json,
                portal_state_form.user_sid,
                gusto_employees_state_tax.questions_json,
                gusto_employees_state_tax.state_code
            ")
            ->where([
                "portal_state_form.company_sid" => $companyId,
                "portal_state_form.user_type" => "employee",
                "portal_state_form.state_form_sid" => 1,
                "portal_state_form.user_consent" => 1
            ])
            ->join(
                "gusto_employees_state_tax",
                "gusto_employees_state_tax.employee_sid = portal_state_form.user_sid",
                "inner"
            )
            ->get("portal_state_form")
            ->result_array();
        //
        if (!$records) {
            exit("No state w4's found found!");
        }
        //
        $this->loadPayrollLibrary($companyId);
        //
        foreach ($records as $v0) {
            // get the state questions
            $questionsObj = json_decode($v0['questions_json'], true);
            // set tmp array
            $tmp = [];
            // convert to list
            foreach ($questionsObj as $question) {
                $tmp[$question['key']] = $question;
            }
            //
            $questionsObj = $tmp;
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
            $data["withholding_allowance"] = $record["section_1_allowances"] ? $record["section_1_allowances"] : 0;
            $data["additional_withholding"] = $record["section_1_additional_withholding"] ? $record["section_1_additional_withholding"] : 0.0;
            $data["file_new_hire_report"] = "yes";
            // set default error array
            $errorsArray = [];
            // add the answers to questions
            foreach ($data as $index => $value) {
                //
                if ($questionsObj[$index]['input_question_format']['type'] !== 'Select' && $value < 0) {
                    $errorsArray[] = '"' . ($questionsObj[$index]['label']) . '" can not be less than 0.';
                }
                //
                if ($questionsObj[$index]['input_question_format']['type'] !== 'Select' && !$value) {
                    $value = 0;
                } elseif ($questionsObj[$index]['input_question_format']['type'] === 'Select') {
                    $value = $value == 'yes' ? "true" : $value;
                    $value = $value == 'no' ? "false" : $value;
                }
                //
                if ($questionsObj[$index]['answers'][0]['value']) {
                    $questionsObj[$index]['answers'][0]['value'] = $value;
                } else {
                    $questionsObj[$index]['answers'] = [['value' => $value, 'valid_from' => '2010-01-01']];
                }
            }
            // when an error occurred
            if ($errorsArray) {
                continue;
            }
            //
            $passData = ['state' => $v0['state_code'], 'questions' => array_values($questionsObj)];
            //
            $this->updateEmployeeStateTax($v0["user_sid"], $passData);
        }

        exit("Sync completed");
    }

    /**
     * sync a company's state w4
     *
     * @param int $companyId
     */
    public function pushMinnesotaStateFormOfEmployeeToGusto(int $employeeId, int $stateFormId)
    {
        // get all signed w4s
        $result = $this->db
            ->select("
                portal_state_form.company_sid,
                portal_state_form.fields_json,
                portal_state_form.user_sid,
                gusto_employees_state_tax.questions_json,
                gusto_employees_state_tax.state_code
            ")
            ->where([
                "portal_state_form.user_sid" => $employeeId,
                "portal_state_form.user_type" => "employee",
                "portal_state_form.state_form_sid" => $stateFormId,
                "portal_state_form.user_consent" => 1
            ])
            ->join(
                "gusto_employees_state_tax",
                "gusto_employees_state_tax.employee_sid = portal_state_form.user_sid",
                "inner"
            )
            ->get("portal_state_form")
            ->row_array();
        //
        if (!$result) {
            return ["errors" => ["No Minnesota state w4 form found."]];
        }
        //
        $this->loadPayrollLibrary($result["company_sid"]);
        //
        // get the state questions
        $questionsObj = json_decode($result['questions_json'], true);
        // set tmp array
        $tmp = [];
        // convert to list
        foreach ($questionsObj as $question) {
            $tmp[$question['key']] = $question;
        }
        //
        $questionsObj = $tmp;
        //
        $record = json_decode($result["fields_json"], true);
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
        $data["withholding_allowance"] = $record["section_1_allowances"] ? $record["section_1_allowances"] : 0;
        $data["additional_withholding"] = $record["section_1_additional_withholding"] ? $record["section_1_additional_withholding"] : 0.0;
        $data["file_new_hire_report"] = "yes";
        // set default error array
        $errorsArray = [];
        // add the answers to questions
        foreach ($data as $index => $value) {
            //
            if ($questionsObj[$index]['input_question_format']['type'] !== 'Select' && $value < 0) {
                $errorsArray[] = '"' . ($questionsObj[$index]['label']) . '" can not be less than 0.';
            }
            //
            if ($questionsObj[$index]['input_question_format']['type'] !== 'Select' && !$value) {
                $value = 0;
            } elseif ($questionsObj[$index]['input_question_format']['type'] === 'Select') {
                $value = $value == 'yes' ? "true" : $value;
                $value = $value == 'no' ? "false" : $value;
            }
            //
            if ($questionsObj[$index]['answers'][0]['value']) {
                $questionsObj[$index]['answers'][0]['value'] = $value;
            } else {
                $questionsObj[$index]['answers'] = [['value' => $value, 'valid_from' => '2010-01-01']];
            }
        }
        // when an error occurred
        if ($errorsArray) {
            return ["errors" => $errorsArray];
        }
        //
        $passData = ['state' => $result['state_code'], 'questions' => array_values($questionsObj)];

        return $this->updateEmployeeStateTax($result["user_sid"], $passData);
    }

    /**
     * sync a company's w4
     *
     * @param int $companyId
     */
    public function syncDDI(int $companyId)
    {
        // get all signed w4s
        $records = $this->db
            ->select("
                users_sid
            ")
            ->where([
                "company_sid" => $companyId,
                "users_type" => "employee",
            ])
            ->group_by("users_sid")
            ->get("bank_account_details")
            ->result_array();
        //
        if (!$records) {
            exit("No DDIs found!");
        }
        //
        $this->loadPayrollLibrary($companyId);
        //
        foreach ($records as $v0) {
            // check and update profile status
            updateUserById([
                "payment_method" => "direct_deposit"
            ], $v0["users_sid"]);
            //
            $this->synEmployeeBankAccounts($v0["users_sid"], true);
        }


        exit("Sync completed");
    }

    /**
     * create employee's federal tax on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    private function createEmployeeFederalTax(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        if (!$gustoEmployee) {
            return ["errors" => ["Employee not found."]];
        }
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'createFederalTax',
            $companyDetails,
            [],
            'GET'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // set update array
        $upd = [];
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['employee_sid'] = $employeeId;
        $upd['filing_status'] = $gustoResponse['filing_status'];
        $upd['extra_withholding'] = $gustoResponse['extra_withholding'];
        $upd['two_jobs'] = $gustoResponse['two_jobs'];
        $upd['dependents_amount'] = $gustoResponse['dependents_amount'];
        $upd['other_income'] = $gustoResponse['other_income'];
        $upd['deductions'] = $gustoResponse['deductions'];
        $upd['w4_data_type'] = $data['w4_data_type'];
        $upd['created_at'] = $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->insert('gusto_employees_federal_tax', $upd);
        //
        return $this->updateEmployeeFederalTax($employeeId, $data);
    }

    /**
     * create employee's federal tax on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    private function updateEmployeeFederalTax(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        if (!$gustoEmployee) {
            return ["errors" => ["Employee not found."]];
        }
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // let's make request
        $request = [];
        $request['version'] = $this->db
            ->select('gusto_version')
            ->where('employee_sid', $employeeId)
            ->get('gusto_employees_federal_tax')
            ->row_array()['gusto_version'];
        $request['filing_status'] = $data['filing_status'];
        $request['extra_withholding'] = $data['extra_withholding'];
        $request['two_jobs'] = $data['two_jobs'];
        $request['dependents_amount'] = $data['dependents_amount'];
        $request['other_income'] = $data['other_income'];
        $request['deductions'] = $data['deductions'];
        $request['w4_data_type'] = 'rev_2020_w4';
        // response
        $gustoResponse = gustoCall(
            'createFederalTax',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // set update array
        $upd = [];
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['filing_status'] = $gustoResponse['filing_status'];
        $upd['extra_withholding'] = $gustoResponse['extra_withholding'];
        $upd['two_jobs'] = $gustoResponse['two_jobs'];
        $upd['dependents_amount'] = $gustoResponse['dependents_amount'];
        $upd['other_income'] = $gustoResponse['other_income'];
        $upd['deductions'] = $gustoResponse['deductions'];
        $upd['w4_data_type'] = $request['w4_data_type'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('employee_sid', $employeeId)
            ->update('gusto_employees_federal_tax', $upd);
        //
        return ['success' => true];
    }

    /**
     * update employee's state tax from Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function updateEmployeeStateTax(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        if (!$gustoEmployee) {
            return false;
        }
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        //
        $request = [
            'states' => [$data]
        ];

        // response
        $gustoResponse = gustoCall(
            'updateStateTax',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        foreach ($gustoResponse as $tax) {
            //
            $whereArray = [
                'employee_sid' => $employeeId,
                'state_code' => $tax['state']
            ];
            //
            $dataArray = [];
            $dataArray['state_code'] = $tax['state'];
            $dataArray['file_new_hire_report'] = $tax['file_new_hire_report'];
            $dataArray['is_work_state'] = $tax['is_work_state'];
            $dataArray['questions_json'] = json_encode($tax['questions']);
            $dataArray['updated_at'] = getSystemDate();
            // update
            $this->db
                ->where($whereArray)
                ->update('gusto_employees_state_tax', $dataArray);
        }
        //
        return ['success' => true];
    }

    /**
     * get employee bank accounts
     *
     * @param int   $employeeId
     */
    private function synEmployeeBankAccounts(
        int $employeeId,
        bool $doMoveToGusto = false
    ): array {
        // get employee bank accounts
        $bankAccounts = $this->db
            ->select('
                sid,
                account_title,
                account_number,
                routing_transaction_number,
                account_type,
                deposit_type,
                account_percentage,
                gusto_uuid
            ')
            ->where([
                'users_sid' => $employeeId,
                'users_type' => 'employee'
            ])
            ->order_by('sid', 'asc')
            ->limit(2)
            ->get('bank_account_details')
            ->result_array();
        //
        if (!$bankAccounts) {
            return [];
        }
        if (!$doMoveToGusto) {
            return $bankAccounts;
        }
        //
        $didIt = false;
        //
        foreach ($bankAccounts as $index => $bankAccount) {
            //
            if (!$bankAccount['gusto_uuid']) {
                //
                $response = $this->addEmployeeBankAccountToGusto($employeeId, [
                    'account_title' => $bankAccount['account_title'],
                    'account_number' => $bankAccount['account_number'],
                    'routing_transaction_number' => $bankAccount['routing_transaction_number'],
                    'account_type' => $bankAccount['account_type'],
                    'sid' => $bankAccount['sid'],
                ]);
                //
                if ($response['errors']) {
                    unset($bankAccounts[$index]);
                } else {
                    $didIt = true;
                    $bankAccounts[$index]['gusto_uuid'] = $response['gusto_uuid'];
                }
            }
        }
        //
        if ($didIt) {
            $this->syncEmployeePaymentMethodFromGusto($employeeId);
        }
        //
        return $bankAccounts;
    }

    /**
     * add employees bank account
     *
     * @param int   $employeeId
     * @param array $data
     */
    private function addEmployeeBankAccountToGusto(int $employeeId, array $data): array
    {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        if (!$gustoEmployee) {
            return [];
        }
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // make request
        $request = [];
        $request['name'] = $data['account_title'];
        $request['routing_number'] = $data['routing_transaction_number'];
        $request['account_number'] = $data['account_number'];
        $request['account_type'] = ucwords($data['account_type']);
        // response
        $gustoResponse = gustoCall(
            'addBankAccount',
            $companyDetails,
            $request,
            'POST'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->reset_query()
            ->where('sid', $data['sid'])
            ->update(
                'bank_account_details',
                [
                    'gusto_uuid' => $gustoResponse['uuid'],
                ]
            );
        //
        return ['success' => true, 'gusto_uuid' => $gustoResponse['uuid']];
    }

    /**
     * get employee's payment method from Gusto
     *
     * @param int   $employeeId
     */
    private function syncEmployeePaymentMethodFromGusto(
        int $employeeId
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        if (!$gustoEmployee) {
            return [];
        }
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getPaymentMethod',
            $companyDetails,
            [],
            'GET'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        $whereArray = [
            'employee_sid' => $employeeId,
        ];
        //
        $dataArray = [];
        $dataArray['gusto_version'] = $gustoResponse['version'];
        $dataArray['type'] = $gustoResponse['type'];
        $dataArray['split_by'] = $gustoResponse['split_by'];
        $dataArray['splits'] = json_encode($gustoResponse['splits']);
        $dataArray['updated_at'] = getSystemDate();
        //
        if (!$this->db->where($whereArray)->count_all_results('gusto_employees_payment_method')) {
            // insert
            $dataArray['created_at'] = $dataArray['updated_at'];
            $dataArray['employee_sid'] = $employeeId;
            //
            $this->db
                ->insert('gusto_employees_payment_method', $dataArray);
        } else {
            // update
            $this->db
                ->where($whereArray)
                ->update('gusto_employees_payment_method', $dataArray);
        }
        if ($gustoResponse['type'] == 'Check') {
            //
            $this->db
                ->where('users_sid', $employeeId)
                ->where('users_type', 'employee')
                ->update('bank_account_details', [
                    'gusto_uuid' => null
                ]);
        }
        //
        return ['success' => true, 'version' => $gustoResponse['version']];
    }
}
