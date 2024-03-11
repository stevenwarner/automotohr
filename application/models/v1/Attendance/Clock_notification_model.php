<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Cron CRON model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Attendance
 */
class Clock_notification_model extends CI_Model
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
    public function checkAndSendDailyLimitBreachEmailToEmployers(
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
            return _e("No attendance Records found");
        }
        //
        $this->clockDate = $date;
        // set email info array
        $employeesArray = [];
        //
        foreach ($attendanceRecords as $v0) {
            // get the employee worked time
            $duration = $this->getAttendanceLoggedInDuration(
                $v0["sid"]
            );
            // get the employee shift
            $shiftInSeconds = $this->getEmployeeShift(
                $v0["employee_sid"],
                $date
            );
            // over the time
            $overTheTime = $duration - $shiftInSeconds;
            // write the email logic
            if ($overTheTime >= $seconds) {
                $employeesArray[] = [
                    "scheduled" => $shiftInSeconds,
                    "worked" => $duration,
                    "difference" => $overTheTime,
                    "employee_sid" => $v0["employee_sid"]
                ];
            }
        }

        //
        if (!$employeesArray) {
            return _e("No employees qualified");
        }
        //
        $this->sendDailyLimitBeachEmailToEmployers(
            $companyId,
            $date,
            $employeesArray
        );
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
                "clocked_out IS NOT NULL" => null
            ])
            ->get("cl_attendance")
            ->result_array();
    }

    /**
     * get the attendance log
     */
    private function getEmployeeShift(
        int $employeeId,
        string $date
    ) {
        $record = $this->db
            ->select("start_time, end_time")
            ->where([
                "employee_sid" => $employeeId,
                "shift_date" => $date
            ])
            ->get("cl_shifts")
            ->result_array();
        //
        if (!$record) {
            return 8 * 60 * 60;
        }
        //
        return $this->attendance_lib
            ->getDurationInMinutes(
                $date . " " . $record["start_time"],
                $date . " " . $record["end_time"]
            );
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
     * Send daily limit breach email to employers
     *
     * @param int $companyId
     * @param string $date
     * @param array $employeeArray
     */
    private function sendDailyLimitBeachEmailToEmployers(
        int $companyId,
        string $date,
        array $employeesArray
    ) {
        // get the company name
        $companyDetails = getCompanyInfo($companyId);
        // get the email header and footer
        $emailHeaderAndFooter = message_header_footer_domain(
            $companyId,
            $companyDetails["company_name"]
        );
        // check for module enable
        if (!get_notifications_status($companyId)["attendance"]) {
            return _e("Notification module not enabled.");
        }
        // get notification recipients
        $recipients = get_notification_email_contacts(
            $companyId,
            "attendance"
        );
        // generate employee rows
        $employeeRows = $this->generateEmployeeRows($employeesArray, $date);
        //
        foreach ($recipients as $v0) {
            // set replacement array
            $replacementArray = [];
            $replacementArray["contact_name"] = $v0["contact_name"];
            $replacementArray["contact_number"] = $v0["contact_no"];
            $replacementArray = array_merge(
                $replacementArray,
                $companyDetails
            );
            $replacementArray["employees_list"] = $employeeRows;
            // get the email template
            log_and_send_templated_notification_email(
                DAILY_LIMIT_BREACH_EMAIL_TO_EMPLOYERS,
                $v0["email"],
                $replacementArray,
                $emailHeaderAndFooter,
                $companyId,
                0,
                'attendance'
            );
        }
    }

    /**
     * generates the HTML of employee array
     *
     * @param array $employeesArray
     * @return string
     */
    private function generateEmployeeRows(array $employeesArray): string
    {
        $html = '<table style="border: 0;" cellspacing="0">';
        $html .= '  <thead>';
        $html .= '      <tr>';
        $html .= '          <th scope="col" style="background: #222; color: #fff;">Employee</th>';
        $html .= '          <th scope="col" style="background: #222; color: #fff;">Scheduled<br/>Time</th>';
        $html .= '          <th scope="col" style="background: #222; color: #fff;">Worked<br/>Time</th>';
        $html .= '          <th scope="col" style="background: #222; color: #fff;">Difference</th>';
        $html .= '      </tr>';
        $html .= '  </thead>';
        $html .= '<tbody>';
        //
        foreach ($employeesArray as $v0) {
            $html .= "<tr>";
            $html .= "  <td>" . (remakeEmployeeName(getUserColumnsById($v0["employee_sid"], getUserFields()))) . "</td>";
            $html .= "  <td>" . ($this->attendance_lib->convertSecondsToHours($v0["scheduled"], true)) . "</td>";
            $html .= "  <td>" . ($this->attendance_lib->convertSecondsToHours($v0["worked"], true)) . "</td>";
            $html .= "  <td>" . ($this->attendance_lib->convertSecondsToHours($v0["difference"], true)) . "</td>";
            $html .= "</tr>";
        }

        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }
}
