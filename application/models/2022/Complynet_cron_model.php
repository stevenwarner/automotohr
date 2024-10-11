<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Complynet_cron_model
 *
 * @version 1.0
 */
class Complynet_cron_model extends CI_Model
{
    /**
     * @var array
     */
    private $companies;

    /**
     * @var array
     */
    private $company;

    /**
     * Constructor
     */
    public function __construct()
    {
        //
        parent::__construct();
        //
        $this
            ->load
            ->library(
                'Complynet/Complynet_lib',
                '',
                'complynet_lib'
            );
    }

    /**
     * sync companies to ComplyNet
     */
    public function syncStoreToComplyNet()
    {
        // die("This event is topped by the Admin, please do not remove this line.");
        // get all companies on ComplyNet
        $this->setCompanies();
        // check there were no data
        if (!$this->companies) {
            exit("No companies found.");
        }
        // iterate
        foreach ($this->companies as $v0) {
            // set the company
            $this->company = $v0;
            $this->syncDepartments();
            // $this->updateEmployees();
        }
    }

    /**
     * sync company to ComplyNet
     */
    public function synCompanyDepartments(int $companyId)
    {
        // get all companies on ComplyNet
        $this->company =
            $this
            ->db
            ->select([
                "company_sid",
                "complynet_company_sid",
                "complynet_location_sid"
            ])
            ->where(
                "company_sid",
                $companyId
            )
            ->limit(1)
            ->get("complynet_companies")
            ->row_array();
        // check there were no data
        if (!$this->company) {
            return "No company found.";
        }
        //
        $this->syncDepartments();
    }

    /**
     * sync company to ComplyNet
     */
    public function synCompanyJobRole(
        string $complynetDepartmentId,
        string $jobTitle
    ) {
        $jobTitle = trim($jobTitle);
        //
        $this->setCompanyFromDepartment(
            $complynetDepartmentId
        );
        // check there were no data
        if (!$this->company) {
            return 0;
        }
        // sync the job roles as well
        $this->syncDepartmentJobRoles(
            $complynetDepartmentId
        );
        // get the job role
        //
        $record = $this
            ->db
            ->select("complynet_job_role_sid")
            ->where([
                "complynet_department_sid" => $complynetDepartmentId,
                "trim(lower(REGEXP_REPLACE(job_title, '[^a-zA-Z]', ''))) =" => $this->stringToSlug($jobTitle),
            ])
            ->limit(1)
            ->get("complynet_jobRole")
            ->row_array();
        //
        if (!$record) {
            $complyNetJobRoleId = $this
                ->complynet_lib
                ->addJobRole([
                    'ParentId' => $complynetDepartmentId,
                    'Name' => $jobTitle
                ]);
            // set insert array
            $ins = [];
            $ins["complynet_department_sid"] = $complynetDepartmentId;
            $ins["complynet_job_role_sid"] = $complyNetJobRoleId;
            $ins["complynet_job_role_name"] = $jobTitle;
            $ins["job_title"] = $jobTitle;
            $ins["status"] = 1;
            $ins["updated_at"] = getSystemDate();
            $this->checkAndAddDepartmentJobRole($ins);
        } else {
            $complyNetJobRoleId = $record["complynet_job_role_sid"];
        }
        // 
        if ($complyNetJobRoleId == "0") {
            return 0;
        }
        return $complyNetJobRoleId;
    }


    private function setCompanyFromDepartment(
        string $complynetDepartmentId
    ) {
        $record = $this
            ->db
            ->select([
                "company_sid",
            ])
            ->where(
                "complynet_department_sid",
                $complynetDepartmentId
            )
            ->limit(1)
            ->get("complynet_departments")
            ->row_array();
        // get company id
        //
        if (!$record) {
            return 0;
        }
        //get all companies on ComplyNet
        $this->company =
            $this
            ->db
            ->select([
                "company_sid",
                "complynet_company_sid",
                "complynet_location_sid"
            ])
            ->where(
                "company_sid",
                $record["company_sid"]
            )
            ->limit(1)
            ->get("complynet_companies")
            ->row_array();
    }

    /*
     | ----------------------------------------------------- 
     | PRIVATE
     | ----------------------------------------------------- 
     */
    /**
     * set companies
     */
    private function setCompanies()
    {
        $this->companies =
            $this
            ->db
            ->select([
                "company_sid",
                "complynet_company_sid",
                "complynet_location_sid"
            ])
            ->get("complynet_companies")
            ->result_array();
    }

