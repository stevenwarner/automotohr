<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Main model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll dashboard
 */
class Main_model extends CI_Model
{
    /**
     * main entry point
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get the employee details
     *
     * @param int $employeeId
     * @param array $columns Optional
     * @return array
     */
    public function getEmployeeDetails(int $employeeId, array $columns = ["*"]): array
    {
        return $this->db
            ->select($columns)
            ->where("sid", $employeeId)
            ->get("users")
            ->row_array();
    }

    /**
     * get the company pay schedules
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyPaySchedules(
        int $companyId
    ): array {
        return $this->db
            ->select("sid, frequency, anchor_pay_date, day_1, day_2, custom_name")
            ->where([
                "company_sid" => $companyId,
                "active" => 1
            ])
            ->order_by("sid", "DESC")
            ->get("companies_pay_schedules")
            ->result_array();
    }

    /**
     * get the user pay schedule
     *
     * @param int    $userId
     * @param string $userType
     * @param bool   $fullInfo Optional
     * @return array
     */
    public function getUserPayScheduleById(
        int $userId,
        string $userType,
        bool $fullInfo = false
    ): array {
        $record = $this->db
            ->select("pay_schedule_sid")
            ->where([
                "employee_sid" => $userId,
            ])
            ->get("employees_pay_schedule")
            ->row_array();
        //
        if (!$record || !$fullInfo) {
            return $record;
        }
        // attach the info
        // get the pay schedule info
        $info = $this->db
            ->select("frequency, anchor_pay_date, day_1, day_2, custom_name")
            ->where("sid", $record["pay_schedule_sid"])
            ->get("companies_pay_schedules")
            ->row_array();
        //
        return array_merge($record, $info);
    }

