<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_model', 'Payroll_model');
/**
 * Garnishments model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Garnishments_model extends Payroll_model
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
     * get all garnishments
     *
     * @param int $employeeId
     * @param int $companyId
     * @return array
     */
    public function getAllGarnishments(int $employeeId, int $companyId): array
    {
        return $this->db
            ->select('
                payrolls.employee_garnishments.*
            ')
            ->join(
                'users',
                'users.sid = payrolls.employee_garnishments.employee_sid',
                'inner'
            )
            ->where('payrolls.employee_garnishments.employee_sid', $employeeId)
            ->where('users.parent_sid', $companyId)
            ->order_by('payrolls.employee_garnishments.sid', 'DESC')
            ->get('payrolls.employee_garnishments')
            ->result_array();
    }

    /**
     * get garnishment by id
     *
     * @param int $employeeId
     * @param int $garnishmentId
     * @return array
     */
    public function getGarnishmentById(int $employeeId, int $garnishmentId): array
    {
        return $this->db
            ->select('
                payrolls.employee_garnishments.*
            ')
            ->where('payrolls.employee_garnishments.employee_sid', $employeeId)
            ->where('payrolls.employee_garnishments.sid', $garnishmentId)
            ->get('payrolls.employee_garnishments')
            ->row_array();
    }

    /**
     * get beneficiary info by id
     *
     * @param int $garnishmentId
     * @param string $method
     * 
     * @return array|int
     */
    public function getBeneficiaryInfoById(int $garnishmentId, string $method = "row_array")
    {
        return $this->db
            ->select('
                payrolls.employee_garnishments_beneficiary.*
            ')
            ->where('payrolls.employee_garnishments_beneficiary.garnishment_sid', $garnishmentId)
            ->get('payrolls.employee_garnishments_beneficiary')
            ->$method();
    }

    /**
     * get employee with company details
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployee(int $employeeId): array
    {
        //
        $record = $this->db
            ->select(getUserFields())
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        //
        if ($record) {
            return [
                'name' => remakeEmployeeName($record, true)
            ];
        }
        //
        return $record;
    }

    // Gusto calls

    /**
     * save garnishment
     *
     * @param int $loggedInCompanyId
     * @param int $loggedInEmployerId
     * @param int $employeeId
     * @param array $data
     * @return array
     */
    public function saveGarnishment(
        int $loggedInCompanyId,
        int $loggedInEmployerId,
        int $employeeId,
        array $data
    ): array {
        // get single payroll
        $gustoEmployee = $this->db
            ->select('
                gusto_uuid
            ')
            ->where('employee_sid', $employeeId)
            ->get('gusto_companies_employees')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($loggedInCompanyId);
        // add payroll uuid
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // prepare array
        $request = [];
        $request = $data;
        // reset
        $request['active'] = $request['active'] == 'yes' ? true : false;
        $request['court_ordered'] = $request['court_ordered'] == 'yes' ? true : false;
        $request['recurring'] = $request['recurring'] == 'yes' ? true : false;
        $request['deduct_as_percentage'] = $request['deduct_as_percentage'] == 'yes' ? true : false;
        // make call
        
        /*
        $gustoResponse = gustoCall(
            'createGarnishment',
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
        $ins['active'] = $gustoResponse['active'];
        $ins['description'] = $gustoResponse['description'];
        $ins['court_ordered'] = $gustoResponse['court_ordered'];
        $ins['times'] = $gustoResponse['times'];
        $ins['deduct_as_percentage'] = $gustoResponse['deduct_as_percentage'];
        $ins['recurring'] = $gustoResponse['recurring'];
        $ins['annual_maximum'] = $gustoResponse['annual_maximum'];
        $ins['pay_period_maximum'] = $gustoResponse['pay_period_maximum'];
        $ins['amount'] = $gustoResponse['amount'];
        $ins['employee_sid'] = $employeeId;
        $ins['last_changed_by'] = $loggedInEmployerId;
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();

        */


        $ins = [];
        $ins['gusto_uuid'] ='122';
        $ins['gusto_version'] = "1.2";
        $ins['active'] = $request['active'];
        $ins['description'] = $request['description'];
        $ins['court_ordered'] = $request['court_ordered'];
        $ins['times'] = $request['times'];
        $ins['deduct_as_percentage'] = $request['deduct_as_percentage'];
        $ins['recurring'] = $request['recurring'];
        $ins['annual_maximum'] = $request['annual_maximum'];
        $ins['pay_period_maximum'] = $request['pay_period_maximum'];
        $ins['amount'] = $request['amount'];
        $ins['employee_sid'] = $employeeId;
        $ins['last_changed_by'] = $loggedInEmployerId;
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();

        //
        $this->db
            ->insert(
                'payrolls.employee_garnishments',
                $ins
            );
        //
        return ['success' => true, 'msg' => 'You have successfully created a garnishment.', 'Id' => $this->db->insert_id()];
    }

    /**
     * update garnishment
     *
     * @param int $loggedInCompanyId
     * @param int $loggedInEmployerId
     * @param int $employeeId
     * @param int $garnishmentId
     * @param array $data
     * @return array
     */
    public function updateGarnishment(
        int $loggedInCompanyId,
        int $loggedInEmployerId,
        int $employeeId,
        int $garnishmentId,
        array $data
    ): array {
        // get single payroll
        $gustoGarnishment = $this->db
            ->select('
                gusto_uuid,
                gusto_version
            ')
            ->where('sid', $garnishmentId)
            ->get('payrolls.employee_garnishments')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($loggedInCompanyId);
        // add payroll uuid
        $companyDetails['other_uuid'] = $gustoGarnishment['gusto_uuid'];
        // prepare array
        $request = [];
        $request = $data;
        $request['version'] = $gustoGarnishment['gusto_version'];
        // reset
        $request['active'] = $request['active'] == 'yes' ? true : false;
        $request['court_ordered'] = $request['court_ordered'] == 'yes' ? true : false;
        $request['recurring'] = $request['recurring'] == 'yes' ? true : false;
        $request['deduct_as_percentage'] = $request['deduct_as_percentage'] == 'yes' ? true : false;
        // make call
        /*
        $gustoResponse = gustoCall(
            'updateGarnishment',
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
        $upd['active'] = $gustoResponse['active'];
        $upd['description'] = $gustoResponse['description'];
        $upd['court_ordered'] = $gustoResponse['court_ordered'];
        $upd['times'] = $gustoResponse['times'];
        $upd['deduct_as_percentage'] = $gustoResponse['deduct_as_percentage'];
        $upd['recurring'] = $gustoResponse['recurring'];
        $upd['annual_maximum'] = $gustoResponse['annual_maximum'];
        $upd['pay_period_maximum'] = $gustoResponse['pay_period_maximum'];
        $upd['amount'] = $gustoResponse['amount'];
        $upd['last_changed_by'] = $loggedInEmployerId;
        $upd['updated_at'] = getSystemDate();

        */


        $upd = [];
        $upd['gusto_version'] = $request['version'];
        $upd['active'] = $request['active'];
        $upd['description'] = $request['description'];
        $upd['court_ordered'] = $request['court_ordered'];
        $upd['times'] = $request['times'];
        $upd['deduct_as_percentage'] = $request['deduct_as_percentage'];
        $upd['recurring'] = $request['recurring'];
        $upd['annual_maximum'] = $request['annual_maximum'];
        $upd['pay_period_maximum'] = $request['pay_period_maximum'];
        $upd['amount'] = $request['amount'];
        $upd['last_changed_by'] = $loggedInEmployerId;
        $upd['updated_at'] = getSystemDate();


        //
        $this->db
            ->where('sid', $garnishmentId)
            ->update(
                'payrolls.employee_garnishments',
                $upd
            );
        //
        return ['success' => true, 'msg' => 'You have successfully updated a garnishment.'];
    }

    
    /**
     * update garnishment Beneficiary
     *
     * @param int $garnishmentId
     * @param array $data
     * @return array
     */
    public function updateGarnishmentBeneficiary(
        int $garnishmentId,
        array $data
    ): array {
        //
        if ($this->getBeneficiaryInfoById($garnishmentId, 'num_rows')) {
            $this->db
            ->where('garnishment_sid', $garnishmentId)
            ->update(
                'payrolls.employee_garnishments_beneficiary',
                $data
            );
        } else {
            $ins = $data;
            $ins['garnishment_sid'] = $garnishmentId;
            //
            $this->db
            ->insert(
                'payrolls.employee_garnishments_beneficiary',
                $ins
            );
        }
        //
        return ['success' => true, 'msg' => 'You have successfully updated a garnishment.'];
    }
}
