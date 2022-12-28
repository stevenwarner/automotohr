<?php defined('BASEPATH') || exit('No direct script access allowed');

class Attendance_ajax extends Public_Controller
{
    /**
     * Holds the current use session
     */
    private $ses;

    /**
     * Holds the company id
     */
    private $companyId;

    /**
     * Holds the employee id
     */
    private $employeeId;

    /**
     * Holds the current datetime
     */
    private $datetime;

    /**
     * Holds the current date
     */
    private $date;

    /**
     * Holds the current day
     */
    private $day;

    /**
     * Holds the current month
     */
    private $month;

    /**
     * Holds the current year
     */
    private $year;

    /**
     * Holds the response array
     */
    private $resp;

    /**
     * Calls when the object is created
     */
    public function __construct()
    {
        //
        parent::__construct();
        //
        $this->ses = $this->session->userdata('logged_in');
        //
        if (!$this->ses || ($this->input->method() === 'post' && empty($this->input->post(NULL, TRUE)))) {
            return SendResponse(401);
        }
        //
        $this->load->model('attendance_model', 'atm');
        //
        $this->resp = ['errors' => ['Invalid call.']];
        //
        $this->companyId = $this->ses['company_detail']['sid'];
        //
        $this->employeeId = $this->ses['employer_detail']['sid'];
        //
        $this->employerId = $this->ses['employer_detail']['sid'];
        //
        $this->datetime = date('Y-m-d H:i:s', strtotime('now'));
        //
        $this->date = date('Y-m-d', strtotime('now'));
        //
        $this->day = date('d', strtotime('now'));
        //
        $this->month = date('m', strtotime('now'));
        //
        $this->year = date('Y', strtotime('now'));
    }

    /**
     * Handles logged in user clock
     */
    public function LoadClockOld()
    {
        //
        $ra = [
            'last_status' => '',
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 0,
        ];
        // Let's check if the employee is
        // clocked in or not
        $attendanceList = $this->atm->GetAttendanceList(
            $this->companyId,
            $this->employeeId,
            $this->date
        );


        // 
        if (empty($attendanceList)) {
            //
            unset($this->resp['errors']);
            //
            $this->resp['success'] = $ra;
            //
            return SendResponse(200, $this->resp);
        }
        //
        $ct = CalculateTime($attendanceList, $this->employeeId);
        $ra['last_status'] = $attendanceList[0]['action'];


        //
        $ra = array_merge(
            $ra,
            GetHMSFromSeconds($ct['total_minutes'])
        );
        //
        unset($this->resp['errors']);
        $this->resp['success'] = $ra;
        //
        return SendResponse(200, $this->resp);
    }





    /**
     * Handles logged in user clock
     */
    public function LoadClock()
    {
        //
        $ra = [
            'last_status' => '',
            'action_date_time' => '',
            'break_record' => ''
        ];
        // Let's check if the employee is
        // clocked in or not


        $attendanceListLastRecord = $this->atm->GetAttendanceList(
            $this->companyId,
            $this->employeeId,
            $this->date
        );

        if ($attendanceListLastRecord[0]['action'] == 'clock_in' || $attendanceListLastRecord[0]['action'] == 'break_out') {

            $attendanceList = $this->atm->GetAttendanceListNew(
                $this->companyId,
                $this->employeeId,
                $this->date
            );

            $ra['last_status'] = $attendanceList[0]['action'];
            $ra['action_date_time'] = $attendanceList[0]['action_date_time'];
        } else if ($attendanceListLastRecord[0]['action'] == 'break_in') {

            // Get all breaks for current day
            $attendanceListBrak = $this->atm->GetAttendanceListBreak(
                $this->companyId,
                $this->employeeId,
                $this->date
            );

            $ra['last_status'] = $attendanceListLastRecord[0]['action'];
            $ra['action_date_time'] = $attendanceListLastRecord[0]['action_date_time'];
            $ra['break_record'] = $attendanceListBrak;
        } else {

            $ra['last_status'] = $attendanceListLastRecord[0]['action'];
            $ra['action_date_time'] = $attendanceListLastRecord[0]['action_date_time'];
        }

        // 
        if (empty($attendanceList)) {
            //
            unset($this->resp['errors']);
            //
            $this->resp['success'] = $ra;
            //
            return SendResponse(200, $this->resp);
        }
        //

        unset($this->resp['errors']);
        $this->resp['success'] = $ra;

        //
        return SendResponse(200, $this->resp);
    }



