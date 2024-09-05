<?php

use Sabberworm\CSS\Value\Value;

if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Schedule model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Scheduling
 */
class Ledger_model extends CI_Model
{
    /**
     * Main entry point
     */
    public function __construct()
    {
        // inherit parent
        parent::__construct();
    }

    function getEmployerDetail($id)
    {
        $this->db->where('sid', $id);
        return $this->db->get('users')->row_array();
    }

    function checkEmployeeExistInCompany($data, $companyId)
    {
        //
        $columnName = "";
        $columnValue = "";
        //
        if ($data['employee_id']) {
            $columnName = "sid";
            $columnValue = $data['employee_id'];
        } else if ($data['employee_number']) {
            $columnName = "employee_number";
            $columnValue = $data['employee_number'];
        } else if ($data['employee_ssn']) {
            $columnName = 'REGEXP_REPLACE(ssn, "[^0-9]", "") =';
            $columnValue = preg_replace('/[^0-9]/', '', $data['employee_ssn']);
        } else if ($data['employee_email']) {
            $columnName = 'LOWER(email)';
            $columnValue = $data['employee_email'];
        } else if ($data['employee_phone']) {
            $columnName = 'PhoneNumber';
            $columnValue = $data['employee_phone'];
        }

        //
        if ($columnName &&  $columnValue) {
            //
            $columnValue = trim(strtolower($columnValue));
            //
            $this->db->select("sid");
            $this->db->where('parent_sid', $companyId);
            //
            if ($columnName == 'PhoneNumber') {
                //
                $phoneNumber = preg_replace('/[^0-9]/', '', $data['employee_phone']);
                //
                $this->db->group_start();
                $this->db->where('REGEXP_REPLACE(PhoneNumber,"[^0-9]","")', $phoneNumber);
                //
                if (substr(preg_replace('/[^0-9]/', '', $data['employee_phone']), 0, 1) != 1) {
                    $this->db->or_where('REGEXP_REPLACE(PhoneNumber,"[^0-9]","")', '1' . $phoneNumber);
                    $this->db->or_where('REGEXP_REPLACE(PhoneNumber,"[^0-9+]","")', '+1' . $phoneNumber);
                } else {
                    $this->db->or_where('REGEXP_REPLACE(PhoneNumber,"[^0-9+]","")', '+' . $phoneNumber);
                    $this->db->or_where('REGEXP_REPLACE(PhoneNumber,"[^0-9]","")', substr($phoneNumber, 1));
                }
                //
                $this->db->group_end();
            } else {
                $this->db->where($columnName, $columnValue);
            }

            $this->db->limit(1);
            //
            $result = $this->db->get('users')->row_array();

            return $result['sid'] ?? 0;
        }
        //
        return 0;
        //
    }

    public function insertLedgerInfo($data)
    {
        $this->db->insert('payrolls.payroll_ledger', $data);
    }


