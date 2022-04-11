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
        $this->args['timeCounts']['totalTodayOvertime'] = 0;
        //
        $this->args['clockEmployeeId'] = $this->employeeId;
        //
        $this->args['lastLocation'] = [0, 0];
        //
        $wlists = $this->atm->GetAttendanceWeekList(
            $this->companyId,
            $this->args['clockEmployeeId'],
            date('Y-m-d', strtotime('monday this week')),
            date('Y-m-d', strtotime('sunday this week'))
        );
        //
        $this->args['timeCounts']['totalWeekWorked'] = $wlists['total_worked_minutes'];
        $this->args['timeCounts']['totalWeekBreaks'] = $wlists['total_break_minutes'];
        // Get today's attendance
        $lists = $this->atm->GetAttendanceList(
            $this->companyId,
            $this->args['clockEmployeeId'],
            $this->date
        );
        //
        if(!empty($lists)){
            //
            $ct = CalculateTime($lists, $this->args['clockEmployeeId']);
            //
            $this->args['timeCounts']['totalTodayWorked'] = $ct['total_worked_minutes'];
            $this->args['timeCounts']['totalTodayBreaks'] = $ct['total_break_minutes'];
            $this->args['timeCounts']['totalTodayOvertime'] = $ct['total_overtime_minutes'];
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
                'datetime' => $fromDate.' 23:59:59',
                'from_format' => 'm/d/Y H:i:s',
                'format' => DB_DATE,
                'from_timezone' => $this->args['employee']['timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
            $toDate = reset_datetime([
                'datetime' => $toDate.' 23:59:59',
                'from_format' => 'm/d/Y H:i:s',
                'format' => DB_DATE,
                'from_timezone' => $this->args['employee']['timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
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
                'format' => DB_DATE,
                'from_timezone' => $this->args['employee']['timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
            $toDate = reset_datetime([
                'datetime' => $toDate.' 23:59:59',
                'from_format' => 'm/d/Y H:i:s',
                'format' => DB_DATE,
                'from_timezone' => $this->args['employee']['timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
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
        $this->args['attendance_sid'] = $id;
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
        if($this->input->get('export', true)){
            //
            $this->exportReport(
                ['ce' => $this->args['currentEmployee'], 'lists' => $this->args['lists']],
                'manage',
                $this->args['currentEmployee']['name'].' Report for '.(
                    reset_datetime([
                        'datetime' => $this->args['lists'][0]['action_date_time'],
                        'format' => DB_DATE,
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' =>  $this->args['currentEmployee']['timezone'],
                        '_this' => $this
                    ])
                ).'.csv',
                []
            );
        }
        //
        $this->load
        ->view($this->header, $this->args)
        ->view('attendance/2022/manage_timesheet')
        ->view($this->footer);
    }
    
    /**
     * Settings
     */
    public function Settings(){
        //
        $this->args['session'] = $this->ses;
        $this->args['employee'] = $this->ses['employer_detail'];
        $this->args['companyId'] = $this->companyId;
        $this->args['security_details'] = db_get_access_level_details($this->ses['employer_detail']['sid']);
        $this->args['title'] = 'Settings | AutomotoHR';
        //
        $this->args['settings'] = $this->atm->GetSettings($this->companyId);
        //
        if(empty($this->args['settings'])){
            //
            $this->args['settings']['company_sid'] = $this->companyId;
            $this->args['settings']['employer_sid'] = $this->employerId;
            $this->args['settings']['created_at'] = $this->datetime;
            $this->args['settings']['updated_at'] = $this->datetime;
            //
            $this->atm->AddSettings($this->companyId, $this->employerId);
        }
        //
        $this->load->model('single/Employee_model', 'sem');
        $this->args['employees'] = $this->sem->GetCompanyEmployees($this->companyId, true);
        $this->load->model('hr_documents_management_model');
        // Get departments & teams
        $this->args['departments'] = $this->hr_documents_management_model->getDepartments($this->companyId);
        $this->args['teams'] = $this->hr_documents_management_model->getTeams($this->companyId, $this->args['departments']);
        //
        $this->load
        ->view($this->header, $this->args)
        ->view('attendance/2022/settings')
        ->view($this->footer);
    }

    /**
     * Today's overview
     */
    public function TodayOverview () {
        //
        $filterEmployeeId = !empty($this->input->get("id", true)) ? $this->input->get("id", true) : [];
        //
        $this->args['session'] = $this->ses;
        $this->args['employee'] = $this->ses['employer_detail'];
        $this->args['companyId'] = $this->companyId;
        $this->args['security_details'] = db_get_access_level_details($this->ses['employer_detail']['sid']);
        $this->args['title'] = 'Time Sheet | AutomotoHR';
        // 
        $this->load->model('single/Employee_model', 'sem');
        // Get employee's list
        $employees = $this->sem->GetCompanyEmployees($this->companyId, true);
        //
        $ActiveEmployeeSids = array();
        $absentEmployees = [];
        //
        if (!empty($employees)) {
           
            $employeeAttendance = $this->atm->GetActiveEmployee($this->date, $filterEmployeeId);

            if (!empty($employeeAttendance)) {
                $AllEployeesSids = array_column($employees, 'sid');
                $ActiveEmployeeSids = array_column($employeeAttendance, 'employee_sid');
                $absentEmployees = array_diff($AllEployeesSids, $ActiveEmployeeSids);

                foreach ($employeeAttendance as $ae_key => $act_employee) {
                    //
                    $attendanceList = $this->atm->GetAttendanceListByID($act_employee["sid"]);
                    // 
                    if(!empty($attendanceList)){
                        //
                        $ct = CalculateTime($attendanceList, $act_employee["employee_sid"]);
                        //
                        $employeeAttendance[$ae_key]['total_minutes'] = $ct['total_minutes'];
                        $employeeAttendance[$ae_key]['total_worked_minutes'] = $ct['total_worked_minutes'];
                        $employeeAttendance[$ae_key]['total_break_minutes'] = $ct['total_break_minutes'];
                        $employeeAttendance[$ae_key]['total_overtime_minutes'] = $ct['total_overtime_minutes'];
                        //
                    }
                } 

            }else {
                $absentEmployees = array_column($employees, 'sid');
            }

            
            foreach ($absentEmployees as $ab_key => $absentEmployeeSid) {
                if (!empty($filterEmployeeId) && !in_array($absentEmployeeSid, $filterEmployeeId)) {
                    unset($absentEmployees[$ab_key]);
                }
            }
        }    
        //
        $todayDate = reset_datetime([
            'datetime' => $this->date,
            'from_format' => DB_DATE,
            'format' => DATE,
            'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
            '_this' => $this
        ]);
        //
        $this->args['today_date'] = $todayDate;
        $this->args['employees'] = $employees;
        $this->args['active_employees'] = $employeeAttendance;
        $this->args['inactive_employees'] = $absentEmployees;
        $this->args['filterEmployeeId'] = $filterEmployeeId;
        //
        $this->load
        ->view($this->header, $this->args)
        ->view('attendance/2022/today_overview')
        ->view($this->footer);
    }

    /**
     * Today's overview
     */
    public function Overtime () {
        //
        $this->args['session'] = $this->ses;
        $this->args['employee'] = $this->ses['employer_detail'];
        $this->args['companyId'] = $this->companyId;
        $this->args['security_details'] = db_get_access_level_details($this->ses['employer_detail']['sid']);
        $this->args['title'] = 'Overtime | AutomotoHR';
        // 
        $this->load->model('single/Employee_model', 'sem');
        // Get employee's list
        $employees = $this->sem->GetCompanyEmployees($this->companyId, true);
        // Filter
        //
        $selectedEmployeeIds = $this->input->get("id", true) ? array_filter($this->input->get("id", true)) : [];
        // Set filter
        $fromDate = $this->input->get('from', true) ? $this->input->get('from', true) : '';
        $toDate = $this->input->get('to', true) ? $this->input->get('to', true) : '';
        //
        if(empty($fromDate) || empty($toDate)){
            $SysToDate = $SysFromDate = $fromDate = $toDate = date('Y-m-d', strtotime('now'));
        } else{
            //
            $SysFromDate = formatDateToDB($fromDate);
            $SysToDate = formatDateToDB($toDate);
            //
            $fromDate = reset_datetime([
                'datetime' => $fromDate.' 00:00:00',
                'from_format' => 'm/d/Y H:i:s',
                'format' => DB_DATE,
                'from_timezone' => $this->args['employee']['timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
            $toDate = reset_datetime([
                'datetime' => $toDate.' 23:59:59',
                'from_format' => 'm/d/Y H:i:s',
                'format' => DB_DATE,
                'from_timezone' => $this->args['employee']['timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
        }
        //
        $this->args['from_date'] = $SysFromDate;
        $this->args['to_date'] = $SysToDate;
        $this->args['employees'] = $employees;
        $this->args['selected_employee_ids'] = $selectedEmployeeIds;
        //
        $overtimeEmployees = $this->atm->GetEmployeeWithOverTime(
            $this->companyId, 
            $fromDate, 
            $toDate,
            $this->args['selected_employee_ids'],
            'employee_sid',
            false
        );
        //
        $overtimeEmployeeIds = array_column($overtimeEmployees, 'employee_sid');
        //
        $fl = [];
        //
        foreach($overtimeEmployeeIds as $eid){
            //
            $fl[$eid] = $this->atm->GetAttendanceWeekList(
                $this->companyId,
                $eid,
                $fromDate,
                $toDate
            );
        }
        //
        if($this->input->get('export', true)){
            //
            $this->exportReport(
                $fl,
                'overtime',
                'Overtime Report '.$SysFromDate.' to '.$SysToDate.'.csv',
                $employees
            );
        }
        //
        $this->args['lists'] = $fl;
        //
        $this->load
        ->view($this->header, $this->args)
        ->view('attendance/2022/overtime')
        ->view($this->footer);
    }

    /**
     * Today's overview
     */
    public function Report () {
        //
        $this->args['session'] = $this->ses;
        $this->args['employee'] = $this->ses['employer_detail'];
        $this->args['companyId'] = $this->companyId;
        $this->args['security_details'] = db_get_access_level_details($this->ses['employer_detail']['sid']);
        $this->args['title'] = 'Report | AutomotoHR';
        // 
        $this->load->model('single/Employee_model', 'sem');
        // Get employee's list
        $employees = $this->sem->GetCompanyEmployees($this->companyId, true);
        // Filter
        //
        $selectedEmployeeIds = $this->input->get("id", true) ? array_filter($this->input->get("id", true)) : [];
        // Set filter
        $fromDate = $this->input->get('from', true) ? $this->input->get('from', true) : '';
        $toDate = $this->input->get('to', true) ? $this->input->get('to', true) : '';
        //
        if(empty($fromDate) || empty($toDate)){
            $SysToDate = $SysFromDate = $fromDate = $toDate = date('Y-m-d', strtotime('now'));
        } else{
            //
            $SysFromDate = formatDateToDB($fromDate);
            $SysToDate = formatDateToDB($toDate);
            //
            $fromDate = reset_datetime([
                'datetime' => $fromDate.' 00:00:00',
                'from_format' => 'm/d/Y H:i:s',
                'format' => DB_DATE,
                'from_timezone' => $this->args['employee']['timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
            $toDate = reset_datetime([
                'datetime' => $toDate.' 23:59:59',
                'from_format' => 'm/d/Y H:i:s',
                'format' => DB_DATE,
                'from_timezone' => $this->args['employee']['timezone'],
                'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR,
                '_this' => $this
            ]);
        }
        //
        $this->args['from_date'] = $SysFromDate;
        $this->args['to_date'] = $SysToDate;
        $this->args['employees'] = $employees;
        $this->args['selected_employee_ids'] = $selectedEmployeeIds;
        //
        $clockedInEmployees = $this->atm->GetEmployeesByFilter(
            $this->companyId, 
            $fromDate, 
            $toDate,
            $this->args['selected_employee_ids'],
            'employee_sid'
        );
        //
        $clockedInEmployeesIds = array_column($clockedInEmployees, 'employee_sid');
        //
        $fl = [];
        //
        foreach($clockedInEmployeesIds as $eid){
            //
            $fl[$eid] = $this->atm->GetAttendanceWeekList(
                $this->companyId,
                $eid,
                $fromDate,
                $toDate
            );
        }
        //
        if($this->input->get('export', true)){
            //
            $this->exportReport(
                $fl,
                'report',
                'Report '.$SysFromDate.' to '.$SysToDate.'.csv',
                $employees
            );
        }
        //
        $this->args['lists'] = $fl;
        //
        $this->load
        ->view($this->header, $this->args)
        ->view('attendance/2022/report')
        ->view($this->footer);
    }


    /**
     * 
     */
    private function exportReport($lists, $type, $filename, $employees){
        // Set the content type 
        header('Content-type: application/csv');
        // Set the file name option to a filename of your choice.
        header('Content-Disposition: attachment; filename='.($filename).'');
        // Set the encoding
        header("Content-Transfer-Encoding: UTF-8");
        //
        $headers = [];
        //
        if($type === 'report' || $type === 'overtime'){
            //
            $headers = [
                'employee',
                'Worked Time (HH:MM)',
                'Break Time (HH:MM)',
                'Total Time (HH:MM)',
                'Over Time (HH:MM)'
            ];
        }
        //
        $handler = fopen("php://output", 'w');
        //
        if(!empty($headers)){
            //
            fputcsv($handler, $headers);
        }
        // For report and overtime
        if($type === 'report' || $type === 'overtime'){
            //
            if(!empty($lists)):
                foreach($lists as $key => $employee):
                    $employee_info = $employees[$key];
                    //
                    $total = GetHMSFromSeconds($employee['total_minutes']);
                    $todayWorked = GetHMSFromSeconds($employee['total_worked_minutes']);
                    $todayBreak = GetHMSFromSeconds($employee['total_break_minutes']);
                    $overtime = GetHMSFromSeconds($employee['total_overtime_minutes']);
                
                    $t = [];
                    $t[] = $employee_info["name"].$employee_info["role"];
                    $t[] = $todayWorked['hours'].":".$todayWorked['minutes'];
                    $t[] = $todayBreak['hours'].":".$todayBreak['minutes'];
                    $t[] = $total['hours'].":".$total['minutes'];
                    $t[] = $overtime['hours'].":".$overtime['minutes'];
                    //       
                    fputcsv($handler, $t);
                
                    if(!empty($employee['lists'])):
                        foreach($employee['lists'] as $k1 => $v1):
                            //
                            $total2 = GetHMSFromSeconds($v1['total_minutes']);
                            $todayWorked2 = GetHMSFromSeconds($v1['total_worked_minutes']);
                            $todayBreak2 = GetHMSFromSeconds($v1['total_break_minutes']);
                            $overtime2 = GetHMSFromSeconds($v1['total_overtime_minutes']);   
                            
                            $t = [];
                            $t[] = reset_datetime([
                                'datetime' => $k1.' 00:00:00',
                                'from_format' => 'Y-m-d H:i:s',
                                'format' => DATE,
                                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                'new_zone' => $this->args['employees'][$key]['timezone'],
                                '_this' => $this
                            ]);
                            $t[] = $todayWorked2['hours'].":".$todayWorked2['minutes'];
                            $t[] = $todayBreak2['hours'].":".$todayBreak2['minutes'];
                            $t[] = $total2['hours'].":".$total2['minutes'];
                            $t[] = $overtime2['hours'].":".$overtime2['minutes'];
                            //       
                            fputcsv($handler, $t);
                        endforeach;
                    endif;
                endforeach;
            endif;
        }
        // For manage
        if($type === 'manage'){
            //
            $headers = ['Employee details'];
            fputcsv($handler, $headers);
            //
            $headers = [''];
            fputcsv($handler, $headers);
            //
            $headers = ['Name', $lists['ce']['name']];
            fputcsv($handler, $headers);
            //
            $headers = ['Role', $lists['ce']['role']];
            fputcsv($handler, $headers);
            //
            $headers = ['Timezone', $lists['ce']['timezone']];
            fputcsv($handler, $headers);
            //
            $headers = ['Workable Shift Time', GetEmployeeShiftTime($lists['ce']['shift_hours'], $lists['ce']['shift_minutes'], 't')];
            fputcsv($handler, $headers);
            //
            $headers = ['Employee Since', formatDateToDB($lists['ce']['joined_on'], DB_DATE, DATE)];
            fputcsv($handler, $headers);
            //
            $headers = [''];
            fputcsv($handler, $headers);
            //
            fputcsv($handler, [
                'Report Date', date(DATE, strtotime('now'))
            ]);
            // Calculate time
            $ct = CalculateTime($lists['lists'], $lists['ce']['sid']);
            // Seconds to HM
            $totalTime = GetHMSFromSeconds($ct['total_minutes']);
            $todayWorked = GetHMSFromSeconds($ct['total_worked_minutes']);
            $todayBreak = GetHMSFromSeconds($ct['total_break_minutes']);
            $overtime = GetHMSFromSeconds($ct['total_overtime_minutes']);
            //
            fputcsv($handler, [
                'Clocked Time', $todayWorked['hours'].' H & '.$todayWorked['minutes'].' M'
            ]);
            //
            fputcsv($handler, [
                'Break Time', $todayBreak['hours'].' H & '.$todayBreak['minutes'].' M'
            ]);
            //
            fputcsv($handler, [
                'Clocked Time (Including breaks)', $totalTime['hours'].' H & '.$totalTime['minutes'].' M'
            ]);
            //
            fputcsv($handler, [
                'Overtime', $overtime['hours'].' H & '.$overtime['minutes'].' M'
            ]);
            //
            $headers = [''];
            fputcsv($handler, $headers);
            //
            fputcsv($handler, [
                'Status', 'Action Date', 'Action Time', 'Latitude', 'Longitude'
            ]);
            //
            foreach($lists['lists'] as $list){
                fputcsv($handler, [
                    GetCleanedAction($list['action']),
                    reset_datetime([
                        'datetime' => $list['action_date_time'],
                        'format' => DATE,
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $lists['ca']['timezone'],
                        '_this' => $this
                    ]),
                    reset_datetime([
                        'datetime' => $list['action_date_time'],
                        'format' => MD,
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $lists['ca']['timezone'],
                        '_this' => $this
                    ]),
                    $list['latitude'],
                    $list['longitude']
                ]);
            }
        }

        //
        fclose($handler);
        exit(0);
    }

}