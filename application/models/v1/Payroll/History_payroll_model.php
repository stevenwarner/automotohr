<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel("v1/Payroll/Base_payroll_model", "base_payroll_model");
/**
 * History payroll model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class History_payroll_model extends Base_payroll_model
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
     * get processed regular payrolls
     *
     * @param int $companyId
     * @param int $limit Optional
     * @return array
     */
    public function getProcessedPayrolls(
        int $companyId,
        int $limit = 0
    ): array {
        //
        $this->db
            ->select('
                sid,
                check_date,
                payroll_deadline,
                off_cycle,
                off_cycle_reason,
                start_date,
                end_date,
                totals
            ')
            ->where('company_sid', $companyId)
            ->where('processed', 1)
            ->where('is_cancelled', 0)
            ->order_by('processed_date', 'DESC');
        //
        if ($limit !== 0) {
            $this->db->limit($limit);
        }

        //
        $records = $this->db->get('payrolls.regular_payrolls')
            ->result_array();
        //
        if ($records) {
            foreach ($records as $key => $value) {
                $records[$key]['totals'] = json_decode(
                    $value['totals'],
                    true
                );
            }
        }
        //
        return $records;
    }

    /**
     * get processed regular payrolls
     *
     * @param int $companyId
     * @param int $limit Optional
     * @return array
     */
    public function getProcessedRegularPayrolls(
        int $companyId,
        int $limit = 0
    ): array {
        //
        $this->db
            ->select('
                sid,
                check_date,
                payroll_deadline,
                start_date,
                end_date,
                totals
            ')
            ->where('company_sid', $companyId)
            ->where('processed', 1)
            ->where('is_cancelled', 0)
            ->where('off_cycle', 0)
            ->order_by('processed_date', 'DESC');
        //
        if ($limit !== 0) {
            $this->db->limit($limit);
        }

        //
        $records = $this->db->get('payrolls.regular_payrolls')
        ->result_array();
        //
        if ($records) {
            foreach ($records as $key => $value) {
                $records[$key]['totals'] = json_decode(
                    $value['totals'],
                    true
                );
            }
        }
        //
        return $records;
    }

    /**
     * get processed regular payrolls
     *
     * @param int $companyId
     * @param int $limit Optional
     * @return array
     */
    public function getProcessedOffcyclePayrolls(
        int $companyId,
        int $limit = 0
    ): array {
        //
        $this->db
            ->select('
                sid,
                check_date,
                payroll_deadline,
                off_cycle_reason,
                start_date,
                end_date,
                totals
            ')
            ->where('company_sid', $companyId)
            ->where('processed', 1)
            ->where('is_cancelled', 0)
            ->where('off_cycle', 1)
            ->order_by('processed_date', 'DESC');
        //
        if ($limit !== 0) {
            $this->db->limit($limit);
        }

        //
        $records = $this->db->get('payrolls.regular_payrolls')
        ->result_array();
        //
        if ($records) {
            foreach ($records as $key => $value) {
                $records[$key]['totals'] = json_decode(
                    $value['totals'],
                    true
                );
            }
        }
        //
        return $records;
    }

    /**
     * get single processed payroll
     *
     * @param int $payrollId
     * @param int $companyId
     * @return array
     */
    public function getPayrollById(
        int $payrollId,
        int $companyId
    ): array {
        //
        $record = $this->db
            ->select('
                sid,
                start_date,
                end_date,
                check_date,
                off_cycle,
                off_cycle_reason,
                payroll_deadline,
                processed_date,
                payroll_receipt
            ')
            ->where('sid', $payrollId)
            ->where('company_sid', $companyId)
            ->where('processed', 1)
            ->where('is_cancelled', 0)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        if (!$record) {
            return $record;
        }
        // convert receipt
        $record['payroll_receipt'] = json_decode(
            $record['payroll_receipt'],
            true
        );
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
     * @param array $employeeIds
     * @return array
     */
    public function getSpecificPayrollEmployees(
        array $employeeIds
    ): array {
        //
        $records = $this->db
            ->select(getUserFields())
            ->join('users', 'users.sid = gusto_companies_employees.employee_sid', 'inner')
            ->where_in('gusto_companies_employees.employee_sid', $employeeIds)
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
}
