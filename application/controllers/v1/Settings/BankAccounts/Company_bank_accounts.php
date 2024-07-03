<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Company bank accounts
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Company_bank_accounts extends Public_Controller
{

    private $sessionDetails;

    /**
     * main function
     */
    public function __construct()
    {
        parent::__construct();
        $this->sessionDetails = checkUserSession();
    }

    /**
     * Sanitized view
     */
    public function listing()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = $this->sessionDetails;
        $data['title'] = "Company bank accounts :: " . STORE_NAME;
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        $data['sanitizedView'] = 1;
        // get the security details
        $data['security_details'] =
            $data["securityDetails"] = db_get_access_level_details(
                $data['session']['employer_detail']['sid'],
                null,
                $data['session']
            );

        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),
            "v1/plugins/ms_modal/main"
        ];
        //
        $data["appJs"] = bundleJs(
            [
                "v1/settings/company_bank_account"
            ],
            "public/v1/js/settings/bank_accounts/",
            'company_bank_accounts',
            true
        );
        // get the bank accounts
        $data["bankAccounts"] = $this
            ->db
            ->select([
                "sid",
                "gusto_uuid",
                "is_active",
                "account_type",
                "routing_number",
                "hidden_account_number",
                "verification_status",
                "name",
            ])
            ->where([
                "company_sid" => $data['session']['company_detail']['sid'],
            ])
            ->order_by("sid", "DESC")
            ->get("gusto_company_bank_accounts")
            ->result_array();
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/settings/BankAccounts/listing')
            ->view('main/footer');
    }

    public function getAddPage()
    {
        return SendResponse(
            200,
            [
                "view" => $this
                    ->load
                    ->view(
                        "v1/settings/BankAccounts/add",
                        [],
                        true
                    )
            ]
        );
    }

    public function processAddPage()
    {
        // set company id
        $companyId = $this->sessionDetails["company_detail"]["sid"];
        //
        if (
            !$this->input->post("confirmed", true)
            && $this->db
            ->where("company_sid", $companyId)
            ->count_all_results("gusto_company_bank_accounts")
        ) {
            // check if there was a bank exists
            return SendResponse(
                200,
                [
                    "required" => true,
                    "message" => "A default bank account exists. if you create this bank it will disable the old one and the new bank account will replace it as the company's default funding method."
                ]
            );
        }
        // get the sanitized post
        $post = $this->input->post(null, true);
        // check for gusto
        if (
            isCompanyLinkedWithGusto($companyId)
        ) {
            // let's create the bank account on Gusto
            // load the company model
            // Call the model
            $this->load->model(
                "v1/Payroll/Company_payroll_model",
                "company_payroll_model"
            );
            //
            $this
                ->company_payroll_model
                ->setCompanyDetails(
                    $companyId
                );
            //
            $response = $this
                ->company_payroll_model
                ->dataToGustoBankAccount([
                    "account_type" => $post["jsAccountType"],
                    "routing_number" => $post["jsRoutingNumber"],
                    "account_number" => $post["jsAccountNumber"],
                    "name" => $post["jsBankName"],
                ]);
            return SendResponse(
                $response["errors"] ? 400 : 200,
                $response
            );
        }
        //
        $systemDateTime = getSystemDate();
        // set the insert array
        $ins = [
            "company_sid" => $companyId,
            "account_type" => $post["jsAccountType"],
            "routing_number" => $post["jsRoutingNumber"],
            "account_number" => $post["jsAccountNumber"],
            "hidden_account_number" => maskBankAccount(
                $post["jsAccountNumber"]
            ),
            "created_at" => $systemDateTime,
            "updated_at" => $systemDateTime,
            "is_active" => 1,
            "name" => $post["jsBankName"],
        ];
        //
        $this
            ->db
            ->where(
                "company_sid",
                $companyId
            )
            ->update(
                "gusto_company_bank_accounts",
                [
                    "is_active" => 0,
                    "updated_at" => $systemDateTime
                ]
            );
        //
        $this
            ->db
            ->insert(
                "gusto_company_bank_accounts",
                $ins
            );
        //
        return SendResponse(
            200,
            [
                "message" =>
                "You have successfully created a new bank account."
            ]
        );
    }

    public function processDelete(int $companyBankId)
    {
        // set company id
        $companyId = $this->sessionDetails["company_detail"]["sid"];
        // check if the bank account is primary
        if (
            $this->db
            ->where("company_sid", $companyId)
            ->where("is_active", 1)
            ->where("sid", $companyBankId)
            ->count_all_results("gusto_company_bank_accounts")
        ) {
            // check if there was a bank exists
            return SendResponse(
                400,
                [
                    "errors" => [
                        "This is the company's default account, which cannot be deleted."
                    ]
                ]
            );
        }
        //
        $this
            ->db
            ->where("sid", $companyBankId)
            ->delete(
                "gusto_company_bank_accounts"
            );
        //
        return SendResponse(
            200,
            [
                "message" =>
                "You have successfully deleted the company bank account."
            ]
        );
    }
}
