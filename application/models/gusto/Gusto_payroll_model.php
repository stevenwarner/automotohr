<?php defined('BASEPATH') || exit('No direct script access allowed');

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
        // lets sync the company bank accounts
        $this->syncCompanyBankAccounts($companyId, $companyDetails);
        // lets sync the company documents
        $this->syncCompanyDocuments($companyId, $companyDetails);
        // lets sync employees
        $this->syncEmployees($companyId, $companyDetails);
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
     * Sync company bank accounts with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncCompanyBankAccounts(int $companyId, array $companyDetails)
    {
        //
        $response = getCompanyBankAccounts($companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            //
            $mainTable = 'payroll_company_bank_accounts';

            foreach ($response as $bankAccount) {
                //
                $dataArray = [];
                $dataArray['routing_number'] = $bankAccount['routing_number'];
                $dataArray['account_number'] = $bankAccount['hidden_account_number'];
                $dataArray['account_type'] = strtolower($bankAccount['account_type']);
                $dataArray['status'] = $bankAccount['verification_status'];
                $dataArray['verification_type'] = $bankAccount['verification_type'];
                //
                $whereArray = [
                    'company_sid' => $companyId,
                    'payroll_uuid' => $bankAccount['uuid']
                ];
                //
                if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
                    //
                    $dataArray['payroll_uuid'] = $bankAccount['uuid'];
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
    public function syncCompanyDocuments(int $companyId, array $companyDetails)
    {
        //
        $response = getCompanyDocuments($companyDetails, [
            'X-Gusto-API-Version: 2023-03-01'
        ]);
        //
        if (!isset($response['errors']) && $response) {
            //
            $mainTable = 'payroll_company_documents';

            foreach ($response as $bankAccount) {
                //
                $dataArray = [];
                $dataArray['document_name'] = $bankAccount['name'];
                $dataArray['document_title'] = $bankAccount['title'];
                $dataArray['document_description'] = $bankAccount['description'];
                $dataArray['require_signing'] = (int)$bankAccount['requires_signing'];
                $dataArray['draft'] = (int)$bankAccount['draft'];
                //
                $whereArray = [
                    'company_sid' => $companyId,
                    'gusto_uuid' => $bankAccount['uuid']
                ];
                //
                if (!$this->db->where($whereArray)->count_all_results($mainTable)) {
                    //
                    $dataArray['gusto_uuid'] = $bankAccount['uuid'];
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
     * Sync employees with Gusto
     *
     * @param int   $companyId
     * @param array $companyDetails
     * @return bool
     */
    public function syncEmployees(int $companyId, array $companyDetails)
    {
        // get all employees on Gusto
        $gustoLinkedEmployees = $this->db
            ->select('
            employee_sid,
            company_sid,
            work_address_sid,
            payroll_employee_id,
            payroll_employee_uuid,
            version
        ')
            ->where(['company_sid' => $companyId])
            ->get('payroll_employees')
            ->result_array();
        //
        if (!$gustoLinkedEmployees) {
            return false;
        }
        //
        foreach ($gustoLinkedEmployees as $employee) {
            // get employee profile
            $gustoEmployee = getEmployeeFromGusto($employee['payroll_employee_uuid'], $companyDetails);
            //
            if (!isset($gustoEmployee['errors']) && $gustoEmployee) {
                // update personal profile
                $this->db
                    ->where('employee_sid', $employee['employee_sid'])
                    ->update('payroll_employees', [
                        'version' => $gustoEmployee['version'],
                        'updated_at' => getSystemDate(),
                    ]);
                // home address
                if ($gustoEmployee['home_address']) {
                    //
                    $updArray = [];
                    $updArray['version'] = $gustoEmployee['home_address']['version'];
                    $updArray['street_1'] = $gustoEmployee['home_address']['street_1'];
                    $updArray['street_2'] = $gustoEmployee['home_address']['street_2'];
                    $updArray['city'] = $gustoEmployee['home_address']['city'];
                    $updArray['state'] = $gustoEmployee['home_address']['state'];
                    $updArray['zip'] = $gustoEmployee['home_address']['zip'];
                    $updArray['country'] = $gustoEmployee['home_address']['country'];
                    $updArray['active'] = $gustoEmployee['home_address']['active'];
                    //
                    if (!$this->db->where('employee_sid', $employee['employee_sid'])->count_all_results('payroll_employee_address')) {
                        //
                        $updArray['employee_sid'] = $employee['employee_sid'];
                        $updArray['created_at'] = $updArray['updated_at'] = getSystemDate();
                        // insert
                        $this->db->insert('payroll_employee_address', $updArray);
                    } else {
                        $updArray['updated_at'] = getSystemDate();
                        // update
                        $this->db->where('employee_sid', $employee['employee_sid'])->update('payroll_employee_address', $updArray);
                    }
                }
                // jobs
                if ($gustoEmployee['jobs']) {
                    //
                    foreach ($gustoEmployee['jobs'] as $job) {
                        // check if job already exists
                        $result = $this->db
                            ->select('sid')
                            ->where([
                                'employee_sid' => $employee['employee_sid'],
                                'payroll_job_id' => $job['id']
                            ])
                            ->get('payroll_employee_jobs')
                            ->row_array();
                        // set job array
                        $updArray = [];
                        $updArray['payroll_job_id'] = $job['id'];
                        $updArray['payroll_uid'] = $job['uuid'];
                        $updArray['payroll_location_id'] = $job['location_id'];
                        $updArray['current_compensation_uuid'] = $job['current_compensation_uuid'];
                        $updArray['current_compensation_id'] = $job['current_compensation_id'];
                        $updArray['title'] = $job['title'];
                        $updArray['hire_date'] = $job['hire_date'];
                        $updArray['rate'] = $job['rate'];
                        $updArray['payment_unit'] = $job['payment_unit'];
                        $updArray['is_primary'] = $job['primary'];
                        $updArray['version'] = $job['version'];
                        //
                        if (!$result) {
                            //
                            $updArray['employee_sid'] = $employee['employee_sid'];
                            $updArray['created_at'] = $updArray['updated_at'] = getSystemDate();
                            // insert
                            $this->db->insert(
                                'payroll_employee_jobs',
                                $updArray
                            );
                            $jobId = $this->db->insert_id();
                        } else {
                            // update
                            $updArray['updated_at'] = getSystemDate();
                            // insert
                            $this->db
                                ->where('employee_sid', $employee['employee_sid'])
                                ->update(
                                    'payroll_employee_jobs',
                                    $updArray
                                );
                            $jobId = $result['sid'];
                        }

                        // handle compensations
                        if ($job['compensations']) {
                            foreach ($job['compensations'] as $compensation) {
                                // check if compensation already exists
                                $result = $this->db
                                    ->select('sid')
                                    ->where([
                                        'payroll_job_sid' => $jobId
                                    ])
                                    ->get('payroll_employee_job_compensations')
                                    ->row_array();
                                // set job array
                                $updArray = [];
                                $updArray['gusto_job_id'] = $compensation['job_id'];
                                $updArray['payroll_id'] = $compensation['id'];
                                $updArray['rate'] = $compensation['rate'];
                                $updArray['payment_unit'] = $compensation['payment_unit'];
                                $updArray['version'] = $compensation['version'];
                                $updArray['flsa_status'] = $compensation['flsa_status'];
                                $updArray['effective_date'] = $compensation['effective_date'];
                                $updArray['adjust_for_minimum_wage'] = $compensation['adjust_for_minimum_wage'];
                                $updArray['minimum_wages'] = json_encode($compensation['minimum_wages']);

                                //
                                if (!$result) {
                                    //
                                    $updArray['payroll_job_sid'] = $jobId;
                                    $updArray['created_at'] = $updArray['updated_at'] = getSystemDate();
                                    // insert
                                    $this->db->insert(
                                        'payroll_employee_job_compensations',
                                        $updArray
                                    );
                                } else {
                                    // update
                                    $updArray['updated_at'] = getSystemDate();
                                    // insert
                                    $this->db
                                        ->where(['payroll_job_sid' => $jobId])
                                        ->update(
                                            'payroll_employee_job_compensations',
                                            $updArray
                                        );
                                }
                            }
                        }
                    }
                }
            }

            // get employee federal tax
            $gustoEmployeeFederalTax = getEmployeeFederalTaxFromGusto($employee['payroll_employee_uuid'], $companyDetails);
            //
            if (!isset($gustoEmployeeFederalTax['errors']) && $gustoEmployeeFederalTax) {
                // set update array
                $updArray = [];
                $updArray['filing_status'] = $gustoEmployeeFederalTax['filing_status'];
                $updArray['extra_withholding'] = $gustoEmployeeFederalTax['extra_withholding'];
                $updArray['multiple_jobs'] = $gustoEmployeeFederalTax['two_jobs'];
                $updArray['dependent'] = $gustoEmployeeFederalTax['dependents_amount'];
                $updArray['other_income'] = $gustoEmployeeFederalTax['other_income'];
                $updArray['deductions'] = $gustoEmployeeFederalTax['deductions'];
                $updArray['w4_data_type'] = $gustoEmployeeFederalTax['w4_data_type'];
                $updArray['version'] = $gustoEmployeeFederalTax['version'];
                $updArray['additional_withholding'] = $gustoEmployeeFederalTax['additional_withholding'] ? (int)$gustoEmployeeFederalTax['additional_withholding'] : 0;
                //
                if (!$this->db->where(['employee_sid' => $employee['employee_sid']])->count_all_results('payroll_employee_federal_tax')) {
                    //
                    $updArray['employee_sid'] = $employee['employee_sid'];
                    $updArray['company_sid'] = $employee['company_sid'];
                    $updArray['created_at'] = $updArray['updated_at'] = getSystemDate();
                    //
                    $this->db->insert('payroll_employee_federal_tax', $updArray);
                } else {
                    //
                    $updArray['updated_at'] = getSystemDate();
                    //
                    $this->db
                        ->where(['employee_sid' => $employee['employee_sid']])
                        ->update('payroll_employee_federal_tax', $updArray);
                }
            }

            // get employee state tax
            $gustoEmployeeStateTax = getEmployeeStateTaxFromGusto($employee['payroll_employee_uuid'], $companyDetails);
            //
            if (!isset($gustoEmployeeStateTax['errors']) && $gustoEmployeeStateTax) {
                foreach ($gustoEmployeeStateTax as $stateTax) {
                    // set update array
                    $updArray = [];
                    $updArray['state'] = $stateTax['state'];
                    $updArray['state_json'] = json_encode($stateTax);
                    //
                    if (!$this->db->where(['employee_sid' => $employee['employee_sid'], 'state' => $stateTax['state']])->count_all_results('payroll_employee_state_tax')) {
                        //
                        $updArray['employee_sid'] = $employee['employee_sid'];
                        $updArray['company_sid'] = $employee['company_sid'];
                        $updArray['created_at'] = $updArray['updated_at'] = getSystemDate();
                        //
                        $this->db->insert('payroll_employee_state_tax', $updArray);
                    } else {
                        //
                        $updArray['updated_at'] = getSystemDate();
                        //
                        $this->db
                            ->where(['employee_sid' => $employee['employee_sid'], 'state' => $stateTax['state']])
                            ->update('payroll_employee_state_tax', $updArray);
                    }
                }
            }

            // get employee payment method
            $gustoEmployeePaymentMethod = getEmployeePaymentMethodFromGusto($employee['payroll_employee_uuid'], $companyDetails);

            if (!isset($gustoEmployeePaymentMethod['errors']) && $gustoEmployeePaymentMethod) {
                // set update array
                $updArray = [];
                $updArray['payment_method'] = $gustoEmployeePaymentMethod['type'];
                $updArray['split_method'] = $gustoEmployeePaymentMethod['split_by'];
                $updArray['splits'] = json_encode($gustoEmployeePaymentMethod['splits']??[]);
                $updArray['version'] = $gustoEmployeePaymentMethod['version'];
                //
                if (!$this->db->where(['employee_sid' => $employee['employee_sid']])->count_all_results('payroll_employee_payment_method')) {
                    //
                    $updArray['employee_sid'] = $employee['employee_sid'];
                    $updArray['company_sid'] = $employee['company_sid'];
                    $updArray['created_at'] = $updArray['updated_at'] = getSystemDate();
                    //
                    $this->db->insert('payroll_employee_payment_method', $updArray);
                } else {
                    //
                    $updArray['updated_at'] = getSystemDate();
                    //
                    $this->db
                        ->where(['employee_sid' => $employee['employee_sid']])
                        ->update('payroll_employee_payment_method', $updArray);
                }
            }

            $gustoEmployeeBankAccounts = getEmployeeBankAccountsFromGusto($employee['payroll_employee_uuid'], $companyDetails);
            if (!isset($gustoEmployeeBankAccounts['errors']) && $gustoEmployeeBankAccounts) {
                foreach($gustoEmployeeBankAccounts as $account) {
                    // set update array
                    $updArray = [];
                    $updArray['account_type'] = $account['account_type'];
                    $updArray['name'] = $account['name'];
                    $updArray['routing_number'] = $account['routing_number'];
                    $updArray['account_number'] = $account['hidden_account_number'];
                    //
                    if (!$this->db->where(['employee_sid' => $employee['employee_sid']])->count_all_results('payroll_employee_bank_accounts')) {
                        //
                        $updArray['payroll_bank_uuid'] = $employee['uuid'];
                        $updArray['employee_sid'] = $employee['employee_sid'];
                        $updArray['created_at'] = $updArray['updated_at'] = getSystemDate();
                        //
                        $this->db->insert('payroll_employee_bank_accounts', $updArray);
                    } else {
                        //
                        $updArray['updated_at'] = getSystemDate();
                        //
                        $this->db
                        ->where(['employee_sid' => $employee['employee_sid']])
                        ->update('payroll_employee_bank_accounts', $updArray);
                    }
                }
            }
        }
        //
        return true;
    }
}
