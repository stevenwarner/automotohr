<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel("v1/Payroll/Base_payroll_model", "base_payroll_model");

/**
 * Off cycle payroll model
 *
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Off_cycle_payroll_model extends Base_payroll_model
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
     * process off cycle payroll
     *
     * @param int $companyId
     * @param array $data
     * @return array
     */
    public function processOffCyclePayroll(int $companyId, array $data): array
    {
        // flush the old record
        $payrollId = $this->getPayrollId($data['off_cycle_reason'], $companyId);
        //
        if ($payrollId != 0) {
            return ['errors' => [
                '"' . ($data['off_cycle_reason']) . '" is already inprogress.'
            ]];
        }
        //
        $gustoResponse = $this->createOffCyclePayrollOnGusto(
            $companyId,
            $data
        );
        //
        if ($gustoResponse['errors']) {
            return $gustoResponse;
        }
        // start the transaction
        $this->db->trans_begin();
        // if payroll id not found
        // prepare insert array
        $ins = [];
        $ins['company_sid'] = $companyId;
        $ins['gusto_uuid'] = $gustoResponse['response']['payroll_uuid'];
        $ins['version'] = $gustoResponse['response']['version'];
        $ins['off_cycle'] = $gustoResponse['response']['off_cycle'];
        $ins['off_cycle_reason'] = $gustoResponse['response']['off_cycle_reason'];
        $ins['start_date'] = $gustoResponse['response']['pay_period']['start_date'];
        $ins['end_date'] = $gustoResponse['response']['pay_period']['end_date'];
        $ins['check_date'] = $gustoResponse['response']['check_date'];
        $ins['payroll_deadline'] = $gustoResponse['response']['payroll_deadline'];
        $ins['processed'] = $gustoResponse['response']['processed'];
        $ins['processed_date'] = $gustoResponse['response']['processed_date'];
        $ins['calculated_at'] = $gustoResponse['response']['calculated_at'];
        $ins['payroll_status_meta'] = json_encode($gustoResponse['response']['payroll_status_meta']);
        $ins['withholding_pay_period'] = $gustoResponse['response']['withholding_pay_period'];
        $ins['fixed_withholding_rate'] = $gustoResponse['response']['fixed_withholding_rate'];
        $ins['skip_regular_deductions'] = $gustoResponse['response']['skip_regular_deductions'];
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        // insert
        $this->db
            ->insert(
                'payrolls.regular_payrolls',
                $ins
            );
        // get the last insert id
        $payrollId = $this->db->insert_id();
        //
        if (!$payrollId) {
            return ['errors' => ['Failed to save record.']];
        }
        //
        if ($gustoResponse['response']['employee_compensations']) {
            foreach ($gustoResponse['response']['employee_compensations'] as $value) {
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
        $gustoResponse['id'] = $payrollId;
        //
        unset($gustoResponse['response']);
        //
        return $gustoResponse;
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
    public function getPayrollId(string $reason, int $companyId): int
    {
        $record = $this->db
            ->select('sid')
            ->where('off_cycle', 1)
            ->where('processed', 0)
            ->where('off_cycle_reason', $reason)
            ->where('company_sid', $companyId)
            ->get('payrolls.regular_payrolls')
            ->row_array();
        //
        return $record ? $record['sid'] : 0;
    }

    /**
     * clear payroll draft data
     *
     * @param int $companyId
     * @param int $payrollId
     * @return array
     */
    public function clearDraftData(
        int $companyId,
        int $payrollId
    ): array {
        // check if exists
        if (!$this->db
            ->where('sid', $payrollId)
            ->where('company_sid', $companyId)
            ->count_all_results('payrolls.regular_payrolls')) {
            return ['errors' => ["Selected off_cycle payroll verification failed."]];
        }
        // delete off cycle payroll
        $this->db
            ->where('sid', $payrollId)
            ->where('company_sid', $companyId)
            ->delete('payrolls.regular_payrolls');
        // delete off cycle payroll employees
        $this->db
            ->where('regular_payroll_sid', $companyId)
            ->delete('payrolls.regular_payrolls_employees');
        //
        return ["success" => true, 'msg' => "You have successfully removed the draft data."];
    }

    /**
     * calculate single payroll
     *
     * @param int $payrollId
     * @return array
     */
    public function calculatePayrollById(int $payrollId): array
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
        // set company details
        $this->setCompanyDetails(
            $payroll['company_sid']
        );
        //
        $this->gustoCompany['other_uuid'] = $payroll['gusto_uuid'];
        //
        // make call
        $gustoResponse = $this
            ->lb_gusto
            ->gustoCall(
                'calculateSinglePayroll',
                $this->gustoCompany,
                [],
                "PUT",
                true
            );
        //
        // errors found
        if (isset($gustoResponse["errors"])) {
            return $gustoResponse["errors"];
        }
        //
        return $gustoResponse;
    }


    // Gusto calls
    /**
     * create off cycle payroll on Gusto
     *
     * @param int $companyId
     * @param array $data
     * @return array
     */
    private function createOffCyclePayrollOnGusto(int $companyId, array $data): array
    {
        // prepare array
        $request = [];
        $request['off_cycle'] = true;
        $request['off_cycle_reason'] = $data['off_cycle_reason'];
        $request['start_date'] = formatDateToDB($data['start_date'], SITE_DATE, DB_DATE);
        $request['end_date'] =
            formatDateToDB($data['end_date'], SITE_DATE, DB_DATE);
        $request['check_date'] =
            formatDateToDB($data['check_date'], SITE_DATE, DB_DATE);
        $request['employee_uuids'] = [];
        $request['withholding_pay_period'] = $data['withholding_pay_period'];
        $request['skip_regular_deductions'] = $data['skip_regular_deductions'];
        $request['fixed_withholding_rate'] = $data['fixed_withholding_rate'];
        // set employee ids
        foreach ($data['employees'] as $value) {
            // get gusto employee details
            $gustoEmployee = $this->getGustoLinkedEmployeeDetails(
                $value
            );
            //
            $request['employee_uuids'][] = $gustoEmployee['gusto_uuid'];
        }
        // set company details
        $this->setCompanyDetails(
            $companyId
        );
        //
        // make call
        $gustoResponse = $this
            ->lb_gusto
            ->gustoCall(
                'createOffCyclePayroll',
                $this->gustoCompany,
                $request,
                "POST",
                true
            );
        //
        // errors found
        if (isset($gustoResponse["errors"])) {
            return $gustoResponse["errors"];
        }
        //
        return [
            'response' => $gustoResponse,
            'success' => true,
            'msg' => 'You have successfully created the "' . ($request['off_cycle_reason']) . '" payroll.'
        ];
    }
}
