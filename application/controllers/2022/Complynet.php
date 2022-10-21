<?php

use phpDocumentor\Reflection\Types\Integer;

 defined('BASEPATH') || exit('No direct script access allowed');

/**
 * ComplyNet
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @version 1.0
 */

class Complynet extends CI_Controller {

    /**
     * Entry point
     * 
     * Codes
     * 
     * NR Not Register
     * AR Already Register
     * RS Registeration Successfully
     * RF Registeration Fail
     * DC Delete Company
     * SF Fetch Data Successfully
     * LNF Location Not Found
     * LF Location Found
     * DL Delete Locati0n
     * DNF Department Not Found
     * DF Department Found
     * US Update Successfully
     * DD Delete Department
     * JRNF Job Role Not Found
     * JRF Job Role Found
     * DJR Delete Job Role
     * ENF Employees Not Found
     * EF Employees Found
     * CNF Company not Found
     * 
     */
    public function __construct()
    {
        // inherit parent properties and methods
        parent::__construct();
        // load the company model
        $this->load->model('2022/Company_model', 'company_model');
        $this->load->library('complynet_lib');
    }

    /**
     * Get the comply company details
     *
     * @param int $companyId
     */
    public function getCompanyDetails(int $companyId)
    {
        // check if company is registered with ComplyNet
        $doExists = $this->company_model->checkOrGetData(
            'complynet_companies',
            ['automotohr_id' => $companyId],
            ['sid'],
            'count_all_results'
        );
        // in case company is not on ComplyNet
        if(!$doExists){
            $this->load->library('complynet_lib');
            //
            $complynetCompanies = $this
            ->complynet_lib
            ->setMode('fake')
            ->authenticate()
            ->getCompanies();
            //
            return SendResponse(200, [
                'code' => 'NR',
                'message' => 'Company is not registered with ComplyNet.',
                'complynetCompanies' => (!empty($complynetCompanies) ? $complynetCompanies : [])
            ]);
        }
        // TODO when company is registered
        $companyDetail = $this->company_model->checkOrGetData(
            'complynet_companies',
            ['automotohr_id' => $companyId],
            ['sid', 'automotohr_id', 'automotohr_name', 'complynet_id', 'complynet_name', 'status', 'created_at'],
            'row_array'
        );
        //
        return SendResponse(200, [
            'code' => 'AR',
            'compantDetail' => $companyDetail
        ]);
    }

    /**
     * Link AHR company with complynet and save into DB
     *
     * 
     */
    public function linkCompany()
    {   
        //
        $AHRCompanySid = $this->input->post('AHRCompanySid');
        $AHRCompanyName = $this->input->post('AHRCompanyName');
        //
        $CNCompanySid = $this->input->post('CNCompanySid');
        $CNCompanyName = $this->input->post('CNCompanyName');
        // check if company is registered with ComplyNet
        $doCNCompanyExists = $this->company_model->checkOrGetData(
            'complynet_companies',
            ['complynet_id' => $CNCompanySid],
            ['sid', 'automotohr_name'],
            'row_array'
        );
        // in case company is not on ComplyNet
        if($doCNCompanyExists){
            return SendResponse(200, [
                'code' => 'AR',
                'message' => 'Selected ComplyNet Company is already Linked with <b>'.$doCNCompanyExists["automotohr_name"].'</b>.'
            ]);
        } 
        //
        // check if company 
        $doAHRCompanyExists = $this->company_model->checkOrGetData(
            'complynet_companies',
            ['automotohr_id' => $AHRCompanySid],
            ['sid'],
            'row_array'
        );
        //
        $insertId = 0;
        //
        if($doAHRCompanyExists) {
            $insertId = $doAHRCompanyExists["sid"];
            //
            $data_to_update = array();
            $data_to_update["automotohr_id"] = $AHRCompanySid;
            $data_to_update["complynet_id"] = $CNCompanySid;
            $data_to_update["automotohr_name"] = $AHRCompanyName;
            $data_to_update["complynet_name"] = $CNCompanyName;
            $data_to_update["status"] = 1;
            $data_to_update["updated_at"] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $this->company_model->updateData(
                "complynet_companies", 
                ['sid' => $insertId],
                $data_to_update
            );
        } else {
            $data_to_insert = array();
            $data_to_insert["automotohr_id"] = $AHRCompanySid;
            $data_to_insert["complynet_id"] = $CNCompanySid;
            $data_to_insert["automotohr_name"] = $AHRCompanyName;
            $data_to_insert["complynet_name"] = $CNCompanyName;
            $data_to_insert["status"] = 1;
            $data_to_insert["created_at"] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $insertId = $this->company_model->addData("complynet_companies", $data_to_insert);
        }
       
        //
        if ($insertId > 0) {
            return SendResponse(200, [
                'code' => 'RS',
                'message' => 'Successfully, <b>'.$AHRCompanyName.'</b> is linked with <b>'.$CNCompanyName.'</b>.'
            ]);
        }
        //
        return SendResponse(200, [
            'code' => 'RF',
            'message' => 'Something went wrong while linking.'
        ]);
    }

