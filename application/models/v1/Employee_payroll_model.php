<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_base_model', 'Payroll_base_model');
/**
 * Employee payroll model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Employee_payroll_model extends Payroll_base_model
{

    private $companyDetails;
    private $employeeDetails;

    private $gustoEmployee;
    private $gustoCompany;
    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
        //
        $this->gustoEmployee = [];
        $this->gustoCompany = [];
    }


    public function syncForms(string $gustoEmployeeId)
    {
        // get employee id from Gusto employee UUID
        $employeeId = $this->db
            ->select("employee_sid")
            ->where("gusto_uuid", $gustoEmployeeId)
            ->get("gusto_companies_employees")
            ->row_array()["employee_sid"];
        //
        $response = $this->syncEmployeeDocuments($employeeId);
        // get the 
        _e($response, true, true);
    }

    /**
     * update employee's payment method
     *
     * @param int   $employeeId
     */
    public function syncEmployeeDocuments(
        int $employeeId
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        //
        $this->employeeDetails = $gustoEmployee;
        $this->companyDetails = $companyDetails;
        $this->loadHelper(
            $gustoEmployee["company_sid"]
        );
        // response
        $gustoResponse = gustoCall(
            'getEmployeeForms',
            $companyDetails,
            [],
            "GET"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        foreach ($gustoResponse as $form) {
            //
            $ins = [];
            $ins['form_name'] = $form['name'];
            $ins['form_title'] = $form['title'];
            $ins['description'] = $form['description'];
            $ins['requires_signing'] = $form['requires_signing'];
            $ins['draft'] = $form['draft'];
            $ins['updated_at'] = getSystemDate();
            //
            $this->handleFormAssignment(
                $employeeId,
                $form
            );
            // we need to check the current status of w4
            if ($form['name'] == 'US_W-4') {
                $document = $this->getEmployeeW4AssignStatus($employeeId);
                $ins['status'] = $document['status'];
                $ins['document_sid'] = $document['documentId'];
            } elseif ($form['name'] == 'employee_direct_deposit') {
                $document = $this->getEmployeeDirectDepositAssignStatus($employeeId);
                $ins['status'] = $document['status'];
                $ins['document_sid'] = $document['documentId'];
            }
            //
            if ($this->db->where(['employee_sid' => $employeeId, 'gusto_uuid' => $form['uuid']])->count_all_results('gusto_employees_forms')) {
                $this->db
                    ->where(['employee_sid' => $employeeId, 'gusto_uuid' => $form['uuid']])
                    ->update('gusto_employees_forms', $ins);
            } else {
                //
                $ins['company_sid'] = $gustoEmployee['company_sid'];
                $ins['employee_sid'] = $employeeId;
                $ins['gusto_uuid'] = $form['uuid'];
                $ins['created_at'] = getSystemDate();
                //
                $this->db
                    ->insert('gusto_employees_forms', $ins);
            }
        }
        //
        return ['success' => true];
    }

    /**
     * update employee's payment method
     *
     * @param int   $employeeId
     */
    private function getEmployeeW4AssignStatus(
        int $employeeId
    ): array {
        //
        $record = $this->db
            ->select('status, sid')
            ->where(['employer_sid' => $employeeId, 'user_type' => 'employee'])
            ->get('form_w4_original')
            ->row_array();
        //
        if (!$record) {
            return [
                'status' => 'pending',
                'documentId' => 0,
                'documentType' => 'w4_form'
            ];
        }
        //
        if ($record['status'] == 1) {
            return [
                'status' => 'assign',
                'documentId' => $record['sid'],
                'documentType' => 'w4_form'
            ];
        }
        //
        return [
            'status' => 'revoke',
            'documentId' => $record['sid'],
            'documentType' => 'w4_form'
        ];
    }

    /**
     * update employee's payment method
     *
     * @param int   $employeeId
     */
    private function getEmployeeDirectDepositAssignStatus(
        int $employeeId
    ): array {
        //
        $record = $this->db
            ->select('status, sid')
            ->where([
                'user_sid' => $employeeId,
                'user_type' => 'employee',
                'document_type' => 'direct_deposit'
            ])
            ->get('documents_assigned_general')
            ->row_array();
        //
        if (!$record) {
            return [
                'status' => 'pending',
                'documentId' => 0,
                'documentType' => 'direct_deposit'
            ];
        }
        //
        if ($record['status'] == 1) {
            return [
                'status' => 'assign',
                'documentId' => $record['sid'],
                'documentType' => 'direct_deposit'
            ];
        }
        return [
            'status' => 'revoke',
            'documentId' => $record['sid'],
            'documentType' => 'direct_deposit'
        ];
    }

    /**
     * loads helper
     *
     * @param int $companyId
     */
    private function loadHelper(int $companyId)
    {
        // load the payroll helper
        $this->load->helper('v1/payroll' . ($this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
    }

    /**
     * check and assign form
     *
     * @param int $employeeId
     * @param array $form
     * @return
     */
    private function handleFormAssignment(int $employeeId, array $form)
    {
        if (
            $form["name"] === "US_W-4" ||
            $form["name"] === "employee_direct_deposit"
        ) {
            return;
        }
        // w4
        if ($form["name"] === "US_W-4" && !$this->db
            ->where([
                "employer_sid" => $employeeId,
                "user_type" => "employee",
                "status" => 1
            ])
            ->count_all_results('form_w4_original')) {
            // check if already assigned
            // assign
            $this->assignW4([
                "userSid" => $employeeId,
                "userType" => "employee",
                "company_sid" => $this->employeeDetails["company_sid"],
            ]);
        }

        // direct deposit
        elseif (
            $form["name"] === "employee_direct_deposit" && !$this->db
                ->where([
                    'user_sid' => $employeeId,
                    'user_type' => 'employee',
                    'document_type' => 'direct_deposit',
                    "status" => 1
                ])
                ->count_all_results('documents_assigned_general')
        ) {
            // assign
            $this->assignDDI([
                "userSid" => $employeeId,
                "userType" => "employee",
                "company_sid" => $this->employeeDetails["company_sid"],
                "documentType" => "direct_deposit",
                "employer_sid" => $employeeId,
                "sid" => $this->getDDId($employeeId),
                "note" => "",
                "isRequired" => 1
            ]);
        }

        if (in_array($form["name"], ["US_W-4", "employee_direct_deposit"])) {
            return;
        }
        // handle the manual documents
        if (!$form["requires_signing"]) {
            //
            $ins = [];
            $ins['form_name'] = $form['name'];
            $ins['form_title'] = $form['title'];
            $ins['description'] = $form['description'];
            $ins['requires_signing'] = $form['requires_signing'];
            $ins['draft'] = $form['draft'];
            $ins['updated_at'] = getSystemDate();
            //
            if ($this->db->where(['employee_sid' => $employeeId, 'gusto_uuid' => $form['uuid']])->count_all_results('gusto_employees_forms')) {
                $this->db
                    ->where(['employee_sid' => $employeeId, 'gusto_uuid' => $form['uuid']])
                    ->update('gusto_employees_forms', $ins);
            } else {
                //
                $ins['company_sid'] = $this->employeeDetails['company_sid'];
                $ins['employee_sid'] = $employeeId;
                $ins['gusto_uuid'] = $form['uuid'];
                $ins['created_at'] = getSystemDate();
            }
            // get the document id
            $systemDocumentId = $this->db
                ->select("document_sid")
                ->where("gusto_uuid", $form["uuid"])
                ->get("gusto_employees_forms")
                ->row_array()["document_sid"];
            // set array
            $ins = [];
            $ins["document_type"] = "uploaded";
            $ins["document_title"] = $form["title"];
            $ins["document_description"] = $form["description"];
            $ins["document_original_name"] = $form["name"];
            $ins["updated_at"] = getSystemDate();

            $this->companyDetails["other_uuid"] = $this->employeeDetails["gusto_uuid"];
            $this->companyDetails["other_uuid_2"] = $form["uuid"];

            // get the form PDF
            $response = gustoCall(
                "getEmployeeFormPdf",
                $this->companyDetails
            );
            //
            $errors = hasGustoErrors($response);
            //
            if ($errors) {
                return $errors;
            }

            //set the file name
            $fileName = uploadFileToAwsFromUrl(
                $response["document_url"],
                ".pdf",
                $employeeId . '_' . $form["name"]
            );
            //
            $ins["document_s3_name"] = $fileName;
            $ins["uploaded_file"] = $fileName;

            //
            if ($systemDocumentId != 0) {
                // update
                // insert
                $this->db
                    ->where("sid", $systemDocumentId)
                    ->update(
                        "documents_assigned",
                        $ins
                    );
                $this->db
                    ->where(['employee_sid' => $employeeId, 'gusto_uuid' => $form['uuid']])
                    ->update('gusto_employees_forms', [
                        "status" => "assign"
                    ]);
            } else {
                $ins["company_sid"] = $this->employeeDetails["company_sid"];
                $ins["assigned_date"] = getSystemDate();
                $ins["user_type"] = "employee";
                $ins["user_sid"] = $employeeId;
                $ins["document_type"] = "uploaded";
                $ins["document_extension"] = "pdf";
                $ins["document_sid"] = 0;
                $ins["uploaded_date"] = getSystemDate();
                $ins["signature_timestamp"] = getSystemDate();
                $ins["user_consent"] = 1;
                $ins["status"] = 1;
                $ins["is_pending"] = 1;
                // insert
                $this->db
                    ->insert(
                        "documents_assigned",
                        $ins
                    );
                //
                $documentId = $this->db->insert_id();
                $this->db
                    ->where(['employee_sid' => $employeeId, 'gusto_uuid' => $form['uuid']])
                    ->update('gusto_employees_forms', [
                        "document_sid" => $documentId,
                        "status" => "assign"
                    ]);
            }
        }
    }

    /**
     * assign w4 to employee
     *
     * @param array $post
     */
    private function assignW4($post)
    {
        $this->load->model("hr_documents_management_model");
        $user_sid = $post["userSid"];
        $user_type = $post["userType"];
        $company_sid = $post["company_sid"];
        $w4_form_history = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);
        //
        if (empty($w4_form_history)) {
            $w4_data_to_insert = array();
            $w4_data_to_insert['employer_sid'] = $user_sid;
            $w4_data_to_insert['company_sid'] = $company_sid;
            $w4_data_to_insert['user_type'] = $user_type;
            $w4_data_to_insert['sent_status'] = 1;
            $w4_data_to_insert['sent_date'] = getSystemDate();
            $w4_data_to_insert['status'] = 1;
            $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
        } else {
            $w4_data_to_update                                          = [];
            $w4_data_to_update['sent_date']                             = getSystemDate();
            $w4_data_to_update['status']                                = 1;
            $w4_data_to_update['signature_timestamp']                   = null;
            $w4_data_to_update['signature_email_address']               = null;
            $w4_data_to_update['signature_bas64_image']                 = null;
            $w4_data_to_update['init_signature_bas64_image']            = null;
            $w4_data_to_update['ip_address']                            = null;
            $w4_data_to_update['user_agent']                            = null;
            $w4_data_to_update['uploaded_file']                         = null;
            $w4_data_to_update['uploaded_by_sid']                       = 0;
            $w4_data_to_update['user_consent']                         = 0;

            $this->hr_documents_management_model->activate_w4_forms($user_type, $user_sid, $w4_data_to_update);
        }

        //
        keepTrackVerificationDocument($user_sid, "employee", 'assign', getVerificationDocumentSid($user_sid, $user_type, 'w4'), 'w4', 'Gusto Forms');
    }

    /**
     * check assign direct deposit
     *
     * @param int $userId
     */
    private function getDDId($userId)
    {
        $result = $this->db->select("sid")
            ->where([
                "user_sid" => $userId,
                "user_type" => "employee"
            ])
            ->get("documents_assigned_general")
            ->row_array();

        return $result ? $result["sid"] : 0;
    }

    /**
     * assign direct deposit
     *
     * @param array $post
     */
    private function assignDDI($post)
    {
        $this->load->model("hr_documents_management_model");
        //
        $insertId = $this->hr_documents_management_model->assignGeneralDocument(
            $post['userSid'],
            $post['userType'],
            $post["company_sid"],
            $post['documentType'],
            $post["employer_sid"],
            $post['sid'],
            $post['note'],
            $post['isRequired']
        );
        //
        if (!$insertId) {
            return;
        }
        return true;
    }

    /**
     * Sync employee from Gusto to Store
     *
     * @param string $gustoEmployeeId
     * @param bool   $doSendResponse Optional
     */
    public function syncEmployeeFromGustoToStore(string $gustoEmployeeId, bool $doSendResponse = true)
    {
        // get store employee details
        $storeEmployeeDetails = $this->db
            ->select("employee_sid, company_sid")
            ->where("gusto_uuid", $gustoEmployeeId)
            ->get("gusto_companies_employees")
            ->row_array();
        // check the employee
        if (!$storeEmployeeDetails) {
            if ($doSendResponse) {
                return SendResponse(400, ["errors" => ["Failed to verify entity."]]);
            }
            return ["errors" => ["Failed to verify entity."]];
        }
        // add the employee gusto uuid
        $storeEmployeeDetails["gusto_uuid"] = $gustoEmployeeId;
        // get company details
        $gustoCompany = $this->getCompanyDetailsForGusto($storeEmployeeDetails["company_sid"]);
        // check the company
        if (!$gustoCompany) {
            if ($doSendResponse) {
                return SendResponse(400, ["errors" => ["Failed to verify entity."]]);
            }
            return ["errors" => ["Failed to verify resource."]];
        }
        //
        $this->loadPayrollLibrary($storeEmployeeDetails["company_sid"]);
        //
        $gustoCompany['other_uuid'] = $storeEmployeeDetails['gusto_uuid'];

        $this->gustoCompany = $gustoCompany;
        $this->gustoEmployee = $storeEmployeeDetails;

        // let's sync the employee work addresses
        $this->syncEmployeeWorkAddresses();
        // let's sync the employee jobs and compensations
        $this->syncEmployeeJobs();
        // let's sync the employee home address
        $this->syncEmployeeHomeAddress();
        // let's sync the employee federal tax
        $this->syncEmployeeFederalTax();
        // let's sync the employee state tax
        $this->syncEmployeeStateTaxFromGusto();
        // let's sync the employee payment method
        $this->syncEmployeePaymentMethodFromGusto();
        // let's sync the employee forms
        $this->syncEmployeeForms();

        if ($doSendResponse) {
            return SendResponse(400, ["errors" => ["Failed to verify entity."]]);
        }

        return [
            "synced!"
        ];
    }


    /**
     * Employee sync functions
     */

    /**
     * sync employee work addresses
     *
     * @param array
     */
    private function syncEmployeeWorkAddresses(): array
    {
        // response
        $gustoResponse = gustoCall(
            'getEmployeeWorkAddress',
            $this->gustoCompany
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        foreach ($gustoResponse as $location) {
            //
            $ins = [];

            $ins['gusto_location_uuid'] = $location['location_uuid'];
            $ins['gusto_version'] = $location['version'];
            $ins['effective_date'] = $location['effective_date'];
            $ins['active'] = $location['active'];
            $ins['street_1'] = $location['street_1'];
            $ins['street_2'] = $location['street_2'];
            $ins['city'] = $location['city'];
            $ins['state'] = $location['state'];
            $ins['zip'] = $location['zip'];
            $ins['country'] = $location['country'];
            $ins['updated_at'] = getSystemDate();
            //
            $whereArray = [
                'gusto_uuid' => $location['uuid']
            ];
            //
            if (!$this->db->where($whereArray)->count_all_results('gusto_companies_employees_work_addresses')) {

                $ins['created_at'] = getSystemDate();
                $ins['gusto_uuid'] = $location['uuid'];
                $ins['employee_sid'] = $this->gustoEmployee["employee_sid"];
                // insert
                $this->db->insert('gusto_companies_employees_work_addresses', $ins);
            } else {
                // update
                $this->db
                    ->where($whereArray)
                    ->update('gusto_companies_employees_work_addresses', $ins);
            }
        }
        //
        return ['success' => true];
    }

    /**
     * sync employee jobs and compensations
     *
     * @param array
     */
    private function syncEmployeeJobs(): array
    {
        // make call
        $gustoResponse = gustoCall(
            "getEmployeeJobs",
            $this->gustoCompany,
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        if (!$gustoResponse) {
            return ['count' => 0];
        }
        //
        $this->db
            ->where(['employee_sid' => $this->gustoEmployee["employee_sid"]])
            ->update('gusto_companies_employees', ['compensation_details' => 1]);
        //
        foreach ($gustoResponse as $gustoJobs) {
            // set array
            $ins = [];

            $ins['gusto_version'] = $gustoJobs['version'];
            $ins['is_primary'] = $gustoJobs['primary'];
            $ins['gusto_location_uuid'] = $gustoJobs['location_uuid'];
            $ins['hire_date'] = $gustoJobs['hire_date'];
            $ins['title'] = $gustoJobs['title'];
            $ins['rate'] = $gustoJobs['rate'];
            $ins['current_compensation_uuid'] = $gustoJobs['current_compensation_uuid'];

            // let's quickly check
            if (
                $gustoEmployeeJobId =
                $this->db
                    ->select('sid')
                    ->where(['gusto_uuid' => $gustoJobs['uuid']])
                    ->get('gusto_employees_jobs')
                    ->row_array()['sid']
            ) {
                //
                $ins['updated_at'] = getSystemDate();
                // insert
                $this->db
                    ->where(['gusto_uuid' => $gustoJobs['uuid']])
                    ->update('gusto_employees_jobs', $ins);
            } else {
                //
                $ins['created_at'] =
                    $ins['updated_at'] =
                    getSystemDate();
                $ins['employee_sid'] = $this->gustoEmployee["employee_sid"];
                $ins['gusto_uuid'] = $gustoJobs['uuid'];
                // insert
                $this->db
                    ->insert('gusto_employees_jobs', $ins);
                $gustoEmployeeJobId = $this->db->insert_id();
            }
            //
            // add compensations
            foreach ($gustoJobs['compensations'] as $compensation) {
                //
                $ins = [];
                $ins['gusto_version'] = $compensation['version'];
                $ins['rate'] = $compensation['rate'];
                $ins['payment_unit'] = $compensation['payment_unit'];
                $ins['flsa_status'] = $compensation['flsa_status'];
                $ins['effective_date'] = $compensation['effective_date'];
                $ins['adjust_for_minimum_wage'] = $compensation['adjust_for_minimum_wage'];
                //
                if ($this->db->where(['gusto_uuid' => $compensation['uuid']])->count_all_results('gusto_employees_jobs_compensations')) {
                    $ins['updated_at'] = getSystemDate();
                    $this->db
                        ->where(['gusto_uuid' => $compensation['uuid']])
                        ->update('gusto_employees_jobs_compensations', $ins);
                } else {
                    $ins['gusto_employees_jobs_sid'] = $gustoEmployeeJobId;
                    $ins['gusto_uuid'] = $compensation['uuid'];
                    $ins['created_at'] = getSystemDate();
                    $ins['updated_at'] = getSystemDate();
                    $this->db->insert('gusto_employees_jobs_compensations', $ins);
                }
            }
        }
        //
        return ['count' => count($gustoResponse)];
    }

    /**
     * sync employee federal tax
     *
     * @param array
     */
    private function syncEmployeeFederalTax(): array
    {
        // response
        $gustoResponse = gustoCall(
            'createFederalTax',
            $this->gustoCompany,
            [],
            'GET'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        if (!$gustoResponse) {
            return ['count' => 0];
        }

        //
        $upd = [];
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['filing_status'] = $gustoResponse['filing_status'];
        $upd['extra_withholding'] = $gustoResponse['extra_withholding'];
        $upd['two_jobs'] = $gustoResponse['two_jobs'];
        $upd['dependents_amount'] = $gustoResponse['dependents_amount'];
        $upd['other_income'] = $gustoResponse['other_income'];
        $upd['deductions'] = $gustoResponse['deductions'];
        $upd['w4_data_type'] = $gustoResponse['w4_data_type'];
        $upd['updated_at'] = getSystemDate();

        if ($this->db->where('employee_sid', $this->gustoEmployee["employee_sid"])->count_all_results("gusto_employees_federal_tax")) {
            //
            $this->db
                ->where('employee_sid', $this->gustoEmployee["employee_sid"])
                ->update('gusto_employees_federal_tax', $upd);
        } else {
            //
            $upd["employee_sid"] = $this->gustoEmployee["employee_sid"];
            $upd["created_at"] = $upd["updated_at"];
            //
            $this->db
                ->insert('gusto_employees_federal_tax', $upd);
        }
        //
        return ['success' => true];
    }

    /**
     * sync employee payment method from Gusto
     *
     * @param int   $employeeId
     */
    private function syncEmployeePaymentMethodFromGusto(): array
    {
        // response
        $gustoResponse = gustoCall(
            'getPaymentMethod',
            $this->gustoCompany,
            [],
            'GET'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        $whereArray = [
            'employee_sid' => $this->gustoEmployee["employee_sid"],
        ];
        //
        $dataArray = [];
        $dataArray['gusto_version'] = $gustoResponse['version'];
        $dataArray['type'] = $gustoResponse['type'];
        $dataArray['split_by'] = $gustoResponse['split_by'];
        $dataArray['splits'] = json_encode($gustoResponse['splits']);
        $dataArray['updated_at'] = getSystemDate();
        //
        if (!$this->db->where($whereArray)->count_all_results('gusto_employees_payment_method')) {
            // insert
            $dataArray['created_at'] = $dataArray['updated_at'];
            $dataArray['employee_sid'] = $this->gustoEmployee["employee_sid"];
            //
            $this->db
                ->insert('gusto_employees_payment_method', $dataArray);
        } else {
            // update
            $this->db
                ->where($whereArray)
                ->update('gusto_employees_payment_method', $dataArray);
        }
        if ($gustoResponse['type'] == 'Check') {
            //
            $this->db
                ->where('users_sid', $this->gustoEmployee["employee_sid"])
                ->where('users_type', 'employee')
                ->update('bank_account_details', [
                    'gusto_uuid' => null
                ]);
        } else {
            $this->syncEmployeeBankAccountsToGusto();
        }
        //
        updateUserById([
            'payment_method' => stringToSlug($gustoResponse['type'], '_')
        ], $this->gustoEmployee["employee_sid"]);
        //
        return ['success' => true, 'version' => $gustoResponse['version']];
    }

    /**
     * sync employee bank accounts
     *
     * @return
     */
    private function syncEmployeeBankAccountsToGusto(): array
    {
        // get employee bank accounts
        $bankAccounts = $this->db
            ->select('
            sid,
            account_title,
            routing_transaction_number,
            account_number,
            account_type,
            gusto_uuid
        ')
            ->where([
                'users_sid' => $this->gustoEmployee["employee_sid"],
                'users_type' => 'employee'
            ])
            ->order_by('sid', 'desc')
            ->get('bank_account_details')
            ->result_array();
        //
        if (!$bankAccounts) {
            return ['errors' => ['No bank accounts found.']];
        }
        //
        foreach ($bankAccounts as $account) {
            //
            if ($account['gusto_uuid'] != '') {
                continue;
            }
            //
            $this->addEmployeeBankAccountToGusto($account);
        }
        //
        return ['success' => true];
    }

    /**
     * sync employee bank account
     *
     * @param array $data
     */
    private function addEmployeeBankAccountToGusto(array $data): array
    {
        // make request
        $request = [];
        $request['name'] = $data['account_title'];
        $request['routing_number'] = $data['routing_transaction_number'];
        $request['account_number'] = $data['account_number'];
        $request['account_type'] = ucwords($data['account_type']);
        // response
        $gustoResponse = gustoCall(
            'addBankAccount',
            $this->gustoCompany,
            $request,
            'POST'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->reset_query()
            ->where('sid', $data['sid'])
            ->update(
                'bank_account_details',
                [
                    'gusto_uuid' => $gustoResponse['uuid'],
                ]
            );
        //
        return ['success' => true, 'gusto_uuid' => $gustoResponse['uuid']];
    }

    /**
     * sync employee state tax from Gusto
     */
    public function syncEmployeeStateTaxFromGusto(): array
    {
        // response
        $gustoResponse = gustoCall(
            'getStateTax',
            $this->gustoCompany,
            [],
            'GET'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        foreach ($gustoResponse as $tax) {
            //
            $whereArray = [
                'employee_sid' => $this->gustoEmployee["employee_sid"],
                'state_code' => $tax['state']
            ];
            //
            $dataArray = [];
            $dataArray['state_code'] = $tax['state'];
            $dataArray['file_new_hire_report'] = $tax['file_new_hire_report'];
            $dataArray['is_work_state'] = $tax['is_work_state'];
            $dataArray['questions_json'] = json_encode($tax['questions']);
            $dataArray['updated_at'] = getSystemDate();
            //
            if (!$this->db->where($whereArray)->count_all_results('gusto_employees_state_tax')) {
                // insert
                $dataArray['created_at'] = $dataArray['updated_at'];
                $dataArray['employee_sid'] = $this->gustoEmployee["employee_sid"];
                //
                $this->db
                    ->insert('gusto_employees_state_tax', $dataArray);
            } else {
                // update
                $this->db
                    ->where($whereArray)
                    ->update('gusto_employees_state_tax', $dataArray);
            }
        }
        //
        return ['success' => true];
    }

    /**
     * sync employee forma
     */
    public function syncEmployeeForms(): array
    {
        // response
        $gustoResponse = gustoCall(
            'getEmployeeForms',
            $this->gustoCompany,
            [],
            "GET"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        foreach ($gustoResponse as $form) {
            //
            $ins = [];
            $ins['form_name'] = $form['name'];
            $ins['form_title'] = $form['title'];
            $ins['description'] = $form['description'];
            $ins['requires_signing'] = $form['requires_signing'];
            $ins['draft'] = $form['draft'];
            $ins['updated_at'] = getSystemDate();
            //
            $this->handleFormAssignment(
                $this->gustoEmployee["employee_sid"],
                $form
            );
            // we need to check the current status of w4
            if ($form['name'] == 'US_W-4') {
                $document = $this->getEmployeeW4AssignStatus($this->gustoEmployee["employee_sid"]);
                $ins['status'] = $document['status'];
                $ins['document_sid'] = $document['documentId'];
            } elseif ($form['name'] == 'employee_direct_deposit') {
                $document = $this->getEmployeeDirectDepositAssignStatus($this->gustoEmployee["employee_sid"]);
                $ins['status'] = $document['status'];
                $ins['document_sid'] = $document['documentId'];
            }
            //
            if ($this->db->where(['employee_sid' => $this->gustoEmployee["employee_sid"], 'gusto_uuid' => $form['uuid']])->count_all_results('gusto_employees_forms')) {
                $this->db
                    ->where([
                        'employee_sid' => $this->gustoEmployee["employee_sid"],
                        'gusto_uuid' => $form['uuid']
                    ])
                    ->update('gusto_employees_forms', $ins);
            } else {
                //
                $ins['company_sid'] = $this->gustoEmployee['company_sid'];
                $ins['employee_sid'] = $this->gustoEmployee["employee_sid"];
                $ins['gusto_uuid'] = $form['uuid'];
                $ins['created_at'] = getSystemDate();
                //
                $this->db
                    ->insert('gusto_employees_forms', $ins);
            }
        }
        //
        return ['success' => true];
    }

    /**
     * sync employee home address
     */
    public function syncEmployeeHomeAddress(): array
    {
        // response
        $gustoResponse = gustoCall(
            'createHomeAddress',
            $this->gustoCompany,
            [],
            "GET"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        foreach ($gustoResponse as $v0) {
            //
            if (!$v0["active"]) {
                continue;
            }
            // update
            $upd = [];
            $upd["gusto_home_address_uuid"] = $v0["uuid"];
            $upd["gusto_home_address_version"] = $v0["version"];
            $upd["gusto_home_address_effective_date"] = $v0["effective_date"];
            $upd["gusto_home_address_courtesy_withholding"] = $v0["courtesy_withholding"];
            $upd["home_address"] = 1;
            $upd["updated_at"] = getSystemDate();
            //
            $this->db
                ->where("employee_sid", $this->gustoEmployee["employee_sid"])
                ->update(
                    "gusto_companies_employees",
                    $upd
                );
            //
            updateUserById(
                [
                    "Location_Address" => $v0["street_1"],
                    "Location_Address_2" => $v0["street_2"],
                    "Location_City" => $v0["city"],
                    "Location_State" => getStateColumn(["state_code" => $v0["state"]], "sid"),
                    "Location_ZipCode" => $v0["zip"],
                ],
                $this->gustoEmployee["employee_sid"]
            );
        }
        //
        return ['success' => true];
    }
}
