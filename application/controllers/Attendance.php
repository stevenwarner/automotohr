<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('attendance_model');
        $this->form_validation->set_error_delimiters('<span class="error_message text-left"><i class="fa fa-exclamation-circle"></i>', '</span>');

        if ($this->session->userdata('logged_in')) { //Ensure portal_detail is set in Session otherwise time calculations will mess up
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];


            $portal_info = $this->attendance_model->get_portal_information($company_sid);
            $data['session']['portal_detail'] = $portal_info;

            
        }
    }

    public function index($year = null, $month = null) {
        if ($this->session->userdata('logged_in')) {
            if ($this->form_validation->run() == false) {
                $data['session']                                                = $this->session->userdata('logged_in');
                $security_sid                                                   = $data['session']['employer_detail']['sid'];
                $security_details                                               = db_get_access_level_details($security_sid);
                $data['security_details']                                       = $security_details;
                check_access_permissions($security_details, 'dashboard', 'attendance_management');
                $company_sid                                                    = $data["session"]["company_detail"]["sid"];
                $employer_sid                                                   = $data["session"]["employer_detail"]["sid"];
                $data['title']                                                  = "Time & Attendance";
                $year                                                           = ($year == null ? date('Y') : $year);
                $month                                                          = ($month == null ? date('m') : $month);
                $start_unix_date                                                = mktime(0, 0, 0, $month, 1, $year);
                $end_unix_date                                                  = mktime(23, 59, 59, $month, date('t', $start_unix_date), $year);
                $start_date                                                     = date('Y-m-d H:i:s', $start_unix_date);
                $end_date                                                       = date('Y-m-d H:i:s', $end_unix_date);
                $attendance_records                                             = $this->attendance_model->get_all_attendance_total_records($company_sid, $employer_sid, $start_date, $end_date);
                /*
                    [sid] => 2
                    [attendance_date] => 2017-04-25
                    [company_sid] => 3
                    [employer_sid] => 4
                    [date_created] => 2017-04-27 17:41:46
                    [daily_time_quota_hours] => 8
                    [daily_break_quota_hours] => 1
                    [total_clocked_hours] => 50
                    [total_clocked_minutes] => 25
                    [total_break_hours] => 0
                    [total_break_minutes] => 4
                    [total_after_break_hours] => 50
                    [total_after_break_minutes] => 21
                    [total_over_quota_hours] => 42
                    [total_over_quota_minutes] => 21
                    [over_quota_break_hours] => 0
                    [over_quota_break_minutes] => 0
                    [status] => 1
                    [profile_picture] => applican-img-w3R4P3.jpg
                    [first_name] => Syed
                    [last_name] => Hassan
                 * 
                 * perform_all_calculations
                 *                  */
                
                $grand_totals                                                   = $this->calculate_grand_totals($attendance_records);
                $data['grand_totals']                                           = $grand_totals;
                $data['attendance_records']                                     = $attendance_records;
                $months                                                         = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                $data['subtitle']                                               = "My Attendance For the Month of " . $months[intval($month)] . ", " . $year;
                $this->load->view('main/header', $data);
                $this->load->view('attendance/index');
                $this->load->view('main/footer');
            } 
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function my_day($year = null, $month = null, $day = null) {
        if ($this->session->userdata('logged_in')) {
            $this->form_validation->set_rules('perform_action', 'perform_action', 'xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $data['session']                                                = $this->session->userdata('logged_in');
                $security_sid                                                   = $data['session']['employer_detail']['sid'];
                $security_details                                               = db_get_access_level_details($security_sid);
                $data['security_details']                                       = $security_details;
                check_access_permissions($security_details, 'attendance', 'my_day');
                
                $company_sid                                                    = $data['session']['company_detail']['sid'];
                $employer_sid                                                   = $data['session']['employer_detail']['sid'];
                $data                                                           = employee_right_nav($employer_sid);
                $data['employer']                                               = $data['session']['employer_detail'];
                $data['title']                                                  = "Time & Attendance";
                $data['subtitle']                                               = "Clock My Day";
                $company_timezone                                               = $data['session']['portal_detail']['company_timezone'];
                $data['company_timezone']                                       = $company_timezone;

                date_default_timezone_set($company_timezone); // to set timezone for next date request. It will be applicable only for one time
                $today                                                          = date('Y-m-d 00:00:00');
                $today_unix                                                     = strtotime($today);
                date_default_timezone_set($company_timezone);
                $year                                                           = ($year == null ? date('Y') : $year);
                date_default_timezone_set($company_timezone);
                $month                                                          = ($month == null ? date('m') : $month);
                date_default_timezone_set($company_timezone);
                $day                                                            = ($day == null ? date('d') : $day);
                $start_unix_date                                                = mktime(0, 0, 0, $month, $day, $year);
                $end_unix_date                                                  = mktime(23, 59, 59, $month, $day, $year);
                date_default_timezone_set($company_timezone);
                $start_date                                                     = date('Y-m-d H:i:s', $start_unix_date);
                date_default_timezone_set($company_timezone);
                $end_date                                                       = date('Y-m-d H:i:s', $end_unix_date);

                //$last_clock_in_data = $this->attendance_model->get_last_attendance_record($company_sid, $employer_sid, '', '', 'clock_in');
                $last_clock_in_data                                             = $this->attendance_model->get_last_clock_in($company_sid, $employer_sid);
                $last_clock_out_data                                            = $this->attendance_model->get_last_clock_out($company_sid, $employer_sid);
                $attendance_records                                             = array();
                $break_records                                                  = array();
                $working_records                                                = array();
                $last_break_record                                              = array();
                $last_working_record                                            = array();
                $current_attendance_type                                        = 'clock_in';
                $current_break_type                                             = 'break_start';
                
                if (!empty($last_clock_in_data)) {
                    $last_clock_in_sid                                          = $last_clock_in_data['sid'];
                    $attendance_records                                         = $this->attendance_model->get_attendance_records_by_clock_in_sid($last_clock_in_sid);
                    $break_records                                              = $this->filter_out_array_based_on_value($attendance_records, 'attendance_status', 'break_hours');
//                    echo "<pre>";
//                    print_r($break_records);
//                    echo "</pre>";
                    if (count($break_records) > 0) {
                        $last_break_record                                      = $break_records[count($break_records) - 1];
                    }
                    
                    $working_records = $this->filter_out_array_based_on_value($attendance_records, 'attendance_status', 'working_hours');
//                    echo "<pre> WORKING Hours";
//                    print_r($working_records);
//                    echo "</pre>";
                    if (count($working_records) > 0) {
                        $last_working_record                                    = $working_records[count($working_records) - 1];
                    }

                    if (!empty($last_break_record)) {
                        $last_break_type                                        = $last_break_record['attendance_type'];
                    } else {
                        $last_break_type                                        = 'break_end';
                    }

                    if (!empty($last_working_record)) {
                        $last_working_type                                      = $last_working_record['attendance_type'];
                    } else {
                        $last_working_type                                      = 'clock_out';
                    }

                    if ($last_break_type == 'break_start') {
                        $current_break_type                                     = 'break_end';
                        $show_break_start_button                                = false;
                        $show_break_end_button                                  = true;
                    } else {
                        $current_break_type                                     = 'break_start';
                        $show_break_start_button                                = true;
                        $show_break_end_button                                  = false;
                    }

                    if ($last_working_type == 'clock_in') {
                        $current_attendance_type                                = 'clock_out';
                        $show_clock_in_button                                   = false;
                        $show_clock_out_button                                  = true;
                    } else {
                        $current_attendance_type                                = 'clock_in';
                        $show_clock_in_button                                   = true;
                        $show_clock_out_button                                  = false;
                    }
                } else {
                    $show_clock_in_button                                       = true;
                    $show_clock_out_button                                      = false;
                    $show_break_start_button                                    = false;
                    $show_break_end_button                                      = false;
                }

                $data['current_attendance_type']                                = $current_attendance_type;
                $data['current_break_type']                                     = $current_break_type;
                $data['last_break_record']                                      = $last_break_record;
                $data['last_working_record']                                    = $last_working_record;
                $data['company_sid']                                            = $company_sid;
                $data['employer_sid']                                           = $employer_sid;
                $location_info                                                  = file_get_contents('http://freegeoip.net/json/' . getUserIP());
                $data['location_info']                                          = $location_info;
                $data['show_clock_in_button']                                   = $show_clock_in_button;
                $data['show_clock_out_button']                                  = $show_clock_out_button;
                $data['show_break_start_button']                                = $show_break_start_button;
                $data['show_break_end_button']                                  = $show_break_end_button;

                if ($show_clock_out_button == true) {
                    if (!empty($last_clock_in_data)) {
                        $last_clock_in_for_clock                                = $last_clock_in_data['attendance_date'];
                        $data['last_clock_in_for_clock']                        = $last_clock_in_for_clock;
                    }
                }

                if ($show_break_end_button == true) {
                    if (!empty($last_break_record)) {
                        $data['last_break_start_for_clock']                     = $last_break_record['attendance_date'];
                    }
                }

                //Quota Management
                $weekly_hours_quota                                             = 40;
                $data['weekly_hours_quota']                                     = $weekly_hours_quota;
                $weekly_minutes_quota                                           = $weekly_hours_quota * 60;
                $daily_minutes_quota                                            = $weekly_minutes_quota / 5;
                $data['daily_minutes_quota']                                    = $daily_minutes_quota;
                $data['daily_hours_quota']                                      = floor($daily_minutes_quota / 60);
                $break_hours_quota                                              = 1;
                $data['break_hours_quota']                                      = $break_hours_quota;
                $data['break_minutes_quota']                                    = $break_hours_quota * 60;

                $last_clock_in_was_today                                        = false;
                $last_clock_out_was_today                                       = false;
                $last_clock_out_was_before_last_clock_in                        = false;

                if (!empty($last_clock_in_data)) {
                    if ($today_unix < strtotime($last_clock_in_data['attendance_date'])) { //last clock in was today
                            $last_clock_in_was_today = true;
                    }
                }

                if (!empty($last_clock_out_data)) {
                    if ($today_unix < strtotime($last_clock_out_data['attendance_date'])) {  //last clock out was today
                            $last_clock_out_was_today = true;
                    }
                }


                if (!empty($last_clock_in_data) && !empty($last_clock_out_data)) {
                    if (strtotime($last_clock_out_data['attendance_date']) < strtotime($last_clock_in_data['attendance_date'])) {
                        $last_clock_out_was_before_last_clock_in = true;
                    }
                }

                if ($last_clock_in_was_today == true &&
                        $last_clock_out_was_today == true &&
                        $last_clock_out_was_before_last_clock_in == false) {

                    date_default_timezone_set($company_timezone);
                    $start_date                                                 = date('Y-m-d 00:00:00');

                    date_default_timezone_set($company_timezone);
                    $end_date                                                   = date('Y-m-d 23:59:59');
                } else if ($last_clock_in_was_today == false) {
                    if ($last_clock_out_was_before_last_clock_in == true) {
                        $start_date                                             = $last_clock_in_data['attendance_date'];
                        date_default_timezone_set($company_timezone);
                        $end_date                                               = date('Y-m-d H:i:s');
                    }
                }
                
                $todays_attendance                                              = $this->attendance_model->get_attendance_records($company_sid, $employer_sid, $start_date, $end_date);
                $data['todays_logs']                                            = $todays_attendance;
            
                $clock_in_records                                               = $this->filter_out_array_based_on_value($todays_attendance, 'attendance_type', 'clock_in'); //All Time Calculations
                $total_calculation_records                                      = array();
                
                foreach ($clock_in_records as $clock_in_record) {
                    $clock_in_sid                                               = $clock_in_record['sid'];
                    $calculated_results                                         = $this->perform_all_calculations($company_sid, $employer_sid, $clock_in_sid);
                    $total_calculation_records[]                                = $calculated_results;
                }

                //Calculate Grand Total for Today
                $total_clocked_unix                                             = 0;
                $todays_total_clocked_hours                                     = 0;
                $todays_total_clocked_minutes                                   = 0;
                $total_break_unix                                               = 0;
                $todays_total_break_hours                                       = 0;
                $todays_total_break_minutes                                     = 0;
                $todays_total_after_break_hours                                 = 0;
                $todays_total_after_break_minutes                               = 0;
                $todays_total_over_quota_hours                                  = 0;
                $todays_total_over_quota_minutes                                = 0;
                $todays_over_quota_break_hours                                  = 0;
                $todays_over_quota_break_minutes                                = 0;

                foreach ($total_calculation_records as $calculation_record) {
                    $total_clocked_unix                                         = $total_clocked_unix + $calculation_record['clocked_after_break_time_unix'];
                    $total_break_unix                                           = $total_break_unix + $calculation_record['break_time_unix'];
                }
                
                $clocked_time                                                   = $this->convert_unix_seconds_to_hours_minutes($total_clocked_unix); //Total Clocked Time - Start
                $todays_total_clocked_hours                                     = $clocked_time['hours'];
                $todays_total_clocked_minutes                                   = $clocked_time['minutes'];

                $data['total_clocked_hours_today']                              = $todays_total_clocked_hours;
                $data['total_clocked_minutes_today']                            = $todays_total_clocked_minutes;
                $daily_time_quota_minutes                                       = 8 * 60;
                $todays_clocked_percentage                                      = floor(((($todays_total_clocked_hours * 60) + $todays_total_clocked_minutes) / $daily_time_quota_minutes) * 100);
                $data['todays_clocked_percentage']                              = $todays_clocked_percentage;
                //Total Clocked Time - End
                //Total Break Time - Start
                $break_time                                                     = $this->convert_unix_seconds_to_hours_minutes($total_break_unix);
                $todays_total_break_hours                                       = $break_time['hours'];
                $todays_total_break_minutes                                     = $break_time['minutes'];
                $data['total_break_hours']                                      = $todays_total_break_hours;
                $data['total_break_minutes']                                    = $todays_total_break_minutes;
                $daily_break_quota_minutes                                      = 1 * 60;
                $break_percentage                                               = ((($todays_total_break_hours * 60) + $todays_total_break_minutes) / $daily_break_quota_minutes) * 100;
                $data['break_percentage']                                       = $break_percentage;
                //Total Break Time - End
                //Get Week Start End Dates
                $today                                                          = new DateTime();
                $currentWeekDay                                                 = $today->format('w'); // Weekday as a number (0 = Sunday, 6 = Saturday)
                $firstdayofweek                                                 = clone $today;
                $lastdayofweek                                                  = clone $today;

                if ($currentWeekDay !== '0') {
                    $firstdayofweek->modify('last sunday');
                }

                if ($currentWeekDay !== '6') {
                    $lastdayofweek->modify('next saturday');
                }

                $my_week_start                                                  = $firstdayofweek->format('Y-m-d 00:00:00');
                $my_week_end                                                    = $lastdayofweek->format('Y-m-d 23:59:59');
                $weeks_attendance_records                                       = $this->attendance_model->get_attendance_records($company_sid, $employer_sid, $my_week_start, $my_week_end);
                $weeks_clock_in_records                                         = $this->filter_out_array_based_on_value($weeks_attendance_records, 'attendance_type', 'clock_in');
                $weeks_total_calculation_records                                = array();
                
                foreach ($weeks_clock_in_records as $weeks_clock_in_record) {
                    $clock_in_sid = $weeks_clock_in_record['sid'];
                    $calculated_results = $this->perform_all_calculations($company_sid, $employer_sid, $clock_in_sid);
                    $weeks_total_calculation_records[] = $calculated_results;
                }

                $total_weeks_clocked_unix = 0;
                foreach ($weeks_total_calculation_records as $calculation_record) {
                    $total_weeks_clocked_unix = $total_weeks_clocked_unix + $calculation_record['clocked_after_break_time_unix'];
                }

                $weeks_clocked_time = $this->convert_unix_seconds_to_hours_minutes($total_weeks_clocked_unix);
                $weeks_total_clocked_hours = $weeks_clocked_time['hours'];
                $weeks_total_clocked_minutes = $weeks_clocked_time['minutes'];
                $data['weeks_total_clocked_hours'] = $weeks_total_clocked_hours;
                $data['weeks_total_clocked_minutes'] = $weeks_total_clocked_minutes;
                $weekly_hours_quota_minutes = 40 * 60;
                $weeks_percentage = floor(((($weeks_total_clocked_hours * 60) + $weeks_total_clocked_minutes) / $weekly_hours_quota_minutes) * 100);
                $data['weeks_percentage'] = $weeks_percentage;
                date_default_timezone_set($company_timezone);
                $data['today_date'] = date('d');
                $data['today_month'] = date('m');
                $data['today_year'] = date('y');
                $data['current_date'] = date('Y-m-d H:i:s');
                date_default_timezone_set($company_timezone);
                $date_string = date('Y-m-d H:i:s');
                $date_array = explode(' ', $date_string);
                $date = $date_array[0];
                $time = $date_array[1];
                $date = explode('-', $date);
                $time = explode(':', $time);
                $data['day'] = $date[2];
                $data['month'] = $date[1];
                $data['year'] = $date[0];
                $data['hours'] = $time['0'];
                $data['minutes'] = $time['1'];
                $data['seconds'] = $time['2'];
                $data['company_timezone'] = $company_timezone;
                $data["return_title_heading"] = "My Profile";
                $data["return_title_heading_link"] = base_url('my_profile');
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'My Day';
                $reload_location = 'my_profile';
                $cancel_url = base_url() . 'my_profile/';
                $data['left_navigation'] = $left_navigation;
                $data['applicant_average_rating'] = $this->attendance_model->getApplicantAverageRating($employer_sid, 'employee');

                if ($current_attendance_type == 'clock_in') {
                    $clock_in_sid = 0;
                } else {
                    $clock_in_sid = $last_clock_in_data['sid'];
                }

                $data['clock_in_sid'] = $clock_in_sid;
                //$this->insert_attendance_total_record($company_sid, $employer_sid, date('Y-m-d'));
                //load views
                $this->load->view('main/header', $data);
                $this->load->view('attendance/my_day');
                $this->load->view('main/footer');
            } else {
                //Set Quotas
                $daily_time_quota_hours = 8;
                $daily_time_quota_minutes = $daily_time_quota_hours * 60;
                $daily_break_quota_hours = 1;
                $daily_break_quota_minutes = $daily_break_quota_hours * 60;
                //$daily_time_quota_minutes = $daily_time_quota_minutes + $daily_break_quota_minutes;
                $data['session'] = $this->session->userdata('logged_in');
                $company_timezone = $data['session']['portal_detail']['company_timezone'];
                $perform_action = $this->input->post('perform_action');
                $clock_in_sid = $this->input->post('clock_in_sid');

                if ($clock_in_sid > 0) {
                    $attendance_records = $this->attendance_model->get_attendance_records_by_clock_in_sid($clock_in_sid);
                    $clock_in_data = $attendance_records[0];
                    $all_break_records = $this->filter_out_array_based_on_value($attendance_records, 'attendance_status', 'break_hours');
                    $all_working_records = $this->filter_out_array_based_on_value($attendance_records, 'attendance_status', 'working_hours');
                    $last_break_record = array();
                    
                    if (count($all_break_records) > 0) {
                        $last_break_record = $all_break_records[count($all_break_records) - 1];
                    }

                    $last_working_record = array();
                    
                    if (count($all_working_records) > 0) {
                        $last_working_record = $all_working_records[count($all_working_records) - 1];
                    }

                    $last_attendance_record = array();
                    
                    if (count($attendance_records) > 0) {
                        $last_attendance_record = $attendance_records[count($attendance_records) - 1];
                    }
                } else {
                    $last_break_record = array();
                    $last_working_record = array();
                    $last_attendance_record = array();
                }

                $applicant_performed_action = false;
                
                switch ($perform_action) {
                    case 'mark_attendance':
                        $applicant_performed_action = true;
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $latitude = $this->input->post('latitude');
                        $longitude = $this->input->post('longitude');
                        $current_attendance_type = $this->input->post('current_attendance_type');
                        $clock_in_sid = $this->input->post('clock_in_sid');
                        date_default_timezone_set($company_timezone);
                        $status_date = date('Y-m-d H:i:s');
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['employer_sid'] = $employer_sid;
                        $data_to_insert['attendance_type'] = $current_attendance_type;
                        $data_to_insert['attendance_date'] = $status_date;
                        $data_to_insert['ip_address'] = getUserIP();
                        $data_to_insert['latitude'] = $latitude;
                        $data_to_insert['longitude'] = $longitude;
                        $data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $data_to_insert['attendance_year'] = date('Y');
                        $data_to_insert['attendance_week'] = date('W');
                        $data_to_insert['timezone'] = $company_timezone;
                        $data_to_insert['attendance_status'] = 'working_hours';
                        $data_to_insert['clock_in_sid'] = $clock_in_sid;
                        $mark_attendance = true;

                        if (!empty($last_attendance_record)) {
                            $last_attendance_status = $last_attendance_record['attendance_type'];

                            if ($last_attendance_status != $current_attendance_type) {
                                if ($last_attendance_status == 'break_start') {
                                    //End Break First
                                    $break_data_to_insert = array();
                                    $break_data_to_insert['company_sid'] = $company_sid;
                                    $break_data_to_insert['employer_sid'] = $employer_sid;
                                    $break_data_to_insert['attendance_type'] = 'break_end';
                                    $break_data_to_insert['attendance_date'] = date('Y-m-d H:i:s', strtotime($status_date) - 10);
                                    $break_data_to_insert['ip_address'] = getUserIP();
                                    $break_data_to_insert['latitude'] = $latitude;
                                    $break_data_to_insert['longitude'] = $longitude;
                                    $break_data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                                    $break_data_to_insert['attendance_year'] = date('Y');
                                    $break_data_to_insert['attendance_week'] = date('W');
                                    $break_data_to_insert['timezone'] = $company_timezone;
                                    $break_data_to_insert['attendance_status'] = 'break_hours';
                                    $break_data_to_insert['clock_in_sid'] = $clock_in_sid;
                                    $break_data_to_insert['last_attendance_sid'] = ((!empty($last_attendance_record) && $last_attendance_record['attendance_type'] == 'break_start') ? $last_attendance_record['sid'] : 0);
                                    $this->attendance_model->insert_attendance_record($break_data_to_insert);
                                }

                                if ($mark_attendance == true) {
                                    $this->attendance_model->insert_attendance_record($data_to_insert);

                                    if ($current_attendance_type == 'clock_in') {
                                        $this->session->set_flashdata('message', '<strong>Success</strong> You Have Successfully Clocked In!');
                                    } else {
                                        $clock_in_data = $this->attendance_model->get_attendance_record($company_sid, $employer_sid, $clock_in_sid);
                                        $this->insert_attendance_total_record($company_sid, $employer_sid, date('Y-m-d', strtotime($clock_in_data['attendance_date'])));
                                        $this->session->set_flashdata('message', '<strong>Success</strong> You Have Successfully Clocked Out!');
                                    }
                                } else {
                                    $this->session->set_flashdata('message', '<strong>Error</strong> Invalid Request!');
                                }
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error</strong> Invalid Request!');
                            }
                        } else {
                            $this->attendance_model->insert_attendance_record($data_to_insert);

                            if ($current_attendance_type == 'clock_in') {
                                $this->session->set_flashdata('message', '<strong>Success</strong> You Have Successfully Clocked In!');
                            } else {
                                $clock_in_data = $this->attendance_model->get_attendance_record($company_sid, $employer_sid, $clock_in_sid);
                                $this->insert_attendance_total_record($company_sid, $employer_sid, date('Y-m-d', strtotime($clock_in_data['attendance_date'])));
                                $this->session->set_flashdata('message', '<strong>Success</strong> You Have Successfully Clocked Out!');
                            }
                        }
                        break;
                    case 'mark_break':
                        $applicant_performed_action = true;
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $latitude = $this->input->post('latitude');
                        $longitude = $this->input->post('longitude');
                        $current_break_type = $this->input->post('current_break_type');
                        $clock_in_sid = $this->input->post('clock_in_sid');
                        date_default_timezone_set($company_timezone);
                        $status_date = date('Y-m-d H:i:s');

                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['employer_sid'] = $employer_sid;
                        $data_to_insert['attendance_type'] = $current_break_type;
                        $data_to_insert['attendance_date'] = $status_date;
                        $data_to_insert['ip_address'] = getUserIP();
                        $data_to_insert['latitude'] = $latitude;
                        $data_to_insert['longitude'] = $longitude;
                        $data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $data_to_insert['attendance_year'] = date('Y');
                        $data_to_insert['attendance_week'] = date('W');
                        $data_to_insert['timezone'] = $company_timezone;
                        $data_to_insert['attendance_status'] = 'break_hours';
                        $data_to_insert['clock_in_sid'] = $clock_in_sid;
                        $data_to_insert['last_attendance_sid'] = ((!empty($last_attendance_record) && $last_attendance_record['attendance_type'] == 'break_start') ? $last_attendance_record['sid'] : 0);

                        if (!empty($last_attendance_record)) {
                            $last_attendance_status = $last_attendance_record['attendance_type'];

                            if ($last_attendance_status != $current_break_type) {
                                $this->attendance_model->insert_attendance_record($data_to_insert);

                                if ($current_break_type == 'break_start') {
                                    $this->session->set_flashdata('message', '<strong>Success</strong> Break Started!');
                                } else {
                                    $this->session->set_flashdata('message', '<strong>Success</strong> Break Ended!');
                                }
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error</strong> Invalid Request!');
                            }
                        } else {
                            $this->attendance_model->insert_attendance_record($data_to_insert);

                            if ($current_break_type == 'break_start') {
                                $this->session->set_flashdata('message', '<strong>Success</strong> Break Started!');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Success</strong> Break Ended!');
                            }
                        }
                        break;
                }

                if ($applicant_performed_action) {
                    // update session *** Start ***
                    $clocked_status = $this->attendance_model->get_last_attendance_record($company_sid, $employer_sid);
                    $previous_session = $this->session->userdata('logged_in');

                    $sess_array = array();
                    $sess_array['company_detail'] = $previous_session["company_detail"];
                    $sess_array['employer_detail'] = $previous_session["employer_detail"];
                    $sess_array['cart'] = $previous_session["cart"];
                    $sess_array['portal_detail'] = $previous_session["portal_detail"];
                    $sess_array['clocked_status'] = $clocked_status;

                    if (isset($previous_session['is_super']) && intval($previous_session['is_super']) == 1) {
                        $sess_array['is_super'] = 1;
                    } else {
                        $sess_array['is_super'] = 0;
                    }

                    $this->session->set_userdata('logged_in', $sess_array);
                    // update session *** End ***
                }
                redirect('attendance/my_day', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    private function perform_all_calculations($company_sid, $employer_sid, $clock_in_sid, $override_clock_out_date = null) {
        $attendance_records = $this->attendance_model->get_attendance_records_by_clock_in_sid($clock_in_sid);

        if (!empty($attendance_records)) {
            $clock_in_data = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'clock_in');
            $clock_out_data = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'clock_out');

            $all_break_start_records = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'break_start');
            $all_break_end_records = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'break_end');


            if (!empty($clock_in_data)) {
                $clock_in_data = $clock_in_data[0];
            }

            if (!empty($clock_out_data)) {
                $clock_out_data = $clock_out_data[0];
            }

            $clocked_time_unix = 0;
            if (isset($clock_in_data['attendance_date']) && isset($clock_out_data['attendance_date']) ||
                    (isset($clock_in_data['attendance_date']) && $override_clock_out_date != null)) {
                $clock_in_date = $clock_in_data['attendance_date'];
                $clock_out_date = is_null($override_clock_out_date) ? $clock_out_data['attendance_date'] : $override_clock_out_date;

                $clock_in_unix = strtotime($clock_in_date);
                $clock_out_unix = strtotime($clock_out_date);

                $clocked_time_unix = $clock_out_unix - $clock_in_unix;
            }

            //Calculate Break

            $total_break_time_unix = 0;

            if (!empty($all_break_end_records)) {
                foreach ($all_break_end_records as $break_end_record) {
                    $break_start_sid = $break_end_record['last_attendance_sid'];

                    $break_start_record = $this->filter_out_array_based_on_value($attendance_records, 'sid', $break_start_sid);

                    $break_time_unix = 0;
                    if (!empty($break_start_record)) {
                        $break_start_record = $break_start_record[0];

                        if (isset($break_start_record['attendance_date']) && isset($break_end_record['attendance_date'])) {
                            $break_start_date = $break_start_record['attendance_date'];
                            $break_end_date = $break_end_record['attendance_date'];

                            $break_start_unix = strtotime($break_start_date);
                            $break_end_unix = strtotime($break_end_date);

                            $break_time_unix = $break_end_unix - $break_start_unix;
                        }
                    }
                    $total_break_time_unix = $total_break_time_unix + $break_time_unix;
                }
            } else {
                if (!empty($all_break_start_records)) {
                    $last_break_start_record = $all_break_start_records[count($all_break_start_records) - 1];

                    $break_time_unix = 0;

                    if (!empty($last_break_start_record)) {
                        if (isset($last_break_start_record['attendance_date']) && !is_null($override_clock_out_date)) {

                            $break_start_date = $last_break_start_record['attendance_date'];

                            $break_start_unix = strtotime($break_start_date);
                            $break_end_unix = strtotime($override_clock_out_date);

                            $break_time_unix = $break_end_unix - $break_start_unix;
                        }
                    }
                    $total_break_time_unix = $total_break_time_unix + $break_time_unix;
                }
            }


            $daily_work_quota_hours = 8;
            $daily_break_quota_hours = 1;

            $daily_work_quota_unix = $daily_work_quota_hours * 60 * 60;
            $daily_break_quota_unix = $daily_break_quota_hours * 60 * 60;

            $total_after_break_unix = $clocked_time_unix - $total_break_time_unix;

            $total_over_quota_time_unix = 0;

            if ($total_after_break_unix > $daily_work_quota_unix) {
                $total_over_quota_time_unix = $total_after_break_unix - $daily_work_quota_unix;
            }

            $total_over_quota_break_unix = 0;
            if ($total_break_time_unix > $daily_break_quota_unix) {
                $total_over_quota_break_unix = $total_break_time_unix - $daily_break_quota_unix;
            }

            $clocked_time = $this->convert_unix_seconds_to_hours_minutes($clocked_time_unix);
            $clocked_after_break_time = $this->convert_unix_seconds_to_hours_minutes($total_after_break_unix);
            $break_time = $this->convert_unix_seconds_to_hours_minutes($total_break_time_unix);
            $over_quota_time = $this->convert_unix_seconds_to_hours_minutes($total_over_quota_time_unix);
            $over_quota_break = $this->convert_unix_seconds_to_hours_minutes($total_over_quota_break_unix);


            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['employer_sid'] = $employer_sid;

            $data_to_insert['clock_in_attendance_sid'] = $clock_in_data['sid'];
            $data_to_insert['clock_out_attendance_sid'] = isset($clock_out_data['sid']) ? $clock_out_data['sid'] : 0;

            $data_to_insert['daily_time_quota_hours'] = $daily_work_quota_hours;
            $data_to_insert['daily_break_quota_hours'] = $daily_break_quota_hours;

            $data_to_insert['related_attendance_sids'] = '';

            $data_to_insert['total_clocked_hours'] = $clocked_time['hours'];
            $data_to_insert['total_clocked_minutes'] = $clocked_time['minutes'];

            $data_to_insert['total_break_hours'] = $break_time['hours'];
            $data_to_insert['total_break_minutes'] = $break_time['minutes'];

            $data_to_insert['total_after_break_hours'] = $clocked_after_break_time['hours'];
            $data_to_insert['total_after_break_minutes'] = $clocked_after_break_time['minutes'];

            $data_to_insert['total_over_quota_hours'] = $over_quota_time['hours'];
            $data_to_insert['total_over_quota_minutes'] = $over_quota_time['minutes'];

            $data_to_insert['over_quota_break_hours'] = $over_quota_break['hours'];
            $data_to_insert['over_quota_break_minutes'] = $over_quota_break['minutes'];


            $data_to_insert['clocked_time_unix'] = $clocked_time_unix;
            $data_to_insert['clocked_after_break_time_unix'] = $total_after_break_unix;
            $data_to_insert['break_time_unix'] = $total_break_time_unix;


            return $data_to_insert;
        }
    }

    private function insert_attendance_total_record($company_sid, $employer_sid, $date) {
        $daily_work_quota_hours = 8;
        $daily_break_quota_hours = 1;
        $daily_work_quota_unix = $daily_work_quota_hours * 60 * 60;
        $daily_break_quota_unix = $daily_break_quota_hours * 60 * 60;
        $start_date = date('Y-m-d 00:00:00', strtotime($date));
        $end_date = date('Y-m-d 23:59:59', strtotime($date));
        $all_clock_in_records = $this->attendance_model->get_all_clock_in_records($company_sid, $employer_sid, $start_date, $end_date);
        $all_calculation_records = array();
        
        if (!empty($all_clock_in_records)) {
            foreach ($all_clock_in_records as $clock_in_record) {
                $clock_in_sid = $clock_in_record['sid'];
                $calculation_record = $this->perform_all_calculations($company_sid, $employer_sid, $clock_in_sid);
                $all_calculation_records[] = $calculation_record;
            }
        }

        $total_clocked_unix = 0;
        $total_break_unix = 0;
        
        if (!empty($all_calculation_records)) {
            foreach ($all_calculation_records as $calculation_record) {
                $total_clocked_unix = $total_clocked_unix + $calculation_record['clocked_time_unix'];
                $total_break_unix = $total_break_unix + $calculation_record['break_time_unix'];
            }
        }

        $total_after_break_unix = $total_clocked_unix - $total_break_unix;
        $over_quota_clocked_unix = 0;
        $over_quota_break_unix = 0;

        if ($total_after_break_unix > $daily_work_quota_unix) {
            $over_quota_clocked_unix = $total_after_break_unix - $daily_work_quota_unix;
        }

        if ($total_break_unix > $daily_break_quota_unix) {
            $over_quota_break_unix = $total_break_unix - $daily_break_quota_unix;
        }

        $clocked_time = $this->convert_unix_seconds_to_hours_minutes($total_clocked_unix);
        $break_time = $this->convert_unix_seconds_to_hours_minutes($total_break_unix);
        $clocked_time_after_break = $this->convert_unix_seconds_to_hours_minutes($total_after_break_unix);
        $over_quota_clocked_time = $this->convert_unix_seconds_to_hours_minutes($over_quota_clocked_unix);
        $over_quota_break_time = $this->convert_unix_seconds_to_hours_minutes($over_quota_break_unix);
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['employer_sid'] = $employer_sid;
        $data_to_insert['attendance_date'] = date('Y-m-d', strtotime($date));
        $data_to_insert['daily_time_quota_hours'] = $daily_work_quota_hours;
        $data_to_insert['daily_break_quota_hours'] = $daily_break_quota_hours;
        $data_to_insert['total_clocked_hours'] = $clocked_time['hours'];
        $data_to_insert['total_clocked_minutes'] = $clocked_time['minutes'];
        $data_to_insert['total_break_hours'] = $break_time['hours'];
        $data_to_insert['total_break_minutes'] = $break_time['minutes'];
        $data_to_insert['total_after_break_hours'] = $clocked_time_after_break['hours'];
        $data_to_insert['total_after_break_minutes'] = $clocked_time_after_break['minutes'];
        $data_to_insert['total_over_quota_hours'] = $over_quota_clocked_time['hours'];
        $data_to_insert['total_over_quota_minutes'] = $over_quota_clocked_time['minutes'];
        $data_to_insert['over_quota_break_hours'] = $over_quota_break_time['hours'];
        $data_to_insert['over_quota_break_minutes'] = $over_quota_break_time['minutes'];
        $data_to_insert['status'] = 1;
        $this->attendance_model->set_status_for_attendance_totals_records($date, 0);
        $this->attendance_model->insert_attendance_totals_record($data_to_insert);
    }

    private function convert_unix_seconds_to_hours_minutes($unix_time) {
        $my_return = array();

        if ($unix_time > 0) {
            $hours = floor($unix_time / (60 * 60));
            $remainder = ($unix_time % (60 * 60));
            $minutes = floor($remainder / 60);

            $my_return['hours'] = $hours;
            $my_return['minutes'] = $minutes;
        } else {
            $my_return['hours'] = 0;
            $my_return['minutes'] = 0;
        }

        return $my_return;
    }

    public function my_time_sheet($start_date = null, $end_date = null) {
        if ($this->session->userdata('logged_in')) {
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $data['session']                                                = $this->session->userdata('logged_in');
                $security_sid                                                   = $data['session']['employer_detail']['sid'];
                $security_details                                               = db_get_access_level_details($security_sid);
                $data['security_details']                                       = $security_details;
                check_access_permissions($security_details, 'attendance', 'my_time_sheet');

                $company_sid                                                    = $data["session"]["company_detail"]["sid"];
                $employer_sid                                                   = $data["session"]["employer_detail"]["sid"];
                $company_timezone                                               = $data['session']['portal_detail']['company_timezone'];
                $data['company_timezone']                                       = $company_timezone;
                $data['title']                                                  = "My Time & Attendance";

                if ($start_date == null) {
                    date_default_timezone_set($company_timezone);
                    $start_date                                                 = date('Y-m-d');
                } else {
                    $start_date                                                 = str_replace('_', '-', $start_date);
                    $start_date_array                                           = explode('-', $start_date);
                    $start_date                                                 = $start_date_array[2] . '-' . $start_date_array[0] . '-' . $start_date_array[1];
                }

                if ($end_date == null) {
                    date_default_timezone_set($company_timezone);
                    $end_date                                                   = date('Y-m-d');
                } else {
                    $end_date                                                   = str_replace('_', '-', $end_date);
                    $end_date_array                                             = explode('-', $end_date);
                    $end_date                                                   = $end_date_array[2] . '-' . $end_date_array[0] . '-' . $end_date_array[1];
                }

                $data['subtitle']                                               = "Attendance Record from ".$start_date. " to ".$end_date;
                $attendance_totals                                              = $this->attendance_model->get_all_attendance_total_records($company_sid, $employer_sid, $start_date, $end_date);
                $attendance_breakout                                            = $this->attendance_breakout($attendance_totals); 
                $data['grand_totals']                                           = array();
                $data['attendance_totals']                                      = array();
                
                if(!empty($attendance_breakout)){
                    $data['attendance_totals']                                  = $attendance_breakout['attendance']; 
                    $data['grand_totals']                                       = $attendance_breakout['grand_totals']; 
                }
                
                date_default_timezone_set($company_timezone);
                $data['current_date']                                           = date('Y-m-d H:i:s');
                $data['start_date']                                             = date('m/d/Y', strtotime($start_date));
                $data['end_date']                                               = date('m/d/Y', strtotime($end_date));
                $data['company_sid']                                            = $company_sid;
                $current_attendance                                             = $this->get_last_clocked_day_data($company_sid, $employer_sid, $company_timezone);
//                echo "<pre>";
//                print_r($current_attendance);
                $current_attendance_date                                        = $current_attendance['attendance_date'];
                $current_clocked_time                                           = $this->convert_unix_seconds_to_hours_minutes($current_attendance['total_clocked_unix']);
                $current_after_break_time                                       = $this->convert_unix_seconds_to_hours_minutes($current_attendance['total_clocked_after_break_unix']);
                $current_break_time                                             = $this->convert_unix_seconds_to_hours_minutes($current_attendance['total_break_unix']);
                $current_clocked_status                                         = $current_attendance['clocked_status'];
//                echo "<hr>";
//                print_r($current_clocked_time);
//                 echo "<hr>";
//                print_r($current_after_break_time);
//                 echo "<hr>";
//                print_r($current_break_time);
//                exit;
                $total_clocked_minutes                                          = (($current_clocked_time['hours'] * 60) + ($current_clocked_time['minutes']));
                $total_clocked_minutes_after_break                              = (($current_after_break_time['hours'] * 60) + ($current_after_break_time['minutes']));
                $total_break_minutes                                            = (($current_break_time['hours'] * 60) + ($current_break_time['minutes']));
                $daily_minutes_quota                                            = 480;
                $daily_break_quota                                              = 60;
                $regular_clocked_hours                                          = 0;
                $regular_clocked_minutes                                        = 0;
                $break_clocked_hours                                            = 0;
                $break_clocked_minutes                                          = 0;
                $overtime_clocked_hours                                         = 0;
                $overtime_clocked_minutes                                       = 0;
                   
                if($total_clocked_minutes_after_break > $daily_minutes_quota){ // calculate total regular time
                    $regular_clocked_minutes                                    = $daily_minutes_quota;
                    $current_overtime_minutes                                   = $total_clocked_minutes_after_break - $regular_clocked_minutes;
                } else { // his logged time is equal too or less than regular time
                    $regular_clocked_minutes                                    = $total_clocked_minutes_after_break;
                    $current_overtime_minutes                                   = 0;
                }
                
                // we have calculated total logged time, regular time, break time and over time in minutes. Now we need to convert it to hours and minutes
                if($regular_clocked_minutes > 0){
                    $regular_clocked_hours                                      = floor($regular_clocked_minutes / 60);
                    $regular_clocked_minutes                                    = $regular_clocked_minutes % 60;
                }
                
                if($total_break_minutes > 0){
                    $break_clocked_hours                                        = floor($total_break_minutes / 60);
                    $break_clocked_minutes                                      = $total_break_minutes % 60;
                }
                
                if($current_overtime_minutes > 0){
                    $overtime_clocked_hours                                     = floor($current_overtime_minutes / 60);
                    $overtime_clocked_minutes                                   = $current_overtime_minutes % 60;
                }
                
                $data['current_attendance_date']                                = $current_attendance_date;
                $data['current_clocked_hours']                                  = $current_clocked_time['hours'];
                $data['current_clocked_minutes']                                = $current_clocked_time['minutes'];
                $data['current_after_break_hours']                              = $current_after_break_time['hours'];
                $data['current_after_break_minutes']                            = $current_after_break_time['minutes'];
                $data['current_break_hours']                                    = $current_break_time['hours'];
                $data['current_break_minutes']                                  = $current_break_time['minutes'];
                $data['regular_clocked_hours']                                  = $regular_clocked_hours;
                $data['regular_clocked_minutes']                                = $regular_clocked_minutes;
                $data['break_clocked_hours']                                    = $break_clocked_hours;
                $data['break_clocked_minutes']                                  = $break_clocked_minutes;
                $data['overtime_clocked_hours']                                 = $overtime_clocked_hours;
                $data['overtime_clocked_minutes']                               = $overtime_clocked_minutes;
                $data['current_clocked_status']                                 = $current_clocked_status;
                
                $this->load->view('main/header', $data);
                $this->load->view('attendance/my_time_sheet');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 're_calculate_totals':
                        $company_sid = $this->input->post('company_sid');
                        $attendance_records = $this->attendance_model->get_company_time_sheet($company_sid, '', '', 'clock_in');

                        foreach ($attendance_records as $record) {
                            if ($record['out_sid'] > 0) {
                                $this->insert_attendance_total_record($company_sid, $record['employer_sid'], $record['out_sid']);
                            }
                        }

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Totals Recalculated!');
                        redirect('attendance/time_sheet', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function time_sheet($start_date = null, $end_date = null, $employer_sid = null) {
    if($session['employer_detail']['access_level_plus']==1 || $session['employer_detail']['pay_plan_flag']==1 ) {
            if ($this->session->userdata('logged_in')) {
                $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

                if ($this->form_validation->run() == false) {
                    $data['session']                                                = $this->session->userdata('logged_in');
                    $security_sid                                                   = $data['session']['employer_detail']['sid'];
                    $security_details                                               = db_get_access_level_details($security_sid);
                    $data['security_details']                                       = $security_details;
                    check_access_permissions($security_details, 'attendance', 'time_sheet');
                    $company_sid                                                    = $data["session"]["company_detail"]["sid"];

                    if ($employer_sid == null) {
                        $employer_sid                                               = $data["session"]["employer_detail"]["sid"];
                    }

                    $company_timezone                                               = $data['session']['portal_detail']['company_timezone'];
                    $data['company_timezone']                                       = $company_timezone;
                    $data['title']                                                  = "Employee Time Sheets";
                    $data['default_selected']                                       = $security_sid;

                    if ($start_date == null) {
                        date_default_timezone_set($company_timezone);
                        $start_date                                                 = date('Y-m-d');
                    } else {
                        $start_date                                                 = str_replace('_', '-', $start_date);
                        $start_date_array                                           = explode('-', $start_date);
                        $start_date                                                 = $start_date_array[2] . '-' . $start_date_array[0] . '-' . $start_date_array[1];
                    }

                    if ($end_date == null) {
                        date_default_timezone_set($company_timezone);
                        $end_date                                                   = date('Y-m-d');
                    } else {
                        $end_date                                                   = str_replace('_', '-', $end_date);
                        $end_date_array                                             = explode('-', $end_date);
                        $end_date                                                   = $end_date_array[2] . '-' . $end_date_array[0] . '-' . $end_date_array[1];
                    }
                    
                    $data['subtitle']                                               = "Attendance Record from ".$start_date. " to ".$end_date;
                    $attendance_totals                                              = $this->attendance_model->get_all_attendance_total_records($company_sid, $employer_sid, $start_date, $end_date);              
                    $attendance_breakout                                            = $this->attendance_breakout($attendance_totals); 
                    $data['grand_totals']                                           = array();
                    $data['attendance_totals']                                      = array();
                    
                    if(!empty($attendance_breakout)){
                        $data['attendance_totals']                                  = $attendance_breakout['attendance']; 
                        $data['grand_totals']                                       = $attendance_breakout['grand_totals']; 
                    }
                                
                    date_default_timezone_set($company_timezone);
                    $data['current_date']                                           = date('Y-m-d H:i:s');
                    $data['start_date']                                             = date('m/d/Y', strtotime($start_date));
                    $data['end_date']                                               = date('m/d/Y', strtotime($end_date));
                    $data['company_sid']                                            = $company_sid;
                    $current_attendance                                             = $this->get_last_clocked_day_data($company_sid, $employer_sid, $company_timezone);
                    
                    $current_attendance_date                                        = $current_attendance['attendance_date'];
                    $current_clocked_time                                           = $this->convert_unix_seconds_to_hours_minutes($current_attendance['total_clocked_unix']);
                    $current_after_break_time                                       = $this->convert_unix_seconds_to_hours_minutes($current_attendance['total_clocked_after_break_unix']);
                    $current_break_time                                             = $this->convert_unix_seconds_to_hours_minutes($current_attendance['total_break_unix']);
                    $current_clocked_status                                         = $current_attendance['clocked_status'];

                    //Set Color
                    $daily_after_break_time_quota                                   = 8 * 60 * 60;
                    $daily_time_quota                                               = 9 * 60 * 60;
                    $daily_break_quota                                              = 1 * 60 * 60;
                    $clocked_color                                                  = 'text-success';
                    $clocked_after_break_color                                      = 'text-success';
                    $break_color                                                    = 'text-success';
                    $total_clocked_minutes                                          = (($current_clocked_time['hours'] * 60) + ($current_clocked_time['minutes']));
                    $total_clocked_minutes_after_break                              = (($current_after_break_time['hours'] * 60) + ($current_after_break_time['minutes']));
                    $total_break_minutes                                            = (($current_break_time['hours'] * 60) + ($current_break_time['minutes']));
                    $daily_minutes_quota                                            = 480;
                    $daily_break_quota                                              = 60;
                    $regular_clocked_hours                                          = 0;
                    $regular_clocked_minutes                                        = 0;
                    $break_clocked_hours                                            = 0;
                    $break_clocked_minutes                                          = 0;
                    $overtime_clocked_hours                                         = 0;
                    $overtime_clocked_minutes                                       = 0;
                    
                    if($total_clocked_minutes_after_break > $daily_minutes_quota){ // calculate total regular time
                        $regular_clocked_minutes                                    = $daily_minutes_quota;
                        $current_overtime_minutes                                   = $total_clocked_minutes_after_break - $regular_clocked_minutes;
                    } else { // his logged time is equal too or less than regular time
                        $regular_clocked_minutes                                    = $total_clocked_minutes_after_break;
                        $current_overtime_minutes                                   = 0;
                    }
                    
                    // we have calculated total logged time, regular time, break time and over time in minutes. Now we need to convert it to hours and minutes
                    if($regular_clocked_minutes > 0){
                        $regular_clocked_hours                                      = floor($regular_clocked_minutes / 60);
                        $regular_clocked_minutes                                    = $regular_clocked_minutes % 60;
                    }
                    
                    if($total_break_minutes > 0){
                        $break_clocked_hours                                        = floor($total_break_minutes / 60);
                        $break_clocked_minutes                                      = $total_break_minutes % 60;
                    }
                    
                    if($current_overtime_minutes > 0){
                        $overtime_clocked_hours                                     = floor($current_overtime_minutes / 60);
                        $overtime_clocked_minutes                                   = $current_overtime_minutes % 60;
                    }

                    if ($current_attendance['total_clocked_after_break_unix'] > $daily_after_break_time_quota) {
                        $clocked_after_break_color                                  = 'text-danger';
                    }

                    if ($current_attendance['total_clocked_unix'] > $daily_time_quota) {
                        $clocked_color                                              = 'text-danger';
                    }

                    if ($current_attendance['total_break_unix'] > $daily_break_quota) {
                        $break_color                                                = 'text-danger';
                    }

                    $data['clocked_after_break_color']                              = $clocked_after_break_color;
                    $data['clocked_color']                                          = $clocked_color;
                    $data['break_color']                                            = $break_color;
                    $data['current_attendance_date']                                = $current_attendance_date;
                    $data['current_clocked_hours']                                  = $current_clocked_time['hours'];
                    $data['current_clocked_minutes']                                = $current_clocked_time['minutes'];
                    $data['current_after_break_hours']                              = $current_after_break_time['hours'];
                    $data['current_after_break_minutes']                            = $current_after_break_time['minutes'];
                    $data['current_break_hours']                                    = $current_break_time['hours'];
                    $data['current_break_minutes']                                  = $current_break_time['minutes'];                                                
                    $data['regular_clocked_hours']                                  = $regular_clocked_hours;
                    $data['regular_clocked_minutes']                                = $regular_clocked_minutes;
                    $data['break_clocked_hours']                                    = $break_clocked_hours;
                    $data['break_clocked_minutes']                                  = $break_clocked_minutes;
                    $data['overtime_clocked_hours']                                 = $overtime_clocked_hours;
                    $data['overtime_clocked_minutes']                               = $overtime_clocked_minutes;
                    $data['current_clocked_status']                                 = $current_clocked_status;
                    
                    $employees                                                      = $this->attendance_model->get_employees($company_sid); //Get Employees
                    $data['employees']                                              = $employees;                
                    $employee_info                                                  = $this->attendance_model->get_employee_information($company_sid, $employer_sid); //Employee Info
                    $data['employee_info']                                          = $employee_info;
                    $this->load->view('main/header', $data);
                    $this->load->view('attendance/time_sheet');
                    $this->load->view('main/footer');
                } 
            } else {
                
                redirect(base_url('login'), "refresh");
            }
      }else{
           $this->session->set_flashdata("message", "You don't have access to this module");
           redirect(base_url("attendance"),"refresh");
      }
    }

    public function edit_time_log($clock_out_sid, $employer_sid, $clock_in_sid) { // not working now
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'attendance', 'edit_time_log');
            $company_sid                                                        = $data["session"]["company_detail"]["sid"];
            
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $company_timezone = $data['session']['portal_detail']['company_timezone'];
                $data['title']                                                  = "Edit Time & Attendance";
                $data['subtitle'] = "Edit Time Log";
                $attendance_records = $this->attendance_model->get_attendance_records_by_clock_in_sid($clock_in_sid);
                $data['attendance_records'] = $attendance_records;
                $allowed_time = array();

                for ($hour = 0; $hour < 24; $hour++) {
                    for ($minute = 0; $minute < 60; $minute++) {
                        $allowed_time[] = str_pad($hour, 2, 0, STR_PAD_LEFT) . ':' . str_pad($minute, 2, 0, STR_PAD_LEFT);
                    }
                }

                $data['allowed_time'] = json_encode($allowed_time);
                $data['clock_out_sid'] = $clock_out_sid;
                $data['clock_in_sid'] = $clock_in_sid;
                $data['employee_sid'] = $employer_sid;

                $this->load->view('main/header', $data);
                $this->load->view('attendance/edit_time_log');
                $this->load->view('main/footer');
            } else { //Handle Post
                $data['session'] = $this->session->userdata('logged_in');
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $company_timezone = $data['session']['portal_detail']['company_timezone'];
                $company_sid = $this->input->post('company_sid');
                $employee_sid = $this->input->post('employer_sid');
                $attendance_sid = $this->input->post('attendance_sid');
                $new_datetime = $this->input->post('new_datetime');
                $attendance_type = $this->input->post('attendance_type');
                $clock_in_sid = $this->input->post('clock_in_sid');
                $clock_out_sid = $this->input->post('clock_out_sid');
                $date_time = explode(' ', $new_datetime);
                $date = explode('/', $date_time[0]);
                $time = explode(':', $date_time[1]);
                $datetime_unix = mktime($time[0], $time[1], 0, $date[0], $date[1], $date[2]);
                $new_datetime = date('Y-m-d H:i:s', $datetime_unix);
                $invalid_date = false;
                $this->attendance_model->update_attendance_record($company_sid, $employee_sid, $attendance_sid, $employer_sid, $company_timezone, $new_datetime);
                $this->session->set_flashdata('message', '<strong>Success: </strong> Attendance Record Updated!');
                redirect('attendance/edit_time_log/' . $clock_out_sid . '/' . $employee_sid . '/' . $clock_in_sid, 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    private function get_last_clocked_day_data($company_sid, $employer_sid, $company_timezone) {
        $last_clock_in                                                          = $this->attendance_model->get_last_clock_in($company_sid, $employer_sid);
        $return_data                                                            = array();
        $clocked_status                                                         = 'not_login';

        if (!empty($last_clock_in)) {
            $attendance_date                                                    = $last_clock_in['attendance_date'];
            $last_clock_in_sid                                                  = $last_clock_in['sid'];
            $date_start                                                         = date('Y-m-d 00:00:00', strtotime($attendance_date));
            $date_end                                                           = date('Y-m-d 23:59:59', strtotime($attendance_date));
            $attendance_records                                                 = $this->attendance_model->get_attendance_records($company_sid, $employer_sid, $date_start, $date_end);
            $all_clock_out_records                                              = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'clock_out');
            $last_clock_out                                                     = $this->filter_out_array_based_on_value($all_clock_out_records, 'clock_in_sid', $last_clock_in_sid);
            $last_clock_in_calculations                                         = array();
            $last_clock_in_calculations['company_sid']                          = $company_sid;
            $last_clock_in_calculations['employer_sid']                         = $employer_sid;
            $last_clock_in_calculations['clock_in_attendance_sid']              = 0;
            $last_clock_in_calculations['clock_out_attendance_sid']             = 0;
            $last_clock_in_calculations['daily_time_quota_hours']               = 0;
            $last_clock_in_calculations['daily_break_quota_hours']              = 0;
            $last_clock_in_calculations['related_attendance_sids']              = '';
            $last_clock_in_calculations['total_clocked_hours']                  = 0;
            $last_clock_in_calculations['total_clocked_minutes']                = 0;
            $last_clock_in_calculations['total_break_hours']                    = 0;
            $last_clock_in_calculations['total_break_minutes']                  = 0;
            $last_clock_in_calculations['total_after_break_hours']              = 0;
            $last_clock_in_calculations['total_after_break_minutes']            = 0;
            $last_clock_in_calculations['total_over_quota_hours']               = 0;
            $last_clock_in_calculations['total_over_quota_minutes']             = 0;
            $last_clock_in_calculations['over_quota_break_hours']               = 0;
            $last_clock_in_calculations['over_quota_break_minutes']             = 0;
            $last_clock_in_calculations['clocked_time_unix']                    = 0;
            $last_clock_in_calculations['clocked_after_break_time_unix']        = 0;
            $last_clock_in_calculations['break_time_unix']                      = 0;

            if (empty($last_clock_out)) {
                date_default_timezone_set($company_timezone);
                $last_clock_in_calculations                                     = $this->perform_all_calculations($company_sid, $employer_sid, $last_clock_in_sid, date('Y-m-d H:i:s'));
                $clocked_status                                                 = 'login';
            }
           
            $clock_in_records                                                   = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'clock_in'); //All Time Calculations
            $total_calculation_records                                          = array();
            
            foreach ($clock_in_records as $clock_in_record) {
                $clock_in_sid                                                   = $clock_in_record['sid'];
                $calculated_results                                             = $this->perform_all_calculations($company_sid, $employer_sid, $clock_in_sid);
                $total_calculation_records[]                                    = $calculated_results;
            }

            $total_calculation_records[]                                        = $last_clock_in_calculations; //Calculate Grand Total for Today
            $total_clocked_unix                                                 = 0;
            $total_clocked_after_break_unix                                     = 0;
            $total_break_unix                                                   = 0;

            foreach($total_calculation_records as $calculation_record) {
                    $total_clocked_unix                                         = $total_clocked_unix + $calculation_record['clocked_time_unix'];
                    $total_clocked_after_break_unix                             = $total_clocked_after_break_unix + $calculation_record['clocked_after_break_time_unix'];
                    $total_break_unix                                           = $total_break_unix + $calculation_record['break_time_unix'];
            }

            $return_data['attendance_date']                                     = $attendance_date;
            $return_data['total_clocked_unix']                                  = $total_clocked_unix;
            $return_data['total_clocked_after_break_unix']                      = $total_clocked_after_break_unix;
            $return_data['total_break_unix']                                    = $total_break_unix;
            $return_data['clocked_status']                                      = $clocked_status;
        } else {
            $return_data['attendance_date']                                     = date('Y-m-d H:i:s');
            $return_data['total_clocked_unix']                                  = 0;
            $return_data['total_clocked_after_break_unix']                      = 0;
            $return_data['total_break_unix']                                    = 0;
            $return_data['clocked_status']                                      = $clocked_status;
        }

        return $return_data;
    }

    private function calculate_grand_totals($attendance_totals_array = array()) {
        $gt_daily_time_quota                                                    = 0;
        $gt_daily_break_quota                                                   = 0;
        $gt_clocked_time                                                        = 0;
        $gt_break_time                                                          = 0;
        $gt_after_break_time                                                    = 0;
        $gt_over_quota_time                                                     = 0;
        $gt_over_quota_break_time                                               = 0;
        $gt_regular_time                                                        = 0;

        if (!empty($attendance_totals_array)) {            
            foreach ($attendance_totals_array as $attendance) { //daily time quota
                    $gt_daily_time_quota                                        = $gt_daily_time_quota + ($attendance['daily_time_quota_hours'] * 60);
                    $gt_daily_break_quota                                       = $gt_daily_break_quota + ($attendance['daily_break_quota_hours'] * 60);
                    $gt_clocked_time                                            = $gt_clocked_time + (($attendance['total_clocked_hours'] * 60) + ($attendance['total_clocked_minutes']));
                    $gt_break_time                                              = $gt_break_time + (($attendance['total_break_hours'] * 60) + ($attendance['total_break_minutes']));
                    $gt_after_break_time                                        = $gt_after_break_time + (($attendance['total_after_break_hours'] * 60) + ($attendance['total_after_break_minutes']));
                    $gt_over_quota_time                                         = $gt_over_quota_time + (($attendance['total_over_quota_hours'] * 60) + ($attendance['total_over_quota_minutes']));
                    $gt_over_quota_break_time                                   = $gt_over_quota_break_time + (($attendance['total_over_quota_hours'] * 60) + ($attendance['total_over_quota_minutes']));
            }
        }
        
//        echo '<hr>';
//        echo '<br>gt_daily_time_quota: '.$gt_daily_time_quota;
//        echo '<br>gt_daily_break_quota: '.$gt_daily_break_quota;
//        echo '<br>gt_clocked_time: '.$gt_clocked_time;
//        echo '<br>gt_break_time: '.$gt_break_time;
//        echo '<br>gt_after_break_time: '.$gt_after_break_time;
//        echo '<br>gt_over_quota_time: '.$gt_over_quota_time;
//        echo '<br>gt_over_quota_break_time: '.$gt_over_quota_break_time;
//        echo '</hr>';
//        exit;
        
        /*
            gt_daily_time_quota: 480 // this is in minutes i.e. 8 * 60 = 480
            gt_daily_break_quota: 60 // this is in minutes i.e. 1 * 60 = 60
            gt_clocked_time: 3025 // total time clocked till 27th April 2017
            gt_break_time: 4 // this is total break 
            gt_after_break_time: 3021 // 3025 - 4 = 3021
            gt_over_quota_time: 2541 // 3021 - 480 = 2541
            gt_over_quota_break_time: 2541 // this needs to be verified.
         *          */

        /*
            gt_daily_time_quota: 960
            gt_daily_break_quota: 120
            gt_clocked_time: 4249 // total time clocked till last checkout
            gt_break_time: 4 // total break till checkout
            gt_after_break_time: 4245
            gt_over_quota_time: 3285
            gt_over_quota_break_time: 3285
         *          */
        
        /*

         *  gt_daily_time_quota: 1440
            gt_daily_break_quota: 180
            gt_clocked_time: 4254 // total time clocked till last checkout
            gt_break_time: 7 // total break till checkout
            gt_after_break_time: 4247
            gt_over_quota_time: 3285
            gt_over_quota_break_time: 3285
         *          */
        
        if($gt_after_break_time > $gt_daily_time_quota){ // calculate total regular time
            $gt_regular_time = $gt_daily_time_quota;
        } else { // his logged time is equal too or less than regular time
            $gt_regular_time = $gt_after_break_time - $gt_over_quota_break_time;
        }
        
        $gt_daily_time_quota_hours                                              = floor($gt_daily_time_quota / 60);
        $gt_daily_time_quota_minutes                                            = $gt_daily_time_quota % 60;
        $gt_clocked_time_hours                                                  = floor($gt_clocked_time / 60);
        $gt_clocked_time_minutes                                                = $gt_clocked_time % 60;
        $gt_break_time_hours                                                    = floor($gt_break_time / 60);
        $gt_break_time_minutes                                                  = $gt_break_time % 60;
        $gt_regular_time_hours                                                  = floor($gt_regular_time / 60);
        $gt_regular_time_minutes                                                = $gt_regular_time % 60;
        $gt_after_break_time_hours                                              = floor($gt_after_break_time / 60);
        $gt_after_break_time_minutes                                            = $gt_after_break_time % 60;
        $gt_over_quota_time_hours                                               = floor($gt_over_quota_time / 60);
        $gt_over_quota_time_minutes                                             = $gt_over_quota_time % 60;
        $gt_over_quota_break_time_hours                                         = floor($gt_over_quota_break_time / 60);
        $gt_over_quota_break_time_minutes                                       = $gt_over_quota_break_time % 60;
        
        $return_data                                                            = array();
        $return_data['gt_daily_time_quota_hours']                               = str_pad($gt_daily_time_quota_hours, 2, 0, STR_PAD_LEFT);
        $return_data['gt_daily_time_quota_minutes']                             = str_pad($gt_daily_time_quota_minutes, 2, 0, STR_PAD_LEFT);
        $return_data['gt_clocked_time_hours']                                   = str_pad($gt_clocked_time_hours, 2, 0, STR_PAD_LEFT);
        $return_data['gt_clocked_time_minutes']                                 = str_pad($gt_clocked_time_minutes, 2, 0, STR_PAD_LEFT);
        $return_data['gt_break_time_hours']                                     = str_pad($gt_break_time_hours, 2, 0, STR_PAD_LEFT);
        $return_data['gt_break_time_minutes']                                   = str_pad($gt_break_time_minutes, 2, 0, STR_PAD_LEFT);
        $return_data['gt_regular_time_hours']                                   = str_pad($gt_regular_time_hours, 2, 0, STR_PAD_LEFT);
        $return_data['gt_regular_time_minutes']                                 = str_pad($gt_regular_time_minutes, 2, 0, STR_PAD_LEFT);
        $return_data['gt_after_break_time_hours']                               = str_pad($gt_after_break_time_hours, 2, 0, STR_PAD_LEFT);
        $return_data['gt_after_break_time_minutes']                             = str_pad($gt_after_break_time_minutes, 2, 0, STR_PAD_LEFT);
        $return_data['gt_over_quota_time_hours']                                = str_pad($gt_over_quota_time_hours, 2, 0, STR_PAD_LEFT);
        $return_data['gt_over_quota_time_minutes']                              = str_pad($gt_over_quota_time_minutes, 2, 0, STR_PAD_LEFT);
        $return_data['gt_over_quota_break_time_hours']                          = str_pad($gt_over_quota_break_time_hours, 2, 0, STR_PAD_LEFT);
        $return_data['gt_over_quota_break_time_minutes']                        = str_pad($gt_over_quota_break_time_minutes, 2, 0, STR_PAD_LEFT);
        //$return_data['gt_regular_time_hours']                                   = str_pad($gt_over_quota_break_time_hours, 2, 0, STR_PAD_LEFT);
        //$return_data['gt_regular_time_minutes']                                 = str_pad($gt_over_quota_break_time_minutes, 2, 0, STR_PAD_LEFT);
        return $return_data;
    }

    private function attendance_breakout($attendance_totals_array = array()) {
        $return_data                                                            = array();
        if (!empty($attendance_totals_array)) {     
            $gt_regular_time                                                    = 0;
            $gt_break_time                                                      = 0;
            $gt_over_quota_time                                                 = 0;
            $gt_after_break_time                                                = 0;
            $gt_regular_clocked_hours                                           = 0;
            $gt_regular_clocked_minutes                                         = 0;
            $gt_total_break_hours                                               = 0;
            $gt_total_break_minutes                                             = 0;
            $gt_total_over_quota_hours                                          = 0;
            $gt_total_over_quota_minutes                                        = 0;
            $gt_total_after_break_hours                                         = 0;
            $gt_total_after_break_minutes                                       = 0;
            
            foreach($attendance_totals_array as $key => $attendance) { //daily time quota
                $total_daily_quota_time                                         = $attendance['daily_time_quota_hours'] * 60;
                $total_clocked_time                                             = ($attendance['total_clocked_hours'] * 60) + $attendance['total_clocked_minutes'];
                $total_break_time                                               = ($attendance['total_break_hours'] * 60) + $attendance['total_break_minutes'];
                $total_over_quota_time                                          = ($attendance['total_over_quota_hours'] * 60) + $attendance['total_over_quota_minutes'];
                $total_after_break_time                                         = ($attendance['total_after_break_hours'] * 60) + $attendance['total_after_break_minutes'];
                $total_regular_time                                             = 0;
                $regular_clocked_hours                                          = 0;
                $regular_clocked_minutes                                        = 0;

                if($total_after_break_time > $total_daily_quota_time){ // calculate total regular time
                    $total_regular_time                                         = $total_daily_quota_time;
                } else { // his logged time is equal too or less than regular time
                    $total_regular_time                                         = $total_after_break_time;
                }

                // Grant total = sum them
                $gt_regular_time                                                += $total_regular_time;
                $gt_break_time                                                  += $total_break_time;
                $gt_over_quota_time                                             += $total_over_quota_time;
                $gt_after_break_time                                            += $total_after_break_time;
                // we have calculated total logged time, regular time, break time and over time in minutes. Now we need to convert it to hours and minutes
                if($total_regular_time > 0){
                    $regular_clocked_hours                                      = floor($total_regular_time / 60);
                    $regular_clocked_minutes                                    = $total_regular_time % 60;
                }
                                
                $regular_clocked_array                                          = array();
                $regular_clocked_array = array( 'regular_clocked_hours'         => str_pad($regular_clocked_hours, 2, 0, STR_PAD_LEFT),
                                                'regular_clocked_minutes'       => str_pad($regular_clocked_minutes, 2, 0, STR_PAD_LEFT)
                                              );
                
                $combined_array                                                 = array();
                $combined_array                                                 = array_merge($attendance, $regular_clocked_array);                
                $return_data['attendance'][$key]                                = $combined_array;
            } // end of foreach
            
                
            if($gt_regular_time > 0){
                $gt_regular_clocked_hours                                       = floor($gt_regular_time / 60);
                $gt_regular_clocked_minutes                                     = $gt_regular_time % 60;
            }

            if($gt_break_time > 0){
                $gt_total_break_hours                                           = floor($gt_break_time / 60);
                $gt_total_break_minutes                                         = $gt_break_time % 60;
            }

            if($gt_over_quota_time > 0){
                $gt_total_over_quota_hours                                      = floor($gt_over_quota_time / 60);
                $gt_total_over_quota_minutes                                    = $gt_over_quota_time % 60;
            }

            if($gt_after_break_time > 0){
                $gt_total_after_break_hours                                     = floor($gt_after_break_time / 60);
                $gt_total_after_break_minutes                                   = $gt_after_break_time % 60;
            }
            
            $gt_clocked_array                                                   = array();
            $gt_clocked_array   = array( 'gt_regular_clocked_hours'             => str_pad($gt_regular_clocked_hours, 2, 0, STR_PAD_LEFT),
                                         'gt_regular_clocked_minutes'           => str_pad($gt_regular_clocked_minutes, 2, 0, STR_PAD_LEFT),
                                         'gt_total_break_hours'                 => str_pad($gt_total_break_hours, 2, 0, STR_PAD_LEFT),
                                         'gt_total_break_minutes'               => str_pad($gt_total_break_minutes, 2, 0, STR_PAD_LEFT),
                                         'gt_total_over_quota_hours'            => str_pad($gt_total_over_quota_hours, 2, 0, STR_PAD_LEFT),
                                         'gt_total_over_quota_minutes'          => str_pad($gt_total_over_quota_minutes, 2, 0, STR_PAD_LEFT),
                                         'gt_total_after_break_hours'           => str_pad($gt_total_after_break_hours, 2, 0, STR_PAD_LEFT),
                                         'gt_total_after_break_minutes'         => str_pad($gt_total_after_break_minutes, 2, 0, STR_PAD_LEFT)
                                        ); 
            $return_data['grand_totals']                                        = $gt_clocked_array;
        }   
        
        return $return_data;       
    }
    
    private function attendance_breakout_single($attendance_totals_array = array()) {
        $return_data                                                            = array();
        
        if (!empty($attendance_totals_array)) {     
            $total_clocked_hours                                                = $attendance_totals_array['total_clocked_hours'];
            $total_clocked_minutes                                              = $attendance_totals_array['total_clocked_minutes'];
            $total_break_hours                                                  = $attendance_totals_array['total_break_hours'];
            $total_break_minutes                                                = $attendance_totals_array['total_break_minutes'];
            $total_after_break_hours                                            = $attendance_totals_array['total_after_break_hours'];
            $total_after_break_minutes                                          = $attendance_totals_array['total_after_break_minutes'];
            $total_over_quota_hours                                             = $attendance_totals_array['total_over_quota_hours'];
            $total_over_quota_minutes                                           = $attendance_totals_array['total_over_quota_minutes'];
            
                $total_daily_quota_time                                         = 8 * 60;
                $total_clocked_time                                             = ($total_clocked_hours * 60) + $total_clocked_minutes;
                $total_break_time                                               = ($total_break_hours * 60) + $total_break_minutes;
                $total_after_break_time                                         = ($total_after_break_hours * 60) + $total_after_break_minutes;
                $total_over_quota_time                                          = ($total_over_quota_hours) + $total_over_quota_minutes;
                
                if($total_after_break_time > $total_daily_quota_time){ // calculate total regular time
                    $total_regular_time                                         = $total_daily_quota_time;
                } else { // his logged time is equal too or less than regular time
                    $total_regular_time                                         = $total_after_break_time;
                }

                if($total_regular_time > 0){
                    $regular_clocked_hours                                      = floor($total_regular_time / 60);
                    $regular_clocked_minutes                                    = $total_regular_time % 60;
                }
                                
                $regular_clocked_array                                          = array();
                $regular_clocked_array = array( 'regular_clocked_hours'         => str_pad($regular_clocked_hours, 2, 0, STR_PAD_LEFT),
                                                'regular_clocked_minutes'       => str_pad($regular_clocked_minutes, 2, 0, STR_PAD_LEFT)
                                              );
                                
                $return_data                                                    = array_merge($attendance_totals_array, $regular_clocked_array);                
        }   
        
        return $return_data;       
    }
    
    private function convert_db_datetime_to_unix_timestamp($date) {
        $date_time = explode(' ', $date);
        $date = explode('-', $date_time[0]);
        $time = explode(':', $date_time[1]);
        return mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
    }

    public function ajax_responder($perform_action = null) {
        if ($this->session->userdata('logged_in')) {
            $this->form_validation->set_rules('perform_action', 'perform_action', 'xss_clean|trim');

            if ($this->form_validation->run() == false) {
                //Handle Get request
            } else {
                //Handle Post request
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'get_confirmation_dialog':
                        $form_id                                                = $this->input->post('form_id');
                        $attendance_type                                        = $this->input->post('attendance_type');
                        $view_data                                              = array();

                        if ($attendance_type == 'clock_in') {
                            $view_data['attendance_status']                     = 'CLOCK IN!';
                            $view_data['attendance_status_message']             = 'CLOCK ME IN!';
                            $view_data['form_id']                               = $form_id;
                        } else if ($attendance_type == 'clock_out') {
                            $view_data['attendance_status']                     = 'CLOCK OUT!';
                            $view_data['attendance_status_message']             = 'CLOCK ME OUT!';
                            $view_data['form_id']                               = $form_id;
                        }

                        $view_html                                              = $this->load->view('attendance/confirm_dialog_partial', $view_data, true);
                        echo $view_html;
                        break;
                    case 'edit_attendance_date_time':
                        $attendance_sid = $this->input->post('attendance_sid');
                        echo 'working';
                        break;
                }
            }
        }
    }

    public function weekly_attendance($employee_sid = null, $year = null, $week_number = null) {
        
      if($session['employer_detail']['access_level_plus']==1 || $session['employer_detail']['pay_plan_flag']==1 ) {  
        if ($this->session->userdata('logged_in')) {
            if ($this->form_validation->run() == false) {
                $data['session']                                                = $this->session->userdata('logged_in');
                $security_sid                                                   = $data['session']['employer_detail']['sid'];
                $security_details                                               = db_get_access_level_details($security_sid);
                $data['security_details']                                       = $security_details;
                check_access_permissions($security_details, 'attendance', 'weekly_attendance');
                $company_timezone                                               = $data['session']['portal_detail']['company_timezone'];
                $data['company_timezone']                                       = $company_timezone;
                $company_sid                                                    = $data['session']['company_detail']['sid'];
                $employer_sid                                                   = $data['session']['employer_detail']['sid'];
                $data['default_selected']                                       = $security_sid;
                
                if ($year == null) {
                    date_default_timezone_set($company_timezone);
                    $year                                                       = date('Y');
                }

                if ($week_number == null) {
                    date_default_timezone_set($company_timezone);
                    $week_number                                                = date('W');
                }

                if ($employee_sid == null) {
                    $employee_sid                                               = $employer_sid;
                }
                
                $today                                                          = new DateTime();
                $tz                                                             = new DateTimeZone($company_timezone);
                $today->setTimezone($tz);

                if ($year != null && $week_number != null) {
                    $today->setISODate($year, $week_number);
                }

                $currentWeekDay                                                 = $today->format('w'); // Weekday as a number (0 = Sunday, 6 = Saturday)
                $firstdayofweek                                                 = clone $today;
                $lastdayofweek                                                  = clone $today;

                if ($currentWeekDay !== '0') {
                    $firstdayofweek->modify('last sunday');
                }

                if ($currentWeekDay !== '6') {
                    $lastdayofweek->modify('next saturday');
                }

                $my_week_start                                                  = $firstdayofweek->format('Y-m-d 00:00:00');
                $my_week_start_unix                                             = strtotime($my_week_start);
                $week_start_date                                                = date('d', $my_week_start_unix);
                $week_start                                                     = new DateTime($my_week_start);
                $my_week_end                                                    = $lastdayofweek->format('Y-m-d 23:59:59');
                $my_week_end_unix                                               = strtotime($my_week_end);
                $attendance_records                                             = $this->attendance_model->get_all_attendance_total_records($company_sid, $employee_sid, $my_week_start, $my_week_end);
                
                /*echo "<pre>";
                print_r($attendance_records);
                exit;*/
                $total_hours_quota                                              = 0;
                $total_working_time_unix                                        = 0;
                $total_break_time_unix                                          = 0;
                $total_over_time_unix                                           = 0;
                $all_week_dates                                                 = array();
                //echo $my_week_start.'<br>'.$my_week_end.'<br>'.$firstdayofweek->format('Y-m-d'); exit;
                for ($week_day = 0; $week_day < 7; $week_day++) {
                    $all_week_dates[]                                           = $week_start->modify('+1 day')->format('Y-m-d');
                }

                $data['all_week_dates']                                         = $all_week_dates;
                $attendance_records_date_wise                                   = array();
                
                foreach ($all_week_dates as $date_key => $date) {
                    $date_record                                                = $this->filter_out_array_based_on_value($attendance_records, 'attendance_date', $date);

                    if (!empty($date_record)) {
                        $data_record                                            = $this->attendance_breakout_single($date_record[0]);
                        $attendance_records_date_wise[$date]                    = $data_record;
                    } else {
                        $attendance_records_date_wise[$date]                    = array();
                    }
                }
                //printexit;
                $data['attendance_records']                                     = $attendance_records_date_wise;
                $employee_info                                                  = $this->attendance_model->get_employee_information($company_sid, $employee_sid);
                $data['employee_info']                                          = $employee_info;
                $data['employer_sid']                                           = $employer_sid;
                $data['employee_sid']                                           = $employee_sid;
                $employees                                                      = $this->attendance_model->get_employees($company_sid);
                $data['employees']                                              = $employees;
                $weeks                                                          = $this->all_weeks_of_year($today, $year, $company_timezone); //Get all weeks

                $data['weeks']                                                  = $weeks;
                $data['title']                                                  = 'Weekly Attendance Management';
                $data['subtitle']                                               = "Attendance Record from ".$firstdayofweek->format('Y-m-d'). ' to '.$lastdayofweek->format('Y-m-d');
                $this->load->view('main/header', $data);
                $this->load->view('attendance/weekly_attendance');
                $this->load->view('main/footer');
            } 
        } else {
            redirect(base_url('login'), "refresh");
        }
      }else{
         $this->session->set_flashdata("message", "You don't have access to this module");
           redirect(base_url("attendance"),"refresh");
      }
    }

    public function manage_attendance($employee_sid = null, $date = null) {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'attendance', 'manage_attendance');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $company_timezone                                                   = $data['session']['portal_detail']['company_timezone'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $valid_date                                                         = 'valid';
            
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $data['title']                                                  = 'Attendance Management';
                $data['subtitle']                                               = 'Edit Time Log';

                if ($date != null && !empty($date) && $employee_sid != null) {
                    $date                                                       = implode('-', explode('_', $date));
                    $from_date                                                  = $date . ' 00:00:00';
                    $to_date                                                    = $date . ' 23:59:59';
                    date_default_timezone_set($company_timezone);
                    $current_date                                               = date('Y-m-d H:i:s');
                    $data['selected_date']                                      = $from_date;
                    
                    if($current_date < $from_date){
                        $valid_date                                             = 'invalid';
                        $this->session->set_flashdata('message', '<strong>Error: </strong> You can not manage attendance for future dates!');
                        redirect('attendance/weekly_attendance/', 'refresh');
                    }
                    
                    $attendance_records                                         = $this->attendance_model->manage_attendance_records($company_sid, $employee_sid, $from_date, $to_date);
                    $data['attendance_records']                                 = $attendance_records;

                    if (!empty($attendance_records)) {
                        $last_attendance_record                                 = $attendance_records[count($attendance_records) - 1];                        
                        $all_clock_in_records                                   = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'clock_in');
                        $all_clock_out_records                                  = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'clock_out');                        
                        $all_break_start_records                                = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'break_start');
                        $all_break_end_records                                  = $this->filter_out_array_based_on_value($attendance_records, 'attendance_type', 'break_end');
                        $all_break_records                                      = $this->filter_out_array_based_on_value($attendance_records, 'attendance_status', 'break_hours');

                        if (count($all_break_records) > 0) {
                            $last_break_record                                  = $all_break_records[count($all_break_records) - 1];
                        } else {
                            $last_break_record                                  = array();
                        }

                        if (count($all_clock_in_records) > 0) { // it checks if there is any clocked in record in that particular date
                            $last_clock_in                                      = $all_clock_in_records[count($all_clock_in_records) - 1];
                            $clock_in_date                                      = new DateTime($last_clock_in['attendance_date']);

                            if (count($all_clock_out_records) > 0) {
                                $last_clock_out                                 = $all_clock_out_records[count($all_clock_out_records) - 1];
                                $clock_out_date                                 = new DateTime($last_clock_out['attendance_date']);
                                $clock_out_date                                 = $clock_out_date->format('Y-m-d H:i:s');
                            } else {
                                $clock_out_date                                 = $clock_in_date->modify('+48 hours');
                                $clock_out_date                                 = $clock_out_date->format('Y-m-d H:i:s');
                                $last_clock_out                                 = array();
                            }

                            if (count($all_break_start_records) > 0) {
                                $last_break_start                               = $all_break_start_records[count($all_break_start_records) - 1];
                            } else {
                                $last_break_start                               = array();
                            }

                            if (count($all_break_end_records) > 0) {
                                $last_break_end                                 = $all_break_end_records[count($all_break_end_records) - 1];
                            } else {
                                $last_break_end                                 = array();
                            }

                            $clock_in_date                                      = $clock_in_date->format('Y-m-d H:i:s');
                        } else {
                            $date                                               = implode('-', explode('_', $date));
                            $clock_in_date                                      = new DateTime($date);
                            $clock_in_date                                      = $clock_in_date->format('Y-m-d H:i:s');
                            $last_clock_in                                      = array();
                            $last_clock_out                                     = array();
                            $last_break_start                                   = array();
                            $last_break_end                                     = array();
                            $clock_out_date                                     = array();
                        }

                        $data['last_clock_in']                                  = $last_clock_in;
                        $data['last_clock_out']                                 = $last_clock_out;
                        $data['last_break_start']                               = $last_break_start;
                        $data['last_break_end']                                 = $last_break_end;
                        $data['clock_in_date']                                  = $clock_in_date;
                        $data['clock_out_date']                                 = $clock_out_date;
                        $show_clock_in_btn                                      = false;
                        $show_clock_out_btn                                     = false;
                        $show_break_start_btn                                   = true;
                        $show_break_end_btn                                     = true;

                        if (!empty($last_attendance_record) && in_array($last_attendance_record['attendance_type'], ['clock_in', 'break_start', 'break_end'])) {
                            $show_clock_out_btn                                 = true;
                        }

                        if (empty($last_attendance_record) && empty($last_clock_in) && empty($last_clock_out)) {
                            $show_clock_in_btn                                  = true;
                        } else if (!empty($last_attendance_record) && $last_attendance_record['attendance_type'] == 'clock_out') {
                            $show_clock_in_btn                                  = true;
                        }
                       // echo "If condition"; exit;
                    } else {
                        $show_clock_in_btn                                      = true;
                        $show_clock_out_btn                                     = false;
                        $show_break_start_btn                                   = false;
                        $show_break_end_btn                                     = false;
                    }

                    $allowed_time                                               = array();

                    for ($hour = 0; $hour < 24; $hour++) {
                        for ($minute = 0; $minute < 60; $minute++) {
                            $allowed_time[]                                     = str_pad($hour, 2, 0, STR_PAD_LEFT) . ':' . str_pad($minute, 2, 0, STR_PAD_LEFT);
                        }
                    }

                    $data['allowed_time']                                       = json_encode($allowed_time);
                    $data['employee_sid']                                       = $employee_sid;
                    $data['company_sid']                                        = $company_sid;
                    $data['show_clock_in_btn']                                  = $show_clock_in_btn;
                    $data['show_clock_out_btn']                                 = $show_clock_out_btn;
                    $data['show_break_start_btn']                               = $show_break_start_btn;
                    $data['show_break_end_btn']                                 = $show_break_end_btn;

                    if (!empty($clock_in_data)) {
                        date_default_timezone_set($company_timezone);
                        $year_week                                              = date('Y|W', strtotime($clock_in_data['attendance_date']));
                        $year_week                                              = explode('|', $year_week);
                        $back_btn_url                                           = base_url('attendance/weekly_attendance/' . $employee_sid . '/' . intval($year_week[0]) . '/' . intval($year_week[1]));
                    } else {
                        date_default_timezone_set($company_timezone);
                        $year_week                                              = date('Y|W', strtotime($date));
                        $year_week                                              = explode('|', $year_week);
                        $back_btn_url                                           = base_url('attendance/weekly_attendance/' . $employee_sid . '/' . intval($year_week[0]) . '/' . intval($year_week[1]));
                    }

                    $data['back_btn_url']                                       = $back_btn_url;
                    $employee_info                                              = $this->attendance_model->get_employee_information($company_sid, $employee_sid); //Get Employee Information
                    $data['employee_info']                                      = $employee_info;

                    $this->load->view('main/header', $data);
                    $this->load->view('attendance/manage_attendance');
                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error: </strong> Employee Not Record!');
                    redirect('attendance/weekly_attendance/', 'refresh');
                }
            } else {
                $perform_action                                                 = $this->input->post('perform_action');
                        
                switch ($perform_action) {
                    case 'insert_new_clock_in':
//                        [company_sid] => 3
//                        [employer_sid] => 227
//                        [attendance_sid] => 0
//                        [attendance_type] => clock_in
//                        [clock_in_sid] => 0
//                        [new_datetime] => 05/08/2017 00:00
                        $company_sid                                            = $this->input->post('company_sid');
                        $employee_sid                                           = $this->input->post('employer_sid');
                        $new_datetime                                           = $this->input->post('new_datetime');
                        $attendance_type                                        = $this->input->post('attendance_type');
                        $clock_in_sid                                           = 0;
                        $date_time                                              = explode(' ', $new_datetime);
                        $date                                                   = explode('/', $date_time[0]);
                        $time                                                   = explode(':', $date_time[1]);

                        date_default_timezone_set($company_timezone);
                        $datetime_unix                                          = mktime($time[0], $time[1], 0, $date[0], $date[1], $date[2]);
                        $new_datetime                                           = date('Y-m-d H:i:s', $datetime_unix);
                        $data_to_insert                                         = array();
                        $data_to_insert['company_sid']                          = $company_sid;
                        $data_to_insert['employer_sid']                         = $employee_sid;
                        $data_to_insert['attendance_type']                      = $attendance_type;
                        $data_to_insert['attendance_date']                      = $new_datetime;
                        $data_to_insert['ip_address']                           = getUserIP();
                        $data_to_insert['user_agent']                           = $_SERVER['HTTP_USER_AGENT'];
                        $data_to_insert['created_by']                           = $employer_sid;
                        date_default_timezone_set($company_timezone);
                        $data_to_insert['attendance_year']                      = intval(date('Y', $datetime_unix));
                        date_default_timezone_set($company_timezone);
                        $data_to_insert['attendance_week']                      = intval(date('W', $datetime_unix));
                        $data_to_insert['is_manual']                            = 1;
                        $data_to_insert['timezone']                             = $company_timezone;
                        $data_to_insert['attendance_status']                    = 'working_hours';
                        $this->attendance_model->insert_attendance_record($data_to_insert);
                        $this->insert_attendance_total_record($company_sid, $employer_sid, $new_datetime);
                        date_default_timezone_set($company_timezone);
                        redirect('attendance/manage_attendance/' . $employee_sid . '/' . date('Y_m_d', strtotime($new_datetime)), 'refresh');
                        break;
                    case 'insert_new_clock_out':
                        $company_sid                                            = $this->input->post('company_sid');
                        $employee_sid                                           = $this->input->post('employer_sid');
                        $new_datetime                                           = $this->input->post('new_datetime');
                        $attendance_type                                        = $this->input->post('attendance_type');
                        $last_attendance_sid                                    = $this->input->post('last_attendance_sid');
                        $date_time                                              = explode(' ', $new_datetime);
                        $date                                                   = explode('/', $date_time[0]);
                        $time                                                   = explode(':', $date_time[1]);
                        date_default_timezone_set($company_timezone);
                        $datetime_unix                                          = mktime($time[0], $time[1], 0, $date[0], $date[1], $date[2]);
                        $new_datetime                                           = date('Y-m-d H:i:s', $datetime_unix);
                        $verify_manual_entry                                    = $this->attendance_model->verify_manual_entry($company_sid, $employee_sid, $new_datetime, $attendance_type);
                        //$clock_in_sid                                         = $this->attendance_model->get_attendance_clock_in_sid($company_sid, $employer_sid, $new_datetime);
                        if(!empty($verify_manual_entry)){ // clockin sid found perform next test
                            $clock_in_sid                                       = $verify_manual_entry['clock_in_sid'];
                            $clock_in_datatime                                  = $verify_manual_entry['datatime'];
                            
                            if($new_datetime >= $clock_in_datatime){ // check if break start is within clocked in time
                                $all_records                                    = $verify_manual_entry['all_records'];
                                
//                                if(!empty($all_records)){ // perform additional checks
//                                    $addtional_checks = $this->perform_additional_checks_for_manual_breaks($verify_manual_entry, $attendance_type, $new_datetime);
//                                }
                                $data_to_insert                                 = array();
                                $data_to_insert['company_sid']                  = $company_sid;
                                $data_to_insert['employer_sid']                 = $employee_sid;
                                $data_to_insert['attendance_type']              = $attendance_type;
                                $data_to_insert['attendance_date']              = $new_datetime;
                                $data_to_insert['ip_address']                   = getUserIP();
                                $data_to_insert['user_agent']                   = $_SERVER['HTTP_USER_AGENT'];
                                $data_to_insert['created_by']                   = $employer_sid;
                                $data_to_insert['clock_in_sid']                 = $clock_in_sid;
                                date_default_timezone_set($company_timezone);
                                $data_to_insert['attendance_year']              = intval(date('Y', $datetime_unix));
                                date_default_timezone_set($company_timezone);
                                $data_to_insert['attendance_week']              = intval(date('W', $datetime_unix));
                                $data_to_insert['is_manual']                    = 1;
                                $data_to_insert['timezone']                     = $company_timezone;
                                $data_to_insert['attendance_status']            = 'working_hours';
                                $this->attendance_model->insert_attendance_record($data_to_insert);
                                $this->insert_attendance_total_record($company_sid, $employee_sid, $new_datetime); 
                                date_default_timezone_set($company_timezone);
                                $this->session->set_flashdata('message', '<strong>Success :</strong> Clocked out successfully!');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error :</strong> Incorrect clock out time!');
                            }                            
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error :</strong> Attendance record not found!');
                        }
                        
                        redirect('attendance/manage_attendance/' . $employee_sid . '/' . date('Y_m_d', strtotime($new_datetime)), 'refresh');
                        break;
                    case 'insert_new_break_start':
                        $company_sid                                            = $this->input->post('company_sid');
                        $employee_sid                                           = $this->input->post('employer_sid');
                        $new_datetime                                           = $this->input->post('new_datetime');
                        $attendance_type                                        = $this->input->post('attendance_type');
                        $date_time                                              = explode(' ', $new_datetime);
                        $date                                                   = explode('/', $date_time[0]);
                        $time                                                   = explode(':', $date_time[1]);
                        date_default_timezone_set($company_timezone);
                        $datetime_unix                                          = mktime($time[0], $time[1], 0, $date[0], $date[1], $date[2]);
                        $new_datetime                                           = date('Y-m-d H:i:s', $datetime_unix);                        
                        $verify_manual_entry                                    = $this->attendance_model->verify_manual_entry($company_sid, $employee_sid, $new_datetime, $attendance_type);
                        
                        if(!empty($verify_manual_entry)){ // clockin sid found perform next test
                            $clock_in_sid                                       = $verify_manual_entry['clock_in_sid'];
                            $clock_in_datatime                                  = $verify_manual_entry['datatime'];
                            
                            if($new_datetime >= $clock_in_datatime){ // check if break start is within clocked in time
                                $all_records                                    = $verify_manual_entry['all_records'];
                                
//                                if(!empty($all_records)){ // perform additional checks
//                                    $addtional_checks = $this->perform_additional_checks_for_manual_breaks($verify_manual_entry, $attendance_type, $new_datetime);
//                                }
                                
                                $data_to_insert                                 = array();
                                $data_to_insert['company_sid']                  = $company_sid;
                                $data_to_insert['employer_sid']                 = $employee_sid;
                                $data_to_insert['attendance_type']              = $attendance_type;
                                $data_to_insert['attendance_date']              = $new_datetime;
                                $data_to_insert['ip_address']                   = getUserIP();
                                $data_to_insert['user_agent']                   = $_SERVER['HTTP_USER_AGENT'];
                                $data_to_insert['created_by']                   = $employer_sid;
                                $data_to_insert['clock_in_sid']                 = $clock_in_sid;
                                date_default_timezone_set($company_timezone);
                                $data_to_insert['attendance_year']              = intval(date('Y', $datetime_unix));
                                date_default_timezone_set($company_timezone);
                                $data_to_insert['attendance_week']              = intval(date('W', $datetime_unix));
                                $data_to_insert['is_manual']                    = 1;
                                $data_to_insert['timezone']                     = $company_timezone;
                                $data_to_insert['attendance_status']            = 'break_hours';
                                $this->attendance_model->insert_attendance_record($data_to_insert);
                                $this->insert_attendance_total_record($company_sid, $employee_sid, $new_datetime); 
                                date_default_timezone_set($company_timezone);
                                $this->session->set_flashdata('message', '<strong>Success :</strong> Manual break-start added to system successfully!');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error :</strong> Incorrect manual break-start time!');
                            }                            
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error :</strong> Attendance record not found!');
                        }
                        
                        redirect('attendance/manage_attendance/' . $employee_sid . '/' . date('Y_m_d', strtotime($new_datetime)), 'refresh');
                        break;
                    case 'insert_new_break_end':
                        $company_sid                                            = $this->input->post('company_sid');
                        $employee_sid                                           = $this->input->post('employer_sid');
                        $new_datetime                                           = $this->input->post('new_datetime');
                        $attendance_type                                        = $this->input->post('attendance_type');
                        $last_attendance_sid                                    = $this->input->post('last_attendance_sid');
                        $date_time                                              = explode(' ', $new_datetime);
                        $date                                                   = explode('/', $date_time[0]);
                        $time                                                   = explode(':', $date_time[1]);
                        date_default_timezone_set($company_timezone);
                        $datetime_unix                                          = mktime($time[0], $time[1], 0, $date[0], $date[1], $date[2]);
                        $new_datetime                                           = date('Y-m-d H:i:s', $datetime_unix);
                        $verify_manual_entry                                    = $this->attendance_model->verify_manual_entry($company_sid, $employee_sid, $new_datetime, $attendance_type);
                        
                        if(!empty($verify_manual_entry)){ // clockin sid found perform next test
                            $clock_in_sid                                       = $verify_manual_entry['clock_in_sid'];
                            $clock_in_datatime                                  = $verify_manual_entry['datatime'];
                            
                            if($new_datetime >= $clock_in_datatime){ // check if break start is within clocked in time
                                $data_to_insert                                 = array();
                                $data_to_insert['company_sid']                  = $company_sid;
                                $data_to_insert['employer_sid']                 = $employee_sid;
                                $data_to_insert['attendance_type']              = $attendance_type;
                                $data_to_insert['attendance_date']              = $new_datetime;
                                $data_to_insert['ip_address']                   = getUserIP();
                                $data_to_insert['user_agent']                   = $_SERVER['HTTP_USER_AGENT'];
                                $data_to_insert['created_by']                   = $employer_sid;
                                $data_to_insert['clock_in_sid']                 = $clock_in_sid;
                                date_default_timezone_set($company_timezone);
                                $data_to_insert['attendance_year']              = intval(date('Y', $datetime_unix));
                                date_default_timezone_set($company_timezone);
                                $data_to_insert['attendance_week']              = intval(date('W', $datetime_unix));
                                $data_to_insert['is_manual']                    = 1;
                                $data_to_insert['timezone']                     = $company_timezone;
                                $data_to_insert['last_attendance_sid']          = $last_attendance_sid;
                                $data_to_insert['attendance_status']            = 'break_hours';
                                $this->attendance_model->insert_attendance_record($data_to_insert);
                                $this->insert_attendance_total_record($company_sid, $employee_sid, $new_datetime);
                                date_default_timezone_set($company_timezone);
                                $this->session->set_flashdata('message', '<strong>Success :</strong> Manual break-end added to system successfully!');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error :</strong> Incorrect manual break-end time!');
                            }                            
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error :</strong> Attendance record not found!');
                        }
                        
                        redirect('attendance/manage_attendance/' . $employee_sid . '/' . date('Y_m_d', strtotime($new_datetime)), 'refresh');
                        break;
                    case 'delete_attendance_record':
                        $attendance_sid = $this->input->post('attendance_sid');
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $attendance_date = $this->input->post('attendance_date');
                        $this->attendance_model->delete_attendance_record($attendance_sid, $company_sid, $employer_sid);
                        $this->insert_attendance_total_record($company_sid, $employer_sid, $attendance_date);
                        $this->session->set_flashdata('message', '<strong>Success :</strong> Attendance Record Deleted!');
                        $new_datetime = '';
                        redirect('attendance/manage_attendance/' . $employer_sid . '/' . date('Y_m_d', strtotime($attendance_date)), 'refresh');
                        break;
                    default:
                        $company_sid = $this->input->post('company_sid');
                        $employee_sid = $this->input->post('employer_sid');
                        $attendance_sid = $this->input->post('attendance_sid');
                        $new_datetime = $this->input->post('new_datetime');
                        $attendance_type = $this->input->post('attendance_type');
                        $clock_in_sid = $this->input->post('clock_in_sid');
                        $clock_out_sid = $this->input->post('clock_out_sid');
                        $date_time = explode(' ', $new_datetime);
                        $date = explode('/', $date_time[0]);
                        $time = explode(':', $date_time[1]);
                        $datetime_unix = mktime($time[0], $time[1], 0, $date[0], $date[1], $date[2]);
                        $new_datetime = date('Y-m-d H:i:s', $datetime_unix);
                        $invalid_date = false;
                        $this->attendance_model->update_attendance_record($company_sid, $employee_sid, $attendance_sid, $employer_sid, $company_timezone, $new_datetime);
                        $this->insert_attendance_total_record($company_sid, $employee_sid, $new_datetime);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Attendance Record Updated!');
                        redirect('attendance/manage_attendance/' . $employee_sid . '/' . date('Y_m_d', strtotime($new_datetime)), 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    private function filter_out_array_based_on_value($data_array, $field_name, $value) {
        $return_array = array();
        if (!empty($data_array)) {
            foreach ($data_array as $key => $row) {
                if (isset($row[$field_name])) {
                    $row_val = $row[$field_name];

                    if ($row_val == $value) {
                        $return_array[] = $row;
                    }
                }
            }
        }

        return $return_array;
    }
    
    function all_weeks_of_year($today, $year, $company_timezone){
        $weeks                                                                  = array(); //Get Weeks

        for ($count = 1; $count <= 52; $count++) {
            $my_date                                                            = new DateTime();
            $tz                                                                 = new DateTimeZone($company_timezone);
            $my_date->setTimezone($tz);
            $my_date->setISODate($year, $count);
            $current_week_day                                                   = intval($today->format('w'));
            $first_day                                                          = clone $my_date;
            $last_day                                                           = clone $my_date;

            if ($current_week_day !== '0') {
                $first_day->modify('last sunday');
            }

            if ($current_week_day !== '6') {
                $last_day->modify('next saturday');
            }

            $week_start                                                         = $first_day->format('m/d/Y');
            $week_end                                                           = $last_day->format('m/d/Y');
            $weeks[$count]                                                      = str_pad($count, 2, 0, STR_PAD_LEFT) . ' ( ' . $week_start . ' - ' . $week_end . ' ) ';
        }
        
        return $weeks;
    }
    
    function perform_additional_checks_for_manual_breaks($data, $attendance_type, $new_datetime){
//        echo '<br> attendance_type: '.$attendance_type;
//        echo '<br> new_datetime: '.$new_datetime;
//        echo '<pre>';
//        print_r($data);
        $manual_date                                                            = date_create($new_datetime);
        $all_records                                                            = $data['all_records'];
        $new_data                                                               = array();
        
        for($i=0; $i<count($all_records); $i++){
                $db_attendance_type = $all_records[$i]['attendance_type'];
                $clock_in_datatime = $all_records[$i]['attendance_date'];
                $baseDate = date_create($clock_in_datatime); // verify the break that manager wants to add is correct in terms of login status.
                
                //if($db_attendance_type == 'break_start' || $db_attendance_type == 'break_end'){
                if($db_attendance_type == $attendance_type){
                    $interval = date_diff($baseDate, $manual_date);
                    $new_data[$interval->format('%s')] = array   (   'data' => $all_records[$i],
                                                                     'break_status' =>$all_records[$i]['attendance_type']
                                                                 );
                }
                 
            } 
            ksort($new_data);
//            echo '<hr>';    
//            print_r($new_data);  
        //exit;
    }

}