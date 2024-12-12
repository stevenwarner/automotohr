<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manual_candidate extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('manual_candidate_model');
        $this->load->model('job_listings_visibility_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index()
    {
        $resume = '';
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'manual_candidate'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $employer_id = $data["session"]["company_detail"]["sid"];
            $data['title'] = "Add Manual Candidate";
            $data['formpost'] = $_POST;
            $data['jobs_approval_module_status'] = $this->manual_candidate_model->GetModuleStatus($company_sid, 'jobs');
            //

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
            $this->form_validation->set_rules('phone_number', 'Primary Number', 'trim|xss_clean|callback_alpha_dash_space');
            $this->form_validation->set_rules('city ', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('job_sid', 'Job Sid', 'trim|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                $all_jobs_query = array();

                if (is_admin($employer_sid)) {
                    $all_jobs_query = $this->job_listings_visibility_model->GetAllJobsTitlesCompanySpecific($company_sid);
                } else {
                    $all_jobs_query = $this->job_listings_visibility_model->GetAllJobsTitlesCompanyAndEmployerSpecific($company_sid, $employer_sid);
                }

                $all_jobs = array();
                $all_jobs[] = array("sid" => '0', "Title" => 'Please select job', "approval_status" => 'approved');

                foreach ($all_jobs_query as $row) {
                    $all_jobs[] = array("sid" => $row['sid'], "Title" => $row['Title'], "approval_status" => $row['approval_status']);
                }

                $data["all_jobs"] = $all_jobs;
                $data_countries = db_get_active_countries();

                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data_states_encode = htmlentities(json_encode($data_states));
                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data['states'] = $data_states_encode;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/add_manual_candidate_new');
                $this->load->view('main/footer');
            } else {
                $applicant_sid = 0;
                $formpost = $this->input->post(NULL, TRUE);
                //
                foreach ($formpost as $key => $value) { //Arranging company detial

                    if ($key != 'action' && $key != 'title_option' && $key != 'template_job_title') { // exclude these values from array
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $user_data[$key] = $value;
                    }
                }
                //

                if ($formpost['template_job_title'] != '0') {
                    $templetJobTitleData = $formpost['template_job_title'];
                    $templetJobTitleDataArray = explode('#', $templetJobTitleData);
                    $user_data['desired_job_title'] = $templetJobTitleDataArray[1];
                    $user_data['job_title_type'] = $templetJobTitleDataArray[0];
                    $user_data['lms_job_title'] = $templetJobTitleDataArray[0];
                } else {
                    $user_data['job_title_type'] = 0;
                    $user_data['lms_job_title'] = null;
                }


                $user_data["employer_sid"] = $employer_id;
                $job_sid = $user_data['job_sid'];
                unset($user_data['job_sid']);
                //
                $candidate_sid = $this->manual_candidate_model->check_manual_candidate($user_data['email'], $company_sid);
                $job_added_successfully = 0;
                $date_applied = date('Y-m-d H:i:s');
                $status = get_default_status_sid_and_text($company_sid);
                //
                if (!in_array($company_sid, array("0"))) {
                    // if (in_array($company_sid, array("7", "51", "57"))) {
                    if ($candidate_sid == 'no_record_found') { // new manual candidate
                        $resume = upload_file_to_aws('resume', $company_sid, str_replace(' ', '_', $formpost['first_name'] . '_' . $formpost['last_name']) . '_resume_', $employer_sid);

                        if ($resume != 'error') {
                            $user_data['resume'] = $resume;
                        }

                        $cover_letter = upload_file_to_aws('cover_letter', $company_sid, str_replace(' ', '_', $formpost['first_name'] . '_' . $formpost['last_name']) . '_cover_letter_', $employer_sid);

                        if ($cover_letter != 'error') {
                            $user_data['cover_letter'] = $cover_letter;
                        }

                        $output = $this->manual_candidate_model->add_manual_candidate($user_data, $company_sid);
                        $applicant_sid = $output[0];

                        if ($output[1] == 1) { // data inserted successfully
                            $new_candidate_sid = $output[0];

                            $insert_data = array();
                            $insert_data['portal_job_applications_sid'] = $new_candidate_sid;
                            $insert_data['company_sid']                 = $company_sid;
                            $insert_data['job_sid']                     = $job_sid;
                            $insert_data['date_applied']                = $date_applied;
                            $insert_data['status_sid']                  = $status['status_sid'];
                            $insert_data['status']                      = $status['status_name'];
                            $insert_data['ip_address']                  = getUserIP();
                            $insert_data['user_agent']                  = $_SERVER['HTTP_USER_AGENT'];
                            $insert_data['applicant_type']              = 'Manual Candidate';
                            $insert_data['applicant_source']            = 'career_website';
                            if ($resume != 'error') {
                                $insert_data['resume']                   = $resume;
                            } else {
                                $insert_data['resume'] = NULL;
                            }
                            // Added on: 28-06-2019
                            if ($user_data['desired_job_title'] != '') $insert_data['desired_job_title'] = $user_data['desired_job_title'];

                            $output = $this->manual_candidate_model->add_applicant_job_details($insert_data);
                            $job_added_successfully = $output[1];
                        }
                    } else {

                        $resume = upload_file_to_aws('resume', $company_sid, str_replace(' ', '_', $formpost['first_name'] . '_' . $formpost['last_name']) . '_resume_', $employer_sid);

                        if ($resume != 'error') {
                            $user_data['resume'] = $resume;
                        }

                        $insert_data = array();
                        $insert_data['portal_job_applications_sid'] = $candidate_sid;
                        $insert_data['company_sid']                 = $company_sid;
                        $insert_data['job_sid']                     = $job_sid;
                        $insert_data['date_applied']                = $date_applied;
                        $insert_data['status_sid']                  = $status['status_sid'];
                        $insert_data['status']                      = $status['status_name'];
                        $insert_data['ip_address']                  = getUserIP();
                        $insert_data['user_agent']                  = $_SERVER['HTTP_USER_AGENT'];
                        $insert_data['applicant_type']              = 'Manual Candidate';
                        $insert_data['applicant_source']            = 'career_website';
                        if ($resume != 'error') {
                            $insert_data['resume']                   = $resume;
                        } else {
                            $insert_data['resume'] = NULL;
                        }

                        if ($user_data['desired_job_title'] != '') $insert_data['desired_job_title'] = $user_data['desired_job_title'];

                        $output = $this->manual_candidate_model->add_applicant_job_details($insert_data);
                        $job_added_successfully = $output[1];
                    }
                } else {
                    if ($candidate_sid == 'no_record_found') { // new manual candidate
                        $resume = upload_file_to_aws('resume', $company_sid, str_replace(' ', '_', $formpost['first_name'] . '_' . $formpost['last_name']) . '_resume_', $employer_sid);

                        if ($resume != 'error') {
                            $user_data['resume'] = $resume;
                        }

                        $cover_letter = upload_file_to_aws('cover_letter', $company_sid, str_replace(' ', '_', $formpost['first_name'] . '_' . $formpost['last_name']) . '_cover_letter_', $employer_sid);

                        if ($cover_letter != 'error') {
                            $user_data['cover_letter'] = $cover_letter;
                        }

                        $output = $this->manual_candidate_model->add_manual_candidate($user_data, $company_sid);
                        $applicant_sid = $output[0];

                        if ($output[1] == 1) { // data inserted successfully
                            $new_candidate_sid = $output[0];

                            $insert_data = array();
                            $insert_data['portal_job_applications_sid'] = $new_candidate_sid;
                            $insert_data['company_sid'] = $company_sid;
                            $insert_data['job_sid'] = $job_sid;
                            $insert_data['date_applied'] = $date_applied;
                            $insert_data['status_sid'] = $status['status_sid'];
                            $insert_data['status'] = $status['status_name'];
                            $insert_data['ip_address'] = getUserIP();
                            $insert_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                            $insert_data['applicant_type'] = 'Manual Candidate';
                            $insert_data['applicant_source'] = 'career_website';

                            if ($user_data['desired_job_title'] != '') $insert_data['desired_job_title'] = $user_data['desired_job_title'];

                            $output = $this->manual_candidate_model->add_applicant_job_details($insert_data);
                            $job_added_successfully = $output[1];
                        }
                    } else {

                        $insert_data = array();
                        $insert_data['portal_job_applications_sid'] = $candidate_sid;
                        $insert_data['company_sid'] = $company_sid;
                        $insert_data['job_sid'] = $job_sid;
                        $insert_data['date_applied'] = $date_applied;
                        $insert_data['status_sid'] = $status['status_sid'];
                        $insert_data['status'] = $status['status_name'];
                        $insert_data['ip_address'] = getUserIP();
                        $insert_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $insert_data['applicant_type'] = 'Manual Candidate';
                        $insert_data['applicant_source'] = 'career_website';

                        if ($user_data['desired_job_title'] != '') $insert_data['desired_job_title'] = $user_data['desired_job_title'];

                        $output = $this->manual_candidate_model->add_applicant_job_details($insert_data);
                        $job_added_successfully = $output[1];
                    }
                }
                //
                if ($candidate_sid == 'no_record_found') {
                    send_full_employment_application($company_sid, $applicant_sid, "applicant");
                }
                //

                if ($job_added_successfully == 1) {
                    $this->session->set_flashdata('message', '<b>Success:</b> Manual candidate added successfully.');
                } else {
                    $this->session->set_flashdata('message', '<b>Failed:</b> Could not add new candidate, Please try Again');
                }
                redirect("application_tracking_system/active/all/all/all/all", "location");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function add_to_other_job()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $name = $data["session"]["employer_detail"]["first_name"] . " " . $data["session"]["employer_detail"]["last_name"];
        $date_applied = date('Y-m-d H:i:s');
        $selected_candidates = $this->input->post('selected_candidates');
        $status = get_default_status_sid_and_text($company_sid);
        foreach ($selected_candidates as $candidate => $job) {
            $resume = $this->manual_candidate_model->get_applicant_resume($candidate, $company_sid);
            //
            $insert_data = array();
            $insert_data['portal_job_applications_sid'] = $candidate;
            $insert_data['company_sid'] = $company_sid;
            $insert_data['job_sid'] = $job;
            $insert_data['date_applied'] = $date_applied;
            $insert_data['status_sid'] = $status['status_sid'];
            $insert_data['status'] = $status['status_name'];
            $insert_data['ip_address'] = getUserIP();
            $insert_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $insert_data['applicant_type'] = 'Manual Candidate';
            $insert_data['applicant_source'] = 'Manually Assigned By ' . $name;
            $insert_data['resume'] = $resume;

            $this->manual_candidate_model->add_applicant_job_details($insert_data);
        }
        echo 'added';
    }

    function alpha_dash_space($str)
    {
        if ($str != "") {
            if (!preg_match("/^([-0-9])+$/i", $str)) {
                $this->form_validation->set_message('alpha_dash_space', 'The %s can only contain numeric and dashes.');
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
}
