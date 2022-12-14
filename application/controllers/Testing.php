<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
        $this->load->model('2022/Company_model', 'company_model');
        $this->load->library('complynet_lib');
    }

    public function complynet()
    {
        // $companies = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->getCompanies();

        // _e($companies, true, true);

        // $locations = $this
        // ->complynet
        // ->setMode('original')
        // ->authenticate()
        // ->getLocations(923);
        // //
        // _e($locations, true, true);

        // $departments = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->getDepartments("3A0168AE-4B6F-42F4-8828-92A89D1CFD35");
        // //
        // _e($departments, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->updateDepartments(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD",
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD",
        //     "Service Provider",
        //     TRUE
        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->createDepartment(
        //     "Software Service Provider"
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);


        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->deleteDepartment(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);


        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->getJobRole(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->updateJobRole(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD",
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD",
        //     "General Manager",
        //     TRUE
        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->createJobRole(
        //     "General Manager"
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);


        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->deleteJobRole(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);


        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->deleteJobRole(
        //     "1F9F9677-2CE0-43B3-A418-0815334B706BD"
        // );
        // //
        // _e($response, true, true);

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->getUser(
        //     "requests@ComplyNet.com"
        // );
        // //
        // _e($response, true, true);

        // $cUser = array(
        //     "firstName" => "Tom",
        //     "lastName" => "Bob",
        //     "userName" => "tombob@ComplyNet.com",
        //     "email" => "tombob@ComplyNet.com",
        //     "password" => "",
        //     "companyId" => "E4A89DDA-12BB-4341-844A-BBE400451274",
        //     "locationId" => "8AB20AFF-C1AE-4F08-AB1C-160ABD4FEA2F",
        //     "departmentId" => "55A3BBA9-CE0F-4E1C-9587-9E3709CF2F25",
        //     "jobRoleId" => "FE96FEBA-DE91-4DA1-A809-499351D001F7",
        //     "PhoneNumber" => 5556667778,
        //     "TwoFactor" => TRUE,
        // );

        // $response = $this
        // ->complynet
        // ->setMode('fake')
        // ->authenticate()
        // ->createUser($cUser);
        // //
        // _e($response, true, true);

        // $upUser = array(
        //     "firstName" => "Tonny",
        //     "lastName" => "Bomber",
        //     "userName" => "tombob@ComplyNet.com",
        //     "email" => "tombob@ComplyNet.com",
        //     "companyId" => "E4A89DDA-12BB-4341-844A-BBE400451274",
        //     "locationId" => "8AB20AFF-C1AE-4F08-AB1C-160ABD4FEA2F",
        //     "departmentId" => "55A3BBA9-CE0F-4E1C-9587-9E3709CF2F25",
        //     "jobRoleId" => "FE96FEBA-DE91-4DA1-A809-499351D001F7",
        //     "PhoneNumber" => 4555666777,
        //     "TwoFactor" => TRUE,
        // );

        // $response = $this
        // ->complynet
        // ->setMode('fakes')
        // ->authenticate()
        // ->updateUser($upUser);
        // //
        // _e($response, true, true);

        $response = $this
            ->complynet_lib
            ->setMode('fake')
            ->authenticate()
            ->disableUser(
                "tombob@ComplyNet.com"

            );
        //
        _e($response, true, true);
    }



    //
    public function complynetSync()
    {
        $automotocompanySid = $_POST['automotocompany_sid'];
        $complyNetLocationId = $_POST['complyNetLocation_Id'];
        //  $complyNetLocationName = $_POST['complyNetLocation_Name'];


        // $userdata = $this->session->userdata('logged_in');
        //  $employer_sid = $userdata["employer_detail"]["sid"];

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

        if (!empty($automotohrEmployeesData)) {
            foreach ($automotohrEmployeesData as $employeeDataRow) {
                // get User form Complynet
                $complynetEmployeeData = $this->complynet_lib
                    ->setMode('fake')
                    ->authenticate()
                    ->getUser($employeeDataRow['email']);

                if (!empty($complynetEmployeeData)) {

                    //Complyenet employee Binde

                    // Check employee is already Bind or not
                    $complynetEmployeeBindID = $this->checkComplynetEmployeeBind($automotocompanySid, $complyNetLocationId, $complynetEmployeeData['email']);

                    if (empty($complynetEmployeeBindID)) {
                        if ($employeeDataRow['department_sid'] > 0 && $employeeDataRow['job_title'] != '' && $employeeDataRow['job_title'] != null) {
                            //get complynet department id
                            $complynetDepartmentId = $this->getComplynetDepartmentBindId($employeeDataRow['department_sid']);
                            if (!empty($complynetDepartmentId)) {
                                //
                                $complynetJobRoleId = $this->getComplynetJobRoleBindId($automotocompanySid, $employeeDataRow['job_title']);
                                if (!empty($complynetJobRoleId)) {
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


                    print_r($complynetEmployeeData);
                } else {

                     $cUser = array(
                         "firstName" => $employeeDataRow['first_name'],
                         "lastName" => $employeeDataRow['last_name'],
                         "userName" => $employeeDataRow['email'],
                         "email" => $employeeDataRow['email'],
                         "password" => "",
                         "companyId" => $employeeDataRow['parent_sid'],,
                         "locationId" => "8AB20AFF-C1AE-4F08-AB1C-160ABD4FEA2F",
                         "departmentId" => "55A3BBA9-CE0F-4E1C-9587-9E3709CF2F25",
                         "jobRoleId" => "FE96FEBA-DE91-4DA1-A809-499351D001F7",
                         "PhoneNumber" => 5556667778,
                         "TwoFactor" => TRUE,
                     );


                    //Create Employee 
                    $complynetEmployeeData = $this->complynet_lib
                        ->setMode('fake')
                        ->authenticate()
                        ->createUser($cUser);
                }
            }
        }
    }

    //
    function getComplynetLocations()
    {

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
    function getComplynetEmployees()
    {
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
}
