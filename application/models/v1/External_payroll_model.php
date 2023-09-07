<?php defined('BASEPATH') || exit('No direct script access allowed');
// manually add the base model
require_once ROOTPATH . "application/models/v1/Payroll_model.php";

class External_payroll_model extends Payroll_model
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
     * get all company external payrolls for
     * listing
     *
     * @param int $companyId
     * @return array
     */
    public function getAllCompanyExternalPayrolls(int $companyId): array
    {
        return $this->db
            ->select('
                sid,
                check_date,
                payment_period_start_date,
                payment_period_end_date
            ')
            ->where('company_sid', $companyId)
            ->where('is_deleted', 0)
            ->order_by('sid', 'DESC')
            ->get('payrolls.external_payrolls')
            ->result_array();
    }

    /**
     * get single company external payroll
     *
     * @param array $where
     * @param array $columns Optional
     *              By default all columns will be returned
     * @return array
     */
    public function getExternalPayrollById(
        array $where,
        array $columns = ['*']
    ): array {
        return $this->db
            ->select($columns)
            ->where($where)
            ->get('payrolls.external_payrolls')
            ->row_array();
    }

    /**
     * check single company external payroll
     *
     * @param array $where
     * @return bool
     */
    public function checkExternalPayrollById(
        array $where
    ): bool {
        return (bool)$this->db
            ->where($where)
            ->count_all_results('payrolls.external_payrolls');
    }

    /**
     * creates an external payroll on gusto
     * and saves to AutomotoHR database
     *
     * @param int $companyId
     * @param int $loggedInPersonId
     * @param array $data
     * @return array
     */
    public function createExternalPayroll(
        int $companyId,
        int $loggedInPersonId,
        array $data
    ): array {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // make request data
        $request = $data;
        $request['check_date'] = formatDateToDB($request['check_date'], SITE_DATE, DB_DATE);
        $request['payment_period_start_date'] = formatDateToDB($request['payment_period_start_date'], SITE_DATE, DB_DATE);
        $request['payment_period_end_date'] = formatDateToDB($request['payment_period_end_date'], SITE_DATE, DB_DATE);
        // make call
        $gustoResponse = gustoCall(
            'createExternalPayroll',
            $companyDetails,
            $request,
            'POST'
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // return errors if found
        if ($errors) {
            return $errors;
        }
        // prepare insert array
        $ins = [];
        $ins['company_sid'] = $companyId;
        $ins['last_changed_by'] = $loggedInPersonId;
        $ins['gusto_uuid'] = $gustoResponse['uuid'];
        $ins['check_date'] = $gustoResponse['check_date'];
        $ins['is_deleted'] = 0;
        $ins['payment_period_start_date'] = $gustoResponse['payment_period_start_date'];
        $ins['payment_period_end_date'] = $gustoResponse['payment_period_end_date'];
        $ins['applicable_earnings'] = json_encode($gustoResponse['applicable_earnings']);
        $ins['applicable_benefits'] = json_encode($gustoResponse['applicable_benefits'] ?? []);
        $ins['applicable_taxes'] = json_encode($gustoResponse['applicable_taxes']);
        $ins['metadata'] = json_encode($gustoResponse['metadata']);
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        // insert data
        $this->db
            ->insert('payrolls.external_payrolls', $ins);
        // get the insert id
        $insertId = $this->db->insert_id();
        // add to history
        $this->db
            ->insert('payrolls.external_payrolls_logs', [
                'external_payrolls_sid' => $insertId,
                'last_changed_by' => $loggedInPersonId,
                'created_at' => getSystemDate(),
                'changes_json' => json_encode([])
            ]);

        // sync it
        $this->syncExternalPayroll($insertId);
        //
        return [
            'success' => true,
            'message' => 'You have successfully created an external payroll.'
        ];
    }

    /**
     * deletes an external payroll on gusto
     * as well as on AutomotoHR
     *
     * @param int $companyId
     * @param int $loggedInPersonId
     * @param int $externalPayrollId
     * @return array
     */
    public function deleteExternalPayroll(
        int $companyId,
        int $loggedInPersonId,
        int $externalPayrollId
    ): array {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // get external payroll UUID
        $externalPayrollDetails = $this->getExternalPayrollById(
            [
                'sid' => $externalPayrollId,
                'company_sid' => $companyId
            ],
            [
                'gusto_uuid'
            ]
        );
        // check if payroll belonged to current company
        if (!$externalPayrollDetails) {
            return [
                'errors' => [
                    'The system failed to verify details.'
                ]
            ];
        }
        //
        $companyDetails['other_uuid'] = $externalPayrollDetails['gusto_uuid'];
        // make call
        $gustoResponse = gustoCall(
            'deleteExternalPayroll',
            $companyDetails,
            [],
            'DELETE'
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // return errors if found
        if ($errors) {
            return $errors;
        }
        // update delete status
        $this->db
            ->where('sid', $externalPayrollId)
            ->update(
                'payrolls.external_payrolls',
                [
                    'is_deleted' => 1,
                    'updated_at' => getSystemDate(),
                    'last_changed_by' => $loggedInPersonId,
                ]
            );

        // add to history
        $this->db
            ->insert('payrolls.external_payrolls_logs', [
                'external_payrolls_sid' => $externalPayrollId,
                'last_changed_by' => $loggedInPersonId,
                'created_at' => getSystemDate(),
                'changes_json' => json_encode([
                    'status' => [
                        'old' => 'Active',
                        'new' => 'Deleted'
                    ]
                ])
            ]);
        //
        return [
            'success' => true,
            'message' => 'You have successfully deleted an external payroll.'
        ];
    }

    /**
     * get the external payroll
     *
     * @param int $externalPayrollId
     * @return array
     */
    private function syncExternalPayroll(
        int $externalPayrollId
    ): array {
        //
        $externalPayrollDetails =
            $this->external_payroll_model
            ->getExternalPayrollById(
                [
                    'sid' => $externalPayrollId
                ],
                [
                    'gusto_uuid',
                    'company_sid'
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($externalPayrollDetails['company_sid']);
        //
        $companyDetails['other_uuid'] = $externalPayrollDetails['gusto_uuid'];
        // make call
        $gustoResponse = gustoCall(
            'getExternalPayroll',
            $companyDetails,
            [],
            'GET'
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // return errors if found
        if ($errors) {
            return $errors;
        }
        //
        $upd = [];
        $upd['gusto_uuid'] = $gustoResponse['uuid'];
        $upd['applicable_earnings'] = json_encode($gustoResponse['applicable_earnings']);
        $upd['applicable_benefits'] = json_encode($gustoResponse['applicable_benefits'] ?? []);
        $upd['applicable_taxes'] = json_encode($gustoResponse['applicable_taxes']);
        $upd['metadata'] = json_encode($gustoResponse['metadata']);
        $upd['updated_at'] = getSystemDate();
        // update data
        $this->db
            ->where('sid', $externalPayrollId)
            ->update('payrolls.external_payrolls', $upd);
        //
        return [
            'success' => true,
            'message' => 'You have successfully synced external payroll.'
        ];
    }
}
