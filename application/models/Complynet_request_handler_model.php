<?php

class Complynet_request_handler_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    private $complynetCompanyId = 'E4A89DDA-12BB-4341-844A-BBE400451274';
    private $complynetLocationId = '8AB20AFF-C1AE-4F08-AB1C-160ABD4FEA2F';
    private $complynetDepartmentId = '55A3BBA9-CE0F-4E1C-9587-9E3709CF2F25';
    private $complynetjobRoleId = 'FE96FEBA-DE91-4DA1-A809-499351D001F7';

    // Add
    // Edit
    // Disable
    // Enable

    function complynet_user_request($employee_sid, $action = null)
    {

        // Get userdate
        $userData = $this->getEmployeedata($employee_sid, $action);

        //Get job titles against companysid

        $jobTitles= $this->getEmployeesjobtitles($userData['parent_sid']);
       // print_r($jobTitles);
      //  die();

        //  Check company complynet status
        if ($userData['complynet_status'] == 0) {
            return 'error';
        }

        //  Check complynet_sid
        $complynetCompanyIddata = $this->getcomplynetCompanyId($userData['parent_sid']);
        if (empty($complynetCompanyIddata)) {
            return 'error';
        }
        $this->complynetCompanyId = $complynetCompanyIddata['complynet_sid'];

        //
        $this->$action($userData);
    }


    // 
    function addEmployee($userData)
    {

        $createUser = array(
            "firstName" => $userData['first_name'],
            "lastName" => $userData['last_name'],
            "userName" => $userData['email'],
            "email" => $userData['email'],
            "password" => "",
            "companyId" => $this->complynetcompanyId,
            "locationId" => $this->complynetLocationId,
            "departmentId" => $this->complynetDepartmentId,
            "jobRoleId" => $this->complynetjobRoleId,
            "PhoneNumber" => $userData['cell_number'],
            "TwoFactor" => TRUE,
        );

        if ($userData['first_name'] != '' && $userData['last_name'] != '' &&  $userData['email'] != ''  && $this->complynetLocationId &&  $this->complynetDepartmentId != '' && $this->complynetjobRoleId != '') {
            $response = $this
                ->complynet
                ->setMode(COMPLYNET_MODE)
                ->authenticate()
                ->createUser($createUser);
            //_e($response, true, true);
            return $response;
        }
    }

    //
    function updateEmployee($userData)
    {

        $updateUser = array(
            "firstName" => $userData['first_name'],
            "lastName" => $userData['last_name'],
            "userName" => $userData['email'],
            "email" => $userData['email'],
            "password" => "",
            "companyId" => $this->complynetcompanyId,
            "locationId" => $this->complynetLocationId,
            "departmentId" => $this->complynetDepartmentId,
            "jobRoleId" => $this->complynetjobRoleId,
            "PhoneNumber" => $userData['cell_number'],
            "TwoFactor" => TRUE,
        );
        if ($userData['first_name'] != '' && $userData['last_name'] != '' &&  $userData['email'] != ''  && $this->complynetLocationId &&  $this->complynetDepartmentId != '' && $this->complynetjobRoleId != '') {

            $response = $this
                ->complynet
                ->setMode(COMPLYNET_MODE)
                ->authenticate()
                ->updateUser($updateUser);
            return $response;
        }
    }

    //
    function disableEmployee($userData)
    {
        $response = $this
            ->complynet
            ->setMode(COMPLYNET_MODE)
            ->authenticate()
            ->disableUser(
                $userData['email']
            );
        return $response;
    }

    //
    function enableEmployee($userData)
    {
    }

    //
    function getcomplynetCompanyId($parentSid)
    {
        $this->db->select('complynet_sid');
        $this->db->where('automotohr_sid', $parentSid);
        return $this->db->get('complynet_companies')->row_array();
    }

    //
    function getEmployeedata($employee_sid, $action)
    {
        if ($action == 'addEmployee' || $action == 'updateEmployee') {
            $this->db->select('first_name,last_name,email,cell_number,complynet_status,parent_sid');
        }

        if ($action == 'disableEmployee' || $action == 'enableEmployee') {
            $this->db->select('email,complynet_status,parent_sid');
        }

        $this->db->where('sid', $employee_sid);
        return $this->db->get('users')->row_array();
    }

    function getEmployeesjobtitles($companyid)
    {

        $this->db->select('job_title');
        $this->db->where('parent_sid', $companyid);
        return $this->db->get('users')->result_array();
    }
}
