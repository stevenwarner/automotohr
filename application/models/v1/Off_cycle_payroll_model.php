<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_model', 'Payroll_model');
/**
 * Off cycle payroll model
 *
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Off_cycle_payroll_model extends Payroll_model
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
     * check if unprocessed payroll already exists
     *
     * @param string $reason
     *               bonus -> Bonus
     *               corrections -> Correction
     *               dismissed-employee -> Dismissed employee
     *               transition -> Transition from old pay schedule
     * @param int    $companyId
     * @return int
     */
    public function alreadyExists(string $reason, int $companyId): int
    {
        //
        return $this->db
            ->where('off_cycle_reason', getReason($reason))
            ->where('company_sid', $companyId)
            ->where('processed', 0)
            ->where('gusto_version <>', null)
            ->count_all_results('payrolls.off_cycle_payrolls');
    }

    /**
     * saves the payroll with employees
     *
     * @param int $companyId
     * @param array $data
     *              employees
     *              reason
     * @return array
     */
    public function addPayrollWithEmployees(int $companyId, array $data): array
    {
        // flush the old record
        $payrollId = $this->checkIfExists($data['reason'], $companyId);
        // start the transaction
        $this->db->trans_begin();
        // if payroll id not found
        if ($payrollId == 0) {
            // prepare insert array
            $ins = [];
            $ins['company_sid'] = $companyId;
            $ins['off_cycle_reason'] = getReason($data['reason']);
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            // insert
            $this->db
                ->insert(
                    'payrolls.off_cycle_payrolls',
                    $ins
                );
            // get the last insert id
            $payrollId = $this->db->insert_id();
        } else {
            // remove all existing employees
            $this->db
                ->where('off_cycle_payroll_sid', $payrollId)
                ->delete('payrolls.off_cycle_payrolls_employees');
            // run update query
            $this->db
                ->where(
                    'sid',
                    $payrollId
                )
                ->update(
                    'payrolls.off_cycle_payrolls',
                    [
                        'updated_at' => getSystemDate()
                    ]
                );
        }
        //
        if (!$payrollId) {
            return ['errors' => ['Failed to save record.']];
        }
        //
        foreach ($data['employees'] as $value) {
            // prepare insert array
            $ins = [];
            $ins['off_cycle_payroll_sid'] = $payrollId;
            $ins['employee_sid'] = $value;
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            // insert
            $this->db
                ->insert(
                    'payrolls.off_cycle_payrolls_employees',
                    $ins
                );
        }
        // check if all went okay
        if ($this->db->trans_status() === false) {
            // revert the queries
            $this->db->trans_rollback();
            // generate response
            return [
                'errors' => [
                    "Failed to process request."
                ]
            ];
        }
        // commit the changes
        $this->db->trans_commit();
        //
        return ['success' => true, 'msg' => "You have successfully saved the employees."];
    }

    /**
     * get the schedule frequency
     *
     * @param int $companyId
     * @return string
     */
    public function getCompanySchedule(int $companyId): string
    {
        $record = $this->db
            ->select('frequency')
            ->where('company_sid', $companyId)
            ->get('companies_pay_schedules')
            ->row_array();
        //
        return $record ? $record['frequency'] : '';
    }

    /**
     * check if unprocessed payroll exists
     *
     * @param string $reason
     *               bonus -> Bonus
     *               corrections -> Correction
     *               dismissed-employee -> Dismissed employee
     *               transition -> Transition from old pay schedule
     * @param int    $companyId
     * @return int
     */
    private function checkIfExists(string $reason, int $companyId): int
    {
        $record = $this->db
            ->select('sid')
            ->where('off_cycle_reason', getReason($reason))
            ->where('company_sid', $companyId)
            ->where('gusto_version', null)
            ->get('payrolls.off_cycle_payrolls')
            ->row_array();
        //
        return $record ? $record['sid'] : 0;
    }
}
