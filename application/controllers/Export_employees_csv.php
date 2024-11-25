<?php defined('BASEPATH') or exit('No direct script access allowed');

class Export_employees_csv extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('path');
        $this->load->model('dashboard_model');
        $this->load->model('export_csv_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'export_employee_csv'); // Param2: Redirect URL, Param3: Function Name
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $data['title'] = 'Export Employees CSV';
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $access_level_plus = $data['session']['employer_detail']['access_level_plus'];
                $pay_plan_flag = $data['session']['employer_detail']['pay_plan_flag'];
                $data["access_levels"] = $this->dashboard_model->get_security_access_levels();
                $data["company_sid"] = $company_sid;
                $data["pay_plan_flag"] = $pay_plan_flag;
                $data["access_level_plus"] = $access_level_plus;
                $data['employeesList'] = $this->export_csv_model->getAllActiveEmployees($company_sid, false);
                $data['csv_report_settings'] = $this->export_csv_model->get_employee_csv_report_settings_bycompany($company_sid, false);
                //print_r($data['csv_report_settings']);


                $this->load->view('main/header', $data);
                $this->load->view('export_employees_csv/index');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'export_employees':

                        $access_level_plus = $data['session']['employer_detail']['access_level_plus'];
                        $pay_plan_flag = $data['session']['employer_detail']['pay_plan_flag'];
                        if (($access_level_plus || $pay_plan_flag == 1)) {
                            $checked_values = $this->input->post("test");
                            $checked_boxes = explode(',', $checked_values);
                        }


                        $access_level = $this->input->post('access_level');
                        $company_sid = $this->input->post('company_sid');
                        $status = $this->input->post('status');
                        $to_date = $this->input->post('to_date');
                        $from_date = $this->input->post('from_date');


                        $start_time = '';
                        $end_time = '';
                        //
                        if (!empty($to_date)) {
                            $start_time = empty($to_date) ? null : DateTime::createFromFormat('m-d-Y', $to_date)->format('Y-m-d 00:00:00');
                        }
                        //
                        if (!empty($from_date)) {
                            $end_time = empty($from_date) ? null : DateTime::createFromFormat('m-d-Y', $from_date)->format('Y-m-d 23:59:59');
                        }
                        //
                        if ($status == "new_hires") {
                            $start_time = date('Y-m-01 00:00:00');
                            $end_time = date('Y-m-t 23:59:59');
                        }

                        //echo $start_time.'#'.$end_time;

                        //
                        // $employees = $this->export_csv_model->get_all_employees($company_sid, $access_level, $status);
                        $employees = $this->export_csv_model->get_all_employees_from_DB($company_sid, $access_level, $status, $start_time, $end_time);

                        $export_data = array();
                        $i = 0;
                        $rows = '';

                        if (!empty($employees)) {
                            $header = [];
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


                                if ($employee['is_executive_admin'] == 1) {
                                    $export_data[$i]['access_level'] = 'Executive Admin';
                                } else {
                                    $export_data[$i]['access_level'] = $employee['access_level'];
                                }
                                $export_data[$i]['job_title'] = $employee['job_title'];
                                if ($employee['active'] == 1) {
                                    $export_data[$i]['status'] = 'Active Employee';
                                } else {
                                    if ($employee['terminated_status'] == 1) {
                                        $export_data[$i]['status'] = 'Terminated';
                                    } else {
                                        $employeeStatus = $this->export_csv_model->get_employee_last_status_info($employee['sid']);
                                        $export_data[$i]['status'] = $employeeStatus;
                                    }
                                }

                                //
                                $approversData = [];
                                if (($access_level_plus || $pay_plan_flag == 1) && !empty($checked_boxes)  && !empty($checked_boxes[0])) {
                                    foreach ($checked_boxes as $key => $value) {
                                        if ($value != "approvers") {
                                            $header[$value] = $value;
                                        }

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
                                                            $terminated_reason = 'Fired';
                                                        } else if ($reason == 3) {
                                                            $terminated_reason = 'Tenure Completed';
                                                        } else if ($reason == 4) {
                                                            $terminated_reason = 'Personal';
                                                        } else if ($reason == 5) {
                                                            $terminated_reason = 'Another Job';
                                                        } else if ($reason == 6) {
                                                            $terminated_reason = 'Problem with Supervisor';
                                                        } else if ($reason == 7) {
                                                            $terminated_reason = 'Relocation';
                                                        } else if ($reason == 8) {
                                                            $terminated_reason = 'Work Schedule';
                                                        } else if ($reason == 9) {
                                                            $terminated_reason = 'Retirement';
                                                        } else if ($reason == 10) {
                                                            $terminated_reason = 'Return to School';
                                                        } else if ($reason == 11) {
                                                            $terminated_reason = 'Pay';
                                                        } else if ($reason == 12) {
                                                            $terminated_reason = 'Without Notice/Reason';
                                                        } else if ($reason == 13) {
                                                            $terminated_reason = 'Involuntary';
                                                        } else if ($reason == 14) {
                                                            $terminated_reason = 'Violating Company Policy';
                                                        } else if ($reason == 15) {
                                                            $terminated_reason = 'Attendance Issues';
                                                        } else if ($reason == 16) {
                                                            $terminated_reason = 'Performance';
                                                        } else if ($reason == 17) {
                                                            $terminated_reason = 'Workforce Reduction';
                                                        } else if ($reason == 18) {
                                                            $terminated_reason = 'Store Closure';
                                                        } else if ($reason == 19) {
                                                            $terminated_reason = 'Did Not Hire';
                                                        } else if ($reason == 20) {
                                                            $terminated_reason = 'Separation';
                                                        }
                                                        //
                                                        $export_data[$i][$value] = $terminated_reason;
                                                        //
                                                        break;
                                                }
                                            } else {
                                                $export_data[$i][$value] = '';
                                            }
                                        } elseif ($value == "drivers_license") {
                                            unset($header['drivers_license']);

                                            //
                                            $drivingData = get_employee_drivers_license($employee['sid']);

                                            $licenseDetails = "";
                                            if (!empty($drivingData)) {


                                                $drivingDataDetails = unserialize($drivingData['license_details']);

                                                $header['license_type'] = 'license_type';
                                                $header['license_authority'] = 'license_authority';
                                                $header['license_class'] = 'license_class';
                                                $header['license_number'] = 'license_number';
                                                $header['license_issue_date'] = 'license_issue_date';
                                                $header['license_expiration_date'] = 'license_expiration_date';
                                                $header['license_indefinite'] = 'license_indefinite';
                                                $header['license_notes'] = 'license_notes';

                                                $export_data[$i]['license_type'] = $drivingDataDetails['license_type'] ?? 'N/A';
                                                $export_data[$i]['license_authority'] = $drivingDataDetails['license_authority'] ?? 'N/A';
                                                $export_data[$i]['license_class'] = $drivingDataDetails['license_class'] ?? 'N/A';
                                                $export_data[$i]['license_number'] = $drivingDataDetails['license_number'] ?? 'N/A';
                                                $export_data[$i]['license_issue_date'] = $drivingDataDetails['license_issue_date'] ?? 'N/A';
                                                $export_data[$i]['license_expiration_date'] = $drivingDataDetails['license_expiration_date'] ?? 'N/A';
                                                $export_data[$i]['license_indefinite'] = $drivingDataDetails['license_indefinite'] ?? 'N/A';
                                                $export_data[$i]['license_notes'] = $drivingDataDetails['license_notes'] ?? 'N/A';
                                            } else {


                                                $export_data[$i]['license_type'] = '';
                                                $header['license_type'] = 'license_type';

                                                $export_data[$i]['license_authority'] = '';
                                                $header['license_authority'] = 'license_authority';

                                                $export_data[$i]['license_class'] = '';
                                                $header['license_class'] = 'license_class';

                                                $export_data[$i]['license_number'] = '';
                                                $header['license_number'] = 'license_number';

                                                $export_data[$i]['license_issue_date'] = '';
                                                $header['license_issue_date'] = 'license_issue_date';

                                                $export_data[$i]['license_expiration_date'] = '';
                                                $header['license_expiration_date'] = 'license_expiration_date';

                                                $export_data[$i]['license_indefinite'] = '';
                                                $header['license_indefinite'] = 'license_indefinite';

                                                $export_data[$i]['license_notes'] = '';
                                                $header['license_notes'] = 'license_notes';
                                            }
                                        } elseif ($value == 'profile_picture') {
                                            if (!empty($employee['profile_picture'])) {
                                                $export_data[$i]['pictures'] = AWS_S3_BUCKET_URL . $employee['profile_picture'];
                                            } else {
                                                $export_data[$i]['pictures'] = '';
                                            }
                                        } elseif ($value == 'union_member') {
                                            $export_data[$i][$value] = $employee['union_member'] == 1 ? 'Yes' : 'No';
                                        } elseif ($value == 'employment_type') {
                                            $export_data[$i][$value] = ucwords(preg_replace("/-_/", " ", $employee["employee_type"]));
                                        } elseif ($value == "approvers") {

                                            $this->load->model('department_management_model');
                                            //_e($company_sid,true,true);
                                            $approversData = $this->department_management_model->get_all_departments($company_sid);


                                            // $approversData=                                       
                                        } else {
                                            $export_data[$i][$value] = str_replace(',', ' ', strip_tags(trim(preg_replace('/\s+/', ' ', $employee[$value]))));
                                        }
                                    }
                                }
                                //
                                //$export_data[$i]['access_level'] =  $employee['access_level'];
                                // good to go
                                $row = '';

                                foreach ($export_data[$i] as $key => $value) {
                                    //
                                    if (DateTime::createFromFormat('Y-m-d', $value) !== false) {
                                        $value = formatDateToDB($value, DB_DATE, SITE_DATE);
                                    }
                                    //
                                    $row .= $value . ',';
                                    substr($row, 0, -1);
                                }
                                $rows .= $row . PHP_EOL;
                                $i++;
                            }
                            //   $header_row = 'First Name,Last Name,E-Mail,Contact Number,Street Address,City,Zipcode,State,Country,Profile Picture,Access Level,Job Title,Status' . (count($header) ? ',' . implode(',', $header) : '');

                            /*
                            <?php if (!empty($session['company_detail']['Logo'])) { ?>
                                <img src="<?php echo 'https://automotohrattachments.s3.amazonaws.com/' . $session['company_detail']['Logo']; ?>" style="width: 75px; height: 75px;" class="img-rounded"><br>
                            <?php } ?>
                            <?php if (!empty($session['company_detail']['CompanyName'])) { ?>
                                <?php echo $session['company_detail']['CompanyName']; ?><br>
                            <?php } ?>
                                */
                            $companyHeader = '';
                            if (!empty($data['session']['company_detail']['CompanyName'])) {
                                $companyHeader = ',,,,' . $data['session']['company_detail']['CompanyName'];
                            }

                            //
                            if (count($header) > 0) {
                                foreach ($header as $key => $item) {
                                    if ($item == "dob") {
                                        $header[$key] = "Date of Birth";
                                    } else if ($item == "ssn") {
                                        $header[$key] = "SSN";
                                    } else if ($item == "eeoc_code") {
                                        $header[$key] = "EEOC Code";
                                    } else {
                                        $header[$key] = ucwords(str_replace("_", " ", $item));
                                    }
                                }
                            }
                            //
                            $header_row = 'First Name,Last Name,E-Mail,Primary Number,Street Address,City,Zipcode,State,Country,Access Level,Job Title,Status' . (count($header) ? ',' . implode(',', $header) : '');

                            $file_content = '';
                            $file_content .= $header_row . PHP_EOL;
                            $file_content .= $rows;
                            $file_size = 0;

                            if (function_exists('mb_strlen')) {
                                $file_size = mb_strlen($file_content, '8bit');
                            } else {
                                $file_size = strlen($file_content);
                            }

                            //  _e($approversData,true,true);

                            header('Pragma: public');     // required
                            header('Expires: 0');         // no cache
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Cache-Control: private', false);
                            header('Content-Type: text/csv');  // Add the mime type from Code igniter.
                            header('Content-Disposition: attachment; filename="employees_' . date('Y_m_d-H:i:s') . '.csv"');  // Add the file name
                            header('Content-Transfer-Encoding: binary');
                            header('Content-Length: ' . $file_size); // provide file size
                            header('Connection: close');
                            echo $companyHeader . PHP_EOL . PHP_EOL;
                            echo $header_row . PHP_EOL;
                            echo $rows;

                            if (!empty($approversData)) {

                                echo PHP_EOL . PHP_EOL . "Approvers" . PHP_EOL;

                                foreach ($approversData as $department) {
                                    if (!empty($department['approvers']) || !empty($department['supervisor'])) {

                                        echo PHP_EOL . "Department," . $department['name'] . PHP_EOL;

                                        $a = explode(',', $department['approvers']);
                                        $s = explode(',', $department['supervisor']);

                                        if(!empty($a)){
                                        echo  "Approvers" . PHP_EOL;
                                        }

                                        foreach ($a as $af) {
                                            $approverDetails = db_get_employee_profile($af)[0];
                                            if (!empty($approverDetails)) {
                                                echo $approverDetails['first_name'] . ' ' . $approverDetails['missle_name'] . ' ' . $approverDetails['last_name'] . PHP_EOL;
                                            }
                                        }

                                        if(!empty($s)){
                                        echo PHP_EOL . "Supervisors" . PHP_EOL;
                                        }

                                        foreach ($s as $sf) {
                                            $supervisorsDetails = db_get_employee_profile($sf)[0];

                                            if (!empty($supervisorsDetails)) {
                                                echo $supervisorsDetails['first_name'] . ' ' . $supervisorsDetails['missle_name'] . ' ' . $supervisorsDetails['last_name'] . PHP_EOL;
                                            }
                                        }
                                    }

                                    //
                                    $teams = $this->department_management_model->get_all_department_teams($company_sid, $department['sid']);

                                    if (!empty($teams)) {
                                        foreach ($teams as $team) {


                                            if (!empty($team['approvers']) || !empty($team['team_lead'])) {
                                                echo PHP_EOL . "Team ," . $team['name'] . PHP_EOL;
                                            }

                                            if (!empty($team['approvers'])) {


                                                $ta = explode(',', $team['approvers']);

                                                if(!empty($ta)){
                                                echo  "Approvers" . PHP_EOL;
                                                }
                                               
                                                foreach ($ta as $taf) {

                                                    $teamApproversDetails = db_get_employee_profile($taf)[0];
                                                    if (!empty($teamApproversDetails)) {

                                                        echo $teamApproversDetails['first_name'] . ' ' . $teamApproversDetails['missle_name'] . ' ' . $teamApproversDetails['last_name'] . PHP_EOL;
                                                    }
                                                }
                                            }


                                            if (!empty($team['team_lead'])) {

                                                $tal = explode(',', $team['team_lead']);

                                                if(!empty($tal)){
                                                echo  PHP_EOL . "Team Leads" . PHP_EOL;
                                                }
                                                
                                                foreach ($tal as $talf) {
                                                    $teamLeadsDetails = db_get_employee_profile($talf)[0];
                                                    if (!empty($teamLeadsDetails)) {
                                                        echo $teamLeadsDetails['first_name'] . ' ' . $teamLeadsDetails['missle_name'] . ' ' . $teamLeadsDetails['last_name'] . PHP_EOL;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            break;
                        } else {
                            $this->session->set_flashdata('message', '<b>Error:</b> Record(s) Not Found!');
                            redirect('export_employees_csv');
                        }


                        /************************************************************************************
                        foreach ($employees as $key => $employee) {
                            $country_sid = $employee['Location_Country'];
                            $state_sid = $employee['Location_State'];
                            $state_info = db_get_state_name($state_sid);
                            $employees[$key]['country'] = $state_info['country_name'];
                            $employees[$key]['state'] = $state_info['state_name'];
                            $employees[$key]['city'] = $employee['Location_City'];
                            $employees[$key]['address'] = $employee['Location_Address'];
                            $employees[$key]['zipcode'] = $employee['Location_ZipCode'];
                            $employees[$key]['phone'] = $employee['PhoneNumber'];

                            if (!empty($employee['profile_picture'])) {
                                $employees[$key]['profile_picture'] = AWS_S3_BUCKET_URL . $employee['profile_picture'];
                            }

                            unset($employees[$key]['Location_Country']);
                            unset($employees[$key]['Location_State']);
                            unset($employees[$key]['Location_City']);
                            unset($employees[$key]['Location_Address']);
                            unset($employees[$key]['Location_ZipCode']);
                            unset($employees[$key]['PhoneNumber']);
                            ksort($employees[$key], SORT_STRING);
                        }

                        $headers = array();
                        $rows = '';
                                                
                        if (!empty($employees)) {
                            foreach ($employees as $employee) {
                                $headers = array_keys($employee);
                                $row = '';
                                
                                foreach ($headers as $header) {
                                    $row .= trim(str_replace(',', ' ', $employee[$header])) . ',';
                                }

                                $rows .= $row . PHP_EOL;
                            }
                        }

                        $header_row = implode(',', $headers);
                        $file_content = '';
                        $file_content .= $header_row . ',' . PHP_EOL;
                        $file_content .= $rows;
                        $file_size = 0;

                        if (function_exists('mb_strlen')) {
                            $file_size = mb_strlen($file_content, '8bit');
                        } else {
                            $file_size = strlen($file_content);
                        }

                        header('Pragma: public');     // required
                        header('Expires: 0');         // no cache
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header('Content-Type: text/csv');  // Add the mime type from Code igniter.
                        header('Content-Disposition: attachment; filename="employees_' . date('Y_m_d') . '.csv"');  // Add the file name
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . $file_size); // provide file size
                        header('Connection: close');

                        echo $header_row . ',' . PHP_EOL;
                        echo $rows;
                        break; */

                    case 'save_report_setting':
                        //  _e($_POST,true,true);
                        //
                        $employer_sid = $data['session']['employer_detail']['sid'];
                        $data_to_insert['company_sid'] = $this->input->post('company_sid');
                        $access_level = explode(',', $this->input->post('access_level'));

                        if ($access_level[0] == 'all') {
                            $data_to_insert['employee_type'] = 'all';
                        } else {
                            $data_to_insert['employee_type'] = $this->input->post('access_level');
                        }

                        $data_to_insert['employee_status'] = $this->input->post('status');
                        $data_to_insert['custom_type'] = $this->input->post('assignAndSendDocument');
                        $data_to_insert['custom_date'] = $this->input->post('assignAndSendCustomDate');
                        $data_to_insert['custom_day'] = $this->input->post('assignAndSendCustomDay');
                        $data_to_insert['custom_time'] = $this->input->post('assignAndSendCustomTime');
                        if ($this->input->post('report_all_columns') == 1) {
                            $data_to_insert['selected_columns'] = 'all';
                        } else {
                            $data_to_insert['selected_columns'] = $this->input->post('test');
                        }
                        $sender_list = $this->input->post('assignAdnSendSelectedEmployees');
                        if ($sender_list[0] == '-1') {
                            $data_to_insert['sender_list'] = 'all';
                        } else {
                            $data_to_insert['sender_list'] = !empty($this->input->post('assignAdnSendSelectedEmployees')) ? implode(',', $this->input->post('assignAdnSendSelectedEmployees')) : '';
                        }
                        //  $data_to_insert['sender_list'] = !empty($this->input->post('assignAdnSendSelectedEmployees')) ? implode(',',$this->input->post('assignAdnSendSelectedEmployees')) : '';

                        $data_to_insert['created_at'] = date('Y-m-d H:i:s');
                        $data_to_insert['created_by'] = $employer_sid;
                        $to_date = $this->input->post('to_date');
                        $from_date =  $this->input->post('from_date');

                        if (!empty($to_date)) {
                            $to_date = empty($to_date) ? null : DateTime::createFromFormat('m-d-Y', $to_date)->format('Y-m-d 00:00:00');
                        }
                        //
                        if (!empty($from_date)) {
                            $from_date = empty($from_date) ? null : DateTime::createFromFormat('m-d-Y', $from_date)->format('Y-m-d 23:59:59');
                        }

                        $data_to_insert['to_date'] = $to_date;
                        $data_to_insert['from_date'] = $from_date;
                        $this->export_csv_model->save_employee_csv_report_settings($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success</strong> CSV Report Settings Saved Successfully');
                        redirect('export_employees_csv', 'refresh');

                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }


    public function report_setting_remove()
    {
        $sid = $this->input->post('sid');
        $data = array('status' => 0);
        $this->export_csv_model->csv_report_settings_delete($sid, $data);
    }
}
