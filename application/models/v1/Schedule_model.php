<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Schedule model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Scheduling
 */
class Schedule_model extends CI_Model
{
    /**
     * Main entry point
     */
    public function __construct()
    {
        // inherit parent
        parent::__construct();
    }

    /**
     * get the holidays
     * get the holidays within the provided time gap
     * @param int $companyId
     * @return array
     */
    public function getCompanyHolidays(int $companyId): array
    {
        //
        $records = $this->db
            ->select("from_date, to_date")
            ->where("company_sid", $companyId)
            ->where("is_archived", 0)
            ->where("holiday_year", getSystemDate("Y"))
            ->get("timeoff_holidays")
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        $datesArray = [];
        //
        foreach ($records as $v0) {
            // add the from date
            $datesArray[] = $v0["from_date"];
            // if the dates are different
            if ($v0["from_date"] != $v0["to_date"]) {
                // get the difference between 2 dates
                $dates = getDatesBetween($v0["from_date"], $v0["to_date"]);
                //
                $datesArray = array_merge($datesArray, $dates);
            }
        }
        //
        return array_unique($datesArray);
    }

    /**
     * get company schedules
     *
     * @param int $companyId
     * @param int $status
     * @return array
     */
    public function getCompanySchedules(int $companyId, int $status): array
    {
        return $this->db
            ->select("
                sid,
                frequency,
                day_1,
                day_2,
                anchor_pay_date,
                deadline_to_run_payroll,
                custom_name,
                gusto_uuid
            ")
            ->where("company_sid", $companyId)
            ->where("active", $status)
            ->order_by("sid", "DESC")
            ->get("companies_pay_schedules")
            ->result_array();
    }

    /**
     * get single schedule
     *
     * @param int $scheduleId
     * @return array
     */
    public function getScheduleById(int $scheduleId): array
    {
        return $this->db
            ->select("
                custom_name,
                frequency,
                anchor_pay_date,
                deadline_to_run_payroll,
                anchor_end_of_pay_period,
                day_1,
                day_2,
                active
            ")
            ->where("sid", $scheduleId)
            ->get("companies_pay_schedules")
            ->row_array();
    }

    /**
     * get company employees schedule ids
     *
     * @param int $companyId
     * @param bool $revert Optional
     * @return array
     */
    public function getCompanyEmployeeSchedulesIds(int $companyId, bool $revert = false): array
    {
        $records = $this->db
            ->select("
                employee_sid,
                pay_schedule_sid
            ")
            ->where("company_sid", $companyId)
            ->get("employees_pay_schedule")
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $v0) {
            if ($revert) {
                if (!$tmp[$v0["pay_schedule_sid"]]) {
                    $tmp[$v0["pay_schedule_sid"]] = [];
                }
                $tmp[$v0["pay_schedule_sid"]][] = $v0["employee_sid"];
                continue;
            }
            $tmp[$v0["employee_sid"]] = $v0["pay_schedule_sid"];
        }
        //
        return $tmp;
    }

    /**
     * link employees to pay schedule
     *
     * @param array $post
     */
    public function linkEmployeesToPaySchedule(array $post)
    {
        //
        foreach ($post["data"] as $payScheduleId => $employeeIds) {
            $this->linkEmployeesToPayScheduleById(
                $payScheduleId,
                $employeeIds,
                $post["companyId"]
            );
        }
    }

    /**
     * link employees to pay schedule
     *
     * @param int $payScheduleId
     * @param array $employeeIds
     * @param int $companyId
     */
    public function linkEmployeesToPayScheduleById(int $payScheduleId, array $employeeIds, int $companyId)
    {
        //
        foreach ($employeeIds as $employeeId) {
            $this->linkEmployeeToPayScheduleById(
                $payScheduleId,
                $employeeId,
                $companyId
            );
        }
    }

    /**
     * link employee to pay schedule
     *
     * @param int $payScheduleId
     * @param int $employeeId
     * @param int $companyId
     */
    public function linkEmployeeToPayScheduleById(int $payScheduleId, int $employeeId, int $companyId)
    {
        // set where array
        $where = [
            "company_sid" => $companyId,
            "employee_sid" => $employeeId
        ];
        // prepare array
        $ins = [];
        $ins["updated_at"] =  getSystemDate();
        $ins["pay_schedule_sid"] = $payScheduleId;
        //
        if (!$this->db->where($where)->count_all_results("employees_pay_schedule")) {
            $ins["company_sid"] = $companyId;
            $ins["employee_sid"] = $employeeId;
            $ins["created_at"] = $ins["updated_at"];
            //
            $this->db->insert(
                "employees_pay_schedule",
                $ins
            );
        } else {
            //
            $this->db
                ->where($where)
                ->update(
                    "employees_pay_schedule",
                    $ins
                );
        }
    }
}
