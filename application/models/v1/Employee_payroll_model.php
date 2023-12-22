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
        // $gustoResponse = gustoCall(
        //     'getEmployeeForms',
        //     $companyDetails,
        //     [],
        //     "GET"
        // );
        $gustoResponse = json_decode('[{"uuid":"7046f46d-fd47-4d8d-a9ad-d38486bad779","name":"US_W-4","title":"Form W-4","description":"Form W-4 records your tax withholding allowance.","requires_signing":true,"draft":false,"year":null,"quarter":null,"employee_uuid":"8a5e2d41-84b0-4605-ba59-6d007a473ba1"},{"uuid":"cf5a8156-c89c-419d-8b30-1693a3b15ad5","name":"mn_new_hire","title":"Minnesota New Hire Reporting Form","description":"This document reports your new hires to the state of Minnesota","requires_signing":false,"draft":false,"year":null,"quarter":null,"employee_uuid":"8a5e2d41-84b0-4605-ba59-6d007a473ba1"}]', true);
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

            die("asdas");
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
       _e($employeeId, true);
       _e($form, true, true);
    }
}
