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
class Manager_clock_model extends Base_model
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
     * holds the date
     * @var string
     */
    private $date;

    /**
     * holds the date in UTC
     * @var string
     */
    private $dateInUTC;

    /**
     * logged in person date time
     * @var string
     */
    private $loggedInPersonDate;


    /**
     * logged in person date time
     * @var string
     */
    private $loggedInPersonDateTime;

    /**
     * holds the clock date
     * @var string
     */
    private $clockDate;


    public function __construct()
    {
        $this->loggedInPersonDateTime = getSystemDateInLoggedInPersonTZ(DB_DATE_WITH_TIME);
        $this->loggedInPersonDate = getSystemDateInLoggedInPersonTZ(DB_DATE);
    }

    public function getAttendanceWithinRange(
        int $companyId,
        int $employeeId,
        string $startDate,
        string $endDate
    ): array {
        $session = checkAndGetSession("all");
        // convert the incoming dates to utc
        //
        $records = $this->getAttendanceByDates(
            $companyId,
            $employeeId,
            $startDate,
            $endDate
        );
        // when no record are found
        if (!$records) {
            return [];
        }
        // get the employee shift
        // set new record array
        $returnArray = [];
        //
        foreach ($records as $v0) {
            // set defaults
            $arr = $v0;
            //
            $arr["clocked_in"] = convertTimeZone(
                $v0["clocked_in"],
                DB_DATE_WITH_TIME,
                DB_TIMEZONE,
                getLoggedInPersonTimeZone()
            );

            if ($v0["clocked_out"]) {
                $arr["clocked_out"] =
                    convertTimeZone(
                        $v0["clocked_out"],
                        DB_DATE_WITH_TIME,
                        DB_TIMEZONE,
                        getLoggedInPersonTimeZone()
                    );
            }
            $arr["worked_time"] = 0;
            $arr["overtime"] = 0;
            $arr["breaks"] = 0;
            $arr["logs"] = [];
            // get the attendance log
            $arr["logs"] = $this->db
                ->select("
                    sid,
                    job_site_sid,
                    clocked_in,
                    clocked_out,
                    break_id,
                    break_start,
                    break_end,
                    duration,
                    lat,
                    lng,
                    lat_2,
                    lng_2
            ")
                ->where("cl_attendance_sid", $v0["sid"])
                ->get("cl_attendance_log")
                ->result_array();
            //
            foreach ($arr["logs"] as $k1 => $v1) {

                if ($v1["clocked_in"]) {
                    //
                    $arr["logs"][$k1]["clocked_in"] = convertTimeZone(
                        $v1["clocked_in"],
                        DB_DATE_WITH_TIME,
                        DB_TIMEZONE,
                        getLoggedInPersonTimeZone()
                    );

                    if ($v1["clocked_out"]) {
                        $arr["logs"][$k1]["clocked_out"] =
                            convertTimeZone(
                                $v1["clocked_out"],
                                DB_DATE_WITH_TIME,
                                DB_TIMEZONE,
                                getLoggedInPersonTimeZone()
                            );
                    }
                }

                if ($v1["break_start"]) {
                    $arr["logs"][$k1]["break_start"] =
                        convertTimeZone(
                            $v1["break_start"],
                            DB_DATE_WITH_TIME,
                            DB_TIMEZONE,
                            getLoggedInPersonTimeZone()
                        );
                    if ($v1["break_end"]) {
                        $arr["logs"][$k1]["break_end"] =
                            convertTimeZone(
                                $v1["break_end"],
                                DB_DATE_WITH_TIME,
                                DB_TIMEZONE,
                                getLoggedInPersonTimeZone()
                            );
                    }
                }


                if ($v1["clocked_in"]) {
                    $arr["worked_time"] += $v1["duration"];
                } else {
                    $arr["breaks"] += $v1["duration"];
                }
            }
            //
            if ($arr["worked_time"] > 144000) {
                $arr["overtime"] = $arr["worked_time"] - 144000;
            }

            // set it
            $returnArray[$v0["clocked_date"]] = $arr;
        }
        //
        return $returnArray;
    }

    /**
     * get the record
     *
     * @return array
     */
    private function getAttendanceByDates(
        int $companyId,
        int $employeeId,
        string $startDate,
        string $endDate
    ): array {
        return $this->db
            ->select("
                sid,
                clocked_in_utc as clocked_in,
                clocked_out_utc as clocked_out,
                is_approved,
                clocked_date
            ")
            ->where([
                "employee_sid" => $employeeId,
                "company_sid" => $companyId,
                "clocked_date >= " => $startDate,
                "clocked_date <= " => $endDate,
            ])
            ->order_by("clocked_date", "ASC")
            ->get("cl_attendance")
            ->result_array();
    }

    /**
     * Calculate the worked time within range
     *
     * @param int $employeeId
     * @param string $periodStartDate Y-m-d
     * @param string $periodEndDate Y-m-d
     * @return array
     */
    public function calculateTimeWithinRange(
        int $employeeId,
        string $periodStartDate,
        string $periodEndDate
    ): array {
        // set new record array
        $returnArray = [
            "periods" => [],
            "schedule_time" => 0,
            "difference_time" => 0,
            "clocked_time" => 0,
            "worked_time" => 0,
            "regular_time" => 0,
            "breaks_time" => 0,
            "paid_break_time" => 0,
            "unpaid_break_time" => 0,
            "overtime" => 0,
            "double_overtime" => 0,
            "normal_rate" => 0,
            "over_time_rate" => 0,
            "double_over_time_rate" => 0,
            "paid_time_off" => [],
            "shift_status" => [
                "approved_count" => 0,
                "unapproved_count" => 0,
                "total_shifts" => 0
            ],
            "text" => [
                "schedule_time" => "0h",
                "difference_time" => "0h",
                "clocked_time" => '0h',
                "worked_time" => '0h',
                "regular_time" => '0h',
                "breaks_time" => '0h',
                "paid_break_time" => '0h',
                "unpaid_break_time" => '0h',
                "overtime" => '0h',
                "double_overtime" => '0h',
                "overtime_detail" => '0h',
                "double_overtime_detail" => '0h',
            ]
        ];
        //
        $records =
            $this->db
            ->select("
                sid,
                company_sid,
                clocked_in,
                clocked_out,
                is_approved,
                clocked_date
            ")
            ->where([
                "employee_sid" => $employeeId,
                "clocked_date >= " => $periodStartDate,
                "clocked_date <= " => $periodEndDate,
            ])
            ->get("cl_attendance")
            ->result_array();
        // when no record are found
        if (!$records) {
            return $returnArray;
        }
        //
        // load break model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // load shift model
        $this->load->model("v1/Shift_model", "shift_model");
        // load holiday model
        $this->load->model("v1/Holiday_model", "holiday_model");
        //
        $this->load->model("v1/Users/main_model", "main_model");
        //
        $this->load->model("Timeoff_model", "timeoff_model");
        // get employee shifts
        $returnArray['paid_time_off'] = $this->timeoff_model
            ->getEmployeePaidTimeOffsInRange(
                $employeeId,
                $periodStartDate,
                $periodEndDate
            );
        //
        $employeeWageInfo = $this->main_model->getJobWageData(
            $employeeId,
            'employee',
            $records[0]["company_sid"]

        );
        //
        // get employee overtime rule
        $employeeOverTime = $this->getEmployeeOverTimeRule($employeeId);
        // get company breaks
        $companyBreaks = $this->shift_break_model->get($records[0]["company_sid"], false);
        // get company holidays date pool
        $companyHolidays = $this->holiday_model->get($records[0]["company_sid"], getSystemDate("Y", $periodStartDate), true);
        // convert the array to list
        $companyBreaks = convertToList($companyBreaks, "break_name");
        // get employee shifts within range
        $employeeShifts = $this->shift_model->getEmployeeShiftsWithinRange(
            $employeeId,
            $periodStartDate,
            $periodEndDate
        );
        //
        $employeeRate = getRatePerHour($employeeWageInfo['rate'], $employeeWageInfo['per']);
        //
        $returnArray['normal_rate'] = $employeeRate;
        $returnArray['over_time_rate'] = $employeeRate * $employeeOverTime['overtime_multiplier'];
        $returnArray['double_over_time_rate'] = $employeeRate * $employeeOverTime['double_overtime_multiplier'];
        //
        foreach ($records as $v0) {
            //
            $returnArray['shift_status']['total_shifts'] += 1;
            //
            if ($v0["is_approved"] == 1) {
                $returnArray['shift_status']['approved_count'] += 1;
            } else {
                $returnArray['shift_status']['unapproved_count'] += 1;
            }
            // set a tmp array
            $tmp = [
                "date" => $v0["clocked_date"],
                "schedule_time" => 0,
                "difference_time" => 0,
                "clocked_time" => 0,
                "worked_time" => 0,
                "regular_time" => 0,
                "breaks_time" => 0,
                "paid_break_time" => 0,
                "unpaid_break_time" => 0,
                "overtime" => 0,
                "double_overtime" => 0,
                "text" => [
                    "schedule_time" => '0h',
                    "difference_time" => '',
                    "clocked_time" => '0h',
                    "worked_time" => '0h',
                    "regular_time" => '0h',
                    "breaks_time" => '0h',
                    "paid_break_time" => '0h',
                    "unpaid_break_time" => '0h',
                    "overtime" => '0h',
                    "double_overtime" => '0h',
                ]
            ];
            // get the attendance log
            $logs = $this->db
                ->select("
                    job_site_sid,
                    clocked_in,
                    clocked_out,
                    break_id,
                    break_start,
                    break_end,
                    duration,
            ")
                ->where("cl_attendance_sid", $v0["sid"])
                ->get("cl_attendance_log")
                ->result_array();
            //
            foreach ($logs as $v1) {
                // clocked in time
                if ($v1["clocked_in"]) {
                    $tmp["clocked_time"] += $v1["duration"];
                } else {
                    // for break
                    if ($v1["break_id"]) {
                        // get the relevant break
                        $break = stringToSlug($employeeShifts[$v0["clocked_date"]]["breaks"][$v1["break_id"]]["break"] ?? "", "_");
                        // check if exists in company breaks
                        if ($companyBreaks[$break]) {
                            // check wether the break is paid or unpaid
                            // case of paid
                            if ($companyBreaks[$break]["break_type"] === "paid") {
                                $tmp["paid_break_time"] += $v1["duration"];
                            } else {
                                //case of unpaid
                                $tmp["unpaid_break_time"] += $v1["duration"];
                            }
                        } else {
                            // case when no company break found
                            $tmp["unpaid_break_time"] += $v1["duration"];
                        }
                    } else {
                        // when no break id found mark it as unpaid
                        $tmp["unpaid_break_time"] += $v1["duration"];
                    }
                    // in any case add the time to break
                    $tmp["breaks_time"] += $v1["duration"];
                }
            }
            //
            $tmp["clocked_time"] =  $tmp["clocked_time"] + ($tmp["unpaid_break_time"] + $tmp["paid_break_time"]);
            $tmp["worked_time"] =  $tmp["clocked_time"] - ($tmp["unpaid_break_time"] + $tmp["paid_break_time"]);
            // calculate
            // calculate overtime for daily
            if ($employeeOverTime["daily"]) {
                // get day
                $day = strtolower(formatDateToDB(
                    $v0["clocked_date"],
                    DB_DATE,
                    "l"
                ));
                //
                // for holiday
                if ($employeeOverTime["holiday"] && $employeeOverTime["holiday"]["overtime"] && in_array($v0["clocked_date"], $companyHolidays)) {
                    // convert overtime to seconds
                    $overtime = ($employeeOverTime["holiday"]["overtime"] ?? 0) * 60 * 60;
                    // convert double-overtime to seconds
                    $double_overtime = ($employeeOverTime["holiday"]["double_overtime"] ?? 0) * 60 * 60;
                    // check if clocked time is > overtime
                    if ($tmp["worked_time"] > $overtime) {
                        $tmp["overtime"] = $tmp["worked_time"] - $overtime;
                    }
                    // for double overtime
                    if ($double_overtime > 0 && ($tmp["worked_time"] > $double_overtime)) {
                        // deduct the double overtime from clocked time
                        $tmp["double_overtime"] = $tmp["worked_time"] - $double_overtime;
                        // recalculate overtime
                        $tmp["overtime"] = $double_overtime - $overtime;
                    }
                }
                // for daily overtime
                elseif ($employeeOverTime["daily"][$day] && $employeeOverTime["daily"][$day]["overtime"]) {
                    // convert overtime to seconds
                    $overtime = ($employeeOverTime["daily"][$day]["overtime"] ?? 0) * 60 * 60;
                    // convert double-overtime to seconds
                    $double_overtime = ($employeeOverTime["daily"][$day]["double_overtime"] ?? 0) * 60 * 60;
                    // check if clocked time is > overtime
                    if ($tmp["worked_time"] > $overtime) {
                        $tmp["overtime"] = $tmp["worked_time"] - $overtime;
                    }
                    // for double overtime
                    if ($double_overtime > 0 && ($tmp["worked_time"] > $double_overtime)) {
                        // deduct the double overtime from clocked time
                        $tmp["double_overtime"] = $tmp["worked_time"] - $double_overtime;
                        // recalculate overtime
                        $tmp["overtime"] = $double_overtime - $overtime;
                    }
                }
                // TODO: seven consecutive day overtime
            }
            // convert to text
            $regularTime = $tmp["worked_time"] - ($tmp["overtime"] + $tmp["double_overtime"]);
            $tmp['regular_time'] = $regularTime;
            //
            if ($employeeShifts[$v0['clocked_date']]) {
                $shiftStart = $v0['clocked_date'].' '.$employeeShifts[$v0['clocked_date']]["start_time"];
                $shiftEnd = $v0['clocked_date'].' '.$employeeShifts[$v0['clocked_date']]["end_time"];
                //
                $d1Obj = new DateTime($shiftStart, new DateTimeZone("UTC"));
                // convert d1 to UTC
                $d2Obj = new DateTime($shiftEnd, new DateTimeZone("UTC"));
                // apply difference
                $diff = $d2Obj->diff($d1Obj);
                // if diference is off one minute
                $shiftDuration =
                    $diff->s
                    + ($diff->i * 60)
                    + ($diff->h * 3600)
                    + ($diff->d * 86400)
                    + ($diff->m * 2592000)
                    + ($diff->y * 31536000);
                //
                $tmp['schedule_time'] = $shiftDuration;
                $tmp['difference_time'] = $tmp['clocked_time'] - $shiftDuration;
            } else {
                $tmp['difference_time'] = $tmp['clocked_time'];
            }
            //
            $shifyDifference = '';
            //
            if ($tmp["difference_time"] < 0) {
                $shifyDifference = '<span class="label label-danger"> -'.convertSecondsToTime(str_replace('-', '', $tmp["difference_time"])).' </span>';
            } else if ($tmp["difference_time"] > 0) {
                $shifyDifference = '<span class="label label-warning"> +'.convertSecondsToTime($tmp["difference_time"]).'</span>';
            }
            //
            $tmp["text"] = [
                "schedule_time" => convertSecondsToTime($tmp['schedule_time']),
                "difference_time" => $shifyDifference,
                "clocked_time" => convertSecondsToTime($tmp['clocked_time']),
                "worked_time" => convertSecondsToTime($tmp["worked_time"]),
                "regular_time" => convertSecondsToTime($tmp['regular_time']),
                "breaks_time" => convertSecondsToTime($tmp["breaks_time"]),
                "paid_break_time" => convertSecondsToTime($tmp["paid_break_time"]),
                "unpaid_break_time" => convertSecondsToTime($tmp["unpaid_break_time"]),
                "overtime" => convertSecondsToTime($tmp["overtime"]),
                "double_overtime" => convertSecondsToTime($tmp["double_overtime"]),
            ];
            //
            // set to main array
            $returnArray["periods"][] = $tmp;
            $returnArray["schedule_time"] += $tmp['schedule_time'];
            $returnArray["difference_time"] += $tmp['difference_time'];
            $returnArray["clocked_time"] += $tmp['clocked_time'];
            $returnArray["worked_time"] += $tmp['worked_time'];
            $returnArray["regular_time"] += $tmp['regular_time'];
            $returnArray["breaks_time"] += $tmp["breaks_time"];
            $returnArray["paid_break_time"] += $tmp["paid_break_time"];
            $returnArray["unpaid_break_time"] += $tmp["unpaid_break_time"];
            $returnArray["overtime"] += $tmp["overtime"];
            $returnArray["double_overtime"] += $tmp["double_overtime"];
        }
        //
        // apply weekly overtime
        if ($employeeOverTime["weekly"] && $employeeOverTime["weekly"]["overtime"]) {
            // convert overtime to seconds
            $overtime = ($employeeOverTime["weekly"]["overtime"] ?? 0) * 60 * 60;
            // convert double-overtime to seconds
            $double_overtime = ($employeeOverTime["weekly"]["double_overtime"] ?? 0) * 60 * 60;
            //
            $weekOverTime = 0;
            $weekDoubleOvertime = 0;
            // check if clocked time is > overtime
            if ($returnArray["worked_time"] > $overtime) {
                $weekOverTime = $returnArray["worked_time"] - $overtime;
            }
            // for double overtime
            if ($double_overtime > 0 && ($returnArray["worked_time"] > $double_overtime)) {
                // deduct the double overtime from clocked time
                $weekDoubleOvertime = $returnArray["worked_time"] - $double_overtime;
                // recalculate overtime
                $weekOverTime = $double_overtime - $overtime;
            }
            //
            $overtime_detail = "Daily overtime: " . convertSecondsToTime($returnArray["overtime"])
                . " <br> Weekly overtime: " . convertSecondsToTime($weekOverTime)
                . " <br> Total overtime: " . convertSecondsToTime($returnArray["overtime"] + $weekOverTime);
            $double_overtime_detail = "Daily double overtime: " . convertSecondsToTime($returnArray["double_overtime"])
                . " <br> Weekly double overtime: " . convertSecondsToTime($weekDoubleOvertime)
                . " <br> Total double overtime: " . convertSecondsToTime($returnArray["double_overtime"] + $weekDoubleOvertime);
            //
            $returnArray["overtime"] += $weekOverTime;
            $returnArray["double_overtime"] += $weekDoubleOvertime;
        }
        // apply seven consecutive day overtime
        // convert to text
        $totalDifference = '';
        
        if ($returnArray["difference_time"] < 0) {
            $totalDifference = '<span class="label label-danger"> -'.convertSecondsToTime($returnArray["difference_time"]).' </span>';
        } else if ($returnArray["difference_time"] > 0) {
            $totalDifference = '<span class="label label-warning"> +'.convertSecondsToTime($returnArray["difference_time"]).'</span>';
        }
        //
        $returnArray["text"] = [
            "schedule_time" => convertSecondsToTime($returnArray["schedule_time"]),
            "difference_time" => $totalDifference,
            "clocked_time" => convertSecondsToTime($returnArray["clocked_time"]),
            "worked_time" => convertSecondsToTime($returnArray["worked_time"]),
            "regular_time" => convertSecondsToTime($returnArray["regular_time"]),
            "breaks_time" => convertSecondsToTime($returnArray["breaks_time"]),
            "paid_break_time" => convertSecondsToTime($returnArray["paid_break_time"]),
            "unpaid_break_time" => convertSecondsToTime($returnArray["unpaid_break_time"]),
            "overtime" => convertSecondsToTime($returnArray["overtime"]),
            "double_overtime" => convertSecondsToTime($returnArray["double_overtime"]),
            "overtime_detail" => $overtime_detail,
            "double_overtime_detail" => $double_overtime_detail,
        ];
        //
        return $returnArray;
    }

    public function getEmployeeOverTimeRule(int $employeeId)
    {
        // set the default array
        $returnArray = [];
        //
        $returnArray["overtime_multiplier"] = 1.5;
        $returnArray["double_overtime_multiplier"] = 2.0;
        //
        $record = $this->db
            ->select("
                company_overtime_rules.overtime_multiplier,
                company_overtime_rules.double_overtime_multiplier,
                company_overtime_rules.daily_json,
                company_overtime_rules.weekly_json,
                company_overtime_rules.seven_consecutive_days_json,
                company_overtime_rules.holiday_json
            ")
            ->join(
                "company_overtime_rules",
                "company_overtime_rules.sid = users.overtime_rule",
                "inner"
            )
            ->where("users.sid", $employeeId)
            ->get("users")
            ->row_array();
        //
        if (!$record) {
            return $returnArray;
        }
        $returnArray["overtime_multiplier"] = $record["overtime_multiplier"];
        $returnArray["double_overtime_multiplier"] = $record["double_overtime_multiplier"];
        //
        $record["daily_json"] = json_decode($record["daily_json"], true);
        $record["weekly_json"] = json_decode($record["weekly_json"], true);
        $record["seven_consecutive_days_json"] = json_decode($record["seven_consecutive_days_json"], true);
        $record["holiday_json"] = json_decode($record["holiday_json"], true);

        // set holiday overtime
        if (
            $record["holiday_json"]["overtime"]["status"] ||
            $record["holiday_json"]["double"]["status"]
        ) {
            $returnArray["holiday"]["type"] = "holiday";
            $returnArray["holiday"]["overtime"] = $record["holiday_json"]["overtime"]["hours"] ? $record["holiday_json"]["overtime"]["hours"] : 0;
            $returnArray["holiday"]["double_overtime"] = $record["holiday_json"]["double"]["hours"] ? $record["holiday_json"]["double"]["hours"] : 0;
        }
        // set seven consecutive days overtime
        if (
            $record["seven_consecutive_days_json"]["overtime"]["status"] ||
            $record["seven_consecutive_days_json"]["double"]["status"]
        ) {
            $returnArray["seven"]["type"] = "seven";
            $returnArray["seven"]["overtime"] = $record["seven_consecutive_days_json"]["overtime"]["hours"] ? $record["seven_consecutive_days_json"]["overtime"]["hours"] : 0;
            $returnArray["seven"]["double_overtime"] = $record["seven_consecutive_days_json"]["double"]["hours"] ? $record["seven_consecutive_days_json"]["double"]["hours"] : 0;
        }
        // set weekly overtime
        if (
            $record["weekly_json"]["overtime"]["status"] ||
            $record["weekly_json"]["double"]["status"]
        ) {
            $returnArray["weekly"]["type"] = "weekly";
            $returnArray["weekly"]["overtime"] = $record["weekly_json"]["overtime"]["hours"] ? $record["weekly_json"]["overtime"]["hours"] : 0;
            $returnArray["weekly"]["double_overtime"] = $record["weekly_json"]["double"]["hours"] ? $record["weekly_json"]["double"]["hours"] :  0;
        }
        // set daily overtime
        $returnArray["daily"]["type"] = "daily";
        //
        foreach ($record["daily_json"] as $k0 => $v0) {
            $returnArray["daily"][$k0]["overtime"] = $v0["overtime"]["hours"] ? $v0["overtime"]["hours"] : 0;
            $returnArray["daily"][$k0]["double_overtime"] = $v0["double"]["hours"] ? $v0["double"]["hours"] : 0;
        }

        return $returnArray;
    }

    public function getEmployees($companyId)
    {
        return //
            $this->db
            ->select(getUserFields())
            ->where("active", 1)
            ->where("is_executive_admin", 0)
            ->where("terminated_status", 0)
            ->where("parent_sid", $companyId)
            ->get("users")
            ->result_array();
    }

    /**
     * get the attendance logs
     *
     * @param int $attendanceId
     * @return array
     */
    public function getAttendanceLogsForSheet(int $attendanceId): array
    {
        return $this->db
            ->select("
                sid,
                clocked_in_utc as clocked_in,
                clocked_out_utc as clocked_out,
                break_id,
                break_start_utc as break_start,
                break_end_utc as break_end,
                duration,
                job_site_sid,
            ")
            ->where("cl_attendance_sid", $attendanceId)
            ->order_by("sid", "ASC")
            ->get("cl_attendance_log")
            ->result_array();
    }

    public function processTimeSheetDetails($post)
    {
        if ($post["sid"] == 0) {
            // prepare insert array
            $ins = [];
            $ins["company_sid"] = $post["companyId"];
            $ins["employee_sid"] = $post["employeeId"];
            $ins["clocked_date"] = $post["clockDate"];
            $ins["is_approved"] = 0;
            $ins["last_event"] = "clocked_in";
            $ins["created_at"] = getSystemDate(DB_DATE_WITH_TIME);
            $ins["updated_at"] = $ins["created_at"];
            // insert
            $this->db->insert(
                "cl_attendance",
                $ins
            );
            // get the last insert id
            $post["sid"] = $this->db->insert_id();
            // when insert id found
            if ($post["sid"]) {
                //
                $initialClockIn = '';
                $initialClockOut = '';
                $userTimezone = '';
                foreach ($post["logs"] as $v0) {
                    //
                    $userTimezone = db_get_employee_profile($v0['employeeId'])[0]['timezone'];
                    //
                    $v0["startTime"] = reset_datetime([
                        "datetime" => $post["clockDate"] . " " . $v0["startTime"],
                        "from_format" => "Y-m-d h:i a",
                        "format" => "Y-m-d H:i:s",
                        "_this" => $this,
                        "new_zone" => DB_TIMEZONE,
                        "revert" => true
                    ]);
                    $v0["endTime"] =
                        reset_datetime([
                            "datetime" => $post["clockDate"] . " " . $v0["endTime"],
                            "from_format" => "Y-m-d h:i a",
                            "format" => "Y-m-d H:i:s",
                            "_this" => $this,
                            "new_zone" => DB_TIMEZONE,
                            "revert" => true
                        ]);
                    //
                    $indexOne = $v0["eventType"] == "clocked_in_out" ? "clocked_in" : "break_start";
                    $indexTwo = $v0["eventType"] == "clocked_in_out" ? "clocked_out" : "break_end";

                    if ($indexOne == "clocked_in" && !$initialClockIn) {
                        $initialClockIn = $v0["startTime"];
                    }
                    if ($indexTwo == "clocked_out") {
                        $initialClockOut = $v0["endTime"];
                    }
                    // check

                    // insert
                    // add the entry to log table
                    $insLog = [];
                    $insLog["cl_attendance_sid"] = $post["sid"];
                    $insLog[$indexOne] = convertTimeZone(
                        $v0["startTime"],
                        DB_DATE_WITH_TIME,
                        "UTC",
                        $userTimezone
                    );
                    $insLog[$indexTwo] = convertTimeZone(
                        $v0["endTime"],
                        DB_DATE_WITH_TIME,
                        "UTC",
                        $userTimezone
                    );
                    $insLog[$indexOne.'_utc'] = $v0["startTime"];
                    $insLog[$indexTwo.'_utc'] = $v0["endTime"];
                    $insLog["lat"] = 0;
                    $insLog["lng"] = 0;
                    $insLog["created_at"] = getSystemDate();
                    $insLog["updated_at"] = $insLog["created_at"];
                    $insLog["duration"] =
                        $this->attendance_lib
                        ->getDurationInMinutes(
                            $insLog[$indexOne],
                            $insLog[$indexTwo]
                        );
                    // insert log
                    $this->db->insert(
                        "cl_attendance_log",
                        $insLog
                    );
                }

                if ($initialClockIn) {
                    // insert log
                    $this->db
                        ->where([
                            "sid" => $post["sid"]
                        ])->update(
                            "cl_attendance",
                            [
                                "clocked_date_utc" => formatDateToDB(
                                    $initialClockIn,
                                    DB_DATE_WITH_TIME,
                                    DB_DATE
                                ),
                                "clocked_in" => convertTimeZone(
                                    $initialClockIn,
                                    DB_DATE_WITH_TIME,
                                    "UTC",
                                    $userTimezone
                                ),
                                "last_record_time" => convertTimeZone(
                                    $initialClockIn,
                                    DB_DATE_WITH_TIME,
                                    "UTC",
                                    $userTimezone
                                ),
                                "clocked_in_utc" => $initialClockIn,
                                "last_record_time_utc" => $initialClockIn
                            ]
                        );
                }
                //
                if ($initialClockOut) {
                    // insert log
                    $this->db
                        ->where([
                            "sid" => $post["sid"]
                        ])->update(
                            "cl_attendance",
                            [
                                "clocked_out" => convertTimeZone(
                                    $initialClockOut,
                                    DB_DATE_WITH_TIME,
                                    "UTC",
                                    $userTimezone
                                ),
                                "clocked_out_utc" => $initialClockOut
                            ]
                        );
                }
            }
        } else {
            //
            $initialClockIn = '';
            $initialClockOut = '';
            $state = "clocked_in_out";
            $breakStartTime = "";
            foreach ($post["logs"] as $v0) {
                $v0["startTime"] =
                    reset_datetime([
                        "datetime" => $post["clockDate"] . " " . $v0["startTime"],
                        "from_format" => "Y-m-d h:i a",
                        "format" => "Y-m-d H:i:s",
                        "_this" => $this,
                        "new_zone" => DB_TIMEZONE,
                        "revert" => true
                    ]);
                $v0["endTime"] =
                    reset_datetime([
                        "datetime" => $post["clockDate"] . " " . $v0["endTime"],
                        "from_format" => "Y-m-d h:i a",
                        "format" => "Y-m-d H:i:s",
                        "_this" => $this,
                        "new_zone" => DB_TIMEZONE,
                        "revert" => true
                    ]);
                //
                $indexOne = $v0["eventType"] == "clocked_in_out" ? "clocked_in" : "break_start";
                $indexTwo = $v0["eventType"] == "clocked_in_out" ? "clocked_out" : "break_end";
                $breakStartTime = $v0["endTime"];
                $state = $v0["eventType"];

                if ($indexOne == "clocked_in" && !$initialClockIn) {
                    $initialClockIn = $v0["startTime"];
                }
                if ($indexTwo == "clocked_out") {
                    $initialClockOut = $v0["endTime"];
                }
                // check
                if (!$this->db->where([
                    "sid" => $v0["id"],
                    "cl_attendance_sid" => $post["sid"]
                ])->count_all_results("cl_attendance_log")) {
                    // insert
                    // add the entry to log table
                    $insLog = [];
                    $insLog["cl_attendance_sid"] = $post["sid"];
                    $insLog[$indexOne] = convertTimeZone(
                        $v0["startTime"],
                        DB_DATE_WITH_TIME,
                        "UTC",
                        db_get_employee_profile($v0['employeeId'])[0]['timezone']
                    );
                    $insLog[$indexTwo] = convertTimeZone(
                        $v0["endTime"],
                        DB_DATE_WITH_TIME,
                        "UTC",
                        db_get_employee_profile($v0['employeeId'])[0]['timezone']
                    );
                    $insLog[$indexOne . '_utc'] = $v0["startTime"];
                    $insLog[$indexTwo . '_utc'] = $v0["endTime"];
                    $insLog["duration"] =
                        $this->attendance_lib
                        ->getDurationInMinutes(
                            $insLog[$indexOne],
                            $insLog[$indexTwo]
                        );
                    $insLog["lat"] = 0;
                    $insLog["lng"] = 0;
                    $insLog["created_at"] = getSystemDate();
                    $insLog["updated_at"] = $insLog["created_at"];
                    // insert log
                    $this->db->insert(
                        "cl_attendance_log",
                        $insLog
                    );
                } else {
                    // update
                    $insLog = [];
                    $insLog[$indexOne] = convertTimeZone(
                        $v0["startTime"],
                        DB_DATE_WITH_TIME,
                        "UTC",
                        db_get_employee_profile($v0['employeeId'])[0]['timezone']
                    );
                    $insLog[$indexTwo] = convertTimeZone(
                        $v0["endTime"],
                        DB_DATE_WITH_TIME,
                        "UTC",
                        db_get_employee_profile($v0['employeeId'])[0]['timezone']
                    );
                    $insLog[$indexOne . '_utc'] = $v0["startTime"];
                    $insLog[$indexTwo . '_utc'] = $v0["endTime"];

                    if ($indexOne  === "clocked_in") {
                        $insLog["break_start"] = null;
                        $insLog["break_end"] = null;
                    } else {
                        $insLog["clocked_out"] = null;
                        $insLog["clocked_in"] = null;
                    }
                    $insLog["duration"] =
                        $this->attendance_lib
                        ->getDurationInMinutes(
                            $insLog[$indexOne],
                            $insLog[$indexTwo]
                        );
                    $insLog["updated_at"] = getSystemDate();
                    //
                    // insert log
                    $this->db
                        ->where([
                            "sid" => $v0["id"]
                        ])->update(
                            "cl_attendance_log",
                            $insLog
                        );
                }
            }
            // set update array
            $upd = [];

            // when the person was clocked in
            if ($state === "clocked_in_out") {
                // set the entry to be clocked out
                $upd["last_event"] = "clocked_out";
            } elseif ($state === "break_in_out") {
                // set the entry to be clocked out
                $upd["last_event"] = "clocked_in";
                $upd["clocked_out"] = null;
                // insert
                // add the entry to log table
                $insLog = [];
                $insLog["cl_attendance_sid"] = $post["sid"];
                $insLog["clocked_in"] = convertTimeZone(
                    $breakStartTime,
                    DB_DATE_WITH_TIME,
                    "UTC",
                    db_get_employee_profile($v0['employeeId'])[0]['timezone']
                );
                $insLog["clocked_in_utc"] = $breakStartTime;
                $insLog["lat"] = 0;
                $insLog["lng"] = 0;
                $insLog["created_at"] = getSystemDate();
                $insLog["updated_at"] = $insLog["created_at"];
                // insert log
                $this->db->insert(
                    "cl_attendance_log",
                    $insLog
                );
            }

            if ($initialClockIn) {
                $upd["clocked_in"] = convertTimeZone(
                    $initialClockIn,
                    DB_DATE_WITH_TIME,
                    "UTC",
                    db_get_employee_profile($v0['employeeId'])[0]['timezone']
                );
                $upd["clocked_in_utc"] = $initialClockIn;
            }
            if ($initialClockOut && $state !== "break_in_out") {
                $upd["clocked_out"] = convertTimeZone(
                    $initialClockOut,
                    DB_DATE_WITH_TIME,
                    "UTC",
                    db_get_employee_profile($v0['employeeId'])[0]['timezone']
                );
                $upd["clocked_out_utc"] = $initialClockOut;
            }
            //
            // insert log
            $this->db
                ->where([
                    "sid" => $post["sid"]
                ])->update(
                    "cl_attendance",
                    $upd
                );
        }
    }

    public function getClockedInEmployees ($companyId, $date, $employees) {
        //
        $this->db->select('
            sid,
            employee_sid,
            clocked_date
        ');
        //
        $this->db->where('company_sid', $companyId);
        $this->db->where('clocked_date', $date);
        //
        if ($employees && array_search("all", $employees) === false) {
            $this->db->where_in('employee_sid', $employees);
        }
        //
        $a = $this->db->get('cl_attendance');
        //
        $records = $a->result_array();
        $a = $a->free_result();
        //
        $markers = [];
        //
        if ($records) {
            foreach ($records as $row) {
                $location = $this->db
                ->select("
                    lat,
                    lng,
                    lat_2,
                    lng_2
                ")
                ->where("cl_attendance_sid", $row['sid'])
                ->order_by("sid", "ASC")
                ->get("cl_attendance_log")
                ->row_array();
                //
                if(!empty($location['lat']) || !empty($location['lng'])){
                    //
                    $userInfo = get_employee_profile_info($row['employee_sid']);
                    //
                    $markers[] = [
                        'employeeId' => $row['employee_sid'],
                        'lat' => $location['lat'], 
                        'lng' => $location['lng'], 
                        'logo' => getImageURL($userInfo['profile_picture']),
                        'name' => remakeEmployeeName([
                            'first_name' => $userInfo['first_name'],
                            'last_name' => $userInfo['last_name'],
                            'access_level' => $userInfo['access_level'],
                            'timezone' => isset($userInfo['timezone']) ? $userInfo['timezone'] : '',
                            'access_level_plus' => $userInfo['access_level_plus'],
                            'is_executive_admin' => $userInfo['is_executive_admin'],
                            'pay_plan_flag' => $userInfo['pay_plan_flag'],
                            'job_title' => $userInfo['job_title'],
                        ]) 
                    ];
                }
            } 
        } 
        //
        return $markers; 
    }

    public function getEmployeeLoginHistory ($employeeId, $date) {
        $record =
            $this->db
            ->select("
                sid
            ")
            ->where([
                "employee_sid" => $employeeId,
                "clocked_date" => $date,
            ])
            ->get("cl_attendance")
            ->row_array();
        //
        $history = [
            'logs' => '',
            'locations' => ''
        ]; 
        //    
        if ($record) {
            $history = $this->getTimeSheetHistory($record['sid']);
            //
            if ($history['logs']) {
                $session = checkAndGetSession("all");
                getLoggedInPersonTimeZone() = $session["company_detail"]["timezone"];
                foreach ($history['logs'] as $key => $log) {
                    $history['logs'][$key]['startTime'] = convertTimeZone(
                        $log['startTime'],
                        DB_DATE_WITH_TIME,
                        "UTC",
                        getLoggedInPersonTimeZone()
                    );

                    $history['logs'][$key]['endTime'] = convertTimeZone(
                        $log['endTime'],
                        DB_DATE_WITH_TIME,
                        "UTC",
                        getLoggedInPersonTimeZone()
                    );
                }
            }
            // _e($history['logs'],true,true);
        } 
        //
        return $history;
    }

    public function getTimeSheetHistory($attendanceId)
    {
        //
        $record = $this->db
            ->select("employee_sid, company_sid, clocked_date")
            ->where("sid", $attendanceId)
            ->get("cl_attendance")
            ->row_array();

        $breaks = $this->getAllowedBreaksForEmployee(
            $record["employee_sid"],
            $record["clocked_date"],
        );

        return $this->getAttendanceFootprints($attendanceId, $breaks);
    }

    /**
     * get employee shift for a specific date
     *
     * @return array
     */
    private function getAllowedBreaksForEmployee($employeeId, $date): array
    {
        // get the employee shifts
        $shift = $this->db
            ->select("
                breaks_json
            ")
            ->where([
                "employee_sid" => $employeeId,
                "shift_date" => $date,
            ])
            ->get("cl_shifts")
            ->row_array();
        //
        if (!$shift) {
            return [];
        }
        //
        if (!$shift["breaks_json"]) {
            return [];
        }
        //
        $breaks = json_decode($shift["breaks_json"], true);
        //
        foreach ($breaks as $index => $v0) {
            //
            if ($v0["start_time"]) {
                $breaks[$index]["start_time"] = formatDateToDB(
                    $v0["start_time"],
                    "H:i",
                    "h:i a"
                );
                $breaks[$index]["end_time"] = formatDateToDB(
                    $v0["end_time"],
                    "H:i",
                    "h:i a"
                );
            }
        }
        //
        return $breaks;
    }

    /**
     * get the attendance logs
     *
     * @param int $attendanceId
     * @param array $breaks
     * @return array
     */
    public function getAttendanceFootprints(int $attendanceId, array $breaks): array
    {
        // set return array
        $returnArray = [
            "logs" => [],
            "locations" => [],
            "pair_locations" => []
        ];
        //
        $logs = $this->db
            ->select("
                sid,
                job_site_sid,
                clocked_in_utc as clocked_in,
                clocked_out_utc as clocked_out,
                break_id,
                break_start_utc as break_start,
                break_end_utc as break_end,
                duration,
                lat,
                lng,
                lat_2,
                lng_2
            ")
            ->where("cl_attendance_sid", $attendanceId)
            ->order_by("sid", "ASC")
            ->get("cl_attendance_log")
            ->result_array();

        if (!$logs) {
            return $returnArray;
        }

        foreach ($logs as $v0) {
            //
            $log = [
                "sid" => $v0["sid"],
                "text" => "Clock in/out",
                "startTime" => "",
                "endTime" => "",
                "lat" => $v0["lat"],
                "lng" => $v0["lng"],
                "lat_2" => $v0["lat_2"],
                "lng_2" => $v0["lng_2"],
                "durationText" => convertSecondsToTime($v0["duration"]),
                "duration" => $v0["duration"],
                "jobSite" => [],
                "break" => [],
                "location" => []
            ];
            // set locations
            $locationArray = [];
            $pairLocationArray = [];
            // set the text
            if ($v0["clocked_in"]) {
                //
                $pairLocationArray = [
                    "id" => $v0["sid"],
                    "lat" => $v0["lat"],
                    "lng" => $v0["lng"],
                    "lat_2" => $v0["lat_2"],
                    "lng_2" => $v0["lng_2"],
                    "event" => "clock"
                ];
                //
                $log["text"] = "Clocked in/out";
                $log["startTime"] =
                    $v0["clocked_in"];
                $log["is_ended"] = $v0["clocked_in"] ? true : false;
                $log["endTime"] = $v0["clocked_out"] ?? "";
                //
                $locationArray["clocked_in"] = [
                    "lat" => $v0["lat"],
                    "lng" => $v0["lng"],
                    "address" => getLocationAddress($v0["lat"], $v0["lng"]),
                    "time" => reset_datetime([
                        "datetime" => $log["startTime"],
                        "from_format" => DB_DATE_WITH_TIME,
                        "format" => DB_DATE_WITH_TIME,
                        "_this" => $this,
                        "from_timezone" => DB_TIMEZONE
                    ]),
                    "title" => "Clocked in"
                ];
                //
                if ($v0["clocked_out"]) {
                    $locationArray["clocked_out"] = [
                        "lat" => $v0["lat_2"],
                        "lng" => $v0["lng_2"],
                        "address" => getLocationAddress($v0["lat_2"], $v0["lng_2"]),
                        "time" => reset_datetime([
                            "datetime" => $log["endTime"],
                            "from_format" => DB_DATE_WITH_TIME,
                            "format" => DB_DATE_WITH_TIME,
                            "_this" => $this,
                            "from_timezone" => DB_TIMEZONE
                        ]),
                        "title" => "Clocked out"
                    ];
                }
            } else {
                //
                $pairLocationArray = [
                    "id" => $v0["sid"],
                    "lat" => $v0["lat"],
                    "lng" => $v0["lng"],
                    "lat_2" => $v0["lat_2"],
                    "lng_2" => $v0["lng_2"],
                    "event" => "break"
                ];
                $log["text"] = "Break start/end";
                $log["startTime"] = $v0["break_start"];
                $log["is_ended"] = $v0["break_end"] ? true : false;
                $log["endTime"] = $v0["break_end"] ?? "";
                //
                $locationArray["break_start"] = [
                    "lat" => $v0["lat"],
                    "lng" => $v0["lng"],
                    "address" => getLocationAddress($v0["lat"], $v0["lng"]),
                    "time" => reset_datetime([
                        "datetime" => $log["startTime"],
                        "from_format" => DB_DATE_WITH_TIME,
                        "format" => DB_DATE_WITH_TIME,
                        "_this" => $this,
                        "from_timezone" => DB_TIMEZONE
                    ]),
                    "title" => "Break started"
                ];
                //
                if ($v0["break_end"]) {
                    $locationArray["break_end"] = [
                        "lat" => $v0["lat_2"],
                        "lng" => $v0["lng_2"],
                        "address" => getLocationAddress($v0["lat_2"], $v0["lng_2"]),
                        "time" => reset_datetime([
                            "datetime" => $log["endTime"],
                            "from_format" => DB_DATE_WITH_TIME,
                            "format" => DB_DATE_WITH_TIME,
                            "_this" => $this,
                            "from_timezone" => DB_TIMEZONE
                        ]),
                        "title" => "Break end"
                    ];
                }
            }
            //
            if ($v0["job_site_sid"] && $v0["job_site_sid"] != 0) {
                $log["jobSite"] = $this->getJobSiteDetails($v0["job_site_sid"]);
            }
            //
            if ($v0["break_id"] && $v0["break_id"] != 0) {
                foreach ($breaks as $break) {
                    if ($break["id"] == $v0["break_id"]) {
                        $log["break"] = $break;
                    }
                }
            }

            //
            if ($log["jobSite"]) {
                $locationArray["clocked_in"]["title"] .= " at " . $log["jobSite"]["site_name"];
                $locationArray["clocked_in"]["constraint"] = [
                    "lat" => $log["jobSite"]["lat"],
                    "lng" => $log["jobSite"]["lng"],
                    "allowed" => $log["jobSite"]["site_radius"],
                    "title" => $log["jobSite"]["site_name"]
                ];
                $pairLocationArray["constraint"] =
                    [
                        "lat" => $log["jobSite"]["lat"],
                        "lng" => $log["jobSite"]["lng"],
                        "allowed" => $log["jobSite"]["site_radius"],
                        "title" => $log["jobSite"]["site_name"]
                    ];
            }
            //
            if ($log["break"]) {
                $locationArray["break_start"]["title"] .= " for " . $log["break"]["break"];
            }
            //
            $pairLocationArray["onSiteFlag"] = true;
            $pairLocationArray["text"] = "On site";

            // when job site exists
            if ($log["jobSite"]) {
                $text1  = "Clocked in";
                $text2  = "Clocked out";
                // check for clock in
                if (!$v0["clocked_in"]) {
                    $text1  = "Break started";
                    $text2  = "Break ended";
                }

                if ($pairLocationArray["lat"] && $pairLocationArray["lng"]) {
                    $dd = is_within_radius([
                        $pairLocationArray["lat"], $pairLocationArray["lng"],
                    ], [
                        $pairLocationArray["constraint"]["lat"], $pairLocationArray["constraint"]["lng"],
                    ], $pairLocationArray["constraint"]["lng"]);


                    if (!$dd["within_range"]) {
                        $pairLocationArray["onSiteFlag"] = false;
                        $pairLocationArray["text"] = $text1 . " " . ($dd["distance"] * 3.28084) . " feet away from the job site - " . $log["jobSite"]["site_name"] . ".";
                    }
                } elseif ($pairLocationArray["lat_2"] && $pairLocationArray["lng_2"]) {
                    $dd = is_within_radius([
                        $pairLocationArray["lat_2"], $pairLocationArray["lng_2"],
                    ], [
                        $pairLocationArray["constraint"]["lat"], $pairLocationArray["constraint"]["lng"],
                    ], $pairLocationArray["constraint"]["lng"]);


                    if (!$dd["within_range"]) {
                        $pairLocationArray["onSiteFlag"] = false;
                        $pairLocationArray["text"] = $text2 . " " . ($dd["distance"] * 3.28084) . " feet away from the job site - " . $log["jobSite"]["site_name"] . ".";
                    }
                }
            }
            //
            $log["location"] = $pairLocationArray;
            $returnArray["logs"][] = $log;
            $returnArray["locations"] = array_merge(
                $returnArray["locations"],
                array_values($locationArray)
            );
            $returnArray["pair_locations"][] = $pairLocationArray;
            //
        }

        return $returnArray;
    }

    /**
     * get a single job site
     */
    private function getJobSiteDetails(int $jobSiteId): array
    {
        return $this->db
            ->select("
            site_name,
            site_radius,
            lat,
            lng
        ")
            ->where("sid", $jobSiteId)
            ->get("company_job_sites")
            ->row_array();
    }

}
