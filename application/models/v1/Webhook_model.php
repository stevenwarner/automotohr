<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_model', 'Payroll_model');
/**
 * Webhook model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Webhook_model extends Payroll_model
{
    private $types;
    private $post;
    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
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
        //
        if ($this->post["event_type"] === "company.approved") {
            $this->db
                ->where("gusto_uuid", $this->post["entity_uuid"])
                ->update("gusto_companies", [
                    "status" => "approved",
                    "updated_at" => getSystemDate()
                ]);
        }
    }
}
