<?php defined('BASEPATH') || exit('No direct script access allowed');
loadUpModel("v1/Attendance/Base_model", "base_model");
/**
 * Clock model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Shift & Clock
 * @tables  cl_attendance
 *          cl_attendance_log
 */
class Clock_model extends Base_model
{
    /**
     * holds the company id
     * @var int
     */
    private $companyId;

    /**
     * holds the employee id
     * @var int
     */
    private $employeeId;

    /**
     * holds the date in UTC
     * @var string
     */
    private $dateInUTC;

    /**
     * get the clock with state and job sites
     *
     * @param int $companyId
     * @param int $employeeId
     * @return array
     */
    public function getClockWithState(
        int $companyId,
        int $employeeId
    ) {
        // set companyId
        $this->companyId = $companyId;
        // set employeeId
        $this->employeeId = $employeeId;
        // get todays date in UTC
        $this->dateInUTC = getSystemDateInUTC(DB_DATE);
        // set return array
        $clockArray = [
            "clock_time" => "",
            "state" => "",
            "breaks" => [],
            "jobs" => $this->getJobs(),
        ];
        // get the attendance state and time
        $record = $this->getAttendanceStateAndTime();
        // when no entry is found
        if (!$record) {
            //
            return SendResponse(
                200,
                $clockArray
            );
        }
        // get the attendance logs
        $records = $this->getAttendanceLogs($record["sid"]);
        // set default variables
        $timeInMinutes = 0;
        $lastClockInTime = $record["clocked_in"];
        $found = 0;

        // when records found
        if ($records) {
            foreach ($records as $v) {
                //
                $timeInMinutes += $v["duration"];
                //
                if ($v["clocked_in"] && !$v["clocked_out"]) {
                    $lastClockInTime = $v["clocked_in"];
                    $found = 1;
                }
                //
                if ($v["break_start"]) {
                    $clockArray["breaks"][] = [
                        "start" => $v["break_start"],
                        "end" => $v["break_end"],
                        "duration" => $v["duration"],
                    ];
                }
            }
        }
        //
        $clockArray["clock_time"] = $lastClockInTime;
        $clockArray["state"] = $record["last_event"];
        $clockArray["time"] = $found ? $timeInMinutes : 0;
        //
        if ($record["last_event"] === "clocked_out") {
            $clockArray["time"] = $timeInMinutes;
        }
        //
        return SendResponse(
            200,
            $clockArray
        );
    }

    /**
     * mark attendance
     *
     * @param int $companyId
     * @param int $employeeId
     * @param array $employeeId
     * @return array
     */
    public function markAttendance(
        int $companyId,
        int $employeeId,
        array $post
    ) {
        // set companyId
        $this->companyId = $companyId;
        // set employeeId
        $this->employeeId = $employeeId;
        // get todays date in UTC
        $this->dateInUTC = getSystemDateInUTC(DB_DATE);
        // set the time
        $eventType = $post["type"];
        // check if event is same
        if ($this->checkLastEvent($eventType)) {
            return SendResponse(
                400,
                [
                    "errors" => [
                        "You already have {$this->attendance_lib->getEventType($eventType)}."
                    ]
                ]
            );
        }
        // entry needs to be make
        if (!$this->checkRecord()) {
            // if event is not clock in
            if ($eventType !== "clocked_in") {
                return SendResponse(
                    400,
                    [
                        "errors" => [
                            'You can only "Clock In".'
                        ]
                    ]
                );
            }
            // mark attendance
            $this->insertAttendance($post);
        } else {
            // update record
            $this->updateAttendance($post);
        }
    }

