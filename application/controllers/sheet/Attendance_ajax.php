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
        $this->resp = ['status' => false, 'message' => 'Invalid call.'];
        //
        $this->companyId = $this->ses['company_detail']['sid'];
        //
        $this->employeeId = $this->ses['employer_detail']['sid'];
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
        // Let's check if the employee is
        // clocked in or not
        $status = $this->atm->GetEmployeeClockedStatus(
            $this->companyId,
            $this->employeeId,
            $this->date
        );
        //

        _e($status, true);

        return SendResponse(200, $this->resp);
    }
    
    /**
     * Marks attendance
     */
    public function MarkAttendance(){
        //
        $post = $this->input->post(NULL, TRUE);
        //
        // $lastStatus = $this->atm->GetLastStatus(
        //     $this->companyId,
        //     $this->employeeId,
        //     $this->date
        // );
        _e($lastStatus, true, true);
        //

        return SendResponse(200, $this->resp);
    }
}