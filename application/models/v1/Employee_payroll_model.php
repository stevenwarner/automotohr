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
    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
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
}
