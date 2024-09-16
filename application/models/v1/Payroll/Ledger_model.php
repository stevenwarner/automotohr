<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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
    public function getCompanyEmployeesOnly(int $companyId): array
    {

        $this->db
            ->select(getUserFields())
            ->where([
                "users.parent_sid" => $companyId,
                "users.is_executive_admin" => 0,
                "users.active" => 1,
                "users.terminated_status" => 0
            ]);
        //

        $this->db->order_by("users.first_name", "ASC");
        return $this->db->get("users")->result_array();
    }


    //
    public function getEmployeesLedger(
        $companyId,
        $between = '',
        $filterEmployees,
        $filterJobTitles,
        $filterDepartment,
        $limit = null,
        $start = null
    ) {
        //
        $this->db->select('payrolls.payroll_ledger.debit_amount');
        $this->db->select('payrolls.payroll_ledger.credit_amount');
        $this->db->select('payrolls.payroll_ledger.description');
        $this->db->select('payrolls.payroll_ledger.is_deleted');
        $this->db->select('payrolls.payroll_ledger.created_at');
        $this->db->select('payrolls.payroll_ledger.updated_at');
        $this->db->select('payrolls.payroll_ledger.gross_pay');
        $this->db->select('payrolls.payroll_ledger.net_pay');
        $this->db->select('payrolls.payroll_ledger.taxes');
        $this->db->select('payrolls.payroll_ledger.transaction_date');
        $this->db->select('payrolls.payroll_ledger.start_date');
        $this->db->select('payrolls.payroll_ledger.end_date');
        $this->db->select('payrolls.payroll_ledger.employee_sid');
        $this->db->select('payrolls.payroll_ledger.company_sid');

        $this->db->select('payrolls.payroll_ledger.account_name');
        $this->db->select('payrolls.payroll_ledger.account_number');
        $this->db->select('payrolls.payroll_ledger.general_entry_number');
        $this->db->select('payrolls.payroll_ledger.reference_number');
        $this->db->select('payrolls.payroll_ledger.extra');

        $this->db->select('payrolls.payroll_ledger.payroll_sid');
        $this->db->select('payrolls.payroll_ledger.is_regular');
        $this->db->select('payrolls.payroll_ledger.is_regular_employee');
        $this->db->select('payrolls.payroll_ledger.is_external');

        //
        $this->db->where('payrolls.payroll_ledger.is_deleted', 0);
        $this->db->where('payrolls.payroll_ledger.company_sid', $companyId);


        if (
            !in_array("all", $filterEmployees)
            || (!in_array("all", $filterDepartment) && !in_array("0", $filterDepartment))
            || !in_array("all", $filterJobTitles)
        ) {
            $this->db->join(
                "users",
                "users.sid = payrolls.payroll_ledger.employee_sid",
                "inner"
            );
            if (!in_array("all", $filterEmployees)) {
                $this->db->where_in('payrolls.payroll_ledger.employee_sid', $filterEmployees);
            }

            if (!in_array("0", $filterDepartment) && !in_array("all", $filterDepartment)) {
                $this->db->where_in('users.team_sid', $filterDepartment);
            }

            if (!in_array("all", $filterJobTitles)) {
                $i = 0;
                $this->db->group_start();
                foreach ($filterJobTitles as $title) {
                    if ($i == 0) {
                        $this->db->like('users.job_title', $title);
                    } else {
                        $this->db->or_like('users.job_title', $title);
                    }
                    $i++;
                }
                $this->db->group_end();
            }
        }

        //
        if ($between != '' && $between != null) {
            $this->db->where($between);
        }

        // in case of count
        if ($limit === null) {
            return $this
                ->db
                ->count_all_results('payrolls.payroll_ledger');
        }

        //
        if ($limit != null) {
            $this->db->limit($limit, $start);
        }
        $this->db->order_by("payrolls.payroll_ledger.sid", "desc");
        $employeesLedger = $this->db->get('payrolls.payroll_ledger')->result_array();

        // set caches
        $employeeCache = [];

        foreach ($employeesLedger as $key => $val) {
            if ($val['employee_sid'] != null) {
                // check in cache
                if (!$employeeCache[$val["employee_sid"]]) {
                    $employeeCache[$val["employee_sid"]] = [
                        "profile" => $this->getEmmployeeInfo($val['employee_sid']),
                        "teamDepartment" => $val['employee_sid'] != null ? getEmployeeDepartmentAndTeams($val['employee_sid']) : ''
                    ];
                }
                $empInfo = $employeeCache[$val["employee_sid"]]["profile"];
                $teamDepartment = $employeeCache[$val["employee_sid"]]["teamDepartment"];

                $employeesLedger[$key]['first_name'] = $empInfo['first_name'];
                $employeesLedger[$key]['last_name'] = $empInfo['last_name'];
                $employeesLedger[$key]['access_level'] = $empInfo['access_level'];
                $employeesLedger[$key]['job_title'] = $empInfo['job_title'];
                $employeesLedger[$key]['is_executive_admin'] = $empInfo['is_executive_admin'];
                $employeesLedger[$key]['pay_plan_flag'] = $empInfo['pay_plan_flag'];
                $employeesLedger[$key]['timezone'] = $empInfo['timezone'];
                $employeesLedger[$key]['middle_name'] = $empInfo['middle_name'];
                $employeesLedger[$key]['ssn'] = $empInfo['ssn'];
                $employeesLedger[$key]['email'] = $empInfo['email'];
                $employeesLedger[$key]['PhoneNumber'] = $empInfo['PhoneNumber'];
                $employeesLedger[$key]['employee_number'] = $empInfo['employee_number'];
                $employeesLedger[$key]['sid'] = $empInfo['sid'];
                $employeesLedger[$key]['employee_id'] = $empInfo['sid'];
                $employeesLedger[$key]['employee_ssn'] = $empInfo['ssn'];
                $employeesLedger[$key]['employee_email'] = $empInfo['email'];
                $employeesLedger[$key]['employee_phone_number'] = $empInfo['PhoneNumber'];
                $employeesLedger[$key]['period_start_date'] = $val['start_date'];
                $employeesLedger[$key]['period_end_date'] = $val['end_date'];
                $employeesLedger[$key]['imported_at'] = $val['created_at'];
                $employeesLedger[$key]['journal_entry_number'] = $val['general_entry_number'];

                $departments = !empty($teamDepartment['departments']) ? implode(',', array_column($teamDepartment['departments'], 'name')) : '';
                $employeesLedger[$key]['department'] = $departments;

                $teams = !empty($teamDepartment['teams']) ? implode(',', array_column($teamDepartment['teams'], 'name')) : '';
                $employeesLedger[$key]['team'] = $teams;
            } else {
                $employeesLedger[$key]['department'] = '';
                $employeesLedger[$key]['team'] = '';
            }
        }

        return $employeesLedger;
    }

    //
    public function getEmmployeeInfo($emp_id)
    {
        $this->db->select('first_name, last_name, email, access_level, job_title, is_executive_admin, access_level_plus, pay_plan_flag, timezone,middle_name,employee_number,ssn,PhoneNumber,sid');
        $this->db->where('sid', $emp_id);
        return $this->db->get('users')->row_array();
    }



    //
    public function getledgerBreakdown($sId, $isRegular, $isRegularEmployee, $isExternal)
    {

        $brakdownData['brakdowndata'] = [];
        if ($isRegular == 1) {
            $this->db->select('totals');
            $this->db->where('sid', $sId);
            $record = $this->db->get('payrolls.regular_payrolls')->row_array();
            if (!empty($record)) {
                $brakdownData['brakdowndata'] = json_decode($record['totals'], true);
            }
        }

        if ($isRegularEmployee == 1) {
            $this->db->select('data_json');
            $this->db->where('sid', $sId);
            $record = $this->db->get('payrolls.regular_payrolls_employees')->row_array();
            if (!empty($record)) {
                $brakdownData['brakdowndata'] = json_decode($record['data_json'], true);
            }
        }



        if ($isExternal == 1) {
            $this->db->select('external_payroll_items,applicable_earnings,applicable_benefits,applicable_taxes');
            $this->db->where('sid', $sId);
            $record = $this->db->get('payrolls.external_payrolls')->row_array();

            if (!empty($record)) {
                $brakdownData['brakdowndata']['external_payroll_items'] = json_decode($record['external_payroll_items'], true);
                $brakdownData['brakdowndata']['applicable_earnings'] = json_decode($record['applicable_earnings'], true);
                $brakdownData['brakdowndata']['applicable_benefits'] = json_decode($record['applicable_benefits'], true);
                $brakdownData['brakdowndata']['applicable_taxes'] = json_decode($record['applicable_taxes'], true);
            }
        }

        return $brakdownData['brakdowndata'];
    }
}