    /**
     * Delete company Link
     *
     * 
     */
    public function deleteCompanyLink()
    {   
        //
        $rowSid = $this->input->post('rowSid');
        //
        $this->company_model->deleteRow(
            "complynet_companies", 
            ['sid' => $rowSid]
        );
        //
        return SendResponse(200, [
            'code' => 'DC',
            'message' => "ComplyNet company link deleted successfully."
        ]);
    }

    public function getComplynetCompanies () {
        $this->load->library('complynet_lib');
        //
        $complynetCompanies = $this
        ->complynet_lib
        ->setMode('fake')
        ->authenticate()
        ->getCompanies();
        //
        return SendResponse(200, [
            'code' => 'SF',
            'message' => 'Successfully fetch complynet companies',
            'complynetCompanies' => (!empty($complynetCompanies) ? $complynetCompanies : [])
        ]);
    }

    /**
     * Get the comply locations details
     *
     * @param int $AHRCompanyId
     * 
     */
    public function getCompanyLocationsDetails ($AHRCompanyId) {
        // check any Complynet location is linked with AHR Company
        $doExists = $this->company_model->checkOrGetData(
            'complynet_locations',
            ['company_id' => $AHRCompanyId],
            ['sid'],
            'count_all_results'
        );
        // in case company location not found
        if(!$doExists){
            return SendResponse(200, [
                'code' => 'LNF',
                'message' => 'No location linked yet.'
            ]);
        }
        // If company location found
        $locationDetails = $this->company_model->checkOrGetData(
            'complynet_locations',
            ['company_id' => $AHRCompanyId],
            ['sid', 'company_id', 'automotohr_location_id', 'automotohr_location_name', 'complynet_location_id', 'complynet_location_name', 'status', 'created_at'],
            'result_array'
        );
        //
        return SendResponse(200, [
            'code' => 'LF',
            'locationDetails' => $locationDetails
        ]);
    }

