<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Company payroll
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Company_payroll extends CI_Controller
{
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        // Call the model
        $this->load->model(
            "v1/Payroll/Company_payroll_model",
            "company_payroll_model"
        );
    }

    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function checkCompanyRequirements(int $companyId): array
    {
        //
        $response = $this
            ->company_payroll_model
            ->checkCompanyRequirements(
                $companyId
            );
        //
        if (!$response) {
            return SendResponse(200, ["success" => true]);
        }
        //
        return SendResponse(400, ["errors" => $response]);
    }

    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function createPartnerCompanyProcess(int $companyId): array
    {
        // call the executor
        $response = $this
            ->company_payroll_model
            ->startCreatePartnerCompany(
                $companyId,
                // [68, 69]
                $this->input->post("employees", true)
            );
        //
        if (isset($response['errors'])) {
            return SendResponse(400, $response);
        }
        return SendResponse(200, [$response]);
    }

    

   
}
