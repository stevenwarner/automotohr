<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_model', 'Payroll_model');
/**
 * Pay stubs model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Pay_stubs_model extends Payroll_model
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
     * get pay stubs count
     *
     * @param int $employeeId
     * @return array
     */
    public function getMyPayStubsCount(
        int $employeeId
    ): int {
        return $this->db
            ->join(
                'payrolls.regular_payrolls',
                'payrolls.regular_payrolls.sid = payrolls.regular_payrolls_employees.regular_payroll_sid',
                'inner'
            )
            ->where('payrolls.regular_payrolls.processed_date <>', null)
            ->where('payrolls.regular_payrolls_employees.employee_sid', $employeeId)
            ->count_all_results('payrolls.regular_payrolls_employees');
    }

    /**
     * get pay stubs
     *
     * @param int $employeeId
     * @param int $companyId
     * @return array
     */
    public function getPayStubs(
        int $employeeId,
        int $companyId
    ): array {
        $records = $this->db
            ->select('
                payrolls.regular_payrolls.start_date,
                payrolls.regular_payrolls.end_date,
                payrolls.regular_payrolls.check_date,
                payrolls.regular_payrolls_employees.sid
            ')
            ->join(
                'payrolls.regular_payrolls',
                'payrolls.regular_payrolls.sid = payrolls.regular_payrolls_employees.regular_payroll_sid',
                'inner'
            )
            ->where('payrolls.regular_payrolls.processed_date <>', null)
            ->where('payrolls.regular_payrolls_employees.employee_sid', $employeeId)
            ->where('payrolls.regular_payrolls.company_sid', $companyId)
            ->order_by('payrolls.regular_payrolls.processed_date', 'DESC')
            ->get('payrolls.regular_payrolls_employees')
            ->result_array();
        //
        return $records;
    }

    /**
     * get single pay stub
     *
     * @param int $payStubId
     * @param int $companyId
     * @return array
     */
    public function getSinglePayStub(
        int $payStubId,
        int $companyId
    ): array {
        $record = $this->db
            ->select('
                payrolls.regular_payrolls.start_date,
                payrolls.regular_payrolls.end_date,
                payrolls.regular_payrolls.check_date,
                payrolls.regular_payrolls_employees.paystub_json,
                payrolls.regular_payrolls_employees.sid
            ')
            ->join(
                'payrolls.regular_payrolls',
                'payrolls.regular_payrolls.sid = payrolls.regular_payrolls_employees.regular_payroll_sid',
                'inner'
            )
            ->where('payrolls.regular_payrolls_employees.sid', $payStubId)
            ->where('payrolls.regular_payrolls.company_sid', $companyId)
            ->get('payrolls.regular_payrolls_employees')
            ->row_array();
        //
        if ($record) {
            $record['paystub_json'] = json_decode(
                $record['paystub_json'],
                true
            );
        }
        //
        return $record;
    }

    /**
     * get pay stubs
     *
     * @param int $companyId
     * @param int $limit Optional
     * @return array
     */
    public function getCompanyPayStubs(
        int $companyId,
        int $limit = 0
    ): array {
        $records = $this->db
            ->select('
                payrolls.regular_payrolls.start_date,
                payrolls.regular_payrolls.end_date,
                payrolls.regular_payrolls.check_date,
                payrolls.regular_payrolls_employees.sid
            ')
            ->join(
                'payrolls.regular_payrolls',
                'payrolls.regular_payrolls.sid = payrolls.regular_payrolls_employees.regular_payroll_sid',
                'inner'
            )
            ->where('payrolls.regular_payrolls.processed_date <>', null)
            ->where('payrolls.regular_payrolls.company_sid', $companyId)
            ->order_by('payrolls.regular_payrolls.processed_date', 'DESC')
            ->get('payrolls.regular_payrolls_employees')
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        $payStubs = [];
        //
        foreach ($records as $value) {
            //
            $slug = $value['check_date'];
            //
            if (!isset($payStubs[$slug])) {
                //
                if ($limit != 0 && count($payStubs) === $limit) {
                    continue;
                }
                $payStubs[$slug] = $value;
                $payStubs[$slug]['count'] = 0;
            }
            $payStubs[$slug]['count']++;
        }
        //
        return array_values($payStubs);
    }
}
