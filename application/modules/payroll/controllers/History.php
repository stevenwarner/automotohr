<?php defined('BASEPATH') || exit('No direct script access allowed');

class History extends Public_controller
{
    /**
     * for js
     */
    private $js;
    /**
     * for css
     */
    private $css;
    /**
     * wether to create minified files or not
     */
    private $createMinifyFiles;
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        // Call the model
        $this->load->model("v1/History_payroll_model", "history_payroll_model");
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/history/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/history/';
        //
        $this->createMinifyFiles = true;
    }

    /**
     * Main page
     */
    public function index()
    {
        //
        $data = $this->getData();
        //
        $data['title'] = "Payroll History";
        // css
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'main',
            $this->createMinifyFiles
        );
        //
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/history/main'
            ],
            $this->js,
            'main',
            $this->createMinifyFiles
        );
        // get the processed payrolls
        $data['payrolls'] = $this
            ->history_payroll_model
            ->getProcessedPayrolls(
                $data['loggedInPersonCompanyId']
            );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/history/manage')
            ->view('main/footer');
    }

    /**
     * get single payroll
     *
     * @param int $payrollId
     */
    public function single(int $payrollId)
    {
        //
        $data = $this->getData();
        // get the processed payrolls
        $data['payroll'] = $this
            ->history_payroll_model
            ->getPayrollById(
                $payrollId,
                $data['loggedInPersonCompanyId']
            );
        //
        if (!$data['payroll']) {
            return redirect('payrolls/history');
        }
        //
        $data['payrollEmployees'] = $this
            ->history_payroll_model
            ->getSpecificPayrollEmployees(
                array_keys($data['payroll']['employees'])
            );
        //
        $data['title'] = "Single Payroll History";
        // css
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'single',
            $this->createMinifyFiles
        );
        //
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/history/main'
            ],
            $this->js,
            'single',
            $this->createMinifyFiles
        );


        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/history/single')
            ->view('main/footer');
    }

    /**
     * get common data
     */
    private function getData()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $data['isLoggedInView'] = 1;
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        $data['loggedInPersonCompanyId'] = $data['session']['company_detail']['sid'];
        $data['loggedInPersonId'] = $data['session']['employer_detail']['sid'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        //
        return $data;
    }
}