    /**
     * Get the complynet company locations and AHR company Locations details
     *
     * @param int $AHRCompanyId
     * 
     */
    public function getComplynetLocationsDetails ($AHRCompanyId) {
        //
        $companyInfo = $this->company_model->checkOrGetData(
            'complynet_companies',
            ['automotohr_id' => $AHRCompanyId],
            ['complynet_id'],
            'row_array'
        );
        //
        $this->load->library('complynet_lib');
        //
        $complynetLocations = $this
        ->complynet_lib
        ->setMode('fake')
        ->authenticate()
        ->getLocations($companyInfo["complynet_id"]);
        //
        $companyPrimaryLocation = $this->company_model->checkOrGetData(
            'users',
            ['sid' => $AHRCompanyId],
            ['Location_Address'],
            'row_array'
        );
        //
        $companySecondaryLocation = $this->company_model->checkOrGetData(
            'company_addresses_locations',
            [
                'company_sid' => $AHRCompanyId, 
                'status' => 1
            ],
            ['sid', 'address'],
            'result_array'
        );
        //
        $companyLocations = array();
        if (!empty($companyPrimaryLocation) && !empty($companySecondaryLocation)) {
            $index = 0;
            $companyLocations[$index]['sid'] = 0;
            $companyLocations[$index]['address'] = $companyPrimaryLocation["Location_Address"];
            //
            foreach ($companySecondaryLocation as $location) {
                $index++;
                // 
                $companyLocations[$index]['sid'] = $location["sid"];
                $companyLocations[$index]['address'] = $location["address"];
            }
        } else if (!empty($companyPrimaryLocation)) {
            $companyLocations[0]['sid'] = 0;
            $companyLocations[0]['address'] = $companyPrimaryLocation["Location_Address"];
        } else if (!empty($companySecondaryLocation)) {
            foreach ($companySecondaryLocation as $l_key => $location) {
                // 
                $companyLocations[$l_key]['sid'] = $location["sid"];
                $companyLocations[$l_key]['address'] = $location["address"];
            }   
        }
        //
        return SendResponse(200, [
            'code' => 'FS',
            'complynetLocations' => $complynetLocations,
            'automotoHRLocations' => $companyLocations
        ]);
    }

    /**
     * Link AHR company location with complynet location and save into DB
     *
     * 
     */
    public function linkLocation()
    {   
        $AHRCompanySid = $this->input->post('companySid');
        $locationRowSid = $this->input->post('rowSid');
        //
        $AHRLocationSid = $this->input->post('AHRLocationSid');
        $AHRLocationName = $this->input->post('AHRLocationName');
        //
        $CNLocationSid = $this->input->post('CNLocationSid');
        $CNLocationName = $this->input->post('CNLocationName');
        //
        // check if location already linked with someone
        $doCNLocationExists = $this->company_model->checkOrGetData(
            'complynet_locations',
            ['complynet_location_id' => $CNLocationSid],
            ['sid', 'automotohr_location_name'],
            'row_array'
        );
        // in case location already linked with someone
        if($doCNLocationExists){
            return SendResponse(200, [
                'code' => 'AR',
                'message' => 'Selected ComplyNet location is already Linked with <b>'.$doCNLocationExists["automotohr_location_name"].'</b>.'
            ]);
        } 
        //
        // check if AHR location is already exist or not  
        $doAHRLocationExists = $this->company_model->checkOrGetData(
            'complynet_locations',
            ['automotohr_location_id' => $AHRLocationSid],
            ['sid'],
            'row_array'
        );
        //
        $insertId = 0;
        //
        if($locationRowSid != 0) {
            $insertId = $locationRowSid;
            //
            $data_to_update = array();
            $data_to_update["company_id"] = $AHRCompanySid;
            $data_to_update["automotohr_location_id"] = $AHRLocationSid;
            $data_to_update["complynet_location_id"] = $CNLocationSid;
            $data_to_update["automotohr_location_name"] = $AHRLocationName;
            $data_to_update["complynet_location_name"] = $CNLocationName;
            $data_to_update["status"] = 1;
            $data_to_update["updated_at"] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $this->company_model->updateData(
                "complynet_locations", 
                ['sid' => $locationRowSid],
                $data_to_update
            );
        } else {
            $data_to_insert = array();
            $data_to_insert["company_id"] = $AHRCompanySid;
            $data_to_insert["automotohr_location_id"] = $AHRLocationSid;
            $data_to_insert["complynet_location_id"] = $CNLocationSid;
            $data_to_insert["automotohr_location_name"] = $AHRLocationName;
            $data_to_insert["complynet_location_name"] = $CNLocationName;
            $data_to_insert["status"] = 1;
            $data_to_insert["created_at"] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $insertId = $this->company_model->addData("complynet_locations", $data_to_insert);
        }
       
        //
        if ($insertId > 0) {
            return SendResponse(200, [
                'code' => 'RS',
                'message' => 'Successfully, <b>'.$AHRLocationName.'</b> is linked with <b>'.$CNLocationName.'</b>.'
            ]);
        }
        //
        return SendResponse(200, [
            'code' => 'RF',
            'message' => 'Something went wrong while linking.'
        ]);
    }

