<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * ComplyNet
 *
 * @version 1.0
 */
class Complynet extends Admin_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        //
        parent::__construct();
        //
        $this->load->library('Complynet/Complynet_lib', '', 'clib');
        //
        $this->load->model('2022/complynet_model');
    }


    /**
     * Dashboard
     */
    public function dashboard()
    {
        $this->data['page_title'] = 'ComplyNet Dashboard';
        $this->data['security_details'] = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        $this->data['PageScripts'] = [
            'js/SystemModal',
            '1.0' => '2022/js/complynet/dashboard'
        ];
        $this->data['PageCSS'] = [
            'css/SystemModel'
        ];
        // Get companies
        $this->data['companies'] = $this->complynet_model->getCompanies('active');
        //
        $this->render('2022/complynet/dashboard', 'admin_master');
    }

    /**
     * AJAX CALLS
     */

    /**
     * Check company integration
     * @param int $companyId
     * @return
     */
    public function checkCompanyIntegration(int $companyId)
    {
        //
        if (!$this->checkLogin()) {
            return SendResponse(401); // Forbidden
        }
        //
        $companyId = (int) $companyId;
        //
        $record = $this->complynet_model->checkAndGetCompanyIntegrationDetails($companyId);
        //
        return SendResponse(200, $record ?? []);
    }
    
    /**
     * Start with company integration
     * @param int $companyId
     * @return
     */
    public function gettingStarted(int $companyId)
    {
        //
        if (!$this->checkLogin()) {
            return SendResponse(401); // Forbidden
        }
        //
        $companyId = (int) $companyId;
        //
        $returnArray = [];
        $returnArray['companyName'] = $this->complynet_model->getUserColumn(
            $companyId,
            'CompanyName'
        );
        $returnArray['complynetCompanies'] = $this->clib->getComplyNetCompanies();
        $returnArray['view'] = $this->load->view('2022/complynet/partials/company_integration');
        //
        return SendResponse(200, $returnArray);
    }

    /**
     * Checks the login
     */
    private function checkLogin(){
        return (bool) $this->ion_auth->user()->row()->id;
    }
}