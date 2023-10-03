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
     * @param array $filter Optional
     * @return array
     */
    public function getCompanyPayStubs(
        int $companyId,
        int $limit = 0,
        array $filter = []
    ): array {
        $this->db
            ->select('
                payrolls.regular_payrolls.start_date,
                payrolls.regular_payrolls.end_date,
                payrolls.regular_payrolls.check_date,
                payrolls.regular_payrolls.sid
            ')
            ->join(
                'payrolls.regular_payrolls',
                'payrolls.regular_payrolls.sid = payrolls.regular_payrolls_employees.regular_payroll_sid',
                'inner'
            )
            ->where('payrolls.regular_payrolls.processed_date <>', null)
            ->where('payrolls.regular_payrolls.company_sid', $companyId)
            ->order_by('payrolls.regular_payrolls.processed_date', 'DESC');
        //
        if ($filter['employeeIds']) {
            $this->db->where_in('payrolls.regular_payrolls_employees.employee_sid', $filter['employeeIds']);
        }
        //
        if ($filter['sid']) {
            $this->db->where_in('payrolls.regular_payrolls.sid', $filter['sid']);
        }
        $records = $this->db->get('payrolls.regular_payrolls_employees')
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
            $slug = $value['sid'];
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

    /**
     * get pay stubs
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyPayStubsFilter(
        int $companyId
    ): array {
        //
        $returnArray = [
            'payPeriods' => [],
            'checkDates' => []
        ];
        //
        $records = $this->db
            ->select('
                payrolls.regular_payrolls.sid,
                payrolls.regular_payrolls.start_date,
                payrolls.regular_payrolls.end_date,
                payrolls.regular_payrolls.check_date
            ')
            ->where('payrolls.regular_payrolls.processed_date <>', null)
            ->where('payrolls.regular_payrolls.company_sid', $companyId)
            ->order_by('payrolls.regular_payrolls.processed_date', 'DESC')
            ->get('payrolls.regular_payrolls')
            ->result_array();
        //
        if (!$records) {
            return $returnArray;
        }
        //
        foreach ($records as $value) {
            //
            $slug = $value['check_date'];
            $slug2 = $value['start_date'] . ' - ' . $value['end_date'];
            //
            if (!isset($returnArray['payPeriods'][$slug2])) {
                $returnArray['payPeriods'][$slug2] = [
                    'sid' => $value['sid'],
                    'start_date' => $value['start_date'],
                    'end_date' => $value['end_date']
                ];
            }
            //
            if (!isset($returnArray['checkDates'][$slug])) {
                $returnArray['checkDates'][$slug] = [
                    'sid' => $value['sid'],
                    'check_date' => $value['check_date']
                ];
            }
        }
        //
        $returnArray['payPeriods'] = array_values($returnArray['payPeriods']);
        $returnArray['checkDates'] = array_values($returnArray['checkDates']);
        //
        return $returnArray;
    }


    /**
     * get pay stub with employees
     *
     * @param int $periodId
     * @param int $companyId
     * @param array $employeeIds
     * @return array
     */
    public function getPayStubWithEmployeesById(
        int $periodId,
        int $companyId,
        array $employeeIds
    ): array {
        // set return array
        $returnArray = [];
        // get the pay stub
        $record = $this->db
            ->select('
                payrolls.regular_payrolls.start_date,
                payrolls.regular_payrolls.end_date,
                payrolls.regular_payrolls.check_date
            ')
            ->join(
                'payrolls.regular_payrolls',
                'payrolls.regular_payrolls.sid = payrolls.regular_payrolls_employees.regular_payroll_sid',
                'inner'
            )
            ->where('payrolls.regular_payrolls.processed_date <>', null)
            ->where('payrolls.regular_payrolls.company_sid', $companyId)
            ->where('payrolls.regular_payrolls.sid', $periodId)
            ->get('payrolls.regular_payrolls_employees')
            ->row_array();
        //
        if (!$record) {
            return [];
        }
        //
        $returnArray = $record;
        // get the period employees pay stubs
        $this->db
            ->select(
                getUserFields() . '
                payrolls.regular_payrolls_employees.sid,
                payrolls.regular_payrolls_employees.paystub_json
            '
            )
            ->join(
                "users",
                "users.sid = payrolls.regular_payrolls_employees.employee_sid",
                "inner"
            )
            ->where('payrolls.regular_payrolls_employees.regular_payroll_sid', $periodId);
        //
        if (!in_array('all', $employeeIds)) {
            $this->db->where_in('payrolls.regular_payrolls_employees.employee_sid', $employeeIds);
        }
        //
        $returnArray['employees'] = $this->db
            ->get('payrolls.regular_payrolls_employees')
            ->result_array();
        //
        return $returnArray;
    }
}
