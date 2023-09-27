<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_model', 'Payroll_model');
class Company_benefits_model extends Payroll_model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * get all benefit categories
     *
     * @param bool $makeIndex
     * @return array
     */
    public function getBenefitCategories(bool $makeIndex = false): array
    {
        $records = $this->db
            ->select('
                sid,
                name
            ')
            ->order_by('name', 'ASC')
            ->get('benefit_categories')
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        //
        if ($makeIndex) {
            //
            $tmp = [];
            //
            foreach ($records as $record) {
                $tmp[$record['sid']] = $record;
            }
            //
            $records = $tmp;
            //
            unset($tmp);
        }
        //
        return $records;
    }

    /**
     * get all benefits
     *
     * @return array
     */
    public function getStoreBenefits(): array
    {
        $records = $this->db
            ->select('
                sid,
                name,
                benefit_type,
                benefit_categories_sid
            ')
            ->order_by('name', 'ASC')
            ->get('benefits')
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        //
        $categories = $this->getBenefitCategories(true);
        //
        $tmp = [];
        //
        foreach ($records as $key => $value) {
            //
            $slug = $categories[$value['benefit_categories_sid']]['name'];
            //
            if (!isset($tmp[$slug])) {
                $tmp[$slug] = [];
            }
            //
            unset($value['benefit_categories_sid']);
            //
            $tmp[$slug][] = $value;
        }
        //
        return $tmp;
    }

    /**
     * get all benefit ids
     *
     * @return array
     */
    public function getBenefitsTypeIds(): array
    {
        $records = $this->db
            ->select('
                benefit_type,
                name
            ')
            ->get('benefits')
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        //
        $tmp = [];
        //
        foreach ($records as $value) {
            $tmp[$value['benefit_type']] = $value['name'];
        }
        //
        return $tmp;
    }

    /**
     * get all benefits
     *
     * @param int $companyId
     * @return array
     */
    public function getBenefits(int $companyId): array
    {
        $records = $this->db
            ->select('
                sid,
                description,
                active,
                deletable,
                responsible_for_employee_w2,
                responsible_for_employer_taxes,
                benefit_type
            ')
            ->where('company_sid', $companyId)
            ->order_by('sid', 'DESC')
            ->get('payrolls.company_benefits')
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        //
        $benefits = $this->getBenefitsTypeIds();
        //
        $tmp = [];
        //
        foreach ($records as $key => $value) {
            // get the benefit employees
            $records[$key]['benefit_type'] = $benefits[$value['benefit_type']];
            $records[$key]['employee_count'] = $this->db
                ->where('company_benefit_sid', $value['sid'])
                ->count_all_results('payrolls.employee_benefits');
        }
        //
        unset($tmp);
        //
        return $records;
    }

    /**
     * get company benefit by id
     *
     * @param int $benefitId
     * @return array
     */
    public function getCompanyBenefitById(int $benefitId): array
    {
        return $this->db
            ->select('
                sid,
                description,
                active,
                responsible_for_employee_w2,
                responsible_for_employer_taxes,
                benefit_type
            ')
            ->where('sid', $benefitId)
            ->get('payrolls.company_benefits')
            ->row_array();
    }

    /**
     * get payroll employees
     *
     * @param int $companyId
     * @param int $benefitId Optional
     * @return array
     */
    public function getFilteredPayrollEmployees(int $companyId, int $benefitId = 0): array
    {
        $employees = $this->getPayrollEmployees($companyId);
        //
        if (!$employees) {
            return $employees;
        }
        //
        $employees = array_filter($employees, function ($employee) {
            return $employee['is_onboard'];
        });
        //
        if ($employees && $benefitId != 0) {
            // get benefit employees
            $benefitEmployees = $this->getBenefitEmployeeById($benefitId);
            //
            if ($benefitEmployees) {
                $benefitEmployees = array_column($benefitEmployees, 'employee_sid');
                //
                $newEmployees = [];
                //
                foreach ($employees as $value) {
                    if (!in_array($value['id'], $benefitEmployees)) {
                        $newEmployees[] = $value;
                    }
                }
                //
                $employees = $newEmployees;
            }
        }
        //
        return $employees;
    }

    /**
     * get the benefit employees
     *
     * @param int $benefitId
     * @return array
     */
    public function getBenefitEmployeeById(int $benefitId): array
    {
        return $this->db
            ->select('
                ' . (getUserFields()) . '
                payrolls.employee_benefits.sid,
                payrolls.employee_benefits.employee_sid,
                payrolls.employee_benefits.employee_deduction,
                payrolls.employee_benefits.company_contribution,
                payrolls.employee_benefits.active
            ')
            ->join(
                'users',
                'users.sid = payrolls.employee_benefits.employee_sid',
                'inner'
            )
            ->where('payrolls.employee_benefits.company_benefit_sid', $benefitId)
            ->order_by('payrolls.employee_benefits.sid', 'DESC')
            ->get('payrolls.employee_benefits')
            ->result_array();
    }

    /**
     * get the employee benefit by id
     *
     * @param int $employeeBenefitId
     * @return array
     */
    public function getEmployeeBenefitById(int $employeeBenefitId): array
    {
        return $this->db
            ->select('
                ' . (getUserFields()) . '
                payrolls.employee_benefits.sid,
                payrolls.employee_benefits.employee_deduction,
                payrolls.employee_benefits.company_contribution,
                payrolls.employee_benefits.company_benefit_sid,
                payrolls.employee_benefits.active
            ')
            ->join(
                'users',
                'users.sid = payrolls.employee_benefits.employee_sid',
                'inner'
            )
            ->where('payrolls.employee_benefits.sid', $employeeBenefitId)
            ->get('payrolls.employee_benefits')
            ->row_array();
    }

    /**
     * get the gusto benefit id
     *
     * @param int $benefitTypeId
     * @return array
     */
    private function getBenefitId(int $benefitTypeId): int
    {
        return $this->db
            ->select('benefit_type')
            ->where('sid', $benefitTypeId)
            ->get('benefits')
            ->row_array()['benefit_type'];
    }

    // Gusto calls
    /**
     * creates a company benefit
     *
     * @param array $data
     * @param int   $companyId
     * @return array
     */
    public function createBenefits(array $data, int $companyId): array
    {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // prepare request array
        $request = [];
        $request['benefit_type'] = $this->getBenefitId($data['benefit_type']);
        $request['active'] = $data['active'] == 'yes' ? true : false;
        $request['description'] = $data['description'];
        // $request['responsible_for_employer_taxes'] = $data['employer_taxes'] == 'yes' ? true : false;
        // $request['responsible_for_employee_w2'] = $data['employee_w2'] == 'yes' ? true : false;
        //
        $gustoResponse = gustoCall(
            'createCompanyBenefit',
            $companyDetails,
            $request,
            "POST"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        $ins = [];
        $ins['gusto_uuid'] = $gustoResponse['uuid'];
        $ins['gusto_version'] = $gustoResponse['version'];
        $ins['benefit_type'] = $gustoResponse['benefit_type'];
        $ins['description'] = $gustoResponse['description'];
        $ins['active'] = $gustoResponse['active'];
        $ins['responsible_for_employer_taxes'] = $gustoResponse['responsible_for_employer_taxes'];
        $ins['responsible_for_employee_w2'] = $gustoResponse['responsible_for_employee_w2'];
        $ins['deletable'] = $gustoResponse['deletable'];
        $ins['supports_percentage_amounts'] = $gustoResponse['supports_percentage_amounts'];
        $ins['company_sid'] = $companyId;
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        //
        $this->db->insert(
            'payrolls.company_benefits',
            $ins
        );
        //
        return [
            'success' => true,
            'msg' => 'You have successfully created a benefit.'
        ];
    }

    /**
     * updates a company benefit
     *
     * @param array $data
     * @param int   $benefitId
     * @return array
     */
    public function updateBenefits(array $data, int $benefitId): array
    {
        // get benefit details
        $benefit = $this->db
            ->select('
                gusto_uuid,
                gusto_version,
                company_sid
            ')
            ->where('sid', $benefitId)
            ->get('payrolls.company_benefits')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($benefit['company_sid']);
        // prepare request array
        $request = [];
        $request['version'] = $benefit['gusto_version'];
        $request['active'] = $data['active'] == 'yes' ? true : false;
        $request['description'] = $data['description'];
        //
        $companyDetails['other_uuid'] = $benefit['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            'updateCompanyBenefit',
            $companyDetails,
            $request,
            "PUT"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        $upd = [];
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['description'] = $gustoResponse['description'];
        $upd['active'] = $gustoResponse['active'];
        $upd['deletable'] = $gustoResponse['deletable'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $benefitId)
            ->update(
                'payrolls.company_benefits',
                $upd
            );
        //
        return [
            'success' => true,
            'msg' => 'You have successfully updated a benefit.'
        ];
    }

    /**
     * deletes a company benefit
     *
     * @param int   $benefitId
     * @return array
     */
    public function deleteBenefits(int $benefitId): array
    {
        // get benefit details
        $benefit = $this->db
            ->select('
                gusto_uuid,
                gusto_version,
                company_sid
            ')
            ->where('sid', $benefitId)
            ->get('payrolls.company_benefits')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($benefit['company_sid']);
        //
        $companyDetails['other_uuid'] = $benefit['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            'deleteCompanyBenefit',
            $companyDetails,
            [],
            "DELETE"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }

        //
        $this->db
            ->where('sid', $benefitId)
            ->delete(
                'payrolls.company_benefits'
            );
        //
        return [
            'success' => true,
            'msg' => 'You have successfully deleted a benefit.'
        ];
    }

    /**
     * add employees to benefits
     *
     * @param int   $benefitId
     * @param array $data
     * @return array
     */
    public function addEmployeesToBenefit(int $benefitId, array $data): array
    {
        // get benefit details
        $benefit = $this->db
            ->select('
                gusto_uuid,
                company_sid
            ')
            ->where('sid', $benefitId)
            ->get('payrolls.company_benefits')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($benefit['company_sid']);
        // prepare call
        $request = [];
        $request['company_benefit_uuid'] = $benefit['gusto_uuid'];
        $request['active'] = true;
        if ($data['employee_deductions']) {
            $request['employee_deduction'] = _a($data['employee_deductions'], '');
        }
        //
        if ($data['company_contribution']) {
            $request['contribution'] = [
                'value' => _a($data['company_contribution'], ''),
                'type' => 'amount'
            ];
        }
        //
        foreach ($data['employees'] as $value) {
            // get benefit details
            $gustoEmployee = $this->db
                ->select('
                    gusto_uuid
                ')
                ->where('employee_sid', $value)
                ->get('gusto_companies_employees')
                ->row_array();
            //
            $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
            //
            $gustoResponse = gustoCall(
                'createEmployeeBenefit',
                $companyDetails,
                $request,
                "POST"
            );
            // check for errors
            $errors = hasGustoErrors($gustoResponse);
            // errors found
            if ($errors) {
                return $errors;
            }
            //
            $ins = [];
            $ins['company_benefit_sid'] = $benefitId;
            $ins['employee_sid'] = $value;
            $ins['gusto_uuid'] = $gustoResponse['uuid'];
            $ins['gusto_version'] = $gustoResponse['version'];
            $ins['active'] = $gustoResponse['active'];
            $ins['employee_deduction'] = $gustoResponse['employee_deduction'];
            $ins['contribution'] = json_encode($gustoResponse['contribution'], true);
            $ins['company_contribution'] = $gustoResponse['contribution']['value'];
            $ins['deduct_as_percentage'] = $gustoResponse['deduct_as_percentage'];
            $ins['catch_up'] = $gustoResponse['catch_up'];
            $ins['limit_option'] = $gustoResponse['limit_option'];
            $ins['elective'] = $gustoResponse['elective'];
            $ins['company_contribution_annual_maximum'] = $gustoResponse['company_contribution_annual_maximum'];
            $ins['coverage_amount'] = $gustoResponse['coverage_amount'];
            $ins['coverage_salary_multiplier'] = $gustoResponse['coverage_salary_multiplier'];
            $ins['deduction_reduces_taxable_income'] = $gustoResponse['deduction_reduces_taxable_income'];
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->db
                ->insert(
                    'payrolls.employee_benefits',
                    $ins
                );
        }
        //
        return [
            'success' => true,
            'msg' => 'You have successfully saved a benefit.'
        ];
    }

    /**
     * update employee benefit
     *
     * @param int   $companyId
     * @param int   $employeeBenefitId
     * @param array $data
     * @return array
     */
    public function updateEmployeeBenefit(
        int $companyId,
        int $employeeBenefitId,
        array $data
    ): array {
        // get benefit details
        $employeeBenefit = $this->db
            ->select('
                gusto_uuid,
                gusto_version
            ')
            ->where('sid', $employeeBenefitId)
            ->get('payrolls.employee_benefits')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        $companyDetails['other_uuid'] = $employeeBenefit['gusto_uuid'];
        // prepare call
        $request = [];
        $request['version'] = $employeeBenefit['gusto_version'];
        $request['active'] = $data['active'] == 'yes' ? true : false;
        if ($data['employee_deductions']) {
            $request['employee_deduction'] = _a($data['employee_deductions'], '');
        }
        //
        if ($data['company_contribution']) {
            $request['contribution'] = [
                'value' => _a($data['company_contribution'], ''),
                'type' => 'amount'
            ];
        }
        //
        $gustoResponse = gustoCall(
            'updateEmployeeBenefit',
            $companyDetails,
            $request,
            "PUT"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        $ins = [];
        $ins['gusto_version'] = $gustoResponse['version'];
        $ins['active'] = $gustoResponse['active'];
        $ins['employee_deduction'] = $gustoResponse['employee_deduction'];
        $ins['contribution'] = json_encode($gustoResponse['contribution'], true);
        $ins['company_contribution'] = $gustoResponse['contribution']['value'];
        $ins['deduct_as_percentage'] = $gustoResponse['deduct_as_percentage'];
        $ins['catch_up'] = $gustoResponse['catch_up'];
        $ins['limit_option'] = $gustoResponse['limit_option'];
        $ins['elective'] = $gustoResponse['elective'];
        $ins['company_contribution_annual_maximum'] = $gustoResponse['company_contribution_annual_maximum'];
        $ins['coverage_amount'] = $gustoResponse['coverage_amount'];
        $ins['coverage_salary_multiplier'] = $gustoResponse['coverage_salary_multiplier'];
        $ins['deduction_reduces_taxable_income'] = $gustoResponse['deduction_reduces_taxable_income'];
        $ins['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $employeeBenefitId)
            ->update(
                'payrolls.employee_benefits',
                $ins
            );
        //
        return [
            'success' => true,
            'msg' => 'You have successfully updated a benefit.'
        ];
    }

    /**
     * delete employee benefit
     *
     * @param int   $companyId
     * @param int   $employeeBenefitId
     * @return array
     */
    public function deleteEmployeeBenefit(
        int $companyId,
        int $employeeBenefitId
    ): array {
        // get benefit details
        $employeeBenefit = $this->db
            ->select('
                gusto_uuid,
                company_benefit_sid,
            ')
            ->where('sid', $employeeBenefitId)
            ->get('payrolls.employee_benefits')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        $companyDetails['other_uuid'] = $employeeBenefit['gusto_uuid'];
        //
        $gustoResponse = gustoCall(
            'deleteEmployeeBenefit',
            $companyDetails,
            [],
            "DELETE"
        );
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        // errors found
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where('sid', $employeeBenefitId)
            ->delete(
                'payrolls.employee_benefits'
            );
        //
        return [
            'success' => true,
            'msg' => 'You have successfully deleted employee from benefit.',
            'key' => $employeeBenefit['company_benefit_sid']
        ];
    }
}
