<?php

use function Aws\filter;

defined('BASEPATH') || exit('No direct script access allowed');

class Gusto_payroll_model extends CI_Model
{

    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        parent::__construct();
        //
        $this->load->helper('payroll_helper');
    }

    /**
     * Get all admins from Gusto
     *
     * @param int   $companyId
     * @return array
     */
    public function fetchAllAdmins($companyId)
    {
        // get company UUID
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        if (!$companyDetails) {
            return [];
        }
        //
        $response = getAdminsFromGusto($companyDetails);
        //
        if (!isset($response['errors']) && $response) {
            //
            $tmp = [];
            //
            foreach ($response as $admin) {
                $tmp[$admin['email']] = $admin;
                //
                if (!$this->db->where([
                    'company_sid' => $companyId,
                    'email_address' => $admin['email']
                ])->count_all_results('payroll_company_admin')) {
                    //
                    $this->db->insert(
                        'payroll_company_admin',
                        [
                            'gusto_uuid' => $admin['uuid'],
                            'first_name' => $admin['first_name'],
                            'last_name' => $admin['last_name'],
                            'email_address' => $admin['email'],
                            'created_at' => getSystemDate(),
                            'company_sid' => $companyId
                        ]
                    );
                }
            }
            //
            return $tmp;
        }
        //
        return [];
    }

    /**
     * Move admin to Gusto
     *
     * @param array $adminArray
     * @param int   $companyId
     * @return array
     */
    public function moveAdminToGusto($adminArray, $companyId)
    {
        // get company UUID
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        if (!$companyDetails) {
            return '';
        }
        //
        $response = addAdminToGusto($adminArray, $companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            //
            return $errors;
        }
        //
        if (
            $this->db
            ->where('company_sid', $companyId)
            ->where('email_address', $adminArray['email'])
            ->count_all_results('payroll_company_admin')
        ) {
            //
            $this->db
                ->where('company_sid', $companyId)
                ->where('email_address', $adminArray['email'])
                ->update('payroll_company_admin', [
                    'gusto_uuid' => $response['uuid']
                ]);
        } else {
            // insert
            $this->db->insert(
                'payroll_company_admin',
                [
                    'gusto_uuid' => $response['uuid'],
                    'first_name' => $adminArray['first_name'],
                    'last_name' => $adminArray['last_name'],
                    'email_address' => $adminArray['email'],
                    'created_at' => getSystemDate(),
                    'company_sid' => $companyId
                ]
            );
        }
        //
        return $response;
    }

    /**
     * Get all signatories from Gusto
     *
     * @param int   $companyId
     * @return array
     */
    public function fetchAllSignatories($companyId)
    {
        // get company UUID
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        if (!$companyDetails) {
            return [];
        }
        //
        $response = getSignatoriesFromGusto($companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            //
            $tmp = [];
            //
            foreach ($response as $signatory) {
                $tmp[$signatory['email']] = $signatory;
                //
                if (!$this->db->where([
                    'company_sid' => $companyId,
                    'email' => $signatory['email']
                ])->count_all_results('payroll_signatories')) {
                    //
                    $this->db->insert(
                        'payroll_signatories',
                        [
                            'company_sid' => $companyId,
                            'gusto_uuid' => $signatory['uuid'],
                            'version' => $signatory['version'],
                            'ssn' => $signatory['ssn'] ?? '',
                            'first_name' => $signatory['first_name'],
                            'last_name' => $signatory['last_name'],
                            'title' => $signatory['title'],
                            'birthday' => $signatory['birthday'],
                            'phone' => $signatory['phone'],
                            'email' => $signatory['email'],
                            'middle_initial' => $signatory['middle_initial'] ?? '',
                            'street_1' => $signatory['home_address']['street_1'],
                            'street_2' => $signatory['home_address']['street_2'],
                            'city' => $signatory['home_address']['city'],
                            'state' => $signatory['home_address']['state'],
                            'zip' => $signatory['home_address']['zip'],
                            'created_at' => getSystemDate(),
                            'updated_at' => getSystemDate()
                        ]
                    );
                }
            }
            //
            return $tmp;
        }
        //
        return [];
    }

    /**
     * Move signatory to Gusto
     *
     * @param array $signatoryArray
     * @param int   $companyId
     * @return array
     */
    public function moveSignatoryToGusto($signatoryArray, $companyId)
    {
        // get company UUID
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        if (!$companyDetails) {
            return '';
        }
        //
        $response = addSignatoryToGusto($signatoryArray, $companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (isset($response['errors'])) {
            //
            return makeGustoErrorArray($response['errors']);
        }
        //
        if (
            $this->db
            ->where('company_sid', $companyId)
            ->where('email', $signatoryArray['email'])
            ->where('is_deleted', 0)
            ->count_all_results('payroll_signatories')
        ) {
            //
            $this->db
                ->where('company_sid', $companyId)
                ->where('email', $signatoryArray['email'])
                ->where('is_deleted', 0)
                ->update('payroll_signatories', [
                    'gusto_uuid' => $response['uuid'],
                    'version' => $response['version'],
                    'updated_at' => getSystemDate()
                ]);
        } else {
            // insert
            $this->db->insert(
                'payroll_signatories',
                [
                    'company_sid' => $companyId,
                    'gusto_uuid' => $response['uuid'],
                    'version' => $response['version'],
                    'identity_verification_status' => $response['identity_verification_status'],
                    'ssn' => $signatoryArray['ssn'],
                    'first_name' => $response['first_name'],
                    'last_name' => $response['last_name'],
                    'title' => $response['title'],
                    'birthday' => $response['birthday'],
                    'middle_initial' => $signatoryArray['middle_initial'],
                    'phone' => $response['phone'],
                    'email' => $response['email'],
                    'street_1' => $response['home_address']['street_1'],
                    'street_2' => $response['home_address']['street_2'],
                    'city' => $response['home_address']['city'],
                    'state' => $response['home_address']['state'],
                    'zip' => $response['home_address']['zip'],
                    'created_at' => getSystemDate(),
                    'updated_at' => getSystemDate()
                ]
            );
        }
        //
        return $response['uuid'];
    }

    /**
     * Update signatory to Gusto
     *
     * @param array  $signatoryArray
     * @param int    $companyId
     * @param int    $signatoryId
     * @param string $signatoryUUID
     * @return array
     */
    public function updateSignatoryToGusto($signatoryArray, $companyId, int $signatoryId, string $signatoryUUID)
    {
        // get company UUID
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        if (!$companyDetails) {
            return '';
        }
        //
        $response = updateSignatoryToGusto($signatoryArray, $signatoryUUID, $companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (isset($response['errors'])) {
            //
            return makeGustoErrorArray($response['errors']);
        }
        // update
        $this->db
            ->where('sid', $signatoryId)
            ->update(
                'payroll_signatories',
                [
                    'version' => $response['version'],
                    'identity_verification_status' => $response['identity_verification_status'],
                    'ssn' => $signatoryArray['ssn'],
                    'first_name' => $response['first_name'],
                    'last_name' => $response['last_name'],
                    'title' => $response['title'],
                    'birthday' => $response['birthday'],
                    'middle_initial' => $signatoryArray['middle_initial'],
                    'phone' => $response['phone'],
                    'email' => $response['email'],
                    'street_1' => $response['home_address']['street_1'],
                    'street_2' => $response['home_address']['street_2'],
                    'city' => $response['home_address']['city'],
                    'state' => $response['home_address']['state'],
                    'zip' => $response['home_address']['zip'],
                    'updated_at' => getSystemDate()
                ]
            );
        //
        return $response['uuid'];
    }

    /**
     * Delete signatory from Gusto
     *
     * @param int $companyId
     * @param int $signatoryId
     * @return array
     */
    public function deleteSignatoryFromGusto($companyId, $signatoryId)
    {
        // get company UUID
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        if (!$companyDetails) {
            return '';
        }
        // get signatory UUID
        $signatoryUUID = $this->db
            ->select('gusto_uuid')
            ->where('sid', $signatoryId)
            ->get('payroll_signatories')
            ->row_array()['gusto_uuid'];

        //
        $response = deleteSignatoryToGusto($signatoryUUID, $companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        $this->db->where('sid', $signatoryId)->update('payroll_signatories', ['is_deleted' => 1]);
        //
        return $response;
    }

    /**
     * Get gusto company details for gusto
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyDetailsForGusto(
        int $companyId
    ) {
        $record =
            $this->db
            ->select('
                gusto_company_uid,
                refresh_token,
                access_token
            ')
            ->where('company_sid', $companyId)
            ->get('payroll_companies')
            ->row_array();
        //
        return $record;
    }

    /**
     * Sync company data from Gusto to system
     *
     * @param int $companyId
     * @return bool
     */
    public function syncCompanyDataWithGusto(int $companyId)
    {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);

        // sync company locations
        $this->syncCompanyLocations($companyId, $companyDetails);

        // lets sync the company admins
        $this->syncCompanyAdmins($companyId, $companyDetails);

        // lets sync the company signatory
        $this->syncCompanySignatory($companyId, $companyDetails);

        // lets sync the company federal tax
        $this->syncCompanyFederalTax($companyId, $companyDetails);

        // lets sync the company industry
        $this->syncCompanyIndustry($companyId, $companyDetails);

        // lets sync the company tax liabilities
        $this->syncCompanyTaxLiabilities($companyId, $companyDetails);

        // lets sync the company payment config
        $this->syncCompanyPaymentConfig($companyId, $companyDetails);

        // lets sync the company payment config
        $this->syncCompanyPayrollHistory($companyId, $companyDetails);
    }

    /**
     * Sync company locations with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyLocations(int $companyId, array $companyDetails)
    {
        //
        $response = getCompanyLocations($companyDetails);
        //
        if (!isset($response['errors']) && $response) {
            //
            foreach ($response as $admin) {
                // set update array as default
                $dataArray = [];
                $dataArray['country'] = $admin['country'];
                $dataArray['state'] = $admin['state'];
                $dataArray['city'] = $admin['city'];
                $dataArray['zip'] = $admin['zip'];
                $dataArray['street_1'] = $admin['street_1'];
                $dataArray['street_2'] = $admin['street21'];
                $dataArray['phone_number'] = $admin['phone_number'];
                $dataArray['filing_address'] = $admin['filing_address'];
                $dataArray['mailing_address'] = $admin['mailing_address'];
                $dataArray['active'] = $admin['active'];
                // set version array
                $versionArray = [];
                $versionArray['gusto_uuid'] = $admin['uuid'];
                $versionArray['version'] = $admin['version'];
                $versionArray['payroll_json'] = json_encode($admin);
                $versionArray['created_at'] = getSystemDate();
                // set where array
                $whereArray = [
                    'company_sid' => $companyId,
                    'gusto_location_id' => $admin['id']
                ];
                //
                if (!$this->db->where($whereArray)->count_all_results('payroll_company_locations')) {
                    // add additional data
                    $dataArray['company_sid'] = $companyId;
                    $dataArray['gusto_location_id'] = $admin['id'];
                    $dataArray['gusto_uuid'] = $admin['uuid'];
                    $dataArray['version'] = $admin['version'];
                    $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
                    // insert the data
                    $this->db->insert('payroll_company_locations', $dataArray);
                    // add the history
                    $this->db->insert('payroll_company_locations_versions', $versionArray);
                } else {
                    // add additional field
                    $dataArray['updated_at'] = getSystemDate();
                    // update the data
                    $this->db->where($whereArray)->update('payroll_company_locations', $dataArray);
                    //
                    if ($this->db->affected_rows()) {
                        // add the history
                        $this->db->insert('payroll_company_locations_versions', $versionArray);
                    }
                }
            }
            //
            return true;
        }
        //
        return false;
    }

    /**
     * Sync company admins with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyAdmins(int $companyId, array $companyDetails)
    {
        //
        $response = getAdminsFromGusto($companyDetails);
        //
        if (!isset($response['errors']) && $response) {
            //
            foreach ($response as $admin) {
                //
                if (!$this->db->where([
                    'company_sid' => $companyId,
                    'email_address' => $admin['email']
                ])->count_all_results('payroll_company_admin')) {
                    //
                    $this->db->insert(
                        'payroll_company_admin',
                        [
                            'gusto_uuid' => $admin['uuid'],
                            'first_name' => $admin['first_name'],
                            'last_name' => $admin['last_name'],
                            'email_address' => $admin['email'],
                            'created_at' => getSystemDate(),
                            'company_sid' => $companyId
                        ]
                    );
                }
            }
            //
            return true;
        }
        //
        return false;
    }

    /**
     * Sync company signatory with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanySignatory(int $companyId, array $companyDetails)
    {
        //
        $response = getSignatoriesFromGusto($companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            //
            foreach ($response as $signatory) {
                //
                $dataArray = [];
                $dataArray['ssn'] = $signatory['ssn'] ?? '';
                $dataArray['first_name'] = $signatory['first_name'];
                $dataArray['last_name'] = $signatory['last_name'];
                $dataArray['title'] = $signatory['title'];
                $dataArray['birthday'] = $signatory['birthday'];
                $dataArray['phone'] = $signatory['phone'];
                $dataArray['email'] = $signatory['email'];
                $dataArray['middle_initial'] = $signatory['middle_initial'] ?? '';
                $dataArray['street_1'] = $signatory['home_address']['street_1'];
                $dataArray['street_2'] = $signatory['home_address']['street_2'];
                $dataArray['city'] = $signatory['home_address']['city'];
                $dataArray['state'] = $signatory['home_address']['state'];
                $dataArray['zip'] = $signatory['home_address']['zip'];
                //
                $whereArray = [
                    'company_sid' => $companyId,
                    'email' => $signatory['email']
                ];
                //
                if (!$this->db->where($whereArray)->count_all_results('payroll_signatories')) {
                    $dataArray['gusto_uuid'] = $signatory['uuid'];
                    $dataArray['version'] = $signatory['version'];
                    $dataArray['company_sid'] = $companyId;
                    $dataArray['created_at'] = getSystemDate();
                    $dataArray['updated_at'] = getSystemDate();
                    //
                    $this->db->insert(
                        'payroll_signatories',
                        $dataArray
                    );
                } else {
                    $dataArray['gusto_uuid'] = $signatory['uuid'];
                    $dataArray['version'] = $signatory['version'];
                    $dataArray['updated_at'] = getSystemDate();
                    //
                    $this->db
                        ->where($whereArray)
                        ->update(
                            'payroll_signatories',
                            $dataArray
                        );
                }
            }
            //
            return true;
        }
        //
        return false;
    }

    /**
     * Sync company federal tax with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyFederalTax(int $companyId, array $companyDetails)
    {
        //
        $response = getCompanyFederalTax($companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            //
            $mainTable = 'payroll_company_federal_tax';
            //
            $dataArray = [];
            $dataArray['version'] = $response['version'];
            $dataArray['tax_payer_type'] = $response['tax_payer_type'];
            $dataArray['taxable_as_scorp'] = $response['taxable_as_scorp'];
            $dataArray['filing_form'] = $response['filing_form'];
            $dataArray['has_ein'] = $response['has_ein'];
            $dataArray['ein_verified'] = $response['ein_verified'];
            $dataArray['legal_name'] = $response['legal_name'];

            //
            $whereArray = [
                'company_sid' => $companyId
            ];
            //
            if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
                //
                $dataArray['company_sid'] = $companyId;
                $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
                //
                $this->db->insert($mainTable, $dataArray);
            } else {
                //
                $dataArray['updated_at'] = getSystemDate();
                //
                $this->db->where($whereArray)->update($mainTable, $dataArray);
            }
            //
            return true;
        }
        //
        return false;
    }

    /**
     * Sync company industry with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyIndustry(int $companyId, array $companyDetails)
    {
        //
        $response = getCompanyIndustry($companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            //
            $mainTable = 'payroll_company_industry';
            //
            $dataArray = [];
            $dataArray['naics_code'] = $response['naics_code'];
            $dataArray['sic_codes'] = json_encode($response['sic_codes']);
            $dataArray['title'] = $response['title'];
            //
            $whereArray = [
                'company_sid' => $companyId
            ];
            //
            if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
                //
                $dataArray['company_sid'] = $companyId;
                $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
                //
                $this->db->insert($mainTable, $dataArray);
            } else {
                //
                $dataArray['updated_at'] = getSystemDate();
                //
                $this->db->where($whereArray)->update($mainTable, $dataArray);
            }
            //
            return true;
        }
        //
        return false;
    }

    /**
     * Sync company tax liabilities with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyTaxLiabilities(int $companyId, array $companyDetails)
    {
        //
        $response = getCompanyTaxLiabilities($companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            //
            $mainTable = 'payroll_company_tax_liabilities';
            //
            $dataArray = [];
            $dataArray['tax_id'] = $response['tax_id'];
            $dataArray['tax_name'] = $response['tax_name'];
            $dataArray['last_unpaid_external_payroll_uuid'] = $response['last_unpaid_external_payroll_uuid'];
            $dataArray['liability_amount'] = $response['liability_amount'];
            $dataArray['payroll_check_date'] = $response['payroll_check_date'];
            $dataArray['external_payroll_uuid'] = $response['external_payroll_uuid'];
            //
            $whereArray = [
                'company_sid' => $companyId
            ];
            //
            if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
                //
                $dataArray['company_sid'] = $companyId;
                $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
                //
                $this->db->insert($mainTable, $dataArray);
            } else {
                //
                $dataArray['updated_at'] = getSystemDate();
                //
                $this->db->where($whereArray)->update($mainTable, $dataArray);
            }
            //
            return true;
        }
        //
        return false;
    }

    /**
     * Sync company payment config with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyPaymentConfig(int $companyId, array $companyDetails)
    {
        //
        $response = getCompanyPaymentConfig($companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            //
            $mainTable = 'payroll_settings';
            //
            $dataArray = [];
            $dataArray['partner_uuid'] = $response['partner_uuid'];
            $dataArray['fast_payment_limit'] = $response['fast_payment_limit'];
            $dataArray['payment_speed'] = $response['payment_speed'];
            //
            $whereArray = [
                'company_sid' => $companyId
            ];
            //
            if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
                //
                $dataArray['company_sid'] = $companyId;
                $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
                //
                $this->db->insert($mainTable, $dataArray);
            } else {
                //
                $dataArray['updated_at'] = getSystemDate();
                //
                $this->db->where($whereArray)->update($mainTable, $dataArray);
            }
            //
            return true;
        }
        //
        return false;
    }

    /**
     * Sync company payment history
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyPayrollHistory(int $companyId, array $companyDetails)
    {
        //
        $response = GetCompletedProcessedPayrolls('', $companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            //
            $mainTable = 'payroll_company_processed_history';
            //
            foreach ($response as $payroll) {
                $payrollId = $payroll['payroll_uuid'];
                //
                $dataArray = [];
                $dataArray['start_date'] = $payroll['pay_period']['start_date'];
                $dataArray['end_date'] = $payroll['pay_period']['end_date'];
                $dataArray['schedule_id'] = $payroll['pay_period']['pay_schedule_uuid'];
                $dataArray['payroll_json'] = json_encode($payroll);
                //
                $whereArray = [
                    'payroll_id' => $payrollId
                ];
                //
                if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
                    //
                    $dataArray['payroll_id'] = $payrollId;
                    $dataArray['company_sid'] = $companyId;
                    $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
                    //
                    $this->db->insert($mainTable, $dataArray);
                } else {
                    //
                    $dataArray['updated_at'] = getSystemDate();
                    //
                    $this->db->where($whereArray)->update($mainTable, $dataArray);
                }
            }

            //
            return true;
        }
        //
        return false;
    }

    /**
     * Sync company employee information
     *
     * @param int   $employeeId
     * @return bool|array
     */
    public function onboardEmployeeOnGusto(int $employeeId)
    {
        $response = $this->getEmployeeDetail($employeeId);
        //
        if ($response['status']) {
            //
            $megaResponse = [];
            //
            $employeeInfo = $response['data'];
            $companyDetails = $this->getCompanyDetailsForGusto($employeeInfo['parent_sid']);
            //
            $whereArray = [
                'employee_sid ' => $employeeInfo['sid'],
                'company_sid' => $employeeInfo['parent_sid']
            ];
            //
            $gustoEmployeeInfo =
                $this->db
                ->select('
                    payroll_employee_uuid,
                    version
                ')
                ->where($whereArray)
                ->get('payroll_employees')
                ->row_array();
            //
            // Add or Update employee profile info
            $response = $this->syncCompanyEmployeeProfile($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            //
            if (is_array($response)) {
                return $response;
            }
            //
            if (!$gustoEmployeeInfo) {
                $gustoEmployeeInfo =
                    $this->db
                    ->select('
                    payroll_employee_uuid,
                    version
                ')
                    ->where($whereArray)
                    ->get('payroll_employees')
                    ->row_array();
            }
            // Update employee address info
            $this->syncCompanyEmployeeAddress($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            //
            // Create employee job
            $this->syncCompanyEmployeeJobInfo($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            //
            // Create employee bank detail 
            $this->syncCompanyEmployeeBankDetail($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            // 
            // // Update employee payment Method 
            $this->syncCompanyEmployeePaymentMethod($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            // //
            // // Create employee federal Tax 
            // $this->syncCompanyEmployeeFederalTax($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            // //
            // // Create employee State Tax 
            // $this->syncCompanyEmployeeStateTax($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            //
            // Update employee status
            $this->updateCompanyEmployeeOnboardingStatus($employeeInfo, $gustoEmployeeInfo, $companyDetails);
        }
        //
        return true;
    }

    /**
     * Update company employee information
     *
     * @param int $employeeId
     * @param string $update
     * @return bool
     */
    public function updateGustoEmployeInfo(int $employeeId, string $update)
    {
        $response = $this->getEmployeeDetail($employeeId);
        //
        if ($response['status']) {
            //
            $employeeInfo = $response['data'];
            $companyDetails = $this->getCompanyDetailsForGusto($employeeInfo['parent_sid']);
            //
            $whereArray = [
                'employee_sid ' => $employeeInfo['sid'],
                'company_sid' => $employeeInfo['parent_sid']
            ];
            //
            $gustoEmployeeInfo =
                $this->db
                ->select('
                    payroll_employee_uuid,
                    version
                ')
                ->where($whereArray)
                ->get('payroll_employees')
                ->row_array();
            //
            if ($update == 'profile') {
                // Add or Update employee profile info
                $this->updateGustoEmployeeProfile($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            }
            //
            if ($update == 'address') {
                // Update employee address info
                $this->syncCompanyEmployeeAddress($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            }
            // 
            if ($update == 'payment_method') {
                // Update employee payment Method 
                $this->updateGustoEmployeePaymentMethod($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            }
        }
        //
        return true;
    }

    /**
     * get employee basic detail
     *
     * @param int   $employeeId
     * @return array
     */
    public function getEmployeeDetail(int $employeeId)
    {
        $response = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        //
        $employeeProfile =
            $this->db
            ->select('
                sid,
                first_name,
                last_name,
                middle_initial,
                dob,
                email,
                Location_Country,
                Location_State,
                Location_Address,
                Location_Address_2,
                Location_ZipCode,
                Location_City,
                ssn,
                parent_sid,
                registration_date,
                joined_at,
                job_title,
                payment_method
            ')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        //
        if (!$employeeProfile) {
            $response['message'] = "Employee not found.";
            return $response;
        }
        //
        if (ctype_space($employeeProfile["first_name"]) || $employeeProfile["first_name"] == '' || strlen(trim($employeeProfile["first_name"])) < 3) {
            $response['message'] = "Employee first name is not valid.";
            return $response;
        }
        //
        if (ctype_space($employeeProfile["last_name"]) || $employeeProfile["last_name"] == '' || strlen(trim($employeeProfile["last_name"])) < 3) {
            $response['message'] = "Employee last name is not valid.";
            return $response;
        }
        //
        if (empty($employeeProfile["dob"]) || $employeeProfile["dob"] == "0000-00-00" || !preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $employeeProfile["dob"])) {
            $response['message'] = "Employee date of birth is not valid.";
            return $response;
        }
        //
        if (empty($employeeProfile["email"]) || !filter_var($employeeProfile["email"], FILTER_VALIDATE_EMAIL)) {
            $response['message'] = "Employee email is not valid.";
            return $response;
        }
        //
        $employeeProfile["ssn"] = preg_replace('/[^0-9]/', '', $employeeProfile["ssn"]);
        //
        if (empty($employeeProfile["ssn"]) || !preg_match('/^[0-9]{9}$/', $employeeProfile["ssn"])) {
            $response['message'] = "Employee SSN is not valid.";
            return $response;
        }
        //
        $response['status'] = true;
        $response['message'] = "Employee Found.";
        $response['data'] = $employeeProfile;
        return $response;
    }

    /**
     * Sync employee profile info
     *
     * @param array $employeeProfile
     * @param array $gustoEmployeeInfo
     * @param array $companyDetails
     * @return bool|array
     */
    public function syncCompanyEmployeeProfile(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        //
        $mainTable = 'payroll_employees';
        //
        if (empty($gustoEmployeeInfo)) {
            //
            $employeeDetails = [];
            $employeeDetails['first_name'] = $employeeProfile['first_name'];
            $employeeDetails['middle_initial'] = $employeeProfile['middle_initial'];
            $employeeDetails['last_name'] = $employeeProfile['last_name'];
            $employeeDetails['date_of_birth'] = $employeeProfile['dob'];
            $employeeDetails['email'] = $employeeProfile['email'];
            $employeeDetails['ssn'] = $employeeProfile['ssn'];
            $employeeDetails['self_onboarding'] = false;
            //
            $response = createAnEmployeeOnGusto($employeeDetails, $companyDetails, [
                'X-Gusto-API-Version: 2023-03-01'
            ]);
            //
            if (!isset($response['errors']) && $response) {
                //
                $dataArray = [];
                $dataArray['company_sid'] = $employeeProfile['parent_sid'];
                $dataArray['employee_sid'] = $employeeProfile['sid'];
                $dataArray['payroll_employee_uuid'] = $response['uuid'];
                $dataArray['onboard_completed'] = 0;
                $dataArray['last_updated_by'] = 0;
                $dataArray['version'] = $response['version'];
                $dataArray['created_at'] = getSystemDate();
                $dataArray['response_body'] = json_encode($response);
                //
                $this->db->insert($mainTable, $dataArray);
                //
                $addressArray = [];
                $addressArray['employee_sid '] = $employeeProfile['sid'];;
                $addressArray['version'] = $response['home_address']['version'];
                $addressArray['street_1'] = $response['home_address']['street_1'];
                $addressArray['street_2'] = $response['home_address']['street_2'];
                $addressArray['city'] = $response['home_address']['city'];
                $addressArray['state'] = $response['home_address']['state'];
                $addressArray['zip'] = $response['home_address']['zip'];
                $addressArray['country'] = $response['home_address']['country'];
                $addressArray['active'] = $response['home_address']['active'];
                $addressArray['created_at'] = getSystemDate();
                //
                $this->db->insert('payroll_employee_address', $addressArray);
                //
                $dataToUpdate = [];
                $dataToUpdate['on_payroll'] = 1;
                //
                $this->db->where('sid', $employeeProfile['sid'])->update('users', $dataToUpdate);
                //
                return true;
            }
            // send back errors
            return parseGustoErrors($response['errors']);
            //
        } else {
            $this->updateGustoEmployeeProfile($employeeProfile, $gustoEmployeeInfo, $companyDetails);
        }
        //
        return false;
    }

    /**
     * Update employee profile info
     *
     * @param array $employeeProfile
     * @param array $gustoEmployeeInfo
     * @param array $companyDetails
     * @return bool
     */
    public function updateGustoEmployeeProfile(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        //
        $mainTable = 'payroll_employees';
        //
        $whereArray = [
            'employee_sid ' => $employeeProfile['sid'],
            'company_sid' => $employeeProfile['parent_sid']
        ];
        //
        if (!empty($gustoEmployeeInfo)) {
            //
            $employeeDetails = [];
            $employeeDetails['version'] = $gustoEmployeeInfo['version'];
            $employeeDetails['first_name'] = $employeeProfile['first_name'];
            $employeeDetails['middle_initial'] = $employeeProfile['middle_initial'];
            $employeeDetails['last_name'] = $employeeProfile['last_name'];
            $employeeDetails['date_of_birth'] = $employeeProfile['dob'];
            $employeeDetails['email'] = $employeeProfile['email'];
            $employeeDetails['ssn'] = $employeeProfile['ssn'];
            //
            $response = updateAnEmployeeOnGusto($employeeDetails, $companyDetails, $gustoEmployeeInfo['payroll_employee_uuid'], [
                'X-Gusto-API-Version: 2023-03-01'
            ]);
            //
            if (!isset($response['errors']) && $response) {
                //
                $dataToUpdate = [];
                $dataToUpdate['version'] = $response['version'];
                $dataToUpdate['updated_at'] = getSystemDate();
                //
                $this->db->where($whereArray)->update($mainTable, $dataToUpdate);
                //
                return true;
            }
        }
        //
        return false;
    }


    /**
     * Update employee home address
     *
     * @param array $employeeProfile
     * @param array $gustoEmployeeInfo
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyEmployeeAddress(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        //
        $mainTable = 'payroll_employee_address';
        //
        $whereArray = [
            'employee_sid ' => $employeeProfile['sid']
        ];
        //
        if ($this->db->where($whereArray)->count_all_results($mainTable)) {
            $employeeAddress = $this->validateEmployeeAddress($employeeProfile, $whereArray, $mainTable);
            //
            if ($employeeAddress['status']) {
                //
                $response = updateAnEmployeeAddressOnGusto($employeeAddress['data'], $companyDetails, $gustoEmployeeInfo['payroll_employee_uuid'], [
                    'X-Gusto-API-Version: 2023-03-01'
                ]);
                //
                if (!isset($response['errors']) && $response) {
                    //
                    $dataToUpdate = [];
                    $dataToUpdate['version'] = $response['version'];
                    $dataToUpdate['street_1'] = $response['street_1'];
                    $dataToUpdate['street_2'] = $response['street_2'];
                    $dataToUpdate['city'] = $response['city'];
                    $dataToUpdate['state'] = $response['state'];
                    $dataToUpdate['country'] = $response['country'];
                    $dataToUpdate['zip'] = $response['zip'];
                    $dataToUpdate['active'] = $response['active'];
                    $dataToUpdate['updated_at'] = getSystemDate();
                    //
                    $this->db->where($whereArray)->update($mainTable, $dataToUpdate);
                    //
                    return true;
                }
            }
        }
        //
        return false;
        //    
    }

    /**
     * Validate employee home address
     *
     * @param array $employeeProfile
     * @param array $whereArray
     * @param string $mainTable
     * @return array
     */
    private function validateEmployeeAddress(array $employeeProfile, array $whereArray, string $mainTable)
    {
        $response = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        //
        if (ctype_space($employeeProfile["Location_Address"]) || $employeeProfile["Location_Address"] == '') {
            $response['message'] = "Must include a street address.";
            return $response;
        }
        //
        if (ctype_space($employeeProfile["Location_State"]) || $employeeProfile["Location_State"] == '') {
            $response['message'] = "Must include a state.";
            return $response;
        } else if (!empty($employeeProfile["Location_State"])) {
            $stateDetails = db_get_state_name($employeeProfile["Location_State"]);
            $employeeProfile['state_code'] = $stateDetails['state_code'];
            $employeeProfile['state_name'] = $stateDetails['state_name'];
        }
        //
        if (ctype_space($employeeProfile["Location_City"]) || $employeeProfile["Location_City"] == '') {
            $response['message'] = "Must include a city.";
            return $response;
        }
        //
        if (ctype_space($employeeProfile["Location_ZipCode"]) || $employeeProfile["Location_ZipCode"] == '') {
            $response['message'] = "Must include a zip code.";
            return $response;
        }
        //
        $gustoEmployeeAddressVersion =
            $this->db
            ->select('version')
            ->where($whereArray)
            ->get($mainTable)
            ->row_array();
        //        
        $employeeAddressDetails = [];
        $employeeAddressDetails['version'] = $gustoEmployeeAddressVersion['version'];
        $employeeAddressDetails['street_1'] = $employeeProfile["Location_Address"];
        $employeeAddressDetails['street_2'] = $employeeProfile["Location_Address_2"];
        $employeeAddressDetails['city'] = $employeeProfile["Location_City"];
        $employeeAddressDetails['state'] = $employeeProfile["state_code"];
        $employeeAddressDetails['zip'] = $employeeProfile["Location_ZipCode"];
        //
        $response['status'] = true;
        $response['message'] = "Employee Address Valid.";
        $response['data'] = $employeeAddressDetails;
        return $response;
    }

    /**
     * Create employee job
     *
     * @param array $employeeProfile
     * @param array $gustoEmployeeInfo
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyEmployeeJobInfo(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        //
        $mainTable = 'payroll_employee_jobs';
        //
        $whereArray = [
            'employee_sid' => $employeeProfile['sid']
        ];
        //
        if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
            //
            $employeeJobDetail = $this->fetchEmployeeJobDetail($employeeProfile);
            //
            if ($employeeJobDetail['status']) {
                //
                $response = createEmployeeJobDetail($employeeJobDetail['data'], $companyDetails, $gustoEmployeeInfo['payroll_employee_uuid'], [
                    'X-Gusto-API-Version: 2023-03-01'
                ]);
                //
                if (!isset($response['errors']) && $response) {
                    //
                    $dataArray = [];
                    $dataArray['employee_sid'] = $employeeProfile['sid'];
                    $dataArray['payroll_job_id'] = $response['uuid'];
                    $dataArray['version'] = $response['version'];
                    $dataArray['payroll_location_id'] = $response['location_uuid'];
                    $dataArray['hire_date'] = $response['hire_date'];
                    $dataArray['title'] = $response['title'];
                    $dataArray['is_primary'] = $response['primary'];
                    $dataArray['rate'] = $response['rate'];
                    $dataArray['payment_unit'] = $response['payment_unit'];
                    $dataArray['current_compensation_id'] = $response['current_compensation_uuid'];
                    $dataArray['payroll_uid'] = $response['uuid'];
                    $dataArray['created_at'] = getSystemDate();
                    //
                    $this->db->insert($mainTable, $dataArray);
                    $payroll_job_sid = $this->db->insert_id();
                    //
                    if (!empty($response['compensations'])) {
                        foreach ($response['compensations'] as $compensation) {
                            $compensationArray = [];
                            $compensationArray['payroll_job_sid '] = $payroll_job_sid;
                            $compensationArray['rate'] = $compensation['rate'];
                            $compensationArray['payment_unit'] = $compensation['payment_unit'];
                            $compensationArray['flsa_status'] = $compensation['flsa_status'];
                            $compensationArray['effective_date'] = $compensation['effective_date'];
                            $compensationArray['payroll_id'] = $response['uuid'];
                            $compensationArray['version'] = $compensation['version'];
                            $compensationArray['created_at'] = getSystemDate();
                            //                        
                            $this->db->insert('payroll_employee_job_compensations', $compensationArray);
                        }
                    }
                    //
                    return true;
                }
            }
        }
        //
        return false;
    }

    /**
     * Process employee job details
     *
     * @param array $employeeProfile
     * @return array
     */
    private function fetchEmployeeJobDetail(array $employeeProfile)
    {
        //
        $response = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        //
        $employeeLocation = $this->getEmployeeJobLocation($employeeProfile['parent_sid']);
        //
        if (empty($employeeLocation)) {
            $response['message'] = "Active single job location not found.";
            return $response;
        }
        //
        $hireDate = get_employee_latest_joined_date($employeeProfile["registration_date"], $employeeProfile["joined_at"], "", false);
        //
        if (empty($hireDate)) {
            $response['message'] = "Must include a hire date.";
            return $response;
        }
        //
        if (ctype_space($employeeProfile["job_title"]) || $employeeProfile["job_title"] == '') {
            $response['message'] = "Must include a job tile.";
            return $response;
        }
        //        
        $employeeJobDetails = [];
        $employeeJobDetails['title'] = $employeeProfile["job_title"];
        $employeeJobDetails['location_uuid'] = $employeeLocation;
        $employeeJobDetails['hire_date'] = $hireDate;
        //
        $response['status'] = true;
        $response['message'] = "Employee job data completed.";
        $response['data'] = $employeeJobDetails;
        return $response;
    }

    /**
     * Get employee job location details
     *
     * @param int $companySid
     * @return string
     */
    private function getEmployeeJobLocation(int $companySid)
    {
        //
        $locationID = '';
        //
        $mainTable = 'payroll_company_locations';
        //
        $whereArray = [
            'company_sid ' => $companySid,
            'active' => 1
        ];
        //
        $companyLocations =
            $this->db
            ->select('
                gusto_uuid
            ')
            ->where($whereArray)
            ->get($mainTable)
            ->result_array();
        //
        if (!empty($companyLocations) && count($companyLocations) == 1) {
            $locationID = $companyLocations[0]['gusto_uuid'];
        }
        //   
        return $locationID;
    }

    /**
     * Create employee bank account
     *
     * @param array $employeeProfile
     * @param array $gustoEmployeeInfo
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyEmployeeBankDetail(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        //
        $mainTable = 'payroll_employee_bank_accounts';
        //
        $whereArray = [
            'employee_sid' => $employeeProfile['sid']
        ];
        //
        if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
            //
            $employeeBankDetail = $this->fetchEmployeeBankDetail($employeeProfile);
            //
            if ($employeeBankDetail['status']) {
                //
                $response = createEmployeeBankDetail($employeeBankDetail['data'], $companyDetails, $gustoEmployeeInfo['payroll_employee_uuid'], [
                    'X-Gusto-API-Version: 2023-03-01'
                ]);
                //
                if (!isset($response['errors']) && $response) {
                    //
                    $dataArray['employee_sid'] = $employeeProfile['sid'];
                    $dataArray['payroll_bank_uuid'] = $response['uuid'];
                    $dataArray['account_type'] = $response['account_type'];
                    $dataArray['name'] = $response['name'];
                    $dataArray['routing_number'] = $response['routing_number'];
                    $dataArray['account_number'] = $response['hidden_account_number'];
                    $dataArray['created_at'] = getSystemDate();
                    //
                    $this->db->insert($mainTable, $dataArray);
                    //
                    return true;
                }
            }
        }
        //
        return false;
    }

    /**
     * Get employee bank account details
     *
     * @param array $employeeProfile
     * @return array
     */
    private function fetchEmployeeBankDetail(array $employeeProfile)
    {
        //
        $response = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        //
        $mainTable = 'bank_account_details';
        //
        $whereArray = [
            'company_sid ' => $employeeProfile['parent_sid'],
            'users_sid ' => $employeeProfile['sid'],
            'users_type' => 'employee',
            'account_status' => 'primary'
        ];
        //
        $employeeBankDetail =
            $this->db
            ->select('
                routing_transaction_number,
                account_number,
                account_type,
                financial_institution_name
            ')
            ->where($whereArray)
            ->get($mainTable)
            ->row_array();
        //
        if (empty($employeeBankDetail)) {
            $response['message'] = "Bank account not exist.";
            return $response;
        }
        //
        if (empty($employeeBankDetail["routing_transaction_number"]) && !is_numeric($employeeBankDetail["routing_transaction_number"])) {
            $response['message'] = "Must include a routing number.";
            return $response;
        }
        //
        if (empty($employeeBankDetail["account_number"]) && !is_numeric($employeeBankDetail["account_number"])) {
            $response['message'] = "Must include a account number.";
            return $response;
        }
        //
        if (empty($employeeBankDetail["account_type"]) && ($employeeBankDetail["account_type"] != 'Checking' || $employeeBankDetail["account_type"] != 'Savings')) {
            $response['message'] = "Must include a account type.";
            return $response;
        }
        //
        if (empty($employeeBankDetail["financial_institution_name"])) {
            $response['message'] = "Must include a financial institution name.";
            return $response;
        }
        //     
        $employeeBankDetails = [];
        $employeeBankDetails['name'] = $employeeBankDetail["financial_institution_name"];
        $employeeBankDetails['routing_number'] = $employeeBankDetail["routing_transaction_number"];
        $employeeBankDetails['account_number'] = $employeeBankDetail["account_number"];
        $employeeBankDetails['account_type'] = ucfirst($employeeBankDetail["account_type"]);
        //
        $response['status'] = true;
        $response['message'] = "Employee bank data completed.";
        $response['data'] = $employeeBankDetails;
        return $response;
    }

    /**
     * Update employee payment method
     *
     * @param array $employeeProfile
     * @param array $gustoEmployeeInfo
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyEmployeePaymentMethod(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        //
        $paymentMethod = $employeeProfile['payment_method'] == 'direct_deposit' ? 'Direct Deposit' : 'Check';
        $mainTable = 'payroll_employee_payment_method';
        //
        $whereArray = [
            'employee_sid' => $employeeProfile['sid']
        ];
        //
        if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
            //
            if ($employeeProfile['payment_method']) {
                //
                $response = getOnboardingEmployeePaymentMethod($companyDetails, $gustoEmployeeInfo['payroll_employee_uuid'], [
                    'X-Gusto-API-Version: 2023-03-01'
                ]);
                //
                if (!isset($response['errors']) && $response) {
                    //
                    $dataArray['employee_sid'] = $employeeProfile['sid'];
                    $dataArray['company_sid'] = $employeeProfile['parent_sid'];
                    $dataArray['payment_method'] = $response['type'];
                    $dataArray['split_method'] = $response['split_by'];
                    $dataArray['version'] = $response['version'];
                    $dataArray['splits'] = json_encode($response['splits']);
                    $dataArray['created_at'] = getSystemDate();
                    //
                    $this->db->insert($mainTable, $dataArray);
                    //
                    if ($response['type'] != $paymentMethod) {
                        $updatePaymentMethod = [];
                        $updatePaymentMethod['version'] = $response['version'];
                        $updatePaymentMethod['type'] = $paymentMethod;
                        //
                        if ($updatePaymentMethod['type'] == 'Direct Deposit') {
                            $updatePaymentMethod['split_by'] = 'Percentage';
                            $updatePaymentMethod['splits'] = json_decode($response['splits']);
                        }
                        //
                        $this->updateEmployeePaymentMethod($updatePaymentMethod, $companyDetails, $gustoEmployeeInfo['payroll_employee_uuid'], $whereArray, $mainTable);
                        //
                    }
                    //
                    return true;
                }
            }
        } else {
            $this->updateGustoEmployeePaymentMethod($employeeProfile, $gustoEmployeeInfo, $companyDetails);
        }
        //
        return false;
    }

    /**
     * Update employee payment method
     *
     * @param array $employeeProfile
     * @param array $gustoEmployeeInfo
     * @param array $companyDetails
     * @return bool
     */
    public function updateGustoEmployeePaymentMethod(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        //
        $paymentMethod = $employeeProfile['payment_method'] == 'direct_deposit' ? 'Direct Deposit' : 'Check';
        $mainTable = 'payroll_employee_payment_method';
        //
        $whereArray = [
            'employee_sid' => $employeeProfile['sid']
        ];
        //
        if ($this->db->where($whereArray)->count_all_results($mainTable)) {
            $employeePaymentMethod = $this->db
                ->select('
                    sid,
                    payment_method,
                    split_method,
                    splits,
                    version
                ')
                ->where($whereArray)
                ->get($mainTable)
                ->row_array();
            //
            if ($employeePaymentMethod['payment_method'] != $paymentMethod) {
                $updatePaymentMethod = [];
                $updatePaymentMethod['version'] = $employeePaymentMethod['version'];
                $updatePaymentMethod['type'] = $paymentMethod;
                //
                if ($updatePaymentMethod['type'] == 'Direct Deposit') {
                    $updatePaymentMethod['split_by'] = 'Percentage';
                    $updatePaymentMethod['splits'] = json_decode($employeePaymentMethod['splits']);
                }
                //
                $this->updateEmployeePaymentMethod($updatePaymentMethod, $companyDetails, $gustoEmployeeInfo['payroll_employee_uuid'], $whereArray, $mainTable);
            }
        }
        //
        return false;
    }

    private function updateEmployeePaymentMethod($data, $companyDetails, $employeeUUID, $whereArray, $mainTable)
    {
        //
        $response = updateOnboardingEmployeePaymentMethod($data, $companyDetails, $employeeUUID, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        $data_to_update = [];
        $data_to_update['version'] = $response['060bf2d587991d8f090a1309b285291c'];
        $data_to_update['payment_method'] = $response['type'];
        $data_to_update['split_method'] = $response['split_by'];
        $data_to_update['splits'] = $response['splits'];
        $dataArray['updated_at'] = getSystemDate();
        //
        $this->db->where($whereArray)->update($mainTable, $data_to_update);
    }

    public function syncCompanyEmployeeFederalTax(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        // TODO on this section
        return true;
    }

    public function syncCompanyEmployeeStateTax(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        // TODO on this section
        return true;
    }

    public function updateCompanyEmployeeOnboardingStatus(array $employeeProfile, array $gustoEmployeeInfo, array $companyDetails)
    {
        //
        $response = getEmployeeOnbordingStatus($companyDetails, $gustoEmployeeInfo['payroll_employee_uuid'], [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            if (isset($response['onboarding_steps'])) {
                //
                $onboardingStatus = [];
                //
                foreach ($response['onboarding_steps'] as $step) {
                    //
                    if ($step['id'] == 'personal_details' && $step['completed'] == 1) {
                        $onboardingStatus['personal_profile'] = 1;
                    } else if ($step['id'] == 'compensation_details' && $step['completed'] == 1) {
                        $onboardingStatus['compensation'] = 1;
                    } else if ($step['id'] == 'add_work_address' && $step['completed'] == 1) {
                        $onboardingStatus['work_address'] = 1;
                    } else if ($step['id'] == 'add_home_address' && $step['completed'] == 1) {
                        $onboardingStatus['home_address'] = 1;
                    } else if ($step['id'] == 'federal_tax_setup' && $step['completed'] == 1) {
                        $onboardingStatus['federal_tax'] = 1;
                    } else if ($step['id'] == 'state_tax_setup' && $step['completed'] == 1) {
                        $onboardingStatus['state_tax'] = 1;
                    } else if ($step['id'] == 'direct_deposit_setup' && $step['completed'] == 1) {
                        $onboardingStatus['payment_method'] = 1;
                    } else if ($step['id'] == 'employee_form_signing' && $step['completed'] == 1) {
                        $onboardingStatus['employee_form_signing'] = 1;
                    } else if ($step['id'] == 'file_new_hire_report' && $step['completed'] == 1) {
                        $onboardingStatus['file_new_hire_report'] = 1;
                    }
                }
                //
                $mainTable = 'payroll_employees';
                //
                $whereArray = [
                    'employee_sid ' => $employeeProfile['sid'],
                    'company_sid' => $employeeProfile['parent_sid']
                ];
                //
                $this->db->where($whereArray)->update($mainTable, $onboardingStatus);
            }
            return true;
        }
        //
        return false;
    }


    /**
     * Handle employee onboard profile
     * 
     * Handles employee onboard profile process from the 
     * UI of super admin and employer panel
     * 
     * @param array $post
     * @param array $gustoEmployeeDetails
     * @param array $gustoCompany
     * @param bool  $doReturn
     * @return array
     */
    public function handleEmployeeProfileForOnboarding(array $post, array $gustoEmployeeDetails, array $gustoCompany, bool $doReturn)
    {
        // set default response
        $errors = [];
        // let verify the data
        // check for first name
        if (!$post['firstName']) {
            $errors['errors'][] = 'First name is required.';
        }
        // check for last name
        if (!$post['lastName']) {
            $errors['errors'][] = 'Last name is required.';
        }
        // check for date of birth
        if (!$post['dob']) {
            $errors['errors'][] = 'Date of birth is required.';
        }
        // check for email address
        if (!$post['email']) {
            $errors['errors'][] = 'Email is required.';
        }
        // check for email address valid
        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['errors'][] = 'Email is invalid.';
        }
        // check for SSN
        if (!preg_match('/[0-9]{9}/', $post['ssn'])) {
            $errors['errors'][] = 'Social security number is required. It must be off 9 digits long.';
        }
        // check if error happened
        if ($errors) {
            return $doReturn ? $errors : SendResponse(200, $errors);
        }
        // set request
        $request = [];
        $request['first_name'] = $post['firstName'];
        $request['middle_initial'] = $post['middleInitial'] ? substr($post['middleInitial'], 0, 1) : '';
        $request['last_name'] = $post['lastName'];
        $request['date_of_birth'] = formatDateToDB($post['dob'], SITE_DATE, DB_DATE);
        $request['email'] = $post['email'];
        $request['ssn'] = $post['ssn'];
        $request['version'] = $gustoEmployeeDetails['version'];
        // lets update the employee profile
        $response = updateOnboardEmployeeProfile(
            $request,
            $gustoEmployeeDetails['payroll_employee_uuid'],
            $gustoCompany
        );
        // check for Gusto errors
        if ($errors = hasGustoErrors($response)) {
            return _e($errors, true);
        }
        // set the user update array
        $userArray = [];
        $userArray['first_name'] = $request['first_name'];
        $userArray['last_name'] = $request['last_name'];
        $userArray['middle_initial'] = $request['middle_initial'];
        $userArray['email'] = $request['email'];
        $userArray['ssn'] = $request['ssn'];
        $userArray['dob'] = $request['date_of_birth'];
        // update the users table
        updateUserById($userArray, $post['employeeId']);
        $this->db->where('sid', $post['employeeId'])->update('users', $userArray);
        // set the payroll employee array
        $payrollEmployeeArray = [];
        $payrollEmployeeArray['version'] = $response['version'];
        $payrollEmployeeArray['personal_profile'] = 1;
        $payrollEmployeeArray['updated_at'] = getSystemDate();
        $payrollEmployeeArray['response_body'] = json_encode($response);
        _e($response, true);


        _e($post);
        _e($request);
        _e($gustoEmployeeDetails);
    }
}
