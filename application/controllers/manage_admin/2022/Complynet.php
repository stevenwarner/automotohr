<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Complynet extends Admin_Controller {

    /**
     * Main entry point
     */
    function __construct() {
        parent::__construct();
        $this->load->model('2022/Company_model', 'company_model');
        $this->load->library('complynet_lib');
    }

    /**
     * 
     */
    public function manage() {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if(!$admin_id){
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // set page title
        $this->data['page_title'] = 'ComplyNet';
        // get all companies
        $this->data['companies'] = $this->company_model->getAllCompanies(
            ['sid', 'CompanyName']
        );

        $this->render('complynet/admin/manage');
    }



    public function manage_new() {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if(!$admin_id){
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // set page title
        $this->data['page_title'] = 'ComplyNet';
        // get all companies
        $this->data['companies'] = $this->company_model->getAllCompanies(
            ['sid', 'CompanyName']
        );

           //
           $locationsData = $this->complynet_lib
           ->setMode('fake')
           ->authenticate()
           ->getLocationsNew();


           $this->data['locations'] = $locationsData;
        $this->render('complynet/admin/manage_new');
    }



//




    //
    public function complynetSync()
    {
        $automotocompanySid = $_POST['automotocompany_sid'];
        $automotocompanyName = $_POST['automotocompany_Name'];
        $complyNetLocationId = $_POST['complyNetLocation_Id'];
        $complyNetLocationName = $_POST['complyNetLocation_Name'];



        //  Bind Companies
        $locationBindID = $this->checkComplynetLocationsBind($automotocompanySid, $complyNetLocationId);
        if (empty($locationBindID)) {
            $locationsDataBind['company_id'] = $automotocompanySid;
            $locationsDataBind['complynet_location_id'] = $complyNetLocationId;
            $locationsDataBind['automotohr_location_name'] = trim($automotocompanyName);
            $locationsDataBind['complynet_location_name'] = trim($complyNetLocationName);
            $locationsDataBind['status'] = '1';
            $locationsDataBind['created_at'] = date('Y-m-d H:i:s');

            $this->bindeComplynetLocations($locationsDataBind);
        }

        //Get Departments
        $departmentsData = $this->getComplynetDepartments($complyNetLocationId);
        //
        if (!empty($departmentsData)) {
            foreach ($departmentsData as $key => $depatmentRow) {
                //check department
                $department = $this->checkDepartment($automotocompanySid, $depatmentRow['Name']);
                if (empty($department)) {
                    $departmentData['name'] = $depatmentRow['Name'];
                    $departmentData['description'] = '';
                    $departmentData['supervisor'] = '';
                    $departmentData['status'] = '1';
                    $departmentData['company_sid'] = $automotocompanySid;
                    $departmentData['created_by_sid'] = '1122';
                    $departmentData['created_date'] = date('Y-m-d H:i:s');
                    $departmentData['is_deleted'] = '0';
                    //insert
                    $departmentManagemantSid = $this->insertDepartment($departmentData);
                } else {
                    $departmentManagemantSid = $department['sid'];
                }

                $bindSid = $this->checkDepartmentBind($departmentManagemantSid, $depatmentRow['Id']);
                //bind department
                if (empty($bindSid)) {
                    $departmentDataBind['company_id'] = $automotocompanySid;
                    $departmentDataBind['complynet_location_id'] = $complyNetLocationId;
                    $departmentDataBind['complynet_department_id'] = $depatmentRow['Id'];
                    $departmentDataBind['automotohr_department_id'] = $departmentManagemantSid;
                    $departmentDataBind['complynet_department_name'] = $depatmentRow['Name'];
                    $departmentDataBind['automotohr_department_name'] = $depatmentRow['Name'];
                    $departmentDataBind['status'] = 1;
                    $departmentDataBind['created_at'] = date('Y-m-d H:i:s');
                    $departmentBindId = $this->bindeComplynetDepartment($departmentDataBind);
                }

                //Get Jobrolls
                $jobRolls = $this->getComplynetJobrolls($depatmentRow['Id']);

                if (!empty($jobRolls)) {
                    foreach ($jobRolls as $key => $jonRow) {
                        // $jonRow['Name']
                        //check Job Role
                        $jonRow['Name']='testing';
                        $roleSid = $this->checkJobRole($automotocompanySid, $jonRow['Name']);
                        if (!empty($roleSid)) {
                            //Bind Job Role
                            $JobRoleBind = $this->checkJobRoleBind($depatmentRow['Id'], $jonRow['Id']);
                            if (empty($JobRoleBind)) {
                                $jobRoleDataBind['company_id'] = $automotocompanySid;
                                $jobRoleDataBind['complynet_department_id'] = $depatmentRow['Id'];
                                $jobRoleDataBind['complynet_jobRole_id'] = $jonRow['Id'];
                                $jobRoleDataBind['complynet_jobRole_name'] = $jonRow['Name'];
                                $jobRoleDataBind['automotohr_jobRole_name'] = $jonRow['Name'];
                                $jobRoleDataBind['status'] = 1;
                                $jobRoleDataBind['created_at'] = date('Y-m-d H:i:s');
                                $jobRoleBindid = $this->bindeComplynetJobRole($jobRoleDataBind);
                            }
                        }
                    }
                }
            }
        }


        //Get Employees On Automotohr
        $automotohrEmployeesData = $this->getautomotoHREmployees($automotocompanySid);

        if (!empty($automotohrEmployeesData) && !empty($departmentsData)) {
            foreach ($automotohrEmployeesData as $employeeDataRow) {
                // get User form Complynet
                $complynetEmployeeData = $this->complynet_lib
                    ->setMode('fake')
                    ->authenticate()
                    ->getUser($employeeDataRow['email']);
                //  $complynetEmployeeData = '';

                if (!empty($complynetEmployeeData)) {
                    //Complyenet employee Binde
                    // Check employee is already Bind or not
                    $complynetEmployeeBindID = $this->checkComplynetEmployeeBind($automotocompanySid, $complyNetLocationId, $complynetEmployeeData['email']);

                    if (empty($complynetEmployeeBindID)) {
                        // if ($employeeDataRow['department_sid'] > 0 && $employeeDataRow['job_title'] != '' && $employeeDataRow['job_title'] != null) {
                        //get complynet department id

                        //    $complynetDepartmentId = $this->getComplynetDepartmentBindId($employeeDataRow['department_sid']);
                        //  if (!empty($complynetDepartmentId)) {
                        //
                        $complynetJobRoleId = $this->getComplynetJobRoleBindId($automotocompanySid, $employeeDataRow['job_title']);
                        if (!empty($complynetJobRoleId)) {
                            $employeeDataBind['company_id'] = $automotocompanySid;
                            $employeeDataBind['complynet_company_id'] = $complyNetLocationId;
                            $employeeDataBind['complynet_department_id'] = $complynetEmployeeData['departmentId'];
                            $employeeDataBind['complynet_jobRole_id'] = $complynetEmployeeData['jobRoleId'];
                            $employeeDataBind['automotohr_employee_id'] = $employeeDataRow['sid'];
                            $employeeDataBind['firstName'] = $complynetEmployeeData['firstName'];
                            $employeeDataBind['lastName'] = $complynetEmployeeData['lastName'];
                            $employeeDataBind['userName'] = $complynetEmployeeData['email'];
                            $employeeDataBind['email'] = $complynetEmployeeData['email'];
                            $employeeDataBind['PhoneNumber'] = $complynetEmployeeData['PhoneNumber'];
                            $employeeDataBind['status'] = 1;
                            $employeeDataBind['created_at'] = date('Y-m-d H:i:s');

                            $this->bindeComplynetEmployee($employeeDataBind);
                            //  }
                            //}
                        }
                    }

                    //  print_r($complynetEmployeeData);
                } else {

                    //  $employeeDataRow['job_title'] = 'Employee';
                    $complynetDepartmentId = $this->getComplynetDepartmentBindId($employeeDataRow['department_sid']);
                    $complynetJobRoleId = $this->getComplynetJobRoleBindId($automotocompanySid, $employeeDataRow['job_title']);
                    if (!empty($complynetDepartmentId) && !empty($complynetJobRoleId)) {
                        $cUser = array(
                            "firstName" => $employeeDataRow['first_name'],
                            "lastName" => $employeeDataRow['last_name'],
                            "userName" => $employeeDataRow['email'],
                            "email" => $employeeDataRow['email'],
                            "password" => "",
                            "companyId" => '', //$employeeDataRow['parent_sid'],
                            "locationId" => $complyNetLocationId,
                            "departmentId" => $complynetDepartmentId['complynet_department_id'],
                            "jobRoleId" => $complynetJobRoleId['complynet_jobRole_id'],
                            "PhoneNumber" => $employeeDataRow['PhoneNumber'],
                            "TwoFactor" => TRUE,
                        );
                        //Create Employee 
                        $complynetEmployeeData = $this->complynet_lib
                            ->setMode('fake')
                            ->authenticate()
                            ->createUser($cUser);

                        // Bind New Complynet User
                        $employeeDataBind['company_id'] = $automotocompanySid;
                        $employeeDataBind['complynet_company_id'] = $complyNetLocationId;
                        $employeeDataBind['complynet_department_id'] = $complynetDepartmentId['complynet_department_id'];
                        $employeeDataBind['complynet_jobRole_id'] = $complynetJobRoleId['complynet_jobRole_id'];
                        $employeeDataBind['automotohr_employee_id'] = $employeeDataRow['sid'];
                        $employeeDataBind['firstName'] = $employeeDataRow['first_name'];
                        $employeeDataBind['lastName'] = $employeeDataRow['last_name'];
                        $employeeDataBind['userName'] = $employeeDataRow['email'];
                        $employeeDataBind['email'] = $employeeDataRow['email'];
                        $employeeDataBind['PhoneNumber'] = $employeeDataRow['PhoneNumber'];
                        $employeeDataBind['status'] = 1;
                        $employeeDataBind['created_at'] = date('Y-m-d H:i:s');

                        $this->bindeComplynetEmployee($employeeDataBind);
                    }
                }
            }
        }
    }

    //
    function getComplynetLocations()
    {

        $allCompanies = $this->hr_documents_management_model->get_all_companies();
        $data['allcompanies'] = $allCompanies;

        //
        $locationsData = $this->complynet_lib
            ->setMode('fake')
            ->authenticate()
            ->getLocationsNew();


        $data['locations'] = $locationsData;
        $this->load->view('main/header', $data);
        $this->load->view('complynet/testing');
        $this->load->view('main/footer');
    }
    //
    function getComplynetDepartments($locationId)
    {
        $locationsData = $this->complynet_lib
            ->setMode('fake')
            ->authenticate()
            ->getDepartments($locationId);
        return $locationsData;
    }

    //
    function getComplynetJobrolls($depatmentId)
    {
        $jobRolesData = $this->complynet_lib
            ->setMode('fake')
            ->authenticate()
            ->getJobRole($depatmentId);
        return $jobRolesData;
    }

    // 

    function checkDepartment($companySid, $departmentName)
    {
        return $this->db
            ->select('sid')
            ->where('company_sid', $companySid)
            ->like('REPLACE(TRIM(LOWER(name)), " ", "") ', str_replace(" ", "", trim(strtolower($departmentName))))
            ->get('departments_management')
            ->row_array();
    }

    //
    function insertDepartment($data)
    {
        $this->db->insert("departments_management", $data);
        return $this->db->insert_id();
    }
    //
    function bindeComplynetDepartment($data)
    {
        $this->db->insert("complynet_departments", $data);
        return $this->db->insert_id();
    }

    //
    function checkDepartmentBind($automotohrDepartmentId, $complynetDepartmentId)
    {
        return $this->db
            ->select('sid')
            ->where('automotohr_department_id', $automotohrDepartmentId)
            ->where('complynet_department_id', $complynetDepartmentId)
            ->get('complynet_departments')
            ->row_array();
    }

    //
    function checkJobRole($companySid, $jobTitle)
    {
        return $this->db
            ->select('sid')
            ->where('parent_sid', $companySid)
            ->like('REPLACE(TRIM(LOWER(job_title)), " ", "") ', str_replace(" ", "", trim(strtolower($jobTitle))))
            ->get('users')
            ->row_array();
    }

    function bindeComplynetJobRole($data)
    {
        $this->db->insert("complynet_jobRole", $data);
        return $this->db->insert_id();
    }
    //
    function checkJobRoleBind($complynetDepartmentId, $complynetJobRoleId)
    {
        return $this->db
            ->select('sid')
            ->where('complynet_department_id', $complynetDepartmentId)
            ->where('complynet_jobRole_id', $complynetJobRoleId)
            ->get('complynet_jobRole')
            ->row_array();
    }

    // 
    function getautomotoHREmployees($companySid)
    {

        return $this->db
            ->select('sid,email,first_name,last_name,PhoneNumber,department_sid,job_title,')
            ->where('parent_sid', $companySid)
            ->get('users')
            ->result_array();
    }

    //
    function checkComplynetEmployeeBind($automotohrCompanyId, $complynetLocationId, $employeeEmail)
    {
        return $this->db
            ->select('sid')
            ->where('company_id', $automotohrCompanyId)
            ->where('complynet_company_id', $complynetLocationId)
            ->where('email', $employeeEmail)
            ->get('complynet_employees')
            ->row_array();
    }

    function bindeComplynetEmployee($data)
    {
        $this->db->insert("complynet_employees", $data);
        return $this->db->insert_id();
    }

    //
    function getComplynetDepartmentBindId($automotohrDepartmentId)
    {
        return $this->db
            ->select('complynet_department_id')
            ->where('automotohr_department_id', $automotohrDepartmentId)
            ->get('complynet_departments')
            ->row_array();
    }

    //
    function getComplynetJobRoleBindId($automotohrCompanyId, $jobTitle)
    {
        return $this->db
            ->select('complynet_jobRole_id')
            ->where('company_id', $automotohrCompanyId)
            ->like('REPLACE(TRIM(LOWER(complynet_jobRole_name)), " ", "") ', str_replace(" ", "", trim(strtolower($jobTitle))))
            ->get('complynet_jobRole')
            ->row_array();
    }

    //
    function bindeComplynetLocations($data)
    {
        $this->db->insert("complynet_locations", $data);
        return $this->db->insert_id();
    }
    //
    function checkComplynetLocationsBind($automotohrCompanyId, $complynetLocationId)
    {
        return $this->db
            ->select('sid')
            ->where('company_id', $automotohrCompanyId)
            ->where('complynet_location_id', $complynetLocationId)
            ->get('complynet_locations')
            ->row_array();
    }


}