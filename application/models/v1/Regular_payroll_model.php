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
        return ['data' => []];
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
     * @param int $loggedInEmployeeId
     * @return array
     */
    public function getRegularPayrolls(int $companyId): array
    {
        //
        $returnArray = [
            'current' => [],
            'late' => []
        ];
        // get payrolls
        $records = $this->db
            ->select('
                sid,
                start_date,
                end_date,
                check_date,
                is_late_payroll
            ')
            ->where('company_sid', $companyId)
            ->where('processed', 0)
            ->get('payrolls.regular_payrolls')
            ->result_array();
        //
        if (!$records) {
            return $returnArray;
        }
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
        // get the employees
        $employees = $this->db
            ->select('employee_sid, data_json')
            ->where('regular_payroll_sid', $payrollId)
            ->get('payrolls.regular_payrolls_employees')
            ->result_array();
        //
        $record['employees'] = $employees ?? [];
        //
        return $record;
    }
}