    /**
     * process the pay schedule
     *
     * @param int    $userId
     * @param string $userType
     * @param array  $post
     * @return array
     */
    public function processPaySchedule(
        int $userId,
        string $userType,
        array $post
    ): array {
        // set up the rules
        $this->form_validation->set_rules("pay_schedule", "pay schedule", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while updating employee's pay schedule."];
        // validation
        // set up the rules
        $this->form_validation->set_rules("pay_schedule", "Pay schedule name", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse($status, getFormErrors());
        }
        // set where array
        $whereArray = [
            "employee_sid" => $userId,
        ];
        // check if entry already exists
        if (!$this->db->where($whereArray)->count_all_results("employees_pay_schedule")) {
            // insert
            $this->db
                ->insert("employees_pay_schedule", [
                    "company_sid" => $post["companyId"],
                    "employee_sid" => $userId,
                    "pay_schedule_sid" => $post["pay_schedule"],
                    "created_at" => getSystemDate(),
                    "updated_at" => getSystemDate(),
                ]);
            // check and insert log
            if ($this->db->insert_id()) {
                //
                $status = 200;
                $response = ["msg" => "You have successfully updated the employee's pay schedule."];
            }
        } else {
            // update
            $this->db
                ->where($whereArray)
                ->update("employees_pay_schedule", [
                    "pay_schedule_sid" => $post["pay_schedule"],
                    "updated_at" => getSystemDate(),
                ]);
            //
            $status = 200;
            $response = ["msg" => "You have successfully updated the employee's pay schedule."];
        }
        // update to Gusto
        // load gusto model
        // TODO
        // enable it once API starts working
        // $this->load->model("v1/Payroll_model", "payroll_model");
        // $this->payroll_model->updateEmployeePaySchedule($post, $userId);
        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }

    /**
     * get the pay schedule
     *
     * @param int    $userId
     * @param string $userType
     * @param int    $companyId
     * @return array
     */
    public function pagePaySchedule(
        int $userId,
        string $userType,
        int $companyId
    ): array {
        //
        $returnArray = [];
        // get company pay scheduled
        $returnArray["return"] = $this
            ->getCompanyPaySchedules(
                $companyId
            );
        $returnArray["companyPaySchedules"] = $returnArray["return"];
        // get the pay schedule
        $returnArray["userPaySchedule"] = $this
            ->getUserPayScheduleById(
                $userId,
                $userType
            );
        //
        return $returnArray;
    }

    /**
     * get the pay schedule
     *
     * @param int    $userId
     * @param string $userType
     * @param int    $companyId
     * @return array
     */
    public function pageJobAndWage(
        int $userId,
        string $userType,
        int $companyId
    ): array {
        //
        $returnArray = [];
        // load overtime rule model
        $this->load->model("v1/Overtime_rules_model", "overtime_rules_model");
        $this->load->model("v1/Minimum_wages_model", "minimum_wages_model");
        // get the company overtime rules
        $returnArray["overtimeRules"] = $this->overtime_rules_model
            ->getOvertimeRules(
                $companyId,
                1
            );
        // get the company overtime rules
        $returnArray["minimumWages"] = $this->minimum_wages_model
            ->get(
                $companyId
            );
        // get the company overtime rules
        $returnArray["jobWageData"] = $this->main_model
            ->getJobWageData(
                $userId,
                $userType,
                $companyId
            );
        //
        return $returnArray;
    }


    /**
     * get the user job wage data
     *
     * @param int    $userId
     * @param string $userType
     * @param int    $companyId
     * @return array
     */
    public function getJobWageData(
        int $userId,
        string $userType,
        int $companyId
    ): array {
        //
        if ($userType === "applicant") {
            // todo for applicant
        }
        // for employee
        // get profile data
        $record = $this->db
            ->select("
                registration_date,
                joined_at,
                employee_type,
                hourly_rate,
                hourly_technician,
                flat_rate_technician,
                semi_monthly_salary,
                semi_monthly_draw,
                overtime_rule,
            ")
            ->where("sid", $userId)
            ->get("users")
            ->row_array();
        // get Gusto profile
        $gustoProfileData =
            $this->db
            ->select("
                sid,
                hire_date,
                rate,
                current_compensation_uuid,
            ")
            ->where("employee_sid", $userId)
            ->where("is_primary", 1)
            ->get("gusto_employees_jobs")
            ->row_array();
        // check and get compensation
        if ($gustoProfileData) {
            // get compensation details
            $gustoCompensationData =
                $this->db
                ->select("
                    payment_unit,
                    flsa_status,
                    effective_date,
                    adjust_for_minimum_wage,
                    minimum_wages
                ")
                ->where("gusto_employees_jobs_sid", $gustoProfileData["sid"])
                ->where("gusto_uuid", $gustoProfileData["current_compensation_uuid"])
                ->get("gusto_employees_jobs_compensations")
                ->row_array();
            // merge with Gusto profile data if found
            if ($gustoCompensationData) {
                $gustoProfileData = array_merge(
                    $gustoProfileData,
                    $gustoCompensationData
                );
            }
        }
        // set return array
        $returnArray = [
            "employmentType" => "",
            "overtimeRule" => "",
            "hireDate" => "",
            "rate" => 0,
            "per" => "",
            "flsaStatus" => "",
            "effectiveDate" => "",
            "adjustForMinimumWage" => "",
            "minimumWages" => [],
        ];

        // set employee_type
        $returnArray["employmentType"] = $record["employee_type"] ?? "";
        // set overtime_rule
        $returnArray["overtimeRule"] = $record["overtime_rule"] ?? "";
        // get employee hire date
        $returnArray["hireDate"] = get_employee_latest_joined_date(
            $record["registration_date"],
            $record["joined_at"],
            ""
        );
        // set rate
        $returnArray["rate"] = $gustoProfileData["rate"] ?? 0;
        // set payment_unit
        $returnArray["payment_unit"] = $gustoProfileData["per"] ?? "";
        // set effective_date
        $returnArray["flsaStatus"] = $gustoProfileData["flsa_status"] ?? "";
        // set effective_date
        $returnArray["effectiveDate"] = $gustoProfileData["effective_date"] ?? "";
        // set adjust_for_minimum_wage
        $returnArray["adjustForMinimumWage"] = $gustoProfileData["adjust_for_minimum_wage"] ?? "";
        // set minimum_wages
        $returnArray["minimumWages"] = $gustoProfileData["minimum_wages"] ?
            unserialize($gustoProfileData["minimum_wages"])
            : [];

        return $returnArray;
    }

    /**
     * get the user job info
     *
     * @param int    $userId
     * @return array
     */
    public function getEmployeeJobInfo(
        int $employeeId
    ): array {
        // get job data
        return $this->db
            ->select("
                sid,
                gusto_uuid,
                gusto_version,
                gusto_location_uuid,
                title,
                hire_date
            ")
            ->where("employee_sid", $employeeId)
            ->get("gusto_employees_jobs")
            ->row_array();
    }

    /**
     * get the user gusto detail
     *
     * @param int    $userId
     * @return array
     */
    public function getEmployeeGustoInfo(
        int $employeeId
    ): array {
        // get gusto data
        return $this->db
        ->select('
            gusto_uuid
        ')
        ->where('employee_sid', $employeeId)
        ->get('gusto_companies_employees')
        ->row_array();
    }

    public function updateEmployeeJob ($companyId, $jobInfo, $newHireDate, $companyGustoDetails) {
        //
        $this->load->helper('v1/payroll' . ($this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
        //
        $request = [];
        $request['version'] = $jobInfo['gusto_version'];
        $request['title'] = $jobInfo['title'];
        $request['location_uuid'] = $jobInfo['gusto_location_uuid'];
        $request['hire_date'] = $newHireDate;
        //
        $gustoResponse = gustoCall(
            'updateEmployeeJob',
            $companyGustoDetails,
            $request,
            "PUT"
        );
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $updateArray = [];
        $updateArray['gusto_version'] = $gustoResponse['version'];
        $updateArray['gusto_location_uuid'] = $gustoResponse['location_uuid'];
        $updateArray['hire_date'] = $gustoResponse['hire_date'];
        $updateArray['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $jobInfo['sid'])
            ->update('gusto_employees_jobs', $updateArray);
    }

    /**
     * create and sync employee job on Gusto
     *
     * @param int   $companyId
     * @param int   $employeeId
     * @param array $companyDetails
     * @return array
     */
    public function createEmployeeJobOnGusto(int $companyId, int $employeeId, array $companyDetails): array
    {
        //
        $this->load->helper('v1/payroll' . ($this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
        //
        // check the company location
        $location = $this->db
            ->select('gusto_uuid')
            ->where('company_sid', $companyDetails['company_sid'])
            ->where('is_active', 1)
            ->get('gusto_companies_locations')
            ->row_array();
        //
        if (!$location) {
            return ['errors' => ['"Location" is missing.']];
        }
        // get employee profile data
        $employeeDetails = $this->db
            ->select('
                job_title,
                complynet_job_title,
                registration_date,
                joined_at,
            ')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        // get job title
        $jobTitle = 'Automotive';
        $joiningDate = get_employee_latest_joined_date(
            $employeeDetails['registration_date'],
            $employeeDetails['joined_at'],
            ''
        );
        //
        if ($employeeDetails['job_title']) {
            $jobTitle = $employeeDetails['job_title'];
        } elseif ($employeeDetails['complynet_job_title']) {
            $jobTitle = $employeeDetails['complynet_job_title'];
        }
        //
        $errorArray = [];
        // validation
        if (!$jobTitle) {
            $errorArray[] = '"Job Title" is required.';
        }
        if (!$joiningDate) {
            $errorArray[] = '"Joining Date" is required.';
        }
        //
        if ($errorArray) {
            return ['errors' => $errorArray];
        }
        // create request
        $request = [];
        $request['title'] = $jobTitle;
        $request['location_uuid'] = $location['gusto_uuid'];
        $request['hire_date'] = $joiningDate;
        // make call
        $gustoResponse = gustoCall(
            "createEmployeeJobOnGusto",
            $companyDetails,
            $request,
            "POST"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // insert
        $this->db
            ->insert('gusto_employees_jobs', [
                'employee_sid' => $employeeId,
                'gusto_uuid' => $gustoResponse['uuid'],
                'gusto_version' => $gustoResponse['version'],
                'gusto_location_uuid' => $gustoResponse['location_uuid'],
                'is_primary' => $gustoResponse['primary'],
                'hire_date' => $gustoResponse['hire_date'],
                'title' => $gustoResponse['title'],
                'rate' => $gustoResponse['rate'],
                'current_compensation_uuid' => $gustoResponse['current_compensation_uuid'],
                'created_at' => getSystemDate(),
                'updated_at' => getSystemDate()
            ]);
        //
        $gustoEmployeeJobId = $this->db->insert_id();
        // add compensations
        foreach ($gustoResponse['compensations'] as $compensation) {
            $this->db
                ->insert('gusto_employees_jobs_compensations', [
                    'gusto_employees_jobs_sid' => $gustoEmployeeJobId,
                    'gusto_uuid' => $compensation['uuid'],
                    'gusto_version' => $compensation['version'],
                    'rate' => $compensation['rate'],
                    'payment_unit' => $compensation['payment_unit'],
                    'flsa_status' => $compensation['flsa_status'],
                    'effective_date' => $compensation['effective_date'],
                    'adjust_for_minimum_wage' => $compensation['adjust_for_minimum_wage'],
                    'minimum_wages' => serialize($compensation['minimum_wages']),
                    'created_at' => getSystemDate(),
                    'updated_at' => getSystemDate()
                ]);
        }
        //
        $this->db
            ->where(['employee_sid' => $employeeId])
            ->update('gusto_companies_employees', [
                'work_address' => 1,
                'compensation_details' => 1
            ]);
        //
        return [
            'gusto_uuid' => $gustoResponse['uuid'],
            'gusto_version' => $gustoResponse['version']
        ];
    }

    public function processEmployeeJobData (int $employeeId, array $post) {
        //
        if($this->db
            ->where("employee_sid", $employeeId)
            ->count_all_results('gusto_employees_jobs')
        ) {
            $updateArray = [];
            $updateArray['rate'] = $post['employeeRate'];
            $updateArray['hire_date'] = formatDateToDB($post['hireDate']);
            $updateArray['updated_at'] = getSystemDate();
            //
            $this->db
                ->where('employee_sid', $$employeeId)
                ->update('gusto_employees_jobs', $updateArray);
            //
            $wagesInfo = $this->getMinimumWagesData($post['adjustMinimumWage'], $post['wagesID']); 
            //
            $updateCompensation = [];
            $updateCompensation['rate'] = $post['employeeRate'];
            $updateCompensation['payment_unit'] = $post['payType'];
            $updateCompensation['flsa_status'] = $post['flsaStatus'];
            $updateCompensation['effective_date'] = $post['hireDate'];
            $updateCompensation['adjust_for_minimum_wage'] = $wagesInfo['minimumWage'];
            $updateCompensation['minimum_wages'] = serialize($wagesInfo['minimum_wages']);
            $updateCompensation['updated_at'] = getSystemDate();   
            //
            $jobId = $this->db
                ->select("
                    sid
                ")
                ->where("employee_sid", $employeeId)
                ->get("gusto_employees_jobs")
                ->row_array()['sid'];
            //
            $this->db
                ->where('gusto_employees_jobs_sid', $jobId)
                ->update('gusto_employees_jobs_compensations', $updateCompensation);
        } else {
            $employeeDetails = $this->db
                ->select('
                    job_title,
                    complynet_job_title,
                    registration_date,
                    joined_at,
                ')
                ->where('sid', $employeeId)
                ->get('users')
                ->row_array();
            // get job title
            $jobTitle = 'Automotive';
            //
            if ($employeeDetails['job_title']) {
                $jobTitle = $employeeDetails['job_title'];
            } elseif ($employeeDetails['complynet_job_title']) {
                $jobTitle = $employeeDetails['complynet_job_title'];
            }
            // insert
            $this->db
                ->insert('gusto_employees_jobs', [
                    'employee_sid' => $employeeId,
                    'gusto_uuid' => '',
                    'gusto_version' => '',
                    'gusto_location_uuid' => '',
                    'is_primary' => 1,
                    'hire_date' => formatDateToDB($post['hireDate']),
                    'title' => $jobTitle['title'],
                    'rate' => $post['employeeRate'],
                    'current_compensation_uuid' => '',
                    'created_at' => getSystemDate(),
                    'updated_at' => getSystemDate()
                ]);
            //    
            $jobId = $this->db->insert_id();    
            // 
            $wagesInfo = $this->getMinimumWagesData($post['adjustMinimumWage'], $post['wagesID']);    
            //
            $ins = [];
            $ins['gusto_version'] = '';
            $ins['gusto_employees_jobs_sid'] = $jobId;
            $ins['rate'] = $post['employeeRate'];
            $ins['payment_unit'] = $post['payType'];
            $ins['flsa_status'] = $post['flsaStatus'];
            $ins['effective_date'] = $post['hireDate'];
            $ins['adjust_for_minimum_wage'] = $wagesInfo['minimumWage'];
            $ins['minimum_wages'] = serialize($wagesInfo['minimum_wages']);
            $ins['created_at'] = getSystemDate();
            //
            $this->db->insert('gusto_employees_jobs_compensations', $ins);
        }    
    }

    public function getMinimumWagesData($minimumWage, $wagesId)
    {
        //
        $response = [
            'minimumWage' => 0,
            'minimum_wages' => []
        ];
        //
        if ($minimumWage == 1 && !empty($wagesId)) {
            //
            $response['minimumWage'] = 1;
            //
            foreach ($wagesId as $id) {
                //
                $uuid = $this->db
                    ->select('gusto_uuid')
                    ->where('sid', $id)
                    ->get('company_minimum_wages')
                    ->row_array()['gusto_uuid'];
                //
                $wageInfo = array('uuid' => $uuid);
                //
                $response['minimum_wages'][] = $wageInfo;
            }
        }
        return $response;
    }
    

}