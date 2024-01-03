<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Dashboard model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Attendance
 */
class Dashboard_model extends Base_model
{

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
     * @return array
     */
    public function getUserPayScheduleById(
        int $userId,
        string $userType
    ): array {
        return $this->db
            ->select("pay_schedule_sid")
            ->where([
                "user_sid" => $userId,
                "user_type" => $userType
            ])
            ->get("user_pay_schedules")
            ->row_array();
    }
}
