<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Export_applicants_csv extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('path');
        //$this->load->model('dashboard_model');
        $this->load->model('export_csv_model');
    }

    public function index($keyword = 'all', $job_sid = 'all', $applicant_type = 'all', $applicant_status = 'all', $start_date = 'all', $end_date = 'all', $page_number = 1) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'export_applicant_csv'); // Param2: Redirect URL, Param3: Function Name
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');


            $data['title'] = 'Export Applicants CSV';
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;

            $keyword = urldecode($keyword);
            $job_sid = urldecode($job_sid);
            $applicant_type = urldecode($applicant_type);
            $applicant_status = urldecode($applicant_status);
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);

            if(!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if(!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_applied = date('Y-m-d 23:59:59');
            }

            if ($job_sid != null || $job_sid != 'all') {
                $data['job_sid_array']                                          = explode(',', $job_sid);
            }

            $allJobs = $this->export_csv_model->GetAllJobsCompanySpecific($company_sid);
            $applicant_statuses = $this->export_csv_model->get_company_statuses($company_sid);
            $data['applicant_statuses'] = $applicant_statuses;

            foreach ($allJobs as $job) {
                $active = ' (In Active) ';
                if($job['active']){
                    $active = ' (Active) ';
                }
                $jobOptions[$job['sid']] = $job['Title'] . $active;

            }
            $data['company_sid'] = $company_sid;
            $data['jobOptions'] = $jobOptions;
//            $applicant_types = array();
//            $applicant_types[] = 'Applicant';
//            $applicant_types[] = 'Talent Network';
//            $applicant_types[] = 'Manual Candidate';
//            $applicant_types[] = 'Job Fair';
//            $applicant_types[] = 'Re-Assigned Candidates';
            $applicant_types = explode(',', APPLICANT_TYPE_ATS);
            $data['applicant_types'] = $applicant_types;

            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'export_applicants':
                    $applicant_type = $this->input->post('applicant_type');
                    $keyword = $this->input->post('keyword');
                    $job_sid = $this->input->post('job_sid');
                    $applicant_type = $this->input->post('applicant_type');
                    $applicant_status = $this->input->post('applicant_status');
                    $start_date = $this->input->post('start_date_applied');
                    $end_date = $this->input->post('end_date_applied');
                    $company_sid = $this->input->post('company_sid');
                    if($job_sid==''){
                        $job_sid = array(0 => 'all');
                    }

                    if(!empty($start_date) && $start_date != 'all') {
                        $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
                    } else {
                        $start_date_applied = date('Y-m-d 00:00:00');
                    }

                    if(!empty($end_date) && $end_date != 'all') {
                        $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
                    } else {
                        $end_date_applied = date('Y-m-d 23:59:59');
                    }
                    $applicants = $this->export_csv_model->get_csv_applicants($company_sid, $applicant_type,$keyword,$job_sid,$applicant_status,$start_date_applied,$end_date_applied);

                    $export_data = array();
                    $i = 0;
                    $rows = '';

                    foreach ($applicants as $key => $applicant) {
                        $notes = $this->export_csv_model->get_applicant_notes($applicant['main_sid']);
                        $applicant_notes = '';

                        if (!empty($notes)) {
                            foreach ($notes as $key => $note) {
                                $note_no = ($key+1).') ';
                                $applicant_notes .= str_replace(array('<p>', '</p>'),array($note_no, '. '),$note['notes']);
                            }
                        }
                        // $export_data[$i]['date_applied'] =  date('m-d-Y H:i:s', strtotime($applicant['date_applied']));
                        $export_data[$i]['date_applied'] =  str_replace(',','',reset_datetime(array('datetime' => $applicant['date_applied'], '_this' => $this)));
                        $export_data[$i]['first_name'] =  $applicant['first_name'];
                        $export_data[$i]['last_name'] =  $applicant['last_name'];
                        $export_data[$i]['email'] =  $applicant['email'];
                        $export_data[$i]['phone_number'] =  $applicant['phone_number'];
                        $export_data[$i]['address'] =  $applicant['address'];
                        $export_data[$i]['city'] =  $applicant['city'];
                        $export_data[$i]['zipcode'] =  $applicant['zipcode'];
                        $export_data[$i]['applicant_status'] =  $applicant['applicant_status'];
                        $export_data[$i]['employee_number'] =  $applicant['employee_number'];
                        $state_sid = $applicant['state'];
                        $country_sid = $applicant['country'];

                        if($state_sid > 0){
                            $state_info = db_get_state_name($state_sid);
                            $export_data[$i]['state'] =  $state_info['state_name'];
                            $export_data[$i]['country'] =  $state_info['country_name'];
                        } else {
                            $export_data[$i]['state'] =  '';

                            if($country_sid > 0) {
                                if($country_sid == 227){
                                    $export_data[$i]['country'] =  'United States';
                                } else {
                                    $export_data[$i]['country'] =  'Canada';
                                }
                            } else {
                                $export_data[$i]['country'] =  '';
                            }
                        }

                        if (!empty($applicant['pictures'])) {
                            $export_data[$i]['pictures'] = AWS_S3_BUCKET_URL . $applicant['pictures'];
                        } else {
                            $export_data[$i]['pictures'] = '';
                        }

                        if (!empty($applicant['resume'])) {
                            $export_data[$i]['resume'] = AWS_S3_BUCKET_URL . $applicant['resume'];
                        } else {
                            $export_data[$i]['resume'] = '';
                        }

                        if (!empty($applicant['cover_letter'])) {
                            $export_data[$i]['cover_letter'] = AWS_S3_BUCKET_URL . $applicant['cover_letter'];
                        } else {
                            $export_data[$i]['cover_letter'] = '';
                        }

                        $export_data[$i]['applicant_type'] =  $applicant['applicant_type'];

                        if($applicant['job_sid'] > 0){
                            $export_data[$i]['job_title'] = $applicant['Title'];
                        } else {
                            $export_data[$i]['job_title'] = $applicant['desired_job_title'];
                        }

                        if ($applicant['archived'] == 0) {
                            $export_data[$i]['status'] = 'Active Applicant';
                        } else {
                            $export_data[$i]['status'] = 'Archived Applicant';
                        }

                        if ($applicant['union_member'] == 1) {
                            $export_data[$i]['union_member'] = 'Yes';
                        } else {
                            $export_data[$i]['union_member'] = 'No';
                        }

                         $export_data[$i]['union_name'] = $applicant['union_name'];


                        $rows .= $export_data[$i]['date_applied'].','.$export_data[$i]['first_name'].','.$export_data[$i]['last_name'].','.$export_data[$i]['email'].','.$export_data[$i]['phone_number'].','.$export_data[$i]['address'].','.$export_data[$i]['city'].','.$export_data[$i]['zipcode'].','.$export_data[$i]['state'].','.$export_data[$i]['country'].','.$export_data[$i]['pictures'].','.$export_data[$i]['resume'].','.$export_data[$i]['cover_letter'].','.$export_data[$i]['applicant_type'].','.$export_data[$i]['job_title'].','.$export_data[$i]['status'].','.$export_data[$i]['applicant_status'].','.$applicant_notes.','.$export_data[$i]['union_member'].','.$export_data[$i]['union_name'].','.$export_data[$i]['employee_number']. PHP_EOL;
                        $i++;
                    }

                    $header_row = 'Date Applied,First Name,Last Name,E-Mail,Primary Number,Street Address,City,Zipcode,State,Country,Profile Picture,Resume,Cover Letter,Applicant Type,Job Title,Status,Applicant Status,Notes,Union Member,Union Name, Employee Number';
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
                    header('Content-Disposition: attachment; filename="applicants_' . date('Y_m_d-H:i:s') . '.csv"');  // Add the file name
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . $file_size); // provide file size
                    header('Connection: close');
                    echo $header_row . ',' . PHP_EOL;
                    echo $rows;
                    exit;
            }


            $this->load->view('main/header', $data);
            $this->load->view('export_applicants_csv/index');
            $this->load->view('main/footer');
        } else {
            redirect('login', 'refresh');
        }
    }

