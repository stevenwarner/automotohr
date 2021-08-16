<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pending_documents extends Admin_Controller {

    private $limit = 10;
    private $resp;


    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/Pending_documents_model', 'pdm');

        $this->resp = array();
        $this->resp['Status'] = FALSE;
        $this->resp['Response'] = 'Invalid Request.';
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
        //
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_documents');
        $this->data['page_title'] = 'Copy Documents To Another Company Account';
        //
        $this->data['companies'] = $this->pdm->getAllCompanies();

        $this->render('manage_admin/company/pending_documents', 'admin_master');
    }


    //
    function get_employees($company_sid){
        //
        $this->load->model('hr_documents_management_model');
        //
        $result = $this->hr_documents_management_model->getEmployeesWithPendingDoc(
            $company_sid,
            'all',
            'all'
        );
        //
        $emp_ids = array_keys( $result );

        if (!empty($emp_ids)) {
            $employees = $this->hr_documents_management_model->getEmployeesDetails($emp_ids);
        } else {
            $employees = array();
        }
        //
        if(sizeof($employees)){
            foreach ($employees as $k => $v) {
                $employees[$k]['Documents'] = $result[$v['sid']]['Documents'];
            }
        }
        //
        //
        res([
            'view' => $this->load->view('pending_employees', ['employees' => $employees, 'company' => $this->pdm->GetCompanyDetail($company_sid, ['sid','name'])], true)
        ]);
    }

}
