<?php

class Payroll_model extends CI_Model{
	//
	private $tables;

	/**
	 * 
	 */
	function __construct(){
		$this->tables = []; 
		$this->tables['P'] = 'payrolls'; 
		$this->tables['PH'] = 'payroll_history'; 
		$this->tables['PC'] = 'payroll_companies'; 
		$this->tables['PCE'] = 'payroll_employees'; 
		$this->tables['PCTD'] = 'payroll_company_tax_details'; 
		$this->tables['PCBA'] = 'payroll_company_bank_accounts'; 
		// 
		$this->tables['U'] = 'users'; 
		$this->tables['PayrollCompanyAdmin'] = 'payroll_company_admin'; 
		$this->tables['PayrollCompanyLocations'] = 'payroll_company_locations'; 
		$this->tables['JCI'] = 'job_category_industries'; 
		$this->tables['PEJ'] = 'payroll_employee_jobs'; 
		$this->tables['PEJC'] = 'payroll_employee_job_compensations'; 
		$this->tables['PEFT'] = 'payroll_employee_federal_tax'; 
		$this->tables['PEST'] = 'payroll_employee_state_tax'; 
		$this->tables['PEPM'] = 'payroll_employee_payment_method'; 
		$this->tables['PEBA'] = 'payroll_employee_bank_accounts'; 
		$this->tables['BAD'] = 'bank_account_details'; 
		$this->tables['PCPP'] = 'payroll_company_pay_periods'; 
		$this->tables['PSI'] = 'payroll_signatory_information'; 
		$this->tables['PSI'] = 'payroll_signatory_information'; 
		$this->tables['CM'] = 'company_modules'; 
	}    

	/**
	 * Check wether the payroll is activated
	 * against aspecific company
	 * or not
	 * 
	 * @param Integer $companyId
	 * @param Booleon $doCount
	 * 
	 * @return
	 */
	function GetCompanyPayrollDetails($companyId, $doCount = FALSE){
		//
		$query = $this
		->db
		->where('company_sid', $companyId)
		->from($this->tables['PC']);
		//
		if($doCount){
			return $query->count_all_results();
		}
		//
		return $query->get()->row_array();
	}
	
	/**
	 * Get the primary admin of a company
	 * 
	 * @param Integer $companyId
	 * 
	 * @return
	 */
	function GetCompanyPrimaryAdmin($companyId){
		//
		$query = $this
		->db
		->select('first_name, last_name, email, PhoneNumber')
		->where('parent_sid', $companyId)
		->where('is_primary_admin', 1)
		->from($this->tables['U']);
		//
		return $query->get()->row_array();
	}


	// Deprecated functions
	// need to verify and delete
	
	/**
	 * 
	 */
	function AddCompany($ia){
		//
		$this->db->insert(
			$this->tables['PC'],
			$ia
		);
		//
		return $this->db->insert_id();
	}
	
	/**
	 * 
	 */
	function AddEmployeeCompany($ia){
		//
		$this->db->insert(
			$this->tables['PCE'],
			$ia
		);
		//
		return $this->db->insert_id();
	}
   
	/**
	 * 
	 */
	function GetCompany($companyId, $columns){
		//
		$query = 
		$this->db
		->where('company_sid', $companyId)
		->select($columns)
		->get($this->tables['PC']);
		//
		$record = $query->row_array();
		//
		$query = $query->free_result();
		//
		return $record;
	}
	
	/**
	 * 
	 */
	function EmployeeAlreadyAddedToGusto($employeeId, $columns){
		//
		$query = 
		$this->db
		->select($columns)
		->where('employee_sid', $employeeId)
		->get($this->tables['PCE']);
		//
		$record = $query->row_array();
		//
		$query = $query->free_result();
		//
		return $record;
	}
	
	/**
	 * 
	 */
	function UpdateToken($array, $where){
		//
		$this->db
		->where($where)
		->update($this->tables['PC'], $array);
	}

