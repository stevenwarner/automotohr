<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Complynet extends Admin_Controller {

    /**
     * Main entry point
     */
    function __construct() {
        parent::__construct();
        $this->load->model('2022/Company_model', 'company_model');
    }

    /**
     * 
     */
    public function manage() {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if(!$admin_id){
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // set page title
        $this->data['page_title'] = 'ComplyNet';
        // get all companies
        $this->data['companies'] = $this->company_model->getAllCompanies(
            ['sid', 'CompanyName']
        );

        $this->render('complynet/admin/manage');
    }

    /**
     * 
     */
    public function report() {
        //
        $admin_id = $this->session->userdata('user_id');
        //
        if(!$admin_id){
            return redirect('/');
        }
        //
        $this->data['security_details'] = db_get_admin_access_level_details($admin_id);
        // set page title
        $this->data['page_title'] = 'ComplyNet - Report';
        // get all companies
        $this->data['companies'] = $this->company_model->getAllCompanies(
            ['sid', 'CompanyName']
        );

        $this->render('complynet/admin/report');
    }

}