    /**
     * Sync departments
     */
    private function syncDepartments()
    {
        // delete records
        $this->deleteLinkedDepartments();
        // fetch linked departments from ComplyNet
        $complyNetDepartments = $this->apiToStoreDepartments();
        // get store departments
        $storeDepartments = $this->getStoreDepartments();
        // link store to ComplyNet
        foreach ($storeDepartments as $k0 => $v0) {
            // set insert array
            $ins = [];
            $ins["company_sid"] = $this->company["company_sid"];
            $ins["department_sid"] = $v0["sid"];
            $ins["department_name"] = $v0["name"];
            $ins["status"] = 1;
            $ins["updated_at"] = getSystemDate();
            // check if department exists
            if (isset($complyNetDepartments[$k0])) {
                $ins["complynet_department_sid"] =
                    $complyNetDepartments[$k0]["Id"];
                $ins["complynet_department_name"] =
                    $complyNetDepartments[$k0]["Name"];
            } else {
                $response = $this
                    ->complynet_lib
                    ->addDepartmentToComplyNet([
                        'ParentId' => $this->company["complynet_location_sid"],
                        'Name' => $v0['name']
                    ]);
                //
                if (!isset($response["Id"])) {
                    continue;
                }
                $ins['complynet_department_sid'] = $response['Id'];
                $ins['complynet_department_name'] = $response['Name'];
            }
            // check if already exists
            $this->checkAndAdd($ins);
            // sync the job roles as well
            $this->syncDepartmentJobRoles(
                $ins["complynet_department_sid"]
            );
        }
    }

    /**
     * delete departments
     */
    private function deleteLinkedDepartments()
    {
        $this
            ->db
            ->where(
                "company_sid",
                $this->company["company_sid"]
            )
            ->delete("complynet_departments");
    }

    /**
     * get ComplyNet linked departments
     */
    private function apiToStoreDepartments()
    {
        $records = $this
            ->complynet_lib
            ->getComplyNetDepartments(
                $this->company["complynet_location_sid"]
            );
        //
        if (!$records) {
            return [];
        }
        // set default
        $tmp = [];
        // iterate
        foreach ($records as $v0) {
            // convert name to slug
            $tmp[$this->stringToSlug(trim($v0["Name"]))] = $v0;
        }
        //
        return $tmp;
    }

    /**
     * get departments
     */
    private function getStoreDepartments()
    {
        // get the departments
        $records =
            $this
            ->db
            ->select([
                "departments_management.sid",
                "departments_management.name",
            ])
            ->where([
                "company_sid" => $this->company["company_sid"],
                "is_deleted" => 0
            ])
            ->get("departments_management")
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        // set default
        $tmp = [];
        // iterate
        foreach ($records as $v0) {
            // convert name to slug
            $tmp[$this->stringToSlug(
                trim($v0["name"])
            )] = $v0;
        }
        //
        return $tmp;
    }

    /**
     * check and add departments link
     *
     * @param array $data
     */
    private function checkAndAdd(array $data)
    {
        if (
            !$this
                ->db
                ->where([
                    "company_sid" => $data["company_sid"],
                    "department_sid" => $data["department_sid"],
                    "complynet_department_sid" => $data["complynet_department_sid"],
                ])
                ->count_all_results("complynet_departments")
        ) {
            $data["created_at"] = $data["updated_at"];
            $this
                ->db
                ->insert(
                    "complynet_departments",
                    $data
                );
        }
    }

    /**
     * Sync department job roles
     */
    private function syncDepartmentJobRoles(
        string $complynetDepartmentId
    ) {
        // delete records
        $this->deleteLinkedJobTitles(
            $complynetDepartmentId
        );
        // get store departments
        $complynetDepartmentJobRoles = $this
            ->apiToStoreDepartmentJobRoles(
                $complynetDepartmentId
            );
        // when no job roles found
        if (!$complynetDepartmentJobRoles) {
            return false;
        }
        // iterate
        foreach ($complynetDepartmentJobRoles as $v0) {
            // set insert array
            $ins = [];
            $ins["complynet_department_sid"] = $complynetDepartmentId;
            $ins["complynet_job_role_sid"] = $v0["Id"];
            $ins["complynet_job_role_name"] = $v0["Name"];
            $ins["job_title"] = $v0["Name"];
            $ins["status"] = 1;
            $ins["updated_at"] = getSystemDate();
            // check if already exists
            $this->checkAndAddDepartmentJobRole($ins);
        }
    }

    /**
     * delete department job roles
     *
     * @param string $complynetDepartmentId
     */
    private function deleteLinkedJobTitles(
        string $complynetDepartmentId
    ) {
        $this
            ->db
            ->where(
                "complynet_department_sid",
                $complynetDepartmentId
            )
            ->delete("complynet_jobRole");
    }

