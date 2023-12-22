<?php defined('BASEPATH') || exit('No direct script access allowed');

class Payroll_base_model extends CI_Model
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
     * Get gusto company details for gusto
     *
     * @param int   $companyId
     * @param array $extra Optional
     * @param bool  $include Optional
     * @return array
     */
    public function getCompanyDetailsForGusto(int $companyId, array $extra = [], bool $include = true): array
    {
        //
        $columns = $include ? array_merge([
            'gusto_uuid',
            'refresh_token',
            'access_token'
        ], $extra) : $extra;
        //
        return $this->db
            ->select($columns)
            ->where('company_sid', $companyId)
            ->get('gusto_companies')
            ->row_array();
    }

    /**
     * Get gusto employees details for gusto
     *
     * @param int   $employeeId
     * @param array $extra Optional
     * @param bool  $include Optional
     * @return array
     */
    public function getEmployeeDetailsForGusto(int $employeeId, array $extra = [], bool $include = true): array
    {
        //
        $columns = $include ? array_merge([
            'gusto_uuid',
            'gusto_version',
        ], $extra) : $extra;
        //
        return $this->db
            ->select($columns)
            ->where('employee_sid', $employeeId)
            ->get('gusto_companies_employees')
            ->row_array();
    }
}