	//
	/**
	 * 
	 */
	function UpdateCompanyEIN($companyId, $array){
		//
		$this->db
		->where('sid', $companyId)
		->update($this->tables['U'], $array);
	}
   
	/**
	 * 
	 */
	function UpdateCompany($companyId, $array){
		//
		$this->db
		->where('sid', $companyId)
		->update($this->tables['U'], $array);
	}

	/**
	 * 
	 */
	function UpdateEmployee($employeeId, $array){
		//
		$this->db
		->where('sid', $employeeId)
		->update($this->tables['U'], $array);
	}

	/**
	 * 
	 */
	function UpdateOndordingLevel($companyId, $array){
		//
		$this->db
		->where('company_sid', $companyId)
		->update($this->tables['PC'], $array);
	}

	//
	function GetCompaniesWithGusto(){
		//
		$query = 
		$this->db
		->select("
			{$this->tables['U']}.sid,
			{$this->tables['U']}.CompanyName,
			{$this->tables['U']}.ssn,
			{$this->tables['PC']}.gusto_company_uid,
			{$this->tables['PC']}.access_token,
			{$this->tables['PC']}.refresh_token,
			{$this->tables['PC']}.old_access_token,
			{$this->tables['PC']}.old_refresh_token,
			{$this->tables['PC']}.is_active,
			{$this->tables['PC']}.updated_at,
			{$this->tables['PC']}.created_at
		")
		->from($this->tables['U'])
		->join("{$this->tables['PC']}", "{$this->tables['PC']}.company_sid = {$this->tables['U']}.sid", 'left')
		->where("{$this->tables['U']}.parent_sid", 0)
		->where("{$this->tables['U']}.active", 1)
		->order_by("{$this->tables['U']}.CompanyName")
		->get();
		//
		$companies = $query->result_array();
		$query = $query->free_result();
		//
		return $companies;
	}

	/**
	 * 
	 */
	function UpdatePC($array, $where){
		//
		$this->db
		->where($where)
		->update($this->tables['PC'], $array);
	}

	/**
	 * 
	 */
	function UpdatePCE($array, $where){
		//
		$this->db
		->where($where)
		->update($this->tables['PCE'], $array);
	}

	/**
	 * 
	 */
	function CheckEINNumber($ein, $companyId){
		//
		return $this->db
		->where('ssn', $ein)
		->where('sid <>', $companyId)
		->count_all_results($this->tables['U']);
	}


	/**
	 * 
	 */
	function CheckAndInsertPayroll(
		$companyId,
		$employerId,
		$payrollUid,
		$payroll
	){
		//
		$isNew = false;
		$doAdd = true;
		//
		$date = date('Y-m-d H:i:s', strtotime('now'));
		// Check if the payroll already
		// been added
		if(
			!$this->db
			->where('payroll_uid', $payrollUid)
			->count_all_results($this->tables['P'])
		){
			// Let's insert the payroll
			$this->db
			->insert(
				$this->tables['P'], [
					'company_sid' => $companyId,
					'payroll_uid' => $payrollUid,
					'version' => $payroll['version'],
					'payroll_id' => $payroll['payroll_id'],
					'start_date' => $payroll['pay_period']['start_date'],
					'end_date' => $payroll['pay_period']['end_date'],
					'check_date' => $payroll['check_date'],
					'deadline_date' => $payroll['payroll_deadline'],
					'payroll_json' => json_encode($payroll),
					'is_processed' => 0,
					'created_by' => $employerId,
					'created_at' => $date,
					'updated_at' => $date
				]
			);
			//
			$isNew = true;
		} else{
			if(!empty($payroll)){
				$this->db
				->where('payroll_uid', $payrollUid)
				->update(
					$this->tables['P'], [
						'payroll_json' => json_encode($payroll)
					]
				);
			}
		}
		//
		if(!$isNew){
			// Get the last history
			$historyVersion = $this->GetPayrollHistory($payroll['payroll_id'], ['version'])['version'];
			//
			if($historyVersion == $payroll['version']){
				$doAdd = false;
			}
		}
		//
		if(!$doAdd){
			return false;
		}
		// Let's add a history
		$this->db
		->insert(
			$this->tables['PH'], [
				'payroll_id' => $payroll['payroll_id'],
				'version' => $payroll['version'],
				'created_by' => $employerId,
				'content' => json_encode($payroll),
				'created_at' => $date
			]
		);
	}


	/**
	 * 
	 */
	function GetPayrollHistory(
		$payrollId, 
		$columns = '*'
	){
		//
		$query =
		$this->db
		->select($columns)
		->where('payroll_id', $payrollId)
		->order_by('sid', 'desc')
		->get($this->tables['PH']);
		//
		$record = $query->row_array();
		$query = $query->free_result();
		//
		return $record;
	}

	/**
	 * 
	 */
	function isEmployeeOnPayroll($employeeId){
		//
		return $this->db
		->where('on_payroll', 1)
		->where('sid', $employeeId)
		->count_all_results('users');
	}


	function UpdateCompanyTax($updateArray, $whereArray){
		//
		$this->db->where($whereArray)
		->update($this->tables['PCTD'], $updateArray);
	}

	// As of 10-2021

	/**
	 * Check if company has a primary admin
	 * to handle payroll
	 * 
	 * @param integer $companyId
	 * @return
	 */
	function HasPrimaryAdmin($companyId){
		//
		return $this->db
		->where('company_sid', $companyId)
		->count_all_results($this->tables['PayrollCompanyAdmin']);
	}

	/**
	 * Insert company payroll amdin
	 * @param array $dataArray
	 * @return integer
	 */
	function InsertAdmin($dataArray){
		$this->db->insert($this->tables['PayrollCompanyAdmin'], $dataArray);
		return $this->db->insert_id();
	}

	/**
	 * Check if company has a primary admin
	 * to handle payroll
	 * 
	 * @param integer $companyId
	 * @return
	 */
	function GetPrimaryAdmin($companyId){
		//
		return $this->db
		->where('company_sid', $companyId)
		->get($this->tables['PayrollCompanyAdmin'])
		->row_array();
	}

	/**
	 * Get all active states
	 * @return
	 */
	function GetStates(){
		return $this->db
		->select('sid, state_code, state_name')
		->where('active', 1)
		->order_by('state_name')
		->get('states')
		->result_array();
	}

	/**
	 * Get company payroll details
	 * @param integer $companyId
	 * @return
	 */
	function GetPayrollCompany($companyId){
		//
		return $this->db
		->select('refresh_token, access_token, gusto_company_uid, onbording_level, onboarding_status')
		->where('company_sid', $companyId)
		->get($this->tables['PC'])
		->row_array();
	}
	
	/**
	 * Get company payroll details
	 * @param integer $companyId
	 * @return
	 */
	function GetCompanyLocations($companyId){
		//
		return $this->db
		->select('
			sid,
			street_1,
			street_2,
			city,
			state,
			zip,
			mailing_address,
			filing_address,
			phone_number,
			gusto_uuid,
			gusto_location_id
		')
		->where('company_sid', $companyId)
		->get($this->tables['PayrollCompanyLocations'])
		->result_array();
	}

	/**
	 * Get company location by ID
	 * @param integer $companyId
	 * @param integer $locationId
	 * @return
	 */
	function GetCompanyLocationById($companyId, $locationId){
		//
		return $this->db
		->select('
			street_1,
			street_2,
			city,
			state,
			zip,
			mailing_address,
			filing_address,
			phone_number,
			gusto_location_id
		')
		->where('company_sid', $companyId)
		->where('sid', $locationId)
		->get($this->tables['PayrollCompanyLocations'])
		->row_array();
	}

	/**
	 * Get company location by ID
	 * @param integer $companyId
	 * @param integer $locationId
	 * @return
	 */
	function GetCompanyFedralTaxInfo($companyId){
		//
		return $this->db
		->select('
			sid,
			ein_number,
			legal_name,
			tax_payer_type,
			filling_form,
			taxable_as_scorp
		')
		->where('company_sid', $companyId)
		->get($this->tables['PCTD'])
		->row_array();
	}

	/**
	 * Get Job Industries
	 * @return
	 */
	function GetJobIndustries(){
		//
		return $this->db
		->select('
			sid,
			industry_name
		')
		->where('status', 1)
		->get($this->tables['JCI'])
		->result_array();
	}

	/**
	 * Get company bank account info
	 * @param integer $companyId
	 * @return
	 */
	function GetCompanyGustoLocationID($companyId){
		//
		return $this->db
		->select('
			gusto_location_id
		')
		->where('company_sid', $companyId)
		->get($this->tables['PayrollCompanyLocations'])
		->row_array();
	}

	/**
	 * Get company bank account info
	 * @param integer $companyId
	 * @return
	 */
	function GetCompanyBankAccountDetail($companyId){
		//
		return $this->db
		->select('
			sid,
			routing_transaction_number,
			account_number,
			account_type
		')
		->where('company_sid', $companyId)
		->get($this->tables['BAD'])
		->row_array();
	}

	 /**
	 * Get company bank account info
	 * @param integer $companyId
	 * @return
	 */
	function GetCompanyBankAccount($companyId){
		//
		return $this->db
		->select('
			sid,
			routing_number,
			account_number,
			account_type
		')
		->where('company_sid', $companyId)
		->get($this->tables['PCBA'])
		->row_array();
	}

	 /**
	 * Get company Payroll employee
	 * @param integer $companyId
	 * @return
	 */
	function GetCompanyPayrollEmployees($companyId){
		//
		return $this->db
		->select('
			employee_sid,
			onboard_level
		')
		->where('company_sid', $companyId)
		->get($this->tables['PCE'])
		->result_array();
	}

	/**
	 * Add company location to system
	 * 
	 * @param array $insertArray
	 * @return
	 */
	function AddCompanyLocation($insertArray){
		//
		$this->db->insert(
			$this->tables['PayrollCompanyLocations'],
			$insertArray
		);
		//
		return $this->db->insert_id();
	}

	//
	function GetEmployeeJobDetails($employee_sid){
		//
		$query = 
		$this->db
		->select("
			{$this->tables['PEJ']}.sid,
			{$this->tables['PEJ']}.title,
			{$this->tables['PEJ']}.hire_date,
			{$this->tables['PEJC']}.rate,
			{$this->tables['PEJC']}.payment_unit,
			{$this->tables['PEJC']}.flsa_status,
		")
		->from($this->tables['PEJ'])
		->join("{$this->tables['PEJC']}", "{$this->tables['PEJ']}.sid = {$this->tables['PEJC']}.payroll_job_sid", 'left')
		->where("{$this->tables['PEJ']}.employee_sid", $employee_sid)
		->get();
		//
		$jobInfo = $query->row_array();
		$query = $query->free_result();
		//
		return $jobInfo;
	}

	/**
	 * Get company Payroll employee
	 * @param integer $companyId
	 * @return
	 */
	function GetWorkAddressId($employeeId){
		//
		$result = $this->db
		->select('
			work_address_sid
		')
		->where('employee_sid', $employeeId)
		->get($this->tables['PCE'])
		->row_array();

		if(!empty($result)) {
			return $result['work_address_sid'];
		} else {
			return 0;
		}
	}

	//
	function GetEmployeeFederalTaxDetails($employee_sid){
		//
		$query = 
		$this->db
		->select("
			filing_status,
			multiple_jobs,
			dependent,
			other_income,
			deductions,
			extra_withholding,
		")
		->from($this->tables['PEFT'])
		->where("employee_sid", $employee_sid)
		->get();
		//
		$taxInfo = $query->row_array();
		$query = $query->free_result();
		//
		return $taxInfo;
	}

	function checkStatePrerequisite ($employee_sid) {
		//
		$prerequisiteStatus = array(
			'work_address' => 0,
			'federal_tax' => 0,
			'status' => 'data_missing'
		);
		//
		$this->db->select('work_address,federal_tax');
        $this->db->where("employee_sid", $employee_sid);
        $record_obj = $this->db->get('payroll_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            if ($record_arr['work_address'] == 1) {
				$prerequisiteStatus['work_address'] = 1;
			}
			//
			if ($record_arr['federal_tax'] == 1) {
				$prerequisiteStatus['federal_tax'] = 1;
			}
			//
			if ($record_arr['federal_tax'] == 1 && $record_arr['work_address'] == 1) {
				$prerequisiteStatus['status'] = 'data_completed';
			}
        }
		//
		return $prerequisiteStatus;
	}

	function checkEmployeeDocumentPrerequisite ($employee_sid) {
		//
		$prerequisiteStatus = array(
			'state_tax' => 0,
			'federal_tax' => 0,
			'status' => 'data_missing',
			'payroll_employee_uuid' => ''
		);
		//
		$this->db->select('state_tax, federal_tax, payroll_employee_uuid');
        $this->db->where("employee_sid", $employee_sid);
        $record_obj = $this->db->get('payroll_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            if ($record_arr['state_tax'] == 1) {
				$prerequisiteStatus['state_tax'] = 1;
			}
			//
			if ($record_arr['federal_tax'] == 1) {
				$prerequisiteStatus['federal_tax'] = 1;
			}
			//
			if ($record_arr['federal_tax'] == 1 && $record_arr['state_tax'] == 1) {
				$prerequisiteStatus['status'] = 'data_completed';
				$prerequisiteStatus['payroll_employee_uuid'] = $record_arr['payroll_employee_uuid'];
			}
        }
		//
		return $prerequisiteStatus;
	}

	function getEmployeeUUID ($employee_sid) {
		//
		$employeeUUID = '';
		//
		$this->db->select('payroll_employee_uuid');
        $this->db->where("employee_sid", $employee_sid);
        $record_obj = $this->db->get('payroll_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            $employeeUUID = $record_arr['payroll_employee_uuid'];
        }
		//
		return $employeeUUID;
	}

	//
	function GetEmployeeStateTaxDetails($employee_sid){
		//
		$query = 
		$this->db
		->select("
			state_json
		")
		->from($this->tables['PEST'])
		->where("employee_sid", $employee_sid)
		->get();
		//
		$taxInfo = [];
		//
		if($query->num_rows){
			$taxInfo = $query->row_array();
			$query = $query->free_result();
		}
		//
		return $taxInfo;
	}

	//
	function GetEmployeePaymentMethod($employee_sid){
		//
		$query = 
		$this->db
		->select("
			payment_method,
			split_method,
			splits,
			version
		")
		->from($this->tables['PEPM'])
		->where("employee_sid", $employee_sid)
		->get();
		//
		$taxInfo = $query->row_array();
		$query = $query->free_result();
		//
		return $taxInfo;
	}

	//
	function GetEmployeeBankDetails($employee_sid){
		//
		$query = 
		$this->db
		->select("
			sid,
			routing_number,
			account_number,
			account_type,
			name
		")
		->from('payroll_employee_bank_accounts')
		->where("employee_sid", $employee_sid)
		->get();
		//
		$taxInfo = $query->result_array();
		$query = $query->free_result();
		//
		return $taxInfo;
	}

	function GetEmployeePayrollBankDetails($employee_sid){
		//
		$query = 
		$this->db
		->select("
			payroll_bank_uuid,
			name,
			routing_number,
			account_number,
			account_type,
			account_percentage,
			direct_deposit_id,
		")
		->from($this->tables['PEBA'])
		->where("employee_sid", $employee_sid)
		->where("is_deleted", 0)
		->get();
		//
		$taxInfo = $query->result_array();
		$query = $query->free_result();
		//
		return $taxInfo;
	}

	function GetEmployeeDirectDeposit($id){
		//
		$query = 
		$this->db
		->select("
			account_title,
			routing_transaction_number,
			account_number,
			account_type,
			account_percentage
		")
		->from($this->tables['BAD'])
		->where("sid", $id)
		->get();
		//
		$taxInfo = $query->row_array();
		$query = $query->free_result();
		//
		return $taxInfo;
	}

	function GetCompanyPayrollSetting($companyId){
		//
		$query = 
		$this->db
		->select("
			sid,
			frequency,
			anchor_pay_date,
			anchor_end_of_pay_period
		")
		->from($this->tables['PCPP'])
		->where("company_sid", $companyId)
		->get();
		//
		$taxInfo = $query->row_array();
		$query = $query->free_result();
		//
		return $taxInfo;
	}

	function GetSignatoryInfo($companyId){
		//
		$query = 
		$this->db
		->select("*")
		->from($this->tables['PSI'])
		->where("company_sid", $companyId)
		->get();
		//
		$taxInfo = $query->row_array();
		$query = $query->free_result();
		//
		return $taxInfo;
	}

	//
	function GetGustoCompanyData($sid){
		//
		$query = 
		$this->db
		->select("
			{$this->tables['U']}.sid,
			{$this->tables['U']}.CompanyName,
			{$this->tables['U']}.ssn,
			{$this->tables['PC']}.gusto_company_uid,
			{$this->tables['PC']}.access_token,
			{$this->tables['PC']}.refresh_token,
			{$this->tables['PC']}.old_access_token,
			{$this->tables['PC']}.old_refresh_token,
			{$this->tables['PC']}.is_active,
			{$this->tables['PC']}.updated_at,
			{$this->tables['PC']}.created_at
		")
		->from($this->tables['U'])
		->join("{$this->tables['PC']}", "{$this->tables['PC']}.company_sid = {$this->tables['U']}.sid", 'left')
		->where("{$this->tables['U']}.sid", $sid)
		->get();
		//
		$company_info = $query->row_array();
		$query = $query->free_result();
		//
		return $company_info;
	}

	//
	function GetCompanyPayrollStatus($sid) {
		$this->db->select('is_active');
		$this->db->where('company_sid', $sid);
		$this->db->where('module_sid', 7);
		
		$record_obj = $this->db->get($this->tables['CM']);
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		
		if (!empty($record_arr)) {
			return $record_arr['is_active'];
		} else {
			return 0;
		}
	}

	//
	function GetCompanyAddressInfo ($company_sid) {
		$this->db->select('Location_Country, Location_State, Location_City, Location_Address, Location_Address_2, PhoneNumber, Location_ZipCode');
		$this->db->where('sid', $company_sid);
		
		$record_obj = $this->db->get($this->tables['U']);
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		
		if (!empty($record_arr)) {
			return $record_arr;
		} else {
			return array();
		}
	}


	//
	public function InsertPayroll($table, $dataArray){
		//
		$this->db->insert($table, $dataArray);
		return $this->db->insert_id();
	}
   
	//
	public function UpdatePayroll($table, $dataArray, $whereArray){
		//
		$this->db
		->where($whereArray)
		->update($table, $dataArray);
	}


	//--------------------------------

	public function GetCompanyOnboardDetails($companyId){
		//
		return
		$this->db
		->select('
			users.on_payroll,
			users.Location_City,
			users.Location_Address,
			users.Location_Address_2,
			users.Location_State,
			users.Location_Country,
			users.Location_ZipCode,
			users.PhoneNumber,
			payroll_companies.gusto_company_uid
		')
		->join('payroll_companies', 'payroll_companies.company_sid = users.sid', 'left')
		->where('users.sid', $companyId)
		->get('users')
		->row_array();
	}


	//
	public function GetCompanyWorkAddress($companyId){
		//
		$query = 
		$this->db
		->select('gusto_location_id')
		->where('company_sid', $companyId)
		->get('payroll_company_locations')
		->row_array();
		//
		return $query ? $query['gusto_location_id'] : 0;
	}
	
	//
	public function GetStateCodeById($stateId){
		//
		$query = 
		$this->db
		->select('state_code')
		->where('sid', $stateId)
		->get('states')
		->row_array();
		//
		return ucwords($query ? $query['state_code'] : 'CA');
	}
	
	//
	public function GetStateId($stateCode){
		//
		$query = 
		$this->db
		->select('sid')
		->where('sid', $stateCode)
		->get('states')
		->row_array();
		//
		return $query ? $query['sid'] : 0;
	}
	
	//
	public function GetPayrollColumn($table, $where, $col = 'sid', $single = true){
		//
		$query = 
		$this->db
		->select($col)
		->where($where)
		->get($table)
		->row_array();
		//
		if(!$single){
			return $query;
		}
		return $query ? $query[$col] : '';
	}
	
	//
	public function GetPayrollColumns($table, $where, $col = 'sid', $orderBy = ['sid', 'DESC']){
		//
		$query = 
		$this->db
		->select($col)
		->where($where)
		->order_by($orderBy[0], $orderBy[1])
		->get($table)
		->result_array();
		//
		return $query ? $query : [];
	}


	//
	public function GetCompanyWorkAddressBySid($sid){
		//
		return 
		$this->db
		->select('gusto_location_id')
		->where('sid', $sid)
		->get('payroll_company_locations')
		->row_array();
	}
	
	//
	public function GetCompensationColumn($uuid){
		//
		return 
		$this->db
		->select('sid')
		->where('payroll_id', $uuid)
		->get('payroll_employee_job_compensations')
		->row_array()['sid'];
	}
	
	//
	public function GetJobColumn($where){
		//
		return 
		$this->db
		->select('sid')
		->where($where)
		->get('payroll_employee_jobs')
		->row_array()['sid'];
	}

	//
	public function DeletePayroll($table, $where){
		$this->db
		->where($where)
		->delete($table);
	}

	public function GetSinglePayroll($payrollId){
		$q = $this->db
		->select('payroll_json')
		->where('payroll_id', $payrollId)
		->get('payrolls')
		->row_array();
		//
		return json_decode($q['payroll_json'], true);
	}

	public function checkFormExist ($formUUID, $employeeId) {
		return $this->db
		->where('form_uuid', $formUUID)
		->where('employee_sid', $employeeId)
		->count_all_results('payroll_employees_forms');
	}

	public function addEmployeeForm ($data_to_insert) {
		$this->db->insert('payroll_employees_forms', $data_to_insert);
	}

	public function getEmployeeForm ($employeeId) {
		//
		$query = 
		$this->db
		->select('sid, title, requires_signing, is_signed')
		->where('employee_sid', $employeeId)
		->get('payroll_employees_forms')
		->result_array();
		//
		return $query ? $query : [];
	}

	public function getEmployeeFormInfo ($formId) {
		$query = $this->db
		->select('form_uuid, title, requires_signing, is_signed,signature_text,signed_by_ip_address')
		->where('sid', $formId)
		->get('payroll_employees_forms')
		->row_array();
		//
		return $query;
	}

	public function updateEmployeeFormInfo ($formId, $data_to_update) {
		$this->db
		->where('sid', $formId)
		->update('payroll_employees_forms', $data_to_update);
	}

	public function getEmployeeFormInfoNew ($formId) {
		$query = $this->db
		->select('gusto_uuid')
		->where('sid', $formId)
		->get('gusto_employees_forms')
		->row_array();
		//
		return $query;
	}
}