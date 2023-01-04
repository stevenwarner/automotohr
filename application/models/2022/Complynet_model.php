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
    ) {
        //
        return $this->db
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
            ->where('expires >= ', date('Y-m-d', strtotime('now')) . ' 23:59:59')
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
    ) {
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
    ) {
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
    ) {
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
    ) {
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

    /**
     * Get company employees
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyEmployees(
        int $companyId
    ) {
        //
        $records =
            $this->db
            ->select('sid, email, first_name, last_name, PhoneNumber, job_title')
            ->where([
                'parent_sid' => $companyId,
                'email != ' => ''
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

    /**
     * Get employee department
     *
     * @param int $employeeId
     * @return string
     */
    public function getEmployeeDepartmentId(
        int $employeeId
    ) {
        //
        $record =
            $this->db
            ->select('departments_management.sid')
            ->where([
                'departments_management.is_deleted' => 0,
                'departments_employee_2_team.employee_sid' => $employeeId
            ])
            ->from('departments_employee_2_team')
            ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid')
            ->get()
            ->row_array();
        //
        if ($record) {
            return $this->getComplyNetLinkedDepartmentById($record['sid']);
        }
        //
        return 0;
    }

    /**
     * Get comply department Id
     *
     * @param int $departmentId
     * @return string
     */
    public function getComplyNetLinkedDepartmentById(
        int $departmentId
    ) {
        //
        $record =
            $this->db
            ->select('complynet_department_sid')
            ->where([
                'department_sid' => $departmentId
            ])
            ->get('complynet_departments')
            ->row_array();
        //
        if ($record) {
            return $record['complynet_department_sid'];
        }
        //
        return 0;
    }

    /**
     * Check and set the job role id
     *
     * @param string $departmentId
     * @param string $jobTitle
     * @return string
     */
    public function getAndSetJobRoleId(
        string $departmentId,
        string $jobTitle
    ) {
        //
        $record = $this->db
            ->select('complynet_job_role_sid')
            ->where([
                'complynet_department_sid' => $departmentId,
                'job_title' => $jobTitle
            ])
            ->get('complynet_jobRole')
            ->row_array();
        //
        if ($record) {
            return $record['complynet_job_role_sid'];
        }
        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        //
        $complyJobTitles = $this->clib->getJobRolesByDepartmentId(
            $departmentId
        );
        //
        $slug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($jobTitle)));
        //
        $complyRoleId = 0;
        //
        foreach ($complyJobTitles as $title) {
            //
            $slugComply = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($title['Name'])));
            //
            if ($slug == $slugComply) {
                $ins = [];
                $ins['complynet_department_sid'] = $departmentId;
                $ins['complynet_job_role_sid'] = $title['Id'];
                $ins['complynet_job_role_name'] = $title['Name'];
                $ins['job_title'] = $jobTitle;
                $ins['status'] = 1;
                $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                //
                $this->db->insert('complynet_jobRole', $ins);
                //
                $complyRoleId = $title['Id'];
                //
                break;
            }
        }
        //
        if ($complyRoleId === 0) {
            // Let's add the job role
            $complyRoleId = $this->clib->addJobRole([
                'ParentId' => $departmentId,
                'Name' => $jobTitle
            ]);
            //
            $ins = [];
            $ins['complynet_department_sid'] = $departmentId;
            $ins['complynet_job_role_sid'] = $complyRoleId;
            $ins['complynet_job_role_name'] = $jobTitle;
            $ins['job_title'] = $jobTitle;
            $ins['status'] = 1;
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->db->insert('complynet_jobRole', $ins);
        }
        //
        return $complyRoleId;
    }

    public function isEmployeeAdded(
        string $email,
        int $companyId
    ) {
        //
        return $this->db
            ->where([
                'company_sid' => $companyId,
                'email' => $email
            ])
            ->count_all_results('complynet_employees');
    }


    public function getTableData($tableName, $where)
    {
        return
            $this->db
            ->where($where)
            ->order_by('sid', 'desc')
            ->get($tableName)
            ->result_array();
    }

    public function syncDepartments(
        int $companyId
    ) {
        $company = $this->getIntegratedCompany(
            $companyId
        );
        //        //
        $complyCompanyId = $company['complynet_company_sid'];
        $complyLocationId = $company['complynet_location_sid'];
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        // Get company departments
        $departments = $this->getCompanyDepartments(
            $companyId
        );
        //
        // Convert department to index
        $departmentObj = [];
        //
        if (!empty($departments)) {
            //
            foreach ($departments as $department) {
                //
                $slug = preg_replace('/[^a-zA-Z]/', '', strtolower($department['name']));
                //
                $departmentObj[$slug] = $department;
            }
        }

        // Get all departments from ComplyNet
        $complyDepartments = $this->clib->getComplyNetDepartments(
            $complyLocationId
        );
        // Convert department to index
        $complyDepartmentObj = [];
        //
        if (!empty($complyDepartments)) {
            //
            foreach ($complyDepartments as $department) {
                //
                $slug = preg_replace('/[^a-zA-Z]/', '', strtolower($department['Name']));
                //
                $complyDepartmentObj[$slug] = $department;
            }
        }

        // Lets hook and push departments to ComplyNet
        if (!empty($departmentObj)) {
            foreach ($departmentObj as $index => $value) {
                //
                if (isset($complyDepartmentObj[$index])) {
                    // the department is on complynet
                    // let's connect in our system
                    $ins = [];
                    $ins['company_sid'] = $companyId;
                    $ins['department_sid'] = $value['sid'];
                    $ins['complynet_department_sid'] = $complyDepartmentObj[$index]['Id'];
                    $ins['complynet_department_name'] = $complyDepartmentObj[$index]['Name'];
                    $ins['department_name'] = $value['name'];
                    $ins['status'] = 1;
                    $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                    //
                    $this->checkAndInsertDepartmentLink($ins);
                } else {
                    // create the department on ComplyNet
                    // then connect it in our system
                    $response = $this->clib->addDepartmentToComplyNet([
                        'ParentId' => $complyLocationId,
                        'Name' => $value['name']
                    ]);
                    //
                    if ($response && !empty($response['Id'])) {
                        // Let connect
                        $ins = [];
                        $ins['company_sid'] = $companyId;
                        $ins['department_sid'] = $value['sid'];
                        $ins['complynet_department_sid'] = $response['Id'];
                        $ins['complynet_department_name'] = $response['Name'];
                        $ins['department_name'] = $value['name'];
                        $ins['status'] = 1;
                        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                        //
                        $this->checkAndInsertDepartmentLink($ins);
                    }
                }
            }
        }

        // Lets hook departments
        if (!empty($complyDepartmentObj)) {
            //
            foreach ($complyDepartmentObj as $index => $value) {
                //
                // create the department on system
                // then connect it to ComplyNet
                $ins = [];
                $ins['company_sid'] = $companyId;
                $ins['name'] = $value['Name'];
                $ins['status'] = 1;
                $ins['created_by_sid'] = 0;
                $ins['created_date'] = getSystemDate();
                //
                $departmentId = $this
                    ->checkAndInsertDepartment($ins);
                // Let connect
                $ins = [];
                $ins['company_sid'] = $companyId;
                $ins['department_sid'] = $departmentId;
                $ins['complynet_department_sid'] = $value['Id'];
                $ins['complynet_department_name'] = $value['Name'];
                $ins['department_name'] = $value['Name'];
                $ins['status'] = 1;
                $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                //
                $this->checkAndInsertDepartmentLink($ins);
            }
        }
    }
}
