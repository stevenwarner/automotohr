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
                "user_sid" => $userId,
                "user_type" => $userType
            ])
            ->get("users_pay_schedule")
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
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while updating users pay schedule."];
        // validation
        // set up the rules
        $this->form_validation->set_rules("pay_schedule", "Pay schedule name", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse($status, getFormErrors());
        }
        // set where array
        $whereArray = [
            "user_sid" => $userId,
            "user_type" => $userType,
        ];
        // check if entry already exists
        if (!$this->db->where($whereArray)->count_all_results("users_pay_schedule")) {
            // insert
            $this->db
                ->insert("users_pay_schedule", [
                    "user_sid" => $userId,
                    "user_type" => $userType,
                    "pay_schedule_sid" => $post["pay_schedule"],
                    "created_at" => getSystemDate(),
                    "updated_at" => getSystemDate(),
                ]);
            // check and insert log
            if ($this->db->insert_id()) {
                //
                $status = 200;
                $response = ["msg" => "You have successfully updated the user pay schedule."];
            }
        } else {
            // update
            $this->db
                ->where($whereArray)
                ->update("users_pay_schedule", [
                    "pay_schedule_sid" => $post["pay_schedule"],
                    "updated_at" => getSystemDate(),
                ]);
            //
            $status = 200;
            $response = ["msg" => "You have successfully updated the user pay schedule."];
        }
        // update
        //
        return SendResponse($status, $response);
    }
}