    /**
     * get the employees week worked time
     *
     * @param int $companyId
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getWorkedHoursForGraph(
        int $companyId,
        int $employeeId,
        string $startDate,
        string $endDate
    ) {
        // set companyId
        $this->companyId = $companyId;
        // set employeeId
        $this->employeeId = $employeeId;
        // add times to dates
        $startDate .= " 00:00:00";
        $endDate .= " 23:59:59";
        // convert the dates in DB format
        $startDate = reset_datetime([
            "datetime" => $startDate,
            "from_format" => SITE_DATE . " H:i:s",
            "format" => DB_DATE,
            "_this" => $this,
            "new_zone" => "UTC"
        ]);
        $endDate = reset_datetime([
            "datetime" => $endDate,
            "from_format" => SITE_DATE . " H:i:s",
            "format" => DB_DATE,
            "_this" => $this,
            "new_zone" => "UTC"
        ]);
        // get date array
        $datesPool = $this->attendance_lib
            ->getDatePoolInRange(
                $startDate,
                $endDate
            );
        // get the attendance logs within range
        $records = $this->getAttendanceInRange($startDate, $endDate);
        // when no records are found
        if (!$records) {
            return SendResponse(
                200,
                $datesPool
            );
        }
        //
        return SendResponse(
            200,
            $this->fillDatesPool($records, $datesPool)
        );
    }

    /**
     * get the attendance state and time
     *
     * @return array
     */
    private function getAttendanceStateAndTime(): array
    {
        return $this->db
            ->select("
                sid,
                clocked_in,
                clocked_out,
                last_event
            ")
            ->where([
                "employee_sid" => $this->employeeId,
                "company_sid" => $this->companyId,
                "clocked_date" => $this->dateInUTC,
            ])
            ->limit(1)
            ->get("cl_attendance")
            ->row_array();
    }

    /**
     * get the attendance logs
     *
     * @param int $attendanceId
     * @return array
     */
    private function getAttendanceLogs(int $attendanceId): array
    {
        return $this->db
            ->select("
                clocked_in,
                clocked_out,
                break_start,
                break_end,
                duration,
            ")
            ->where("cl_attendance_sid", $attendanceId)
            ->order_by("sid", "DESC")
            ->get("cl_attendance_log")
            ->result_array();
    }

    /**
     * check the event type
     *
     * @param string $eventType
     * @return int
     */
    private function checkLastEvent(string $eventType): int
    {
        return $this->db
            ->where([
                "employee_sid" => $this->employeeId,
                "company_sid" => $this->companyId,
                "clocked_date" => $this->dateInUTC,
                "last_event" => $eventType,
            ])
            ->limit(1)
            ->count_all_results("cl_attendance");
    }

    /**
     * check the record
     *
     * @return int
     */
    private function checkRecord(): int
    {
        return $this->db
            ->where([
                "employee_sid" => $this->employeeId,
                "company_sid" => $this->companyId,
                "clocked_date" => $this->dateInUTC,
            ])
            ->limit(1)
            ->count_all_results("cl_attendance");
    }

    /**
     * get the record
     *
     * @return array
     */
    private function getAttendanceByDate(): array
    {
        return $this->db
            ->select("
            sid,
            last_event,
            last_record_time
        ")
            ->where([
                "employee_sid" => $this->employeeId,
                "company_sid" => $this->companyId,
                "clocked_date" => $this->dateInUTC,
            ])
            ->limit(1)
            ->get("cl_attendance")
            ->row_array();
    }

    /**
     * insert attendance
     *
     * @param array $post
     */
    private function insertAttendance(array $post)
    {
        // prepare insert array
        $ins = [];
        $ins["company_sid"] = $this->companyId;
        $ins["employee_sid"] = $this->employeeId;
        $ins["clocked_date"] = getSystemDateInUTC(DB_DATE);
        $ins["clocked_in"] = getSystemDateInUTC(DB_DATE_WITH_TIME);
        $ins["last_record_time"] = $ins["clocked_in"];
        $ins["is_approved"] = 0;
        $ins["last_event"] = $post["type"];
        $ins["created_at"] = getSystemDate(DB_DATE_WITH_TIME);
        $ins["updated_at"] = $ins["created_at"];
        // insert
        $this->db->insert(
            "cl_attendance",
            $ins
        );
        // get the last insert id
        $insertId = $this->db->insert_id();
        // when insert id found
        if ($insertId) {

            // add the entry to log table
            $insLog = [];
            $insLog["cl_attendance_sid"] = $insertId;
            $insLog["clocked_in"] = $ins["clocked_in"];
            $insLog["lat"] = $post["latitude"];
            $insLog["lng"] = $post["longitude"];
            $insLog["created_at"] = $ins["created_at"];
            $insLog["updated_at"] = $ins["updated_at"];
            // insert log
            $this->db->insert(
                "cl_attendance_log",
                $insLog
            );
            //
            return SendResponse(
                200,
                [
                    "success" => true,
                    "msg" => 'You have successfully "' . ($this->attendance_lib->getEventType($post["type"])) . '".'
                ]
            );
        }
        //
        return SendResponse(
            400,
            [
                "errors" => [
                    "The system failed to mark the attendance."
                ]
            ]
        );
    }

    /**
     * update attendance
     *
     * @param array $post
     */
    private function updateAttendance(array $post)
    {
        // get attendance
        $record = $this->getAttendanceByDate();
        // handles event states
        $response = $this->handleEventStates($record, $post);
        // update main table
        $upd = [];
        //
        if ($post["type"] === "clocked_out" || $post["type"] === "break_started") {
            $upd["clocked_out"] = getSystemDateInUTC();
        }
        $upd["last_event"] = $post["type"] === "break_ended" ? "clocked_in" : $post["type"];
        $upd["updated_at"] = getSystemDate();
        $upd["last_record_time"] = $upd["updated_at"];
        $upd["is_approved"] = 0;
        //
        $this->db
            ->where("sid", $record["sid"])
            ->update(
                "cl_attendance",
                $upd
            );
        //
        return SendResponse(
            200,
            $response
        );
    }

    /**
     * start break
     *
     * @param int $attendanceId
     * @param array $post
     * @return array
     */
    private function startBreak(array $post, int $attendanceId)
    {
        // first clock out
        $response = $this->clockOut($post, $attendanceId);
        // check for errors
        if ($response["errors"]) {
            return $response;
        }
        // add the entry to log table
        $insLog = [];
        $insLog["cl_attendance_sid"] = $attendanceId;
        $insLog["break_start"] = getSystemDateInUTC();
        $insLog["lat"] = $post["latitude"];
        $insLog["lng"] = $post["longitude"];
        $insLog["created_at"] = $insLog["break_start"];
        $insLog["updated_at"] = $insLog["created_at"];
        // insert break
        $this->db
            ->insert(
                "cl_attendance_log",
                $insLog
            );
        //
        return ["success" => true];
    }

    /**
     * end break
     *
     * @param int $attendanceId
     * @param array $post
     * @return array
     */
    private function endBreak(array $post, int $attendanceId)
    {
        // first clock out
        $response = $this->clockIn($post, $attendanceId);
        // check for errors
        if ($response["errors"]) {
            return $response;
        }
        // get last open break
        $record = $this->db
            ->select("sid, break_start")
            ->where("cl_attendance_sid", $attendanceId)
            ->where("break_start is not null", null, null)
            ->where("break_end is null", null, null)
            ->limit(1)
            ->get("cl_attendance_log")
            ->row_array();
        // when there is no open slot
        if (!$record) {
            return [
                "success" => true,
                "duration" => 0
            ];
        }
        // add the entry to log table
        $updLog = [];
        $updLog["break_end"] = getSystemDateInUTC();
        $updLog["lat_2"] = $post["latitude"];
        $updLog["lng_2"] = $post["longitude"];
        $updLog["duration"] = $this->attendance_lib
            ->getDurationInMinutes(
                $record["break_start"],
                $updLog["break_end"]
            );
        $updLog["updated_at"] = $updLog["break_end"];
        // update the record
        $this->db
            ->where("sid", $record["sid"])
            ->update(
                "cl_attendance_log",
                $updLog
            );
        //
        return [
            "success" => true,
            "duration" => $updLog["duration"]
        ];
    }

    /**
     * clock in
     *
     * @param int $attendanceId
     * @param array $post
     * @return array
     */
    private function clockIn(array $post, int $attendanceId)
    {
        // add the entry to log table
        $insLog = [];
        $insLog["cl_attendance_sid"] = $attendanceId;
        $insLog["clocked_in"] = getSystemDateInUTC();
        $insLog["lat"] = $post["latitude"];
        $insLog["lng"] = $post["longitude"];
        $insLog["created_at"] = $insLog["clocked_in"];
        $insLog["updated_at"] = $insLog["created_at"];
        // insert clock in
        $this->db
            ->insert(
                "cl_attendance_log",
                $insLog
            );
        //
        return ["success" => true];
    }

    /**
     * clock out
     *
     * @param int $attendanceId
     * @param array $post
     * @return array
     */
    private function clockOut(array $post, int $attendanceId)
    {
        // get the last open clock in
        $record = $this->db
            ->select("sid, clocked_in")
            ->where("cl_attendance_sid", $attendanceId)
            ->where("clocked_in is not null", null, null)
            ->where("clocked_out is null", null, null)
            ->limit(1)
            ->get("cl_attendance_log")
            ->row_array();
        // when there is no open slot
        if (!$record) {
            return [
                "success" => true,
                "duration" => 0
            ];
        }
        // update table log
        $updLog = [];
        $updLog["clocked_out"] = getSystemDateInUTC();
        $updLog["lat_2"] = $post["latitude"];
        $updLog["lng_2"] = $post["longitude"];
        $updLog["duration"] = $this->attendance_lib
            ->getDurationInMinutes(
                $record["clocked_in"],
                $updLog["clocked_out"]
            );
        $updLog["updated_at"] = $updLog["clocked_out"];
        // update the record
        $this->db
            ->where("sid", $record["sid"])
            ->update(
                "cl_attendance_log",
                $updLog
            );
        //
        return [
            "success" => true,
            "duration" => $updLog["duration"]
        ];
    }

    /**
     * handle event states
     *
     * @param array $record
     * @param array $post
     * @return array
     */
    private function handleEventStates(array $record, array $post): array
    {
        // set default errors
        $errors = [];
        if (
            $record["last_event"] === "clocked_in" &&
            $post["type"] !== "break_started" &&
            $post["type"] !== "clocked_out"
        ) {
            $errors =  ['You can only "Start your break" or "Clock out".'];
        } elseif (
            $record["last_event"] === "break_started" &&
            $post["type"] !== "break_ended" &&
            $post["type"] !== "clocked_out"
        ) {
            $errors =  ['You can only "End your break" or "Clock out".'];
        } elseif (
            $record["last_event"] === "clocked_out" &&
            $post["type"] !== "clocked_in"
        ) {
            $errors =  ['You can only "Clock in".'];
        }
        //
        if ($post["type"] !== "clocked_in" && !$this->attendance_lib->canMark($record["last_record_time"])) {
            $errors =  ["Please wait one minute."];
        }
        // check for errors
        if ($errors) {
            return SendResponse(
                400,
                [
                    "errors" => $errors
                ]
            );
        }
        //
        $response = [];
        // star break
        if ($post["type"] === "break_started") {
            $response = $this->startBreak($post, $record["sid"]);
        }
        // end break
        elseif ($post["type"] === "break_ended") {
            $response = $this->endBreak($post, $record["sid"]);
        }
        // clock out
        elseif ($post["type"] === "clocked_out") {
            $response = $this->clockOut($post, $record["sid"]);
        }
        // clock in
        elseif ($post["type"] === "clocked_in") {
            $response = $this->clockIn($post, $record["sid"]);
        }

        if ($response["errors"]) {
            //
            return SendResponse(
                400,
                $response
            );
        }

        return $response;
    }

    /**
     * get attendance logs in range
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getAttendanceInRange(string $startDate, string $endDate): array
    {
        return $this->db
            ->select("
                sid,
                clocked_in,
                last_event
            ")
            ->where("company_sid", $this->companyId)
            ->where("employee_sid", $this->employeeId)
            ->where("clocked_date >= ", $startDate)
            ->where("clocked_date <= ", $endDate)
            ->get("cl_attendance")
            ->result_array();
    }


    /**
     * get attendance logs in range
     *
     * @param array $records
     * @param array $datesPool
     * @return array
     */
    private function fillDatesPool(array $attendanceRecords, array $datesPool): array
    {
        //
        foreach ($attendanceRecords as $v0) {
            //
            $tmp = explode(" ", $v0["clocked_in"])[0];
            // get the attendance logs
            $records = $this->getAttendanceLogs($v0["sid"]);
            // set the time to add
            $timeInMinutes = 0;

            if ($records) {
                // set today date in UTC
                $todayDateOBJ = new DateTime("now", new DateTimeZone("UTC"));
                //
                foreach ($records as $v1) {
                    // for clock in
                    if ($v1["clocked_in"]) {
                        // convert date to object
                        $clockedInObj = new DateTime($v1["clocked_in"]);
                        // when clocked in with no clock out for today
                        if (
                            $v1["clocked_in"] &&
                            !$v1["clocked_out"] &&
                            $clockedInObj->format(DB_DATE) ===
                            $todayDateOBJ->format(DB_DATE)
                        ) {
                            $v1["clocked_out"] = getSystemDateInUTC();
                        }
                        // when clocked in with no clock out for previous dates
                        if (
                            $v1["clocked_in"] &&
                            !$v1["clocked_out"] &&
                            $clockedInObj->format(DB_DATE) <=
                            $todayDateOBJ->format(DB_DATE)
                        ) {
                            $v1["clocked_out"] =
                                $clockedInObj->format(DB_DATE) + " 23:59:59";
                        }
                        // get the difference
                        $timeInMinutes +=
                            $this->attendance_lib
                            ->getDurationInMinutes($v1["clocked_in"], $v1["clocked_out"]);
                    }
                    // for break
                    elseif ($v1["break_start"]) {
                        // convert date to object
                        $breakInObj = new DateTime($v1["break_start"]);
                        // when clocked in with no clock out for today
                        if (
                            $v1["break_start"] &&
                            !$v1["break_end"] &&
                            $breakInObj->format(DB_DATE) ===
                            $todayDateOBJ->format(DB_DATE)
                        ) {
                            $v1["break_end"] = getSystemDateInUTC();
                        }
                        // when clocked in with no clock out for previous dates
                        if (
                            $v1["break_start"] &&
                            !$v1["break_end"] &&
                            $breakInObj->format(DB_DATE) <=
                            $todayDateOBJ->format(DB_DATE)
                        ) {
                            $v1["break_end"] =
                                $breakInObj->format(DB_DATE) + " 23:59:59";
                        }
                        // get the difference
                        $timeInMinutes +=
                            $this->attendance_lib
                            ->getDurationInMinutes($v1["break_start"], $v1["break_end"]);
                    }
                }
            }

            // add the worked time
            $datesPool[$tmp] = $this->attendance_lib
                ->convertSecondsToHours($timeInMinutes)["hours"];
        }
        //
        return $datesPool;
    }

    /**
     * get employee jobs by shift
     *
     * @return array
     */
    private function getJobs()
    {
        // get the employee shifts
    }
}
