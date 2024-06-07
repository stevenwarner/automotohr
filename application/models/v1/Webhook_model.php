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
            return $this->verifyHook("company", $this->post);
        }


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


        //
        $data = [];
        $data['entity_uuid'] = $this->post["entity_uuid"];

        if ($this->post["event_type"] === "company.approved") {

            $this->db
                ->where("gusto_uuid", $this->post["entity_uuid"])
                ->update("gusto_companies", [
                    "status" => "approved",
                    "updated_at" => getSystemDate()
                ]);


            // Send Company Approved Email
            $data['company_status'] = "Approved";
            $this->sendCompanyEmail($data);
        }
        // updated
        elseif ($this->post["event_type"] === "company.updated") {
            // load the helper
            $this
                ->company_payroll_model
                ->syncGustoToStore();

            //Send Mail
            $data['company_status'] = "Updated";
            $this->sendCompanyEmail($data);
        } elseif ($this->post["event_type"] === "company.onboarded") {
            //Send Mail
            $data['company_status'] = "Onboarded";
            $this->sendCompanyEmail($data);
        } elseif ($this->post["event_type"] === "company.bank_account.created") {

            // Company Bank Account
            $data['account_status'] = "Created";
            $data['account_for'] = "Comapny";
            $this->sendBankAccountEmail($data);
        } elseif ($this->post["event_type"] === "company.bank_account.updated") {

            $data['account_status'] = "Update";
            $data['account_for'] = "Comapny";
            $this->sendBankAccountEmail($data);
        } elseif ($this->post["event_type"] === "company.bank_account.deleted") {

            $data['account_status'] = "Deleted";
            $data['account_for'] = "Comapny";
            $this->sendBankAccountEmail($data);
        } elseif ($this->post["event_type"] === "company_benefit.created") {

            // Company Benefit 
            $data['benefit_status'] = "Created";
            $data['benefit_for'] = "Comapny";
            $this->sendBenefitsEmail($data);
        } elseif ($this->post["event_type"] === "company_benefit.updated") {

            $data['benefit_status'] = "Updated";
            $data['benefit_for'] = "Comapny";
            $this->sendBenefitsEmail($data);
        } elseif ($this->post["event_type"] === "company_benefit.deleted") {

            $data['benefit_status'] = "Deleted";
            $data['benefit_for'] = "Comapny";
            $this->sendBenefitsEmail($data);
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
        // load the company model
        $this
            ->load
            ->model(
                "v1/Payroll/Employee_payroll_model",
                "employee_payroll_model"
            );
        //
        $this
            ->employee_payroll_model
            ->setCompanyDetails(
                $this->post["resource_uuid"],
                "gusto_uuid"
            );
        // when employee is onboard

        $data = [];
        $data['entity_uuid'] = $this->post["entity_uuid"];

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


            //Send Email
            $data['employee_status'] = "Onboarded";
            $this->sendEmployeeEmail($data);
        }
        if ($this->post["event_type"] === "employee.updated") {
            // handle employee sync
            $this
                ->employee_payroll_model
                ->syncEmployeeFromGustoToStore(
                    $this->post["entity_uuid"]
                );

            //Send Email
            $data['employee_status'] = "Updated";
            $this->sendEmployeeEmail($data);
        } elseif ($this->post["event_type"] === "employee.deleted") {
            //Send Email
            $data['employee_status'] = "Deleted";
            $this->sendEmployeeEmail($data);
        } elseif ($this->post["event_type"] === "employee.terminated") {
            //Send Email
            $data['employee_status'] = "Terminated";
            $this->sendEmployeeEmail($data);
        } elseif ($this->post["event_type"] === "employee.rehired") {
            //Send Email
            $data['employee_status'] = "Rehired";
            $this->sendEmployeeEmail($data);
        } elseif ($this->post["event_type"] === "employee.bank_account.created") {

            // Company Bank Account
            $data['account_status'] = "Created";
            $data['account_for'] = "Employee";
            $this->sendBankAccountEmail($data);
        } elseif ($this->post["event_type"] === "employee.bank_account.updated") {

            $data['account_status'] = "Updated";
            $data['account_for'] = "Employee";
            $this->sendBankAccountEmail($data);
        } elseif ($this->post["event_type"] === "employee.bank_account.deleted") {

            $data['account_status'] = "Deleted";
            $data['account_for'] = "Employee";
            $this->sendBankAccountEmail($data);
        } elseif ($this->post["event_type"] === "employee_benefit.created") {

            // Employee Benefit 
            $data['benefit_status'] = "Created";
            $data['benefit_for'] = "Employee";
            $this->sendBenefitsEmail($data);
        } elseif ($this->post["event_type"] === "employee_benefit.updated") {

            $data['benefit_status'] = "Updated";
            $data['benefit_for'] = "Employee";
            $this->sendBenefitsEmail($data);
        } elseif ($this->post["event_type"] === "employee_benefit.deleted") {

            $data['benefit_status'] = "Deleted";
            $data['benefit_for'] = "Employee";
            $this->sendBenefitsEmail($data);
        } elseif ($this->post["event_type"] === "employee.home_address.created") {

            $data['address_status'] = "created";
            $data['address_for'] = "Employee";
            $this->sendAddressEmail($data);
        } elseif ($this->post["event_type"] === "employee.home_address.updated") {

            $data['address_status'] = "Updated";
            $data['address_for'] = "Employee";
            $this->sendAddressEmail($data);
        } elseif ($this->post["event_type"] === "employee.home_address.deleted") {

            $data['address_status'] = "Deleted";
            $data['address_for'] = "Employee";
            $this->sendAddressEmail($data);
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


        $data = [];
        $data['entity_uuid'] = $this->post["entity_uuid"];

        //
        if ($this->post["event_type"] === "payroll.submitted") {

            // Send Email
            $data['payroll_status'] = "Submitted";
            $this->sendPayrollEmail($data);
        } elseif ($this->post["event_type"] === "payroll.cancelled") {
            // Send Email
            $data['payroll_status'] = "Cancelled";
            $this->sendPayrollEmail($data);
        } elseif ($this->post["event_type"] === "payroll.processed") {
            // Send Email
            $data['payroll_status'] = "Processed";
            $this->sendPayrollEmail($data);
        } elseif ($this->post["event_type"] === "payroll.paid") {
            // Send Email
            $data['payroll_status'] = "Paid";
            $this->sendPayrollEmail($data);
        } elseif ($this->post["event_type"] === "payroll.updated") {
            // Send Email
            $data['payroll_status'] = "Updated";
            $this->sendPayrollEmail($data);
        } elseif ($this->post["event_type"] === "payroll.reversed") {
            // Send Email
            $data['payroll_status'] = "Reversed";
            $this->sendPayrollEmail($data);
        }
    }



    //
    function getEmailTemplateById($sid)
    {
        $this->db->select('sid, name, from_name, from_email, subject, text');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('email_templates');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }


    //
    function sendCompanyEmail($data)
    {

        // Send Email
        $this->db->select('company_sid');
        $this->db->where('gusto_uuid', $data['entity_uuid']);
        $record_obj = $this->db->get('gusto_companies');
        $record_arr = $record_obj->row_array();

        if (!empty($record_arr)) {
            $companySid = $record_arr['company_sid'];
            $employeeList = getNotificationContacts(
                $companySid,
                'payroll_notifications',
                'payroll_notifications'
            );

            $companyName = getCompanyNameBySid($companySid);

            $message_hf = message_header_footer(
                $companySid,
                $companyName
            );

            if (!empty($employeeList)) {

                foreach ($employeeList as $employeeRow) {

                    $emailTemplateData = $this->getEmailTemplateById(COMPANY_STATUS_FROM_GUSTO_EMAIL);
                    $emailTemplateBody = $emailTemplateData['text'];
                    //
                    $emailTemplateBody = str_replace('{{contact_name}}', $employeeRow['contact_name'], $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{company_name}}', $companyName, $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{company_status}}', $data['company_status'], $emailTemplateBody);

                    $subject = str_replace('{{company_status}}', $data['company_status'], $emailTemplateData['subject']);

                    //
                    $message_body = '';
                    $message_body .= $message_hf['header'];
                    $message_body .= $emailTemplateBody;
                    $message_body .= $message_hf['footer'];
                    //
                    log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $employeeRow['email'], $subject, $message_body, FROM_STORE_NAME);
                }
            }
        }
    }


    //
    function sendPayrollEmail($data)
    {

        // Send Email
        $this->db->select('company_sid');
        $this->db->where('gusto_uuid', $data['entity_uuid']);
        $record_obj = $this->db->get('gusto_companies');
        $record_arr = $record_obj->row_array();

        if (!empty($record_arr)) {
            $companySid = $record_arr['company_sid'];

            $employeeList = getNotificationContacts(
                $companySid,
                'payroll_notifications',
                'payroll_notifications'
            );

            $companyName = getCompanyNameBySid($companySid);

            $message_hf = message_header_footer(
                $companySid,
                $companyName
            );

            if (!empty($employeeList)) {

                foreach ($employeeList as $employeeRow) {

                    $emailTemplateData = $this->getEmailTemplateById(PAYROLL_STATUS_FROM_GUSTO_EMAIL);
                    $emailTemplateBody = $emailTemplateData['text'];
                    //
                    $emailTemplateBody = str_replace('{{contact_name}}', $employeeRow['contact_name'], $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{company_name}}', $companyName, $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{payroll_status}}', $data['payroll_status'], $emailTemplateBody);
                    $subject = str_replace('{{payroll_status}}', $data['payroll_status'], $emailTemplateData['subject']);

                    //
                    $message_body = '';
                    $message_body .= $message_hf['header'];
                    $message_body .= $emailTemplateBody;
                    $message_body .= $message_hf['footer'];
                    //
                    log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $employeeRow['email'], $subject, $message_body, FROM_STORE_NAME);
                }
            }
        }
    }

    //
    function sendEmployeeEmail($data)
    {

        // Send Email
        $this->db->select('company_sid');
        $this->db->where('gusto_uuid', $data['entity_uuid']);
        $record_obj = $this->db->get('gusto_companies');
        $record_arr = $record_obj->row_array();

        if (!empty($record_arr)) {
            $companySid = $record_arr['company_sid'];

            $employeeList = getNotificationContacts(
                $companySid,
                'payroll_notifications',
                'payroll_notifications'
            );

            $companyName = getCompanyNameBySid($companySid);

            $message_hf = message_header_footer(
                $companySid,
                $companyName
            );

            if (!empty($employeeList)) {

                foreach ($employeeList as $employeeRow) {

                    $emailTemplateData = $this->getEmailTemplateById(EMPLOYEE_STATUS_FROM_GUSTO_EMAIL);
                    $emailTemplateBody = $emailTemplateData['text'];
                    //
                    $emailTemplateBody = str_replace('{{contact_name}}', $employeeRow['contact_name'], $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{company_name}}', $companyName, $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{employee_status}}', $data['employee_status'], $emailTemplateBody);
                    $subject = str_replace('{{employee_status}}', $data['employee_status'], $emailTemplateData['subject']);

                    //
                    $message_body = '';
                    $message_body .= $message_hf['header'];
                    $message_body .= $emailTemplateBody;
                    $message_body .= $message_hf['footer'];
                    //
                    log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $employeeRow['email'], $subject, $message_body, FROM_STORE_NAME);
                }
            }
        }
    }




    //
    function sendBankAccountEmail($data)
    {

        // Send Email
        $this->db->select('company_sid');
        $this->db->where('gusto_uuid', $data['entity_uuid']);
        $record_obj = $this->db->get('gusto_companies');
        $record_arr = $record_obj->row_array();

        if (!empty($record_arr)) {
            $companySid = $record_arr['company_sid'];

            $employeeList = getNotificationContacts(
                $companySid,
                'payroll_notifications',
                'payroll_notifications'
            );

            $companyName = getCompanyNameBySid($companySid);

            $message_hf = message_header_footer(
                $companySid,
                $companyName
            );

            if (!empty($employeeList)) {

                foreach ($employeeList as $employeeRow) {

                    $emailTemplateData = $this->getEmailTemplateById(BANK_ACCOUNT_STATUS_FROM_GUSTO_EMAIL);
                    $emailTemplateBody = $emailTemplateData['text'];
                    //
                    $emailTemplateBody = str_replace('{{contact_name}}', $employeeRow['contact_name'], $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{company_name}}', $companyName, $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{account_status}}', $data['account_status'], $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{account_for}}', $data['account_for'], $emailTemplateBody);

                    $subject = str_replace('{{account_for}}', $data['account_for'], $emailTemplateData['subject']);

                    //
                    $message_body = '';
                    $message_body .= $message_hf['header'];
                    $message_body .= $emailTemplateBody;
                    $message_body .= $message_hf['footer'];
                    //
                    log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $employeeRow['email'], $subject, $message_body, FROM_STORE_NAME);
                }
            }
        }
    }




    //
    function sendBenefitsEmail($data)
    {

        // Send Email
        $this->db->select('company_sid');
        $this->db->where('gusto_uuid', $data['entity_uuid']);
        $record_obj = $this->db->get('gusto_companies');
        $record_arr = $record_obj->row_array();

        if (!empty($record_arr)) {
            $companySid = $record_arr['company_sid'];

            $employeeList = getNotificationContacts(
                $companySid,
                'payroll_notifications',
                'payroll_notifications'
            );

            $companyName = getCompanyNameBySid($companySid);

            $message_hf = message_header_footer(
                $companySid,
                $companyName
            );

            if (!empty($employeeList)) {

                foreach ($employeeList as $employeeRow) {

                    $emailTemplateData = $this->getEmailTemplateById(BENEFITS_STATUS_FROM_GUSTO_EMAIL);
                    $emailTemplateBody = $emailTemplateData['text'];
                    //
                    $emailTemplateBody = str_replace('{{contact_name}}', $employeeRow['contact_name'], $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{company_name}}', $companyName, $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{benefit_status}}', $data['benefit_status'], $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{benefit_for}}', $data['benefit_for'], $emailTemplateBody);

                    $subject = str_replace('{{benefit_for}}', $data['benefit_for'], $emailTemplateData['subject']);

                    //
                    $message_body = '';
                    $message_body .= $message_hf['header'];
                    $message_body .= $emailTemplateBody;
                    $message_body .= $message_hf['footer'];
                    //
                    log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $employeeRow['email'], $subject, $message_body, FROM_STORE_NAME);
                }
            }
        }
    }


    //
    function sendAddressEmail($data)
    {

        // Send Email
        $this->db->select('company_sid');
        $this->db->where('gusto_uuid', $data['entity_uuid']);
        $record_obj = $this->db->get('gusto_companies');
        $record_arr = $record_obj->row_array();

        if (!empty($record_arr)) {
            $companySid = $record_arr['company_sid'];

            $employeeList = getNotificationContacts(
                $companySid,
                'payroll_notifications',
                'payroll_notifications'
            );

            $companyName = getCompanyNameBySid($companySid);

            $message_hf = message_header_footer(
                $companySid,
                $companyName
            );

            if (!empty($employeeList)) {

                foreach ($employeeList as $employeeRow) {

                    $emailTemplateData = $this->getEmailTemplateById(ADDRESS_STATUS_FROM_GUSTO_EMAIL);
                    $emailTemplateBody = $emailTemplateData['text'];
                    //
                    $emailTemplateBody = str_replace('{{contact_name}}', $employeeRow['contact_name'], $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{company_name}}', $companyName, $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{address_status}}', $data['address_status'], $emailTemplateBody);
                    $emailTemplateBody = str_replace('{{address_for}}', $data['address_for'], $emailTemplateBody);

                    $subject = str_replace('{{address_for}}', $data['address_for'], $emailTemplateData['subject']);

                    //
                    $message_body = '';
                    $message_body .= $message_hf['header'];
                    $message_body .= $emailTemplateBody;
                    $message_body .= $message_hf['footer'];
                    //
                    log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $employeeRow['email'], $subject, $message_body, FROM_STORE_NAME);
                }
            }
        }
    }
}