    /**
     * Delete location Link
     *
     * 
     */
    public function deleteLocationLink()
    {   
        //
        $rowSid = $this->input->post('rowSid');
        //
        $this->company_model->deleteRow(
            "complynet_locations", 
            ['sid' => $rowSid]
        );
        //
        return SendResponse(200, [
            'code' => 'DL',
            'message' => "Location link deleted successfully."
        ]);
    }

    /**
     * Get the complynet departments details
     *
     * @param int $AHRCompanyId
     * 
     */
    public function getCompanyDepartmentsDetails ($AHRCompanyId) {
        // check any Complynet departments is linked with AHR Company
        $doExists = $this->company_model->checkOrGetData(
            'complynet_departments',
            ['company_id' => $AHRCompanyId],
            ['sid'],
            'count_all_results'
        );
        // in case company departmentsn not found
        if(!$doExists){
            return SendResponse(200, [
                'code' => 'DNF',
                'message' => 'No Department Found.'
            ]);
        }
        // If company departments found
        $departmentsDetails = $this->company_model->checkOrGetData(
            'complynet_departments',
            ['company_id' => $AHRCompanyId],
            ['sid', 'company_id', 'complynet_location_id', 'complynet_department_id', 'complynet_department_name', 'automotohr_department_id', 'automotohr_department_name', 'status', 'created_at'],
            'result_array'
        );
        //
        return SendResponse(200, [
            'code' => 'DF',
            'departmentsDetails' => $departmentsDetails
        ]);
    }

    public function getComplynetLinkLocations ($AHRCompanyId) {
        $locations = $this->company_model->checkOrGetData(
            'complynet_locations',
            ['company_id' => $AHRCompanyId],
            ['sid', 'automotohr_location_name', 'complynet_location_id'],
            'result_array'
        );
        // in case location already linked
        if($locations){
            return SendResponse(200, [
                'code' => 'FS',
                'locations' => $locations
            ]);
        } 
        //
        return SendResponse(200, [
            'code' => 'LNF',
            'message' => 'Please link the location first.'
        ]);
    }

    public function getSpecificLocationDepartments ($rowId, $type) {
        //
        $selectedLocation = $this->company_model->checkOrGetData(
            'complynet_locations',
            ['sid' => $rowId],
            ['complynet_location_id', 'company_id'],
            'row_array'
        );
        //
        if(!$selectedLocation){
            return SendResponse(200, [
                'code' => 'LNF',
                'message' => 'Selected location not found.'
            ]);
        } 
        //
        $automotoHRSelectedDepartments = $this->company_model->checkOrGetData(
            'complynet_departments',
            ['complynet_location_id' => $selectedLocation["complynet_location_id"]],
            ['sid', 'automotohr_department_name', 'automotohr_department_id'],
            'result_array'
        );
        //
        if ($type == "job_role") {
            return SendResponse(200, [
                'code' => 'FS',
                'selectedDepartment' => $automotoHRSelectedDepartments
            ]);
        }
        //
        $selectedDepartmentSids =array();
        //
        if ($automotoHRSelectedDepartments) {
           $selectedDepartmentSids = array_column($automotoHRSelectedDepartments, 'automotohr_department_id'); 
        }
        //
        $this->load->library('complynet_lib');
        //
        $complyNetDepartments = $this
        ->complynet_lib
        ->setMode('fake')
        ->authenticate()
        ->getDepartments($selectedLocation["complynet_location_id"]);
        //
        $automotoHRDepartments = $this->company_model->checkOrGetData(
            'departments_management',
            ['company_sid' => $selectedLocation["company_id"]],
            ['sid', 'name'],
            'result_array'
        );
        //
        return SendResponse(200, [
            'code' => 'FS',
            'complyNetDepartments' => $complyNetDepartments,
            'automotoHRDepartments' => $automotoHRDepartments,
            'selectedDepartmentSids' => $selectedDepartmentSids
        ]);
    }

