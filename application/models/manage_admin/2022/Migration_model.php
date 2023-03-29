<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration of company data 
 * 
 * Migrates one company data to another company. Data can be applicants, employees, 
 * groups, document.
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Mubashir Ahmed <mubashar@automotohr.com>
 * @version 1.0 
 * 
 */
class Migration_model extends CI_Model {

    /**
     * Entry point
     */
    function __construct() {
        // Inherit parent class properties and methods
        parent::__construct();
        // Connect to company model
        $this->load->model('2022/Company_model', 'company_model');
    }


    /**
     * Get companies
     * 
     * @return array
     */
    function getAllCompanies(){
        //
        return $this->company_model->getAllCompanies([
            'sid',
            'CompanyName'
        ], [
            'is_paid' => 1,
            'active' => 1,
            'parent_sid' => 0,
            'career_page_type' => 'standard_career_site'
        ], 'result_array', ['CompanyName', 'ASC']);
    }

    /**
     * Get company groups with documents
     * 
     * @param integer $companyId
     * @return array
     */
    public function getCompanyGroupsWithDocuments($companyId){
        //
        return $this->company_model->getCompanyGroupsWithDocuments($companyId);
    }

    /**
     * Migrate groups with documents
     * 
     * @param int   $fromCompanyId
     * @param int   $toCompanyId
     * @param array $groupIds
     * 
     * @return array
     */
    public function migrateGroupsWithDocumentsByIds(
        int $fromCompanyId,
        int $toCompanyId,
        array $groupIds
    ){
        return $this->company_model->migrateGroupsWithDocumentsByIds(
            $fromCompanyId,
            $toCompanyId,
            $groupIds
        );
    }

}
