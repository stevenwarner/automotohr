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
     * Get gusto company details for gusto
     *
     * @param int   $companyId
     * @param array $extra Optional
     * @param bool  $include Optional
     * @return array
     */
    protected function getGustoLinkedCompanyDetails(
        int $companyId,
        array $extra = [],
        bool $include = true
    ): array {
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

    /**
     * loads the Gusto library
     *
     * @param int $companyId
     */
    protected function initialize(int $companyId)
    {
        // load library
        $this
            ->load
            ->library(
                "Lb_gusto",
                ["companyId" => $companyId],
                "lb_gusto"
            );
    }
}
