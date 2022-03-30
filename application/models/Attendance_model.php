<?php

class Attendance_model extends CI_Model
{

    private $datetime;

    function __construct()
    {
        parent::__construct();
        //
        $this->datetime = date('Y-m-d H:i:s', strtotime('now'));
    }


    function get_last_attendance_record($company_sid, $employer_sid, $from_date = '', $to_date = '', $attendance_type = '')
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->order_by('attendance_date', 'DESC');
        $this->db->limit(1);

        if ($from_date != '' && $to_date != '') {
            $this->db->where('attendance_date BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
        }

        if ($attendance_type != '') {
            $this->db->where('attendance_type', $attendance_type);
        }


        $attendance = $this->db->get('attendance')->result_array();

        if (!empty($attendance)) {
            return $attendance[0];
        } else {
            return array();
        }
    }

    function get_attendance($company_sid, $employer_sid, $from_date, $to_date)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->order_by('attendance_date', 'ASC');

        $this->db->where('attendance_date BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');

        $attendance = $this->db->get('attendance')->result_array();

        return $attendance;
    }

    function get_attendance_register($company_sid, $employer_sid, $from_date, $to_date, $attendance_type = 'clock_out', $get_breaks = false)
    {
        $this->db->select('attendance.*');

        $this->db->select('t2.attendance_type as start_attendance_type');
        $this->db->select('t2.attendance_date as start_attendance_date');


        $this->db->where('attendance.company_sid', $company_sid);
        $this->db->where('attendance.employer_sid', $employer_sid);
        $this->db->where('attendance.attendance_type', $attendance_type);


        $this->db->order_by('attendance.attendance_date', 'ASC');

        $this->db->where('attendance.attendance_date BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');

        $this->db->join('attendance as t2', 't2.sid = attendance.last_attendance_sid', 'left');

        $attendances = $this->db->get('attendance')->result_array();


        if ($get_breaks == true) {
            if (!empty($attendances)) {
                foreach ($attendances as $key => $attendance) {

                    $total_break_hours = 0;
                    $total_break_minutes = 0;

                    $start_date = $attendance['start_attendance_date'];
                    $end_date = $attendance['attendance_date'];

                    $breaks = $this->get_attendance_register($company_sid, $employer_sid, $start_date, $end_date, 'break_end');

                    if (!empty($breaks)) {
                        foreach ($breaks as $break) {
                            $hours = $break['total_hours'];
                            $minutes = $break['total_minutes'];

                            $total_break_hours = $total_break_hours + $hours;
                            $total_break_minutes = $total_break_minutes + $minutes;
                        }
                    }

                    $break_hours_to_minutes = $total_break_hours * 60;

                    $total_break_minutes = $total_break_minutes + $break_hours_to_minutes;


                    $clocked_hours = $attendance['total_hours'];
                    $clocked_minutes = $attendance['total_minutes'];

                    $clocked_hours_to_minutes = $clocked_hours * 60;
                    $clocked_minutes = $clocked_minutes + $clocked_hours_to_minutes;


                    $actual_minutes = $clocked_minutes - $total_break_minutes;

                    $clocked_converted_hours = $actual_minutes / 60;
                    $remainder = $actual_minutes % 60;

                    $converted_break_hours = $total_break_minutes / 60;
                    $break_minutes = $total_break_minutes % 60;


                    $attendances[$key]['hours_after_breaks'] = round($clocked_converted_hours, 0);
                    $attendances[$key]['minutes_after_breaks'] = round($remainder);


                    $attendances[$key]['total_break_hours'] = round($converted_break_hours, 0);
                    $attendances[$key]['total_break_minutes'] = round($break_minutes);

                    $attendances[$key]['breaks'] = $breaks;
                }
            }
        }

        return $attendances;
    }


    function insert_attendance_record($data)
    {
        $this->db->insert('attendance', $data);
    }


    function get_employees($company_sid)
    {
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('is_executive_admin', 0);

        return $this->db->get('users')->result_array();
    }

    function get_employee_information($company_sid, $employee_sid)
    {
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $employee_sid);

        $details = $this->db->get('users')->result_array();

        if (!empty($details)) {
            $details = $details[0];

            $position_info = $this->get_employee_position_from_org_hierarchy($company_sid, $employee_sid);

            if (!empty($position_info)) {
                $details['position_info'] = $position_info;
            } else {
                $details['position_info'] = array();
            }
        } else {
            $details = array();
        }

        return $details;
    }

    function get_company_time_sheet($company_sid, $start_date, $end_date, $attendance_type = '', $employer_sid = 0)
    {

        $this->db->select('attendance.*');
        $this->db->select('att.attendance_date as out_attendance_date');
        $this->db->select('att.attendance_type as out_attendance_type');
        $this->db->select('att.latitude as out_latitude');
        $this->db->select('att.longitude as out_longitude');
        $this->db->select('att.sid as out_sid');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');
        $this->db->select('users.profile_picture');

        $this->db->where('attendance.company_sid', $company_sid);

        if ($attendance_type != '') {
            $this->db->where('attendance.attendance_type', $attendance_type);
        }

        if ($employer_sid > 0) {
            $this->db->where('attendance.employer_sid', $employer_sid);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $this->db->where('attendance.attendance_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        }

        $this->db->join('attendance as att', 'attendance.sid = att.last_attendance_sid', 'left');
        $this->db->join('users', 'attendance.employer_sid = users.sid', 'left');

        $this->db->order_by('attendance.attendance_date', 'ASC');

        $records = $this->db->get('attendance')->result_array();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $out_sid = $record['out_sid'];
                $company_sid = $record['company_sid'];
                $employer_sid = $record['employer_sid'];

                if ($out_sid > 0) {
                    $this->db->select('*');
                    $this->db->where('clock_out_attendance_sid', $out_sid);
                    $this->db->where('company_sid', $company_sid);
                    $this->db->where('employer_sid', $employer_sid);
                    $this->db->limit(1);
                    $this->db->order_by('sid', 'DESC');

                    $totals_record = $this->db->get('attendance_totals')->result_array();

                    if (!empty($totals_record)) {
                        $totals_record = $totals_record[0];

                        $records[$key]['totals'] = $totals_record;
                    } else {
                        $records[$key]['totals'] = array();
                    }
                } else {
                    $records[$key]['totals'] = array();
                }
            }
        }

        return $records;
    }

    function get_attendance_record($company_sid, $employer_sid, $attendance_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('sid', $attendance_sid);

        $this->db->limit(1);
        $record = $this->db->get('attendance')->result_array();

        if (!empty($record)) {
            return $record[0];
        } else {
            return array();
        }
    }

    function update_attendance_record($company_sid, $employer_sid, $attendance_sid, $modified_by, $timezone, $new_datetime)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('sid', $attendance_sid);
        $record = $this->db->get('attendance')->result_array();

        if (!empty($record)) {
            $record = $record[0];

            $data_to_update = array();
            $data_to_update['attendance_date'] = $new_datetime;
            $data_to_update['modified_by'] = $modified_by;
            date_default_timezone_set($timezone);
            $data_to_update['modified_date'] = date('Y-m-d H:i:s');
            $data_to_update['attendance_year'] = date('Y');
            $data_to_update['attendance_week'] = date('W');

            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $employer_sid);
            $this->db->where('sid', $attendance_sid);
            $this->db->update('attendance', $data_to_update);


            $this->db->select('*');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $employer_sid);
            $this->db->where('sid', $attendance_sid);
            $record = $this->db->get('attendance')->result_array();
            $record = $record[0];

            unset($record['sid']);
            $record['attendance_sid'] = $attendance_sid;
            $this->db->insert('attendance_modification_history', $record);

        }
    }

    public function get_out_or_end_record($company_sid, $employer_sid, $attendance_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('last_attendance_sid', $attendance_sid);

        $record = $this->db->get('attendance')->result_array();

        if (!empty($record)) {
            return $record[0];
        } else {
            return array();
        }
    }


    public function update_time_logged_for_out($sid, $hours, $minutes)
    {
        $this->db->where('sid', $sid);
        $data_to_update = array();
        $data_to_update['total_hours'] = $hours;
        $data_to_update['total_minutes'] = $minutes;
        $this->db->update('attendance', $data_to_update);
    }

    public function update_time_logged_for_in($sid, $hours, $minutes)
    {
        $this->db->where('last_attendance_sid', $sid);
        $data_to_update = array();
        $data_to_update['total_hours'] = $hours;
        $data_to_update['total_minutes'] = $minutes;
        $this->db->update('attendance', $data_to_update);
    }

    public function get_portal_information($company_sid)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);
        $portal_info = $this->db->get('portal_employer')->result_array();

        if (!empty($portal_info)) {
            return $portal_info[0];
        } else {
            return array();
        }
    }

    public function insert_attendance_totals_record($data)
    {
        $this->db->insert('attendance_totals', $data);
    }

    public function get_employee_position_from_org_hierarchy($company_sid, $employee_sid)
    {
        $this->db->select('vacancies_status.*');
        $this->db->select('departments.dept_name as department_name');
        $this->db->select('positions.position_name');

        $this->db->where('vacancies_status.company_sid', $company_sid);
        $this->db->where('vacancies_status.employee_sid', $employee_sid);
        $this->db->order_by('vacancies_status.sid', 'DESC');
        $this->db->limit(1);

        $this->db->join('departments', 'departments.sid = vacancies_status.department_sid', 'left');
        $this->db->join('positions', 'positions.sid = vacancies_status.position_sid', 'left');

        $data = $this->db->get('vacancies_status')->result_array();

        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }

    function getApplicantAverageRating($app_id, $users_type = NULL)
    {
        $result = $this->db->get_where('portal_applicant_rating', array(
            'applicant_job_sid' => $app_id
        ));
        $rows = $result->num_rows();
        if ($rows > 0) {
            $this->db->select_sum('rating');
            $this->db->where('applicant_job_sid', $app_id);
            $this->db->where('users_type', $users_type);
            $data = $this->db->get('portal_applicant_rating')->result_array();
            return round($data[0]['rating'] / $rows, 2);
        }
    }

    function get_last_attendance_record_by_clock_in_sid($clock_in_sid, $attendance_status = '', $attendance_type = '')
    {
        $this->db->select('*');


        $this->db->group_start();

        $this->db->where('clock_in_sid', $clock_in_sid);

        if ($attendance_status != '') {
            $this->db->where('attendance_status', $attendance_status);
        }

        if ($attendance_type != '') {
            $this->db->where('attendance_type', $attendance_type);
        }

        $this->db->group_end();


        $this->db->or_where('sid', $clock_in_sid);


        $this->db->order_by('created_date', 'DESC');
        $this->db->limit(1);

        $attendance = $this->db->get('attendance')->result_array();

        //echo $this->db->last_query();

        if (!empty($attendance)) {
            return $attendance[0];
        } else {
            return array();
        }
    }

    function get_attendance_records_by_clock_in_sid($clock_in_sid) {
        $this->db->select('*');
        $this->db->where('clock_in_sid', $clock_in_sid);
        $this->db->or_where('sid', $clock_in_sid);
        $this->db->order_by('attendance_date', 'ASC');
        return $this->db->get('attendance')->result_array();
    }

    function delete_attendance_record($sid, $company_sid, $employer_sid)
    {
        $this->db->where('sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->delete('attendance');
    }

    function get_attendance_records($company_sid, $employer_sid, $date_start, $date_end)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('attendance_date BETWEEN \'' . $date_start . '\' AND \'' . $date_end . '\'');
        $this->db->order_by('created_date', 'ASC');
        $records = $this->db->get('attendance')->result_array();
        if(!empty($records)){
            foreach($records as $key => $record){
                if($record['attendance_type'] == 'clock_in'){
                    $count = $this->get_records_count_by_clock_in_sid($record['sid']);
                    //echo $this->db->last_query();
                    $records[$key]['linked_records_count'] = $count;
                } else {
                    $records[$key]['linked_records_count'] = 0;
                }
            }
        }

        return $records;
    }
    
    function manage_attendance_records($company_sid, $employer_sid, $date_start, $date_end) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('attendance_date BETWEEN \'' . $date_start . '\' AND \'' . $date_end . '\'');
        $this->db->order_by('attendance_date', 'ASC');
        $records = $this->db->get('attendance')->result_array();
        if(!empty($records)){
            foreach($records as $key => $record){
                if($record['attendance_type'] == 'clock_in'){
                    $count = $this->get_records_count_by_clock_in_sid($record['sid']);
                    $records[$key]['linked_records_count'] = $count;
                } else {
                    $records[$key]['linked_records_count'] = 0;
                }
            }
        }

        return $records;
    }

    function get_last_clock_in($company_sid, $employer_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('attendance_type', 'clock_in');
        $this->db->order_by('attendance_date', 'DESC');
        $this->db->limit(1);
        $record = $this->db->get('attendance')->result_array();
        //echo '<br> last query: '.$this->db->last_query().'<br>';
        if (!empty($record)) {
            return $record[0];
        } else {
            return array();
        }
    }

    function get_last_clock_out($company_sid, $employer_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('attendance_type', 'clock_out');
        $this->db->order_by('attendance_date', 'DESC');
        $this->db->limit(1);
        $record = $this->db->get('attendance')->result_array();

        if (!empty($record)) {
            return $record[0];
        } else {
            return array();
        }
    }

    function get_last_clock_out_for_clock_in($company_sid, $employer_sid, $clock_in) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('clock_in_sid', $clock_in);
        $this->db->where('attendance_type', 'clock_out');
        $this->db->order_by('sid', 'DESC');
        $this->db->limit(1);
        $record = $this->db->get('attendance')->result_array();

        if (!empty($record)) {
            return $record[0];
        } else {
            return array();
        }
    }

    function get_all_clock_in_records($company_sid, $employer_sid, $start_date, $end_date)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('attendance_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        $this->db->where('attendance_type', 'clock_in');

        return $this->db->get('attendance')->result_array();
    }

    function get_all_attendance_total_records($company_sid, $employer_sid, $start_date, $end_date){
        //echo $company_sid.'<br>'.$employer_sid.'<br>'.$start_date.'<br>'.$end_date;
        //exit;
        $this->db->select('attendance_totals.*');
        //$this->db->select_max('attendance_totals.attendance_date');
        $this->db->select('users.profile_picture');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');
        $this->db->where('attendance_totals.company_sid', $company_sid);
        $this->db->where('attendance_totals.employer_sid', $employer_sid);
        $this->db->where('attendance_totals.status', 1);

        if(!empty($start_date) && !empty($end_date)) {
            $this->db->where('attendance_totals.attendance_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        }
        $this->db->order_by('attendance_totals.attendance_date', 'DESC');
        $this->db->join('users', 'attendance_totals.employer_sid = users.sid', 'left');
        $records = $this->db->get('attendance_totals');
        //echo $this->db->last_query();

        $records_array = $records->result_array();
        $records->free_result();

        if(!empty($records_array)){
//            echo "<br>I am IN Model <br><pre>";
//            print_r($records_array);
//            exit;
            return $records_array;
        } else {
            return array();
        }

    }
    
    function get_all_attendance_total_records_modified($company_sid, $employer_sid, $start_date, $end_date){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        //$this->db->where('attendance_totals.status', 1);
        if(!empty($start_date) && !empty($end_date)) {
            $this->db->where('attendance_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        }

        $this->db->order_by('attendance_date', 'DESC');
        $records = $this->db->get('attendance_totals');
      
//exit;
        $records_array = $records->result_array();
        $records->free_result();

        if(!empty($records_array)){
//            echo "<br>I am IN Model <br><pre>";
//            print_r($records_array);
//            exit;
            return $records_array;
        } else {
            return array();
        }

    }

    function get_attendance_clock_in_sid($company_sid, $employer_sid, $entry_datetime){
        $entry_datetime = date('Y-m-d H:i:s', strtotime($entry_datetime));
        $this->db->select_max('sid');
        //$this->db->where('attendance_date BETWEEN \'' . $filter_start . '\' AND \'' . $filter_end . '\'');
        $this->db->where('attendance_date <= \'' . $entry_datetime . '\'');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('attendance_type', 'clock_in');
        $this->db->order_by('attendance_date', 'DESC');
        $this->db->limit(1);

        $record = $this->db->get('attendance');
        //echo $this->db->last_query();
        $record_array = $record->result_array();
        $record->free_result();

        if(!empty($record_array)){
            return $record_array[0]['sid'];
        } else {
            return 0;
        }
    }

    function set_status_for_attendance_totals_records($date, $status = 0){
        $this->db->where('attendance_date', date('Y-m-d', strtotime($date)));
        $data_to_update = array();
        $data_to_update['status'] = $status;
        $this->db->update('attendance_totals', $data_to_update);

    }

    function get_records_count_by_clock_in_sid($clock_in_sid){
        $this->db->select('sid');
        $this->db->where('clock_in_sid', $clock_in_sid);
        return $this->db->count_all_results('attendance');
    }
    
    function verify_manual_entry($company_sid, $employer_sid, $new_datetime, $attendance_type){
        $date_object                                                            = new DateTime($new_datetime);                        
        $current_month                                                          = $date_object->format('m'); 
        $date_object->modify('first day of previous month');                        
        $current_year                                                           = $date_object->format('Y'); 
        $previous_month                                                         = $date_object->format('m'); 
        $entry_date                                                             = date('Y-m-d', strtotime($new_datetime));
        $from_date                                                              = $entry_date . ' 00:00:00';
        $to_date                                                                = $entry_date . ' 23:59:59';
        $return_data                                                            = array();
        
        // we need to verify few things before manual entry.
        // 1) check clocked status for the type of entry is required.
        // 2) if clocked status is already set for the employee then no need to do second entry
        // 3) if it is break or clockout then we need to verify that it is against any clocked in sid
        // 4) get clocked in sid for the entry
        
        // $this->db->where('month(created)', date('m'));
        
        switch ($attendance_type){
            case 'break_start':
            case 'break_end':
            case 'clock_out':
                $attendance_status = 'break_hours';
                $this->db->select('sid, clock_in_sid, attendance_date, last_attendance_sid');
                $this->db->where('company_sid', $company_sid);
                $this->db->where('employer_sid', $employer_sid);
                $this->db->where('attendance_type', 'clock_in');
                //$this->db->where('month(attendance_date)', date($current_month));
                //$this->db->where('year(attendance_date)', date($current_year));
                $this->db->where('attendance_date BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
                $this->db->order_by('attendance_date', 'DESC');
                $record = $this->db->get('attendance');
                $record_array = $record->result_array();
                $record->free_result();
                //echo $this->db->last_query();
                $newDate                                                        = array();
                $manual_date                                                    = date_create($new_datetime);
                $manual_date_time                                               = strtotime($new_datetime);
                if(!empty($record_array)){
                    for($i=0; $i<count($record_array); $i++){
                        $clock_in_datatime = $record_array[$i]['attendance_date'];
                        $clock_in_datatime_time                                 = strtotime($clock_in_datatime);
                        $diff = $clock_in_datatime_time - $manual_date_time;
                        if($diff > 0){
                            $resultArrFuture[$diff] = $clock_in_datatime;
                        } else {
                            //$diff = abs($diff);
                            $resultArrPast[$diff] = $clock_in_datatime;
                        }
                        
                        $baseDate = date_create($clock_in_datatime); // verify the break that manager wants to add is correct in terms of login status.
                        $interval = date_diff($baseDate, $manual_date);

                        $key = $interval->format('%s');
                        if(key_exists($key, $newDate)){
                            $key+1;
                        }

                        $newDate[$key] = array   (   'clock_in_sid' => $record_array[$i]['sid'],
                                                                        'datetime' =>$clock_in_datatime
                                                                    );
                    }
                }
                
                if($attendance_type == 'clock_out'){
                    $attendance_status = 'working_hours';
                }
                ksort($newDate);
                @ksort($resultArrFuture);
                @krsort($resultArrPast);
                foreach($newDate as $value){
                    $clock_in_sid                                               = $value['clock_in_sid'];
                    $this->db->select('sid, clock_in_sid, last_attendance_sid, attendance_type, is_manual, attendance_date, company_sid, employer_sid, attendance_year, attendance_week');
                    $this->db->where('clock_in_sid', $clock_in_sid);
                    $this->db->where('attendance_status', $attendance_status);
                    $this->db->order_by('attendance_date', 'DESC');
                    $record                                                     = $this->db->get('attendance');
                    $record_array                                               = $record->result_array();
                    $record->free_result();
                    $return_data = array(
                                            'clock_in_sid'                      => $clock_in_sid,
                                            'datatime'                          => $value['datetime'],
                                            'all_records'                       => $record_array
                                        );
                    break; // break the foreach loop
                }
            break; 
        }
         
        return $return_data;
    }

    // ----------------------------------------AS of 2022-------------------------------------- //
    
    /**
     * Get the attendance id
     * 
     * @param number $companyId
     * @param number $employeeId
     * @param string $date
     * 
     * @return number
     */
    public function GetAttendanceId($companyId, $employeeId, $date){
        //
        $q = $this->db
        ->select('sid')
        ->where([
            'company_sid' => $companyId,
            'employee_sid' => $employeeId,
            'action_date' => $date
        ])
        ->limit(1);
        //
        $result = $q->get('portal_attendance');
        //
        $data = $result->row_array();
        //
        $result = $result->free_result();
        //
        if(empty($data)){
            return 0;
        }
        //
        return $data['sid'];
        
    }

    /**
     * Get the attendance list
     * 
     * @param number $companyId
     * @param number $employeeId
     * @param string $date
     * 
     * @return array
     */
    public function GetAttendanceList($companyId, $employeeId, $date){
        //
        $Id = $this->GetAttendanceId($companyId, $employeeId, $date);
        //
        if($Id === 0){
            return [];
        }
        //
        $q = $this->db
        ->where([
            'portal_attendance_sid' => $Id
        ])
        ->order_by('sid', 'desc');
        //
        $result = $q->get('portal_attendance_log');
        //
        $data = $result->result_array();
        //
        $result = $result->free_result();
        //
        return $data;
    }

    /**
     * Mark the attendance
     * 
     * @param number $companyId
     * @param number $employeeId
     * @param number $employerId
     * @param string $date
     * @param string $datetime
     * @param string $day
     * @param string $month
     * @param string $year
     * @param string $action
     * @param number $lat
     * @param number $lon
     * 
     * @return number
     */
    public function MarkAttendance(
        $companyId,
        $employeeId,
        $employerId,
        $date,
        $datetime,
        $day,
        $month,
        $year,
        $action,
        $latitude = 0,
        $longitude = 0,
        $added_by
    ){
        //
        $Id = $this->GetAttendanceId($companyId, $employeeId, $date);
        // Insert if attendance is not found
        if($Id === 0){
            //
            $this->db->insert(
                'portal_attendance', [
                    'company_sid' => $companyId,
                    'employee_sid' => $employeeId,
                    'employer_sid' => $employerId,
                    'day' => $day,
                    'month' => $month,
                    'year' => $year,
                    'total_minutes' => 0,
                    'total_worked_minutes' => 0,
                    'total_break_minutes' => 0,
                    'action_date' => $date,
                    'created_at' => $this->datetime,
                    'updated_at' => $this->datetime,
                ]
            );
            //
            $Id = $this->db->insert_id();
        }
        // Let's check if the data is inserted or not
        if(!$Id){
            return 0;
        }
        // Let's add the attendance log
        $this->db->insert(
            'portal_attendance_log', [
                'portal_attendance_sid' => $Id,
                'action' => $action,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'action_date_time' => $datetime,
                'is_deleted' => 0,
                'created_at' => $this->datetime,
                'updated_at' => $this->datetime,
                'added_by' => $added_by
            ]
        );
        //
        $lastId = $this->db->insert_id();
        //
        $attendanceList = $this->GetAttendanceListByID($Id);
        // 
        if(!empty($attendanceList)){
            //
            $ct = CalculateTime($attendanceList, $attendanceInfo["employee_sid"]);
            //
            $this->db->update(
                'portal_attendance', [
                    'total_minutes' => $ct['total_minutes'],
                    'total_worked_minutes' => $ct['total_worked_minutes'],
                    'total_break_minutes' => $ct['total_break_minutes'],
                    'total_overtime_minutes' => $ct['total_overtime_minutes']
                ], [
                    'sid' => $sid
                ]
            );
            //
        }
        // Update the last record
        $this->db->update(
            'portal_attendance', [
                'last_action' => $action
            ], [
                'sid' => $Id
            ]
        );
        //
        return $lastId;
    }

    /**
     * Get the attendance between week dates
     * 
     * @param number $companyId
     * @param number $employeeId
     * @param string $fromDate
     * @param string $toDate
     * 
     * @return array
     */
    public function GetAttendanceWeekList(
        $companyId,
        $employeeId,
        $fromDate,
        $toDate
    ){
        //
        $ra = [
            'total_minutes' => 0,
            'total_worked_minutes' => 0,
            'total_break_minutes' => 0,
            'total_overtime_minutes' => 0,
            'lists' => []
        ];
        //
        $currentDate = $fromDate;
        //
        while($currentDate <= $toDate){
            //
            $lists = $this->GetAttendanceList($companyId, $employeeId, $currentDate);
            //
            if(!empty($lists)){
                //
                $ct = CalculateTime($lists, $employeeId);
                //
                $ct['pId'] = $lists[0]['portal_attendance_sid'];
                //
                $ra['lists'][$currentDate] = $ct;
                //
                $ra['total_minutes'] += $ct['total_minutes'];
                $ra['total_worked_minutes'] += $ct['total_worked_minutes'];
                $ra['total_break_minutes'] += $ct['total_break_minutes'];
                $ra['total_overtime_minutes'] += $ct['total_overtime_minutes'];
            }
            //
            $currentDate = ModifyDate($currentDate, '+1 day', 'Y-m-d');
        }
        //
        return $ra;
    }

    /**
     * Get the attendance id
     * 
     * @param number $companyId
     * @param number $id
     * 
     * @return number
     */
    public function VerifyAttendanceById($companyId, $id){
        //
        $q = $this->db
        ->select('employee_sid')
        ->where([
            'company_sid' => $companyId,
            'sid' => $id
        ])
        ->limit(1);
        //
        $result = $q->get('portal_attendance');
        //
        $data = $result->row_array();
        //
        $result = $result->free_result();
        //
        if(empty($data)){
            return 0;
        }
        //
        return $data;
        
    }

    /**
     * Get the attendance list by id
     * 
     * @param number $Id
     * 
     * @return array
     */
    public function GetAttendanceListById($Id){
        //
        $q = $this->db
        ->where([
            'portal_attendance_sid' => $Id
        ])
        ->order_by('sid', 'desc');
        //
        $result = $q->get('portal_attendance_log');
        //
        $data = $result->result_array();
        //
        $result = $result->free_result();
        //
        return $data;
    }

    /**
     * Get the attendance date by id
     * 
     * @param number $Id
     * 
     * @return array
     */
    public function GetAttendanceDateAndStatusById($sid){
        //
        $q = $this->db
        ->select('company_sid, employee_sid, action_date, last_action')
        ->where([
            'sid' => $sid
        ])
        ->limit(1);
        //
        $result = $q->get('portal_attendance');
        //
        $data = $result->row_array();
        //
        $result = $result->free_result();
        //
        if(empty($data)){
            return 0;
        }
        //
        return $data;
    }

    /**
     * Get the attendance id
     * 
     * @param number $Id
     * 
     * @return array
     */
    public function GetAttendanceIDByListId($sid){
        //
        $q = $this->db
        ->select('portal_attendance_sid')
        ->where([
            'sid' => $sid
        ])
        ->limit(1);
        //
        $result = $q->get('portal_attendance_log');
        //
        $data = $result->row_array();
        //
        $result = $result->free_result();
        //
        if(empty($data)){
            return 0;
        }
        //
        return $data["portal_attendance_sid"];
    }


    public function UpdateEmployeeTimeSlot($sid, $time, $added_by)
    {
        $this->db->where('sid', $sid);
        $data_to_update = array();
        $data_to_update['action_date_time'] = $time;
        $data_to_update['added_by'] = $added_by;
        $data_to_update['updated_at'] = $this->datetime;
        $this->db->update('portal_attendance_log', $data_to_update);
    }
}