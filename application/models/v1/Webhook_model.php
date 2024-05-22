<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Webhook model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Webhook_model extends CI_Model
{
    private $types;
    private $post;
    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // load the payroll helper
        // $this->load->helper('v1/payroll_production_helper');
        // call the parent constructor
        parent::__construct();
        //
        $this->types = [
            "company" => [
                "subscription_types" => ["Company"],
                "url" => "https://www.automotohr.com/webhook/gusto/company"
            ],
            "employee" => [
                "subscription_types" => ["Employee"],
                "url" => "https://www.automotohr.com/webhook/gusto/employee"
            ],
            "payroll" => [
                "subscription_types" => ["Payroll"],
                "url" => "https://www.automotohr.com/webhook/gusto/payroll"
            ]
        ];
    }

    /**
     * create company webhook
     *
     * @return array
     */
    public function syncWebhooks(): array
    {
        // response
        $gustoResponse = getWebHooks();
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }

        if ($gustoResponse) {
            //
            foreach ($gustoResponse as $hook) {
                //
                $ins = [];
                $ins['webhook_type'] = 'company';
                $ins['url'] = $hook['url'];
                $ins['status'] = $hook['status'];
                $ins['subscription_types'] = json_encode($hook['subscription_types']);
                $ins['updated_at'] = getSystemDate();
                //
                if ($this->db->where('gusto_uuid', $hook['uuid'])->count_all_results('payrolls.gusto_webhooks')) {
                    $this->db
                        ->where('gusto_uuid', $hook['uuid'])
                        ->update('payrolls.gusto_webhooks', $ins);
                } else {
                    //
                    $ins['created_at'] = $ins['updated_at'];
                    $ins['gusto_uuid'] = $hook['uuid'];
                    $this->db->insert('payrolls.gusto_webhooks', $ins);
                }
            }
        }
        //
        return ['success' => true];
    }

    /**
     * create webhook
     *
     * @param string $type company, employee, payroll
     * @return array
     */
    public function createWebhook(string $type): array
    {
        //
        $gustoType = $this->types[$type];
        //
        if (!$gustoType) {
            return ["error" => ["{$type} not found."]];
        }
        //
        if ($this->db->where('webhook_type', $type)->count_all_results('payrolls.gusto_webhooks')) {
            return ['errors' => [
                "{$type} already exists."
            ]];
        }
        // response
        $gustoResponse = createCompanyWebHook($gustoType);
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $ins = [];
        $ins['webhook_type'] = $type;
        $ins['gusto_uuid'] = $gustoResponse['uuid'];
        $ins['status'] = $gustoResponse['status'];
        $ins['url'] = $gustoResponse['url'];
        $ins['subscription_types'] = json_encode($gustoResponse['subscription_types']);
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        //
        $this->db
            ->insert('payrolls.gusto_webhooks', $ins);
        //
        return ['success' => true];
    }

    /**
     * request verification token
     *
     * @return array
     */
    public function requestVerificationTokens(): array
    {
        // get pending webhooks
        $result = $this->db
            ->select("gusto_uuid")
            ->where('status <> ', "approved")
            ->get('payrolls.gusto_webhooks')
            ->result_array();
        //
        if (!$result) {
            return ["error" => ["No pending webhooks found."]];
        }

        foreach ($result as $hook) {
            // response
            requestVerificationToken($hook["gusto_uuid"]);
        }
        return ['success' => true];
    }

    /**
     * verify hook
     *
     * @param string $type
     * @param array $post
     * @return array
     */
    public function verifyHook(string $type, array $post): array
    {
        // get pending webhooks
        $result = $this->db
            ->select("gusto_uuid")
            ->where('webhook_type', $type)
            ->get('payrolls.gusto_webhooks')
            ->row_array();
        //
        if (!$result) {
            return ["error" => ["No hook found."]];
        }
        $gustoResponse = callWebHook(
            $result["gusto_uuid"],
            $post
        );

        $errors = hasGustoErrors($gustoResponse);
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where("gusto_uuid", $gustoResponse["uuid"])
            ->update(
                "payrolls.gusto_webhooks",
                [
                    "status" => $gustoResponse["status"],
                    "updated_at" => getSystemDate()
                ]
            );

        return ['success' => true];
    }


    /**
     * save webhook calls
     *
     * @param string $type
     * @param string $post
     */
    public function saveWebhookCall(string $type, string $post)
    {
        $this->db
            ->insert("payrolls.gusto_webhook_calls", [
                "call_type" => $type,
                "data_json" => $post,
                "created_at" => getSystemDate()
            ]);
    }

    /**
     * process company events
     *
     * @param array $post
     * @return array
     */
    public function processCompany(array $post)
    {
        //
        $this->post = $post;
        // we need to verify hook
        if ($this->post["verification_token"]) {
            $this->verifyHook("company", $this->post);
        }
        //
        elseif ($this->post["event_type"] === "company.approved") {
            $this->db
                ->where("gusto_uuid", $this->post["entity_uuid"])
                ->update("gusto_companies", [
                    "status" => "approved",
                    "updated_at" => getSystemDate()
                ]);
        }
        // updated
        elseif ($this->post["event_type"] === "company.updated") {
            // get the company id by gusto uuid
            // load the company model
            $this
                ->load
                ->model(
                    "v1/Payroll/Company_payroll_model",
                    "company_payroll_model"
                );
            //
            $this
                ->company_payroll_model
                ->setCompanyDetails(
                    $this->post["entity_uuid"],
                    "gusto_uuid"
                );
            _e($this->company_payroll_model->test());
        }
    }

    /**
     * process employee events
     *
     * @param array $post
     * @return array
     */
    public function processEmployee(array $post)
    {
        //
        $this->post = $post;
        // we need to verify hook
        if ($this->post["verification_token"]) {
            return $this->verifyHook("employee", $this->post);
        }
        // load payroll model
        $this->load->model("v1/Employee_payroll_model", "employee_payroll_model");
        // when employee is onboard
        if ($this->post["event_type"] === "employee.onboarded") {
            // update onboard status
            $this->db
                ->where("gusto_uuid", $this->post["entity_uuid"])
                ->update(
                    "gusto_companies_employees",
                    [
                        "personal_details" => 1,
                        "compensation_details" => 1,
                        "work_address" => 1,
                        "home_address" => 1,
                        "federal_tax" => 1,
                        "state_tax" => 1,
                        "is_onboarded" => 1,
                        "updated_at" => getSystemDate()
                    ]
                );
        }
        if (
            $this->post["event_type"] === "employee.onboarded"
            || $this->post["event_type"] === "employee.updated"
        ) {
            // handle employee sync
            $this
                ->employee_payroll_model
                ->syncEmployeeFromGustoToStore(
                    $this->post["entity_uuid"]
                );
        }
    }

    /**
     * process employee events
     *
     * @param array $post
     * @return array
     */
    public function processPayroll(array $post)
    {
        //
        $this->post = $post;
        // we need to verify hook
        if ($this->post["verification_token"]) {
            $this->verifyHook("payroll", $this->post);
        }
    }
}
