<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Base payroll model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Base_payroll_model extends CI_Model
{
    /**
     * holds the gusto company
     * @var array
     */
    protected $gustoCompany;

    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
    }

    /**
     * load the company helper
     *
     * @param int $companyId
     * @return void
     */
    protected function loadPayrollHelper(int $companyId)
    {
        // load the payroll helper
        $this->load->helper('v1/payroll' . ($this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
    }

    /**
     * Get gusto company details for gusto
     *
     * @param int   $companyId
     * @param array $extra Optional
     * @param bool  $include Optional
     * @return array
     */
    protected function getGustoLinkedCompanyDetails(int $companyId, array $extra = [], bool $include = true): array
    {
        //
        $columns = $include ? array_merge([
            'gusto_uuid',
            'refresh_token',
            'access_token'
        ], $extra) : $extra;
        //
        return $this->gustoCompany =
            $this->db
            ->select($columns)
            ->where('company_sid', $companyId)
            ->get('gusto_companies')
            ->row_array();
    }
}
