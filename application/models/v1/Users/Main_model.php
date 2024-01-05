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
}
