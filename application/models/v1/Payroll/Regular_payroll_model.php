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
        // set date time
        $systemDateTime = getSystemDate();
        // iterate through it
        foreach ($response as $v0) {
            // set insert array
            $ins = [];
            $ins['payroll_deadline'] = $v0['payroll_deadline'];
            $ins['check_date'] = $v0['check_date'];
            $ins['processed'] = $v0['processed'];
            $ins['processed_date'] = $v0['processed_date'];
            $ins['calculated_at'] = $v0['calculated_at'];
            $ins['off_cycle'] = $v0["off_cycle"];
            $ins['off_cycle_reason'] = $v0["off_cycle_reason"]
                ? $v0["off_cycle_reason"]
                : "Bonus";
            $ins['fixed_withholding_rate'] = $v0["fixed_withholding_rate"]
                ? $v0["fixed_withholding_rate"]
                : 0;
            $ins['payroll_status_meta'] = json_encode(
                $v0["payroll_status_meta"]
            );
            $ins['totals'] = json_encode(
                $v0["totals"]
            );
            $ins['submission_blockers'] = json_encode(
                $v0["submission_blockers"]
            );
            $ins['credit_blockers'] = json_encode(
                $v0["credit_blockers"]
            );
            $ins['pay_period'] = json_encode(
                $v0["pay_period"]
            );
            $ins['last_changed_by'] = $employeeId;
            $ins['updated_at'] = $systemDateTime;
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
     * get regular payroll
     *
     * @param int $companyId
     * @param int $limit Optional
     * @return array
     */
    public function getRegularPayrolls(int $companyId, int $limit = 0): array
    {
        //
        $returnArray = [
            'current' => [],
            'late' => []
        ];
        // get payrolls
        $this->db
            ->select('
                sid,
                start_date,
                end_date,
                check_date,
                status
            ')
            ->where('company_sid', $companyId)
            ->where('processed', 0)
            ->order_by('check_date', 'DESC');
        //
        if ($limit != 0) {
            $this->db->limit($limit);
        }
        $records = $this->db
            ->get('payrolls.regular_payrolls')
            ->result_array();
        //
        if (!$records) {
            return $returnArray;
        }
        //
        $todayDate = getSystemDate("Y-m-d");
        //
        foreach ($records as $record) {
            if (
                $record["check_date"] > $todayDate
                && !$returnArray['current']
            ) {
                $returnArray['current'] = $record;
                continue;
            }
            $returnArray['late'][] = $record;
        }
        //
        return $returnArray;
    }

    /**
     * check and get regular payroll
     *
     * @param int $payrollId
     * @param int $companyId
     * @return string
     */
    public function checkAndGetPayrollStatus(
        int $payrollId,
        int $companyId
    ): string {
        // get single payroll
        $record = $this->db
            ->select('sid')
            ->where('company_sid', $companyId)
            ->where('sid', $payrollId)
            ->where('processed', 0)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        if (!$record) {
            return 'not_found';
        }
        // check
        if (
            $this
            ->db
            ->where("regular_payroll_sid", $record["sid"])
            ->where("version is not null", null)
            ->count_all_results("payrolls.regular_payrolls_employees")
        ) {
            return "success";
        }
        //
        return 'prepare';
    }

    /**
     * prepare payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function preparePayrollForUpdate(int $payrollId)
    {
        // get single payroll
        $payroll = $this->db
            ->select('
                gusto_uuid
            ')
            ->where('sid', $payrollId)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        // add payroll uuid
        $this->gustoCompany['other_uuid'] = $payroll['gusto_uuid'];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                'payroll_prepare',
                $this->gustoCompany,
                [],
                "PUT",
                true
            );
        // errors found
        if ($response["errors"]) {
            return $response["errors"];
        }
        // update the payroll details
        $upd = [];
        $upd['gusto_uuid'] = $response['payroll_uuid'];
        $upd['check_date'] = $response['check_date'];
        $upd['calculated_at'] = $response['calculated_at'];
        $upd['payroll_deadline'] = $response['payroll_deadline'];
        $upd['status'] = "pending";
        $upd['fixed_compensations_json'] = json_encode(
            $response["fixed_compensation_types"]
        );
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $payrollId)
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );
        // sync the employees
        if ($response['employee_compensations']) {
            foreach ($response['employee_compensations'] as $value) {
                //
                $employeeId = $this
                    ->db
                    ->select('employee_sid')
                    ->where('gusto_uuid', $value['employee_uuid'])
                    ->get('gusto_companies_employees')
                    ->row_array()['employee_sid'];
                //
                if (!$employeeId) {
                    continue;
                }
                // set where array
                $whereArray = [
                    'regular_payroll_sid' => $payrollId,
                    'employee_sid' => $employeeId
                ];
                //
                $dataArray = [];
                $dataArray['data_json'] = json_encode($value);
                $dataArray['is_skipped'] = $value['excluded'];
                $dataArray['version'] = $value['version'];
                $dataArray['note'] = $value['memo'];
                $dataArray['payment_method'] = $value['payment_method'];
                $dataArray['updated_at'] = getSystemDate();
                $dataArray['gross_pay'] =
                    $dataArray['net_pay'] =
                    $dataArray['check_amount'] = 0;
                //
                if (!$this->db->where($whereArray)->count_all_results('payrolls.regular_payrolls_employees')) {
                    // insert
                    $dataArray['regular_payroll_sid'] = $payrollId;
                    $dataArray['employee_sid'] = $whereArray['employee_sid'];
                    $dataArray['created_at'] = $dataArray['updated_at'];
                    //
                    $this->db
                        ->insert(
                            'payrolls.regular_payrolls_employees',
                            $dataArray
                        );
                } else {
                    // update
                    $this->db
                        ->where($whereArray)
                        ->update(
                            'payrolls.regular_payrolls_employees',
                            $dataArray
                        );
                }
            }
        }
        //
        return $this;
    }

    /**
     * get regular payroll specific columns by id
     *
     * @param int $payrollId
     * @param array $columns
     * @return array
     */
    public function getRegularPayrollByIdColumns(
        int $payrollId,
        array $columns
    ): array {
        // get single payroll
        return $this->db
            ->select($columns)
            ->where('sid', $payrollId)
            ->get('payrolls.regular_payrolls')
            ->row_array();
    }

    /**
     * get regular payroll
     *
     * @param int $companyId
     * @param int $payrollId
     * @param array $columns
     * @return array
     */
    public function getRegularPayrollById(
        int $companyId,
        int $payrollId,
        array $columns
    ): array {
        // get single payroll
        $record = $this->db
            ->select($columns)
            ->where('company_sid', $companyId)
            ->where('sid', $payrollId)
            ->where('processed', 0)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        if (!$record) {
            return [];
        }
        // decode the fixed compensations
        $record["fixed_compensations"] = json_decode(
            $record["fixed_compensations_json"],
            true
        );
        //
        if ($record["fixed_compensations"]) {
            $tmp = [];
            foreach ($record["fixed_compensations"] as $v0) {
                $v0["amount"] = 0;
                $tmp[stringToSlug($v0['name'], '_')] = $v0;
            }
            $record["fixed_compensations"] = $tmp;
        }
        unset($record["fixed_compensations_json"]);
        $record['totals'] = json_decode($record['totals'], true);
        // get the employees
        $employees = $this->db
            ->select('
                employee_sid,
                data_json,
                additional_earnings,
                reimbursement_json,
                deductions_json
            ')
            ->where('regular_payroll_sid', $payrollId)
            ->get('payrolls.regular_payrolls_employees')
            ->result_array();
        //
        $record['employees'] = [];
        //
        if ($employees) {
            foreach ($employees as $value) {
                //
                $value['data_json'] = json_decode($value['data_json'], true);
                //
                $dataArray = $value['data_json'];
                //
                if ($dataArray['hourly_compensations']) {
                    //
                    $tmp = [];
                    //
                    foreach ($dataArray['hourly_compensations'] as $v1) {
                        $v1["hours"] = (float)$v1["hours"];
                        $tmp[stringToSlug($v1['name'], '_')] = $v1;
                    }
                    $dataArray['hourly_compensations'] = $tmp;
                }
                //
                $dataArray["fixed_compensations"]
                    = $record["fixed_compensations"];
                //
                if ($value["data_json"]["fixed_compensations"]) {
                    foreach ($value["data_json"]["fixed_compensations"] as $v2) {
                        $slug = stringToSlug($v2['name'], '_');
                        // if exists
                        if ($dataArray["fixed_compensations"][$slug]) {
                            $dataArray["fixed_compensations"][$slug]["amount"] = $v2["amount"];
                            $dataArray["fixed_compensations"][$slug]["job_uuid"] = $v2["job_uuid"];
                        } else {
                            $dataArray["fixed_compensations"][$slug] = $v2;
                        }
                    }
                }
                //
                $dataArray["data"] = [
                    "additional_earnings" =>
                    json_decode(
                        $value["additional_earnings"],
                        true
                    ), "reimbursement_json" =>
                    json_decode(
                        $value["reimbursement_json"],
                        true
                    ), "deductions_json" =>
                    json_decode(
                        $value["deductions_json"],
                        true
                    ),
                ];
                //
                $record['employees'][$value['employee_sid']] = $dataArray;
            }
        }
        //
        return $record;
    }

    /**
     * get payroll employees
     *
     * @method getEmployeeCompensation
     * @param int $companyId
     * @return array
     */
    public function getPayrollEmployeesWithCompensation(
        int $companyId
    ): array {
        //
        $records = $this->db
            ->select(getUserFields())
            ->join('users', 'users.sid = gusto_companies_employees.employee_sid', 'inner')
            ->where('gusto_companies_employees.company_sid', $companyId)
            ->where('gusto_companies_employees.is_onboarded', 1)
            ->get('gusto_companies_employees')
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $employee) {
            $tmp[$employee['userId']] = [
                'id' => $employee['userId'],
                'name' => remakeEmployeeName($employee),
                'compensation' => $this->getEmployeeCompensation($employee['userId'])
            ];
        }
        //
        return $tmp;
    }

    /**
     * get employee compensation
     *
     * @param int $employeeId
     * @return array
     */
    private function getEmployeeCompensation(
        int $employeeId
    ): array {
        //
        $returnArray = [
            'rate' => 0,
            'payment_unit' => 'hour',
            'text' => '$0.00 /hour'
        ];
        //
        $record = $this->db
            ->select('current_compensation_uuid')
            ->where('employee_sid', $employeeId)
            ->get('gusto_employees_jobs')
            ->row_array();
        //
        if (!$record) {
            return $returnArray;
        }
        //
        $compensation = $this->db
            ->select('rate, payment_unit, flsa_status')
            ->where('gusto_uuid', $record['current_compensation_uuid'])
            ->get('gusto_employees_jobs_compensations')
            ->row_array();
        //
        if ($compensation) {
            $returnArray['rate'] = $compensation['rate'];
            $returnArray['payment_unit'] = $compensation['payment_unit'];
            $returnArray['payment_unit_text'] = getPaymentUnitType($compensation['flsa_status']);
            $returnArray['text'] = '$' . $returnArray['rate'] . '/' . (strtolower($returnArray['payment_unit']));
        }
        //
        return $returnArray;
    }


    /**
     * 
     */
    public function updateEmployeeCompensation(
        int $payrollId,
        array $employeeArray
    ) {
        // set where array
        $whereArray = [
            "regular_payroll_sid" => $payrollId,
            'employee_sid' => $this
                ->db
                ->select('employee_sid')
                ->where('gusto_uuid', $employeeArray['employee_uuid'])
                ->get('gusto_companies_employees')
                ->row_array()['employee_sid']
        ];
        //
        $dataArray = [];
        $dataArray['is_skipped'] = $employeeArray['excluded'];
        $dataArray['version'] = $employeeArray['version'];
        $dataArray['payment_method'] = $employeeArray['payment_method'];
        $dataArray['updated_at'] = getSystemDate();
        //
        $dataArray['regular_hours'] = $employeeArray['hourly_compensations']["regular_hours"]["hours"];
        $dataArray['overtime'] = $employeeArray['hourly_compensations']["overtime"]["hours"];
        $dataArray['double_overtime'] = $employeeArray['hourly_compensations']["double_overtime"]["hours"];
        $dataArray['bonus'] = $employeeArray['fixed_compensations']["bonus"]["amount"] ?? 0;
        $employeeArray["fixed_compensations"] = array_values($employeeArray["fixed_compensations"]);
        $employeeArray["hourly_compensations"] = array_values($employeeArray["hourly_compensations"]);
        // reset the json
        $dataArray['reimbursement_json'] = json_encode($employeeArray["data"]["reimbursement_json"]);
        $dataArray['deductions_json'] = json_encode($employeeArray["data"]["deductions_json"]);
        $dataArray['additional_earnings'] = json_encode($employeeArray["data"]["additional_earnings"]);
        $dataArray['data_json'] = json_encode($employeeArray);
        //
        if (!$this->db->where($whereArray)->count_all_results('payrolls.regular_payrolls_employees')) {
            // insert
            $dataArray['employee_sid'] = $whereArray['employee_sid'];
            $dataArray['created_at'] = $dataArray['updated_at'];
            //
            $this->db
                ->insert(
                    'payrolls.regular_payrolls_employees',
                    $dataArray
                );
        } else {
            // update
            $this->db
                ->where($whereArray)
                ->update(
                    'payrolls.regular_payrolls_employees',
                    $dataArray
                );
        }
    }

    /**
     * update payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function updatePayrollById(int $payrollId)
    {
        // get single payroll
        $payroll = $this->db
            ->select('
                gusto_uuid,
                company_sid
            ')
            ->where('sid', $payrollId)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        if (!$payroll) {
            return [
                "errors" => [
                    "No payroll found."
                ]
            ];
        }
        // set the company details
        $this->setCompanyDetails($payroll["company_sid"]);
        // add payroll uuid
        $this->gustoCompany['other_uuid']
            = $payroll['gusto_uuid'];
        //
        $request = [];
        $request['employee_compensations'] = $this->getEmployeeCompensationsForGustoByPayrollId(
            $payrollId
        );
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "payroll_update",
                $this->gustoCompany,
                $request,
                "PUT",
                true
            );
        //
        if (isset($response["errors"])) {
            return $response;
        }
        //
        $this->gustoToStoreRegularPayroll($response);
        //
        $this->updateEmployeeCompensations(
            $payrollId,
            $response["employee_compensations"]
        );
        return ["message" => "Your requests has been processed."];
    }

    public function gustoToStorePayrollById(
        string $payrollUUID
    ) {
        // get single payroll
        $payroll = $this->db
            ->select('
                sid,
                gusto_uuid,
                company_sid
            ')
            ->where('gusto_uuid', $payrollUUID)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        if (!$payroll) {
            return [
                "errors" => [
                    "No payroll found."
                ]
            ];
        }
        // set the company details
        $this->setCompanyDetails($payroll["company_sid"]);
        // add payroll uuid
        $this->gustoCompany['other_uuid']
            = $payroll['gusto_uuid'];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "payroll_get",
                $this->gustoCompany,
                [
                    "include=benefits,deductions,taxes,company_taxes"
                ],
                "GET",
                true
            );
        //
        if (isset($response["errors"])) {
            return $response;
        }
        //
        $this->gustoToStoreRegularPayroll($response);
        //
        $this->updateEmployeeCompensations(
            $payroll["sid"],
            $response["employee_compensations"]
        );
        _e($response);
    }

    /**
     * get employee compensations for Gusto
     *
     * @param int $payrollId
     * @return array
     */
    private function getEmployeeCompensationsForGustoByPayrollId(
        int $payrollId
    ): array {
        // get all the employee compensations
        $records = $this
            ->db
            ->select([
                "data_json"
            ])
            ->where(
                "regular_payroll_sid",
                $payrollId
            )
            ->get(
                "payrolls.regular_payrolls_employees"
            )
            ->result_array();
        // set request compensation
        $requests = [];
        //
        foreach ($records as $v0) {
            //
            $v0 = json_decode(
                $v0["data_json"],
                true
            );
            //
            unset(
                $v0["reimbursement_json"],
                $v0["additional_earnings"],
                $v0["deductions_json"],
                $v0["data"],
            );
            $v0["excluded"] = $v0["excluded"] == "true" ? true : false;
            //
            $requests[] = $v0;
        }
        return $requests;
    }

    /**
     * update employee compensations for Gusto
     *
     * @param array $response
     * @return bool
     */
    private function gustoToStoreRegularPayroll(
        array $response
    ): bool {
        // check and insert
        // lets update the main table
        $upd = [
            "fixed_compensations_json" => json_encode(
                $response["fixed_compensation_types"]
            ),
            "check_date" => $response["check_date"],
            "processed" => $response["processed"],
            "processed_date" => $response["processed_date"],
            "calculated_at" => $response["calculated_at"],
            "start_date" => $response["pay_period"]["start_date"],
            "end_date" => $response["pay_period"]["end_date"],
            "payroll_deadline" => $response["payroll_deadline"],
            "payroll_status_meta" => json_encode(
                $response["payroll_status_meta"]
            ),
            "updated_at" => getSystemDate(),
        ];
        // check and add extras
        if ($response["totals"]) {
            $upd["totals"] = json_encode($response["totals"]);
        }
        if ($response["submission_blockers"]) {
            $upd["submission_blockers"] = json_encode($response["submission_blockers"]);
        }
        if ($response["credit_blockers"]) {
            $upd["credit_blockers"] = json_encode($response["credit_blockers"]);
        }
        //
        $this->db
            ->where('gusto_uuid', $response["payroll_uuid"])
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );
        //
        return true;
    }

    /**
     * get employee compensations for Gusto
     *
     * @param int $payrollId
     * @return bool
     */
    private function updateEmployeeCompensations(
        int $payrollId,
        array $payrollEmployeeCompensations
    ): bool {
        // check for empty
        if (!$payrollEmployeeCompensations) {
            return false;
        }
        // iterate
        foreach ($payrollEmployeeCompensations as $v0) {
            // get the employee id
            $employeeId = $this
                ->db
                ->select('employee_sid')
                ->where('gusto_uuid', $v0['employee_uuid'])
                ->get('gusto_companies_employees')
                ->row_array()['employee_sid'];
            //
            if (!$employeeId) {
                continue;
            }
            // set where array
            $whereArray = [
                'regular_payroll_sid' => $payrollId,
                'employee_sid' => $employeeId
            ];
            //
            $dataArray = [];
            $dataArray['data_json'] = json_encode($v0);
            $dataArray['is_skipped'] = $v0['excluded'];
            $dataArray['version'] = $v0['version'];
            $dataArray['note'] = $v0['memo'];
            $dataArray['payment_method'] = $v0['payment_method'];
            $dataArray['updated_at'] = getSystemDate();
            // check for extra
            if ($v0["gross_pay"]) {
                $dataArray["gross_pay"] = $v0["gross_pay"];
            }
            if ($v0["net_pay"]) {
                $dataArray["net_pay"] = $v0["net_pay"];
            }
            if ($v0["check_amount"]) {
                $dataArray["check_amount"] = $v0["check_amount"];
            }
            //
            if (
                !$this
                    ->db
                    ->where($whereArray)
                    ->count_all_results('payrolls.regular_payrolls_employees')
            ) {
                // insert
                $dataArray['regular_payroll_sid'] = $payrollId;
                $dataArray['employee_sid'] = $whereArray['employee_sid'];
                $dataArray['created_at'] = $dataArray['updated_at'];
                //
                $this->db
                    ->insert(
                        'payrolls.regular_payrolls_employees',
                        $dataArray
                    );
            } else {
                // update
                $this->db
                    ->where($whereArray)
                    ->update(
                        'payrolls.regular_payrolls_employees',
                        $dataArray
                    );
            }
        }
        //
        return true;
    }

    /**
     * check and prepare payroll
     *
     * @method checkAndGetPayrollStatus
     * @param int $payrollId
     * @param int $companyId
     */
    public function checkAndPreparePayroll(
        int $payrollId,
        int $companyId
    ) {
        // get the payroll status
        $payrollStatus = $this->checkAndGetPayrollStatus(
            $payrollId,
            $companyId
        );
        // we need to prepare it
        if ($payrollStatus === "prepare") {
            // prepare the payroll
            // this action will fetch all the
            // employee compensations from
            // Gusto and attach them to payroll
            $this
                ->setCompanyDetails($companyId)
                ->preparePayrollForUpdate($payrollId);
        } elseif ($payrollStatus === "not_found") {
            return redirect("payrolls/regular");
        }
    }


    /**
     * calculate payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function calculatePayrollById(int $payrollId)
    {
        // get single payroll
        $payroll = $this->db
            ->select('
                gusto_uuid,
                company_sid
            ')
            ->where('sid', $payrollId)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        if (!$payroll) {
            return [
                "errors" => [
                    "No payroll found."
                ]
            ];
        }
        // set the company details
        $this->setCompanyDetails($payroll["company_sid"]);
        // add payroll uuid
        $this->gustoCompany['other_uuid']
            = $payroll['gusto_uuid'];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "calculate_update",
                $this->gustoCompany,
                [],
                "PUT",
                true
            );
        //
        if (isset($response["errors"])) {
            return $response;
        }
        //
        $this
            ->db
            ->where(
                "sid",
                $payrollId
            )
            ->update(
                "payrolls.regular_payrolls",
                [
                    "status" => "calculating",
                    "updated_at" => getSystemDate()
                ]
            );
        //
        return ["message" => "Your requests has been processed."];
    }

    /**
     * get the regular payroll stage
     *
     * @param int $payrollId
     * @param string $stage
     * @return int
     */
    public function verifyPayrollStage(
        int $payrollId,
        string $stage
    ) {
        return $this
            ->db
            ->where([
                "sid" => $payrollId,
                "status <>" => $stage
            ])
            ->count_all_results("payrolls.regular_payrolls");
    }

    /**
     * revert payroll
     *
     * @param int $regularPayrollId
     * @return array
     */
    public function discardPayrollChanges(
        int $regularPayrollId
    ): array {
        // check if payroll exists
        if (!$this->db
            ->where('sid', $regularPayrollId)
            ->count_all_results("payrolls.regular_payrolls")) {
            return ["errors" => ['Unable to verify the payroll.']];
        }
        // verify the stage
        if (!$this->db
            ->where('sid', $regularPayrollId)
            ->where('status', "calculating")
            ->count_all_results("payrolls.regular_payrolls")) {
            return ["errors" => ['Unable to discard changes.']];
        }
        //
        $payroll = $this->db
            ->select("company_sid")
            ->where('sid', $regularPayrollId)
            ->get("payrolls.regular_payrolls")
            ->row_array();
        //
        $this->setCompanyDetails($payroll["company_sid"]);
        // flush data
        $response = $this->preparePayrollForUpdate($regularPayrollId);
        //
        if (is_array($response) && isset($response["errors"])) {
            return $response;
        }
        return [
            "message" => "You have successfully discarded the payroll data."
        ];
    }

    /**
     * get payroll blocker
     *
     * @param int $companyId
     * @return array
     */
    public function getRegularPayrollBlocker(int $companyId): array
    {
        $this->setCompanyDetails(
            $companyId
        );
        //
        // get the blockers
        $gustoResponse = $this
            ->lb_gusto
            ->gustoCall(
                "getPayrollBlockers",
                $this->gustoCompany,
                [],
                "GET",
                true
            );
        //
        if (isset($gustoResponse["errors"])) {
            return $gustoResponse['errors'];
        }
        // check if already exists
        if ($this->db->where('company_sid', $companyId)->count_all_results('payrolls.payroll_blockers')) {
            $this->db
                ->where('company_sid', $companyId)
                ->update('payrolls.payroll_blockers', [
                    'updated_at' => getSystemDate(),
                    'blocker_json' => json_encode($gustoResponse),
                ]);
        } else {
            $this->db
                ->insert('payrolls.payroll_blockers', [
                    'company_sid' => $companyId,
                    'created_at' => getSystemDate(),
                    'updated_at' => getSystemDate(),
                    'blocker_json' => json_encode($gustoResponse),
                ]);
        }
        //
        return [
            'success' => true,
            'data' => $gustoResponse
        ];
    }
}
