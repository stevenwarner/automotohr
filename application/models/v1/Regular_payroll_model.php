<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_model', 'Payroll_model');
/**
 * Regular payroll model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Regular_payroll_model extends Payroll_model
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
     * get payroll blocker
     *
     * @param int $companyId
     * @return array
     */
    public function getRegularPayrollBlocker(int $companyId): array
    {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto(
            $companyId
        );
        // get the blockers
        $gustoResponse = gustoCall(
            'getPayrollBlockers',
            $companyDetails,
            [],
            "GET"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // return errors if any
        if ($errors) {
            return $errors;
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

    /**
     * sync regular payroll
     *
     * @param int $companyId
     * @param int $loggedInEmployeeId
     * @return array
     */
    public function syncRegularPayrolls(int $companyId, int $loggedInEmployeeId): array
    {
        // get the last start date from payrolls
        $record = $this->db
            ->select('start_date')
            ->where('company_sid', $companyId)
            ->where('is_late_payroll', 0)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        $startDate = $record ? $record['start_date'] : (getSystemDate('Y')) . '-01-01';
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto(
            $companyId
        );
        //
        $companyDetails['other_uuid'] = $startDate;
        // get the blockers
        $gustoResponse = gustoCall(
            'getUnprocessedPayrollsByStartDate',
            $companyDetails,
            [],
            "GET"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // return errors if any
        if ($errors) {
            return $errors;
        }
        //
        $currentPayroll = [];
        //
        if ($gustoResponse) {
            //
            $lastIndex = count($gustoResponse) - 1;
            //
            $currentPayroll = $gustoResponse[$lastIndex];
            //
            foreach ($gustoResponse as $key => $value) {
                // set insert array
                $ins = [];
                $ins['check_date'] = $value['check_date'];
                $ins['payroll_deadline'] = $value['payroll_deadline'];
                $ins['processed'] = $value['processed'];
                $ins['processed_date'] = $value['processed_date'];
                $ins['calculated_at'] = $value['calculated_at'];
                $ins['updated_at'] = getSystemDate();
                $ins['last_changed_by'] = $loggedInEmployeeId;
                $ins['is_late_payroll'] = $lastIndex === $key ? 0 : 1;
                // check if already exists
                if (
                    $this->db
                    ->where('gusto_uuid', $value['payroll_uuid'])
                    ->count_all_results('payrolls.regular_payrolls')
                ) {
                    // update it
                    $this->db
                        ->where('gusto_uuid', $value['payroll_uuid'])
                        ->update('payrolls.regular_payrolls', $ins);
                } else {
                    //
                    $ins['company_sid'] = $companyId;
                    $ins['gusto_uuid'] = $value['payroll_uuid'];
                    $ins['start_date'] = $value['pay_period']['start_date'];
                    $ins['end_date'] = $value['pay_period']['end_date'];
                    $ins['gusto_pay_schedule_uuid'] = $value['pay_period']['pay_schedule_uuid'];
                    $ins['created_at'] = $ins['updated_at'];
                    // insert it
                    $this->db
                        ->insert('payrolls.regular_payrolls', $ins);
                }
            }
        }
        //
        return [
            'success' => true,
            'data' => $currentPayroll
        ];
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
                is_late_payroll
            ')
            ->where('company_sid', $companyId)
            ->where('processed', 0)
            ->order_by('is_late_payroll', 'ASC')
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
        $this->db
            ->where("processed", 0)
            ->where("company_sid", $companyId)
            ->update("payrolls.regular_payrolls", [
                "version" => null
            ]);
        //
        foreach ($records as $record) {
            if ($record['is_late_payroll']) {
                $returnArray['late'][] = $record;
            } else {
                $returnArray['current'] = $record;
            }
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
            ->select('
                version
            ')
            ->where('company_sid', $companyId)
            ->where('sid', $payrollId)
            ->where('processed', 0)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        if (!$record) {
            return 'not_found';
        }
        //
        return !$record['version'] ? 'prepare' : 'success';
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
     * @return array
     */
    public function getRegularPayrollById(
        int $companyId,
        int $payrollId
    ): array {
        // get single payroll
        $record = $this->db
            ->select('
                sid,
                gusto_uuid,
                start_date,
                end_date,
                check_date,
                payroll_deadline,
                totals,
                is_late_payroll
            ')
            ->where('company_sid', $companyId)
            ->where('sid', $payrollId)
            ->where('processed', 0)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        if (!$record) {
            return [];
        }
        $record['totals'] = json_decode($record['totals'], true);
        if (!$this->db
            ->where('regular_payroll_sid', $payrollId)
            ->count_all_results('payrolls.regular_payrolls_employees')) {
            // check payroll employees
            $this->getPayrollById($payrollId);
        }
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
                // add indexes
                if ($dataArray['fixed_compensations']) {
                    //
                    $tmp = [];
                    //
                    foreach ($dataArray['fixed_compensations'] as $v0) {
                        $tmp[stringToSlug($v0['name'], '_')] = $v0;
                    }
                    $dataArray['fixed_compensations'] = $tmp;
                }
                //
                if ($dataArray['hourly_compensations']) {
                    //
                    $tmp = [];
                    //
                    foreach ($dataArray['hourly_compensations'] as $v1) {
                        $tmp[stringToSlug($v1['name'], '_')] = $v1;
                    }
                    $dataArray['hourly_compensations'] = $tmp;
                }
                //
                $dataArray['v1'] = [];
                $dataArray['v1']['reimbursements'] = $value['reimbursement_json'] ? json_decode($value['reimbursement_json'], true) : ['total' => 0, 'data' => []];
                $dataArray['v1']['additional_earnings'] = $value['additional_earnings'] ? json_decode($value['additional_earnings'], true) : [];
                $dataArray['v1']['deductions'] = $value['deductions_json'] ? json_decode($value['deductions_json'], true) : [];
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


    // Gusto Calls

    /**
     * prepare payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function preparePayrollForUpdate(int $payrollId): array
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
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($payroll['company_sid']);
        // add payroll uuid
        $companyDetails['other_uuid'] = $payroll['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            'preparePayrollForUpdate',
            $companyDetails,
            [],
            "PUT"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        // update the payroll details
        $upd = [];
        $upd['version'] = $gustoResponse['version'];
        $upd['gusto_uuid'] = $gustoResponse['payroll_uuid'];
        $upd['check_date'] = $gustoResponse['check_date'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $payrollId)
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );
        // sync the employees
        if ($gustoResponse['employee_compensations']) {
            foreach ($gustoResponse['employee_compensations'] as $value) {
                //
                $employeeId = $this->db->select('employee_sid')->where('gusto_uuid', $value['employee_uuid'])->get('gusto_companies_employees')->row_array()['employee_sid'];
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
                $dataArray['payment_method'] = $value['payment_method'];
                $dataArray['updated_at'] = getSystemDate();
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
        return $gustoResponse;
    }

    /**
     * update payroll
     *
     * @param int $payrollId
     * @param array $payrollEmployees
     * @return array
     */
    public function updatePayrollById(int $payrollId, array $payrollEmployees): array
    {
        // get single payroll
        $payroll = $this->db
            ->select('
                gusto_uuid,
                version,
                company_sid
            ')
            ->where('sid', $payrollId)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($payroll['company_sid']);
        // add payroll uuid
        $companyDetails['other_uuid'] = $payroll['gusto_uuid'];
        //
        $request = [];
        $request['version'] = $payroll['version'];
        $request['employee_compensations'] = $payrollEmployees;
        //
        $gustoResponse = gustoCall(
            'updateRegularPayrollById',
            $companyDetails,
            $request,
            "PUT"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        // update the payroll details
        $upd = [];
        $upd['version'] = $gustoResponse['version'];
        $upd['gusto_uuid'] = $gustoResponse['payroll_uuid'];
        $upd['check_date'] = $gustoResponse['check_date'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $payrollId)
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );
        // // sync the employees
        if ($gustoResponse['employee_compensations']) {
            foreach ($gustoResponse['employee_compensations'] as $value) {
                // set where array
                $whereArray = [
                    'regular_payroll_sid' => $payrollId,
                    'employee_sid' => $this->db->select('employee_sid')->where('gusto_uuid', $value['employee_uuid'])->get('gusto_companies_employees')->row_array()['employee_sid']
                ];
                //
                $dataArray = [];
                $dataArray['data_json'] = json_encode($value);
                $dataArray['is_skipped'] = $value['excluded'];
                $dataArray['payment_method'] = $value['payment_method'];
                $dataArray['updated_at'] = getSystemDate();
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
        return $gustoResponse;
    }

    /**
     * sync payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function syncPayrollVersion(int $payrollId): array
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
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($payroll['company_sid']);
        // add payroll uuid
        $companyDetails['other_uuid'] = $payroll['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            'getSinglePayrollById',
            $companyDetails,
            [],
            "GET"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        $upd = [];
        $upd['version'] = $gustoResponse['version'];
        $upd['gusto_uuid'] = $gustoResponse['payroll_uuid'];
        $upd['check_date'] = $gustoResponse['check_date'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $payrollId)
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );
        return $gustoResponse;
    }

    /**
     * calculate single payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function calculatePayrollById(int $payrollId): array
    {
        if ($this->db
            ->where('sid', $payrollId)
            ->where('version is null', null)
            ->count_all_results('payrolls.regular_payrolls')
        ) {
            return ['success' => false];
        }
        // get single payroll
        $payroll = $this->db
            ->select('
                gusto_uuid,
                company_sid
            ')
            ->where('sid', $payrollId)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($payroll['company_sid']);
        // add payroll uuid
        $companyDetails['other_uuid'] = $payroll['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            'calculateSinglePayroll',
            $companyDetails,
            [],
            "PUT"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        $upd = [];
        $upd['version'] = null;
        //
        $this->db
            ->where('sid', $payrollId)
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );

        //
        return $gustoResponse;
    }

    /**
     * get single payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function getPayrollById(int $payrollId): array
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
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($payroll['company_sid']);
        // add payroll uuid
        $companyDetails['other_uuid'] = $payroll['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            'getSinglePayrollById',
            $companyDetails,
            [],
            "GET"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        $upd = [];
        $upd['calculated_at'] = $gustoResponse['calculated_at'];
        $upd['totals'] = json_encode($gustoResponse['totals']);
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $payrollId)
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );
        //
        if ($gustoResponse['employee_compensations']) {
            foreach ($gustoResponse['employee_compensations'] as $value) {
                // set where array
                $whereArray = [
                    'regular_payroll_sid' => $payrollId,
                    'employee_sid' => $this->db->select('employee_sid')->where('gusto_uuid', $value['employee_uuid'])->get('gusto_companies_employees')->row_array()['employee_sid']
                ];
                //
                $dataArray = [];
                $dataArray['data_json'] = json_encode($value);
                $dataArray['is_skipped'] = $value['excluded'];
                $dataArray['payment_method'] = $value['payment_method'];
                $dataArray['updated_at'] = getSystemDate();
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
        return $gustoResponse;
    }

    /**
     * submit single payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function submitPayroll(int $payrollId): array
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
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($payroll['company_sid']);
        // add payroll uuid
        $companyDetails['other_uuid'] = $payroll['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            'submitSinglePayrollById',
            $companyDetails,
            [],
            "PUT"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        // sync payroll
        $this->syncPayroll($payrollId, $companyDetails);
        // sync receipt
        $this->syncPayrollRecept($payrollId, $companyDetails);
        // sync employee pay stubs
        $this->syncPayrollEmployeePayStubs($payrollId, $companyDetails);
        //
        return $gustoResponse;
    }

    /**
     * cancel single payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function cancelPayroll(int $payrollId): array
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
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($payroll['company_sid']);
        // add payroll uuid
        $companyDetails['other_uuid'] = $payroll['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            'cancelSinglePayrollById',
            $companyDetails,
            [],
            "PUT"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        $upd = [];
        $upd['off_cycle'] = $gustoResponse['off_cycle'];
        $upd['processed'] = $gustoResponse['processed'];
        $upd['processed_date'] = $gustoResponse['processed_date'];
        $upd['calculated_at'] = $gustoResponse['calculated_at'];
        $upd['payroll_deadline'] = $gustoResponse['payroll_deadline'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $payrollId)
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );
        //
        return $gustoResponse;
    }

    /**
     * sync payroll
     *
     * @param int $payrollId
     * @param array $companyDetails
     * @return array
     */
    private function syncPayroll(int $payrollId, array $companyDetails): array
    {
        //
        $gustoResponse = gustoCall(
            'getSinglePayrollById',
            $companyDetails,
            [],
            "GET"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse['processed']) {
            return $this->syncPayroll($payrollId, $companyDetails);
        }
        //
        $upd = [];
        $upd['off_cycle'] = $gustoResponse['off_cycle'];
        $upd['processed'] = $gustoResponse['processed'];
        $upd['processed_date'] = $gustoResponse['processed_date'];
        $upd['calculated_at'] = $gustoResponse['calculated_at'];
        $upd['payroll_deadline'] = $gustoResponse['payroll_deadline'];
        $upd['payroll_status_meta'] = json_encode($gustoResponse['payroll_status_meta']);
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $payrollId)
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );
        //
        return $gustoResponse;
    }

    /**
     * sync payroll receipt
     *
     * @param int $payrollId
     * @param array $companyDetails
     * @return array
     */
    private function syncPayrollRecept(int $payrollId, array $companyDetails): array
    {
        //
        $gustoResponse = gustoCall(
            'getSinglePayrollReceipt',
            $companyDetails,
            [],
            "GET"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        $upd = [];
        $upd['payroll_receipt'] = json_encode($gustoResponse);
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $payrollId)
            ->update(
                'payrolls.regular_payrolls',
                $upd
            );
        //
        return $gustoResponse;
    }

    /**
     * sync payroll employee pay stubs
     *
     * @param int $payrollId
     * @param array $companyDetails
     * @return array
     */
    private function syncPayrollEmployeePayStubs(int $payrollId, array $companyDetails): array
    {
        // get all employees
        $employees = $this->db
            ->select('
                payrolls.regular_payrolls_employees.sid,
                gusto_companies_employees.gusto_uuid
            ')
            ->join(
                'gusto_companies_employees',
                'gusto_companies_employees.employee_sid = payrolls.regular_payrolls_employees.employee_sid',
                'inner'
            )
            ->where('payrolls.regular_payrolls_employees.regular_payroll_sid', $payrollId)
            ->get('payrolls.regular_payrolls_employees')
            ->result_array();
        //
        if (!$employees) {
            return ['success' => true];
        }
        //
        foreach ($employees as $value) {
            //
            $companyDetails['other_uuid_2'] = $value['gusto_uuid'];
            //
            $gustoResponse = gustoCall(
                'getSinglePayrollEmployeePayStubs',
                $companyDetails,
                [],
                "GET"
            );
            // check for errors
            $errors = hasGustoErrors($gustoResponse);
            // errors found
            if ($errors) {
                return $errors;
            }
            // //
            $upd = [];
            $upd['paystub_json'] = json_encode($gustoResponse);
            $upd['updated_at'] = getSystemDate();
            //
            $this->db
                ->where('sid', $value['sid'])
                ->update(
                    'payrolls.regular_payrolls_employees',
                    $upd
                );
        }
        //
        return
            ['success' => true];
    }


//
    public function getEmployeesLedger($company_sids = NULL, $between = '', $filterEmployees, $filterJobTitles,$filterDepartment, $limit = null, $start = null)
    {
        //
        $this->db->select('payroll_ledger.debit_amount');
        $this->db->select('payroll_ledger.credit_amount');
        $this->db->select('payroll_ledger.description');
        $this->db->select('payroll_ledger.is_deleted');
        $this->db->select('payroll_ledger.created_at');
        $this->db->select('payroll_ledger.updated_at');
        $this->db->select('users.sid');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');
        $this->db->select('users.access_level');
        $this->db->select('users.timezone');
        $this->db->select('users.is_executive_admin');
        $this->db->select('users.pay_plan_flag');
        $this->db->select('users.job_title');
        $this->db->select('users.joined_at');
        $this->db->select('users.rehire_date');
        $this->db->select('users.department_sid');
        //
        $this->db->join('users', 'users.sid = payroll_ledger.employee_sid', 'left');
        $this->db->where('users.parent_sid', $company_sids);
        //
        if ($between != '' && $between != NULL) {
            $this->db->where($between);
        }

        if (!in_array("all", $filterEmployees)) {

            $this->db->where_in('payroll_ledger.employee_sid', $filterEmployees);
        }

        if (!in_array("0", $filterDepartment)) {

            $this->db->where_in('users.department_sid', $filterDepartment);
        }

        if (!in_array("all", $filterJobTitles)) {

            $i = 0;
            $this->db->group_start();
            foreach ($filterJobTitles as $title) {
                if ($i == 0) {
                    $this->db->like('users.job_title', $title);
                } else {
                    $this->db->or_like('users.job_title', $title);
                }
                $i++;
            }
            $this->db->group_end();
        }

        //
        if ($limit != null) {
            $this->db->limit($limit, $start);
        }
        //  
        $this->db->order_by("payroll_ledger.sid", "desc");
        $employeesLedger = $this->db->get('payroll_ledger')->result_array();
        //
        $str = $this->db->last_query();

        return $employeesLedger;
    }

    //
    public function getCompanyEmployeesOnly(int $companyId): array
    {

        $this->db
            ->select(getUserFields())
            ->where([
                "users.parent_sid" => $companyId,
                "users.is_executive_admin" => 0,
                "users.active" => 1,
                "users.terminated_status" => 0
            ]);
        //

        $this->db->order_by("users.first_name", "ASC");
        return $this->db->get("users")->result_array();
    }
}
