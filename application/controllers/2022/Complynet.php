<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * ComplyNet
 *
 * @version 1.0
 */
class Complynet extends Admin_Controller
{
    /**
     * Set the company id for sync
     * @var int
     */
    private $companyId;

    /**
     * Set the location id for sync
     * @var int
     */
    private $complyLocationId;

    /**
     * Set the location id for sync
     * @var int
     */
    private $complyCompanyId;

    /**
     * Set the department id for sync
     * @var int
     */
    private $complyDepartmentIds;

    /**
     * Constructor
     */
    public function __construct()
    {
        //
        parent::__construct();
        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        //
        $this->load->model('2022/complynet_model', 'complynet_model');
        //
        $this->companyId = 0;
    }


    /**
     * Dashboard
     */
    public function dashboard()
    {
        $this->data['page_title'] = 'ComplyNet Dashboard';
        $this->data['security_details'] = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        $this->data['PageScripts'] = [
            'https://cdn.jsdelivr.net/npm/chart.js',
            'js/SystemModal',
            '1.0' => '2022/js/complynet/dashboard'
        ];
        $this->data['PageCSS'] = [
            'css/SystemModel'
        ];
        // Get companies
        $this->data['companies'] = $this->complynet_model->getCompanies('active');
        //
        $this->render('2022/complynet/dashboard', 'admin_master');
    }

    /**
     * Dashboard
     */
    public function manageJobRoles()
    {
        $this->data['page_title'] = 'Manage Job Roles';
        $this->data['customSelect2'] = true;
        $this->data['security_details'] = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        $this->data['PageScripts'] = [
            'js/SystemModal',
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js',
            '1.0' => '2022/js/complynet/manage_job_roles'
        ];
        $this->data['PageCSS'] = [
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css',
            'css/SystemModel'
        ];
        // Get companies
        $this->data['job_roles'] = $this->complynet_model->getTableData('complynet_job_roles', ['status' => 1]);
        //
        $this->render('2022/complynet/manage_job_roles', 'admin_master');
    }

    /**
     * AJAX CALLS
     */

    /**
     * Check company integration
     * @param int $companyId
     * @return
     */
    public function checkCompanyIntegration(int $companyId)
    {
        //
        if (!$this->checkLogin()) {
            return SendResponse(401); // Forbidden
        }
        //
        $companyId = (int) $companyId;
        //
        $record = $this->complynet_model->checkAndGetCompanyIntegrationDetails($companyId);
        //
        return SendResponse(200, $record ?? []);
    }

    /**
     * Start with company integration
     * @param int $companyId
     * @return
     */
    public function gettingStarted(int $companyId)
    {
        //
        if (!$this->checkLogin()) {
            return SendResponse(401); // Forbidden
        }
        //
        $companyId = (int) $companyId;
        //
        $complyCompanies = $this->clib->getComplyNetCompanies();
        //
        $returnArray = [];
        $returnArray['companyName'] = $this->complynet_model->getUserColumn(
            $companyId,
            'CompanyName'
        );
        $returnArray['view'] = $this->load->view('2022/complynet/partials/company_integration', [
            'companyName' => $returnArray['companyName'],
            'complyCompanies' => $complyCompanies
        ], true);
        //
        return SendResponse(200, $returnArray);
    }

    /**
     * Get ComplyNet company locations
     * @param string $companyId
     * @return
     */
    public function getComplyNetLocations(string $companyId)
    {
        //
        if (!$this->checkLogin()) {
            return SendResponse(401); // Forbidden
        }
        //
        $companyId = (string) $companyId;
        //
        $complyLocations = $this->clib->getComplyNetCompanyLocations($companyId);
        //
        return SendResponse(200, $complyLocations);
    }

