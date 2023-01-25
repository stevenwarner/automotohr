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
        $data['departments'] = $this->complynet_model->getTableData(
            'complynet_departments',
            [
                'company_sid' => $companyId
            ]
        );

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
        // Lets sync departments
        $this->syncDepartments();
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
                $email = strtolower($employee['email']);
                //
                if ($this->complynet_model->isEmployeeAdded($email, $this->companyId)) {
                    continue;
                }
                //
                $complyDepartmentId = $this->complynet_model->getEmployeeDepartmentId(
                    $employee['sid']
                );
                //
                if ($complyDepartmentId === 0) {
                    continue;
                }
                //
                $complyJobRoleId = $this->complynet_model->getAndSetJobRoleId(
                    $complyDepartmentId,
                    $employee['job_title']
                );
                //
                if ($complyJobRoleId === 0) {
                    continue;
                }
                //
                if (empty($complyJobRoleId)) {
                    continue;
                }

                // Check if exists in ComplyNet
                $employeeObj = $this->clib->getEmployeeByEmail($email);
                //
                if (isset($employeeObj[0]['Id'])) {
                    // Just link it
                    $ins = [];
                    $ins['company_sid'] = $this->companyId;
                    $ins['complynet_employee_sid'] = $employeeObj[0]['Id'];
                    $ins['complynet_company_sid'] = $this->complyCompanyId;
                    $ins['complynet_location_sid'] = $this->complyLocationId;
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
                    $this->db
                        ->where('sid', $employee['sid'])
                        ->update('users', [
                            'complynet_onboard' => 1
                        ]);
                } else {
                    $ins = [];
                    $ins['firstName'] = $employee['first_name'];
                    $ins['lastName'] = $employee['last_name'];
                    $ins['userName'] = $employee['username'];
                    $ins['email'] = $email;
                    $ins['password'] = 'password';
                    $ins['companyId'] = $this->complyCompanyId;
                    $ins['locationId'] = $this->complyLocationId;
                    $ins['departmentId'] = $complyDepartmentId;
                    $ins['jobRoleId'] = $complyJobRoleId;
                    $ins['PhoneNumber'] = $employee['PhoneNumber'];
                    $ins['TwoFactor'] = false;
                    //
                    $response = $this->clib->addEmployee($ins);

                    //
                    if (preg_match('/created user/i', $response)) {
                        $employeeObj = $this->clib->getEmployeeByEmail($email);
                        //
                        if (isset($employeeObj[0]['Id'])) {
                            $ins = [];
                            $ins['company_sid'] = $this->companyId;
                            $ins['complynet_employee_sid'] = $employeeObj[0]['Id'];
                            $ins['complynet_company_sid'] = $this->complyCompanyId;
                            $ins['complynet_location_sid'] = $this->complyLocationId;
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
                            $this->db
                                ->where('sid', $employee['sid'])
                                ->update('users', [
                                    'complynet_onboard' => 1
                                ]);
                        }
                    }
                }
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

    /**
     * Checks the login
     */
    private function checkLogin()
    {
        return (bool) $this->ion_auth->user()->row()->id;
    }
}
