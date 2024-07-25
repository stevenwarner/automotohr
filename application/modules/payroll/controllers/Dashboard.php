<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the base controller
loadController('Base_controller', 'payroll');
/**
 * Payroll dashboard controller
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Dashboard extends Base_controller
{
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        //
        $this->checkForLinkedCompany();
    }

    /**
     * dashboard page
     * @mode public
     */
    public function index()
    {
        // set the public view
        $data = $this->setPublicData();
        //
        $data['title'] = "Payroll Dashboard";
        // get the payroll blockers
        $payrollBlockers = $this
            ->payrolls_model
            ->checkPayrollBlockers(
                $this->companyId
            );
        // when there are blockers
        if ($payrollBlockers) {
            return redirect("payrolls/setup");
        }
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/dashboard')
            ->view('main/footer');
    }

    /**
     * set up payroll
     */
    public function setup()
    {
        // set the public view
        $data = $this->setPublicData();
        //
        $data['title'] = "Payroll set up";
        // set the JS files
        $data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/payroll/js/dashboard'
        ], $this->js, 'dashboard', $this->createMinifyFiles);
        // get the payroll blockers
        $data["payrollBlockers"] = $this
            ->payrolls_model
            ->getPayrollBlockers(
                $this->companyId
            );
        // get the Gusto company flow link
        $data["flow"] = $this
            ->payrolls_model
            ->getCompanyFlowLink(
                $this->companyId
            );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/setup')
            ->view('main/footer');
    }
}
