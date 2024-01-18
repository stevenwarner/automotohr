<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_base_model', 'Payroll_base_model');
/**
 * Regular payroll model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class W4_payroll_model extends Payroll_base_model
{
    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
    }

    /**
     * sync a company's w4
     *
     * @param int $companyId
     */
    public function syncW4s(int $companyId)
    {
        // get all signed w4s
        $records = $this->db
            ->select("
                employer_sid,
                marriage_status,
                mjsw_status,
                claim_total_amount,
                other_income,
                other_deductions,
                additional_tax,
            ")
            ->where([
                "company_sid" => $companyId,
                "user_type" => "employee",
                "status" => 1,
                "user_consent" => 1
            ])
            ->get("form_w4_original")
            ->result_array();
        //
        if (!$records) {
            exit("No w4s found!");
        }
        $this->loadPayrollLibrary($companyId);
        //
        foreach ($records as $v0) {
            //
            $dataArray = [];
            $dataArray["filing_status"] = "Single";
            if ($v0["marriage_status"] == "jointly") {
                $dataArray["filing_status"] = "Married";
            } elseif ($v0["marriage_status"] == "head") {
                $dataArray["filing_status"] = "Head of Household";
            }
            $dataArray["two_jobs"] = $v0["mjsw_status"] == "similar_pay" ? "yes" : "no";
            $dataArray["dependents_amount"] = $v0["claim_total_amount"];
            $dataArray["extra_withholding"] = $v0["additional_tax"];
            $dataArray["other_income"] = $v0["other_income"];
            $dataArray["deductions"] = $v0["other_deductions"];
            $dataArray["w4_data_type"] = "rev_2020_w4";
            //
            $gustoFederalTax = $this->db
                ->where('employee_sid', $v0["employer_sid"])
                ->count_all_results('gusto_employees_federal_tax');
            $method = !$gustoFederalTax ? 'createEmployeeFederalTax' : 'updateEmployeeFederalTax';
            // let's update employee's home address
            $this
                ->$method(
                    $v0["employer_sid"],
                    $dataArray
                );
            //
        }
        exit("Sync completed");
    }

    /**
     * sync a company's employee
     *
     * @param int $employeeId
     * @return array
     */
    public function syncW4ForEmployee(int $employeeId)
    {
        // get all signed w4s
        $records = $this->db
            ->select("
                employer_sid,
                marriage_status,
                mjsw_status,
                claim_total_amount,
                other_income,
                other_deductions,
                additional_tax,
                company_sid
            ")
            ->where([
                "employer_sid" => $employeeId,
                "user_type" => "employee",
                "status" => 1,
                "user_consent" => 1
            ])
            ->get("form_w4_original")
            ->result_array();
        //
        if (!$records) {
            return [
                "errors" => [
                    "No record found."
                ]
            ];
        }
        $this->loadPayrollLibrary($records[0]["company_sid"]);
        //
        foreach ($records as $v0) {
            //
            $dataArray = [];
            $dataArray["filing_status"] = "Single";
            if ($v0["marriage_status"] == "jointly") {
                $dataArray["filing_status"] = "Married";
            } elseif ($v0["marriage_status"] == "head") {
                $dataArray["filing_status"] = "Head of Household";
            }
            $dataArray["two_jobs"] = $v0["mjsw_status"] == "similar_pay" ? true : false;
            $dataArray["dependents_amount"] = $v0["claim_total_amount"];
            $dataArray["extra_withholding"] = $v0["additional_tax"];
            $dataArray["other_income"] = $v0["other_income"];
            $dataArray["deductions"] = $v0["other_deductions"];
            $dataArray["w4_data_type"] = "rev_2020_w4";
            //
            $gustoFederalTax = $this->db
                ->where('employee_sid', $v0["employer_sid"])
                ->count_all_results('gusto_employees_federal_tax');
            $method = !$gustoFederalTax ? 'createEmployeeFederalTax' : 'updateEmployeeFederalTax';
            // let's update employee's home address
            $this
                ->$method(
                    $v0["employer_sid"],
                    $dataArray
                );
            //
        }
        return [
            "success" => [
                "Successfully synced."
            ]
        ];
    }

    /**
     * create employee's federal tax on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    private function createEmployeeFederalTax(
        int $employeeId,
        array $data
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
        if (!$gustoEmployee) {
            return ["errors" => ["Employee not found."]];
        }
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'createFederalTax',
            $companyDetails,
            [],
            'GET'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // set update array
        $upd = [];
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['employee_sid'] = $employeeId;
        $upd['filing_status'] = $gustoResponse['filing_status'];
        $upd['extra_withholding'] = $gustoResponse['extra_withholding'];
        $upd['two_jobs'] = $gustoResponse['two_jobs'];
        $upd['dependents_amount'] = $gustoResponse['dependents_amount'];
        $upd['other_income'] = $gustoResponse['other_income'];
        $upd['deductions'] = $gustoResponse['deductions'];
        $upd['w4_data_type'] = $data['w4_data_type'];
        $upd['created_at'] = $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->insert('gusto_employees_federal_tax', $upd);
        //
        return $this->updateEmployeeFederalTax($employeeId, $data);
    }

    /**
     * create employee's federal tax on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    private function updateEmployeeFederalTax(
        int $employeeId,
        array $data
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
        if (!$gustoEmployee) {
            return ["errors" => ["Employee not found."]];
        }
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // let's make request
        $request = [];
        $request['version'] = $this->db
            ->select('gusto_version')
            ->where('employee_sid', $employeeId)
            ->get('gusto_employees_federal_tax')
            ->row_array()['gusto_version'];
        $request['filing_status'] = $data['filing_status'];
        $request['extra_withholding'] = $data['extra_withholding'];
        $request['two_jobs'] = $data['two_jobs'];
        $request['dependents_amount'] = $data['dependents_amount'];
        $request['other_income'] = $data['other_income'];
        $request['deductions'] = $data['deductions'];
        $request['w4_data_type'] = 'rev_2020_w4';
        // response
        $gustoResponse = gustoCall(
            'createFederalTax',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // set update array
        $upd = [];
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['filing_status'] = $gustoResponse['filing_status'];
        $upd['extra_withholding'] = $gustoResponse['extra_withholding'];
        $upd['two_jobs'] = $gustoResponse['two_jobs'];
        $upd['dependents_amount'] = $gustoResponse['dependents_amount'];
        $upd['other_income'] = $gustoResponse['other_income'];
        $upd['deductions'] = $gustoResponse['deductions'];
        $upd['w4_data_type'] = $request['w4_data_type'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('employee_sid', $employeeId)
            ->update('gusto_employees_federal_tax', $upd);
        //
        return ['success' => true];
    }
}
