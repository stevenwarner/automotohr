<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron_courses_csv_report extends CI_Controller
{

    public function index()
    {

        $this->load->model('v1/course_model');
        // Get CSV report setting 
        $csv_report_settings = $this->course_model->get_courses_csv_report_settings();

        if (!empty($csv_report_settings)) {
            //Get current day,month and time
            $current_day =  (int) date("d");
            $current_month = (int) date('m');
            $current_time = date('H:i A');
            // 
            foreach ($csv_report_settings as $settings_row) {

                $custom_type = $settings_row['custom_type'];
                $custom_date = $settings_row['custom_date'];
                $custom_day =  $settings_row['custom_day'];
                $custom_time = $settings_row['custom_time'];
                $sender_list = explode(',', $settings_row['sender_list']);
                $company_sid = $settings_row['company_sid'];
             
                if ($custom_date != '') {
                    $custom_month_day = explode('/', $custom_date);
                }

                //  check cron type
                if ($custom_type == 'daily') {
                    if ($current_time == $custom_time) {
                        $this->generate_csv($company_sid,$sender_list);
                    }
                }

                if ($custom_type == 'weekly') {
                    if ($current_day == $custom_day  && $current_time == $custom_time) {
                        $this->generate_csv($company_sid,$sender_list);
                    }
                }

                if ($custom_type == 'monthly') {
                    if ($current_month == (int) $custom_date  && $current_time == $custom_time) {
                        $this->generate_csv($company_sid, $sender_list);
                    }
                }

                if ($custom_type == 'yearly') {
                    if ((int) $custom_month_day[0] == $current_month  && (int) $custom_month_day[1] == $current_day && $current_time == $custom_time) {
                        $this->generate_csv($company_sid, $sender_list);
                    }
                }

            }
        }
    }

    // Generate CSV and Send email;
    public function generate_csv($company_sid, $sender_list)
    {

        $employeeCoursesData = $this->course_model->getEmployeeCourseDataForCSV($company_sid);

        $export_data = array();
        $i = 0;
        $rows = '';

        if (!empty($employeeCoursesData)) {
            foreach ($employeeCoursesData as $key => $employee) {
                $export_data[$i]['sid'] =  $employee['sid'];
                $export_data[$i]['employee_number'] =  $employee['employee_number'];
                $export_data[$i]['ssn'] =  $employee['ssn'];
                $export_data[$i]['email'] =  $employee['email'];
                $export_data[$i]['PhoneNumber'] =  $employee['PhoneNumber'];
                $export_data[$i]['course_title'] =  $employee['course_title'];
                $export_data[$i]['lesson_status'] =  $employee['lesson_status'];
                $export_data[$i]['course_status'] =  $employee['course_status'] != '' ? $employee['course_status'] : ' ';
                $export_data[$i]['course_type'] =  $employee['course_type'];
                $export_data[$i]['course_taken_count'] =  $employee['course_taken_count'];
                $export_data[$i]['course_start_period'] =  $employee['course_start_period'];
                $export_data[$i]['course_end_period'] =  $employee['course_end_period'];

                $header = '';
                //
                $row = '';
                foreach ($export_data[$i] as $key => $value) {
                    $row .= $value . ',';
                    substr($row, 0, -1);
                }
                $rows .= $row . PHP_EOL;
                $i++;
            }

            $header_row = 'Employee Id,Employee Number,Employee SSN,Employee Email,Phone Number,Course Title,Lesson Status,Course Status,Course Type,Course Taken Count,Course Start Date,Course End Date' . $header;
            $file_content = '';
            $file_content .= $header_row . PHP_EOL;
            $file_content .= $rows;
            //
            $company_name =  getCompanyNameBySid($company_sid);

            $subject = 'Employees Pending Courses Report';

            //
            $file_name = str_replace(' ', '_', $subject);
            $file_name = $file_name . '_' . date('Y_m_d-H:i:s') . '.csv';

            $dir = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'csv';

            if (!is_dir($dir)) mkdir($dir, 0777, true);
            $temp_file_path = $dir . '/' . $file_name;

            if (file_exists($temp_file_path)) {
                unlink($temp_file_path);
            }

            $buffer = $header_row . PHP_EOL;
            $buffer = $buffer . $rows;

            $fd = fopen($temp_file_path, "w");
            fputs($fd, $buffer);
            fclose($fd);

            //  
            require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
            $email = new PHPMailer;
            $email->AddAttachment($temp_file_path, str_replace(' ', '_', $subject) . '.csv');
            $email->FromName = "AutoMotoHR";
            $email->addReplyTo(NULL);
            $email->CharSet = 'UTF-8';
            $email->isHTML(true);
            $email->From =  'events@automotohr.com';
            $email->Subject   = $subject;

            foreach ($sender_list as $employee_sid) {

                $employee_info = get_employee_profile_info($employee_sid);
                $to = $employee_info['email'];
                $name = $employee_info['first_name'] . ' ' . $employee_info['last_name'];
                $message_date = date('Y-m-d H:i:s');

                $hf = message_header_footer_domain($company_sid, $company_name);

                $body = $hf['header']
                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                    . '<br><br>'
                    . 'Here is the report of employees have pending courses in CSV formate attached with this email.'
                    . '<br><br><b>'
                    . 'Date:</b> '
                    . $message_date
                    . '<br><a href="'.base_url('assets/csv/'.$file_name).'" >Download</a><br><b>'               
                    . $hf['footer'];

                $email->Body = $body;
                $email->addAddress($to);
                $email->send();
            }
        } else {
        }
    }
}
