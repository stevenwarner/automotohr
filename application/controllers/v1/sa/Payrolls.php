<?php defined('BASEPATH') or exit('No direct script access allowed');

class Payrolls extends Admin_Controller
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


    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('v1/Payroll_model', 'payroll_model');
        // set path to CSS file
        $this->css = 'public/v1/sa/css/payrolls/';
        // set path to JS file
        $this->js = 'public/v1/sa/js/payrolls/';
        //
        $this->createMinifyFiles = true;
    }

    /**
     * main page
     *
     * @param int $companyId
     */
    public function index(int $companyId)
    {
        // set the company id
        $this->data['loggedInCompanyId'] = $companyId;
        //
        $this->data["mode"] = $this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "Production" : "Demo";
        //
        $this->data['companyOnboardingStatus'] = $this
            ->payroll_model
            ->getCompanyOnboardingStatus($companyId);
        // set title
        $this->data['page_title'] = 'Payroll dashboard :: ' . (STORE_NAME);
        // set CSS
        $this->data['appCSS'] = bundleCSS([
            "css/theme-2021"
        ], $this->css, "admins", $this->createMinifyFiles);
        // set JS
        $this->data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/sa/payrolls/dashboard'
        ], $this->js, 'dashboard', $this->createMinifyFiles);
        // render the page
        $this->render('v1/sa/payrolls/dashboard', 'admin_master');
    }


    /**
     * update company payment configuration
     *
     * @param int $companyId
     * @return
     */
    public function updateMode(int $companyId)
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        //
        $this->payroll_model->updateMode($companyId, $this->input->post(null, true));
    }
}
