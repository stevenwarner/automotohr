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
                            'ssn' => $admin['ssn'],
                            'first_name' => $admin['first_name'],
                            'middle_initial' => $admin['middle_initial'],
                            'last_name' => $admin['last_name'],
                            'email_address' => $admin['email'],
                            'title' => $admin['title'],
                            'phone' => $admin['phone'],
                            'birthday' => $admin['birthday'],
                            'street_1' => $admin['street_1'],
                            'street_2' => $admin['street_2'],
                            'city' => $admin['city'],
                            'state' => $admin['state'],
                            'zip' => $admin['zip'],
                            'gusto_uuid' => $admin['uuid'],
                            'created_at' => getSystemDate(),
                            'updated_at' => getSystemDate(),
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
