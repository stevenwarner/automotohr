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
                $companies = $this->copy_policies_model->getAllCompanies(1);
                //
                if(!sizeof($companies)){
                    $this->resp['Response'] = 'Oops! System unable to find any company.';
                    $this->response();
                }

                $this->resp['Data'] = $companies;
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Proceed.';
                $this->response();
            break;

            case 'get_company_policies':
                $companyPolicies = $this->copy_policies_model->getCompanyPolicies($formpost, $this->limit);
                //
                if(!sizeof($companyPolicies['policies'])){
                    $this->resp['Response'] = 'Oops! This company has no policies.';
                    $this->response();
                }
                //
                $this->resp['Data'] = $companyPolicies['policies'];
                $this->resp['Status'] = TRUE;
                if($formpost['page'] == 1){
                    $this->resp['Limit'] = $this->limit;
                    $this->resp['TotalRecords'] = $companyPolicies['PoliciesCount'];
                    $this->resp['TotalPages'] = ceil($this->resp['TotalRecords'] / $this->limit);
                }
                $this->resp['Response'] = 'Proceed.';
                $this->response();
            break;

            case 'copy_process':
                $this->resp['Copied'] = FALSE;
                $this->resp['Failed'] = FALSE;
                $this->resp['Exists'] = FALSE;
                // Check if policy is copied
                // $isCopied = $this->copy_policies_model->checkPolicyCopied($formpost);
                // //
                // if($isCopied){
                //     $this->resp['Exists'] = TRUE;
                //     $this->resp['Status'] = TRUE;
                //     $this->resp['Response'] = 'Policy already copied';
                //     $this->response();
                // }
                //
                $isMoved = $this->copy_policies_model->movePolicy($formpost, $this->ion_auth->user()->row()->id);
                //
                $this->resp['Copied'] = $isMoved;
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Proceed.';
                $this->response();
            break;
            
            case 'copy_timeoff':
                $isMoved = $this->copy_policies_model->copyTimeOff(
                    $formpost['fromCompanyId'],
                    $formpost['toCompanyId'],
                    $this->ion_auth->user()->row()->id
                );
                //
                $this->resp['Copied'] = $isMoved;
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Proceed.';
                $this->response();
            break;
        }
        $this->response();
    }

    public function copyTimeoffPolicies () {
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_policies');
        $this->data['page_title'] = 'Copy Documents To Another Company Account';

        $this->render('manage_admin/company/copy_timeoff_policies', 'admin_master');
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