    /**
     * Integrate company
     * @return
     */
    public function integrate()
    {
        //
        if (!$this->checkLogin()) {
            return SendResponse(401); // Forbidden
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $companyId = (int) $post['companyId'];
        $companyName = (string) $post['companyName'];
        $complyCompanyId = (string) $post['complyCompanyId'];
        $complyCompanyName = (string) $post['complyCompanyName'];
        $complyLocationId = (string) $post['complyLocationId'];
        $complyLocationName = (string) $post['complyLocationName'];
        // Check if the company is integrated or not
        $company = $this->complynet_model->getIntegratedCompany(
            $companyId
        );
        //
        if ($company) {
            //
            if ($company['complynet_location_sid'] == $complyLocationId) {
                //
                return $this->sync($companyId);
            }
            //
            return SendResponse(200, [
                'error' => 'Company already integrated with "' . ($company['complynet_company_name']) . '".'
            ]);
        }
        //
        $ins = [];
        $ins['company_sid'] = $companyId;
        $ins['company_name'] = $companyName;
        $ins['complynet_company_sid'] = $complyCompanyId;
        $ins['complynet_company_name'] = $complyCompanyName;
        $ins['complynet_location_sid'] = $complyLocationId;
        $ins['complynet_location_name'] = $complyLocationName;
        $ins['status'] = 1;
        $ins['created_at'] = $ins['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
        //
        $this->db->insert(
            'complynet_companies',
            $ins
        );
        //
        return $this->sync($companyId);
    }

    /**
     * Integrate view
     * @return
     */
    public function integrateView(
        int $companyId
    ) {
        //
        $data = [];
        // Get company
        $data['company'] = $this->complynet_model->getTableData(
            'complynet_companies',
            [
                'company_sid' => $companyId
            ]
        )[0];

        // Get company all departments
        $data['allDepartmentCount'] = $this->complynet_model->getCompanyAllDepartments($companyId, 'count');

        // Get departments
        $data['departments'] = $this->complynet_model->getComplyNetSyncedDepartmentsByCompanyId($companyId);

        // Get job roles
        $data['employees'] = $this->complynet_model->getTableData(
            'complynet_employees',
            [
                'company_sid' => $companyId
            ]
        );
        //
        $data['offComplyNetEmployees'] = $this->complynet_model->getOffComplyNetEmployees(
            array_column($data['employees'], 'employee_sid'),
            $companyId
        );
        //
        return SendResponse(200, [
            'view' => $this->load->view('2022/complynet/partials/company_integration_view', $data, true)
        ]);
    }

    public function syncCompany()
    {
        //
        $companyId = $this->input->post('companyId', true);
        //
        return $this->sync($companyId);
    }

    /**
     * Sync company with complynet
     *
     * @param int $companyId
     */
    private function sync($companyId)
    {
        $company = $this->complynet_model->getIntegratedCompany(
            $companyId
        );
        //
        $this->companyId = $companyId;
        //
        $this->complyCompanyId = $company['complynet_company_sid'];
        $this->complyLocationId = $company['complynet_location_sid'];

        // load model
        $this->load->model(
            "2022/Complynet_cron_model",
            "complynet_cron_model"
        );
        // sync company departments and job roles
        $this
            ->complynet_cron_model
            ->synCompanyDepartments($companyId);
        // Lets sync job roles
        $this->syncEmployees();
        //
        return SendResponse(200, ['message' => 'Success']);
    }

    /**
     * Sync company with complynet
     */
    private function syncDepartments()
    {
        // Get company departments
        $departments = $this->complynet_model->getCompanyDepartments(
            $this->companyId
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
            $this->complyLocationId
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
                    //
                    if ($complyDepartmentObj[$index]['Id'] == 'A') {
                        continue;
                    }
                    // the department is on complynet
                    // let's connect in our system
                    $ins = [];
                    $ins['company_sid'] = $this->companyId;
                    $ins['department_sid'] = $value['sid'];
                    $ins['complynet_department_sid'] = $complyDepartmentObj[$index]['Id'];
                    $ins['complynet_department_name'] = $complyDepartmentObj[$index]['Name'];
                    $ins['department_name'] = $value['name'];
                    $ins['status'] = 1;
                    $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                    //
                    $this->complynet_model->checkAndInsertDepartmentLink($ins);
                } else {
                    // create the department on ComplyNet
                    // then connect it in our system
                    $response = $this->clib->addDepartmentToComplyNet([
                        'ParentId' => $this->complyLocationId,
                        'Name' => $value['name']
                    ]);
                    //
                    if ($response && !empty($response['Id'])) {
                        //
                        if ($response['Id'] == 'A') {
                            continue;
                        }
                        // Let connect
                        $ins = [];
                        $ins['company_sid'] = $this->companyId;
                        $ins['department_sid'] = $value['sid'];
                        $ins['complynet_department_sid'] = $response['Id'];
                        $ins['complynet_department_name'] = $response['Name'];
                        $ins['department_name'] = $value['name'];
                        $ins['status'] = 1;
                        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                        //
                        $this->complynet_model->checkAndInsertDepartmentLink($ins);
                    }
                }
            }
        }

        // Lets hook departments
        if (!empty($complyDepartmentObj)) {
            //
            $this->complyDepartmentIds = [];
            //
            foreach ($complyDepartmentObj as $index => $value) {
                //
                $this->complyDepartmentIds[] = $value['Id'];
                // create the department on system
                // then connect it to ComplyNet
                $ins = [];
                $ins['company_sid'] = $this->companyId;
                $ins['name'] = $value['Name'];
                $ins['status'] = 1;
                $ins['created_by_sid'] = 0;
                $ins['created_date'] = getSystemDate();
                //
                $departmentId = $this->complynet_model
                    ->checkAndInsertDepartment($ins);
                // Let connect
                $ins = [];
                $ins['company_sid'] = $this->companyId;
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
                $this->complynet_model->checkAndInsertDepartmentLink($ins);
            }
        }
    }