    /**
     * Link AHR department with complynet department and save into DB
     *
     * 
     */
    public function linkDepartment()
    {   
        $action = "create";
        //
        $complyNetDepartmentSiD = '';
        $complyNetDepartmentName = '';
        $automotoHRDepartmentName = '';
        //
        $AHRCompanySid = $this->input->post('companySid');
        $locationRowSid = $this->input->post('locationRowSid');
        $AHRDepartmentList = $this->input->post('AHRDepartmentSid');
        //
        if (isset($_POST["complyNetDepartmentSiD"])) {
            $action = "link";
            $complyNetDepartmentSiD = $this->input->post('complyNetDepartmentSiD');
            $complyNetDepartmentName = $this->input->post('complyNetDepartmentName');
        }
        //
        // get complynet location id from DB
        $locationInfo = $this->company_model->checkOrGetData(
            'complynet_locations',
            ['sid' => $locationRowSid],
            ['complynet_location_id'],
            'row_array'
        );
        //
        $complyNetLocationId = $locationInfo["complynet_location_id"];
        //
        $insertId = 0;
        //
        foreach ($AHRDepartmentList as $departmentSid) {
            // get department name from DB
            $departmentInfo = $this->company_model->checkOrGetData(
                'departments_management',
                ['sid' => $departmentSid],
                ['name'],
                'row_array'
            );
            //
            $automotoHRDepartmentName = $departmentInfo["name"];
            //
            if ($action == "create") {
                $this->load->library('complynet_lib');
                //
                $response = $this
                ->complynet_lib
                ->setMode('fake')
                ->authenticate()
                ->createDepartment($automotoHRDepartmentName, $complyNetLocationId);
                //
                $complyNetDepartmentSiD = $response["Id"];
                $complyNetDepartmentName = $response["Name"];
            }
            //
            $data_to_insert = array();
            $data_to_insert["company_id"] = $AHRCompanySid;
            $data_to_insert["complynet_location_id"] = $complyNetLocationId;
            $data_to_insert["complynet_department_id"] = $complyNetDepartmentSiD;
            $data_to_insert["automotohr_department_id"] = $departmentSid;
            $data_to_insert["complynet_department_name"] = $complyNetDepartmentName;
            $data_to_insert["automotohr_department_name"] = $automotoHRDepartmentName;
            $data_to_insert["status"] = 1;
            $data_to_insert["created_at"] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $insertId = $this->company_model->addData("complynet_departments", $data_to_insert);
        }
        //
        if ($insertId > 0) {
            return SendResponse(200, [
                'code' => 'RS',
                'message' => 'Department is linked Successfully.'
            ]);
        }
        //
        return SendResponse(200, [
            'code' => 'RF',
            'message' => 'Something went wrong while linking.'
        ]);
    }

    /**
     * Delete department Link
     *
     * 
     */
    public function deleteDepartmentLink()
    {   
        //
        $rowSid = $this->input->post('rowSid');
        //
        $this->company_model->deleteRow(
            "complynet_departments", 
            ['sid' => $rowSid]
        );
        //
        return SendResponse(200, [
            'code' => 'DD',
            'message' => "Department link deleted successfully."
        ]);
    }

