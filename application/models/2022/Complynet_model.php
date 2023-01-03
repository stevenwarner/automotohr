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

    /**
     * Get integrated company details
     *
     * @param int $companyId
     * @return array
     */
    public function getIntegratedCompany(
        int $companyId
    )
    {
        return
        $this->db
        ->select([
            'company_name',
            'complynet_company_sid',
            'complynet_company_name',
            'complynet_location_sid',
            'complynet_location_name',
            'status',
            'created_at'
        ])
        ->where([
            'company_sid' => $companyId
        ])
        ->get('complynet_companies')
        ->row_array();
    }
    
    /**
     * Get company departments
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyDepartments(
        int $companyId
    )
    {
        //
        $complynetLinkedDepartments = $this->getComplyNetLinkedDepartments(
            $companyId
        );
        $this->db
        ->select([
            'sid',
            'name'
        ])
        ->where([
            'company_sid' => $companyId,
            'is_deleted' => 0
        ]);
        //
        if ($complynetLinkedDepartments) {
            $this->db->where_not_in('sid', $complynetLinkedDepartments);
        }
        return $this->db
        ->get('departments_management')
        ->result_array();
    }

    /**
     * Check and insert department
     *
     * @param array $ins
     */
    public function checkAndInsertDepartment($ins)
    {
        //
        $record =
        $this->db
        ->select('sid')
        ->where([
            'company_sid' => $ins['company_sid'],
            'lower(name)' => strtolower($ins['name'])
        ])
        ->get('departments_management')
        ->row_array();
        //
        if (!$record) {
            //
            $this->db->insert(
                'departments_management',
                $ins
            );
            //
            return $this->db->insert_id();
        }
        //
        return $record['sid'];
    }

    /**
     * Check and insert department
     *
     * @param array $ins
     */
    public function checkAndInsertDepartmentLink($ins)
    {
        //
        $record =
        $this->db
        ->select('sid')
        ->where([
            'department_sid' => $ins['department_sid'],
            'complynet_department_sid' => $ins['complynet_department_sid']
        ])
        ->get('complynet_departments')
        ->row_array();
        //
        if (!$record) {
            //
            $this->db->insert(
                'complynet_departments',
                $ins
            );
            //
            return $this->db->insert_id();
        }
        //
        return $record['sid'];
    }

    /**
     * Check and insert department
     *
     * @param int $companyId
     * @return array
     */
    public function getComplyNetLinkedDepartments(
        int $companyId
    )
    {
        //
        $records =
        $this->db
        ->select('department_sid')
        ->where([
            'company_sid' => $companyId
        ])
        ->get('complynet_departments')
        ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        return array_column($records, 'department_sid');
    }

    /**
     * Get company job roles
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyJobRoles(
        int $companyId
    )
    {
        //
        $records =
        $this->db
        ->select('distinct(job_title)')
        ->where([
            'parent_sid' => $companyId
        ])
        ->group_start()
        ->where('job_title != ', null)
        ->where('job_title != ', '')
        ->group_end()
        ->get('users')
        ->result_array();
        //
        return $records;
    }
}