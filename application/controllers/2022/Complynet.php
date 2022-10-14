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
    public function link_company()
    {   
        //
        $AHRCompanySid = $this->input->post('AHRCompanySid');
        $AHRCompanyName = $this->input->post('AHRCompanyName');
        //
        $CNCompanySid = $this->input->post('CNCompanySid');
        $CNCompanyName = $this->input->post('CNCompanyName');
        // check if company is registered with ComplyNet
        $doExists = $this->company_model->checkOrGetData(
            'complynet_companies',
            ['complynet_id' => $CNCompanySid],
            ['sid', 'automotohr_name'],
            'row_array'
        );
        // in case company is not on ComplyNet
        if($doExists){
            return SendResponse(200, [
                'code' => 'AR',
                'message' => 'Selected ComplyNet Company is already Linked with <b>'.$doExists["automotohr_name"].'</b>.'
            ]);
        } 
        //
        
     
        $data_to_insert = array();
        $data_to_insert["automotohr_id"] = $AHRCompanySid;
        $data_to_insert["complynet_id"] = $CNCompanySid;
        $data_to_insert["automotohr_name"] = $AHRCompanyName;
        $data_to_insert["complynet_name"] = $CNCompanyName;
        $data_to_insert["status"] = 1;
        $data_to_insert["created_at"] = date('Y-m-d H:i:s', strtotime('now'));
        //
        $insert_id = $this->company_model->addData("complynet_companies", $data_to_insert);
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
     * Toggle Link company status
     *
     * 
     */
    public function change_company_status()
    {   
        //
        $rowSid = $this->input->post('rowSid'); 
        $status = $this->input->post('status');
        //
        $data_to_update = array();
        $data_to_update["status"] = $status;
        //
        $this->company_model->updateData(
            "complynet_companies", 
            ['sid' => $rowSid],
            $data_to_update
        );
        //
        $message = "Successfully enable complynet company.";

        //
        if ($status == 0) {
            $message = "Successfully disable complynet company.";
        }
        //
        return SendResponse(200, [
            'code' => 'CS',
            'message' => $message
        ]);
    }

    public function get_complynet_companies () {
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
}