     /**
     * Get all jobroles of company
     *
     * 
     */
    public function getCompanyJobRoleDetails ($AHRCompanyId) {
        // check any Complynet job_role is linked with AHR Company
        $doExists = $this->company_model->checkOrGetData(
            'complynet_jobRole',
            ['company_id' => $AHRCompanyId],
            ['sid'],
            'count_all_results'
        );
        //
        // in case company job_role not found
        if(!$doExists){
            return SendResponse(200, [
                'code' => 'JRNF',
                'message' => 'No JobRole Found.'
            ]);
        }
        //
        // If company job_role found
        $jobRolesDetails = $this->company_model->checkOrGetData(
            'complynet_jobRole',
            ['company_id' => $AHRCompanyId],
            ['sid', 'complynet_department_id', 'complynet_jobRole_id', 'complynet_jobRole_name', 'automotohr_jobRole_name', 'status', 'created_at'],
            'result_array'
        );
        //
        return SendResponse(200, [
            'code' => 'JRF',
            'jobRolesDetails' => $jobRolesDetails
        ]);
    }

    public function getSpecificJobRoles ($rowId, $type) {
        //
        $selectedDepaerments = $this->company_model->checkOrGetData(
            'complynet_departments',
            ['sid' => $rowId],
            ['complynet_department_id', 'company_id'],
            'row_array'
        );
        //
        if(!$selectedDepaerments){
            return SendResponse(200, [
                'code' => 'DNF',
                'message' => 'Selected location not found.'
            ]);
        } 
        //
        $selectedJobRoles = $this->company_model->checkOrGetData(
            'complynet_jobRole',
            ['complynet_department_id' => $selectedDepaerments["complynet_department_id"]],
            ['sid', 'automotohr_jobRole_name'],
            'result_array'
        );
        //
        if ($type == "employee") {
            //
            return SendResponse(200, [
                'code' => 'FS',
                'selectedJobRoles' => $selectedJobRoles
            ]);
        }
        //
        $automotoHRJobRoles = $this->company_model->checkOrGetData(
            'users',
            [
                'parent_sid' => $selectedDepaerments["company_id"],
                'job_title <>' => null ,
                'job_title <>' => ''
            ],
            ['distinct(job_title)'],
            'result_array'
        );
        //
        $this->load->library('complynet_lib');
        //
        $complyNetJobRoles = $this
        ->complynet_lib
        ->setMode('fake')
        ->authenticate()
        ->getJobRole($selectedDepaerments["complynet_department_id"]);
        //
        return SendResponse(200, [
            'code' => 'FS',
            'selectedJobRoles' => array_column($selectedJobRoles, 'automotohr_jobRole_name'),
            'automotoHRJobRoles' => array_column($automotoHRJobRoles, 'job_title'),
            'complyNetJobRoles' => $complyNetJobRoles
        ]);
    }

    /**
     * Link AHR jobRole with complynet jobRole and save into DB
     *
     * 
     */
    public function linkJobRole()
    {   
        $action = "create";
        //
        $complyNetJobRoleSid = '';
        $complyNetJobRoleName = '';
        //
        $AHRCompanySid = $this->input->post('companySid');
        $departmentRowSid = $this->input->post('departmentRowSid');
        $automotoHRJobRoleList = $this->input->post('jobRoleList');
        //
        if (isset($_POST["complyNetJobRoleSid"])) {
            $action = "link";
            $complyNetJobRoleSid = $this->input->post('complyNetJobRoleSid');
            $complyNetJobRoleName = $this->input->post('complyNetJobRoleName');
        }
        //
        // get complynet department id from DB
        $departmentInfo = $this->company_model->checkOrGetData(
            'complynet_departments',
            ['sid' => $departmentRowSid],
            ['complynet_department_id'],
            'row_array'
        );
        //
        $complyNetDepartmentId = $departmentInfo["complynet_department_id"];
        //
        $insertId = 0;
        //
        foreach ($automotoHRJobRoleList as $jobRole) {
            //
            if ($action == "create") {
                $this->load->library('complynet_lib');
                //
                $response = $this
                ->complynet_lib
                ->setMode('fake')
                ->authenticate()
                ->createJobRole($jobRole, $complyNetDepartmentId);
                //
                $complyNetJobRoleSid = $response["Id"];
                $complyNetJobRoleName = $response["Name"];
            }
            //
            $data_to_insert = array();
            $data_to_insert["company_id"] = $AHRCompanySid;
            $data_to_insert["complynet_department_id"] = $complyNetDepartmentId;
            $data_to_insert["complynet_jobRole_id"] = $complyNetJobRoleSid;
            $data_to_insert["complynet_jobRole_name"] = $complyNetJobRoleName;
            $data_to_insert["automotohr_jobRole_name"] = $jobRole;
            $data_to_insert["status"] = 1;
            $data_to_insert["created_at"] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $insertId = $this->company_model->addData("complynet_jobRole", $data_to_insert);
        }
        //
        if ($insertId > 0) {
            return SendResponse(200, [
                'code' => 'RS',
                'message' => 'JobRole is linked Successfully.'
            ]);
        }
        //
        return SendResponse(200, [
            'code' => 'RF',
            'message' => 'Something went wrong while linking.'
        ]);
    }

