<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Copy_policies extends Admin_Controller {

    private $limit = 10;
    private $resp;


    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/copy_policies_model');

        $this->resp = array();
        $this->resp['Status'] = FALSE;
        $this->resp['Response'] = 'Invalid Request.';
    }

    /**
     * Handles Index traffic
     * Created on: 28-10-2022
     *
     * @uses db_get_admin_access_level_details
     * @uses check_access_permissions
     *
     * @return VOID
     */
    public function index() {
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_policies');
        $this->data['page_title'] = 'Copy Documents To Another Company Account';

        $this->render('manage_admin/company/copy_policies', 'admin_master');
    }


    /**
     * Handles AJAX requests
     *
     * @accepts POST
     *
     * @uses response
     *
     * @return JSON
     */
    function handler(){
        $formpost = $this->input->post(NULL, TRUE);
        //
        switch($formpost['action']){
            case 'get_all_companies':
                $companies = $this->copy_documents_model->getAllCompanies(1);

                if(!sizeof($companies)){
                    $this->resp['Response'] = 'Oops! System unable to find any company.';
                    $this->response();
                }

                $this->resp['Data'] = $companies;
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Proceed.';
                $this->response();
            break;

            case 'get_company_documents':
                $companyDocuments = $this->copy_documents_model->getCompanyDocuments($formpost, $this->limit);
                $companyOfferLetters = $this->copy_documents_model->getCompanyOfferLetters($formpost, $this->limit);
                //
                $companyDocuments['Documents'] = array_merge( 
                    $companyDocuments['Documents'],  
                    $companyOfferLetters['Documents']
                );
                $companyDocuments['DocumentCount'] = $companyDocuments['DocumentCount'] + $companyOfferLetters['DocumentCount'];
                //
                if(!sizeof($companyDocuments['Documents'])){
                    $this->resp['Response'] = 'Oops! This company has no documents.';
                    $this->response();
                }
                //
                $this->resp['Data'] = $companyDocuments['Documents'];
                $this->resp['Status'] = TRUE;
                if($formpost['page'] == 1){
                    $this->resp['Limit'] = $this->limit;
                    $this->resp['TotalRecords'] = $companyDocuments['DocumentCount'];
                    $this->resp['TotalPages'] = ceil($this->resp['TotalRecords'] / $this->limit);
                }
                $this->resp['Response'] = 'Proceed.';
                $this->response();
            break;

            case 'copy_process':
                $this->resp['Copied'] = FALSE;
                $this->resp['Failed'] = FALSE;
                $this->resp['Exists'] = FALSE;
                // Check if document is copied
                $isCopied = $this->copy_documents_model->checkDocumentCopied($formpost);
                //
                if($isCopied){
                    $this->resp['Exists'] = TRUE;
                    $this->resp['Status'] = TRUE;
                    $this->resp['Response'] = 'Document already copied';
                    $this->response();
                }
                //
                if($formpost['document']['document_type'] == 'offer_letter'){
                    $isMoved = $this->copy_documents_model->moveOfferLetter($formpost, $this->ion_auth->user()->row()->id);
                } else{
                    // Fetch document details
                    $isMoved = $this->copy_documents_model->moveDocument($formpost, $this->ion_auth->user()->row()->id);
                }

                $this->resp['Copied'] = $isMoved;
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Proceed.';
                $this->response();
            break;
        }
        $this->response();
    }

    /**
     * Handles AJAX requests
     * @accepts POST
     * @return JSON
     */
    private function response(){
        header('Content-Type: application/json');
        echo json_encode($this->resp);
        exit(0);
    }


}
