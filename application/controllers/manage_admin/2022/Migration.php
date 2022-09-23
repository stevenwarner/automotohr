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
class Migration extends Admin_Controller {

    private $resp;

    /**
     * Entry point of the controller
     */
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        
        $this->resp = array();
        $this->resp['data'] = [];

        // Lets load the migration model
        $this->load->model('manage_admin/2022/Migration_model', 'migration_model');
    }

    /**
     * Handles Index traffic
     * Created on: 11-08-2019
     *
     * @uses db_get_admin_access_level_details
     * @uses check_access_permissions
     *
     * @return VOID
     */
    public function index() {
        // Set page title
        $this->data['page_title'] = 'Migration of company groups :: '.STORE_NAME;
        // Set security
        $this->data['security_details'] = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        // Fetch all the companies
        $this->data['companies'] = $this->migration_model->getAllCompanies();
        //
        $this->render('manage_admin/2022/migration/company_group_with_documents', 'admin_master');
    }

    /**
     * Handles AJAX calls
     * Migration calls of company group with documents
     * 
     * @method SendResponse
     * 
     * @param integer $fromCompanyId
     * @return json
     */
    public function groupWithDocumentHandler($fromCompanyId){
        // Verify if session exists
        if(!$this->ion_auth->user()->row()->id){
            return SendResponse(404);
        }
        // Let's clean the input
        $fromCompanyId = (int) $fromCompanyId;
        // Get all company groups with documents
        $results = $this->migration_model->getCompanyGroupsWithDocuments(
            [
                'company_sid' => $fromCompanyId,
                'status' => 1
            ], [
                'sid',
                'name'
            ], [
                'documents_2_group.group_sid', 
                'documents_management.sid',
                'documents_management.document_title',
                'documents_management.document_type',
                'documents_management.acknowledgment_required',
                'documents_management.download_required',
                'documents_management.signature_required'
            ]
        );
        //
        return sendResponse(200, $results);
    }
    
    /**
     * Handles AJAX calls
     * Migration calls of company group with documents
     * 
     * @method SendResponse
     * 
     * @param integer $fromCompanyId
     * @return json
     */
    public function groupWithDocumentPostHandler(){
        // Verify if session exists
        if(!$this->ion_auth->user()->row()->id){
            return SendResponse(404);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        // Let's clean the input
        $fromCompanyId = (int) $post['fromId'];
        $toCompanyId = (int) $post['toId'];
        $groupIds = $post['ids'];
        // Migrate groups with documents
        $this->migration_model->migrateGroupsWithDocumentsByIds(
            $fromCompanyId,
            $toCompanyId,
            $groupIds
        );
        //
        return sendResponse(200, true);
    }
}
