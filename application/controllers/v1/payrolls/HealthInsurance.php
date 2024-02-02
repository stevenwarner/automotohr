<?php defined('BASEPATH') || exit('No direct script access allowed');

class HealthInsurance extends Public_Controller
{
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        //
        $this->load->model(
            "v1/Payroll_model",
            "payroll_model"
        );
    }


    public function setup()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        $data['title'] = "Payroll Health Insurance Set up";
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        $data['level'] = 0;
        $data["sanitizedView"] = true;
        //
        $companyId = $data['session']['company_detail']['sid'];
        // get the security details
        $data['security_details'] = $data["securityDetails"] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        //
        $companyGustoDetails = $this->payroll_model->getCompanyDetailsForGusto($companyId, ['status', 'added_historical_payrolls', 'is_ts_accepted']);

        // get the company onboard flow
        $data['flow'] = gustoCall(
            'getCompanyOnboardFlow',
            $companyGustoDetails,
            [
                'flow_type' => "company_health_insurance",
                "entity_type" => "Company",
                "entity_uuid" => $companyGustoDetails['gusto_uuid']
            ],
            "POST"
        )['url'];

        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/healthInsuranceSetup')
            ->view('main/footer');
    }
}
