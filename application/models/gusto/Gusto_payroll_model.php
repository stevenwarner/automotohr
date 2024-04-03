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
        //
        $this->tables = [];
        $this->tables['PayrollCompanyAdmin'] = 'payroll_company_admin';
        $this->tables['U'] = 'users';
        $this->tables['P'] = 'payrolls';
        $this->tables['PH'] = 'payroll_history';
        $this->tables['PC'] = 'payroll_companies';
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
            'X-Gusto-API-Version: 2024-03-01'
        ]);
        //
        if (isset($response['errors'])) {
            //
            return hasGustoErrors($response);
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
            'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
        return $this->db
            ->select('
                gusto_company_uid,
                refresh_token,
                access_token
            ')
            ->where('company_sid', $companyId)
            ->get('payroll_companies')
            ->row_array();
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

        // lets sync the company bank accounts
        $this->syncCompanyBankAccounts($companyId, $companyDetails);
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
                } else {
                    $this->db->where([
                        'company_sid' => $companyId,
                        'email_address' => $admin['email']
                    ])->update('payroll_company_admin', ['gusto_uuid' => $admin['uuid']]);
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
            'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
     * Sync company bank accounts with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyBankAccounts(int $companyId, array $companyDetails)
    {
        //
        $response = getCompanyBankAccountsFromGusto($companyDetails, [
            'X-Gusto-API-Version: 2024-03-01'
        ]);
        //
        $errors = hasGustoErrors($response);
        //
        if (!$errors && $response) {
            //
            $mainTable = 'payroll_company_bank_accounts';

            //
            foreach ($response as $bankAccount) {
                //
                $dataArray = [];
                $dataArray['payroll_uuid'] = $bankAccount['uuid'];
                $dataArray['routing_number'] = $bankAccount['routing_number'];
                $dataArray['account_number'] = $bankAccount['hidden_account_number'];
                $dataArray['account_type'] = $bankAccount['account_type'];
                $dataArray['status'] = $bankAccount['verification_status'];
                //
                $whereArray = [
                    'company_sid' => $companyId,
                    'payroll_uuid' => $bankAccount['uuid'],
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
            $employeeInfo = $response['data'];
            $companyDetails = $this->getCompanyDetailsForGusto($employeeInfo['parent_sid']);
            //
            if (empty($companyDetails)) {
                return ['errors' => ['Failed to verify company.']];
            }
            //
            $whereArray = [
                'employee_sid' => $employeeInfo['sid'],
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
            // Create employee job
            $this->syncCompanyEmployeeJobInfo($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            // Create employee bank detail
            $this->syncCompanyEmployeeBankDetail($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            // // Update employee payment Method
            $this->syncCompanyEmployeePaymentMethod($employeeInfo, $gustoEmployeeInfo, $companyDetails);
            // Create employee federal Tax
            // $this->syncCompanyEmployeeFederalTax($employeeInfo, $gustoEmployeeInfo, $companyDetails);
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
                'X-Gusto-API-Version: 2024-03-01'
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
                'X-Gusto-API-Version: 2024-03-01'
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
                    'X-Gusto-API-Version: 2024-03-01'
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
                    'X-Gusto-API-Version: 2024-03-01'
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
                    'X-Gusto-API-Version: 2024-03-01'
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
                    'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
            'X-Gusto-API-Version: 2024-03-01'
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
    public function handleEmployeeProfileForOnboarding(
        array $post,
        array $gustoEmployeeDetails,
        array $gustoCompany,
        bool $doReturn
    ) {
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
        if ($errors['errors']) {
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
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        // set the user update array
        $userArray = [];
        $userArray['first_name'] = $request['first_name'];
        $userArray['last_name'] = $request['last_name'];
        $userArray['middle_initial'] = $request['middle_initial'];
        $userArray['email'] = $request['email'];
        $userArray['ssn'] = $request['ssn'];
        $userArray['dob'] = $request['date_of_birth'];
        if ($post['startDate']) {
            $userArray['joined_at'] = formatDateToDB($post['startDate'], SITE_DATE, DB_DATE);
        }
        // update the users table
        updateUserById($userArray, $post['employeeId']);
        // set the payroll employee array
        $payrollEmployeeArray = [];
        $payrollEmployeeArray['version'] = $response['version'];
        $payrollEmployeeArray['personal_profile'] = 1;
        $payrollEmployeeArray['updated_at'] = getSystemDate();
        $payrollEmployeeArray['response_body'] = json_encode($response);

        if ($post['workLocation']) {
            $payrollEmployeeArray['work_address_sid'] = $post['workLocation'];
        }
        // update the table
        $this->db->where(['employee_sid' => $post['employeeId']])->update('payroll_employees', $payrollEmployeeArray);
        // if location is set
        if ($post['workLocation'] && $post['startDate']) {
            // get the required things for job & compensation
            $employeeDetails = $this->db->select('
                job_title,
                hourly_rate
            ')
                ->where('sid', $post["employeeId"])
                ->get('users')
                ->row_array();
            //
            if ($employeeDetails['job_title']) {
                // set post
                $newPost = [];
                $newPost['title'] = $employeeDetails['job_title'];
                $newPost['rate'] = $employeeDetails['hourly_rate'];
                $newPost['flsa_status'] = "Nonexempt";
                $newPost['payment_unit'] = "Hour";
                $newPost['employeeId'] = $post['employeeId'];
                $newPost['companyId'] = $post['companyId'];
                // call the event
                $this->handleEmployeeCompensationForOnboarding(
                    $newPost,
                    $gustoEmployeeDetails,
                    $gustoCompany,
                    false
                );
            }
        }
        // send response
        return $doReturn ? true : sendResponse(200, [
            'success' => 'The employee\'s profile has been successfully updated.'
        ]);
    }

    /**
     * Handle employee onboard job and compensation
     *
     * Handles employee onboard job & compensation process from the
     * UI of super admin and employer panel
     *
     * @param array $post
     * @param array $gustoEmployeeDetails
     * @param array $gustoCompany
     * @param bool  $doReturn
     * @return array
     */
    public function handleEmployeeCompensationForOnboarding(
        array $post,
        array $gustoEmployeeDetails,
        array $gustoCompany,
        bool $doReturn
    ) {
        // set default response
        $errors = [];
        // let verify the data
        // check for job title
        if (!$post['title']) {
            $errors['errors'][] = 'Job title is required.';
        }
        // check for rate
        if (!$post['rate']) {
            $errors['errors'][] = 'Rate is required.';
        }
        // check for flsa status
        if (!$post['flsa_status']) {
            $errors['errors'][] = 'FLSA status is required.';
        }
        // check for payment unit
        if (!$post['payment_unit']) {
            $errors['errors'][] = 'Payment unit is required.';
        }
        // check if error happened
        if (isset($errors['errors'])) {
            return $doReturn ? $errors : SendResponse(200, $errors);
        }
        // lets check the job first
        if (
            !$this->db
                ->where('employee_sid', $post['employeeId'])
                ->count_all_results('payroll_employee_jobs')
        ) {
            // we need to add job and compensation
            // lets fetch the job from Gusto
            $gustoJob = getEmployeeJobsFromGusto(
                $gustoEmployeeDetails['payroll_employee_uuid'],
                $gustoCompany
            );
            // check for errors
            if ($errors = hasGustoErrors($gustoJob)) {
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            // check if already job present on Gusto
            if (!$gustoJob) {
                // add the job to Gusto
                // check if location exists
                $gustoEmployeeWorkLocation = $this->db
                    ->select('payroll_company_locations.gusto_uuid')
                    ->join(
                        'payroll_company_locations',
                        'payroll_company_locations.gusto_uuid = payroll_employees.work_address_sid',
                        'inner'
                    )
                    ->where('payroll_employees.employee_sid', $post['employeeId'])
                    ->get('payroll_employees')
                    ->row_array()['gusto_uuid'];
                // check the work location
                if (!$gustoEmployeeWorkLocation) {
                    // set error
                    $errors['errors'][] = 'Employee\'s work location is required.';
                    // send response
                    return $doReturn ? $errors : sendResponse(200, $errors);
                }
                // check if employee start date is present
                // get the required things for job & compensation
                $employeeStartDate = getUserStartDate($post['employeeId'], true);
                // check the date as well
                if (!$employeeStartDate) {
                    // set error
                    $errors['errors'][] = 'Employee\'s start date is required.';
                    // send response
                    return $doReturn ? $errors : sendResponse(200, $errors);
                }
                // create request
                $request = [];
                $request['title'] = $post['title'];
                $request['location_uuid'] = $gustoEmployeeWorkLocation;
                $request['hire_date'] = $employeeStartDate;
                // create job on Gusto
                $response = createEmployeeJobOnGusto(
                    $request,
                    $gustoEmployeeDetails['payroll_employee_uuid'],
                    $gustoCompany
                );
                // check for errors
                if ($errors = hasGustoErrors($response)) {
                    return $doReturn ? $errors : sendResponse(200, $errors);
                }
                //
                $this->checkAndAddJobs($response, $post['employeeId']);
                // update compensation
                $response = $this->updateEmployeeCompensationOnGusto([
                    'rate' => $post['rate'],
                    'payment_unit' => $post['payment_unit'],
                    'flsa_status' => $post['flsa_status']
                ], $post['employeeId'], $gustoEmployeeDetails['payroll_employee_uuid'], $gustoCompany);
                //
                if (isset($response['errors'])) {
                    // send response
                    return $doReturn ? $errors : sendResponse(200, $response);
                }
            } else {
                $this->checkAndAddJobs($gustoJob, $post['employeeId']);
                // update compensation
                $response = $this->updateEmployeeCompensationOnGusto([
                    'rate' => $post['rate'],
                    'payment_unit' => $post['payment_unit'],
                    'flsa_status' => $post['flsa_status']
                ], $post['employeeId'], $gustoEmployeeDetails['payroll_employee_uuid'], $gustoCompany);
                //
                if (isset($response['errors'])) {
                    // send response
                    return $doReturn ? $errors : sendResponse(200, $response);
                }
            }
        } else {
            // we need to update job and compensation
            // check if location exists
            $gustoEmployeeWorkLocation = $this->db
                ->select('payroll_company_locations.gusto_uuid')
                ->join(
                    'payroll_company_locations',
                    'payroll_company_locations.gusto_uuid = payroll_employees.work_address_sid',
                    'inner'
                )
                ->where('payroll_employees.employee_sid', $post['employeeId'])
                ->get('payroll_employees')
                ->row_array()['gusto_uuid'];
            // check the work location
            if (!$gustoEmployeeWorkLocation) {
                // set error
                $errors['errors'][] = 'Employee\'s work location is required.';
                // send response
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            // check if employee start date is present
            // get the required things for job & compensation
            $employeeStartDate = getUserStartDate($post['employeeId'], true);
            // check the date as well
            if (!$employeeStartDate) {
                // set error
                $errors['errors'][] = 'Employee\'s start date is required.';
                // send response
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            // get the primary job
            $employeePrimaryJob = $this->db
                ->select('version, sid, payroll_uid')
                ->where([
                    'employee_sid' => $post['employeeId'],
                    'is_primary' => 1
                ])
                ->get('payroll_employee_jobs')
                ->row_array();
            //
            if (!$employeePrimaryJob) {
                // set error
                $errors['errors'][] = 'Employee\'s primary job not found.';
                // send response
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            // let's update the job first
            // create request
            $request = [];
            $request['title'] = $post['title'];
            $request['location_uuid'] = $gustoEmployeeWorkLocation;
            $request['hire_date'] = $employeeStartDate;
            $request['version'] = $employeePrimaryJob['version'];
            // create job on Gusto
            $response = updateEmployeeJobOnGusto(
                $request,
                $employeePrimaryJob['payroll_uid'],
                $gustoCompany
            );
            // check for errors
            if ($errors = hasGustoErrors($response)) {
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            //
            $this->checkAndAddJobs($response, $post['employeeId']);
            // update compensation
            $response = $this->updateEmployeeCompensationOnGusto([
                'rate' => $post['rate'],
                'payment_unit' => $post['payment_unit'],
                'flsa_status' => $post['flsa_status']
            ], $post['employeeId'], $gustoEmployeeDetails['payroll_employee_uuid'], $gustoCompany);
            //
            if (isset($response['errors'])) {
                // send response
                return $doReturn ? $errors : sendResponse(200, $response);
            }
        }
        // set the payroll employee array
        $payrollEmployeeArray = [];
        $payrollEmployeeArray['work_address'] = 1;
        $payrollEmployeeArray['compensation'] = 1;
        $payrollEmployeeArray['updated_at'] = getSystemDate();
        // update the table
        $this->db->where(['employee_sid' => $post['employeeId']])->update('payroll_employees', $payrollEmployeeArray);
        // update the users table
        updateUserById([
            'hourly_rate' => $post['rate']
        ], $post['employeeId']);
        // send response
        return $doReturn ? $errors : sendResponse(200, ['success' => 'Employee\'s job is successfully updated.']);
    }

    /**
     * Handle employee onboard home address
     * 
     * Handles employee onboard home address process from the 
     * UI of super admin and employer panel
     * 
     * @param array $post
     * @param array $gustoEmployeeDetails
     * @param array $gustoCompany
     * @param bool  $doReturn
     * @return array
     */
    public function handleEmployeeHomeAddressForOnboarding(
        array $post,
        array $gustoEmployeeDetails,
        array $gustoCompany,
        bool $doReturn
    ) {
        // set default response
        $errors = [];
        // let verify the data
        // check for street 1
        if (!$post['street1']) {
            $errors['errors'][] = 'Street 1 is required.';
        }
        // check for city
        if (!$post['city']) {
            $errors['errors'][] = 'City is required.';
        }
        // check for state
        if (!$post['state']) {
            $errors['errors'][] = 'State is required.';
        }
        // check for zip
        if (!$post['zip']) {
            $errors['errors'][] = 'Zip is required.';
        }
        // check if error happened
        if ($errors['errors']) {
            return $doReturn ? $errors : SendResponse(200, $errors);
        }
        // check if home address is already synced
        if (!$this->db->where(['employee_sid' => $post['employeeId'], 'active' => 1])->count_all_results('payroll_employee_address')) {
            // insert it
            // check the home address on Gusto
            $gustoEmployeeHomeAddress = getEmployeeHomeAddressFromGusto(
                $gustoEmployeeDetails['payroll_employee_uuid'],
                $gustoCompany
            );
            // check for errors
            if ($errors = hasGustoErrors($gustoEmployeeHomeAddress)) {
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            // if address present
            if ($gustoEmployeeHomeAddress) {
                // set insert array
                $insertArray = [];
                $insertArray['gusto_uuid'] = $gustoEmployeeHomeAddress['uuid'];
                $insertArray['employee_sid'] = $post['employeeId'];
                $insertArray['street_1'] = $gustoEmployeeHomeAddress['street_1'];
                $insertArray['street_2'] = $gustoEmployeeHomeAddress['street_2'];
                $insertArray['city'] = $gustoEmployeeHomeAddress['city'];
                $insertArray['state'] = $gustoEmployeeHomeAddress['state'];
                $insertArray['zip'] = $gustoEmployeeHomeAddress['zip'];
                $insertArray['country'] = $gustoEmployeeHomeAddress['country'];
                $insertArray['active'] = $gustoEmployeeHomeAddress['active'];
                $insertArray['effective_date'] = $gustoEmployeeHomeAddress['effective_date'];
                $insertArray['version'] = $gustoEmployeeHomeAddress['version'];
                $insertArray['courtesy_withholding'] = $gustoEmployeeHomeAddress['courtesy_withholding'];
                $insertArray['created_at'] = $insertArray['updated_at'] = getSystemDate();
                // insert the home address
                $this->db->insert('payroll_employee_address', $insertArray);
            }
        } else {
            // update
            // get version and sid
            $employeeHomeAddress = $this->db
                ->select('sid, version')
                ->get('payroll_employee_address')
                ->row_array();
            // create request
            $request = [];
            $request['version'] = $employeeHomeAddress['version'];
            $request['street_1'] = $post['street1'];
            $request['street_2'] = $post['street2'] ?? '';
            $request['state'] = ucwords($post['state']);
            $request['city'] = $post['city'];
            $request['zip'] = $post['zip'];
            $request['active'] = true;
            // send update call
            $response = updateEmployeeHomeAddressOnGusto(
                $request,
                $gustoEmployeeDetails['payroll_employee_uuid'],
                $gustoCompany
            );
            // check for errors
            if ($errors = hasGustoErrors($response)) {
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            // create update system array
            $insertArray = $request;
            $insertArray['country'] = $response['country'];
            $insertArray['version'] = $response['version'];
            $insertArray['effective_date'] = $response['effective_date'];
            $insertArray['courtesy_withholding'] = $response['courtesy_withholding'];
            $insertArray['gusto_uuid'] = $response['uuid'];
            $insertArray['updated_at'] = getSystemDate();
            // update the array
            $this->db
                ->where('sid', $employeeHomeAddress['sid'])
                ->update('payroll_employee_address', $insertArray);
        }

        // set user array
        $userArray = [];
        $userArray['Location_Address'] = $insertArray['street_1'];
        $userArray['Location_Address_2'] = $insertArray['street_2'];
        $userArray['Location_City'] = $insertArray['city'];
        $userArray['Location_ZipCode'] = $insertArray['zip'];
        // update the users table
        updateUserById($userArray, $post['employeeId']);
        //
        // set the payroll employee array
        $payrollEmployeeArray = [];
        $payrollEmployeeArray['home_address'] = 1;
        $payrollEmployeeArray['updated_at'] = getSystemDate();
        // update the table
        $this->db->where(['employee_sid' => $post['employeeId']])->update('payroll_employees', $payrollEmployeeArray);

        // send response
        return $doReturn ? $errors : sendResponse(200, ['success' => 'Employee\'s home address is successfully updated.']);
    }

    /**
     * Handle employee onboard federal tax
     * 
     * Handles employee onboard federal tax process from the 
     * UI of super admin and employer panel
     * 
     * @param array $post
     * @param array $gustoEmployeeDetails
     * @param array $gustoCompany
     * @param bool  $doReturn
     * @return array
     */
    public function handleEmployeeFederalTaxForOnboarding(
        array $post,
        array $gustoEmployeeDetails,
        array $gustoCompany,
        bool $doReturn
    ) {
        // set default response
        $errors = [];
        // let verify the data
        // check
        if (!$post['federalFilingStatus']) {
            $errors['errors'][] = 'Federal filing status is required.';
        }
        // check
        if (!$post['multipleJobs']) {
            $errors['errors'][] = 'Multiple jobs is required.';
        }
        // check
        if (!$post['dependentTotal']) {
            $errors['errors'][] = 'Dependent total is required.';
        }
        // check
        if (!$post['otherIncome']) {
            $errors['errors'][] = 'Other income is required.';
        }
        // check
        if (!$post['deductions']) {
            $errors['errors'][] = 'Deductions is required.';
        }
        // check
        if (!$post['extraWithholding']) {
            $errors['errors'][] = 'Extra withholding is required.';
        }
        // check if error happened
        if ($errors['errors']) {
            return $doReturn ? $errors : SendResponse(200, $errors);
        }
        // reset fields
        $post['otherIncome'] = number_format($post['otherIncome'], 2);
        $post['deductions'] = number_format($post['deductions'], 2);
        $post['extraWithholding'] = number_format($post['extraWithholding'], 2);
        // set where array
        $where = [
            'employee_sid' => $post['employeeId'],
            'company_sid' => $post['companyId']
        ];
        // let check if we have federal tax
        // in our system
        if (!$this->db->where($where)->count_all_results('payroll_employee_federal_tax')) {
            // get the federal tax from Gusto
            $gustoFederalTax = getEmployeeFederalTaxFromGusto(
                $gustoEmployeeDetails['payroll_employee_uuid'],
                $gustoCompany
            );
            // check for errors
            if ($errors = hasGustoErrors($gustoFederalTax)) {
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            // set insert array
            $insertArray = [];
            $insertArray['employee_sid'] = $post['employeeId'];
            $insertArray['company_sid'] = $post['companyId'];
            $insertArray['filing_status'] = $gustoFederalTax['filing_status'];
            $insertArray['extra_withholding'] = $gustoFederalTax['extra_withholding'];
            $insertArray['multiple_jobs'] = $gustoFederalTax['two_jobs'];
            $insertArray['dependent'] = $gustoFederalTax['dependents_amount'];
            $insertArray['other_income'] = $gustoFederalTax['other_income'];
            $insertArray['deductions'] = $gustoFederalTax['deductions'];
            $insertArray['w4_data_type'] = $gustoFederalTax['w4_data_type'];
            $insertArray['version'] = $gustoFederalTax['version'];
            $insertArray['created_at'] = $insertArray['updated_at'] = getSystemDate();
            // insert it
            $this->db->insert('payroll_employee_federal_tax', $insertArray);
        }
        // get the federal tax sid, and version
        $employeeFederalTax = $this->db
            ->select('sid, version, w4_data_type')
            ->where($where)
            ->get('payroll_employee_federal_tax')
            ->row_array();
        // create request
        $request = [];
        $request['filing_status'] = $post['federalFilingStatus'];
        $request['extra_withholding'] = $post['extraWithholding'];
        $request['two_jobs'] = $post['multipleJobs'];
        $request['dependents_amount'] = $post['dependentTotal'];
        $request['other_income'] = $post['otherIncome'];
        $request['deductions'] = $post['deductions'];
        $request['w4_data_type'] = $employeeFederalTax['w4_data_type'];
        $request['version'] = $employeeFederalTax['version'];
        // make request
        $response = updateEmployeeFederalTaxOnGusto(
            $request,
            $gustoEmployeeDetails['payroll_employee_uuid'],
            $gustoCompany
        );
        // check for errors
        if ($errors = hasGustoErrors($response)) {
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        $insertArray = [];
        $insertArray['filing_status'] = $response['filing_status'];
        $insertArray['extra_withholding'] = $response['extra_withholding'];
        $insertArray['multiple_jobs'] = $response['two_jobs'];
        $insertArray['dependent'] = $response['dependents_amount'];
        $insertArray['other_income'] = $response['other_income'];
        $insertArray['deductions'] = $response['deductions'];
        $insertArray['w4_data_type'] = $response['w4_data_type'];
        $insertArray['version'] = $response['version'];
        $insertArray['updated_at'] = getSystemDate();
        // update it
        $this->db
            ->where('sid', $employeeFederalTax['sid'])
            ->update('payroll_employee_federal_tax', $insertArray);
        // set the payroll employee array
        $payrollEmployeeArray = [];
        $payrollEmployeeArray['federal_tax'] = 1;
        $payrollEmployeeArray['updated_at'] = getSystemDate();
        // update the table
        $this->db->where(['employee_sid' => $post['employeeId']])->update('payroll_employees', $payrollEmployeeArray);

        // send response
        return $doReturn ? $errors : sendResponse(200, [
            'success' => 'Employee\'s federal tax is successfully updated.'
        ]);
    }

    /**
     * Handle employee onboard payment method
     * 
     * Handles employee onboard payment method process from the 
     * UI of super admin and employer panel
     * 
     * @param array $post
     * @param array $gustoEmployeeDetails
     * @param array $gustoCompany
     * @param bool  $doReturn
     * @return array
     */
    public function handleEmployeePaymentMethodForOnboarding(
        array $post,
        array $gustoEmployeeDetails,
        array $gustoCompany,
        bool $doReturn
    ) {
        // set default response
        $errors = [];
        // let verify the data
        // check
        if (!$post['paymentMethod']) {
            $errors['errors'][] = 'Payment method is required.';
        }
        if (!in_array($post['paymentMethod'], ['Check', 'Direct Deposit'])) {
            $errors['errors'][] = 'Payment method cna be either \'Check\' or \'Direct Deposit\'.';
        }
        // check if error happened
        if ($errors['errors']) {
            return $doReturn ? $errors : SendResponse(200, $errors);
        }
        //
        // set split type
        $splitType = null;
        $splits = null;
        //
        if (
            $post['paymentMethod'] == 'Direct Deposit' &&
            !$this->db
                ->where('employee_sid', $post['employeeId'])
                ->count_all_results('payroll_employee_bank_accounts')
        ) {
            $errors['errors'][] = 'Please add at least one bank account.';
            return $doReturn ? $errors : SendResponse(200, $errors);
        }
        if ($post['paymentMethod'] == 'Direct Deposit') {
            // get banks
            $bankAccounts = $this->db
                ->select('
                    payroll_employee_bank_accounts.payroll_bank_uuid,
                    payroll_employee_bank_accounts.name,
                    payroll_employee_bank_accounts.account_percentage,
                    bank_account_details.deposit_type
                ')
                ->join(
                    'bank_account_details',
                    'payroll_employee_bank_accounts.direct_deposit_id = bank_account_details.sid',
                    'inner'
                )
                ->where('payroll_employee_bank_accounts.employee_sid', $post['employeeId'])
                ->where('payroll_employee_bank_accounts.is_deleted', 0)
                ->get('payroll_employee_bank_accounts')
                ->result_array();
            // set split type
            $splitType = ucfirst($bankAccounts[0]['deposit_type']);
            $splits = [];
            //
            foreach ($bankAccounts as $index => $account) {
                // set split
                $splits[$index] = [];
                $splits[$index]['uuid'] = $account['payroll_bank_uuid'];
                $splits[$index]['name'] = $account['name'];
                $splits[$index]['priority'] = $index + 1;
                //
                if ($account['deposit_type'] == 'percentage') {
                    //
                    $splits[$index]['split_amount'] = $account['account_percentage'];
                    //
                    if ($index == 1) {
                        $splits[$index]['split_amount'] = 100 - $bankAccounts[0]['account_percentage'];
                    }
                } else {
                    if ($index == 1) {
                        $splits[$index]['split_amount'] = null;
                    } else {
                        $splits[$index]['split_amount'] = $account['account_percentage'];
                    }
                }
            }
        }
        // set where array
        $where = [
            'employee_sid' => $post['employeeId']
        ];
        $this->db->where($where)->delete('payroll_employee_payment_method');
        // let check if we have federal tax
        // in our system
        if (!$this->db->where($where)->count_all_results('payroll_employee_payment_method')) {
            // get the federal tax from Gusto
            $gustoPaymentMethod = getEmployeePaymentMethodFromGusto(
                $gustoEmployeeDetails['payroll_employee_uuid'],
                $gustoCompany
            );
            // check for errors
            if ($errors = hasGustoErrors($gustoPaymentMethod)) {
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            // set insert array
            $insertArray = [];
            $insertArray['employee_sid'] = $post['employeeId'];
            $insertArray['company_sid'] = $post['companyId'];
            $insertArray['payment_method'] = $gustoPaymentMethod['type'];
            $insertArray['split_method'] = $gustoPaymentMethod['split_by'];
            $insertArray['splits'] = json_encode($gustoPaymentMethod['splits'] ?? []);
            $insertArray['version'] = $gustoPaymentMethod['version'];
            $insertArray['created_at'] = $insertArray['updated_at'] = getSystemDate();
            // insert it
            $this->db->insert('payroll_employee_payment_method', $insertArray);
        }
        // get sid and record
        $employeePaymentMethod = $this->db
            ->select('sid, version')
            ->where($where)
            ->get('payroll_employee_payment_method')
            ->row_array();
        // set request array
        $request = [];
        $request['version'] = $employeePaymentMethod['version'];
        $request['type'] = ucfirst($post['paymentMethod']);
        $request['split_by'] = $splitType;
        $request['splits'] = $splits;
        // make request
        $response = updateEmployeePaymentMethodOnGusto(
            $request,
            $gustoEmployeeDetails['payroll_employee_uuid'],
            $gustoCompany
        );
        // check for errors
        if ($errors = hasGustoErrors($response)) {
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        //
        if ($post['paymentMethod'] == 'Check') {
            $this->db->where('employee_sid', $post['employeeId'])->update('payroll_employee_bank_accounts', ['is_deleted' => 1]);
        }
        // set insert array
        $insertArray = [];
        $insertArray['payment_method'] = $response['type'];
        $insertArray['split_method'] = $response['split_by'];
        $insertArray['splits'] = json_encode($response['splits'] ?? []);
        $insertArray['version'] = $response['version'];
        $insertArray['updated_at'] = getSystemDate();
        // update it
        $this->db
            ->where('sid', $employeePaymentMethod['sid'])
            ->update('payroll_employee_payment_method', $insertArray);

        // send response
        return $doReturn ? $errors : sendResponse(200, [
            'success' => 'Employee\'s payment method is successfully updated.'
        ]);
    }

    /**
     * Handle employee bank account add
     * 
     * Handles employee bank account add process from the 
     * UI of super admin and employer panel
     * 
     * @param array $post
     * @param array $gustoEmployeeDetails
     * @param array $gustoCompany
     * @param bool  $doReturn
     * @return array
     */
    public function handleEmployeeBankAccountAddForOnboarding(
        array $post,
        array $gustoEmployeeDetails,
        array $gustoCompany,
        bool $doReturn
    ) {
        // set default response
        $errors = [];
        // let verify the data
        // check
        if (!$post['bankName']) {
            $errors['errors'][] = 'Bank name is required.';
        }
        if (strlen($post['routingNumber']) != 9) {
            $errors['errors'][] = 'Routing number is required. It must consists of 9 digits.';
        }
        //
        if (!$post['accountNumber']) {
            $errors['errors'][] = 'Account number is required.';
        }
        if (!in_array($post['accountType'], ['Savings', 'Checking'])) {
            $errors['errors'][] = 'Account type can be either \'Savings\' or \'Checking\'.';
        }
        // check if error happened
        if ($errors['errors']) {
            return $doReturn ? $errors : SendResponse(200, $errors);
        }
        // set request array
        $request = [];
        $request['name'] = $post['bankName'];
        $request['routing_number'] = $post['routingNumber'];
        $request['account_number'] = $post['accountNumber'];
        $request['account_type'] = $post['accountType'];
        // make request
        $response = addEmployeeBankAccountToGusto(
            $request,
            $gustoEmployeeDetails['payroll_employee_uuid'],
            $gustoCompany
        );
        // check for errors
        if ($errors = hasGustoErrors($response)) {
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        // set insert array
        $insertArray = [];
        $insertArray['employee_sid'] = $post['employeeId'];
        $insertArray['direct_deposit_id'] = 0;
        $insertArray['payroll_bank_uuid'] = $response['uuid'];
        $insertArray['name'] = $response['name'];
        $insertArray['routing_number'] = $response['routing_number'];
        $insertArray['account_number'] = $response['hidden_account_number'];
        $insertArray['account_type'] = $response['account_type'];
        $insertArray['account_percentage'] = 0;
        $insertArray['is_deleted'] = 0;
        $insertArray['created_at'] = $insertArray['updated_at'] = getSystemDate();
        // insert it
        $this->db
            ->insert('payroll_employee_bank_accounts', $insertArray);
        // send response
        return $doReturn ? $errors : sendResponse(200, [
            'success' => 'Employee\'s bank account is successfully updated.'
        ]);
    }

    /**
     * Handle job
     * 
     * @param array $jobs
     * @param int   $employeeId
     * 
     * @return int
     */
    public function checkAndAddJobs(array $jobs, int $employeeId)
    {
        // check for empty compensations
        if (!$jobs) {
            return false;
        }
        // for non-array
        if (!isset($jobs[0])) {
            $t = $jobs;
            $jobs = [];
            $jobs[0] = $t;
        }
        //
        $activeJobId = 0;
        // loop through compensations
        foreach ($jobs as $job) {
            // add the job
            $jobArray = [];
            $jobArray['payroll_uid'] = $job['uuid'];
            $jobArray['payroll_job_id'] = NULL;
            $jobArray['payroll_location_id'] = $job['location_uuid'];
            $jobArray['current_compensation_id'] = $job['current_compensation_uuid'];
            $jobArray['title'] = $job['title'];
            $jobArray['rate'] = $job['rate'];
            $jobArray['payment_unit'] = $job['payment_unit'];
            $jobArray['hire_date'] = $job['hire_date'];
            $jobArray['version'] = $job['version'];
            $jobArray['last_modified_by'] = 0;
            $jobArray['is_primary'] = $job['primary'];
            $jobArray['is_deleted'] = 0;

            // set where array
            $where = ['employee_sid' => $employeeId, 'payroll_uid' => $job['uuid']];
            // check if job exists
            if ($jobRecord = $this->db->select('sid')->where($where)->get('payroll_employee_jobs')->row_array()) {
                // job found
                if ($job['primary']) {
                    $activeJobId = $jobRecord['sid'];
                }
                //
                $jobId = $jobRecord['sid'];
                $jobArray['updated_at'] = getSystemDate();
                //
                $this->db->where('sid', $jobRecord['sid'])->update('payroll_employee_jobs', $jobArray);
            } else {
                // insert job
                $jobArray['employee_sid'] = $employeeId;
                $jobArray['created_at'] = $jobArray['updated_at'] = getSystemDate();
                //
                $jobId = $this->db->insert('payroll_employee_jobs', $jobArray);
                //
                if ($job['primary']) {
                    $activeJobId = $jobId;
                }
            }
            // handle the compensations
            $this->checkAndCompensation($job['compensations'], $jobId);
        }

        //
        return $activeJobId;
    }

    /**
     * Handle job compensations
     * 
     * @param array $compensations
     * @param int   $jobId
     * 
     * @return bool
     */
    public function checkAndCompensation(array $compensations, int $jobId)
    {
        // check for empty compensations
        if (!$compensations) {
            return false;
        }

        // loop through compensations
        foreach ($compensations as $compensation) {
            // set array
            $compensationArray = [];
            $compensationArray['payroll_job_sid'] = $jobId;
            $compensationArray['payroll_id'] = $compensation['uuid'];
            $compensationArray['rate'] = $compensation['rate'];
            $compensationArray['payment_unit'] = $compensation['payment_unit'];
            $compensationArray['flsa_status'] = $compensation['flsa_status'];
            $compensationArray['version'] = $compensation['version'];
            $compensationArray['effective_date'] = $compensation['effective_date'];
            $compensationArray['adjust_for_minimum_wage'] = $compensation['adjust_for_minimum_wage'];
            $compensationArray['minimum_wages'] = json_encode($compensation['minimum_wages']);
            //
            $where = ['payroll_job_sid' => $jobId, 'payroll_id' => $compensation['uuid']];
            // check if already exists
            if (!$this->db->where($where)->count_all_results('payroll_employee_job_compensations')) {
                // insert
                $compensationArray['last_updated_by'] = 0;
                $compensationArray['created_at'] = $compensationArray['updated_at'] = getSystemDate();
                //
                $this->db->insert('payroll_employee_job_compensations', $compensationArray);
            } else {
                // update
                $compensationArray['updated_at'] = getSystemDate();
                $this->db->where($where)->update('payroll_employee_job_compensations', $compensationArray);
            }
        }
        //
        return true;
    }

    /**
     * Update job compensation and versions
     * 
     * @param array  $post
     * @param int    $employeeId
     * @param string $employeeUid
     * @param array  $gustoCompany
     * @return array
     */
    public function updateEmployeeCompensationOnGusto(
        array $post,
        int $employeeId,
        string $employeeUId,
        array $gustoCompany
    ) {
        // get the active compensation
        $employeeCompensation = $this->db
            ->select('
            payroll_employee_job_compensations.sid,
            payroll_employee_job_compensations.version,
            payroll_employee_job_compensations.payroll_id
        ')
            ->join(
                'payroll_employee_job_compensations',
                'payroll_employee_job_compensations.payroll_id = payroll_employee_jobs.current_compensation_id',
                'inner'
            )
            ->where([
                'payroll_employee_jobs.employee_sid' => $employeeId,
                'payroll_employee_jobs.is_primary' => 1
            ])
            ->get('payroll_employee_jobs')
            ->row_array();
        //
        if (!$employeeCompensation) {
            return ['errors' => ['Compensation not found.']];
        }
        // set update array
        $request = [];
        $request['version'] = $employeeCompensation['version'];
        $request['rate'] = $post['rate'];
        $request['payment_unit'] = $post['payment_unit'];
        $request['flsa_status'] = $post['flsa_status'];
        // make call
        $response = updateEmployeeJobCompensation(
            $request,
            $employeeCompensation['payroll_id'],
            $gustoCompany
        );
        // check for errors
        if ($errors = hasGustoErrors($response)) {
            return $errors;
        }
        // set update array
        $updateArray = [];
        $updateArray['rate'] = $response['rate'];
        $updateArray['payment_unit'] = $response['payment_unit'];
        $updateArray['flsa_status'] = $response['flsa_status'];
        $updateArray['adjust_for_minimum_wage'] = $response['adjust_for_minimum_wage'];
        $updateArray['minimum_wages'] = json_encode($response['minimum_wages']);
        $updateArray['version'] = $response['version'];
        $updateArray['effective_date'] = $response['effective_date'];
        $updateArray['updated_at'] = getSystemDate();
        // update it
        $this->db->where([
            'sid' => $employeeCompensation['sid']
        ])->update(
            'payroll_employee_job_compensations',
            $updateArray
        );
        //
        $gustoJob = getEmployeeJobsFromGusto(
            $employeeUId,
            $gustoCompany
        );
        // check for errors
        if ($errors = hasGustoErrors($gustoJob)) {
            return $errors;
        }
        $this->checkAndAddJobs($gustoJob, $employeeId);
        //
        return [];
    }

    /**
     * Push employee bank accounts to Gusto
     * 
     * @param int $employeeId
     * @return array
     */
    public function syncEmployeeBankAccountsWithGusto(int $companyId, int $employeeId)
    {
        //
        $errors = [];
        // get gusto employee details
        $gustoEmployeeDetails = $this->db
            ->select('payroll_employee_uuid, version')
            ->where([
                'company_sid' => $companyId,
                'employee_sid' => $employeeId
            ])
            ->get('payroll_employees')
            ->row_array();
        // double check the intrusion
        if (!$gustoEmployeeDetails) {
            // add the error
            $errors['errors'][] = 'Employee not found.';
            // send back response
            return $errors;
        }
        // get the company details
        $gustoCompany =
            $this->db
            ->select('
                gusto_company_sid, 
                gusto_company_uid,
                access_token,
                refresh_token
            ')
            ->where([
                'company_sid' => $companyId
            ])
            ->get('payroll_companies')
            ->row_array();

        // double check the intrusion
        if (!$gustoCompany) {
            // add the error
            $errors['errors'][] = 'Company credentials not found.';
            // send back response
            return $errors;
        }
        // get the employee bank accounts
        $bankAccounts  = $this->db
            ->select('
            sid,
            account_title,
            routing_transaction_number,
            account_number,
            financial_institution_name,
            account_type,
            deposit_type,
            account_percentage,
        ')
            ->where([
                'users_type' => 'employee',
                'users_sid' => $employeeId
            ])
            ->limit(2)
            ->get('bank_account_details')
            ->result_array();

        // check bank accounts
        if (!$bankAccounts) {
            $errors['errors'][] = 'No bank accounts found.';
            return $errors;
        }
        // loop through the data
        foreach ($bankAccounts as $account) {
            // check if linked with Gusto
            $gustoBankAccount = $this->db
                ->select('sid, direct_deposit_id, payroll_bank_uuid')
                ->where('direct_deposit_id', $account['sid'])
                ->where('is_deleted', 0)
                ->get('payroll_employee_bank_accounts')
                ->row_array();
            //
            if (!$gustoBankAccount) {
                // need to add
                // create request array
                $request = [];
                $request['name'] = $account['account_title'];
                $request['routing_number'] = $account['routing_transaction_number'];
                $request['account_number'] = $account['account_number'];
                $request['account_type'] = ucfirst($account['account_type']);
                // make call
                $response = addEmployeeBankAccountToGusto(
                    $request,
                    $gustoEmployeeDetails['payroll_employee_uuid'],
                    $gustoCompany
                );
                // check for errors
                if ($err = hasGustoErrors($response)) {
                    $errors['errors'] = $err['errors'];
                    continue;
                }
                // set insert array
                $insertArray = [];
                $insertArray['employee_sid'] = $employeeId;
                $insertArray['direct_deposit_id'] = $account['sid'];
                $insertArray['payroll_bank_uuid'] = $response['uuid'];
                $insertArray['account_type'] = $response['account_type'];
                $insertArray['name'] = $response['name'];
                $insertArray['routing_number'] = $response['routing_number'];
                $insertArray['account_number'] = $response['hidden_account_number'];
                $insertArray['account_percentage'] = $account['account_percentage'];
                $insertArray['is_deleted'] = 0;
                $insertArray['created_at'] = $insertArray['updated_at'] = getSystemDate();
                // insert
                $this->db->insert('payroll_employee_bank_accounts', $insertArray);
            }
        }
        // get employee payment method
        $employeePaymentMethod = $this->db
            ->select('payment_method')
            ->where('employee_sid', $employeeId)
            ->get('payroll_employee_payment_method')
            ->row_array()['payment_method'];
        //
        if ($employeePaymentMethod == 'Direct Deposit') {
            $this->db
                ->where('employee_sid', $employeeId)
                ->delete('payroll_employee_payment_method');
            //
            $this->handleEmployeePaymentMethodForOnboarding(
                [
                    'employeeId' => $employeeId,
                    'companyId' => $companyId,
                    'paymentMethod' => $employeePaymentMethod
                ],
                $gustoEmployeeDetails,
                $gustoCompany,
                true
            );
        }
    }

    /**
     * 
     */
    public function checkAndFinishCompanyOnboard(int $companyId, bool $doReturn = false)
    {
        // get the company details
        $gustoCompany =
            $this->db
            ->select('
                gusto_company_sid, 
                gusto_company_uid,
                access_token,
                refresh_token
            ')
            ->where([
                'company_sid' => $companyId
            ])
            ->get('payroll_companies')
            ->row_array();

        // double check the intrusion
        if (!$gustoCompany) {
            // add the error
            $errors['errors'][] = 'Company credentials not found.';
            // send back response
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        // get the onboard status
        $response = getCompanyOnboardStatusFromGusto(
            $gustoCompany
        );
        //
        if ($errors = hasGustoErrors($response)) {
            // send back response
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        //
        if ($response['onboarding_completed']) {
            //
            $errors = ['success' => ['Company onboarding already completed.']];
            //
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        // 
        $isAllStepsDone = 1;
        $allSteps = [];
        //
        foreach ($response['onboarding_steps'] as $step) {
            //
            $allSteps[] = [
                'title' => $step['title'],
                'required' => $step['required'],
                'completed' => $step['completed']
            ];
            //
            if ($step['required'] == 1 && $step['completed'] == 0) {
                $isAllStepsDone = 0;
            }
        }
        //
        if ($isAllStepsDone == 0) {
            //
            $errors['errors'] = [];
            $errors['errors'][] = ['Please complete the required onboarding steps.'];
            $errors['steps'] = $allSteps;
        } else {
            // onboard a company
            $response = finishCompanyOnboardOnGusto($gustoCompany);
            //
            if ($errors = hasGustoErrors($response)) {
                // send back response
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            $response = approveCompanyOnboardOnGusto($gustoCompany);
            //
            if ($errors = hasGustoErrors($response)) {
                // send back response
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            $errors = [];
            $errors['success'] = ['All the required steps for company onboarding are completed, and company successfully onboard.'];
        }
        //
        return $doReturn ? $errors : sendResponse(200, $errors);
    }

    /**
     * 
     */
    public function checkAndFinishEmployeeOnboard(int $employeeId, bool $doReturn = false)
    {
        //
        $errors = [];
        // get gusto employee details
        $gustoEmployeeDetails = $this->db
            ->select('payroll_employee_uuid, version, company_sid')
            ->where([
                'employee_sid' => $employeeId
            ])
            ->get('payroll_employees')
            ->row_array();
        // double check the intrusion
        if (!$gustoEmployeeDetails) {
            // add the error
            $errors['errors'][] = 'Employee not found.';
            // send back response
            return $errors;
        }
        // get the company details
        $gustoCompany =
            $this->db
            ->select('
                gusto_company_sid, 
                gusto_company_uid,
                access_token,
                refresh_token
            ')
            ->where([
                'company_sid' => $gustoEmployeeDetails['company_sid']
            ])
            ->get('payroll_companies')
            ->row_array();

        // double check the intrusion
        if (!$gustoCompany) {
            // add the error
            $errors['errors'][] = 'Company credentials not found.';
            // send back response
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        // get the onboard status
        $response = getEmployeeOnboardStatusFromGusto(
            $gustoEmployeeDetails['payroll_employee_uuid'],
            $gustoCompany
        );
        //
        if ($errors = hasGustoErrors($response)) {
            // send back response
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        //
        if ($response['onboarding_status'] != 'admin_onboarding_incomplete') {
            //
            $this->db->where('employee_sid', $employeeId)->update(
                'payroll_employees',
                [
                    'onboard_completed' => 1
                ]
            );
            //
            $errors = ['success' => ['Employee onboarding already completed.']];
            //
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        // 
        $isAllStepsDone = 1;
        $allSteps = [];
        //
        foreach ($response['onboarding_steps'] as $step) {
            //
            $allSteps[] = [
                'title' => $step['title'],
                'required' => $step['required'],
                'completed' => $step['completed']
            ];
            //
            if ($step['required'] == 1 && $step['completed'] == 0) {
                $isAllStepsDone = 0;
            }
        }
        //
        if ($isAllStepsDone == 0) {
            //
            $errors['errors'] = [];
            $errors['errors'][] = ['Please complete the required onboarding steps.'];
            $errors['steps'] = $allSteps;
        } else {
            // onboard a company
            $response = finishEmployeeOnboardOnGusto(
                ['onboarding_status' => 'onboarding_completed'],
                $gustoEmployeeDetails['payroll_employee_uuid'],
                $gustoCompany
            );
            //
            if ($errors = hasGustoErrors($response)) {
                // send back response
                return $doReturn ? $errors : sendResponse(200, $errors);
            }
            //
            $this->db->where('employee_sid', $employeeId)->update(
                'payroll_employees',
                [
                    'onboard_completed' => 1
                ]
            );
            $errors = [];
            $errors['success'] = ['All the required steps for employee onboarding are completed, and employee successfully onboard.'];
        }
        //
        return $doReturn ? $errors : sendResponse(200, $errors);
    }

    /**
     * 
     */
    function getCompanyEmployees(
        $companyId,
        $columns = '*',
        $whereArray = []
    ) {
        //
        $whereArray = !empty($whereArray) ? $whereArray : ["users.active" => 1, "users.terminated_status" => 0];
        //
        $redo = false;
        //
        if ($columns === true) {
            //
            $redo = true;
            //
            $columns = [];
            $columns[] = "users.sid";
            $columns[] = "users.first_name";
            $columns[] = "users.last_name";
            $columns[] = "users.email";
            $columns[] = "users.joined_at";
            $columns[] = "users.registration_date";
            $columns[] = "users.ssn";
            $columns[] = "users.timezone";
            $columns[] = "company.timezone as company_timezone";
            $columns[] = "users.dob";
            $columns[] = "users.profile_picture";
            $columns[] = "users.access_level";
            $columns[] = "users.access_level_plus";
            $columns[] = "users.user_shift_hours";
            $columns[] = "users.user_shift_minutes";
            $columns[] = "users.is_executive_admin";
            $columns[] = "users.job_title";
            $columns[] = "users.pay_plan_flag";
            $columns[] = "users.full_employment_application";
            $columns[] = "users.on_payroll";
            $columns[] = "payroll_employees.onboard_completed";
        }
        //
        $query =
            $this->db
            ->select($columns)
            ->join("users as company", "users.parent_sid = company.sid", 'inner')
            ->join("payroll_employees", "payroll_employees.employee_sid = users.sid", 'left')
            ->where("users.parent_sid", $companyId)
            ->where($whereArray)
            ->order_by("users.first_name", 'asc')
            ->get('users');
        //
        $records = $query->result_array();
        //
        $query = $query->free_result();
        //
        if ($redo && !empty($records)) {
            //
            $newRecords = [];
            //
            $updateArray = [];
            //
            foreach ($records as $record) {
                //
                $newRecords[$record['sid']] = [
                    'sid' => $record['sid'],
                    'timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'first_name' => ucwords($record['first_name']),
                    'last_name' => ucwords($record['last_name']),
                    'name' => ucwords($record['first_name'] . ' ' . $record['last_name']),
                    'role' => remakeEmployeeName($record, false),
                    'plus' => $record['access_level_plus'],
                    'email' => $record['email'],
                    'image' => getImageURL($record['profile_picture']),
                    'joined_on' => $record['joined_at'],
                    'ssn' => $record['ssn'],
                    'dob' => $record['dob'],
                    'shift_hours' => $record['user_shift_hours'],
                    'shift_minutes' => $record['user_shift_minutes'],
                    'on_payroll' => $record['on_payroll'],
                    'onboard_completed' => $record['onboard_completed'],
                ];
                //
                if (!empty($record['timezone'])) {
                    $newRecords[$record['sid']]['timezone'] = $record['timezone'];
                } else if (!empty($record['company_timezone'])) {
                    $newRecords[$record['sid']]['timezone'] = $record['company_timezone'];
                }
                //
                if (!empty($record['full_employment_application'])) {
                    //
                    $fef = unserialize($record['full_employment_application']);
                    //
                    if (empty($newRecords[$record['sid']]['ssn']) && isset($fef['TextBoxSSN'])) {
                        $newRecords[$record['sid']]['ssn'] = $fef['TextBoxSSN'];
                        //
                        $updateArray[$record['sid']]['sid'] = $record['sid'];
                        $updateArray[$record['sid']]['ssn'] = $fef['TextBoxSSN'];
                    }
                    //
                    if (empty($newRecords[$record['sid']]['dob']) && isset($fef['TextBoxDOB'])) {
                        $newRecords[$record['sid']]['dob'] = DateTime::createfromformat('m-d-Y', $fef['TextBoxDOB'])->format('Y-m-d');
                        $updateArray[$record['sid']]['sid'] = $record['sid'];
                        $updateArray[$record['sid']]['dob'] = $newRecords[$record['sid']]['dob'];
                    }
                }
            }
            //
            if (!empty($updateArray)) {
                $this->db->update_batch($this->U, $updateArray, 'sid');
            }
            //
            $records = $newRecords;
            //
            unset($newRecords);
        }
        //
        return $records;
    }

    /**
     * Check if company has a primary admin
     * to handle payroll
     * 
     * @param integer $companyId
     * @return
     */
    function HasPrimaryAdmin($companyId)
    {
        //
        return $this->db
            ->where('company_sid', $companyId)
            ->count_all_results($this->tables['PayrollCompanyAdmin']);
    }

    /**
     * Check if company has a primary admin
     * to handle payroll
     * 
     * @param integer $companyId
     * @return
     */
    function GetPrimaryAdmin($companyId)
    {
        //
        return $this->db
            ->where('company_sid', $companyId)
            ->get($this->tables['PayrollCompanyAdmin'])
            ->row_array();
    }

    public function GetCompanyOnboardDetails($companyId)
    {
        //
        return
            $this->db
            ->select('
			users.on_payroll,
			payroll_companies.gusto_company_uid
		')
            ->join('payroll_companies', 'payroll_companies.company_sid = users.sid', 'left')
            ->where('users.sid', $companyId)
            ->get('users')
            ->row_array();
    }

    /**
     * 
     */
    function GetCompanyDetails(
        $companyId,
        $columns = '*'
    ) {
        //
        $query =
            $this->db
            ->select($columns)
            ->where('sid', $companyId)
            ->get($this->tables['U']);
        //
        $record = $query->row_array();
        //
        $query = $query->free_result();
        //
        return $record;
    }

    /**
     * 
     */
    function CheckAndInsertPayroll(
        $companyId,
        $employerId,
        $payrollUid,
        $payroll
    ) {
        //
        $isNew = false;
        $doAdd = true;
        //
        $date = date('Y-m-d H:i:s', strtotime('now'));
        // Check if the payroll already
        // been added
        if (
            !$this->db
                ->where('payroll_uid', $payrollUid)
                ->count_all_results($this->tables['P'])
        ) {
            // Let's insert the payroll
            $this->db
                ->insert(
                    $this->tables['P'],
                    [
                        'company_sid' => $companyId,
                        'payroll_uid' => $payrollUid,
                        'version' => $payroll['version'],
                        'payroll_id' => $payroll['payroll_id'],
                        'start_date' => $payroll['pay_period']['start_date'],
                        'end_date' => $payroll['pay_period']['end_date'],
                        'check_date' => $payroll['check_date'],
                        'deadline_date' => $payroll['payroll_deadline'],
                        'payroll_json' => json_encode($payroll),
                        'is_processed' => 0,
                        'created_by' => $employerId,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]
                );
            //
            $isNew = true;
        } else {
            if (!empty($payroll)) {
                $this->db
                    ->where('payroll_uid', $payrollUid)
                    ->update(
                        $this->tables['P'],
                        [
                            'payroll_json' => json_encode($payroll)
                        ]
                    );
            }
        }
        //
        if (!$isNew) {
            // Get the last history
            $historyVersion = $this->GetPayrollHistory($payroll['payroll_id'], ['version'])['version'];
            //
            if ($historyVersion == $payroll['version']) {
                $doAdd = false;
            }
        }
        //
        if (!$doAdd) {
            return false;
        }
        // Let's add a history
        $this->db
            ->insert(
                $this->tables['PH'],
                [
                    'payroll_id' => $payroll['payroll_id'],
                    'version' => $payroll['version'],
                    'created_by' => $employerId,
                    'content' => json_encode($payroll),
                    'created_at' => $date
                ]
            );
    }

    /**
     * 
     */
    function GetPayrollHistory(
        $payrollId,
        $columns = '*'
    ) {
        //
        $query =
            $this->db
            ->select($columns)
            ->where('payroll_id', $payrollId)
            ->order_by('sid', 'desc')
            ->get($this->tables['PH']);
        //
        $record = $query->row_array();
        $query = $query->free_result();
        //
        return $record;
    }


    /**
     * 
     */
    function UpdateCompany($companyId, $array)
    {
        //
        $this->db
            ->where('sid', $companyId)
            ->update($this->tables['U'], $array);
    }

    /**
     * Get company payroll details
     * @param integer $companyId
     * @return
     */
    function GetPayrollCompany($companyId)
    {
        //
        return $this->db
            ->select('refresh_token, access_token, gusto_company_uid, onbording_level, onboarding_status')
            ->where('company_sid', $companyId)
            ->get($this->tables['PC'])
            ->row_array();
    }

    //
    public function InsertPayroll($table, $dataArray)
    {
        //
        $this->db->insert($table, $dataArray);
        return $this->db->insert_id();
    }

    //
    public function GetPayrollColumn($table, $where, $col = 'sid', $single = true)
    {
        //
        $query =
            $this->db
            ->select($col)
            ->where($where)
            ->get($table)
            ->row_array();
        //
        if (!$single) {
            return $query;
        }
        return $query ? $query[$col] : '';
    }

    //
    function GetCompany($companyId, $columns)
    {
        //
        $query =
            $this->db
            ->where('company_sid', $companyId)
            ->select($columns)
            ->get($this->tables['PC']);
        //
        $record = $query->row_array();
        //
        $query = $query->free_result();
        //
        return $record;
    }


    /**
     * Sync company data from Gusto to system
     *
     * @param int $companyId
     * @return bool
     */
    public function pushCompanyDataToGusto(int $companyId)
    {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // push company locations
        $this->pushCompanyLocationToGusto($companyId, $companyDetails);
        // lets push the company admins
        $this->pushCompanyAdmins($companyId, $companyDetails);
        // lets push the company payment config
        $this->pushCompanyPaymentConfig($companyId, $companyDetails);
        //
        $this->createCustomEarningTypeOnGusto($companyId, $companyDetails, 'Paid Time Off');
    }

    /**
     * add company location to Gusto
     *
     * @param int $companyId
     * @param int $companyDetails
     * @param bool $doReturn
     * @return array
     */
    private function pushCompanyLocationToGusto(int $companyId, array $companyDetails, bool $doReturn = true)
    {
        // get company current location
        $companyLocation = $this->getUserLocation($companyId);
        //
        $request = [];
        $request['street_1'] = $companyLocation['street_1'];
        $request['street_2'] = $companyLocation['street_2'];
        $request['country'] = $companyLocation['country'];
        $request['city'] = $companyLocation['city'];
        $request['zip'] = $companyLocation['zip'];
        $request['state'] = $companyLocation['state_code'];
        $request['phone_number'] = $companyLocation['phone'];
        $request['mailing_address'] = $companyLocation['mailing_address'];
        $request['filing_address'] = $companyLocation['filing_address'];
        //
        $response = AddCompanyLocation($request, $companyDetails);
        //
        $errors = hasGustoErrors($response);
        //
        if ($errors) {
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        //
        $insertArray = [];
        $insertArray['company_sid'] = $companyId;
        $insertArray['gusto_uuid'] = $response['uuid'];
        $insertArray['country'] = $response['country'];
        $insertArray['state'] = $response['state'];
        $insertArray['city'] = $response['city'];
        $insertArray['street_1'] = $response['street_1'];
        $insertArray['street_2'] = $response['street_2'];
        $insertArray['zip'] = $response['zip'];
        $insertArray['phone_number'] = $response['phone_number'];
        $insertArray['mailing_address'] = $response['mailing_address'];
        $insertArray['filing_address'] = $response['filing_address'];
        $insertArray['version'] = $response['version'];
        $insertArray['active'] = $response['active'];
        $insertArray['last_updated_by'] = 0;
        $insertArray['created_at'] =
            $insertArray['updated_at'] = getSystemDate();
        //
        $this->InsertPayroll('payroll_company_locations', $insertArray);
        //
        return $doReturn ? $response : sendResponse(200, $response);;
    }

    /**
     * push company admins to Gusto
     *
     * @param int $companyId
     * @param int $companyDetails
     * @param bool $doReturn
     * @return array
     */
    private function pushCompanyAdmins(int $companyId, array $companyDetails, bool $doReturn = true)
    {
        // get company current location
        $companyAdmins = $this->getCompanyAdmins($companyId);
        // check for empty
        if (!$companyAdmins) {
            return $doReturn ? $companyAdmins : sendResponse(200, $companyAdmins);
        }
        // loop through the data
        foreach ($companyAdmins as $ca) {
            $this->pushCompanyAdmin($companyId, $ca);
        }
    }

    /**
     * Push admin to Gusto
     */
    private function pushCompanyAdmin($companyId, $ca)
    {
        //
        if ($ca['gusto_uuid']) {
            return ['errors' => 'Admin already exists.'];
        }
        // fetch all admins
        $gustoAdmins = $this->gusto_payroll_model->fetchAllAdmins($companyId);
        //
        if ($gustoAdmins[$ca['email_address']]) {
            //
            $this->db
                ->where('sid', $ca['sid'])
                ->update('payroll_company_admin', [
                    'gusto_uuid' => $gustoAdmins[$ca['email_address']]['uuid']
                ]);
            return ['errors' => 'Admin already exists.'];
        }
        // add a new one
        $this->gusto_payroll_model->moveAdminToGusto([
            'first_name' => $ca['first_name'],
            'last_name' => $ca['last_name'],
            'email' => $ca['email_address']
        ], $companyId);
    }


    /**
     * push company payment config to Gusto
     *
     * @param int $companyId
     * @param int $companyDetails
     * @param bool $doReturn
     * @return array
     */
    private function pushCompanyPaymentConfig(
        int $companyId,
        array $companyDetails,
        bool $doReturn = true
    ) {
        //
        $response = UpdatePaymentConfig([
            'fast_payment_limit' => 500,
            'payment_speed' => '4-day'
        ], $companyDetails);
        //
        $errors = hasGustoErrors($response);
        //
        if ($errors) {
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        //
        $this->db->insert('payroll_settings', [
            'company_sid' => $companyId,
            'partner_uid' => $response['partner_uuid'],
            'partner_uuid' => $response['partner_uuid'],
            'payment_speed' => $response['payment_speed'],
            'fast_payment_limit' => $response['fast_payment_limit'],
            'created_at' => getSystemDate(),
            'updated_at' => getSystemDate()
        ]);
        //
        return $doReturn ? $response : sendResponse(200, $response);
    }

    /**
     * push company payment config to Gusto
     *
     * @param int $companyId
     * @param int $companyDetails
     * @param string $type
     * @param bool $doReturn
     * @return array
     */
    private function createCustomEarningTypeOnGusto(
        int $companyId,
        array $companyDetails,
        string $type,
        bool $doReturn = true
    ) {
        //
        $response = createCustomEarningTypeOnGusto($type, $companyDetails);
        //
        $errors = hasGustoErrors($response);
        //
        if ($errors) {
            return $doReturn ? $errors : sendResponse(200, $errors);
        }
        //
        $this->db->insert('payroll_company_earning_types', [
            'company_sid' => $companyId,
            'gusto_uuid' => $response['uuid'],
            'earning_name' => $response['name'],
            'created_at' => getSystemDate(),
            'updated_at' => getSystemDate()
        ]);
        //
        return $doReturn ? $response : sendResponse(200, $response);
    }

    /**
     * get the user location
     *
     * @param int $userId
     * @return array
     */
    public function getUserLocation(int $userId)
    {
        // set default array
        $locationArray = [
            'street_1' => '',
            'street_2' => '',
            'country' => 'USA',
            'state_code' => '',
            'city' => '',
            'zip' => '',
            'phone' => '',
            'filing_address' => true,
            'mailing_address' => true
        ];
        // get location
        $record = $this->db
            ->select('
                users.Location_City,
                users.Location_Address,
                users.Location_Address_2,
                users.Location_State,
                users.Location_ZipCode,
                users.PhoneNumber
            ')
            ->where('users.sid', $userId)
            ->get('users')
            ->row_array();
        // check if record found
        if ($record) {
            $locationArray['street_1'] = trim($record['Location_Address']);
            $locationArray['street_2'] = trim($record['Location_Address_2']);
            $locationArray['state_code'] = db_get_state_code_only(trim($record['Location_State']));
            $locationArray['city'] = trim($record['Location_City']);
            $locationArray['zip'] = trim($record['Location_ZipCode']);
            $locationArray['phone'] = str_replace('+1', '', trim($record['PhoneNumber']));
        }
        // return location array
        return $locationArray;
    }

    /**
     * check and save store admin for Gusto
     *
     * @param int $companyId
     * @param array $adminDetails
     */
    public function checkAndSetStoreAdminForGusto(int $companyId, array $adminDetails)
    {
        // set where array
        $whereArray = [
            'company_sid' => $companyId,
            'email_address' => $adminDetails['email_address'],
            'is_store_admin' => 1
        ];
        // check if admin already exists
        if (!$this->db->where($whereArray)->count_all_results('payroll_company_admin')) {
            $this->db->insert(
                'payroll_company_admin',
                [
                    'company_sid' => $companyId,
                    'gusto_uuid' => '',
                    'first_name' => $adminDetails['first_name'],
                    'last_name' => $adminDetails['last_name'],
                    'email_address' => $adminDetails['email_address'],
                    'phone_number' => $adminDetails['phone_number'],
                    'is_store_admin' => 1,
                    'created_at' => getSystemDate(),
                    'updated_at' => getSystemDate(),
                ]
            );
        }
    }

    /**
     * get the company admins
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyAdmins(int $companyId)
    {
        // get records
        return $this->db
            ->select('
                sid,
                gusto_uuid,
                first_name,
                last_name,
                email_address,
                phone_number
            ')
            ->where('company_sid', $companyId)
            ->where('is_store_admin', 0)
            ->get('payroll_company_admin')
            ->result_array();
    }
    
    /**
     * get the company bank account
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyBankAccount(int $companyId)
    {
        // get records
        return $this->db
            ->select('
                payroll_uuid
            ')
            ->where('company_sid', $companyId)
            ->where('status', 'awaiting_deposits')
            ->get('payroll_company_bank_accounts')
            ->row_array();
    }
}