    /**
     * get ComplyNet linked departments
     */
    private function apiToStoreDepartmentJobRoles(
        string $complynetDepartmentId
    ) {
        $records = $this
            ->complynet_lib
            ->getJobRolesByDepartmentId(
                $complynetDepartmentId
            );
        //
        if (!$records) {
            return [];
        }
        // set default
        $tmp = [];
        // iterate
        foreach ($records as $v0) {
            // convert name to slug
            $tmp[$this->stringToSlug(trim($v0["Name"]))] = $v0;
        }
        //
        return $tmp;
    }

    /**
     * check and add department job role
     *
     * @param array $data
     */
    private function checkAndAddDepartmentJobRole(array $data)
    {
        if (
            !$this
                ->db
                ->where([
                    "complynet_department_sid" => $data["complynet_department_sid"],
                    "complynet_job_role_sid" => $data["complynet_job_role_sid"],
                ])
                ->count_all_results("complynet_jobRole")
        ) {
            $data["created_at"] = $data["updated_at"];
            $this
                ->db
                ->insert(
                    "complynet_jobRole",
                    $data
                );
        }
    }

    /**
     * fix employees data
     */
    private function updateEmployees()
    {
        // get company employees on ComplyNet
        $records = $this->getEmployeesOnComplyNet();
        //
        if (!$records) {
            return false;
        }
    }


    /**
     * get company employees
     */
    private function getEmployeesOnComplyNet(): array
    {
        $records =  $this
            ->db
            ->select([
                "users.sid",
                "users.first_name",
                "users.last_name",
                "users.email",
                "users.PhoneNumber",
                "complynet_employees.sid as rowId",
                "complynet_employees.complynet_employee_sid",
                "complynet_employees.alt_id",
                "complynet_employees.complynet_department_sid",
                "complynet_employees.complynet_job_role_sid",
                "complynet_employees.complynet_json",
            ])
            ->where([
                "users.active" => 1,
                "users.pending_complynet_update" => 0,
                "users.parent_sid" => $this->company["company_sid"],
            ])
            ->join(
                "complynet_employees",
                "
                complynet_employees.employee_sid
                = users.sid
                ",
                "inner"
            )
            ->get("users")
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        // iterate
        foreach ($records as $k0 => $v0) {
            //
            $extraData = json_decode($v0["complynet_json"], true);
            //
            if ($extraData[0]) {
                $extraData = $extraData[0];
            }
            // get employee department
            $department = $this
                ->getEmployeeDepartmentId($v0["sid"]);
            //
            if ($department) {
                //  get the new department
                $v0["complynet_department_sid"] =
                    $records[$k0]["complynet_department_sid"] = $department["complyNetDepartmentId"];
            }
            // get job role
            $jobRole = $this
                ->getEmployeeJobRole(
                    $v0["complynet_department_sid"],
                    $v0["sid"]
                );
            //
            if (!$jobRole) {
                continue;
            }
            $v0["complynet_job_role_sid"] = $jobRole;
            // make update array
            $updateArray = [];
            $updateArray['firstName'] = $v0["first_name"];
            $updateArray['lastName'] = $v0["last_name"];
            $updateArray['userName'] = $extraData["UserName"];
            $updateArray['email'] = $extraData["Email"];
            $updateArray['password'] = 'password';
            $updateArray['companyId'] = $this->company['complynet_company_sid'];
            $updateArray['locationId'] = $this->company['complynet_location_sid'];
            $updateArray['departmentId'] = $v0["complynet_department_sid"];
            $updateArray['jobRoleId'] = $v0['complynet_job_role_sid'];
            $updateArray['PhoneNumber'] = $v0['PhoneNumber'];
            $updateArray['TwoFactor'] = "FALSE";
            $updateArray['AltId'] = $extraData["AltId"] ? $extraData["AltId"] : $v0['alt_id'];
            //
            $response = $this
                ->complynet_lib
                ->updateUser($updateArray);
            //
            if (preg_match(
                '/^(\(\d+\))\s+Update/',
                $response
            )) {
                $this
                    ->db
                    ->where("sid", $v0["rowId"])
                    ->update(
                        "complynet_employees",
                        [
                            "complynet_department_sid" => $v0["complynet_department_sid"],
                            "complynet_job_role_sid" => $v0['complynet_job_role_sid'],
                            "updated_at" => getSystemDate()
                        ]
                    );
            }
        }
        return $records;
    }

