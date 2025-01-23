<?php

use function Aws\filter;

defined('BASEPATH') || exit('No direct script access allowed');

class Reporting extends CI_Controller
{
    //
    private $data;
    public function __construct()
    {
        parent::__construct();
        // check if logged in
        if (!$this->session->userdata('executive_loggedin')) {
            return redirect(base_url('login'), "refresh");
        }
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->model('reporting_model');
        //
        $this->data = $this->session->userdata('executive_loggedin');
    }


    public function all()
    {
        $this->data['page_title'] = 'Executive Admin Reports';

        $this->load->view('main/header', $this->data);
        $this->load->view('reporting/listing');
        $this->load->view('main/footer');
    }

    public function exportEmployees()
    {
        // set filter
        $this->data["filter"] = [
            "companies" => $this->input->get("companies", true) ?? ["all"],
            "access_level" => $this->input->get("access_level", true) ?? ["all"],
            "status" => $this->input->get("status", true) ?? ["all"],
            "columns" => $this->input->get("columns", true) ?? "",
            "from_date" => $this->input->get("from_date", true) ?? null,
            "to_date" => $this->input->get("to_date", true) ?? null,
            "export" => $this->input->get("export", true) ?? false
        ];

        $this->data["companies"] =
            $this
            ->reporting_model
            ->getCompaniesList(
                $this->data["executive_user"]["sid"]
            );
        if ($this->data["filter"]["export"]) {
            return $this->export($this->data["filter"]);
        }
        // get companies with access level plus
        // get access levels
        $this->data["access_levels"] = $this
            ->reporting_model
            ->get_security_access_levels();
        $this->data['page_title'] = 'Export Employees :: Executive Admin';
        $this->load->view('main/header', $this->data);
        if ($this->data["companies"]) {
            $this->load->view('reporting/export_employees');
        } else {
            $this->load->view('reporting/export_employees_empty');
        }
        $this->load->view('main/footer');
    }

