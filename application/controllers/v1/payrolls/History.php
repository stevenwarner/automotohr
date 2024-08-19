<?php defined('BASEPATH') || exit('No direct script access allowed');
// add the controller
loadController(
    "modules/payroll/Payroll_base_controller"
);
class History extends Payroll_base_controller
{
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct(true);
        // Call the model
        $this->load->model(
            "v1/History_payroll_model",
            "history_payroll_model"
        );
        // load compulsory plugins
        // plugins
        $this->data['pageCSS'] = [
            base_url("public/v1/plugins/alertifyjs/css/alertify.min.css")
        ];
        $this->data['pageJs'] = [
            base_url("public/v1/plugins/alertifyjs/alertify.min.js"),
            base_url("public/v1/plugins/jquery/jquery-ui.js")
        ];
        // css
        $this->data['appCSS'] = $this->loadCssBundle(
            [
                'v1/app/css/loader'
            ],
            'loader'
        );
        // load library
        $this
            ->load
            ->library(
                "Lb_gusto",
                ["companyId" => $this->data["companyId"]],
                "lb_gusto"
            );
    }

    /**
     * Main page
     */
    public function index()
    {
        //
        $this->data['title'] = "Payrolls History";
        //
        $this->data['appJs'] = $this->loadJsBundle(
            [
                'v1/payroll/js/history/main'
            ],
            'payroll_history'
        );
        // get the processed payrolls
        $this->data['payrolls'] = $this
            ->history_payroll_model
            ->getProcessedPayrolls(
                $this->data['companyId']
            );
        //
        $this->loadView('v1/payroll/history/manage');
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