    /**
     * Marks attendance
     */
    public function MarkAttendance()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $post['lat'] = isset($post['lat']) ? $post['lat'] : NULL;
        $post['lon'] = isset($post['lon']) ? $post['lon'] : NULL;
        //
        $date = $this->date;
        $datetime = $this->datetime;
        $employeeId = $this->employeeId;
        $day = $this->day;
        $month = $this->month;
        $year = $this->year;
        $added_by = "";
        //
        if (isset($post["id"])) {
            $attendanceInfo = $this->atm->GetAttendanceDateAndStatusById($post["id"]);
            $date = $attendanceInfo["action_date"];
            $employee_date = ConvertDateTime($attendanceInfo["company_sid"], $attendanceInfo["employee_sid"], $attendanceInfo["action_date"])["modified"];
            //
            $day = explode("-", $employee_date)[2];
            $month = explode("-", $employee_date)[1];
            $year = explode("-", $employee_date)[0];
            //
            $employee_date = $employee_date . " " . $post["time"] . ":00";
            $datetime = ConvertDateTime($attendanceInfo["company_sid"], $attendanceInfo["employee_sid"], $employee_date, DB_DATE_WITH_TIME, true)["modified"];
            $employeeId = $attendanceInfo["employee_sid"];
            $added_by = $this->employerId;
            //Todo Need to check converted date and actual date

        }
        //
        $attendanceList = $this->atm->GetAttendanceList(
            $this->companyId,
            $employeeId,
            $date
        );
        // 
        if (!empty($attendanceList)) {
            //
            $ct = CalculateTime($attendanceList, $employeeId);
            //
            $this->db->update(
                'portal_attendance',
                [
                    'total_minutes' => $ct['total_minutes'],
                    'total_worked_minutes' => $ct['total_worked_minutes'],
                    'total_break_minutes' => $ct['total_break_minutes'],
                    'total_overtime_minutes' => $ct['total_overtime_minutes']
                ],
                [
                    'sid' => $attendanceList[0]['portal_attendance_sid']
                ]
            );
            //
            $this->HandleConditions($attendanceList[0]['action'], $post['action']);
            // Check if clock out was triggered while on break
            if ($attendanceList[0]['action'] === 'break_in' && $post['action'] === 'clock_out') {
                // Now we have to end the break first
                $this->LogAttendance(
                    'break_out',
                    $post['lat'],
                    $post['lon'],
                    $employeeId,
                    $date,
                    $datetime,
                    $day,
                    $month,
                    $year,
                    $added_by,
                    false
                );
            }
        }
        // Mark the attendance
        return $this->LogAttendance(
            $post['action'],
            $post['lat'],
            $post['lon'],
            $employeeId,
            $date,
            $datetime,
            $day,
            $month,
            $year,
            $added_by
        );
    }

    /**
     * Updates date time
     */
    public function ManageTimeSheet()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $sid = $this->atm->GetAttendanceIDByListId($post["Id"]);
        $attendanceInfo = $this->atm->GetAttendanceDateAndStatusById($sid);
        $date = $attendanceInfo["action_date"];
        $employee_date = ConvertDateTime($attendanceInfo["company_sid"], $attendanceInfo["employee_sid"], $attendanceInfo["action_date"])["modified"];
        //
        $employee_date = $employee_date . " " . $post["time"] . ":00";
        $datetime = ConvertDateTime($attendanceInfo["company_sid"], $attendanceInfo["employee_sid"], $employee_date, DB_DATE_WITH_TIME, true)["modified"];
        $added_by = $this->employerId;
        //
        $this->atm->UpdateEmployeeTimeSlot($post["Id"], $datetime, $added_by);
        //
        $attendanceList = $this->atm->GetAttendanceListByID($sid);
        // 
        if (!empty($attendanceList)) {
            //
            $ct = CalculateTime($attendanceList, $attendanceInfo["employee_sid"]);
            //
            $this->db->update(
                'portal_attendance',
                [
                    'total_minutes' => $ct['total_minutes'],
                    'total_worked_minutes' => $ct['total_worked_minutes'],
                    'total_break_minutes' => $ct['total_break_minutes'],
                    'total_overtime_minutes' => $ct['total_overtime_minutes']
                ],
                [
                    'sid' => $sid
                ]
            );
            //
        }
        //
        return SendResponse(200, $this->resp);
    }



    /**
     * Updates date time
     */
    public function GetAddSlot($id)
    {
        //
        $get = $this->input->get(NULL, TRUE);
        //
        $attendanceInfo = $this->atm->GetAttendanceDateAndStatusById($id);
        //
        $last_action = $attendanceInfo["last_action"];
        //
        $option = "";
        //
        if ($last_action == "clock_in") {
            $option .= '<option value="break_in">Break Start</option>';
            $option .= '<option value="clock_out">Clock Out</option>';
        } else if ($last_action == "break_in") {
            $option .= '<option value="break_out">Break End</option>';
            $option .= '<option value="clock_out">Clock Out</option>';
        } else if ($last_action == "break_out") {
            $option .= '<option value="clock_out">Clock Out</option>';
        } else if ($last_action == "clock_out") {
            $option .= '<option value="clock_in">Clock In</option>';
        }

        $timezone = getTimeZone($attendanceInfo["company_sid"], $attendanceInfo["employee_sid"]);
        $action_date = reset_datetime([
            'datetime' => $attendanceInfo["action_date"],
            'format' => DATE,
            'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
            'new_zone' => $timezone,
            '_this' => $this
        ]);
        //
        $ra = [
            'option' => $option,
            'date' => $action_date
        ];
        //
        unset($this->resp['errors']);
        $this->resp['success'] = $ra;
        //
        return SendResponse(200, $this->resp);
    }

    /**
     * Update settings
     */
    public function UpdateSettings()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->atm->UpdateSettings([
            'employer_sid' => $this->employeeId,
            'updated_at' => $this->datetime,
            'is_visible_to_payroll' => isset($post['payroll']) ? $post['payroll'] : 0,
            'allowed_roles' => isset($post['roles']) ? implode(',', $post['roles']) : null,
            'allowed_departments' => isset($post['departments']) ? implode(',', $post['departments']) : null,
            'allowed_teams' => isset($post['teams']) ? implode(',', $post['teams']) : null,
            'allowed_employees' => isset($post['employees']) ? implode(',', $post['employees']) : null
        ], [
            'company_sid' => $this->companyId
        ]);
        //
        unset($this->resp['errors']);
        $this->resp['success'] = 'success';
        //
        return SendResponse(200, $this->resp);
    }

    /**
     * Get action for front end
     * 
     * @param string $action
     * @return string
     */
    private function GetCleanedAction($action)
    {
        return ucwords(str_replace('_', ' ', $action));
    }

    /**
     * Log attendance
     * 
     * @param string  $action
     * @param number  $lat
     * @param number  $lon
     * @param boolean $return
     * @return response
     */
    private function LogAttendance(
        $action,
        $lat,
        $lon,
        $employeeId,
        $date,
        $datetime,
        $day,
        $month,
        $year,
        $added_by,
        $return = true
    ) {
        // Mark the attendance

        $Id = $this->atm->MarkAttendance(
            $this->companyId,
            $employeeId,
            $this->employerId,
            $date,
            $datetime,
            $day,
            $month,
            $year,
            $action,
            $lat,
            $lon,
            $added_by
        );
        //
        if (!$Id) {
            //
            $this->resp['errors'] = ['Something went wrong while marking attendance.'];
        } else {
            //
            unset($this->resp['errors']);
            //
            $this->resp['success'] = 'Hurray! you are successfully "' . (GetAttendanceActionText($action)) . '".';
        }
        //
        if ($return) {
            return SendResponse(200, $this->resp);
        }
    }

    /**
     * Handle attendance conditions
     * 
     * @param string  $lastAction
     * @param string  $action
     * @return response
     */
    private function HandleConditions($lastAction, $action)
    {
        // Check for clock out status
        if ($lastAction === 'clock_out' && $action != 'clock_in') {
            //
            $this->resp['errors'] = ['You haven\'t clock in.'];
            //
            return SendResponse(200, $this->resp);
        }
        // Compare the last and current status
        if ($lastAction === $action) {
            //
            $this->resp['errors'] = ['You are already "' . ($this->GetCleanedAction($action)) . '".'];
            //
            return SendResponse(200, $this->resp);
        }
        // Check if break end was triggered
        if ($lastAction != 'break_in' && $action === 'break_out') {
            //
            $this->resp['errors'] = ['You haven\'t started the break.'];
            //
            return SendResponse(200, $this->resp);
        }
    }
}