//    public function index() {
//        if ($this->session->userdata('logged_in')) {
//            $data['session'] = $this->session->userdata('logged_in');
//            $security_sid = $data['session']['employer_detail']['sid'];
//            $security_details = db_get_access_level_details($security_sid);
//            $data['security_details'] = $security_details;
//            //check_access_permissions($security_details, 'my_settings', 'export_employees_csv'); // Param2: Redirect URL, Param3: Function Name
//            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
//
//            if ($this->form_validation->run() == false) {
//                $data['title'] = 'Export Applicants CSV';
//                $company_sid = $data['session']['company_detail']['sid'];
//                $employer_sid = $data['session']['employer_detail']['sid'];
//                $data['company_sid'] = $company_sid;
//                $this->load->view('main/header', $data);
//                $this->load->view('export_applicants_csv/index');
//                $this->load->view('main/footer');
//            } else {
//                $perform_action = $this->input->post('perform_action');
//
//                switch ($perform_action) {
//                    case 'export_applicants':
//                        $applicant_type = $this->input->post('applicant_type');
//                        $company_sid = $this->input->post('company_sid');
//                        $applicants = $this->export_csv_model->get_csv_applicants($company_sid, $applicant_type);
//
//                        $export_data = array();
//                        $i = 0;
//                        $rows = '';
//
//                        foreach ($applicants as $key => $applicant) {
//                            $export_data[$i]['date_applied'] =  date('m-d-Y H:i:s', strtotime($applicant['date_applied']));
//                            $export_data[$i]['first_name'] =  $applicant['first_name'];
//                            $export_data[$i]['last_name'] =  $applicant['last_name'];
//                            $export_data[$i]['email'] =  $applicant['email'];
//                            $export_data[$i]['phone_number'] =  $applicant['phone_number'];
//                            $export_data[$i]['address'] =  $applicant['address'];
//                            $export_data[$i]['city'] =  $applicant['city'];
//                            $export_data[$i]['zipcode'] =  $applicant['zipcode'];
//                            $state_sid = $applicant['state'];
//                            $country_sid = $applicant['country'];
//
//                            if($state_sid > 0){
//                                $state_info = db_get_state_name($state_sid);
//                                $export_data[$i]['state'] =  $state_info['state_name'];
//                                $export_data[$i]['country'] =  $state_info['country_name'];
//                            } else {
//                                $export_data[$i]['state'] =  '';
//
//                                if($country_sid > 0) {
//                                    if($country_sid == 227){
//                                        $export_data[$i]['country'] =  'United States';
//                                    } else {
//                                        $export_data[$i]['country'] =  'Canada';
//                                    }
//                                } else {
//                                    $export_data[$i]['country'] =  '';
//                                }
//                            }
//
//                            if (!empty($applicant['pictures'])) {
//                                $export_data[$i]['pictures'] = AWS_S3_BUCKET_URL . $applicant['pictures'];
//                            } else {
//                                $export_data[$i]['pictures'] = '';
//                            }
//
//                            if (!empty($applicant['resume'])) {
//                                $export_data[$i]['resume'] = AWS_S3_BUCKET_URL . $applicant['resume'];
//                            } else {
//                                $export_data[$i]['resume'] = '';
//                            }
//
//                            if (!empty($applicant['cover_letter'])) {
//                                $export_data[$i]['cover_letter'] = AWS_S3_BUCKET_URL . $applicant['cover_letter'];
//                            } else {
//                                $export_data[$i]['cover_letter'] = '';
//                            }
//
//                            $export_data[$i]['applicant_type'] =  $applicant['applicant_type'];
//
//                            if($applicant['job_sid'] > 0){
//                                $export_data[$i]['job_title'] = $applicant['Title'];
//                            } else {
//                                $export_data[$i]['job_title'] = $applicant['desired_job_title'];
//                            }
//
//                            if ($applicant['archived'] == 0) {
//                                $export_data[$i]['status'] = 'Active Applicant';
//                            } else {
//                                $export_data[$i]['status'] = 'Archived Applicant';
//                            }
//
//                            $rows .= $export_data[$i]['date_applied'].','.$export_data[$i]['first_name'].','.$export_data[$i]['last_name'].','.$export_data[$i]['email'].','.$export_data[$i]['phone_number'].','.$export_data[$i]['address'].','.$export_data[$i]['city'].','.$export_data[$i]['zipcode'].','.$export_data[$i]['state'].','.$export_data[$i]['country'].','.$export_data[$i]['pictures'].','.$export_data[$i]['resume'].','.$export_data[$i]['cover_letter'].','.$export_data[$i]['applicant_type'].','.$export_data[$i]['job_title'].','.$export_data[$i]['status']. PHP_EOL;
//                            $i++;
//                        }
//
//                        $header_row = 'Date Applied,First Name,Last Name,E-Mail,Contact Number,Street Address,City,Zipcode,State,Country,Profile Picture,Resume,Cover Letter,Applicant Type,Job Title,Status';
//                        $file_content = '';
//                        $file_content .= $header_row . ',' . PHP_EOL;
//                        $file_content .= $rows;
//                        $file_size = 0;
//
//                        if (function_exists('mb_strlen')) {
//                            $file_size = mb_strlen($file_content, '8bit');
//                        } else {
//                            $file_size = strlen($file_content);
//                        }
//
//                        header('Pragma: public');     // required
//                        header('Expires: 0');         // no cache
//                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//                        header('Cache-Control: private', false);
//                        header('Content-Type: text/csv');  // Add the mime type from Code igniter.
//                        header('Content-Disposition: attachment; filename="applicants_' . date('Y_m_d-H:i:s') . '.csv"');  // Add the file name
//                        header('Content-Transfer-Encoding: binary');
//                        header('Content-Length: ' . $file_size); // provide file size
//                        header('Connection: close');
//                        echo $header_row . ',' . PHP_EOL;
//                        echo $rows;
//                        break;
//                }
//            }
//        } else {
//            redirect('login', 'refresh');
//        }
//    }
}