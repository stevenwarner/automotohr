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
        $insert_id = 0;
        //
        if($doAHRCompanyExists) {
            $insert_id = $doAHRCompanyExists["sid"];
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
                ['sid' => $insert_id],
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
            $insert_id = $this->company_model->addData("complynet_companies", $data_to_insert);
        }
       
        //
        if ($insert_id > 0) {
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
            ['company_sid' => $AHRCompanyId, 'status' => 1],
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
    public function link_location()
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
        $insert_id = 0;
        //
        if($locationRowSid != 0) {
            $insert_id = $locationRowSid;
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
            $insert_id = $this->company_model->addData("complynet_locations", $data_to_insert);
        }
       
        //
        if ($insert_id > 0) {
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
     * Get the comply locations details
     *
     * @param int $AHRCompanyId
     * 
     */
    public function getCompanyDepartmentsDetails ($AHRCompanyId) {
        // check any Complynet location is linked with AHR Company
        $doExists = $this->company_model->checkOrGetData(
            'complynet_departments',
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
}