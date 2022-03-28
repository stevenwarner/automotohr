<?php defined('BASEPATH') || exit('No direct script access allowed');

class Attendance extends Public_Controller {

    private $ses = [];
    private $companyId = 0;
    private $employerId = 0;
    private $employeeId = 0;
    private $args = [];

    /**
     * 
     */
    public function __construct() {
        //
        parent::__construct();
        //
        if (!$this->session->userdata('logged_in')) {
            return redirect('/');
        }
        //
        $this->load->model('attendance_model', 'atm');
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
        //
        $this->ses = $this->session->userdata('logged_in');
        $this->companyId = $this->ses["company_detail"]["sid"];
        $this->employeeId = $this->employerId = $this->ses["employer_detail"]["sid"];
        //
        $this->args = [];
        //
        $this->header = 'main/header';
        $this->footer = 'main/footer';
    }


    /**
     * Clock my day
     */
    public function MyAttendance(){
        //
        $this->args['session'] = $this->ses;
        $this->args['employee'] = $this->ses['employer_detail'];
        $this->args['companyId'] = $this->companyId;
        $this->args['security_details'] = db_get_access_level_details($this->ses['employer_detail']['sid']);
        $this->args['title'] = 'Clock My Day | AutomotoHR';
        // Set default totals
        $this->args['timeCounts'] = [];
        $this->args['timeCounts']['totalWeekWorked'] = 0;
        $this->args['timeCounts']['totalWeekBreaks'] = 0;
        $this->args['timeCounts']['totalTodayWorked'] = 0;
        $this->args['timeCounts']['totalTodayBreaks'] = 0;
        //
        $this->args['lastLocation'] = [0, 0];
        //
        $wlists = $this->atm->GetAttendanceWeekList(
            $this->companyId,
            $this->employeeId,
            date('Y-m-d', strtotime('monday this week')),
            date('Y-m-d', strtotime('sunday this week'))
        );
        //
        $this->args['timeCounts']['totalWeekWorked'] = $wlists['total_worked_minutes'] - $wlists['total_break_minutes'];
        $this->args['timeCounts']['totalWeekBreaks'] = $wlists['total_break_minutes'];
        // Get today's attendance
        $lists = $this->atm->GetAttendanceList(
            $this->companyId,
            $this->employeeId,
            $this->date
        );
        if(!empty($lists)){
            //
            $ct = CalculateTime($lists);
            //
            $this->args['timeCounts']['totalTodayWorked'] = $ct['total_worked_minutes'] - $ct['total_break_minutes'];
            $this->args['timeCounts']['totalTodayBreaks'] = $ct['total_break_minutes'];
            $this->args['lastLocation'] = [$lists[0]['latitude'], $lists[0]['longitude']];
        }
        //
        $this->args['todayList'] = $lists;
        //
        $this->load
        ->view($this->header, $this->args)
        ->view('attendance/2022/my_day')
        ->view($this->footer);
    }