    /**
     * Sync company employees with complynet
     */
    private function syncEmployees()
    {
        // Get company job roles
        $employees = $this->complynet_model->getCompanyEmployees(
            $this->companyId
        );
        //
        if (!empty($employees)) {
            //
            foreach ($employees as $employee) {
                //
                $this->complynet_model->syncSingleEmployee($this->companyId, $employee['sid']);
            }
            //
            return true;
        }

        return false;
    }


    public function getComplyCompanyDepartments(
        $companyId
    ) {
        $company = $this->complynet_model->getIntegratedCompany(
            $companyId
        );
        //
        $complyCompanyId = $company['complynet_company_sid'];
        $complyLocationId = $company['complynet_location_sid'];

        // Get all departments from ComplyNet
        $complyDepartments = $this->clib->getComplyNetDepartments(
            $complyLocationId
        );
        //
        $data = [];
        $data['title'] = 'Departments';
        $data['records'] = $complyDepartments;

        //
        if ($complyDepartments) {
            $activeComplyNetIds = array_column($complyDepartments, 'Id');
            //
            $this->db
                ->where('company_sid', $companyId)
                ->where_not_in('complynet_department_sid', $activeComplyNetIds)
                ->delete('complynet_departments');
        }

        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('2022/complynet/partials/show_table', $data, true)
            ]
        );
    }

    public function getComplyCompanyJobRoles(
        $companyId
    ) {
        $company = $this->complynet_model->getIntegratedCompany(
            $companyId
        );

        //
        $complyCompanyId = $company['complynet_company_sid'];
        $complyLocationId = $company['complynet_location_sid'];

        // Get all departments from ComplyNet
        $complyDepartments = $this->clib->getComplyNetDepartments(
            $complyLocationId
        );
        //
        $data = [];
        $data['title'] = 'Job Roles';
        $records = [];
        //
        foreach ($complyDepartments as $department) {
            //
            $records[$department['Name']] = $this->clib->getJobRolesByDepartmentId($department['Id']);
        }
        $data['records'] = $records;

        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('2022/complynet/partials/show_table_jobs', $data, true)
            ]
        );
    }

    public function getEmployeeDetail(
        int $rowId
    ) {
        //
        $data['data'] = $this->complynet_model->getEmployeeDetailById(
            $rowId
        );

        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('2022/complynet/partials/employee_details', $data, true)
            ]
        );
    }

    /**
     * Sync single employee with complynet
     *
     * @param int $companyId
     * @return json
     */
    public function syncSingleEmployee(int $companyId)
    {
        //
        $employeeId = $this->input->post('employeeId', true);
        //
        return $this->complynet_model->syncSingleEmployee($companyId, $employeeId);
    }


    public function getSystemJobRoles(
        int $complyJobRoleId
    ) {
        //
        $data = [];
        // get comply job role name by id
        $data['complyJobRoleName'] = $this->complynet_model->getComplyJobRole(
            $complyJobRoleId,
            'job_title'
        )['job_title'];
        //
        $data['alreadyLinked'] = array_column($this->complynet_model->getLinkedRoles(), 'job_title');
        // get system job roles
        $data['systemJobRoles'] = $this->complynet_model->getSystemJobRoles();

        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('2022/complynet/partials/add_job_role', $data, true)
            ]
        );
    }

    public function getRoleDetails(
        int $complyJobRoleId
    ) {
        //
        $data = [];
        // get comply job role name by id
        $data['complyJobRoleName'] = $this->complynet_model->getComplyJobRole(
            $complyJobRoleId,
            'job_title'
        )['job_title'];
        //
        $data['job_roles'] = $this->complynet_model->getLinkedJobRoles($complyJobRoleId);

        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('2022/complynet/partials/view_job_roles', $data, true)
            ]
        );
    }

    public function deleteJobRole(int $sid)
    {

        // get details
        $record =
            $this->db
            ->select('job_title, complynet_job_tile_sid')
            ->where('sid', $sid)
            ->get('complynet_job_roles_jobs')
            ->row_array();
        //
        if ($record) {
            //
            $this->db->query("UPDATE complynet_job_roles SET job_title_count = job_title_count -1 WHERE sid = " . ($record['complynet_job_tile_sid']) . ";");
        }
        // delete from list
        $this->db->where('sid', $sid)
            ->delete('complynet_job_roles_jobs');
        // unlink from users
        return SendResponse(200, ['success' => true]);
    }


    public function linkJobRoles(
        int $complyJobRoleId
    ) {
        //
        $post = $this->input->post(null, true);
        //
        $added = 0;
        // add the job title
        foreach ($post['job_roles'] as $role) {
            // check if already linked
            if (
                $this->db
                ->where(['complynet_job_tile_sid' => $complyJobRoleId, 'job_title' => $role])
                ->count_all_results('complynet_job_roles_jobs')
            ) {
                continue;
            }
            $added++;
            //
            $this->db
                ->insert('complynet_job_roles_jobs', [
                    'complynet_job_tile_sid' => $complyJobRoleId,
                    'job_title' => $role,
                    'created_at' => getSystemDate()
                ]);
        }
        //
        if ($added > 0) {
            //
            $this->db->query(
                "UPDATE complynet_job_roles SET job_title_count=job_title_count+{$added} WHERE sid = {$complyJobRoleId}"
            );
        }
        // remove selected job titles from other ids
        $this->db
            ->where_in('job_title', $post['job_roles'])
            ->where('complynet_job_tile_sid != ', $complyJobRoleId)
            ->delete('complynet_job_roles_jobs');
        //
        // get comply job role name by id
        $complyJobRoleName = $this->complynet_model->getComplyJobRole(
            $complyJobRoleId,
            'job_title'
        )['job_title'];
        //
        foreach ($post['job_roles'] as $role) {
            // update job titles on users
            $this->db
                ->where('job_title', $role)
                ->update('users', [
                    'complynet_job_title' => $complyJobRoleName
                ]);
        }
        // update job roles on complynet
        $users =
            $this->db
            ->select('
                sid,
                parent_sid,
                first_name,
                last_name,
                PhoneNumber,
                email
            ')
            ->where(['complynet_job_title' => $complyJobRoleName, 'complynet_status' => 1, 'complynet_onboard' => 1])
            ->get('users')
            ->result_array();
        //
        if ($users) {
            foreach ($users as $user) {
                $this->complynet_model->updateEmployeeOnComplyNet(
                    $user['parent_sid'],
                    $user['sid'],
                    $user
                );
            }
        }
        //
        return SendResponse(
            200,
            [
                'success' => true
            ]
        );
    }

    /**
     * Checks the login
     */
    private function checkLogin()
    {
        return (bool) $this->ion_auth->user()->row()->id;
    }

    /**
     * Fix empty job roles issue
     */
    public function fixEmptyJobRoles()
    {
        // Get all empty job roles
        $roles = $this->db
            ->select('sid, complynet_department_sid, job_title, complynet_job_role_sid')
            ->where('complynet_job_role_sid', '0')
            ->or_where('complynet_job_role_sid', '')
            ->get('complynet_jobRole')
            ->result_array();
        //
        if (!$roles) {
            exit('No roles found');
        }
        //
        $rolesByDepartment = [];
        //
        foreach ($roles as $role) {
            //
            $found = false;
            //
            if (!isset($rolesByDepartment[$role['complynet_department_sid']])) {
                //
                $rolesByDepartment[$role['complynet_department_sid']] = $this->clib->getJobRolesByDepartmentId($role['complynet_department_sid']);
            }
            //
            foreach ($rolesByDepartment[$role['complynet_department_sid']] as $departmentRole) {
                //
                if ($departmentRole['Name'] == $role['job_title']) {
                    $found = true;
                    $this->db->where('sid', $role['sid'])->update('complynet_jobRole', ['complynet_job_role_sid' => $departmentRole['Id']]);
                }
            }
            //
            if (!$found) {
                continue;
                // Add role to ComplyNet
                $response = $this->clib->addJobRole([
                    'ParentId' => $role['complynet_department_sid'],
                    'Name' => $role['job_title']
                ]);
                //
                if ($response != 'A') {
                    $this->db->where('sid', $role['sid'])->update('complynet_jobRole', ['complynet_job_role_sid' => $response]);
                }
            }
        }

        _e(count($rolesByDepartment));
        //
        exit('Roles processed.');
    }

    /**
     * remove connection
     *
     * @param int $employeeId
     * @return array
     */
    public function removeEmployee(int $employeeId): array
    {
        //
        $this->db->where('employee_sid', $employeeId)->delete('complynet_employees');
        //
        return SendResponse(200, ['success' => true]);
    }
}
