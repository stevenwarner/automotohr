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

    /**
     * get the company agreement
     *
     * @param int $companyId
     */
    public function getCompanyAgreement(int $companyId)
    {
        //
        if (!isCompanyLinkedWithGusto($companyId)) {
            return SendResponse(
                400,
                [
                    'errors' => [
                        "Company is not set up for payroll."
                    ]
                ]
            );
        }
        // set
        $data = [];
        // check if the contract is signed
        $data['agreement'] = $this
            ->db
            ->select('is_ts_accepted, ts_email, ts_ip')
            ->where('company_sid', $companyId)
            ->get('gusto_companies')
            ->row_array();
        // get company's admins
        $data['admins'] = $this->db
            ->select('
                email_address, 
                automotohr_reference
            ')
            ->where('company_sid', $companyId)
            ->where('is_store_admin', 0)
            ->get('gusto_companies_admin')
            ->result_array();
        //
        return SendResponse(
            200,
            [
                'view' =>
                $this->load->view(
                    'v1/payroll/create_partner_company/agreement',
                    $data,
                    true
                )
            ]
        );
    }

    /**
     * get the company agreement
     *
     * @param int $companyId
     */
    public function signCompanyAgreement(int $companyId): array
    {
        // set the sanitized post
        $post = $this->input->post(null, true);
        //
        $errors = [];
        // validation
        if (!$post['email']) {
            $errors[] = '"Email" is required.';
        }
        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '"Email" is invalid.';
        }
        if (!$post['userReference']) {
            $errors[] = '"System User Reference" is required.';
        }
        //
        if ($errors) {
            return SendResponse(400, ['errors' => $errors]);
        }
        //
        $response = $this
            ->company_payroll_model
            ->signCompanyAgreement(
                $companyId,
                $post
            );
        //
        if ($response["errors"]) {
            return SendResponse(400, $errors);
        }
        // add the job to the queue
        $this
            ->company_payroll_model
            ->addSyncJobToQueue(
                $companyId
            );
        //
        return SendResponse(200, $response);
    }
}