    /**
     * get departments
     */
    private function getEmployeeDepartmentId(
        int $employeeId
    ) {
        // get the department employees
        $record =
            $this
            ->db
            ->select([
                "departments_management.sid",
            ])
            ->join(
                "departments_team_management",
                "
                departments_team_management.sid
                = departments_employee_2_team.team_sid
                ",
                "inner"
            )
            ->join(
                "departments_management",
                "
                departments_management.sid
                = departments_team_management.department_sid
                ",
                "inner"
            )
            ->where([
                "departments_employee_2_team.employee_sid" => $employeeId,
                "departments_team_management.is_deleted" => 0,
                "departments_management.is_deleted" => 0,
            ])
            ->order_by("sid", "DESC")
            ->limit(1)
            ->get("departments_employee_2_team")
            ->row_array();
        //
        if (!$record) {
            return [];
        }
        // get the relevant department id
        $record2 = $this
            ->db
            ->select("complynet_department_sid")
            ->where([
                "department_sid" =>
                $record["sid"],
                "company_sid" => $this->company["company_sid"]
            ])
            ->limit(1)
            ->get("complynet_departments")
            ->row_array();
        //
        if (!$record2) {
            return [];
        }
        return [
            "departmentId" => $record["sid"],
            "complyNetDepartmentId"
            => $record2["complynet_department_sid"],
        ];
    }

    /**
     * get departmentsn
     */
    private function getEmployeeJobRole(
        string $departmentId,
        int $employeeId
    ) {
        // get employee job title
        $record = $this
            ->db
            ->select("complynet_job_title")
            ->where(
                "sid",
                $employeeId
            )
            ->limit(1)
            ->get("users")
            ->row_array();
        //
        if (
            !$record
            || !$record["complynet_job_title"]
        ) {
            return false;
        }
        //
        $record2 = $this
            ->db
            ->select("complynet_job_role_sid")
            ->where([
                "complynet_department_sid" => $departmentId,
                "trim(lower(REGEXP_REPLACE(job_title, '[^a-zA-Z]', ''))) =" => $this->stringToSlug($record["complynet_job_title"]),
            ])
            ->limit(1)
            ->get("complynet_jobRole")
            ->row_array();
        //
        if (!$record2) {
            $complyNetJobRoleId = $this
                ->complynet_lib
                ->addJobRole([
                    'ParentId' => $departmentId,
                    'Name' => $record["complynet_job_title"]
                ]);
            // set insert array
            $ins = [];
            $ins["complynet_department_sid"] = $departmentId;
            $ins["complynet_job_role_sid"] = $complyNetJobRoleId;
            $ins["complynet_job_role_name"] = $record["complynet_job_title"];
            $ins["job_title"] = $record["complynet_job_title"];
            $ins["status"] = 1;
            $ins["updated_at"] = getSystemDate();
            $this->checkAndAddDepartmentJobRole($ins);
        } else {
            $complyNetJobRoleId = $record2["complynet_job_role_sid"];
        }
        // 
        if ($complyNetJobRoleId == "0") {
            return 0;
        }

        return $complyNetJobRoleId;
    }

    /**
     * get departments
     */
    private function getDepartmentEmployees(
        string $complynetDepartmentId
    ) {
        // get the department employees
        return
            $this
            ->db
            ->select([
                "complynet_employee_sid",
                "complynet_department_sid",
                "complynet_job_role_sid",
                "employee_sid",
                "complynet_json",
            ])
            ->where(
                "complynet_department_sid",
                $complynetDepartmentId
            )
            ->get("complynet_employees")
            ->result_array();
    }

    private function stringToSlug(string $string, string $is = "")
    {
        return trim(
            strtolower(
                preg_replace(
                    "/[^a-z]/i",
                    "",
                    $string
                )
            )
        );
    }


    public function syncEmployeesToComplyNet()
    {
        // get all active and complynet linked employees
        $employees = $this
            ->db
            ->select("employee_sid")
            ->get("complynet_employees")
            ->result_array();
        //
        if (!$employees) {
            exit("No linked employees found.");
        }

        foreach ($employees as $v0) {

            $sid = $v0["employee_sid"];

            $oldData = $this->db
                ->select('first_name, last_name, email, PhoneNumber, parent_sid')
                ->where('sid', $sid)
                ->get('users')
                ->row_array();
            //
            $this->load->model('2022/complynet_model', 'complynet_model');
            // check if employee is ready for transfer
            $this
                ->complynet_model
                ->checkAndStartTransferEmployeeProcess(
                    $sid,
                    $oldData["parent_sid"]
                );
            // ComplyNet interjection
            if (isCompanyOnComplyNet($oldData['parent_sid'])) {
                //
                updateEmployeeJobRoleToComplyNet($sid, $oldData['parent_sid']);
            }
        }
    }
}
