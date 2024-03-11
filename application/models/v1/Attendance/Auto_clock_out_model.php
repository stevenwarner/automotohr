<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Cron CRON model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Attendance
 */
class Auto_clock_out_model extends CI_Model
{
    /**
     * holds the date
     * @var string
     */
    private $clockDate;

    /**
     * main entry point
     */
    public function __construct()
    {
        parent::__construct();
        //
        $this->load->library("attendance_lib");
    }

    /**
     * Auto clock out process
     */
    public function startProcess(
        int $companyId,
        string $hours,
        string $date
    ) {
        // convert hours to seconds
        $seconds = $hours * 60 * 60;
        // get the company clocked in employees
        $attendanceRecords = $this->getCompanyClockedInEmployeesByDate(
            $companyId,
            $date
        );
        // when no employees are found
        if (!$attendanceRecords) {
            return "No attendanceRecords found";
        }
        //
        $this->clockDate = $date;
        //
        foreach ($attendanceRecords as $v0) {
            // get the employee worked time
            $duration = $this->getAttendanceLoggedInDuration(
                $v0["sid"]
            );
            //
            if ($duration >= $seconds) {
                $this->clockOut(
                    [
                        "latitude" => null,
                        "longitude" => null,
                        "confirmed" => true,
                        "type" => "clocked_out"
                    ],
                    $v0["sid"]
                );
            }
        }
    }

    /**
     * get the attendance log
     */
    private function getCompanyClockedInEmployeesByDate(
        int $companyId,
        string $date
    ) {
        return $this->db
            ->select("sid, employee_sid")
            ->where([
                "company_sid" => $companyId,
                "clocked_date" => $date,
                "clocked_out IS NULL" => null
            ])
            ->get("cl_attendance")
            ->result_array();
    }

    /**
     * get the duration
     */
    private function getAttendanceLoggedInDuration(
        int $attendanceLogId
    ) {
        // get the attendance logs
        $records = $this->getAttendanceLogs($attendanceLogId);
        // set the time to add
        $timeInMinutes = 0;
        $timeInMinutesForBreaks = 0;

        if ($records) {
            // set today date in UTC
            $todayDateOBJ = new DateTime("now", new DateTimeZone(DB_TIMEZONE));
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
                            convertTimeZone(
                                $clockedInObj->format(DB_DATE) . " 23:59:59",
                                DB_DATE_WITH_TIME,
                                STORE_DEFAULT_TIMEZONE_ABBR,
                                DB_TIMEZONE
                            );
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
                            $breakInObj->format(DB_DATE) . " 23:59:59";
                    }
                    // get the difference
                    $timeInMinutesForBreaks +=
                        $this->attendance_lib
                        ->getDurationInMinutes($v1["break_start"], $v1["break_end"]);
                }
            }
        }

        return $timeInMinutes * 60;
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
                break_id,
                break_start,
                break_end,
                duration,
                job_site_sid,
            ")
            ->where("cl_attendance_sid", $attendanceId)
            ->order_by("sid", "DESC")
            ->get("cl_attendance_log")
            ->result_array();
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
        // get last open break
        $record = $this->db
            ->select("sid, break_start, job_site_sid")
            ->where("cl_attendance_sid", $attendanceId)
            ->where("break_start is not null", null, null)
            ->where("break_end is null", null, null)
            ->limit(1)
            ->get("cl_attendance_log")
            ->row_array();
        //
        $post["job_site"] = 0;
        //
        if ($record) {
            $post["job_site"] = $record["job_site_sid"];
        }
        // first clock out
        $response = $this->clockIn($post, $attendanceId);
        // check for errors
        if ($response["errors"]) {
            return $response;
        }
        // when there is no open slot
        if (!$record) {
            return [
                "success" => true,
                "duration" => 0
            ];
        }
        // add the entry to log table
        $updLog = [];
        $updLog["break_end"] =
            $this->clockDate . " " . getSystemDateInUTC(TIME);
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
     * check and end break break
     *
     * @param int $attendanceId
     * @param array $post
     * @return array
     */
    private function checkAndEndBreak(array $post, int $attendanceId)
    {
        // get last open break
        $record = $this->db
            ->select("sid, break_start, job_site_sid")
            ->where("cl_attendance_sid", $attendanceId)
            ->where("break_start is not null", null, null)
            ->where("break_end is null", null, null)
            ->limit(1)
            ->get("cl_attendance_log")
            ->row_array();
        if ($record) {
            return $this->endBreak($post, $attendanceId);
        }
        return [];
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
        $insLog["clocked_in"] =
            $this->clockDate . " " . getSystemDateInUTC(TIME);
        $insLog["job_site_sid"] = $post["job_site"];
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
        // check and end break
        $this->checkAndEndBreak($post, $attendanceId);
        // get the last open clock in
        $record = $this->db
            ->select("sid, clocked_in, job_site_sid")
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
                "duration" => 0,
                "job_site_sid" => 0
            ];
        }
        // update table log
        $updLog = [];
        $updLog["clocked_out"] =
            $this->clockDate . " " . getSystemDateInUTC(TIME);
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

        // update main table
        $upd = [];
        //
        if ($post["type"] === "clocked_out") {
            $upd["clocked_out"] = $this->clockDate . " " . getSystemDateInUTC(TIME);
        } else {
            $upd["clocked_out"] = null;
        }
        $upd["last_event"] = $post["type"];
        $upd["updated_at"] = getSystemDate();
        $upd["last_record_time"] = $this->clockDate . " " . getSystemDateInUTC(TIME);
        $upd["is_approved"] = 0;
        //
        $this->db
            ->where("sid", $attendanceId)
            ->update(
                "cl_attendance",
                $upd
            );

        //
        return [
            "success" => true,
            "duration" => $updLog["duration"],
            "job_site_sid" => $record["job_site_sid"]
        ];
    }
}
