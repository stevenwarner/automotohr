<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_model', 'Payroll_model');
/**
 * External payroll model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
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
                is_processed,
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
     * check if any unprocessed payrolls
     *
     * @param int $companyId
     * @return bool
     */
    public function hasAnyUnprocessedExternalPayrolls(int $companyId): bool
    {
        return (bool)$this->db
            ->where('company_sid', $companyId)
            ->where('is_deleted', 0)
            ->where('is_processed', 0)
            ->count_all_results('payrolls.external_payrolls');
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
     * check and link employee to external payroll
     *
     * @param int $externalPayrollId
     * @param int $employeeId
     * @return array
     */
    public function checkAndLinkEmployeeToExternalPayroll(
        int $externalPayrollId,
        int $employeeId
    ): array {
        // check if employee already linked
        if ($this->db->where([
            'external_payrolls_sid' => $externalPayrollId,
            'employee_sid' => $employeeId,
        ])->count_all_results('payrolls.external_payrolls_employees')) {
            return ['success' => true, 'message' => "Employee already linked with external payroll."];
        }
        // get gusto id of external payroll
        $externalPayrollDetails =
            $this->external_payroll_model
            ->getExternalPayrollById(
                [
                    'sid' => $externalPayrollId
                ],
                [
                    'gusto_uuid',
                    'external_payroll_items',
                    'applicable_benefits',
                    'applicable_earnings',
                    'company_sid'
                ]
            );
        // get employee details
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($externalPayrollDetails['company_sid']);
        // add external uuid
        $companyDetails['other_uuid'] = $externalPayrollDetails['gusto_uuid'];
        //
        $earnings = json_decode($externalPayrollDetails['applicable_earnings'], true);
        $passEarnings = [];
        //
        if ($earnings) {
            foreach ($earnings as $earning) {
                $passEarnings[] = [
                    'earning_id' => $earning['earning_id'],
                    'earning_type' => $earning['earning_type'],
                    'amount' => 0.0,
                    'hours' => 0.0,
                ];
            }
        }
        // prepare request
        $request = [];
        $request['replace_fields'] = true;
        $request['external_payroll_items'] = json_decode($externalPayrollDetails['external_payroll_items']);
        $request['external_payroll_items'][] = [
            'employee_uuid' => $gustoEmployee['gusto_uuid'],
            'earnings' => $passEarnings,
            'benefits' => json_decode($externalPayrollDetails['applicable_benefits'], true),
        ];
        // make call
        $gustoResponse = gustoCall(
            'updateExternalPayroll',
            $companyDetails,
            $request,
            'PUT'
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // return errors if found
        if ($errors) {
            return $errors;
        }
        //
        $current = [];
        // get current person details
        foreach ($gustoResponse['external_payroll_items'] as $value) {
            if ($gustoEmployee['gusto_uuid'] == $value['employee_uuid']) {
                $current = $value;
                break;
            }
        }
        //
        $ins = [];
        $ins['external_payrolls_sid'] = $externalPayrollId;
        $ins['employee_sid'] = $employeeId;
        $ins['data_json'] = json_encode($current);
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        //
        $this->db->insert(
            'payrolls.external_payrolls_employees',
            $ins
        );
        // get the latest stuff
        $this->syncExternalPayroll($externalPayrollId);
        //
        return [
            'success' => true,
            'message' => 'You have successfully linked employee with external payroll.'
        ];
    }

    /**
     * get employee external payroll
     *
     * @param int $externalPayrollId
     * @param int $employeeId
     * @return array
     */
    public function getEmployeeExternalPayroll(
        int $externalPayrollId,
        int $employeeId
    ): array {
        // get the record
        $record = $this->db
            ->select('data_json')
            ->where([
                'external_payrolls_sid' => $externalPayrollId,
                'employee_sid' => $employeeId
            ])
            ->get('payrolls.external_payrolls_employees')
            ->row_array();
        //
        return $record ? json_decode($record['data_json'], true) : [];
    }

    /**
     * get linked employee ids
     *
     * @param int $externalPayrollId
     * @return array
     */
    public function getLinkEmployeeIds(
        int $externalPayrollId
    ): array {
        // get the record
        $records = $this->db
            ->select('employee_sid')
            ->where([
                'external_payrolls_sid' => $externalPayrollId,
            ])
            ->get('payrolls.external_payrolls_employees')
            ->result_array();
        //
        return $records ? array_column($records, 'employee_sid') : [];
    }

    /**
     * get linked employee ids
     *
     * @param int $companyId
     * @param int $loggedInPersonId
     * @param int $externalPayrollId
     * @param int $employeeId
     * @param array $data
     * @return array
     */
    public function updateEmployeeExternalPayroll(
        int $companyId,
        int $loggedInPersonId,
        int $externalPayrollId,
        int $employeeId,
        array $data
    ): array {
        // get external payroll earnings, benefits, and taxes
        $externalPayroll = $this->getExternalPayrollById([
            'sid' => $externalPayrollId,
            'company_sid' => $companyId
        ], [
            'gusto_uuid',
            'external_payroll_items',
            'applicable_earnings',
            'applicable_benefits',
            'applicable_taxes',
        ]);
        // check if found
        if (!$externalPayroll) {
            return ['errors' => ['The system failed to verify the external payroll.']];
        }
        // get employee
        $gustoEmployee = $this->getEmployeeDetailsForGusto(
            $employeeId
        );
        // get company
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // add external payroll id to be passed in URL
        $companyDetails['other_uuid'] = $externalPayroll['gusto_uuid'];
        // map the external payroll with incoming
        // employee payroll
        $mappedExternalPayroll = $this->getMappedExternalPayroll(
            $data
        );
        // json to array
        $items = json_decode($externalPayroll['external_payroll_items'], true);
        // replace data
        foreach ($items as $key => $value) {
            //
            if ($gustoEmployee['gusto_uuid'] == $value['employee_uuid']) {
                $items[$key]['earnings'] = $mappedExternalPayroll['earnings'];
                $items[$key]['taxes'] = $mappedExternalPayroll['taxes'];
                break;
            }
        }
        // prepare request
        $request = [];
        $request['replace_fields'] = true;
        $request['external_payroll_items'] = $items;
        //z
        $gustoResponse = gustoCall(
            "updateExternalPayroll",
            $companyDetails,
            $request,
            "PUT"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where('sid', $externalPayrollId)
            ->update(
                'payrolls.external_payrolls',
                [
                    'external_payroll_items' => json_encode($gustoResponse['external_payroll_items'])
                ]
            );
        //
        $employeeExternalPayroll = [];
        // replace data
        foreach ($gustoResponse['external_payroll_items'] as $value) {
            //
            if ($gustoEmployee['gusto_uuid'] == $value['employee_uuid']) {
                $employeeExternalPayroll = $value;
                break;
            }
        }
        if ($employeeExternalPayroll) {
            //
            $this->db
                ->where('external_payrolls_sid', $externalPayrollId)
                ->where('employee_sid', $employeeId)
                ->update(
                    'payrolls.external_payrolls_employees',
                    [
                        'data_json' => json_encode($employeeExternalPayroll)
                    ]
                );
        }
        return [
            'success' => true,
            'message' => "You have successfully updated the employee's external payroll."
        ];
    }

    /**
     * calculate employee external payroll taxes
     *
     * @param int $companyId
     * @param int $loggedInPersonId
     * @param int $externalPayrollId
     * @param int $employeeId
     * @return array
     */
    public function calculateEmployeeExternalPayroll(
        int $companyId,
        int $loggedInPersonId,
        int $externalPayrollId,
        int $employeeId
    ): array {
        // get external payroll earnings, benefits, and taxes
        $externalPayroll = $this->getExternalPayrollById([
            'sid' => $externalPayrollId,
            'company_sid' => $companyId
        ], [
            'gusto_uuid',
        ]);
        // check if found
        if (!$externalPayroll) {
            return ['errors' => ['The system failed to verify the external payroll.']];
        }
        // get employee
        $gustoEmployee = $this->getEmployeeDetailsForGusto(
            $employeeId
        );
        // get company
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // add external payroll id to be passed in URL
        $companyDetails['other_uuid'] = $externalPayroll['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            "calculateExternalPayrollTaxes",
            $companyDetails,
            [],
            "GET"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $employeePayrollSuggestion = [];
        //
        foreach ($gustoResponse as $value) {
            if ($value['employee_uuid'] == $gustoEmployee['gusto_uuid']) {
                $employeePayrollSuggestion = $value;
                break;
            }
        }
        //
        return [
            'success' => true,
            'data' => $employeePayrollSuggestion['tax_suggestions'],
            'message' => "You have successfully updated the employee's external payroll and calculated taxes."
        ];
    }

    /**
     * sync external payroll tax liabilities
     *
     * @param int $companyId
     * @param int $loggedInPersonId
     * @return array
     */
    public function syncTaxLiabilitiesForExternalPayroll(
        int $companyId,
        int $loggedInPersonId
    ): array {
        // get company
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        $gustoResponse = gustoCall(
            "getExternalPayrollTaxLiabilities",
            $companyDetails,
            [],
            "GET"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if ($gustoResponse) {
            //
            $ins = [];
            $ins['last_changed_by'] = $loggedInPersonId;
            $ins['liabilities_json'] = json_encode($gustoResponse);
            $ins['updated_at'] = getSystemDate();
            $ins['is_processed'] = 0;
            // check it
            if (!$this->db->where('company_sid', $companyId)->count_all_results('payrolls.external_payrolls_tax_liabilities')) {
                //
                $ins['company_sid'] = $companyId;
                $ins['created_at'] = $ins['updated_at'];
                // insert
                $this->db
                    ->insert(
                        'payrolls.external_payrolls_tax_liabilities',
                        $ins
                    );
            } else {
                $this->db
                    ->where('company_sid', $companyId)
                    ->where('is_processed', 0)
                    ->update(
                        'payrolls.external_payrolls_tax_liabilities',
                        $ins
                    );
            }
        }
        //
        return [
            'success' => true,
            'message' => "You have successfully synced external payroll tax liabilities."
        ];
    }

    /**
     * update external payroll tax liabilities
     *
     * @param int $companyId
     * @param int $loggedInPersonId
     * @param array $data
     * @return array
     */
    public function updateTaxLiabilities(
        int $companyId,
        int $loggedInPersonId,
        array $data
    ): array {
        // get company
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        $request = [];
        $request['liability_selections'] = $data;
        //
        $gustoResponse = gustoCall(
            "updateExternalPayrollTaxLiabilities",
            $companyDetails,
            $request,
            "PUT"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $saveTax = [];
        foreach ($data as $tax) {
            $saveTax[$taxis_processed['tax_id']] = $tax;
        }
        //
        $this->db
            ->where('company_sid', $companyId)
            ->where('is_processed', 0)
            ->update(
                'payrolls.external_payrolls_tax_liabilities',
                [
                    'liabilities_save_json' => json_encode($saveTax),
                    'liabilities_json' => json_encode($gustoResponse)
                ]
            );
        //
        return [
            'success' => true,
            'message' => "You have successfully updated external payroll tax liabilities."
        ];
    }

    /**
     * confirm and finish external payroll tax liabilities
     *
     * @param int $companyId
     * @param int $loggedInPersonId
     * @return array
     */
    public function finishTaxLiabilities(
        int $companyId,
        int $loggedInPersonId
    ): array {
        // get company
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        $gustoResponse = gustoCall(
            "confirmExternalPayrollTaxLiabilities",
            $companyDetails,
            [],
            "PUT"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where('company_sid', $companyId)
            ->where('is_processed', 0)
            ->update(
                'payrolls.external_payrolls_tax_liabilities',
                [
                    'is_processed' => 1
                ]
            );

        //
        $this->db
            ->where('company_sid', $companyId)
            ->where('is_processed', 0)
            ->update(
                'payrolls.external_payrolls',
                [
                    'is_processed' => 1
                ]
            );
        //
        $this->db
            ->where('company_sid', $companyId)
            ->update(
                'gusto_companies',
                [
                    'added_historical_payrolls' => 1
                ]
            );
        //
        return [
            'success' => true,
            'message' => "You have successfully confirmed external payroll tax liabilities."
        ];
    }

    /**
     * get external payroll tax liabilities
     *
     * @param int $companyId
     * @return array
     */
    public function getExternalPayrollTaxLiabilities(
        int $companyId
    ): array {
        //
        $record = $this->db
            ->select('liabilities_json, liabilities_save_json')
            ->where('company_sid', $companyId)
            ->where('is_processed', 0)
            ->get('payrolls.external_payrolls_tax_liabilities')
            ->row_array();
        //
        if ($record) {
            $record['liabilities_json'] = json_decode($record['liabilities_json'], true);
            $record['liabilities_save_json'] = json_decode($record['liabilities_save_json'], true);
        }
        //
        return $record;
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
        $upd['external_payroll_items'] = json_encode($gustoResponse['external_payroll_items']);
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

    /**
     * map the external payroll with employee data
     *
     * @param array $employeeExternalPayroll
     * @return array
     */
    private function getMappedExternalPayroll(
        array $employeeExternalPayroll
    ): array {
        // set default
        $newPayroll = [
            'earnings' => [],
            'benefits' => [],
            'taxes' => [],
        ];
        // map earnings
        if ($employeeExternalPayroll['applicable_earnings']) {
            //
            foreach ($employeeExternalPayroll['applicable_earnings'] as $value) {
                //
                if (!$newPayroll['earnings'][$value['id']]) {
                    //
                    $newPayroll['earnings'][$value['id']] = [
                        'earning_id' => $value['id'],
                        'earning_type' => $value['type'],
                        'amount' => 0.0,
                        'hours' => 0.0
                    ];
                }
                //
                $newPayroll['earnings'][$value['id']]['amount'] = $value['inputType'] == 'amount' ? $value['value'] : $newPayroll['earnings'][$value['id']]['amount'];
                $newPayroll['earnings'][$value['id']]['hours'] = $value['inputType'] == 'hours' ? $value['value'] : $newPayroll['earnings'][$value['id']]['hours'];
            }
        }
        // todo benefits
        // map taxes
        if ($employeeExternalPayroll['applicable_taxes']) {
            //
            foreach ($employeeExternalPayroll['applicable_taxes'] as $value) {
                //
                $newPayroll['taxes'][] = [
                    'tax_id' => $value['id'],
                    'amount' => $value['value']
                ];
            }
        }
        $newPayroll['earnings'] = array_values(
            $newPayroll['earnings']
        );
        return $newPayroll;
    }
}