    //
    public  function savePayrollLedgerFromJson(
        $data,
        $companySid
    ) {


        $ledgerData = json_decode($data, true);

        $companyDataInsert = [];

        foreach ($ledgerData as $key => $val) {
            if ($key == 'totals') {
                $companyDataInsert['company_sid'] = $companySid;
                $companyDataInsert['debit_amount'] = $val['company_debit'] ? $val['company_debit'] : '0';
                $companyDataInsert['credit_amount'] = $val['company_credit'] ? $val['company_credit'] : '0';
                $companyDataInsert['net_pay'] = $val['net_pay_debit'] ? $val['net_pay_debit'] : '0';
                $companyDataInsert['taxes'] = $val['tax_debit'] ? $val['tax_debit'] : '0';
                $companyDataInsert['gross_pay'] = $val['gross_pay_debit'] ? $val['gross_pay_debit'] : '0';
                $companyDataInsert['is_regular'] = 1;
                $companyDataInsert['created_at'] = date('Y-m-d H:i:s');
                $companyDataInsert['updated_at'] = date('Y-m-d H:i:s');
            }

            if ($key == 'employee_compensations') {
                if (!empty($val)) {
                    foreach ($val as $employeeVal) {

                        $employeeId = $this->getEmployeeIdByUuid($employeeVal['employee_uuid']);

                        if ($employeeId != '') {
                            // employee
                            if (
                                !$this->db
                                    ->where([
                                        'start_date' => $employeeVal['start_date'],
                                        'end_date' => $employeeVal['end_date'],
                                        'transaction_date' => $employeeVal['debit_date'],
                                        'debit_amount' => $employeeVal['employee_debit'],
                                        'credit_amount' => $employeeVal['employee_credit'],
                                        'company_sid' => $companySid,
                                        'employee_sid' => $employeeId
                                    ])
                                    ->count_all_results('payrolls.payroll_ledger')
                            ) {
                                $employeeDataInsert = [];

                                $employeeDataInsert['debit_amount'] = $employeeVal['employee_debit'] ? $employeeVal['employee_debit'] : '0';
                                $employeeDataInsert['credit_amount'] = $employeeVal['employee_credit'] ? $employeeVal['employee_credit'] : 0;
                                $employeeDataInsert['net_pay'] = $employeeVal['net_pay'] ? $employeeVal['net_pay'] : '0';
                                $employeeDataInsert['taxes'] = $employeeVal['total_tax'] ? $employeeVal['total_tax'] : '0';
                                $employeeDataInsert['gross_pay'] = $employeeVal['gross_pay_debit'] ? $employeeVal['gross_pay_debit'] : '0';
                                $employeeDataInsert['is_regular_employee'] = 1;
                                $employeeDataInsert['employee_sid'] = $employeeId;
                                $employeeDataInsert['start_date'] = $employeeVal['start_date'];
                                $employeeDataInsert['end_date'] = $employeeVal['end_date'];
                                $employeeDataInsert['transaction_date'] = $employeeVal['debit_date'];
                                $employeeDataInsert['description'] = $employeeVal['recipient_notice'] ? $employeeVal['recipient_notice'] : '0';
                                $employeeDataInsert['company_sid'] = $companySid;
                                $employeeDataInsert['created_at'] = date('Y-m-d H:i:s');
                                $employeeDataInsert['updated_at'] = date('Y-m-d H:i:s');

                                $this->db->insert("payrolls.payroll_ledger", $employeeDataInsert);
                            }
                        }
                    }
                }
            }

            if ($key == 'start_date') {
                $companyDataInsert['start_date'] = $val;
            }
            if ($key == 'end_date') {
                $companyDataInsert['end_date'] = $val;
            }
            if ($key == 'debit_date') {
                $companyDataInsert['transaction_date'] = $val;
            }
            if ($key == 'recipient_notice') {
                $companyDataInsert['description'] = $val;
            }
        }

        // Company
        if (
            !$this->db
                ->where([
                    'start_date' => $companyDataInsert['start_date'],
                    'end_date' =>  $companyDataInsert['end_date'],
                    'transaction_date' => $companyDataInsert['transaction_date'],
                    'debit_amount' => $companyDataInsert['debit_amount'],
                    'credit_amount' => $companyDataInsert['credit_amount'],
                    'company_sid' => $companySid,
                    'employee_sid' => null
                ])
                ->count_all_results('payrolls.payroll_ledger')
            && !empty($companyDataInsert)
        ) {
            //
            $this->db->insert("payrolls.payroll_ledger", $companyDataInsert);
        }


    }

    //
    public function getEmployeeIdByUuid($employeeUuid)
    {
        if ($employeeUuid == '') {
            return '';
        }

        $this->db->select('employee_sid');
        $this->db->where('gusto_uuid', $employeeUuid);
        $rec = $this->db->get('gusto_companies_employees')->row_array();

        if (!empty($rec)) {
            return $rec['employee_sid'];
        } else {
            return '';
        }
    }
}
