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
            //
            return SendResponse(200, [
                'code' => 'NR',
                'message' => 'Company is not registered with ComplyNet.'
            ]);
        }
        // TODO when company is registered
        _e($companyId, true);
    }
}