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
}
