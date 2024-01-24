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

    /**
     * get the clock with state and job sites
     *
     * @param int $companyId
     * @param int $employeeId
     * @param string $date
     * @param bool $doReturn
     * @return array
     */
    public function getClockWithState(
        int $companyId,
        int $employeeId,
        string $date = "",
        bool $doReturn = false
    ) {
        // set companyId
        $this->companyId = $companyId;
        // get company permissions
        $companyPermissions = unserialize(getUserColumnById($companyId, "extra_info"));
        // set employeeId
        $this->employeeId = $employeeId;
        $this->date = $date;
        if (!$this->date) {
            $this->date = $this->loggedInPersonDateTime;
        }
        // get todays date in UTC
        $this->dateInUTC =
            formatDateToDB(
                convertTimeZone(
                    $this->date,
                    DB_DATE_WITH_TIME,
                    getLoggedInPersonTimeZone(),
                    DB_TIMEZONE
                ),
                DB_DATE_WITH_TIME,
                DB_DATE
            );
        // set return array
        $clockArray = [
            "clock_time" => "",
            "state" => "",
            "breaks" => [],
            "job_sites" => [],
            "allowed_breaks" => [],
            "current_job_id" => 0,
        ];
        // set the time as well
        $clockArray["timerDateTime"] =
            getSystemDateInLoggedInPersonTZ("Y-m-d\TH:i:sO");
        // set the time as well
        $clockArray["timezone"] = getTimeZoneFromAbbr(getLoggedInPersonTimeZone());
        // set job sites
        $clockArray["job_sites"] = $this->getEmployeeJobSites();
        // set breaks
        $clockArray["allowed_breaks"] = $this->getAllowedBreaks();
        // get the attendance state and time
        $record = $this->getAttendanceStateAndTime();
        //
        if (!$companyPermissions['clock_enable_for_attendance']) {
            $clockArray["blocked"] = true;
        }
        // when no entry is found
        if (!$record) {
            //
            return $doReturn
                ? $clockArray
                : SendResponse(
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
                // set current job id
                $clockArray["current_job_id"] = $v["job_site_sid"];
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
                        "id" => $v["break_id"],
                        "start" => $v["break_start"],
                        "end" => $v["break_end"],
                        "duration" => $v["duration"],
                        "text" => $this->attendance_lib
                            ->convertSecondsToHours($v["duration"], true)
                    ];
                }
            }
        }
        //
        if ($record["last_event"] === "break_started") {
            // get the last active break
            $lastBreak = $clockArray["breaks"][0];
            //
            $clockArray["clock_time"] = $lastBreak["start"];
        } else {
            //
            $clockArray["clock_time"] = $lastClockInTime;
        }
        $clockArray["state"] = $record["last_event"];
        $clockArray["time"] = $found ? $timeInMinutes : 0;
        $clockArray["reference"] = $record["sid"];
        //
        if ($record["last_event"] === "clocked_out") {
            $clockArray["time"] = $timeInMinutes;
        }
        $clockArray["text"] = $this->attendance_lib
            ->convertSecondsToHours($clockArray["time"], true);
        //
        return $doReturn
            ? $clockArray
            : SendResponse(
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
        $this->dateInUTC =
            formatDateToDB(
                convertTimeZone(
                    $this->loggedInPersonDateTime,
                    DB_DATE_WITH_TIME,
                    getLoggedInPersonTimeZone(),
                    DB_TIMEZONE
                ),
                DB_DATE_WITH_TIME,
                DB_DATE
            );
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

        // get date array
        $datesPool = $this->attendance_lib
            ->getDatePoolInRange(
                $startDate,
                $endDate
            );
        // get the attendance logs within range
        $records = $this->getAttendanceInRange(
            formatDateToDB($startDate, SITE_DATE, DB_DATE),
            formatDateToDB($endDate, SITE_DATE, DB_DATE)
        );
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
                $locationArray["clocked_in"] = [
                    "lat" => $v0["lat"],
                    "lng" => $v0["lng"],
                    "title" => "Clocked in"
                ];
                $pairLocationArray = [
                    "id" => $v0["sid"],
                    "lat" => $v0["lat"],
                    "lng" => $v0["lng"],
                    "lat_2" => $v0["lat_2"],
                    "lng_2" => $v0["lng_2"],
                    "event" => "clock"
                ];
                //
                if ($v0["clocked_out"]) {
                    $locationArray["clocked_out"] = [
                        "lat" => $v0["lat_2"],
                        "lng" => $v0["lng_2"],
                        "title" => "Clocked out"
                    ];
                }
                $log["text"] = "Clocked in/out";
                $log["startTime"] =
                    $v0["clocked_in"];
                $log["is_ended"] = $v0["clocked_in"] ? true : false;
                $log["endTime"] = $v0["clocked_out"] ?? "";
            } else {
                $locationArray["break_start"] = [
                    "lat" => $v0["lat"],
                    "lng" => $v0["lng"],
                    "title" => "Break started"
                ];
                //
                if ($v0["break_end"]) {
                    $locationArray["break_end"] = [
                        "lat" => $v0["lat_2"],
                        "lng" => $v0["lng_2"],
                        "title" => "Break end"
                    ];
                }
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
            }

            $log["startTime"] = reset_datetime([
                "datetime" => $log["startTime"],
                "from_format" => DB_DATE_WITH_TIME,
                "format" => DB_DATE_WITH_TIME,
                "_this" => $this,
                "from_timezone" => DB_TIMEZONE
            ]);

            $log["endTime"] = reset_datetime([
                "datetime" => $log["endTime"],
                "from_format" => DB_DATE_WITH_TIME,
                "format" => DB_DATE_WITH_TIME,
                "_this" => $this,
                "from_timezone" => DB_TIMEZONE
            ]);
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


    public function getAttendanceWithinRange(
        int $companyId,
        int $employeeId,
        string $startDate,
        string $endDate
    ): array {
        // convert the incoming dates to utc
        $startDateUTC = convertTimeZone(
            $startDate,
            DB_DATE,
            getLoggedInPersonTimeZone(),
            DB_TIMEZONE
        );
        $endDateUTC = convertTimeZone(
            $endDate,
            DB_DATE,
            getLoggedInPersonTimeZone(),
            DB_TIMEZONE
        );
        //
        $records = $this->getAttendanceByDates(
            $companyId,
            $employeeId,
            $startDateUTC,
            $endDateUTC
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
            // convert the dates back to logged in person timezone
            $v0["clocked_date"] = convertTimeZone(
                $v0["clocked_date"],
                DB_DATE,
                DB_TIMEZONE,
                getLoggedInPersonTimeZone()
            );
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
                clocked_in,
                clocked_out,
                break_id,
                break_start,
                break_end,
                duration,
                job_site_sid,
            ")
            ->where("cl_attendance_sid", $attendanceId)
            ->order_by("sid", "ASC")
            ->get("cl_attendance_log")
            ->result_array();
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
            clocked_date,
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
        $ins["clocked_date"] = $this->loggedInPersonDate;
        $ins["clocked_in"] = $ins["clocked_date"] . " " . getSystemDateInUTC(TIME);
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
            $insLog["job_site_sid"] = $post["job_site"];
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
        $this->clockDate = $record["clocked_date"];
        // handles event states
        $response = $this->handleEventStates($record, $post);
        // update main table
        $upd = [];
        //
        if ($post["type"] === "clocked_out" || $post["type"] === "break_started") {
            $upd["clocked_out"] = $this->clockDate . " " . getSystemDateInUTC(TIME);
        } else {
            $upd["clocked_out"] = null;
        }
        $upd["last_event"] = $post["type"] === "break_ended" ? "clocked_in" : $post["type"];
        $upd["updated_at"] = getSystemDate();
        $upd["last_record_time"] = $this->clockDate . " " . getSystemDateInUTC(TIME);
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
        $insLog["break_start"] = $this->clockDate . " " . getSystemDateInUTC(TIME);
        $insLog["lat"] = $post["latitude"];
        $insLog["lng"] = $post["longitude"];
        $insLog["job_site_sid"] = $response["job_site_sid"];
        $insLog["break_id"] = $post["job_site"];
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
        //
        return [
            "success" => true,
            "duration" => $updLog["duration"],
            "job_site_sid" => $record["job_site_sid"]
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
                clocked_date,
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
            $tmp = $v0["clocked_date"];
            // get the attendance logs
            $records = $this->getAttendanceLogs($v0["sid"]);
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

            // add the worked time
            $datesPool[$tmp] = [
                "totalTime" => $this->attendance_lib
                    ->convertSecondsToHours($timeInMinutes + $timeInMinutesForBreaks),
                "workedTotalTime" => $this->attendance_lib
                    ->convertSecondsToHours($timeInMinutes),
                "breakTotalTime" => $this->attendance_lib
                    ->convertSecondsToHours($timeInMinutesForBreaks),
            ];
        }
        //
        return $datesPool;
    }

    /**
     * get employee shift for a specific date
     *
     * @return array
     */
    private function getEmployeeJobSites(): array
    {
        // get the employee shifts
        $shift = $this->db
            ->select("
                job_sites
            ")
            ->where([
                "company_sid" => $this->companyId,
                "employee_sid" => $this->employeeId,
                "shift_date" => $this->dateInUTC,
            ])
            ->get("cl_shifts")
            ->row_array();
        //
        if (!$shift) {
            return [];
        }
        //
        $jobSites = json_decode(
            $shift["job_sites"],
            true
        );
        //
        if (!$jobSites) {
            return [];
        }
        //
        return $this->getJobSitesById(
            $jobSites
        );
    }


    /**
     * get employee shift for a specific date
     *
     * @return array
     */
    private function getAllowedBreaks(): array
    {
        // get the employee shifts
        $shift = $this->db
            ->select("
                breaks_json
            ")
            ->where([
                "company_sid" => $this->companyId,
                "employee_sid" => $this->employeeId,
                "shift_date" => $this->dateInUTC,
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
     * get job shifts by ids
     *
     * @param array $jobSiteIds
     * @return array
     */
    private function getJobSitesById(array $jobSiteIds): array
    {
        // get the employee shifts
        return $this->db
            ->select("
                sid,
                site_name
            ")
            ->where_in("sid", $jobSiteIds)
            ->get("company_job_sites")
            ->result_array();
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
                clocked_in,
                clocked_out,
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

    public function deleteAttendanceLogById($logId)
    {
        $this->db
            ->where("sid", $logId)
            ->delete("cl_attendance_log");
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
                foreach ($post["logs"] as $v0) {
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
                    $insLog[$indexOne] = $v0["startTime"];
                    $insLog[$indexTwo] = $v0["endTime"];
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

                                "clocked_in" => $initialClockIn,
                                "last_record_time" => $initialClockIn
                            ]
                        );
                }
                if ($initialClockOut) {
                    // insert log
                    $this->db
                        ->where([
                            "sid" => $post["sid"]
                        ])->update(
                            "cl_attendance",
                            [
                                "clocked_out" => $initialClockOut
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
                    $insLog[$indexOne] = $v0["startTime"];
                    $insLog[$indexTwo] = $v0["endTime"];
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
                    $insLog[$indexOne] = $v0["startTime"];
                    $insLog[$indexTwo] = $v0["endTime"];

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
                $insLog["clocked_in"] = $breakStartTime;
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
                $upd["clocked_in"] = $initialClockIn;
            }
            if ($initialClockOut && $state !== "break_in_out") {
                $upd["clocked_out"] = $initialClockOut;
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


    public function getPeopleClocks(int $companyId, string $date)
    {
        // convert the date in UTC
        $date = convertTimeZone(
            $date,
            DB_DATE,
            STORE_DEFAULT_TIMEZONE_ABBR,
            DB_TIMEZONE
        );
        //
        $returnArray = [
            "breaks" => [],
            "clocked_in" => [],
            "absent" => []
        ];
        //
        $records = $this->db
            ->select(getUserFields())
            ->select("cl_attendance.last_event, cl_attendance.employee_sid")
            ->join(
                "users",
                "users.sid = cl_attendance.employee_sid",
                "inner"
            )
            ->where("cl_attendance.clocked_date", $date)
            ->where("cl_attendance.company_sid", $companyId)
            ->get("cl_attendance")
            ->result_array();
        //
        if ($records) {
            foreach ($records as $v0) {
                if ($v0["last_event"] == "clocked_in") {
                    $returnArray["clocked_in"][] = $v0;
                } elseif ($v0["last_event"] == "break_started") {
                    $returnArray["breaks"][] = $v0;
                }
            }
        }

        $ids = array_column($records, "employee_sid");

        //
        $this->db
            ->select(getUserFields())
            ->where("active", 1)
            ->where("is_executive_admin", 0)
            ->where("terminated_status", 0)
            ->where("parent_sid", $companyId);

        if ($ids) {
            $this->db->where_not_in("sid", $ids);
        }
        $returnArray["absent"] = $this->db->get("users")
            ->result_array();


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

    public function processTimeSheetStatus($post)
    {
        $this->db
            ->where_in("sid", $post["ids"])
            ->update("cl_attendance", [
                "is_approved" => $post["status"]
            ]);
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
            "worked_time" => 0,
            "breaks_time" => 0,
            "paid_break_time" => 0,
            "unpaid_break_time" => 0,
            "overtime" => 0,
            "double_overtime" => 0,
        ];
        //
        $records =
            $this->db
            ->select("
                sid,
                clocked_in,
                clocked_out,
                is_approved,
                clocked_date
            ")
            ->where([
                "employee_sid" => $employeeId,
                "is_approved" => 1,
                "clocked_date >= " => $periodStartDate,
                "clocked_date <= " => $periodEndDate,
            ])
            ->get("cl_attendance")
            ->result_array();
        // when no record are found
        if (!$records) {
            return $returnArray;
        }
        // get employee overtime rule
        $employeeOverTime = $this->getEmployeeOverTimeRule($employeeId);
        // get employee shifts within range
        $employeeShifts = $this->getEmployeeShiftsWithinRange($employeeId, $periodStartDate, $periodEndDate);
        //
        foreach ($records as $v0) {
            // set a tmp array
            $tmp = [
                "date" => $v0["clocked_date"],
                "worked_time" => 0,
                "breaks_time" => 0,
                "paid_break_time" => 0,
                "unpaid_break_time" => 0,
                "overtime" => 0,
                "double_overtime" => 0,
                "text" => [
                    "worked_time" => 0,
                    "breaks_time" => 0,
                    "paid_break_time" => 0,
                    "unpaid_break_time" => 0,
                    "overtime" => 0,
                    "double_overtime" => 0,
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
                // for clock in
                if ($v1["clocked_in"]) {
                    $tmp["worked_time"] += $v1["duration"];
                } else {
                    //
                    if ($v1["break_id"]) {
                        // check if the break is paid or not
                        $isPaid = $this->db
                            ->where("sid", $v1["break_id"])
                            ->where("break_type", "paid")
                            ->count_all_results("company_breaks");
                        //
                        if ($isPaid) {
                            $tmp["paid_break_time"] += $v1["duration"];
                        } else {
                            $tmp["unpaid_break_time"] += $v1["duration"];
                        }
                    }
                    $tmp["breaks_time"] += $v1["duration"];
                }
            }
            // for daily
            if ($employeeOverTime["type"] === "daily") {
                // get day
                $day = strtolower(formatDateToDB(
                    $v0["clocked_date"],
                    DB_DATE,
                    "l"
                ));
                // for overtime
                if ($employeeOverTime[$day] && $employeeOverTime[$day]["overtime"]) {
                    //
                    $overtime = ($employeeOverTime[$day]["overtime"] ?? 0) * 60 * 60;
                    $double_overtime = ($employeeOverTime[$day]["double_overtime"] ?? 0) * 60 * 60;
                    if ($tmp["worked_time"] > $overtime) {
                        $tmp["overtime"] = $tmp["worked_time"] - $overtime;
                    }
                    if ($double_overtime > 0 && (($tmp["worked_time"] + $tmp["overtime"]) > $double_overtime)) {
                        $tmp["double_overtime"] = ($tmp["worked_time"] + $overtime) - $double_overtime;
                    }
                }
            }
            // convert to text
            $tmp["text"] = [
                "worked_time" => convertSecondsToTime($tmp["worked_time"]),
                "breaks_time" => convertSecondsToTime($tmp["breaks_time"]),
                "paid_break_time" => convertSecondsToTime($tmp["paid_break_time"]),
                "unpaid_break_time" => convertSecondsToTime($tmp["unpaid_break_time"]),
                "overtime" => convertSecondsToTime($tmp["overtime"]),
                "double_overtime" => convertSecondsToTime($tmp["double_overtime"]),
            ];
            // set to main array
            $returnArray["periods"][] = $tmp;
            $returnArray["worked_time"] += $tmp["worked_time"];
            $returnArray["breaks_time"] += $tmp["breaks_time"];
            $returnArray["paid_break_time"] += $tmp["paid_break_time"];
            $returnArray["unpaid_break_time"] += $tmp["unpaid_break_time"];
            $returnArray["overtime"] += $tmp["overtime"];
            $returnArray["double_overtime"] += $tmp["double_overtime"];
        }
        // TODO
        // apply overtime rule for weekly, holiday, and seven
        // convert to text
        $returnArray["text"] = [
            "worked_time" => convertSecondsToTime($returnArray["worked_time"]),
            "breaks_time" => convertSecondsToTime($returnArray["breaks_time"]),
            "paid_break_time" => convertSecondsToTime($returnArray["paid_break_time"]),
            "unpaid_break_time" => convertSecondsToTime($returnArray["unpaid_break_time"]),
            "overtime" => convertSecondsToTime($returnArray["overtime"]),
            "double_overtime" => convertSecondsToTime($returnArray["double_overtime"]),
        ];

        return $returnArray;
    }


    public function getEmployeeOverTimeRule(int $employeeId)
    {
        //
        $returnArray["type"] = "weekly";
        $returnArray["overtime_multiplier"] = 1.5;
        $returnArray["double_overtime_multiplier"] = 2.0;
        $returnArray["overtime"] = 0;
        $returnArray["double_overtime"] = 0;
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
        //
        if ($record["holiday_json"]["overtime"]["status"]) {
            $returnArray["type"] = "holiday";
            $returnArray["overtime"] = $record["holiday_json"]["overtime"]["hours"];
            $returnArray["double_overtime"] = $record["holiday_json"]["double"]["hours"];
        } elseif ($record["seven_consecutive_days_json"]["overtime"]["status"]) {
            $returnArray["type"] = "seven";
            $returnArray["overtime"] = $record["seven_consecutive_days_json"]["overtime"]["hours"];
            $returnArray["double_overtime"] = $record["seven_consecutive_days_json"]["double"]["hours"];
        } elseif ($record["weekly_json"]["overtime"]["status"]) {
            $returnArray["type"] = "weekly";
            $returnArray["overtime"] = $record["weekly_json"]["overtime"]["hours"];
            $returnArray["double_overtime"] = $record["weekly_json"]["double"]["hours"];
        } else {
            $returnArray["type"] = "daily";
            foreach ($record["daily_json"] as $k0 => $v0) {
                $returnArray[$k0]["overtime"] = $v0["overtime"]["hours"];
                $returnArray[$k0]["double_overtime"] = $v0["double"]["hours"];
            }
        }

        return $returnArray;
    }
}
