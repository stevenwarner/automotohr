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
            ->select('access_token')
            ->where('expires >= ', date('Y-m-d', strtotime('now')) . ' 23:59:59')
            ->get('complynet_access_token')
            ->row_array();
        //
        return $record['access_token'] ?? '';
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

    //
    public function getOffComplyNetEmployees($employeesArray, $companySid)
    {
        $this->db->select('
            sid,
            first_name,
            last_name,
            email,
            job_title,
            access_level,
            access_level_plus,
            pay_plan_flag
        ')
            ->where('parent_sid', $companySid)
            ->order_by('first_name', 'ASC');
        //
        if (!empty($employeesArray)) {
            $this->db->where_not_in('sid', $employeesArray);
        }
        //
        $employees = $this->db->get('users')->result_array();
        //
        if ($employees) {
            foreach ($employees as $index => $employee) {
                //
                $employees[$index]['department_sid'] = $this->getEmployeeDepartmentId($employee['sid']);
            }
        }
        //
        return $employees;
    }

    /**
     * Get company employee
     *
     * @param int $companyId
     * @param int $employeeId
     * @return array
     */
    public function getCompanyEmployee(
        int $companyId,
        int $employeeId
    ) {
        //
        $record =
            $this->db
            ->select('sid, email, first_name, last_name, PhoneNumber, job_title')
            ->where([
                'parent_sid' => $companyId,
                'sid' => $employeeId
            ])
            ->get('users')
            ->row_array();
        //
        return $record;
    }


    /**
     * Get company department
     *
     * @param int    $companyId
     * @param string $type
     * @return int | array
     */
    public function getCompanyAllDepartments(
        int $companyId,
        string $type
    ) {
        //
        $this->db
            ->where([
                'departments_management.is_deleted' => 0,
                'departments_management.company_sid' => $companyId
            ])
            ->from('departments_management');

        //
        if ($type == 'count') {
            return $this->db->count_all_results();
        }
        return $this->db->result_array();
    }

    /**
     * Sync single employee with complynet
     *
     * @param int $companyId
     * @param int $employeeId
     * @return json
     */
    public function syncSingleEmployee(int $companyId, int $employeeId)
    {
        // Get company job roles
        $employee = $this->getCompanyEmployee(
            $companyId,
            $employeeId
        );
        //
        if (empty($employee)) {
            return sendResponse(
                200,
                [
                    'errors' => 'Employee not found.'
                ]
            );
        }
        //
        $errorArray = [];
        //
        $email = strtolower($employee['email']);
        //
        $company = $this->getIntegratedCompany(
            $companyId
        );
        //
        $complyCompanyId = $company['complynet_company_sid'];
        $complyLocationId = $company['complynet_location_sid'];
        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        //
        if ($this->isEmployeeAdded($email, $companyId)) {
            $errorArray[] = 'Employee already synced with ComplyNet.';
            return SendResponse(200, ['errors' => $errorArray]);
        }
        //
        $complyDepartmentId = $this->getEmployeeDepartmentId(
            $employee['sid']
        );
        //
        if ($complyDepartmentId === 0) {
            $errorArray[] = 'Department not found.';
            return SendResponse(200, ['errors' => $errorArray]);
        }
        //
        $complyJobRoleId = $this->getAndSetJobRoleId(
            $complyDepartmentId,
            $employee['job_title']
        );
        //
        if ($complyJobRoleId === 0) {
            $errorArray[] = 'Job role not found.';
            return SendResponse(200, ['errors' => $errorArray]);
        }
        //
        if (empty($complyJobRoleId)) {
            $errorArray[] = 'Job role not found.';
            return SendResponse(200, ['errors' => $errorArray]);
        }

        // Check if exists in ComplyNet
        $employeeObj = $this->clib->getEmployeeByEmail($email);
        //
        if (isset($employeeObj[0]['Id'])) {
            // Just link it
            $ins = [];
            $ins['company_sid'] = $companyId;
            $ins['complynet_employee_sid'] = $employeeObj[0]['Id'];
            $ins['complynet_company_sid'] = $complyCompanyId;
            $ins['complynet_location_sid'] = $complyLocationId;
            $ins['complynet_department_sid'] = $complyDepartmentId;
            $ins['complynet_job_role_sid'] = $complyJobRoleId;
            $ins['employee_sid'] = $employee['sid'];
            $ins['email'] = $email;
            $ins['complynet_json'] = json_encode($employeeObj);
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->db->insert(
                'complynet_employees',
                $ins
            );

            //
            return SendResponse(200, ['success' => true]);
        } else {
            $ins = [];
            $ins['firstName'] = $employee['first_name'];
            $ins['lastName'] = $employee['last_name'];
            $ins['userName'] = $email;
            $ins['email'] = $email;
            $ins['password'] = 'password';
            $ins['companyId'] = $complyCompanyId;
            $ins['locationId'] = $complyLocationId;
            $ins['departmentId'] = $complyDepartmentId;
            $ins['jobRoleId'] = $complyJobRoleId;
            $ins['PhoneNumber'] = $employee['PhoneNumber'];
            $ins['TwoFactor'] = false;
            //
            $response = $this->clib->addEmployee($ins);
            //
            if (preg_match('/created user/i', $response)) {
                //
                $employeeObj = $this->clib->getEmployeeByEmail($email);
                if (isset($employeeObj[0]['Id'])) {
                    //
                    $ins = [];
                    $ins['company_sid'] = $companyId;
                    $ins['complynet_employee_sid'] = $employeeObj[0]['Id'];
                    $ins['complynet_company_sid'] = $complyCompanyId;
                    $ins['complynet_location_sid'] = $complyLocationId;
                    $ins['complynet_department_sid'] = $complyDepartmentId;
                    $ins['complynet_job_role_sid'] = $complyJobRoleId;
                    $ins['employee_sid'] = $employee['sid'];
                    $ins['email'] = $email;
                    $ins['complynet_json'] = json_encode($employeeObj);
                    $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                    //
                    $this->db->insert(
                        'complynet_employees',
                        $ins
                    );
                    //
                    return SendResponse(200, ['success' => true]);
                }
            }
        }

        //
        return SendResponse(200, ['errors' => [
            'System failed to link employee with ComplyNet.'
        ]]);
    }
}