    private function export($filter)
    {
        //
        extract($filter);
        // when all is selected
        $companies = in_array("all", $companies)
            ? array_column(
                $this->data["companies"],
                "parent_sid"
            )
            : $companies;
        //
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
        //
        $export_data = array();
        $i = 0;
        $rows = "";
        //
        $columnsToBeIncluded = [];

        if ($columns) {
            //
            $tmpColumns = explode(",", $columns);
            //
            foreach ($tmpColumns as $v0) {
                $columnsToBeIncluded[$v0] = $v0;
            }
        }
        //
        foreach ($companies as $company_sid) {
            // get the employees based on the filter 
            $employees = $this
                ->reporting_model
                ->get_all_employees_from_DB(
                    $company_sid,
                    $access_level,
                    $status,
                    $start_time,
                    $end_time
                );
            //
            if (!$employees) {
                continue;
            }

            // loop through employees
            foreach ($employees as $key => $employee) {
                // add department
                if ($employee['department_sid'] != 0) {
                    $department_name = $this->reporting_model->get_department_name($employee['department_sid']);
                    $employee['department_sid'] = $department_name;
                } else {
                    $employee['department_sid'] = '';
                }
                // add team
                if ($employee['team_sid'] != 0) {
                    $team_name = $this->reporting_model->get_team_name($employee['team_sid']);
                    $employee['team_sid'] = $team_name;
                } else {
                    $employee['team_sid'] = '';
                }

                //
                $employee['secondary_email'] = isset($extra_info['secondary_email']) ? $extra_info['secondary_email'] : '';
                $employee['secondary_PhoneNumber'] = isset($extra_info['secondary_PhoneNumber']) ? $extra_info['secondary_PhoneNumber'] : '';
                $employee['other_email'] = isset($extra_info['other_email']) ? $extra_info['other_email'] : '';
                $employee['other_PhoneNumber'] = isset($extra_info['other_PhoneNumber']) ? $extra_info['other_PhoneNumber'] : '';
                $employee['title'] = isset($extra_info['title']) ? $extra_info['title'] : '';
                $employee['office_location'] = isset($extra_info['office_location']) ? $extra_info['office_location'] : '';
                $export_data[$i]['company_name'] =  $this
                    ->reporting_model
                    ->getCompanyName(
                        $company_sid
                    );
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
                //
                $export_data[$i]['job_title'] = $employee['job_title'];

                if ($employee['is_executive_admin'] == 1) {
                    $export_data[$i]['access_level'] = 'Executive Admin';
                } else {
                    $export_data[$i]['access_level'] = $employee['access_level'];
                }

                if ($employee['active'] == 1) {
                    $export_data[$i]['status'] = 'Active Employee';
                } else {
                    if ($employee['terminated_status'] == 1) {
                        $export_data[$i]['status'] = 'Terminated';
                    } else {
                        $employeeStatus = $this
                            ->reporting_model
                            ->get_employee_last_status_info(
                                $employee['sid']
                            );
                        $export_data[$i]['status'] = $employeeStatus;
                    }
                }
                //
                $approversData = [];
                //
                if ($columnsToBeIncluded) {
                    foreach ($columnsToBeIncluded as $k1 => $value) {
                        if (
                            $value != "approvers"
                        ) {
                            $columnsToBeIncluded[$value] = $value;
                        }

                        if (
                            $value == "terminated_date" || $value == "terminated_reason"
                        ) {
                            $terminated_data = $this->reporting_model->get_status_info($employee['sid'], 1);
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
                            unset($columnsToBeIncluded['drivers_license']);

                            //
                            $drivingData = get_employee_drivers_license($employee['sid']);

                            $licenseDetails = "";
                            if (!empty($drivingData)) {
                                $drivingDataDetails = unserialize($drivingData['license_details']);

                                $columnsToBeIncluded['license_type'] = 'license_type';
                                $columnsToBeIncluded['license_authority'] = 'license_authority';
                                $columnsToBeIncluded['license_class'] = 'license_class';
                                $columnsToBeIncluded['license_number'] = 'license_number';
                                $columnsToBeIncluded['license_issue_date'] = 'license_issue_date';
                                $columnsToBeIncluded['license_expiration_date'] = 'license_expiration_date';
                                $columnsToBeIncluded['license_indefinite'] = 'license_indefinite';
                                $columnsToBeIncluded['license_notes'] = 'license_notes';

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
                                $columnsToBeIncluded['license_type'] = 'license_type';

                                $export_data[$i]['license_authority'] = '';
                                $columnsToBeIncluded['license_authority'] = 'license_authority';

                                $export_data[$i]['license_class'] = '';
                                $columnsToBeIncluded['license_class'] = 'license_class';

                                $export_data[$i]['license_number'] = '';
                                $columnsToBeIncluded['license_number'] = 'license_number';

                                $export_data[$i]['license_issue_date'] = '';
                                $columnsToBeIncluded['license_issue_date'] = 'license_issue_date';

                                $export_data[$i]['license_expiration_date'] = '';
                                $columnsToBeIncluded['license_expiration_date'] = 'license_expiration_date';

                                $export_data[$i]['license_indefinite'] = '';
                                $columnsToBeIncluded['license_indefinite'] = 'license_indefinite';

                                $export_data[$i]['license_notes'] = '';
                                $columnsToBeIncluded['license_notes'] = 'license_notes';
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
                            $approversData = $this->reporting_model->get_all_departments($company_sid);
                        } else {
                            $export_data[$i][$value] = str_replace(',', ' ', strip_tags(trim(preg_replace('/\s+/', ' ', $employee[$value]))));
                        }
                    }
                }
                //
                $rows .= implode(",", $export_data[$i]) . PHP_EOL;
                //
                $i++;
            }
           
        }
        //
        $columnsToBeAdded = array_keys(
            $export_data[0]
        );
        //
        if ($columnsToBeAdded) {
            foreach ($columnsToBeAdded as $key => $item) {
                if ($item == "dob") {
                    $columnsToBeAdded[$key] = "Date of Birth";
                } elseif ($item == "ssn") {
                    $columnsToBeAdded[$key] = "SSN";
                } elseif ($item == "eeoc_code") {
                    $columnsToBeAdded[$key] = "EEOC Code";
                } else {
                    $columnsToBeAdded[$key] = ucwords(str_replace("_", " ", $item));
                }
            }
        }
        // set the header
        $headerContent =
            implode(",", $columnsToBeAdded) . PHP_EOL;
        // set teh file content
        $fileContent = "";
        $fileContent .= $headerContent;
        $fileContent .= $rows;
        $fileSize = function_exists("mb_strlen")
            ?  mb_strlen($file_content, '8bit')
            : strlen($rows);
        //
        header('Pragma: public'); // required
        header('Expires: 0'); // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: text/csv'); // Add the mime type from Code igniter.
        header('Content-Disposition: attachment; filename="employees_' . date('Y_m_d-H:i:s') . '.csv"'); // Add the file name
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $fileSize); // provide file size
        header('Connection: close');
        echo $fileContent;
        exit(1);
    }
}
