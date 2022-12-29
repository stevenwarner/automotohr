<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * ComplyNet
 *
 * @version 1.0
 */
class Complynet_model extends CI_Model
{
    /**
     * Constructor
     */
    public function __construct()
    {
        //
        parent::__construct();
    }


    /**
     * Get companies
     * @param string $status
     * @return array
     */
    public function getCompanies(string $status = 'all')
    {
        $this->db
        ->select('CompanyName, sid')
        ->where([
            'parent_sid' => 0
        ])
        ->order_by('CompanyName', 'ASC');
        //
        if ($status != 'all') {
            $this->db->where('active', $status == 'active' ? 1 : 0);
        }
        //
        return
        $this->db->get('users')
        ->result_array();
    }

    /**
     * Company integration details
     * @param int $companyId
     * @return array
     */
    public function checkAndGetCompanyIntegrationDetails(
        int $companyId
    )
    {
        //
        $this->db
        ->select('
            complynet_company_sid,
            complynet_location_sid,
            complynet_company_name,
            company_name
        ')
        ->where('company_sid', $companyId)
        ->get('complynet_companies')
        ->row_array();
    }

    /**
     * Check and get token
     */
    public function checkAndGetToken()
    {
        $record =
        $this->db
        ->select('token')
        ->where('expires >= ', date('Y-m-d', strtotime('now')).' 23:59:59')
        ->get('complynet_access_token')
        ->row_array();
        //
        return $record['token'] ?? '';
    }

    /**
     * Get the column
     * 
     * @param int $id
     * @param string $column
     * @return string
     */
    public function getUserColumn($id, $column)
    {
        $record =
        $this->db
        ->select($column)
        ->where('sid', $id)
        ->get('users')
        ->row_array();
        //
        return $record ? $record[$column] : '';
    }
}