    /**
     * Delete jobRole Link
     *
     * 
     */
    public function deleteJobRoleLink()
    {   
        //
        $rowSid = $this->input->post('rowSid');
        //
        $this->company_model->deleteRow(
            "complynet_jobRole", 
            ['sid' => $rowSid]
        );
        //
        return SendResponse(200, [
            'code' => 'DJR',
            'message' => "JobRole link deleted successfully."
        ]);
    }

    /**
     * Get all jobroles of company
     *
     * 
     */
    public function getCompanyEmployeesDetails ($AHRCompanyId) {
        // check any Complynet employees is linked with AHR Company
        $doExists = $this->company_model->checkOrGetData(
            'complynet_employees',
            ['company_id' => $AHRCompanyId],
            ['sid'],
            'count_all_results'
        );
        //
        // in case company employees not found
        if(!$doExists){
            return SendResponse(200, [
                'code' => 'ENF',
                'message' => 'No employee Found.'
            ]);
        }
        //
        // If company job_role found
        $employeesDetails = $this->company_model->checkOrGetData(
            'complynet_employees',
            ['company_id' => $AHRCompanyId],
            ['sid', 'automotohr_employee_id', 'userName', 'firstName', 'lastName', 'status', 'created_at'],
            'result_array'
        );
        //
        foreach ($employeesDetails as $eKey => $employee) {
            $employeesDetails[$eKey]["automotohr_employee_name"] = getUserNameBySID($employee["automotohr_employee_id"]);
        }
        //
        return SendResponse(200, [
            'code' => 'EF',
            'employeesDetails' => $employeesDetails
        ]);
    }

    public function getCompanyEmployees ($AHRCompanyId) {
        //
        $automotoHREmployees = $this->company_model->checkOrGetData(
            'users',
            [
                'parent_sid' => $AHRCompanyId, 
                'active' => 1, 
                'terminated_status' => 0, 
                'is_executive_admin' => 0, 
                'email <>' => null , 
                'email <>' => ''
            ],
            ['sid', 'first_name', 'last_name', 'job_title','is_executive_admin','access_level_plus', 'pay_plan_flag', 'access_level'],
            'result_array'
        );
        //
        // in case location already linked
        if(!$automotoHREmployees){
            return SendResponse(200, [
                'code' => 'ENF',
                'message' => 'Employees not found.'
            ]);
        }
        //
        $linkedLocations = $this->company_model->checkOrGetData(
            'complynet_locations',
            ['company_id' => $AHRCompanyId],
            ['sid', 'automotohr_location_name', 'complynet_location_id'],
            'result_array'
        );
        // in case location already linked
        if(!$linkedLocations){
            return SendResponse(200, [
                'code' => 'LNF',
                'message' => 'Please link location first.'
            ]);
        } 
        //
        $selectedEmployees = $this->company_model->checkOrGetData(
            'complynet_employees',
            ['company_id' => $AHRCompanyId],
            ['automotohr_employee_id'],
            'result_array'
        );
        //
        return SendResponse(200, [
            'code' => 'FS',
            'selectedEmployees' => array_column($selectedEmployees, "automotohr_employee_id"),
            'automotoHREmployees' => $automotoHREmployees,
            'linkedLocations' => $linkedLocations
        ]);
    }

