<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron_csv_report extends CI_Controller
{

    public function index()
    {

        $this->load->model('export_csv_model');
        // Get CSV report setting 
        $csv_report_settings = $this->export_csv_model->get_employee_csv_report_settings();

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
                $status = $settings_row['status'];

                $access_level = $settings_row['employee_type'];
                $company_sid = $settings_row['company_sid'];
                $status = $settings_row['employee_status'];
                $checked_boxes = explode(',', $settings_row['selected_columns']);

                $start_time = '';
                $end_time = '';



                //
                if ($status == "new_hires") {
                    $start_time = date('Y-m-01 00:00:00');
                    $end_time = date('Y-m-t 23:59:59');
                }

                if ($custom_date != '') {
                    $custom_month_day = explode('/', $custom_date);
                }

                //  check cron type

                $this->generate_csv($company_sid, $access_level, $status, $start_time, $end_time, $sender_list, $checked_boxes);


                if ($custom_type == 'daily') {
                    if ($current_time == $custom_time) {
                        //call generte csv and send mail
                        $this->generate_csv($company_sid, $access_level, $status, $start_time, $end_time, $sender_list, $checked_boxes);
                    }
                }

                if ($custom_type == 'weekly') {
                    if ($current_day == $custom_day  && $current_time == $custom_time) {
                        $this->generate_csv($company_sid, $access_level, $status, $start_time, $end_time, $sender_list, $checked_boxes);
                    }
                }

                if ($custom_type == 'monthly') {
                    if ($current_month == (int) $custom_date  && $current_time == $custom_time) {
                        $this->generate_csv($company_sid, $access_level, $status, $start_time, $end_time, $sender_list, $checked_boxes);
                    }
                }

                if ($custom_type == 'yearly') {
                    if ((int) $custom_month_day[0] == $current_month  && (int) $custom_month_day[1] == $current_day && $current_time == $custom_time) {
                        $this->generate_csv($company_sid, $access_level, $status, $start_time, $end_time, $sender_list, $checked_boxes);
                    }
                }

                //  $this->generate_csv($company_sid, $access_level, $status, $start_time, $end_time, $sender_list,$checked_boxes);
                // die('d');
            }
        }
    }

    // Generate CSV and Send email;
    public function generate_csv($company_sid, $access_level, $status, $start_time, $end_time, $sender_list, $checked_boxes)
    {
        // Get employees data for csv

        $employees = $this->export_csv_model->get_all_employees_from_DB($company_sid, $access_level, $status, $start_time, $end_time);

        $export_data = array();
        $i = 0;
        $rows = '';

        if (!empty($employees)) {
            foreach ($employees as $key => $employee) {

                if ($employee['department_sid'] != 0) {
                    $department_name = $this->export_csv_model->get_department_name($employee['department_sid']);
                    $employee['department_sid'] = $department_name;
                } else {
                    $employee['department_sid'] = '';
                }

                if ($employee['team_sid'] != 0) {
                    $team_name = $this->export_csv_model->get_team_name($employee['team_sid']);
                    $employee['team_sid'] = $team_name;
                } else {
                    $employee['team_sid'] = '';
                }

                $extra_info = unserialize($employee['extra_info']);
                $employee['secondary_email'] = isset($extra_info['secondary_email']) ? $extra_info['secondary_email'] : '';
                $employee['secondary_PhoneNumber'] = isset($extra_info['secondary_PhoneNumber']) ? $extra_info['secondary_PhoneNumber'] : '';
                $employee['other_email'] = isset($extra_info['other_email']) ? $extra_info['other_email'] : '';
                $employee['other_PhoneNumber'] = isset($extra_info['other_PhoneNumber']) ? $extra_info['other_PhoneNumber'] : '';
                $employee['title'] = isset($extra_info['title']) ? $extra_info['title'] : '';
                $employee['office_location'] = isset($extra_info['office_location']) ? $extra_info['office_location'] : '';
                $export_data[$i]['first_name'] =  $employee['first_name'];  // good to go
                $export_data[$i]['last_name'] =  $employee['last_name'];   // good to go
                $export_data[$i]['email'] =  $employee['email'];          // good to go
                $export_data[$i]['phone_number'] =  $employee['PhoneNumber'];    // good to go
                $export_data[$i]['address'] =  str_replace(',', ' ', $employee['Location_Address']); // Making sure excel don't split address // good to go
                $export_data[$i]['city'] =  $employee['Location_City']; // good to go
                $export_data[$i]['zipcode'] =  $employee['Location_ZipCode']; // good to go
                $state_sid = $employee['Location_State']; // good to go
                $country_sid = $employee['Location_Country']; // good to go

                if ($state_sid > 0) {
                    $state_info = db_get_state_name($state_sid);
                    $export_data[$i]['state'] =  $state_info['state_name'];
                    $export_data[$i]['country'] =  $state_info['country_name'];
                } else {
                    $export_data[$i]['state'] =  '';

                    if ($country_sid > 0) {
                        if ($country_sid == 227) {
                            $export_data[$i]['country'] =  'United States';
                        } else {
                            $export_data[$i]['country'] =  'Canada';
                        }
                    } else {
                        $export_data[$i]['country'] =  '';
                    }
                }

                if (!empty($employee['profile_picture'])) {
                    $export_data[$i]['pictures'] = AWS_S3_BUCKET_URL . $employee['profile_picture']; // good to go
                } else {
                    $export_data[$i]['pictures'] = '';
                }

                if ($employee['is_executive_admin'] == 1) {
                    $export_data[$i]['access_level'] = 'Executive Admin';
                } else {
                    $export_data[$i]['access_level'] = $employee['access_level'];
                }
                $export_data[$i]['job_title'] = $employee['job_title'];
                if ($employee['active'] == 1) {
                    $export_data[$i]['status'] = 'Active Employee';
                } else {
                    $export_data[$i]['status'] = 'Archived Employee';
                }
                $header = '';
                //      if (($access_level_plus || $pay_plan_flag == 1) && !empty($checked_boxes)  && !empty($checked_boxes[0])) {

                if (!empty($checked_boxes)  && !empty($checked_boxes[0])) {

                    foreach ($checked_boxes as $key => $value) {
                        $header .= $value . ',';
                        if ($value == "terminated_date" || $value == "terminated_reason") {
                            $terminated_data = $this->export_csv_model->get_status_info($employee['sid'], 1);
                            //
                            if (!empty($terminated_data)) {
                                switch ($value) {
                                    case "terminated_date":
                                        $terminated_date = $terminated_data['termination_date'];
                                        $export_data[$i][$value] = $terminated_date;
                                        break;
                                    case "terminated_reason":
                                        $reason = $terminated_data['termination_reason'];
                                        $terminated_reason = '';
                                        if ($reason == 1) {
                                            $terminated_reason = 'Resignation';
                                        } else if ($reason == 2) {
                                            $terminated_reason = 'Tenure Completed';
                                        } else if ($reason == 3) {
                                            $terminated_reason = 'Fired';
                                        }
                                        //
                                        $export_data[$i][$value] = $terminated_reason;
                                        //
                                        break;
                                }
                            } else {
                                $export_data[$i][$value] = '';
                            }
                        } else {
                            $export_data[$i][$value] = $employee[$value];
                        }
                    }
                    $header = ',' . substr($header, 0, -1);
                }
                //

                $row = '';
                foreach ($export_data[$i] as $key => $value) {
                    $row .= $value . ',';
                    substr($row, 0, -1);
                }
                $rows .= $row . PHP_EOL;

                $i++;
            }

            if (in_array('approvers', $checked_boxes)) {

                // $rows .= "Approvers" . PHP_EOL;
                $this->load->model('department_management_model');
                $approversData = $this->department_management_model->get_all_departments($company_sid);

                $rows .= PHP_EOL . PHP_EOL . "Approvers" . PHP_EOL;

                foreach ($approversData as $department) {
                    if (!empty($department['approvers']) || !empty($department['supervisor'])) {

                        $rows .= PHP_EOL . "Department," . $department['name'] . PHP_EOL;

                        $a = explode(',', $department['approvers']);
                        $s = explode(',', $department['supervisor']);

                        if (!empty($a)) {
                            $rows .=  "Approvers" . PHP_EOL;
                        }

                        foreach ($a as $af) {
                            $approverDetails = db_get_employee_profile($af)[0];
                            if (!empty($approverDetails)) {
                                $rows .= $approverDetails['first_name'] . ' ' . $approverDetails['missle_name'] . ' ' . $approverDetails['last_name'] . PHP_EOL;
                            }
                        }

                        if (!empty($s)) {
                            $rows .= PHP_EOL . "Supervisors" . PHP_EOL;
                        }

                        foreach ($s as $sf) {
                            $supervisorsDetails = db_get_employee_profile($sf)[0];

                            if (!empty($supervisorsDetails)) {
                                $rows .= $supervisorsDetails['first_name'] . ' ' . $supervisorsDetails['missle_name'] . ' ' . $supervisorsDetails['last_name'] . PHP_EOL;
                            }
                        }
                    }

                    //
                    $teams = $this->department_management_model->get_all_department_teams($company_sid, $department['sid']);

                    if (!empty($teams)) {
                        foreach ($teams as $team) {
                            if (!empty($team['approvers']) || !empty($team['team_lead'])) {
                                $rows .= PHP_EOL . "Team ," . $team['name'] . PHP_EOL;
                            }

                            if (!empty($team['approvers'])) {
                                $ta = explode(',', $team['approvers']);

                                if (!empty($ta)) {
                                    $rows .=  "Approvers" . PHP_EOL;
                                }

                                foreach ($ta as $taf) {
                                    $teamApproversDetails = db_get_employee_profile($taf)[0];
                                    if (!empty($teamApproversDetails)) {
                                        $rows .= $teamApproversDetails['first_name'] . ' ' . $teamApproversDetails['missle_name'] . ' ' . $teamApproversDetails['last_name'] . PHP_EOL;
                                    }
                                }
                            }

                            if (!empty($team['team_lead'])) {
                                $tal = explode(',', $team['team_lead']);
                                if (!empty($tal)) {
                                    $rows .=  PHP_EOL . "Team Leads" . PHP_EOL;
                                }

                                foreach ($tal as $talf) {
                                    $teamLeadsDetails = db_get_employee_profile($talf)[0];
                                    if (!empty($teamLeadsDetails)) {
                                        $rows .= $teamLeadsDetails['first_name'] . ' ' . $teamLeadsDetails['missle_name'] . ' ' . $teamLeadsDetails['last_name'] . PHP_EOL;
                                    }
                                }
                            }
                        }
                    }
                }
            }


            $header_row = 'First Name,Last Name,E-Mail,Primary Number,Street Address,City,Zipcode,State,Country,Profile Picture,Access Level,Job Title,Status' . $header;
            $file_content = '';
            $file_content .= $header_row . PHP_EOL;
            $file_content .= $rows;

            //
            $company_name =  getCompanyNameBySid($company_sid);

            ($status == 'active') ? $subject = 'Active Employees Report' : '';
            ($status == 'archived') ? $subject = 'Archived Employees Report' : '';
            ($status == 'new_hires') ? $subject = 'New Hires Employees Report' : '';
            ($status == 'terminated') ? $subject = 'Terminated Employees Report' : '';
            ($status == 'manual_employee') ? $subject = 'Manual Added Employees Report' : '';
            ($status == 'both') ? $subject = 'All Employees Report' : '';
            ($status == 'all') ? $subject = 'All Employees Report' : '';

            //
            $file_name = str_replace(' ', '_', $subject);
            $file_name = $file_name . '_' . date('Y_m_d-H:i:s') . '.csv';

            $dir = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'csv_reports';


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
            // attach the SMTP creds
            attachSMTPToMailer($email, "events@automotohr.com");

            foreach ($sender_list as $employee_sid) {

                $employee_info = get_employee_profile_info($employee_sid);
                $to = $employee_info['email'];
                $name = $employee_info['first_name'] . ' ' . $employee_info['last_name'];
                $message_date = date('Y-m-d H:i:s');

                $hf = message_header_footer_domain($company_sid, $company_name);

                $downloadLink = '<a href="' . base_url() . 'assets/csv_reports/' . $file_name . '"> Download </a>';

                $body = $hf['header']
                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                    . '<br><br>'
                    . 'Here is the report of "' . str_replace(' Employees Report', '', $subject) . '" employees in CSV formate attached with this email.'
                    . '<br><br><b>'
                    . 'Date:</b> '
                    . $message_date
                    . '<br><br>'
                    . $downloadLink
                    . $hf['footer'];
                $email->Body = $body;
                $email->addAddress($to);
                $email->send();
            }
            //  break;
        } else {
        }
    }
}