    /**
     * My time sheet
     */
    public function MyTimeSheet(){
        //
        $this->args['session'] = $this->ses;
        $this->args['employee'] = $this->ses['employer_detail'];
        $this->args['companyId'] = $this->companyId;
        $this->args['security_details'] = db_get_access_level_details($this->ses['employer_detail']['sid']);
        $this->args['title'] = 'My Time Sheet | AutomotoHR';
        // Set filter
        $fromDate = $this->input->get('from', true) ? $this->input->get('from', true) : '';
        $toDate = $this->input->get('to', true) ? $this->input->get('to', true) : '';
        //
        if(empty($fromDate) || empty($toDate)){
            $SysToDate = $SysFromDate = $toDate = $fromDate = date('Y-m-d', strtotime('now'));
        } else{
            //
            $SysFromDate = formatDateToDB($fromDate);
            $SysToDate = formatDateToDB($toDate);
            //
            $fromDate = reset_datetime([
                'datetime' => $fromDate.' 00:00:00',
                'from_format' => 'm/d/Y H:i:s',
                'format' => 'Y-m-d H:i:s',
                'from_timezone' => $this->args['employee']['timezone'],
                'timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
            $toDate = reset_datetime([
                'datetime' => $toDate.' 00:00:00',
                'from_format' => 'm/d/Y H:i:s',
                'format' => 'Y-m-d H:i:s',
                'from_timezone' => $this->args['employee']['timezone'],
                'timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
        }
        //
        $this->args['fromDate'] = $SysFromDate;
        $this->args['toDate'] = $SysToDate;
        //
        $this->args['lists'] = $this->atm->GetAttendanceWeekList(
            $this->companyId,
            $this->employeeId,
            $fromDate,
            $toDate
        );
        //
        $this->load
        ->view($this->header, $this->args)
        ->view('attendance/2022/my_timesheet')
        ->view($this->footer);
    }

    /**
     * Time sheet
     */
    public function TimeSheet(){
        //
        $this->args['session'] = $this->ses;
        $this->args['employee'] = $this->ses['employer_detail'];
        $this->args['companyId'] = $this->companyId;
        $this->args['security_details'] = db_get_access_level_details($this->ses['employer_detail']['sid']);
        $this->args['title'] = 'Time Sheet | AutomotoHR';
        // Set filter
        $fromDate = $this->input->get('from', true) ? $this->input->get('from', true) : '';
        $toDate = $this->input->get('to', true) ? $this->input->get('to', true) : '';
        $employeeId = $this->input->get('id', true) ? $this->input->get('id', true) : '';
        //
        if(empty($fromDate) || empty($toDate)){
            $SysToDate = $SysFromDate = $toDate = $fromDate = date('Y-m-d', strtotime('now'));
        } else{
            //
            $SysFromDate = formatDateToDB($fromDate);
            $SysToDate = formatDateToDB($toDate);
            //
            $fromDate = reset_datetime([
                'datetime' => $fromDate.' 00:00:00',
                'from_format' => 'm/d/Y H:i:s',
                'format' => 'Y-m-d H:i:s',
                'from_timezone' => $this->args['employee']['timezone'],
                'timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
            $toDate = reset_datetime([
                'datetime' => $toDate.' 00:00:00',
                'from_format' => 'm/d/Y H:i:s',
                'format' => 'Y-m-d H:i:s',
                'from_timezone' => $this->args['employee']['timezone'],
                'timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
        }
        //
        if(empty($employeeId)){
            $employeeId = $this->employeeId;
        }
        //
        $this->args['fromDate'] = $SysFromDate;
        $this->args['toDate'] = $SysToDate;
        $this->args['filterEmployeeId'] = $employeeId;
        //
        $this->load->model('single/Employee_model', 'sem');
        // Get employee's list
        $this->args['employees'] = $this->sem->GetCompanyEmployees($this->companyId, true);
        $this->args['currentEmployee'] = $this->args['employees'][$employeeId];
        //
        $this->args['lists'] = $this->atm->GetAttendanceWeekList(
            $this->companyId,
            $employeeId,
            $fromDate,
            $toDate
        );
        //
        $this->load
        ->view($this->header, $this->args)
        ->view('attendance/2022/timesheet')
        ->view($this->footer);
    }

    /**
     * Manage Time sheet
     * 
     * @param number $id
     */
    public function ManageTimeSheet($id){
        //
        $this->args['session'] = $this->ses;
        $this->args['employee'] = $this->ses['employer_detail'];
        $this->args['companyId'] = $this->companyId;
        $this->args['security_details'] = db_get_access_level_details($this->ses['employer_detail']['sid']);
        $this->args['title'] = 'Time Sheet | AutomotoHR';
        // Verify the record
        $record = $this->atm->VerifyAttendanceById(
            $this->companyId,
            $id
        );
    //
        if(empty($record)){
            return redirect('attendance/my');
        }
        //
        $this->args['lists'] = $this->atm->GetAttendanceListById(
            $id
        );
        //
        $this->load->model('single/Employee_model', 'sem');
        // Get employee's list
        $this->args['employees'] = $this->sem->GetCompanyEmployees($this->companyId, true);
        $this->args['currentEmployee'] = $this->args['employees'][$record['employee_sid']];
        //
        $this->load
        ->view($this->header, $this->args)
        ->view('attendance/2022/manage_timesheet')
        ->view($this->footer);
    }

}