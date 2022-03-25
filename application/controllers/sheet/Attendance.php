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
     * 
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

}