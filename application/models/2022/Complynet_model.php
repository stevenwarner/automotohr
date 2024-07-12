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
     * Check and insert department
     *
     * @param int $companyId
     * @return array
     */
    public function getComplyNetLinkedDepartmentsSid(
        int $companyId
    ) {
        //
        $records =
            $this->db
            ->select('complynet_department_sid')
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
        return array_column($records, 'complynet_department_sid');
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
            ->select('sid, email, first_name, last_name, PhoneNumber, job_title, username ,complynet_job_title')
            ->where([
                'parent_sid' => $companyId,
                'email != ' => ''
            ])
            ->group_start()
            ->where('complynet_job_title != ', null)
            ->where('complynet_job_title != ', '')
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
     * @param bool $returnComplyId Optional
     * @return string
     */
    public function getEmployeeDepartmentId(
        int $employeeId,
        bool $returnComplyId = true
    ) {
        //
        $record =
            $this->db
            ->select('departments_management.sid')
            ->where([
                'departments_management.is_deleted' => 0,
                'departments_employee_2_team.employee_sid' => $employeeId
            ])
            ->order_by('id', 'desc')
            ->from('departments_employee_2_team')
            ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid')
            ->get()
            ->row_array();
        //
        if ($record) {
            if ($returnComplyId) {
                return $this->getComplyNetLinkedDepartmentById($record['sid'], $employeeId);
            } else {
                return $record['sid'];
            }
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
        int $departmentId,
        int $employeeId = 0
    ) {
        //
        $record =
            $this->db
            ->select('complynet_department_sid, department_name, complynet_department_sid')
            ->where([
                'department_sid' => $departmentId,
                'complynet_department_sid !=  ' => 'A'
            ])
            ->get('complynet_departments')
            ->row_array();
        //
        if ($record) {
            // lets double check it
            $response = $this->getComplyNetDepartmentByAhrId(
                $departmentId,
                $record['department_name'],
                $record['complynet_department_sid'],
                $employeeId
            );
            //
            if ($response != '0') {
                return $response;
            }
            return 0;
        }
        //
        return 0;
    }

    private function getComplyNetDepartmentByAhrId(
        int $departmentId,
        string $departmentName,
        string $departmentUUID,
        int $employeeId
    ) {
        // get the company location id
        $result = $this->db
            ->select('parent_sid')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        //
        if (!$result) {
            return 0;
        }
        //
        $result = $this->db
            ->select('complynet_location_sid')
            ->where('company_sid', $result['parent_sid'])
            ->get('complynet_companies')
            ->row_array();
        //
        if (!$result) {
            return 0;
        }
        //
        $locations = $this->clib->getComplyNetDepartments(
            $result['complynet_location_sid']
        );
        //
        if (!$locations) {
            return 0;
        }
        //
        $locationId = 0;
        //
        foreach ($locations as $department) {
            //
            if ($department['Id'] == $departmentUUID) {
                $locationId = $department['Id'];
            }
        }
        //
        return $locationId;
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
        if ($record && $record['complynet_job_role_sid'] == 0) {
            return $this->syncJobRoles($departmentId, $jobTitle);
        }
        //
        if ($record && $record['complynet_job_role_sid'] != 0) {
            return $record['complynet_job_role_sid'];
        }
        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        //
        $complyJobTitles = $this->clib->getJobRolesByDepartmentId(
            $departmentId
        );
        //
        $complyRoleId = 0;
        //
        if (isset($complyJobTitles['error'])) {
            $reportError = array();
            $reportError['department_sid'] = $departmentId;
            $reportError['response'] = $complyJobTitles;
            //
            $message = 'JobRole not found on ComplyNet against department ID <strong>' . $departmentId . '</strong>';
            //
            $this->sendEmailToDeveloper($message, $reportError);
            //
            return $complyRoleId;
        }
        //
        $slug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($jobTitle))); //
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
            if (empty($complyRoleId) || isset($complyRoleId['error'])) {
                //
                $reportError = array();
                $reportError['response'] = $complyRoleId;
                $reportError['complynetObject'] = json_encode([
                    'ParentId' => $departmentId,
                    'Name' => $jobTitle
                ]);
                //
                $message = 'JobRole not created on ComplyNet.';
                //
                $this->sendEmailToDeveloper($message, $reportError);
                //
                return $complyRoleId;
            }
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

    public function findEmployeeBySid(
        int $employeeId,
        int $companyId
    ) {
        //
        return $this->db
            ->where([
                'company_sid' => $companyId,
                'employee_sid' => $employeeId
            ])
            ->count_all_results('complynet_employees');
    }


    public function getTableData($tableName, $where)
    {
        if ($tableName == 'complynet_employees') {
            $records_arr = $this->db
                ->select('complynet_employees.*,users.active')
                ->where($where)
                ->join('users', 'users.sid = complynet_employees.employee_sid', 'left')
                ->order_by('sid', 'desc')
                ->get($tableName)
                ->result_array();
            $this->GetEmployeeStatus($records_arr);
            return $records_arr;
        } else {
            return
                $this->db
                ->where($where)
                ->order_by('sid', 'desc')
                ->get($tableName)
                ->result_array();
        }
    }

    public function syncDepartments(
        int $companyId
    ) {
        //
        $this->deleteAllPreviousDepartment(
            $companyId
        );
        //
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
        //
        if (isset($complyDepartments['error'])) {
            //
            $reportError = array();
            $reportError['location_ID'] = $complyLocationId;
            $reportError['response'] = $complyDepartments;
            //
            $message = 'Department not found on ComplayNet against location ID <strong>' . $complyLocationId . '</strong>';
            //
            $this->sendEmailToDeveloper($message, $reportError);
            //
            return "System failed to link department with ComplyNet";
        }
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
                    //
                    if ($complyDepartmentObj[$index]['Id'] == 'A') {
                        continue;
                    }
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
                    if (isset($response['error'])) {
                        $reportError = array();
                        $reportError['location_ID'] = $complyLocationId;
                        $reportError['response'] = $response;
                        $reportError['complynetObject'] = json_encode([
                            'ParentId' => $complyLocationId,
                            'Name' => $value['name']
                        ]);
                        //
                        $message = 'Department not created on ComplyNet against location ID <strong>' . $complyLocationId . '</strong>';
                        //
                        $this->sendEmailToDeveloper($message, $reportError);
                        //
                        continue;
                    }
                    //
                    if ($response && !empty($response['Id'])) {
                        //
                        if ($response['Id'] == 'A') {
                            continue;
                        }
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
                if ($value['Id'] == 'A') {
                    continue;
                }
                //
                $this->checkAndInsertDepartmentLink($ins);
            }
        }

        //
        // Get all departments from ComplyNet after sync
        // $complySyncDepartments = $this->clib->getComplyNetDepartments(
        //     $complyLocationId
        // );
        // //
        // $ahrDepartments = $this->complynet_model->getComplyNetLinkedDepartmentsSid($companyId);
        // $complynetDepartmentsIds = array_column($complySyncDepartments, 'Id');
        // //
        // foreach ($ahrDepartments as $departmentId) {
        //     if (!in_array($departmentId, $complynetDepartmentsIds)) {
        //         $this->complynet_model->deleteDepartmentLinkAndJobRoll($departmentId);
        //     }
        // }
    }

    //
    public function getOffComplyNetEmployees($employeesArray, $companySid)
    {
        $this->db->select('
            sid,
            first_name,
            last_name,
            email,
            username,
            PhoneNumber,
            department_sid,
            team_sid,
            job_title,
            complynet_job_title,
            access_level,
            access_level_plus,
            pay_plan_flag,
            active
        ')
            ->where('parent_sid', $companySid)
            ->where('is_executive_admin', 0)
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
                $employees[$index]['department_sid'] = $this->getEmployeeDepartmentId($employee['sid'], false);
                $employees[$index]['employee_sid'] = $employee['sid'];
            }
        }
        //
        $this->GetEmployeeStatus($employees);

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
            ->select('sid, email, first_name, last_name, PhoneNumber, job_title, username, department_sid, team_sid , complynet_job_title')
            ->where([
                'parent_sid' => $companyId,
                'sid' => $employeeId
            ])
            ->get('users')
            ->row_array();
        //
        if ($record) {
            $record['department_sid'] = $this->getEmployeeDepartmentId($record['sid'], false);
        }
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
     * @param bool $doReturn Optional
     * @return json
     */
    public function syncSingleEmployee(int $companyId, int $employeeId, bool $doReturn = false)
    {
        // Get company job roles
        $employee = $this->getCompanyEmployee(
            $companyId,
            $employeeId
        );
        //
        if (empty($employee)) {
            if ($doReturn) {
                return [
                    'errors' => 'Employee not found.'
                ];
            }
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
        if ($this->findEmployeeBySid($employeeId, $companyId)) {
            $errorArray[] = 'Employee already synced with ComplyNet.';
            if ($doReturn) {
                return $errorArray;
            }
            return SendResponse(200, ['errors' => $errorArray]);
        }
        $employee['complynet_job_title'] = $this->checkJobRoleForComplyNet($employee['job_title'], $employee['complynet_job_title']);
        //
        if (checkEmployeeMissingData($employee)) {
            if ($doReturn) {
                return checkEmployeeMissingData($employee);
            }
            return SendResponse(200, ['errors' => checkEmployeeMissingData($employee)]);
        }
        //
        $complyDepartmentId = $this->getEmployeeDepartmentId(
            $employee['sid']
        );
        //
        if ($complyDepartmentId === 0) {
            $errorArray[] = 'Department not found.';
            if ($doReturn) {
                return $errorArray;
            }
            return SendResponse(200, ['errors' => $errorArray]);
        }
        //
        $complyJobRoleId = $this->getAndSetJobRoleId(
            $complyDepartmentId,
            $employee['complynet_job_title']
        );
        //
        if ($complyJobRoleId === 0) {
            $errorArray[] = 'Job role not found.';
            if ($doReturn) {
                return $errorArray;
            }
            return SendResponse(200, ['errors' => $errorArray]);
        }
        //
        if (empty($complyJobRoleId)) {
            $errorArray[] = 'Job role not found.';
            if ($doReturn) {
                return $errorArray;
            }
            return SendResponse(200, ['errors' => $errorArray]);
        }
        //
        $isTransferred = $this->checkIsEmployeeTransferred($employeeId);
        //
        if ($isTransferred) {
            //
            if ($doReturn) {
                return ['System failed to link employee with ComplyNet.'];
            }
            return SendResponse(200, ['success' => true]);
        }

        // Check employee by email
        $employeeObj = $this->clib->getEmployeeByEmail($email);
        //
        if (isset($employeeObj['error'])) {
            //
            $reportError = array();
            $reportError['email'] = $email;
            $reportError['response'] = $employeeObj;
            //
            $message = 'Employee not found on ComplyNet against email <strong>' . $email . '</strong>';
            //
            $this->sendEmailToDeveloper($message, $reportError);
            //
            return 'System failed to link employee with ComplyNet.';
        }
        // found
        if (isset($employeeObj[0]['Id']) && findTheRightEmployee($employeeObj, $complyCompanyId, $complyLocationId)) {
            $employeeObj[0] = findTheRightEmployee($employeeObj, $complyCompanyId, $complyLocationId);
            //
            if (!$employeeObj[0]) {
                if ($doReturn) {
                    return ['failed to find employee'];
                }
                return SendResponse(200, ['errors' => ['failed to find employee']]);
            }
            // Just link it
            $ins = [];
            $ins['company_sid'] = $companyId;
            $ins['complynet_employee_sid'] = $employeeObj[0]['Id'];
            $ins['complynet_company_sid'] = $complyCompanyId;
            $ins['complynet_location_sid'] = $complyLocationId;
            $ins['complynet_department_sid'] = $complyDepartmentId;
            $ins['complynet_job_role_sid'] = $complyJobRoleId;
            $ins['employee_sid'] = $employeeId;
            $ins['email'] = $email;
            $ins['alt_id'] = 'AHR' . $employeeId;
            $ins['complynet_json'] = json_encode($employeeObj);
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->db->insert(
                'complynet_employees',
                $ins
            );
            //
            $this->db
                ->where('sid', $employeeId)
                ->update('users', [
                    'complynet_onboard' => 1
                ]);
            //
            if ($doReturn) {
                return [];
            }
            return SendResponse(200, ['success' => true]);
        }
        //
        // Try to save with email address
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
        $ins['AltId'] = 'AHR' . $employeeId;
        $ins['TwoFactor'] = false;
        //
        $response = $this->clib->addEmployee($ins);
        //
        if (isset($response['error'])) {
            $reportError = array();
            $reportError['employee'] = $employee;
            $reportError['response'] = $response;
            $reportError['complynetObject'] = json_encode($ins);
            //
            $message = 'Employee not created on ComplyNet for company <strong>' . getCompanyNameBySid($companyId) . '</strong>';
            //
            $this->sendEmailToDeveloper($message, $reportError);
            //
            return "System failed to link employee with ComplyNet";
        }
        // Lets save the user
        if (preg_match('/created user/i', $response)) {
            // fetch user
            $employeeObj = $this->clib->getEmployeeByEmail($email);
            //
            if (isset($employeeObj['error'])) {
                //
                $reportError = array();
                $reportError['email'] = $email;
                $reportError['response'] = $employeeObj;
                //
                $message = 'Employee not found on ComplyNet against email <strong>' . $email . '</strong>';
                //
                $this->sendEmailToDeveloper($message, $reportError);
                //
                if ($doReturn) {
                    return ['failed to find employee'];
                }
                return SendResponse(200, ['errors' => ['failed to find employee']]);
            }
            //
            $employeeObj[0] = findTheRightEmployee($employeeObj, $complyCompanyId, $complyLocationId);
            //
            if (!$employeeObj[0]) {
                if ($doReturn) {
                    return ['failed to find employee'];
                }
                return SendResponse(200, ['errors' => ['failed to find employee']]);
            }
            // Just link it
            $ins = [];
            $ins['company_sid'] = $companyId;
            $ins['complynet_employee_sid'] = $employeeObj[0]['Id'];
            $ins['complynet_company_sid'] = $complyCompanyId;
            $ins['complynet_location_sid'] = $complyLocationId;
            $ins['complynet_department_sid'] = $complyDepartmentId;
            $ins['complynet_job_role_sid'] = $complyJobRoleId;
            $ins['employee_sid'] = $employeeId;
            $ins['email'] = $email;
            $ins['alt_id'] = 'AHR' . $employeeId;
            $ins['complynet_json'] = json_encode($employeeObj);
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->db->insert(
                'complynet_employees',
                $ins
            );
            //
            $this->db
                ->where('sid', $employeeId)
                ->update('users', [
                    'complynet_onboard' => 1
                ]);
            //
            if ($doReturn) {
                return [];
            }
            return SendResponse(200, ['success' => true]);
        }
        //
        if (preg_match('/unavailable/i', $response)) {
            $reportError = array();
            $reportError['employee'] = $employee;
            $reportError['response'] = $response;
            $reportError['complynetObject'] = json_encode($ins);
            //
            $message = 'Employee not created on ComplyNet for company <strong>' . getCompanyNameBySid($companyId) . '</strong>';
            //
            $this->sendEmailToDeveloper($message, $reportError);
            //
            if ($doReturn) {
                return $response;
            }
            return SendResponse(200, ['errors' => [
                $response
            ]]);
        }

        //
        if ($doReturn) {
            return 'System failed to link employee with ComplyNet.';
        }
        return SendResponse(200, ['errors' => [
            'System failed to link employee with ComplyNet.'
        ]]);
    }

    public function checkIsEmployeeTransferred($employeeId)
    {
        $this->db->select('sid, previous_employee_sid, from_company_sid, new_employee_sid, to_company_sid');
        $this->db->where_in('new_employee_sid', $employeeId);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('employees_transfer_log');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            // set default record
            $result = [
                'previous_employee_sid' => $record_arr['previous_employee_sid'],
                'from_company_sid' => $record_arr['from_company_sid']
            ];
            // check for old data
            $result1 = $this->checkEmployeeOnComplynet($record_arr['previous_employee_sid'], $record_arr['from_company_sid']);
            // check if employee is not on ComplyNet
            if ($result1 == 0) {
                // get the ComplyNet employee
                $result = $this->isSecondaryEmployeeTransferred($record_arr['previous_employee_sid'], $record_arr['from_company_sid']);
            }
            //
            if (empty($result)) {
                return false;
            }
            //
            $passArray = [
                'newCompanyId' => $record_arr['to_company_sid'],
                'newEmployeeId' => $record_arr['new_employee_sid'],
                'oldEmployeeId' => $result['previous_employee_sid'],
                'oldCompanyId' => $result['from_company_sid']
            ];
            //
            $result = $this->manageEmployee($passArray);

            if (isset($result['success'])) {
                return true;
            }
            //
            return false;
        } else {
            return false;
        }
    }

    public function isSecondaryEmployeeTransferred($employeeId, $companyId)
    {
        $this->db->select('sid, previous_employee_sid, from_company_sid, new_employee_sid, to_company_sid');
        $this->db->where('new_employee_sid', $employeeId);
        $this->db->where('to_company_sid', $companyId);
        $record_obj = $this->db->get('employees_transfer_log');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $checkPrimary = $this->checkEmployeeOnComplynet($record_arr['new_employee_sid'], $record_arr['to_company_sid']);
            $checkSecondary = $this->checkEmployeeOnComplynet($record_arr['previous_employee_sid'], $record_arr['from_company_sid']);
            //
            if ($checkSecondary == 0 && $checkPrimary == 0) {
                return $this->isSecondaryEmployeeTransferred($record_arr['previous_employee_sid'], $record_arr['from_company_sid']);
            } else {
                return $record_arr;
            }
        } else {
            return array();
        }
    }

    public function checkEmployeeOnComplynet($employeeId, $companyId)
    {
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('company_sid', $companyId);
        $this->db->from('complynet_employees');
        return $this->db->count_all_results();
    }


    public function getEmployeeDetailById(
        int $id
    ) {
        //
        return $this->db
            ->where([
                'sid' => $id,
            ])
            ->get('complynet_employees')->row_array();
    }

    /**
     *  Sync the employee to ComplyNet
     *
     * @param int $companyId
     * @param int $employeeId
     * @param array $oldData
     * @return bool|string
     */
    public function updateEmployeeOnComplyNet(
        int $companyId,
        int $employeeId,
        array $oldData
    ) {
        // Check if user is on ComplyNet
        if (!$this->db->where('employee_sid', $employeeId)->count_all_results('complynet_employees')) {
            return false;
        }
        // Get the employee details
        $employeeDetails = $this->db
            ->select('
            users.first_name,
            users.last_name,
            users.email,
            users.username,
            users.job_title,
            users.complynet_job_title,
            users.PhoneNumber,
            users.department_sid,
            users.team_sid,
            complynet_employees.complynet_company_sid,
            complynet_employees.complynet_location_sid,
            complynet_employees.complynet_job_role_sid,
            complynet_employees.complynet_department_sid
        ')
            ->where([
                'users.parent_sid' => $companyId,
                'users.sid' => $employeeId
            ])
            ->join('complynet_employees', 'complynet_employees.employee_sid = users.sid', 'inner')
            ->get('users')
            ->row_array();
        //
        if (empty($employeeDetails)) {
            return false;
        }
        //
        $differenceArray = [];
        // check first name
        if ($employeeDetails['first_name'] != $oldData['first_name']) {
            $differenceArray['first_name'] = $employeeDetails['first_name'];
        }
        // check last name
        if ($employeeDetails['last_name'] != $oldData['last_name']) {
            $differenceArray['last_name'] = $employeeDetails['last_name'];
        }
        // check email
        if ($employeeDetails['email'] != $oldData['email']) {
            $differenceArray['email'] = $employeeDetails['email'];
        }
        // check phone number
        if ($employeeDetails['PhoneNumber'] != $oldData['PhoneNumber']) {
            $differenceArray['PhoneNumber'] = $employeeDetails['PhoneNumber'];
        }
        //
        if (!$differenceArray) {
            return false;
        }

        // fetch complynet object
        $complyEmployee = $this->db
            ->where('employee_sid', $employeeId)
            ->get('complynet_employees')->row_array();
        //
        if (!$complyEmployee['alt_id']) {
            return false;
        }
        // make update array
        $updateArray['firstName'] = $differenceArray['first_name'] ?? $employeeDetails['first_name'];
        $updateArray['lastName'] = $differenceArray['last_name'] ?? $employeeDetails['last_name'];
        $updateArray['userName'] = $complyEmployee['email'];
        $updateArray['email'] = $differenceArray['email'] ?? $employeeDetails['email'];
        $updateArray['password'] = 'password';
        $updateArray['companyId'] = $complyEmployee['complynet_company_sid'];
        $updateArray['locationId'] = $complyEmployee['complynet_location_sid'];
        $updateArray['departmentId'] = $complyEmployee['complynet_department_sid'];
        $updateArray['jobRoleId'] = $complyEmployee['complynet_job_role_sid'];
        $updateArray['PhoneNumber'] = $differenceArray['PhoneNumber'] ?? $employeeDetails['PhoneNumber'];
        $updateArray['TwoFactor'] = "FALSE";
        $updateArray['AltId'] = $complyEmployee['alt_id'];

        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        //
        $response = $this->clib->updateUser($updateArray);
        //
        if (preg_match('/(1)\s+Update/', $response)) {
            return true;
        }
        return false;
    }


    public function getComplyJobRole(
        int $id,
        string $column = '*'
    ) {
        return $this->db
            ->select($column)
            ->where('sid', $id)
            ->get('complynet_job_roles')
            ->row_array();
    }

    public function getSystemJobRoles()
    {
        return $this->db
            ->select('distinct(job_title) as job_title')
            ->where('job_title IS NOT NULL', null)
            ->where('job_title != ""', null)
            ->order_by('job_title', 'ASC')
            ->get('users')
            ->result_array();
    }

    public function getLinkedRoles()
    {
        return $this->db
            ->select('job_title')
            ->get('complynet_job_roles_jobs')
            ->result_array();
    }

    public function getLinkedJobRoles(
        int $id
    ) {
        return $this->db
            ->select('job_title, created_at, sid')
            ->where('complynet_job_tile_sid', $id)
            ->order_by('job_title', 'ASC')
            ->get('complynet_job_roles_jobs')
            ->result_array();
    }

    public function checkJobRoleForComplyNet(
        $jobTitle,
        $complyJobTitle
    ) {
        //
        if (!empty($complyJobTitle) && $complyJobTitle != null) {
            return $complyJobTitle;
        }
        //
        $record =
            $this->db
            ->select('complynet_job_roles.job_title')
            ->where('complynet_job_roles_jobs.job_title', preg_replace('/[^a-z\s]/i', '', trim($jobTitle)))
            ->join('complynet_job_roles', 'complynet_job_roles.sid = complynet_job_roles_jobs.complynet_job_tile_sid', 'inner')
            ->get('complynet_job_roles_jobs')
            ->row_array();
        //
        if (!$record) {
            return $complyJobTitle;
        }
        //
        return $record['job_title'];
    }

    //
    private function GetEmployeeStatus(&$employees, $status = 1)
    {
        //
        if (empty($employees)) {
            return false;
        }
        $transferRecords = $this->db
            ->select('new_employee_sid')
            ->get('employees_transfer_log')
            ->result_array();
        //
        $transferIds = array_column($transferRecords, 'new_employee_sid');
        //
        $employeeIds = array_column($employees, 'employee_sid');
        //
        $statuses = $this->db
            ->select('employee_sid, termination_date, status_change_date, details, do_not_hire')
            ->where_in('employee_sid', $employeeIds)
            ->where('employee_status', $status)
            ->get('terminated_employees')
            ->result_array();
        //
        $last_statuses = $this->db
            ->select('employee_sid, termination_date, status_change_date, details, do_not_hire, employee_status')
            ->where_in('employee_sid', $employeeIds)
            ->order_by('terminated_employees.sid', 'DESC')
            ->get('terminated_employees')
            ->result_array();
        //
        if (!empty($statuses)) {
            //
            $tmp = [];
            //
            foreach ($statuses as $stat) {
                //
                $tmp[$stat['employee_sid']] = $stat;
            }
            //
            $statuses = $tmp;
            //
            $tmp = [];
            //
            foreach ($last_statuses as $stat) {
                //
                if (!isset($tmp[$stat['employee_sid']])) {
                    $tmp[$stat['employee_sid']] = $stat;
                }
            }
            //
            $last_statuses = $tmp;
            //
            unset($tmp);
        }
        //
        foreach ($employees as $index => $employee) {
            //
            if (in_array($employee['employee_sid'], $transferIds)) {
                $transferDate = get_employee_transfer_date($employee['employee_sid']);
                $employees[$index]['trensfer_date'] = $transferDate;
            }
            //
            $employees[$index]['last_status'] = isset($statuses[$employee['employee_sid']]) ? $statuses[$employee['employee_sid']] : [];
            $employees[$index]['last_status_2'] = isset($last_statuses[$employee['employee_sid']]) ? $last_statuses[$employee['employee_sid']] : [];
            $employees[$index]['last_status_text'] = isset($last_statuses[$employee['employee_sid']]) ? GetEmployeeStatusText($last_statuses[$employee['employee_sid']]['employee_status']) : '';
        }
        //
        return true;
    }

    private function sendEmailToDeveloper($message, $data)
    {
        mail(
            'mubashar.ahmed@egenienext.com',
            $message,
            json_encode($data)
        );
    }

    public function syncJobRoles($departmentId, $jobTitle)
    {
        //
        $complyRoleId = 0;
        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        //
        $complyJobTitles = $this->clib->getJobRolesByDepartmentId(
            $departmentId
        );
        //
        if (isset($complyJobTitles['error'])) {
            $reportError = array();
            $reportError['department_sid'] = $departmentId;
            $reportError['response'] = $complyJobTitles;
            //
            $message = 'JobRole not found on ComplyNet against department ID <strong>' . $departmentId . '</strong>';
            //
            $this->sendEmailToDeveloper($message, $reportError);
            //
            return $complyRoleId;
        }
        //
        if (!empty($complyJobTitles)) {
            foreach ($complyJobTitles as $title) {
                //
                $record = $this->db
                    ->select('sid, complynet_job_role_sid')
                    ->where([
                        'complynet_department_sid' => $departmentId,
                        'job_title' => $jobTitle
                    ])
                    ->get('complynet_jobRole')
                    ->row_array();
                //
                if (empty($record)) {
                    $ins = [];
                    $ins['complynet_department_sid'] = $departmentId;
                    $ins['complynet_job_role_sid'] = $title['Id'];
                    $ins['complynet_job_role_name'] = $title['Name'];
                    $ins['job_title'] = $jobTitle;
                    $ins['status'] = 1;
                    $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                    //
                    $this->db->insert('complynet_jobRole', $ins);
                } else if (!empty($record) && $record['complynet_job_role_sid'] == 0) {
                    $this->db
                        ->where('sid', $record['sid'])
                        ->update('complynet_jobRole', [
                            'complynet_job_role_sid' => $title['Id']
                        ]);
                }
                //
                $slug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($jobTitle)));
                $slugComply = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($title['Name'])));
                //
                if ($slug == $slugComply) {
                    $complyRoleId = $title['Id'];
                }
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
            if (empty($complyRoleId) || isset($complyRoleId['error'])) {
                //
                $reportError = array();
                $reportError['response'] = $complyRoleId;
                $reportError['complynetObject'] = json_encode([
                    'ParentId' => $departmentId,
                    'Name' => $jobTitle
                ]);
                //
                $message = 'JobRole not created on ComplayNet.';
                //
                $this->sendEmailToDeveloper($message, $reportError);
                //
                return $complyRoleId;
            }
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

    function getComplyNetEmployeeDepartment($employeeId)
    {
        $this->db->select('complynet_departments.department_sid');
        $this->db->where('complynet_employees.employee_sid', $employeeId);
        $this->db->join('complynet_departments', 'complynet_departments.complynet_department_sid = complynet_employees.complynet_department_sid', 'inner');

        $record_obj = $this->db->get('complynet_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr['department_sid'];
        } else {
            return '';
        }
    }


    /**
     * Copy employee department and team
     * 
     * @param array $passArray
     * 
     * @return bool
     */
    public function checkAndMoveEmployeeDepartmentAndTeam(array $passArray)
    {
        //
        // Get Employee Complynet department
        $complynetEmployeeDepartmentId = $this->getComplyNetEmployeeDepartment($passArray['oldEmployeeId']);
        if (!$complynetEmployeeDepartmentId) {
            return false;
        }
        //
        $whereArray = [];
        $whereArray['departments_employee_2_team.employee_sid'] = $passArray['oldEmployeeId'];
        //
        if ($complynetEmployeeDepartmentId) {
            $whereArray['departments_management.sid'] = $complynetEmployeeDepartmentId;
        } else {
            $whereArray['departments_management.is_deleted'] = 0;
            $whereArray['departments_team_management.is_deleted'] = 0;
        }
        // get the employee's current department and team name
        $this->db->select('
            departments_management.name as department_name,
            departments_team_management.name
        ')->where($whereArray);
        //
        $this->db->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid');
        $this->db->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid');
        $record_obj = $this->db->get('departments_employee_2_team');
        $records = $record_obj->result_array();
        $record_obj->free_result();
        //  
        if (!$records) {
            return false;
        }
        //
        foreach ($records as $record) {
            //
            $departmentId = 0;
            $teamId = 0;
            //
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $record['department_name']));
            $slugTeam = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $record['name']));
            // check if department exists
            if ($this->db->where(['LOWER(REGEXP_REPLACE(name, "[^a-zA-Z0-9]", "")) = ' => $slug, 'company_sid' => $passArray['newCompanyId']])->count_all_results('departments_management')) {
                //
                $departmentId = $this->db
                    ->select('sid')
                    ->where(['LOWER(REGEXP_REPLACE(name, "[^a-zA-Z0-9]", "")) = ' => $slug, 'company_sid' => $passArray['newCompanyId']])
                    ->get('departments_management')
                    ->row_array()['sid'];
                //
                if ($this->db->where([
                    'LOWER(REGEXP_REPLACE(name, "[^a-zA-Z0-9]", "")) = ' => $slugTeam,
                    'company_sid' => $passArray['newCompanyId'],
                    'department_sid' => $departmentId
                ])->count_all_results('departments_team_management')) {
                    //
                    $teamId = $this->db
                        ->select('sid')
                        ->where([
                            'LOWER(REGEXP_REPLACE(name, "[^a-zA-Z0-9]", "")) = ' => $slugTeam,
                            'company_sid' => $passArray['newCompanyId'],
                            'department_sid' => $departmentId
                        ])
                        ->get('departments_team_management')
                        ->row_array()['sid'];
                }
            } else {
                //


                $description = "This department was established for the purpose of " . getUserNameBySID($passArray['newEmployeeId']) . " on " . formatDateToDB(getSystemDate(), DB_DATE_WITH_TIME, DATE_WITH_TIME);

                $this->db->insert('departments_management', [
                    'name' => $record['department_name'],
                    'status' => 1,
                    'company_sid' => $passArray['newCompanyId'],
                    'created_date' => getSystemDate(),
                    'created_by_sid' => getCompanyAdminSid($passArray['newCompanyId']),
                    'description' => $description
                ]);
                //
                $departmentId = $this->db->insert_id();
            }

            //
            if ($teamId == 0) {
                //

                $description = "This team was established under the " . getDepartmentNameBySID($departmentId) . " Department for " . getUserNameBySID($passArray['newEmployeeId']) . " on " . formatDateToDB(getSystemDate(), DB_DATE_WITH_TIME, DATE_WITH_TIME);

                $this->db->insert('departments_team_management', [
                    'name' => $record['name'],
                    'department_sid' => $departmentId,
                    'status' => 1,
                    'company_sid' => $passArray['newCompanyId'],
                    'created_date' => getSystemDate(),
                    'created_by_sid' => getCompanyAdminSid($passArray['newCompanyId']),
                    'description' => $description
                ]);

                //
                $teamId =
                    $this->db->insert_id();
            }
            //
            if (!$this->db->where([
                'department_sid' => $departmentId,
                'team_sid' => $teamId,
                'employee_sid' => $passArray['newEmployeeId'],
            ])->count_all_results('departments_employee_2_team')) {
                // let link the employee to it
                $this->db->insert('departments_employee_2_team', [
                    'department_sid' => $departmentId,
                    'team_sid' => $teamId,
                    'employee_sid' => $passArray['newEmployeeId'],
                    'created_at' => getSystemDate()
                ]);
            }


            // update user table
            $this->db
                ->where('sid', $passArray['newEmployeeId'])
                ->update('users', [
                    'department_sid' => $departmentId,
                    'team_sid' => $teamId,
                ]);
        }
        //
        return true;
    }

    /**
     * Transfer employee from one location to another
     * 
     * @param array $passArray
     * @return bool
     */
    public function transferEmployeeToAnotherLocation(array $passArray)
    {
        // check if employee is on ComplyNet
        $complyOldEmployee = $this->db
            ->where('employee_sid', $passArray['oldEmployeeId'])
            ->get('complynet_employees')
            ->row_array();

        //
        if (!$complyOldEmployee) {
            return ['errors' => "Transferred employee was not on ComplyNet."];
        }
        // check if company is on ComplyNet
        $company = $this->getIntegratedCompany($passArray['newCompanyId']);
        // if not then return
        if (!$company) {
            return ['errors' => 'Company is not on ComplyNet'];
        }
        // resync departments to make sure all is working
        $this->syncDepartments($passArray['newCompanyId']);
        // get new employee profile
        // Get company job roles
        $employee = $this->getCompanyEmployee(
            $passArray['newCompanyId'],
            $passArray['newEmployeeId']
        );
        // in case employee not found
        if (!$employee) {
            return ['errors' => 'Employee not found.'];
        }
        // set a default error array
        $errorArray = [];
        // lower the email address
        $email = strtolower($employee['email']);
        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        // //
        if ($this->findEmployeeBySid($passArray['newEmployeeId'], $passArray['newCompanyId'])) {
            $errorArray[] = 'Employee already synced with ComplyNet.';
            return $errorArray;
        }
        //
        $employee['complynet_job_title'] = $this->checkJobRoleForComplyNet($employee['job_title'], $employee['complynet_job_title']);
        $oldComplynetEmployeeData =  $this->checkJobRoleForComplyNet($employee['job_title'], $employee['complynet_job_title']);
        // check the missing data
        $complyOldEmployeeInfo = json_decode($complyOldEmployee['complynet_json'], true);
        //
        $employee = $this->addMissingEmployeeInfo($complyOldEmployeeInfo[0], $employee);

        if (checkEmployeeMissingData($employee)) {
            //
            mail('mubashar@automotohr.com', 'Employee ' . getUserNameBySID($employee['sid']) . ' data missing while moving on complynet: ', json_encode(checkEmployeeMissingData($employee)));
            return checkEmployeeMissingData($employee);
        }
        // get the comply department id
        $complyDepartmentId = $this->getEmployeeDepartmentId(
            $employee['sid']
        );
        //
        if ($complyDepartmentId === 0) {

            $errorArray[] = 'Department not found.';
            return $errorArray;
        }
        //
        $complyJobRoleId = $this->getAndSetJobRoleId(
            $complyDepartmentId,
            $employee['complynet_job_title']
        );
        if ($complyJobRoleId === 0) {
            $errorArray[] = 'Job role not found.';
            return $errorArray;
        }
        //
        if (empty($complyJobRoleId)) {
            $errorArray[] = 'Job role not found.';
            return $errorArray;
        }
        // set update array
        $updateArray = [];
        $updateArray["firstName"] = $employee['first_name'];
        $updateArray["lastName"] = $employee['last_name'];
        $updateArray["userName"] = $email;
        $updateArray["email"] = $email;
        $updateArray["companyId"] = $company['complynet_company_sid'];
        $updateArray["locationId"] = $company['complynet_location_sid'];
        $updateArray["departmentId"] = $complyDepartmentId;
        $updateArray["jobRoleId"] = $complyJobRoleId;
        $updateArray["PhoneNumber"] = $employee['PhoneNumber'];
        $updateArray["password"] = 'password';
        $updateArray["TwoFactor"] = false;
        $updateArray["AltId"] = $complyOldEmployee['alt_id'];
        // // set insert array
        $complyArray = $complyOldEmployee;
        unset($complyArray['sid']);
        $complyArray['company_sid'] = $passArray['newCompanyId'];
        $complyArray['employee_sid'] = $passArray['newEmployeeId'];
        $complyArray['complynet_company_sid'] = $company['complynet_company_sid'];
        $complyArray['complynet_location_sid'] = $company['complynet_location_sid'];
        $complyArray['complynet_department_sid'] = $complyDepartmentId;
        $complyArray['complynet_job_role_sid'] = $complyJobRoleId;
        $complyArray['created_at'] = $complyArray['updated_at'] = getSystemDate();
        // lets check the employee
        $employeeObj = $this->clib->getEmployeeByEmail($email);
        //
        if (isset($employeeObj[0]['Id'])) {
            //
            if (!$employeeObj[0]['AltId']) {
                $this->clib->updateAltId($employeeObj[0]['Id'], $updateArray['AltId']);
                // lets check the employee
                $employeeObj = $this->clib->getEmployeeByEmail($email);
            }
            //
            $employeeObj = findTheRightEmployee($employeeObj, $complyArray['complynet_company_sid'], $complyArray['complynet_location_sid']);
            //
            if ($employeeObj) {
                //
                if (!$this->db->where(['employee_sid' => $passArray['newEmployeeId']])->count_all_results('complynet_employees')) {
                    $complyArray['complynet_employee_sid'] = $employeeObj['Id'];
                    $complyArray['complynet_json'] = json_encode($employeeObj);
                    // add the new record
                    $this->db->insert('complynet_employees', $complyArray);
                } else {
                    //
                    $this->db->where([
                        'employee_sid' => $passArray['newEmployeeId']
                    ])->update(
                        'complynet_employees',
                        [
                            'complynet_employee_sid' => $employeeObj['Id'],
                            'complynet_json' => json_encode($employeeObj)
                        ]
                    );
                }
                //
                return ['success' => 'Employee synced.'];
            }
        }
        //
        $response = $this->clib->updateUser($updateArray);
        //
        if (preg_match('/\s+Update/i', $response)) {
            //
            if (!$this->db->where(['employee_sid' => $passArray['newEmployeeId']])->count_all_results('complynet_employees')) {
                // add the new record
                $this->db->insert('complynet_employees', $complyArray);
            }
            //  get the employee
            $employeeObj = $this->clib->getEmployeeByEmail($email);
            //
            if (isset($employeeObj[0]['Id'])) {
                $employeeObj = findTheRightEmployee($employeeObj, $complyArray['complynet_company_sid'], $complyArray['complynet_location_sid']);
                //
                if ($employeeObj) {
                    $this->db->where([
                        'employee_sid' => $passArray['newEmployeeId']
                    ])->update('complynet_employees', [
                        'complynet_employee_sid' => $employeeObj['Id'],
                        'complynet_json' => json_encode($employeeObj)
                    ]);
                }
            }
            return ['success' => 'Employee synced.'];
        }
        //
        return ['errors' => 'Failed to add employees'];
    }


    /**
     * 
     */
    public function manageEmployee(array $passArray)
    {
        //
        $departmentAdded = $this->checkAndMoveEmployeeDepartmentAndTeam($passArray);
        //
        if (!$departmentAdded) {
            return false;
        }
        // transfer employee to another location
        return $this->transferEmployeeToAnotherLocation($passArray);
    }

    public function addMissingEmployeeInfo($oldEmployee, $newEmployee)
    {
        //
        if (!$newEmployee['first_name']) {
            $newEmployee['first_name'] = $oldEmployee['first_name'];
        }
        if (!$newEmployee['last_name']) {
            $newEmployee['last_name'] = $oldEmployee['last_name'];
        }
        if (!$newEmployee['PhoneNumber']) {
            $newEmployee['PhoneNumber'] = $oldEmployee['PhoneNumber'];
        }
        if (!$newEmployee['complynet_job_title']) {;
            $newEmployee['complynet_job_title'] = $oldEmployee['JobRole'];
        }
        //
        return $newEmployee;
    }

    /**
     * Get ComplyNet synced departments
     *
     * @param int $companyId
     * @return array
     */
    public function getComplyNetSyncedDepartmentsByCompanyId(int $companyId)
    {
        // get departments
        return $this->db
            ->select(
                [
                    'complynet_departments.department_sid',
                    'complynet_departments.department_name',
                    'complynet_departments.complynet_department_sid',
                    'complynet_departments.complynet_department_name',
                    'complynet_departments.created_at'
                ]
            )
            ->join(
                'departments_management',
                'departments_management.sid = complynet_departments.department_sid',
                'inner'
            )
            ->where([
                'complynet_departments.company_sid' => $companyId,
                'departments_management.is_deleted' => 0,
            ])
            ->order_by('complynet_departments.sid', 'DESC')
            ->get('complynet_departments')
            ->result_array();
    }

    public  function getEmployeeOldComplyNetDepartment(int $employeeId): string
    {
        $this->db
            ->select('complynet_department_sid')
            ->where('employee_sid', $employeeId)
            ->limit(1);
        //
        $data = $this->db->get('complynet_employees')->row_array();
        //
        return $data["complynet_department_sid"] ?? '';
    }

    //
    public function getDepartmentName(
        int $employeeId
    ) {
        //
        $record =
            $this->db
            ->select('departments_management.name, departments_management.sid')
            ->where([
                'departments_management.is_deleted' => 0,
                'departments_employee_2_team.employee_sid' => $employeeId
            ])
            ->from('departments_employee_2_team')
            ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid')
            ->get()
            ->row_array();
        //
        return $record;
    }

    /**
     * 
     */
    public function checkAndCreateDepartmentOnComplyNet(int $departmentId, string $departmentName, int $companyId)
    {
        $company = $this->getIntegratedCompany(
            $companyId
        );
        //  
        $complyLocationId = $company['complynet_location_sid'];

        // Get all departments from ComplyNet
        $complyDepartments = $this->clib->getComplyNetDepartments(
            $complyLocationId
        );

        // Convert department to index

        if (!empty($complyDepartments)) {
            //
            $systemDepartmentslug = preg_replace('/[^a-zA-Z]/', '', strtolower($departmentName));

            foreach ($complyDepartments as $department) {
                //
                $complyNetDepartmentslug = preg_replace('/[^a-zA-Z]/', '', strtolower($department['Name']));

                //Link Department
                if ($systemDepartmentslug == $complyNetDepartmentslug) {
                    //
                    $ins = [];
                    $ins['company_sid'] = $companyId;
                    $ins['department_sid'] = $departmentId;
                    $ins['complynet_department_sid'] = $department['Id'];
                    $ins['complynet_department_name'] = $department['Name'];
                    $ins['department_name'] = $departmentName;
                    $ins['status'] = 1;
                    $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                    //
                    $this->checkAndInsertDepartmentLink($ins);

                    return $department['Id'];
                }
            }
        }

        //Create Department On ComplyNet

        $response = $this->clib->addDepartmentToComplyNet([
            'ParentId' => $complyLocationId,
            'Name' => $departmentName
        ]);

        if (isset($response['error'])) {
            return 0;
        }

        //
        if ($response && !empty($response['Id'])) {
            //
            if ($response['Id'] == 'A') {
                return 0;
            }
            $ins = [];
            $ins['company_sid'] = $companyId;
            $ins['department_sid'] = $departmentId;
            $ins['complynet_department_sid'] = $response['Id'];
            $ins['complynet_department_name'] = $response['Name'];
            $ins['department_name'] = $departmentName;
            $ins['status'] = 1;
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->checkAndInsertDepartmentLink($ins);
            return $response['Id'];
        }
        return 0;
    }

    //
    public function getemployeeComplyNetJobTitle(int $employeeId)
    {
        return $this->db
            ->select('complynet_job_title')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
    }

    public function updateEmployeeJobTitleOnComplyNet(
        int $employeeId,
        string $employeeNewDepartmentId,
        string $complyJobRoleId
    ) {
        // Check if user is on ComplyNet
        if (!$this->db->where('employee_sid', $employeeId)->count_all_results('complynet_employees')) {
            return false;
        }
        $employeeDetails = $this->db
            ->select('
                users.first_name,
                users.last_name,
                users.email,
                users.username,
                users.job_title,
                users.complynet_job_title,
                users.PhoneNumber,
                users.department_sid,
                users.team_sid
            ')
            ->where('users.sid', $employeeId)
            ->get('users')
            ->row_array();
        //
        if (empty($employeeDetails)) {
            return false;
        }

        // fetch complynet object
        $complyEmployee = $this->db
            ->where('employee_sid', $employeeId)
            ->get('complynet_employees')->row_array();
        //
        if (!$complyEmployee['alt_id']) {
            return false;
        }
        // make update array
        $updateArray['firstName'] = $employeeDetails['first_name'];
        $updateArray['lastName'] =  $employeeDetails['last_name'];
        $updateArray['userName'] = $complyEmployee['email'];
        $updateArray['email'] =    $employeeDetails['email'];
        $updateArray['password'] = 'password';
        $updateArray['companyId'] = $complyEmployee['complynet_company_sid'];
        $updateArray['locationId'] = $complyEmployee['complynet_location_sid'];
        $updateArray['departmentId'] = $employeeNewDepartmentId;
        $updateArray['jobRoleId'] = $complyJobRoleId;
        $updateArray['PhoneNumber'] = $employeeDetails['PhoneNumber'];
        $updateArray['TwoFactor'] = "FALSE";
        $updateArray['AltId'] = $complyEmployee['alt_id'];
        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        //
        $response = $this->clib->updateUser($updateArray);
        //
        if (preg_match('/\s+Update/', $response)) {

            //Update Employee
            $updateData['complynet_department_sid'] = $employeeNewDepartmentId;
            $updateData['complynet_job_role_sid'] = $complyJobRoleId;

            $this->db
                ->where('employee_sid', $employeeId)
                ->update('complynet_employees', $updateData);
            return true;
        }
        return false;
    }

    //
    public  function getEmployeeOldComplyNetJobRole(int $employeeId): string
    {
        $this->db
            ->select('complynet_job_role_sid')
            ->where('employee_sid', $employeeId)
            ->limit(1);
        //
        $data = $this->db->get('complynet_employees')->row_array();
        //
        return $data["complynet_job_role_sid"] ?? '';
    }

    //
    public function getComplyNetJobTitle(
        int $employeeId
    ) {
        //
        $record =
            $this->db
            ->select('complynet_job_title')
            ->where('sid', $employeeId)
            ->from('users')
            ->get()
            ->row_array();
        //
        return $record;
    }

    //
    public function getEmployeeNewComplyNetJobRole(
        string $complynetDepartmentId,
        string $complynetJobTitle
    ) {

        $employeeComplyNetJobTitleslug = preg_replace('/[^a-zA-Z]/', '', strtolower($complynetJobTitle));

        $JobTitles = $this->db
            ->select('
                        complynet_department_sid,
                        complynet_job_role_sid,
                        complynet_job_role_name,
                        job_title
                      ')
            ->where('complynet_department_sid', $complynetDepartmentId)
            ->get('complynet_jobRole')
            ->result_array();
        //
        if (empty($JobTitles)) {
            return '';
        } else {
            foreach ($JobTitles as $JobTitleRow) {

                $employeeJobTitleslug = preg_replace('/[^a-zA-Z]/', '', strtolower($JobTitleRow['complynet_job_role_name']));
                if ($employeeJobTitleslug == $employeeComplyNetJobTitleslug) {
                    return $JobTitleRow['complynet_job_role_sid'];
                }
            }
        }

        return '';
    }

    //
    public function updateComplyNetEmployeeJobTitle(
        $employeeId,
        $companyId,
        $updateData
    ) {
        $this->db
            ->where('employee_sid', $employeeId)
            ->where('company_sid', $companyId)
            ->update('complynet_employees', $updateData);
    }

    //
    public function getAndSetComplyNetJobRoleId(
        string $departmentId,
        string $jobTitle
    ) {
        // fetch complynet
        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        //
        $complyJobTitles = $this->clib->getJobRolesByDepartmentId(
            $departmentId
        );
        //
        $complyRoleId = 0;
        //
        if (isset($complyJobTitles['error'])) {

            return $complyRoleId;
        }

        $slug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($jobTitle))); //

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


        // Add to ComplyNet
        if ($complyRoleId === 0) {
            // Let's add the job role
            $complyRoleId = $this->clib->addJobRole([
                'ParentId' => $departmentId,
                'Name' => $jobTitle
            ]);
            //
            if (empty($complyRoleId) || isset($complyRoleId['error'])) {
                //
                return $complyRoleId;
            }
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

            return $complyRoleId;
        }
    }

    function deleteAllPreviousDepartment($companyId)
    {
        //
        $linkDepartments = $this->getComplyNetLinkedDepartmentsSid($companyId);
        //
        foreach ($linkDepartments as $departmentId) {
            // delete linked department
            $this->db->where('complynet_department_sid', $departmentId);
            $this->db->delete('complynet_departments');
        }
    }

    function deleteDepartmentLinkAndJobRoll($departmentId)
    {
        // delete linked department
        $this->db->where('complynet_department_sid', $departmentId);
        $this->db->delete('complynet_departments');
        //
        // delete above departments jobRolls
        $this->db->where('complynet_department_sid', $departmentId);
        $this->db->delete('complynet_jobRole');
    }
}