    /**
     * Link AHR employee with complynet employee and save into DB
     *
     * 
     */
    public function linkEmployees ()
    {
        //
        $AHRCompanySid = $this->input->post('companySid');
        $locationRowSid = $this->input->post('locationRowSid');
        $departmentRowSid = $this->input->post('departmentRowSid');
        $jobRoleRowSid = $this->input->post('jobRoleRowSid');
        $employeesList = $this->input->post('employeesList');
        //
        // get complynet location id from DB
        $companyInfo = $this->company_model->checkOrGetData(
            'complynet_companies',
            ['automotohr_id' => $AHRCompanySid],
            ['complynet_id'],
            'row_array'
        );
        //
        if (!$companyInfo) {
            return SendResponse(200, [
                'code' => 'CNF',
                'message' => 'Please link company first.'
            ]);
        }
        //
        // get complynet location id from DB
        $locationInfo = $this->company_model->checkOrGetData(
            'complynet_locations',
            ['sid' => $locationRowSid],
            ['complynet_location_id'],
            'row_array'
        );
        //
        if (!$locationInfo) {
            return SendResponse(200, [
                'code' => 'LNF',
                'message' => 'Please link Location first.'
            ]);
        }
        //
        // get complynet department id from DB
        $departmentInfo = $this->company_model->checkOrGetData(
            'complynet_departments',
            ['sid' => $departmentRowSid],
            ['complynet_department_id'],
            'row_array'
        );
        //
        if (!$departmentInfo) {
            return SendResponse(200, [
                'code' => 'DNF',
                'message' => 'Please link Department first.'
            ]);
        }
        //
        // get complynet jobRole id from DB
        $jobRoleInfo = $this->company_model->checkOrGetData(
            'complynet_jobRole',
            ['sid' => $jobRoleRowSid],
            ['complynet_jobRole_id'],
            'row_array'
        );
        //
        if (!$jobRoleInfo) {
            return SendResponse(200, [
                'code' => 'JRNF',
                'message' => 'Please link job role first.'
            ]);
        }
        //
        $complyNetCompanyId = $companyInfo["complynet_id"];
        $complyNetLocationId = $locationInfo["complynet_location_id"];
        $complyNetDepartmentId = $departmentInfo["complynet_department_id"];
        $complyNetJobRoleId = $jobRoleInfo["complynet_jobRole_id"];
        //
        $this->load->library('complynet_lib');
        //
        $employeeResponse = $this
        ->complynet_lib
        ->setMode('fake')
        ->authenticate()
        ->makeRequest(
            $complyNetCompanyId,
            $complyNetLocationId,
            $complyNetDepartmentId,
            $complyNetJobRoleId,
            $employeesList
        );
        //
        return $employeeResponse;
    }

    /**
     * Disable employee Link
     *
     * 
     */
    public function disableEmployeeLink()
    {   
        //
        $rowSid = $this->input->post('rowSid');
        $name = $this->input->post('name');
        $status = $this->input->post('status');
        //
        $this->company_model->updateData(
            "complynet_employees", 
            ['sid' => $rowSid],
            ['status' => $status]
        );
        //
        return SendResponse(200, [
            'code' => 'DE',
            'message' => '<b>'.$name.'</b> employee '.($status == 0 ? "disabled" : "enabled").' successfully.'
        ]);
    }
}