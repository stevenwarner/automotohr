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
        $returnArray["jobWageData"] = $this
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
                rehire_date,
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
                guarantee_info
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
            "guaranteeInfo" => [],
        ];

        // set employee_type
        $returnArray["employmentType"] = $record["employee_type"] ?? "";
        // set overtime_rule
        $returnArray["overtimeRule"] = $record["overtime_rule"] ?? "";
        // get employee hire date
        $returnArray["hireDate"] = get_employee_latest_joined_date(
            $record["registration_date"],
            $record["joined_at"],
            $record["rehire_date"]
        );
        // set rate
        $returnArray["rate"] = $gustoProfileData["rate"] ?? 0;
        // set payment_unit
        $returnArray["per"] = $gustoProfileData["per"] ?? "";
        // set effective_date
        $returnArray["flsaStatus"] = $gustoProfileData["flsa_status"] ?? "";
        // set effective_date
        $returnArray["effectiveDate"] = $gustoProfileData["effective_date"] ?? "";
        // set adjust_for_minimum_wage
        $returnArray["adjustForMinimumWage"] = $gustoProfileData["adjust_for_minimum_wage"] ?? "";
        // set minimum_wages
        $returnArray["minimumWages"] = $gustoProfileData["minimum_wages"] ?
            array_column(unserialize($gustoProfileData["minimum_wages"]), 'uuid')
            : [];
        //
        $returnArray["guaranteeInfo"] = $gustoProfileData["guarantee_info"] ?
            unserialize($gustoProfileData["guarantee_info"])
            : [];

        return $returnArray;
    }

    /**
     * get the user job wage data
     *
     * @param int    $userId
     * @param string $userType
     * @param int $companyId
     * @return array
     */
    public function getUserJobWageData(
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
                job_title,
                registration_date,
                joined_at,
                employee_type,
                overtime_rule,
                rehire_date,
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
                title,
                current_compensation_uuid,
                earning_types,
            ")
            ->where("employee_sid", $userId)
            ->where("is_primary", 1)
            ->get("gusto_employees_jobs")
            ->row_array();
        //
        if ($gustoProfileData) {
            // get compensation details
            $gustoCompensationData =
                $this->db
                ->select("
                    payment_unit
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
            "title" => "",
            "employmentType" => "",
            "overtimeRule" => "N/A",
            "hireDate" => "",
            "rate" => "",
            "paymentUnit" => "",
            "earnings" => [],
        ];
        //
        if ($record["overtime_rule"] > 0) {
            // load overtime rule model
            $this->load->model("v1/Overtime_rules_model", "overtime_rules_model");
            // get the company overtime rules
            $returnArray["overtimeRule"] = $this->overtime_rules_model
                ->getOvertimeRuleById(
                    $companyId,
                    $record["overtime_rule"]
                )['rule_name'];
        }
        // set employee_title
        $returnArray["title"] = $gustoProfileData["title"] ?? $record["job_title"];
        // set employee_type
        $returnArray["employmentType"] = $record["employee_type"] ?? "";
        // get employee hire date
        if (!$gustoProfileData) {
            $returnArray["hireDate"] = get_employee_latest_joined_date(
                $record["registration_date"],
                $record["joined_at"],
                $record["rehire_date"]
            );
        } else {
            $returnArray["hireDate"] = $gustoProfileData["hire_date"];
        }
        // set payment_unit
        $returnArray["paymentUnit"] = $gustoProfileData["payment_unit"] ?? "Hours";
        // set rate
        $returnArray["rate"] = $gustoProfileData["rate"] ?? 0;
        // set earning types
        $returnArray["earnings"] = $gustoProfileData["earning_types"] ? json_decode($gustoProfileData["earning_types"], true) : [];
        //
        return $returnArray;
    }

    /**
     * get the user job wage data
     *
     * @param int    $userId
     * @param string $userType
     * @param int $companyId
     * @return array
     */
    public function pageEmployeeCustomEarnings(
        int $userId,
        string $userType,
        int $companyId
    ): array {
        //
        // get company earning data
        $companyEarnings = $this->db
            ->select("
                sid,
                name,
                fields_json,
            ")
            ->where("company_sid", $companyId)
            ->get("gusto_companies_earning_types")
            ->result_array();
        // get employee earning data
        $employeeEarnings =
            $this->db
            ->select("
                earning_types,
            ")
            ->where("employee_sid", $userId)
            ->where("is_primary", 1)
            ->get("gusto_employees_jobs")
            ->row_array();
        //
        $returnArray = [];
        $employeeAssignedEarning = [];
        //
        if (!empty($employeeEarnings['earning_types'])) {
            foreach (json_decode($employeeEarnings['earning_types'], true) as $earning) {
                $employeeAssignedEarning[$earning['sid']] = $earning['rate'];
            }
        }

        if (!empty($companyEarnings)) {
            //
            foreach ($companyEarnings as $companyEarning) {
                //
                $earningData = [
                    "sid" => "",
                    "title" => "",
                    "rate_type" => "N/A",
                    "wage_type" => "N/A",
                    "rate" => "0",
                    "is_assign" => 0
                ];
                //
                $earningData['sid'] = $companyEarning['sid'];
                //
                if ($companyEarning['fields_json']) {
                    $fields_json = json_decode($companyEarning['fields_json'], true);
                    //
                    $earningData['title'] = $fields_json['name'];
                    $earningData['rate_type'] = $fields_json['rate_type'];
                    $earningData['wage_type'] = $fields_json['wage_type'];
                } else {
                    $earningData['title'] = $companyEarning['name'];
                }
                //
                if (isset($employeeAssignedEarning[$companyEarning['sid']])) {
                    $earningData['is_assign'] = 1;
                    $earningData['rate'] = $employeeAssignedEarning[$companyEarning['sid']];
                }
                //
                $returnArray['earnings'][] = $earningData;
            }
        }

        //
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

    public function processEmployeeJobData(int $employeeId, array $post)
    {
        //
        $this->updateEmployeeBasicInfo($employeeId, $post['overTimeRule'], $post['employeeType']);
        //
        if ($this->db
            ->where("employee_sid", $employeeId)
            ->count_all_results('gusto_employees_jobs')
        ) {
            $updateArray = [];
            $updateArray['rate'] = $post['amount'];
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
            $updateCompensation['rate'] = $post['amount'];
            $updateCompensation['payment_unit'] = $post['per'];
            $updateCompensation['flsa_status'] = $post['classification'];
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
            //
            $this->updateEmployeeGuaranteeInfo($employeeId, $post);
            //
            return ['msg' => 'You have successfully updated employee Job & wage.'];
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
                    'rate' => $post['amount'],
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
            $ins['rate'] = $post['amount'];
            $ins['payment_unit'] = $post['per'];
            $ins['flsa_status'] = $post['classification'];
            $ins['effective_date'] = $post['hireDate'];
            $ins['adjust_for_minimum_wage'] = $wagesInfo['minimumWage'];
            $ins['minimum_wages'] = serialize($wagesInfo['minimum_wages']);
            $ins['created_at'] = getSystemDate();
            //
            $this->db->insert('gusto_employees_jobs_compensations', $ins);
            //
            $this->updateEmployeeGuaranteeInfo($employeeId, $post);
            //
            return ['msg' => 'You have successfully created employee Job & wage.'];
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

    public function updateEmployeeBasicInfo($employeeId, $overtimeRule, $employeeType)
    {
        $dataToUpdate = [];
        $dataToUpdate['overtime_rule'] = $overtimeRule;
        $dataToUpdate['employee_type'] = $employeeType;
        //
        $this->db
            ->where('sid', $employeeId)
            ->update('users', $dataToUpdate);
    }

    public function updateEmployeeGuaranteeInfo($employeeId, $post)
    {
        //
        $guaranteeInfo = [];
        $guaranteeInfo['guarantee_rate'] = $post['guaranteeRate'];
        $guaranteeInfo['guarantee_per'] = $post['guaranteePer'];
        $guaranteeInfo['guarantee_times'] = $post['guaranteeTime'];
        //
        $dataToUpdate = [];
        $dataToUpdate['guarantee_info'] = serialize($guaranteeInfo);
        //
        $this->db
            ->where('employee_sid', $employeeId)
            ->update('gusto_employees_jobs', $dataToUpdate);
    }

    public function updateEmployeeEarnings($employeeId, $dataToUpdate)
    {
        $this->db
            ->where('employee_sid', $employeeId)
            ->where("is_primary", 1)
            ->update('gusto_employees_jobs', $dataToUpdate);
    }
}
