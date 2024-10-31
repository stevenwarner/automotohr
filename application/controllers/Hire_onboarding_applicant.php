<?php defined('BASEPATH') or exit('No direct script access allowed');

class Hire_onboarding_applicant extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('hire_onboarding_applicant_model');
        $this->load->model('onboarding_model');
    }

    /**
     * Hire Applicant function will move applicant data and convert his type from employee
     */
    function index($unique_sid)
    {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
        $this->form_validation->set_rules('username', 'User Name', 'required|trim|min_length[5]|is_unique[users.username]');
        if ($this->form_validation->run() == false) {

            $this->session->set_flashdata('message', '<strong>Error</strong> This username already exists, please choose a different one!');
            redirect('onboarding/my_credentials/' . $unique_sid, 'refresh');
        } else {
            //Handle Post Request
            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'hire_applicant':

                    $company_sid = $this->input->post('company_sid');
                    $company_info = $this->hire_onboarding_applicant_model->get_company_information($company_sid);
                    $applicant_detail = $this->onboarding_model->get_details_by_unique_sid($unique_sid);
                    $applicant_info = $applicant_detail['applicant_info'];
                    $applicant_sid = $this->input->post('applicant_sid');
                    $job_list_sid = $this->input->post('job_list_sid');
                    $applicant_job_sid = $this->input->post('applicant_job_sid');
                    $applicant_email = $this->input->post('applicant_email');
                    $unique_sid = $this->input->post('unique_sid');
                    $company_name = $company_info['CompanyName'];

                    $username = $this->input->post('username');
                    $password = $this->input->post('password');


                    $configuration = $this->onboarding_model->get_onboarding_configuration('applicant', $applicant_sid);
                    $credentials_data = $this->get_single_record_from_array($configuration, 'section', 'credentials');
                    $credentials = empty($credentials_data) ? array() : unserialize($credentials_data['items']);

                    $employee_status = 1;
                    $access_level = 'Employee';
                    if (isset($credentials['access_level'])) {
                        $access_level = ucwords($credentials['access_level']);
                    }

                    if (isset($credentials['joining_date'])) {
                        $joining_date = ucwords($credentials['joining_date']);
                    }
                    $result = $this->move_applicant_to_employee($company_sid, $company_info, $applicant_sid, $applicant_email, $applicant_job_sid, $username, $password, $access_level, $employee_status, $joining_date);
                    $status = $result['status'];

                    if ($status == 'success') {
                        //                    if(1){
                        //Send New Applicant On-board Email Notification
                        $notifications_status                                           = get_notifications_status($company_sid);
                        $onboarding_request_email_notification                          = 0;
                        if (!empty($notifications_status)) {
                            $onboarding_request_email_notification                      = $notifications_status['onboarding_request_notification'];
                        }

                        if ($onboarding_request_email_notification == 1) { //Send email to Users which are registered to receive notifications
                            $emailTemplateData                                          = get_email_template(ONBOARDING_REQUEST_COMPLETED);
                            $employer_name                                              = $applicant_info['first_name'] . ' ' . $applicant_info['last_name'];
                            $onboarding_request_handlers                                = get_notification_email_contacts($company_sid, 'onboarding_request');
                            $company_sms_notification_status                            = get_company_sms_status($this, $company_sid);

                            if (!empty($onboarding_request_handlers)) {

                                foreach ($onboarding_request_handlers as $emp_info) {
                                    $sms_notify = 0;
                                    $contact_no = 0;
                                    if ($company_sms_notification_status) {
                                        if ($emp_info['employer_sid'] != 0) {
                                            $notify_by = get_employee_sms_status($this, $emp_info['employer_sid']);
                                            if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                                $contact_no = $notify_by['PhoneNumber'];
                                                $sms_notify = 1;
                                            }
                                        } else {
                                            if (!empty($emp_info['contact_no'])) {
                                                $contact_no = $emp_info['contact_no'];
                                                $sms_notify = 1;
                                            }
                                        }
                                        if ($sms_notify) {
                                            $this->load->library('Twilioapp');
                                            // Send SMS
                                            $sms_template = get_company_sms_template($this, $company_sid, 'onboarding_request');
                                            $replacement_sms_array = array(); //Send Payment Notification to admin.
                                            $replacement_sms_array['applicant_name'] = $applicant_info['first_name'] . ' ' . $applicant_info['last_name'];
                                            $replacement_sms_array['contact_name'] = ucwords(strtolower($emp_info['contact_name']));
                                            $sms_body = 'This sms is to inform you that ' . $applicant_info['first_name'] . ' ' . $applicant_info['last_name'] . '�has completed their on-boarding Processing.';
                                            if (sizeof($sms_template) > 0) {
                                                $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_sms_array);
                                            }
                                            sendSMS(
                                                $contact_no,
                                                $sms_body,
                                                trim(ucwords(strtolower($emp_info['contact_name']))),
                                                $emp_info['email'],
                                                $this,
                                                $sms_notify,
                                                $company_sid
                                            );
                                        }
                                    }
                                    //                                    $emp_info = $this->job_approval_rights_model->getemployeedetails($ertre, $company_sid);

                                    if (!empty($emp_info)) {
                                        $userEmail = $emp_info['email'];
                                        $userFullName = ucwords($emp_info['contact_name']);
                                        $emailTemplateBody = $emailTemplateData['text'];
                                        $emailTemplateBody = str_replace('{{firstname}}', ucwords($applicant_info['first_name']), $emailTemplateBody);
                                        $emailTemplateBody = str_replace('{{lastname}}', ucwords($applicant_info['last_name']), $emailTemplateBody);
                                        $emailTemplateBody = str_replace('{{first_name}}', ucwords($applicant_info['first_name']), $emailTemplateBody);
                                        $emailTemplateBody = str_replace('{{last_name}}', ucwords($applicant_info['last_name']), $emailTemplateBody);
                                        $from = $emailTemplateData['from_email'];
                                        $to = $userEmail;
                                        $subject = $emailTemplateData['subject'];
                                        $subject = str_replace('{{company_name}}', ucwords($company_name), $subject);
                                        $from_name = $emailTemplateData['from_name'];
                                        $body = EMAIL_HEADER
                                            . $emailTemplateBody
                                            . EMAIL_FOOTER;
                                        log_and_sendEmail($from, $to, $subject, $body, $from_name);
                                    }
                                }
                            }
                        }

                        $this->session->sess_destroy();
                        redirect('login', 'refresh');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Error</strong> ' . $result['message'] . '');
                        redirect('onboarding/getting_started/' . $unique_sid, 'refresh');
                    }

                    break;
            }
        }
    }

    private function move_applicant_to_employee($company_sid, $company_info, $applicant_sid, $applicant_email, $applicant_job_sid, $username, $password, $access_level, $employee_status, $joining_date = null)
    {


        $assignedBySid = $this->session->userdata('logged_in')['employer_detail']['sid'];

        if ($joining_date == null) $joining_date = date('Y-m-d');
        else $joining_date = DateTime::createFromFormat('m/d/Y', $joining_date)->format('Y-m-d');
        $company_name = $company_info['CompanyName'];
        //Step No:1, Check if this email address is already registered in the company.
        $applicant_exists = $this->hire_onboarding_applicant_model->check_email_exists($company_sid, $applicant_email);


        if ($applicant_exists != 'success') {
            $array['status']    = "error";
            $array['message']   = "Error! The E-Mail address of the applicant is already registered at your company as employee!";
            $array['category']  = "email_error";

            return $array;
        }
        // The applicant can be added to company as employee
        $applicant_profile_info = $this->hire_onboarding_applicant_model->get_applicant_profile($applicant_sid);


        if (empty($applicant_profile_info)) {
            $array['status'] = "error";
            $array['message'] = "Error! Could not hire applicant, Please try again!";
            $array['category']  = "applicant_not_found_error";

            return $array;
        }

        $resume = $this->hire_onboarding_applicant_model->check_for_resume($applicant_sid);
        if ($resume != 0) {
            $applicant_profile_info['resume'] = $resume;
        }

        // For job title
        if ($applicant_profile_info['job_sid'] != 0) {
            $applicant_profile_info['jobTitle'] = $this->hire_onboarding_applicant_model->getJobTitleById($applicant_profile_info['job_sid']);
            //
            if (empty($applicant_profile_info['jobTitle'])) $applicant_profile_info['jobTitle'] = $applicant_profile_info['desired_job_title'];
        } else $applicant_profile_info['jobTitle'] = $applicant_profile_info['desired_job_title'];

        $employer_data = array();
        $employer_data['first_name'] = $applicant_profile_info['first_name'];
        $employer_data['last_name'] = $applicant_profile_info['last_name'];
        $employer_data['active'] = $employee_status;
        $employer_data['registration_date'] = $joining_date . date(' H:i:s');
        // $employer_data['registration_date'] = date('Y-m-d H:i:s', strtotime('+3 days'));
        $employer_data['access_level'] = $access_level;
        $employer_data['resume'] = $applicant_profile_info['resume'];
        $employer_data['profile_picture'] = $applicant_profile_info['pictures'];
        $employer_data['full_employment_application'] = $applicant_profile_info['full_employment_application'];
        $employer_data['Location_Country'] = $applicant_profile_info['country'];
        $employer_data['Location_State'] = $applicant_profile_info['state'];
        $employer_data['Location_City'] = $applicant_profile_info['city'];
        $employer_data['Location_Address'] = $applicant_profile_info['address'];
        $employer_data['PhoneNumber'] = $applicant_profile_info['phone_number'];
        $employer_data['CompanyName'] = $company_name;
        $employer_data['Location_ZipCode'] = $applicant_profile_info['zipcode'];
        $employer_data['YouTubeVideo'] = $applicant_profile_info['YouTube_Video'];
        $employer_data['job_title'] = $applicant_profile_info['jobTitle'];
        $employer_data['employee_status'] = $applicant_profile_info['employee_status'];
        $employer_data['applicant_sid'] = $applicant_sid;
        $employer_data['email'] = $applicant_email;
        $employer_data['parent_sid'] = $company_sid;
        $employer_data['extra_info'] = $applicant_profile_info['extra_info'];
        $employer_data['linkedin_profile_url'] = $applicant_profile_info['linkedin_profile_url'];
        $employer_data['username'] = $username;
        $employer_data['joined_at'] = $joining_date;
        $employer_data['created_by'] = $this->session->userdata('logged_in')['employer_detail']['sid'];

        $employer_data['employee_number'] = $applicant_profile_info['employee_number'];


        //
        $employee_type = '';
        //
        if ($applicant_profile_info['employee_type'] == 'part-time') {
            $employee_type = 'parttime';
        } else if ($applicant_profile_info['employee_type'] == 'full-time') {
            $employee_type = 'fulltime';
        } else {
            $employee_type = $applicant_profile_info['employee_type'];
        }
        $employer_data['employee_type'] = $employee_type;
        //
        if (!empty($password)) {
            $employer_data['password'] = do_hash($password, 'md5');
        }
        $employer_data['ssn'] = $applicant_profile_info['ssn'];
        $employer_data['dob'] = $applicant_profile_info['dob'];

        if (!empty($applicant_profile_info['marital_status'])) {
            $employer_data['marital_status'] = $applicant_profile_info['marital_status'];
        }

        if (!empty($applicant_profile_info['gender'])) {
            $employer_data['gender'] = $applicant_profile_info['gender'];
        }

        if (!empty($applicant_profile_info['ip_address'])) {
            $employer_data['ip_address'] = $applicant_profile_info['ip_address'];
        }

        if (!empty($applicant_profile_info['cover_letter'])) {
            $employer_data['cover_letter'] = $applicant_profile_info['cover_letter'];
        }

        if (!empty($applicant_profile_info['video_type'])) {
            $employer_data['video_type'] = $applicant_profile_info['video_type'];
        }

        if (!empty($applicant_profile_info['middle_name'])) {
            $employer_data['middle_name'] = $applicant_profile_info['middle_name'];
        }

        if (!empty($applicant_profile_info['nick_name'])) {
            $employer_data['nick_name'] = $applicant_profile_info['nick_name'];
        }

        if (!empty($applicant_profile_info['uniform_top_size'])) {
            $employer_data['uniform_top_size'] = $applicant_profile_info['uniform_top_size'];
        }
        if (!empty($applicant_profile_info['uniform_bottom_size'])) {
            $employer_data['uniform_bottom_size'] = $applicant_profile_info['uniform_bottom_size'];
        }

        //
        if (!empty($applicant_profile_info['hourly_rate'])) {
            $employer_data['hourly_rate'] = $applicant_profile_info['hourly_rate'];
        }

        if (!empty($applicant_profile_info['hourly_technician'])) {
            $employer_data['hourly_technician'] = $applicant_profile_info['hourly_technician'];
        }
        if (!empty($applicant_profile_info['flat_rate_technician'])) {
            $employer_data['flat_rate_technician'] = $applicant_profile_info['flat_rate_technician'];
        }
        if (!empty($applicant_profile_info['semi_monthly_salary'])) {
            $employer_data['semi_monthly_salary'] = $applicant_profile_info['semi_monthly_salary'];
        }
        if (!empty($applicant_profile_info['semi_monthly_draw'])) {
            $employer_data['semi_monthly_draw'] = $applicant_profile_info['semi_monthly_draw'];
        }


        //
        $employer_data['languages_speak'] = $applicant_profile_info['languages_speak'];
        $employer_data['salary_benefits'] = $applicant_profile_info['salary_benefits'];
        $employer_data['workers_compensation_code'] = $applicant_profile_info['workers_compensation_code'];
        $employer_data['eeoc_code'] = $applicant_profile_info['eeoc_code'];
        //
        $departmentsTeams = $this->hire_onboarding_applicant_model->get_applicant_department_team($company_sid, $applicant_sid);
        $employer_data['department_sid'] = !empty($departmentsTeams['department_sid']) ? $departmentsTeams['department_sid'] : 0;
        $employer_data['team_sid'] = !empty($departmentsTeams['team_sid']) ? $departmentsTeams['team_sid'] : 0;

        // insert employer data to table and get its ID
        $employee_result = $this->hire_onboarding_applicant_model->insert_company_employee($employer_data, $applicant_sid, $applicant_job_sid);


        if ($employee_result != 'error' && is_numeric($employee_result) && is_int($employee_result)) { // There was some issue.
            //Update Applicant Data


            // now move all other information

            // 1) emergency_contacts
            $applicant_emergency_contacts = $this->hire_onboarding_applicant_model->get_applicant_emergency_contacts($applicant_sid, $employee_result);

            // 2) equipment_information
            $applicant_equipment_information = $this->hire_onboarding_applicant_model->get_applicant_equipment_information($applicant_sid, $employee_result);

            // 3) dependant_information
            $applicant_dependant_information = $this->hire_onboarding_applicant_model->get_applicant_dependant_information($applicant_sid, $employee_result);

            // 4) license_information
            $applicant_license_information = $this->hire_onboarding_applicant_model->get_applicant_license_information($applicant_sid, $employee_result);

            // 5) background_check_orderss
            $applicant_background_check = $this->hire_onboarding_applicant_model->get_applicant_background_check($applicant_sid, $employee_result);

            // 6) portal_misc_notes
            $applicant_misc_notes = $this->hire_onboarding_applicant_model->get_applicant_misc_notes($applicant_sid, $employee_result);

            // 7) private_message
            $applicant_private_message = $this->hire_onboarding_applicant_model->get_applicant_private_message($applicant_email, $employee_result, $applicant_sid);

            // 8) portal_applicant_rating
            $applicant_rating = $this->hire_onboarding_applicant_model->get_applicant_rating($applicant_sid, $employee_result);

            // 9) calendar events - portal_schedule_event
            $applicant_schedule_event = $this->hire_onboarding_applicant_model->get_applicant_schedule_event($applicant_sid, $employee_result);

            // 10) background check orders
            // $applicant_schedule_event = $this->hire_onboarding_applicant_model->get_applicant_background_check_order($applicant_sid, $employee_result);

            // 11) portal_applicant_attachments
            $applicant_applicant_attachments = $this->hire_onboarding_applicant_model->get_applicant_attachments($applicant_sid, $employee_result);

            // 12) reference_checks
            $applicant_reference_checks = $this->hire_onboarding_applicant_model->get_reference_checks($applicant_sid, $employee_result);

            // 13) Onboarding Configuration
            $onboarding_configuration = $this->hire_onboarding_applicant_model->get_onboarding_configuration($applicant_sid, $employee_result);

            // 14) Documents
            $documents = $this->hire_onboarding_applicant_model->get_documents($applicant_sid, $employee_result,$assignedBySid);

            // 15) Direct Deposit Information
            $bank_details = $this->hire_onboarding_applicant_model->get_direct_deposit_information($applicant_sid, $employee_result);

            // 16) E Signature Data
            $e_signature_data = $this->hire_onboarding_applicant_model->get_applicant_e_signature($applicant_sid, $employee_result);

            // 17) Mark Applicant Onboarding as Complete
            $this->hire_onboarding_applicant_model->set_onboarding_as_completed($company_sid, $applicant_sid);

            // 18) Update training session user_id
            // Fetch assignments
            $ids = $this->hire_onboarding_applicant_model->update_applicant_training_sessions_to_employee($company_sid, $applicant_sid, $employee_result);

            // 19) EEOC Form
            $eeoc = $this->hire_onboarding_applicant_model->copy_eeoc_as_user($applicant_sid, $employee_result);
            if (preg_match('/applybuz/', base_url())) {
                if ($ids) {
                    foreach ($ids as $k0 => $v0) {
                        ics_files(
                            $v0['sid'],
                            $company_sid,
                            $company_info,
                            'update_event'
                        );
                    }
                }
            }

            //
            // 20) Copy full form application
            $ffea = $this->hire_onboarding_applicant_model->copyApplicantFFEAToEmployee($applicant_sid, $employee_result);

            $array['status'] = "success";
            $array['added_id'] = $employee_result;
            $array['message'] = "Success! Applicant is successfully hired!";

            return $array;
        } else {
            $array['status'] = "error";
            $array['message'] = "Error! Could not hire applicant, Please try again!";
            $array['category']  = "record_not_insert_error";

            return $array;
        }
    }


    private function get_single_record_from_array($records, $key, $value)
    {
        if (is_array($records)) {
            foreach ($records as $record) {
                foreach ($record as $k => $v) {
                    if ($k == $key && $v == $value) {
                        return $record;
                    }
                }
            }

            return array();
        } else {
            return array();
        }
    }

    public function hire_applicant_manually()
    {
        $session = $this->session->userdata('logged_in');
        $security_sid = $session['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        if ($session['employer_detail']['access_level_plus'] != 1) {
            check_access_permissions($security_details, 'dashboard', 'hire_applicant_manually');
        }
        //
        $company_sid        = $this->input->post('company_sid');
        $applicant_sid      = $this->input->post('applicant_sid');
        $applicant_job_sid  = $this->input->post('applicant_job_sid');
        $access_level       = 'Employee';
        $employee_status    = 0;
        $username           = '';
        $password           = '';
        $joining_date       = '';

        $company_info       = $this->hire_onboarding_applicant_model->get_company_information($company_sid);
        $applicant_detail   = $this->hire_onboarding_applicant_model->get_details_by_applicant_sid($applicant_sid);

        if (empty($applicant_detail)) {
            echo 'error';
        }

        $applicant_email    = $applicant_detail['email'];
        $first_name         = strtolower($applicant_detail['first_name']);
        $last_name          = strtolower($applicant_detail['last_name']);
        $username           = str_replace(' ', '', $first_name) . str_replace(' ', '', $last_name);

        $is_exist           = $this->hire_onboarding_applicant_model->is_username_exist($username);

        if ($is_exist == 1) {
            $rendom = generateRandomString(5);
            $username = $username . $rendom;
        }

        $configuration      = $this->onboarding_model->get_onboarding_configuration('applicant', $applicant_sid);
        $credentials_data   = $this->get_single_record_from_array($configuration, 'section', 'credentials');
        $credentials        = empty($credentials_data) ? array() : unserialize($credentials_data['items']);

        if (isset($credentials['access_level'])) {
            $access_level = ucwords($credentials['access_level']);
        }

        if (isset($credentials['joining_date'])) {
            $joining_date = ucwords($credentials['joining_date']);
        }



        $result = $this->move_applicant_to_employee($company_sid, $company_info, $applicant_sid, $applicant_email, $applicant_job_sid, $username, $password, $access_level, $employee_status, $joining_date);
        $status = $result['status'];

        if ($status == 'success') {
            $this->session->set_flashdata('message', '<strong>Success:</strong> You have moved applicant <b>' . $first_name . ' ' . $last_name . '</b> to the "Employee / Team Members" section!');
            print_r(json_encode(array('status' => 'success', 'adid' => $result['added_id'])));
        } else {
            $error_type = $result['category'];
            if ($error_type == "email_error") {
                print_r(json_encode(array('status' => 'failure_e')));
                //                echo 'failure_e';
            } else if ($error_type == "applicant_not_found_error") {
                print_r(json_encode(array('status' => 'failure_f')));
                //                echo 'failure_f';
            } else if ($error_type == "record_not_insert_error") {
                print_r(json_encode(array('status' => 'failure_i')));
                //                echo 'failure_i';
            }
        }
    }

    public function merge_applicant_with_employee()
    {
        $session = $this->session->userdata('logged_in');
        $security_sid = $session['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        if ($session['employer_detail']['access_level_plus'] != 1) {
            check_access_permissions($security_details, 'dashboard', 'merge_applicant_with_employee');
        }
        //
        $company_sid        = $this->input->post('company_sid');
        $applicant_sid      = $this->input->post('applicant');
        $employee_sid       = $this->input->post('employee');
        $applicant_email    = $this->input->post('email');
        $update_flag        = 0;                                //Flag to check anything update or not
        $array              = array();

        //Get Applicant Profile
        $applicant_profile_info = $this->hire_onboarding_applicant_model->get_applicant_profile($applicant_sid);
        if (empty($applicant_profile_info)) {
            $array['status'] = "error";
            $array['message'] = "Error! Could not merge applicant, Please try again!";
            $array['category']  = "applicant_not_found_error";
            return print_r(json_encode($array));
        }
        $resume = $this->hire_onboarding_applicant_model->check_for_resume($applicant_sid);
        if ($resume != 0) {
            $applicant_profile_info['resume'] = $resume;
        }

        //Get Employee Profile
        $employee_profile_info = $this->hire_onboarding_applicant_model->get_employee_profile($employee_sid);
        if (empty($applicant_profile_info)) {
            $array['status'] = "error";
            $array['message'] = "Error! Could not merge applicant, Please try again!";
            $array['category']  = "employee_not_found_error";
            return print_r(json_encode($array));
        }
        $employee_profile_info = $employee_profile_info[0];

        //Preparing Difference Array
        $employer_data = array();
        $employer_data['applicant_sid'] = $applicant_sid;
        if ((empty($employee_profile_info['resume']) || $employee_profile_info['resume'] == NULL) && !empty($applicant_profile_info['resume'])) {
            $employer_data['resume'] = $applicant_profile_info['resume'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['profile_picture']) || $employee_profile_info['profile_picture'] == NULL) && !empty($applicant_profile_info['pictures'])) {
            $employer_data['profile_picture'] = $applicant_profile_info['pictures'];
            $update_flag = 1;
        }
        //
        if ((empty($employee_profile_info['full_employment_application']) || $employee_profile_info['full_employment_application'] == NULL) && !empty($applicant_profile_info['full_employment_application'])) {
            $employer_data['full_employment_application'] = $applicant_profile_info['full_employment_application'];
            $update_flag = 1;
            //
            $this->hire_onboarding_applicant_model->form_full_employment_application($applicant_sid, $employee_sid);
            //
        } else if (!empty($employee_profile_info['full_employment_application']) && !empty($applicant_profile_info['full_employment_application'])) {
            //
            $fef = unserialize($employee_profile_info['full_employment_application']);
            $faf = unserialize($applicant_profile_info['full_employment_application']);
            //
            foreach ($faf as $k => $v) {
                //
                if (!isset($fef[$k]) || empty($fef[$k])) {
                    $fef[$k] = $v;
                }
            }
            //
            $employer_data['full_employment_application'] = serialize($fef);
            //
            $update_flag = 1;
        }

        //
        if (
            (empty($employee_profile_info['Location_Country']) || $employee_profile_info['Location_Country'] == NULL) &&
            (empty($employee_profile_info['Location_State']) || $employee_profile_info['Location_State'] == NULL) &&
            (empty($employee_profile_info['Location_City']) || $employee_profile_info['Location_City'] == NULL) &&
            (empty($employee_profile_info['Location_Address']) || $employee_profile_info['Location_Address'] == NULL) &&
            (empty($employee_profile_info['Location_ZipCode']) || $employee_profile_info['Location_ZipCode'] == NULL)
        ) {
            $employer_data['Location_Country'] = $applicant_profile_info['country'];
            $employer_data['Location_State'] = $applicant_profile_info['state'];
            $employer_data['Location_City'] = $applicant_profile_info['city'];
            $employer_data['Location_Address'] = $applicant_profile_info['address'];
            $employer_data['Location_ZipCode'] = $applicant_profile_info['zipcode'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['PhoneNumber']) || $employee_profile_info['PhoneNumber'] == NULL) && !empty($applicant_profile_info['phone_number'])) {
            $employer_data['PhoneNumber'] = $applicant_profile_info['phone_number'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['YouTubeVideo']) || $employee_profile_info['YouTubeVideo'] == NULL) && !empty($applicant_profile_info['YouTube_Video'])) {
            $employer_data['YouTubeVideo'] = $applicant_profile_info['YouTube_Video'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['job_title']) || $employee_profile_info['job_title'] == NULL) && !empty($applicant_profile_info['desired_job_title'])) {
            $employer_data['job_title'] = $applicant_profile_info['desired_job_title'];
            $update_flag = 1;
        }

        //
        $emp_extra_unserial = unserialize($employee_profile_info['extra_info']);
        $emp_extra_keys = array();
        if ($emp_extra_unserial && sizeof($emp_extra_unserial)) {
            $emp_extra_keys = array_keys($emp_extra_unserial);
        }
        $app_extra_unserial = unserialize($applicant_profile_info['extra_info']);
        if ($app_extra_unserial && sizeof($app_extra_unserial)) {
            foreach ($app_extra_unserial as $key => $info) {

                if (!in_array($key, $emp_extra_keys)) {
                    $emp_extra_unserial[$key] = $info;
                    $update_flag = 1;
                }
            }

            if ($emp_extra_unserial && sizeof($emp_extra_unserial)) {
                $employer_data['extra_info'] = serialize($emp_extra_unserial);
            }
        }

        //
        if ((empty($employee_profile_info['linkedin_profile_url']) || $employee_profile_info['linkedin_profile_url'] == NULL) && !empty($applicant_profile_info['linkedin_profile_url'])) {
            $employer_data['linkedin_profile_url'] = $applicant_profile_info['linkedin_profile_url'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['ssn']) || $employee_profile_info['ssn'] == NULL) && !empty($applicant_profile_info['ssn'])) {
            $employer_data['ssn'] = $applicant_profile_info['ssn'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['dob']) || $employee_profile_info['dob'] == NULL) && !empty($applicant_profile_info['dob'])) {
            $employer_data['dob'] = $applicant_profile_info['dob'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['languages_speak']) || $employee_profile_info['languages_speak'] == NULL) && !empty($applicant_profile_info['languages_speak'])) {
            $employer_data['languages_speak'] = $applicant_profile_info['languages_speak'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['salary_benefits']) || $employee_profile_info['salary_benefits'] == NULL) && !empty($applicant_profile_info['salary_benefits'])) {
            $employer_data['salary_benefits'] = $applicant_profile_info['salary_benefits'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['workers_compensation_code']) || $employee_profile_info['workers_compensation_code'] == NULL) && !empty($applicant_profile_info['workers_compensation_code'])) {
            $employer_data['workers_compensation_code'] = $applicant_profile_info['workers_compensation_code'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['eeoc_code']) || $employee_profile_info['eeoc_code'] == NULL) && !empty($applicant_profile_info['eeoc_code'])) {
            $employer_data['eeoc_code'] = $applicant_profile_info['eeoc_code'];
            $update_flag = 1;
        }

        //
        if ((empty($employee_profile_info['hourly_rate']) || $employee_profile_info['hourly_rate'] == NULL || $employee_profile_info['hourly_rate'] == "0.00" || $employee_profile_info['hourly_rate'] == "0") && !empty($applicant_profile_info['hourly_rate'])) {
            $employer_data['hourly_rate'] = $applicant_profile_info['hourly_rate'];
            $update_flag = 1;
        }
        if ((empty($employee_profile_info['hourly_technician']) || $employee_profile_info['hourly_technician'] == NULL || $employee_profile_info['hourly_technician'] == "0.00" || $employee_profile_info['hourly_technician'] == "0") && !empty($applicant_profile_info['hourly_technician'])) {
            $employer_data['hourly_technician'] = $applicant_profile_info['hourly_technician'];
            $update_flag = 1;
        }
        if ((empty($employee_profile_info['flat_rate_technician']) || $employee_profile_info['flat_rate_technician'] == NULL || $employee_profile_info['flat_rate_technician'] == "0.00" || $employee_profile_info['flat_rate_technician'] == "0") && !empty($applicant_profile_info['flat_rate_technician'])) {
            $employer_data['flat_rate_technician'] = $applicant_profile_info['flat_rate_technician'];
            $update_flag = 1;
        }
        if ((empty($employee_profile_info['semi_monthly_salary']) || $employee_profile_info['semi_monthly_salary'] == NULL || $employee_profile_info['semi_monthly_salary'] == "0.00" || $employee_profile_info['semi_monthly_salary'] == "0") && !empty($applicant_profile_info['semi_monthly_salary'])) {
            $employer_data['semi_monthly_salary'] = $applicant_profile_info['semi_monthly_salary'];
            $update_flag = 1;
        }
        if ((empty($employee_profile_info['semi_monthly_draw']) || $employee_profile_info['semi_monthly_draw'] == NULL || $employee_profile_info['semi_monthly_draw'] == "0.00" || $employee_profile_info['semi_monthly_draw'] == "0")  && !empty($applicant_profile_info['semi_monthly_draw'])) {
            $employer_data['semi_monthly_draw'] = $applicant_profile_info['semi_monthly_draw'];
            $update_flag = 1;
        }
        if ((empty($employee_profile_info['uniform_top_size']) || $employee_profile_info['uniform_top_size'] == NULL || $employee_profile_info['uniform_top_size'] == "0.00" || $employee_profile_info['uniform_top_size'] == "0") && !empty($applicant_profile_info['uniform_top_size'])) {
            $employer_data['uniform_top_size'] = $applicant_profile_info['uniform_top_size'];
            $update_flag = 1;
        }
        if ((empty($employee_profile_info['uniform_bottom_size']) || $employee_profile_info['uniform_bottom_size'] == NULL) && !empty($applicant_profile_info['uniform_bottom_size'])) {
            $employer_data['uniform_bottom_size'] = $applicant_profile_info['uniform_bottom_size'];
            $update_flag = 1;
        }


        //
        $departmentsTeams = $this->hire_onboarding_applicant_model->get_applicant_department_team($company_sid, $applicant_sid);
        if ($employee_profile_info['department_sid'] == 0 && $departmentsTeams['department_sid'] != 0 && $employee_profile_info['team_sid'] == 0  && $departmentsTeams['team_sid'] != 0) {
            $employer_data['department_sid'] = $departmentsTeams['department_sid'];
            $employer_data['team_sid'] = $departmentsTeams['team_sid'];
        }

        $this->hire_onboarding_applicant_model->update_company_employee($employer_data, $applicant_sid, $employee_sid, 0);

        // now move all other information

        // 1) emergency_contacts
        $applicant_emergency_contacts = $this->hire_onboarding_applicant_model->update_employee_emergency_contacts($applicant_sid, $employee_sid);

        // 2) equipment_information
        $applicant_equipment_information = $this->hire_onboarding_applicant_model->update_employee_equipment_information($applicant_sid, $employee_sid);

        // 3) dependant_information
        $applicant_dependant_information = $this->hire_onboarding_applicant_model->update_employee_dependant_information($applicant_sid, $employee_sid);

        // 4) license_information
        $applicant_license_information = $this->hire_onboarding_applicant_model->update_employee_license_information($applicant_sid, $employee_sid);

        // 5) background_check_orderss
        $applicant_background_check = $this->hire_onboarding_applicant_model->update_employee_background_check($applicant_sid, $employee_sid);

        // 6) portal_misc_notes
        $applicant_misc_notes = $this->hire_onboarding_applicant_model->update_employee_misc_notes($applicant_sid, $employee_sid);

        // 7) private_message skipped as instructions
        $applicant_private_message = $this->hire_onboarding_applicant_model->update_applicant_private_message($applicant_email, $employee_sid);

        // 8) portal_applicant_rating
        $applicant_rating = $this->hire_onboarding_applicant_model->update_employee_rating($applicant_sid, $employee_sid);

        // 9) calendar events - portal_schedule_event
        $applicant_schedule_event = $this->hire_onboarding_applicant_model->update_employee_schedule_event($applicant_sid, $employee_sid);

        // 10) background check orders
        $applicant_schedule_event = $this->hire_onboarding_applicant_model->update_employee_background_check_order($applicant_sid, $employee_sid);

        // 11) portal_applicant_attachments
        $applicant_applicant_attachments = $this->hire_onboarding_applicant_model->update_employee_attachments($applicant_sid, $employee_sid);

        // 12) reference_checks
        $applicant_reference_checks = $this->hire_onboarding_applicant_model->update_employee_reference_checks($applicant_sid, $employee_sid);

        // 13) Onboarding Configuration
        $onboarding_configuration = $this->hire_onboarding_applicant_model->update_onboarding_configuration($applicant_sid, $employee_sid);

        // Groups
        $applicant_groups = $this->hire_onboarding_applicant_model->update_groups($applicant_sid, $employee_sid);

        // 14) Documents
        $documents = $this->hire_onboarding_applicant_model->update_documents($applicant_sid, $employee_sid);

        // 15) Direct Deposit Information
        $bank_details = $this->hire_onboarding_applicant_model->update_employee_direct_deposit_information($applicant_sid, $employee_sid);

        // 16) E Signature Data
        $e_signature_data = $this->hire_onboarding_applicant_model->update_employee_e_signature($applicant_sid, $employee_sid);

        // 17) Mark Applicant Onboarding as Complete
        $this->hire_onboarding_applicant_model->set_onboarding_as_completed($company_sid, $applicant_sid);

        // 18) Update training session skipped as instructed

        // 19) EEOC Form
        $eeoc = $this->hire_onboarding_applicant_model->update_employee_copy_eeoc_as_user($applicant_sid, $employee_sid);
        //
        $merge_applicant_data = [
            'user_profile' => $applicant_profile_info,
            'old_data_update' => $employer_data,
            'emergency_contacts' => $applicant_emergency_contacts,
            'equipment_information' => $applicant_equipment_information,
            'dependant_information' => $applicant_dependant_information,
            'license_information' => $applicant_license_information,
            'direct_deposit_information' => $bank_details,
            'e_signature' => $e_signature_data,
            'eeoc' => $eeoc,
            'group' => $applicant_groups,
            'documents' => $documents
        ];
        //
        $this->hire_onboarding_applicant_model->save_merge_applicant_info($merge_applicant_data, $applicant_sid, $employee_sid);
        //
        $array['status'] = "success";
        $array['message'] = "Success! Applicant is successfully merged!";
        $this->session->set_flashdata('message', '<b>Success:</b> Applicant Merged Successfully!');
        return print_r(json_encode($array));
    }
}
