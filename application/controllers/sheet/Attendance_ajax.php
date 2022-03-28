<?php defined('BASEPATH') || exit('No direct script access allowed');

class Attendance_ajax extends Public_Controller {
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
    public function __construct() {
        //
        parent::__construct();
        //
        $this->ses = $this->session->userdata('logged_in');
        //
        if(!$this->ses || ($this->input->method() === 'post' && empty($this->input->post(NULL, TRUE)))){
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
    public function LoadClock(){
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
        if(empty($attendanceList)){
            //
            unset($this->resp['errors']);
            //
            $this->resp['success'] =$ra;
            //
            return SendResponse(200, $this->resp);
        }
        //
        $ct = CalculateTime($attendanceList);
        $ra['last_status'] = $attendanceList[0]['action'];
        //
        $ra = array_merge(
            $ra,
            GetHMSFromMinutes($ct['total_worked_minutes'])
        );
        //
        unset($this->resp['errors']);
        $this->resp['success'] =$ra;
        //
        return SendResponse(200, $this->resp);
    }
    
    /**
     * Marks attendance
     */
    public function MarkAttendance(){
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $attendanceList = $this->atm->GetAttendanceList(
            $this->companyId,
            $this->employeeId,
            $this->date
        );
        // 
        if(!empty($attendanceList)){
            //
            $ct = CalculateTime($attendanceList);
            //
            $this->db->update(
                'portal_attendance', [
                    'total_minutes' => $ct['total_minutes'],
                    'total_worked_minutes' => $ct['total_worked_minutes'],
                    'total_break_minutes' => $ct['total_break_minutes']
                ], [
                    'sid' => $attendanceList[0]['portal_attendance_sid']
                ]
            );
            //
            $this->HandleConditions($attendanceList[0]['action'], $post['action']);
            // Check if clock out was triggered while on break
            if($attendanceList[0]['action'] === 'break_in' && $post['action'] === 'clock_out'){
                // Now we have to end the break first
                $this->LogAttendance(
                    'break_out',
                    $post['lat'],
                    $post['lon'],
                    false
                );
            }
        }
        // Mark the attendance
        return $this->LogAttendance(
            $post['action'],
            $post['lat'],
            $post['lon']
        );
        
    }
    
    /**
     * Updates date time
     */
    public function ManageTimeSheet(){
        //
        $post = $this->input->post(NULL, TRUE);

        _e($post, true, true);
        //
        $attendanceList = $this->atm->GetAttendanceList(
            $this->companyId,
            $this->employeeId,
            $this->date
        );
    }

    /**
     * Get action for front end
     * 
     * @param string $action
     * @return string
     */
    private function GetCleanedAction($action){
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
    private function LogAttendance($action, $lat, $lon, $return = true){
        // Mark the attendance
        $Id = $this->atm->MarkAttendance(
            $this->companyId,
            $this->employeeId,
            $this->employerId,
            $this->date,
            $this->datetime,
            $this->day,
            $this->month,
            $this->year,
            $action,
            $lat,
            $lon
        );
        //
        if(!$Id){
            //
            $this->resp['errors'] = ['Something went wrong while marking attendance.'];
        } else{
            //
            unset($this->resp['errors']);
            //
            $this->resp['success'] = 'Hurray! you are successfully "'.($this->GetCleanedAction($action)).'".';
        }
        //
        if($return){
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
    private function HandleConditions($lastAction, $action){
        // Check for clock out status
        if($lastAction === 'clock_out' && $action != 'clock_in'){
            //
            $this->resp['errors'] = ['You haven\'t clock in.'];
            //
            return SendResponse(200, $this->resp);
        }
        // Compare the last and current status
        if($lastAction === $action){
            //
            $this->resp['errors'] = ['You are already "'.($this->GetCleanedAction($action)).'".'];
            //
            return SendResponse(200, $this->resp);
        }
        // Check if break end was triggered
        if($lastAction != 'break_in' && $action === 'break_out'){
            //
            $this->resp['errors'] = ['You haven\'t started the break.'];
            //
            return SendResponse(200, $this->resp);
        }
    }
}