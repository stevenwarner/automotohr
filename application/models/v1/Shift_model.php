<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Shift model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Clock
 */
class Shift_model extends CI_Model
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
     * get the overtime rules
     *
     * @param int $companyId
     * @return array
     */
    public function get(
        int $companyId
    ): array {
        return $this->db
            ->select("sid, break_name, break_duration, break_type")
            ->where("company_sid", $companyId)
            ->order_by("sid", "DESC")
            ->get("company_breaks")
            ->result_array();
    }

    /**
     * get the overtime rules
     *
     * @param int $companyId
     * @param int $wageId
     * @return array
     */
    public function getSingle(
        int $companyId,
        int $wageId
    ): array {
        return $this->db
            ->select("
                break_name,
                break_duration,
                break_type
            ")
            ->where("company_sid", $companyId)
            ->where("sid", $wageId)
            ->get("company_breaks")
            ->row_array();
    }


    /**
     * process
     *
     * @param int   $companyId
     * @param array $post
     * @return array
     */
    public function process(
        int $companyId,
        array $post
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while updating break."];
        //
        if ($post["id"]) {
            // update
            // check if entry already exists
            if ($this->db->where([
                "LOWER(REGEXP_REPLACE(break_name, '[^a-zA-Z]', '')) = " => strtolower(
                    preg_replace(
                        '/[^a-z]/i',
                        '',
                        $post["break_name"],
                    )
                ),
            ])->where("sid <>", $post["id"])->count_all_results("company_breaks")) {
                $response["msg"] = "Break already exists.";
            } else {
                // update
                $this->db
                    ->where("sid", $post["id"])
                    ->update("company_breaks", [
                        "break_name" => $post["break_name"],
                        "break_duration" => $post["break_duration"],
                        "break_type" => $post["break_type"],
                        "updated_at" => getSystemDate(),
                    ]);

                $status = 200;
                $response = ["msg" => "You have successfully updated break."];
            }
        } else {
            // insert
            // check if entry already exists
            if ($this->db->where([
                "LOWER(REGEXP_REPLACE(break_name, '[^a-zA-Z]', '')) = " => strtolower(
                    preg_replace(
                        '/[^a-z]/i',
                        '',
                        $post["break_name"],
                    )
                )
            ])->count_all_results("company_breaks")) {
                $response["msg"] = "Break already exists.";
            } else {
                // insert
                $this->db
                    ->insert("company_breaks", [
                        "company_sid" => $companyId,
                        "break_name" => $post["break_name"],
                        "break_duration" => $post["break_duration"],
                        "break_type" => $post["break_type"],
                        "created_at" => getSystemDate(),
                        "updated_at" => getSystemDate(),
                    ]);
                // check and insert log
                if ($this->db->insert_id()) {
                    //
                    $status = 200;
                    $response = ["msg" => "You have successfully add a new break."];
                }
            }
        }
        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }

    /**
     * delete
     *
     * @param int $companyId
     * @param int $breakId
     * @return array
     */
    public function delete(
        int $companyId,
        int $breakId
    ): array {
        //
        $status = 400;
        $response = ["msg" => "Something went wrong while deleting break."];
        // check if entry already exists
        if (!$this->db->where(["company_sid" => $companyId, "sid" => $breakId])->count_all_results("company_breaks")) {
            $response["msg"] = "System failed to verify the break.";
        } else {
            // update
            $this->db
                ->where("sid", $breakId)
                ->delete("company_breaks");

            $status = 200;
            $response = ["msg" => "You have successfully deleted the break."];
        }

        //
        return SendResponse($status, $status === 400 ? ["errors" => [$response["msg"]]] : $response);
    }

    /**
     * get the company employees
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyEmployees(int $companyId): array
    {
        return $this->db
            ->select(getUserFields())
            ->where([
                "parent_sid" => $companyId,
            ])
            ->order_by("first_name", "ASC")
            ->get("users")
            ->result_array();
    }

    /**
     * apply shift template
     *
     * @param int $companyId
     * @param array $post
     * @return array
     */
    public function applyTemplate(int $companyId, array $post)
    {
        // convert the dates
        $startDate = formatDateToDB(
            $post["start_date"],
            SITE_DATE,
            DB_DATE
        );
        //
        $endDate = formatDateToDB(
            $post["end_date"],
            SITE_DATE,
            DB_DATE
        );
        //
        $dates = getDatesInRange($startDate, $endDate, DB_DATE);
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        // get the shift
        $scheduleDetails = $this->shift_template_model->getSingle($companyId, $post["schedule_id"]);
        // get company off days
        $holidaysAndOffDays = $this->getCompanyHolidays($companyId, $startDate, $endDate);
        //
        $employeesAlreadyExists = [];
        //
        $currentDateAndTime = getSystemDate();
        //
        foreach ($post["employees"] as $employeeId) {
            foreach ($dates as $v0) {
                //
                if (in_array($v0, $holidaysAndOffDays)) {
                    continue;
                }
                //
                $where = [
                    "employee_sid" => $employeeId,
                    "shift_date" => $v0
                ];
                // check if shift already assigned
                if ($this->db->where($where)->count_all_results("cl_shifts")) {
                    if (!$employeesAlreadyExists[$employeeId]) {
                        $employeesAlreadyExists[$employeeId] = [
                            "dates" => []
                        ];
                    }
                    $employeesAlreadyExists[$employeeId]["dates"][] = $v0;
                    continue;
                }
                // add the shift
                $ins = [];
                $ins["company_sid"] = $companyId;
                $ins["employee_sid"] = $employeeId;
                $ins["shift_date"] = $v0;
                $ins["start_time"] = $scheduleDetails["start_time"];
                $ins["end_time"] = $scheduleDetails["end_time"];
                $ins["breaks_count"] = $scheduleDetails["breaks_count"];
                $ins["breaks_json"] = $scheduleDetails["breaks_json"];
                $ins["created_at"] = $ins["updated_at"] = $currentDateAndTime;
                //
                $this->db->insert("cl_shifts", $ins);
                //
                $insertId = $this->db->insert_id();
                //
                if ($insertId) {
                    $ins = [];
                    $ins["cl_shift_sid"] = $insertId;
                    $ins["employee_sid"] = checkAndGetSession("employee")["sid"];
                    $ins["action"] = "created";
                    $ins["action_json"] = "{}";
                    $ins["created_at"] = $currentDateAndTime;
                    //
                    $this->db->insert("cl_shifts_logs", $ins);
                }
            }
        }

        return SendResponse(
            200,
            [
                "msg" => "You have successfully applied the selected shift to he selected employees." . (
                    $employeesAlreadyExists ? "<p>However, the below employees already have shifts.</p>" : ""
                ),
                "list" => $employeesAlreadyExists
            ]
        );
    }

    /**
     * get the shifts
     *
     * @param array $filter
     * @param array $employeeIds
     * @return array
     */
    public function getShifts(array $filter, array $employeeIds): array
    {
        //
        $this->db
            ->select("sid, employee_sid, shift_date, start_time, end_time")
            ->where_in("employee_sid", $employeeIds);

        if ($filter["mode"] === "month") {
            //
            $startDate = $filter["year"] . '-' . $filter["month"] . '-01';
            //
            $endDateObj = new DateTime($startDate);
            $endDate = $endDateObj->format("Y-m-t");
            //
            $this->db
                ->where("shift_date >= ", $startDate)
                ->where("shift_date <= ", $endDate);
        } else {
            //
            $this->db
                ->where("shift_date >= ", formatDateToDB($filter["start_date"], SITE_DATE, DB_DATE))
                ->where("shift_date <= ", formatDateToDB($filter["end_date"], SITE_DATE, DB_DATE));
        }

        //
        $records = $this->db
            ->get("cl_shifts")
            ->result_array();
        //
        if ($records) {
            //
            $employees = [];
            //
            foreach ($records as $v0) {
                //
                if (!$employees[$v0["employee_sid"]]) {
                    $employees[$v0["employee_sid"]] = [
                        "totalTimeText" => '0h',
                        "totalTime" => 0,
                        "dates" => []
                    ];
                }
                //
                $employees[$v0["employee_sid"]]["dates"][$v0["shift_date"]] = [
                    "start_time" => $v0["start_time"],
                    "end_time" => $v0["end_time"],
                    "totalTime" => getTimeBetweenTwoDates(
                        $v0["shift_date"] . ' ' . $v0["start_time"],
                        $v0["shift_date"] . ' ' . $v0["end_time"],
                    ),
                ];
                //
                $employees[$v0["employee_sid"]]["totalTime"] += getTimeBetweenTwoDates(
                    $v0["shift_date"] . ' ' . $v0["start_time"],
                    $v0["shift_date"] . ' ' . $v0["end_time"],
                );
                //
                $employees[$v0["employee_sid"]]["totalTimeText"] = convertSecondsToTime(
                    $employees[$v0["employee_sid"]]["totalTime"]
                );
            }
            $records = $employees;
        }

        return $records;
    }

    /**
     * get the company holidays
     *
     * @param int $companyId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getCompanyHolidays(int $companyId, string $startDate, string $endDate): array
    {
        $records = $this->db
            ->select("from_date, to_date")
            ->where("from_date >= ", $startDate)
            ->where("to_date <= ", $endDate)
            ->where("company_sid", $companyId)
            ->get("timeoff_holidays")
            ->result_array();
        //
        if ($records) {
            //
            $holidays = [];
            //
            foreach ($records as $record) {
                $holidays = array_merge(
                    $holidays,
                    getDatesInRange(
                        $record["from_date"],
                        $record["to_date"],
                    )
                );
            }
            //
            $records = $holidays;
        }
        //
        return array_merge($records, getSundaysAndSaturdays($startDate, $endDate));
    }

    /**
     * get the company holidays
     *
     * @param int $companyId
     * @param array $filter
     * @return array
     */
    public function getCompanyHolidaysWithTitle(int $companyId, array $filter): array
    {
        $startDate = $endDate = "";

        if ($filter["mode"] === "month") {
            //
            $startDate = $filter["year"] . '-' . $filter["month"] . '-01';
            //
            $endDateObj = new DateTime($startDate);
            $endDate = $endDateObj->format("Y-m-t");
        } else {
            $startDate = formatDateToDB($filter["start_date"], SITE_DATE, DB_DATE);
            $endDate = formatDateToDB($filter["end_date"], SITE_DATE, DB_DATE);
        }
        $records = $this->db
            ->select("from_date, to_date, holiday_title")
            ->where("from_date >= ", $startDate)
            ->where("to_date <= ", $endDate)
            ->where("company_sid", $companyId)
            ->get("timeoff_holidays")
            ->result_array();
        //
        if ($records) {
            //
            $holidays = [];
            //
            foreach ($records as $record) {
                $range = getDatesInRange(
                    $record["from_date"],
                    $record["to_date"],
                );
                foreach ($range as $date) {
                    $holidays[$date] = [
                        "title" => $record["holiday_title"],
                        "date" => $date
                    ];
                }
            }
            //
            $records = $holidays;
        }

        $offs = getSundaysAndSaturdays($startDate, $endDate);
        foreach ($offs as $date) {
            if (!$records[$date]) {
                $records[$date] = [
                    "title" => "Day off",
                    "date" => $date
                ];
            }
        }
        //
        return $records;
    }
}
