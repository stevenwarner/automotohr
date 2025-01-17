<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reference_checks extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('reference_checks_model');
        $this->load->model('application_tracking_system_model');
        $this->load->model('users_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $session_details = $this->session->userdata('logged_in');
        $sid = $session_details['employer_detail']['sid'];
    }

    public function index($type = NULL, $sid = NULL, $jobs_listing = NULL) {
        if ($this->session->userdata('logged_in')) {           
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];            
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'reference_checks');
            
            if ($sid == NULL && $type == NULL) {
                $employer_id = $data['session']['employer_detail']['sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'Reference Checks';
                $reload_location = 'emergency_contacts';
                $cancel_url = base_url('reference_checks');
                $type = 'employee';
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee'); //getting average rating of applicant
            } else if ($type == 'employee') {
                
                if (!checkIfAppIsEnabled('etm')) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                    redirect(base_url('dashboard'), "refresh");
                }

                $data = employee_right_nav($sid);
                $employer_id = $sid;
                $data['return_title_heading'] = 'Employee Profile';
                $data['return_title_heading_link'] = base_url() . 'employee_profile/' . $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = 'Employee / Team Members Reference Checks';
                $reload_location = 'reference_checks/employee/' . $sid;
                $cancel_url = base_url('reference_checks/employee/' . $sid);
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'employee'); //getting average rating of applicant
            } else if ($type == 'applicant') {
                $data = applicant_right_nav($sid);
                $employer_id = $sid;
                $data['return_title_heading'] = 'Applicant Profile';
                $data['return_title_heading_link'] = base_url() . 'applicant_profile/' . $sid . '/' .$jobs_listing;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant Reference Checks';
                $reload_location = 'reference_checks/applicant/' . $sid . '/' .$jobs_listing;
                $cancel_url = base_url('reference_checks_applicant/' . $sid . '/' .$jobs_listing);
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
            }

            $data['cancel_url'] = $cancel_url;
            $data['employer'] = $this->dashboard_model->get_company_detail($employer_id);
            $data['employer_access_level'] = $employer_access_level;
            $full_access = false;
            $data['questions_sent'] = $this->application_tracking_system_model->check_sent_video_questionnaires($sid, $company_id);
            $data['questions_answered'] = $this->application_tracking_system_model->check_answered_video_questionnaires($sid, $company_id);

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Contact found!');
                redirect('dashboard', 'refresh');
            }

            if (isset($_POST['perform_action'])) {
                switch ($_POST['perform_action']) {
                    case 'delete_reference':
                        $reference_id = $_POST['sid'];
                        $this->reference_checks_model->SetStatusAs($reference_id, 'deleted');
                        break;
                    case 'send_questionnaire_link_by_email':
                        $reference_id = $_POST['sid'];
                        $reference = $this->reference_checks_model->GetReferenceById($reference_id);

                        if (!empty($reference)) {
                            $VerificationKey = '';
                            if ($reference['verification_key'] == null) {
                                $VerificationKey = generateRandomString(24);
                                $this->reference_checks_model->UpdateReferenceVerificationKey($reference_id, $VerificationKey);
                            } else {
                                $VerificationKey = $reference['verification_key'];
                            }

                            $referenceEmail = $reference['reference_email'];
                            $url = base_url('reference_questionnaire') . '/' . $VerificationKey;                           
                            $data2 = $this->session->userdata('logged_in');
                            $company_name = $data2['company_detail']['CompanyName'];
                            $company_sid = $data2['company_detail']['sid'];                           
                            $message_hf = message_header_footer($company_sid, $company_name);
                            $employment_reference_questionnaire_letter_check = $this->reference_checks_model->check_whether_table_exists('employment_reference_questionnaire_letter', $company_sid);
                                    
                            switch ($reference['users_type']) {
                                case 'employee':
                                    $userDetails = $this->reference_checks_model->GetUserDetails($reference['user_sid']);
                                    
                                    if(empty($employment_reference_questionnaire_letter_check)){
                                        $subject = 'Employment Reference Questionnaire';                                        
                                        $body = '';
                                        //$body .= '<p>Employment Reference Questionnaire</p>';
                                        $body .= $message_hf['header'];
                                        $body .= '<p>Dear ' . $reference['reference_name'] . ',</p>';
                                        $body .= '<p>' . ucwords($userDetails['first_name'] . ' ' . $userDetails['last_name']) . ' has provided your contact details as a reference for employment.</p>';
                                        $body .= '<p></p>';
                                        $body .= '<p>Please help ' . ucwords($userDetails['first_name'] . ' ' . $userDetails['last_name']) . ' by clicking on the following link and answering a short questionnaire:</p>';
                                        $body .= '<p><a target="_blank" href="' . $url . '">Employee Reference Questionnaire</a></p>';
                                        $body .= '<p></p>';
                                        //$body .= '<p>Employee Reference Questionnaire</p>';
                                        $body .= '<p>Thank You!</p>';
                                        $body .= '<p>---------------------------------------------------------</p>';
                                        $body .= '<p>Automated Email; Please Do Not reply!</p>';
                                        $body .= '<p>---------------------------------------------------------</p>';
                                        $body .= $message_hf['footer'];
                                    } else {
                                        $email_template = $employment_reference_questionnaire_letter_check[0];
                                        $subject = $email_template['subject'];                                        
                                        $body = $message_hf['header'];
                                        $body .= $email_template['message_body'];
                                        $body .= $message_hf['footer'];                                        
                                        $body = str_replace('{{applicant_name}}', $userDetails['first_name'] . ' ' . $userDetails['last_name'], $body);
                                        $body = str_replace('{{reference_name}}', $reference['reference_name'], $body);
                                        $body = str_replace('{{reference_questionnaire_link}}', '<a target="_blank" href="' . $url . '">Employee Reference Questionnaire</a>', $body);
                                    }
                                    
                                    break;                                    
                                case 'applicant':
                                    $applicantDetails = $this->reference_checks_model->GetApplicantDetails($reference['user_sid']);
                                    
                                    if(empty($employment_reference_questionnaire_letter_check)){
                                        $subject = 'Employment Reference Questionnaire';
                                        
                                        $body = '';
                                        $body .= $message_hf['header'];
                                        //$body .= '<p>Employment Reference Questionnaire</p>';
                                        $body .= '<p>Dear ' . $reference['reference_name'] . ',</p>';
                                        $body .= '<p>' . ucwords($applicantDetails['first_name'] . ' ' . $applicantDetails['last_name']) . ' has provided your contact details as a reference for employment.</p>';
                                        $body .= '<p></p>';
                                        $body .= '<p>Please help ' . ucwords($applicantDetails['first_name'] . ' ' . $applicantDetails['last_name']) . ' by clicking on the following link and answering a short questionnaire:</p>';
                                        $body .= '<p><a target="_blank" href="' . $url . '">Applicant Reference Questionnaire</a></p>';
                                        $body .= '<p></p>';
                                        //$body .= '<p>Employee Reference Questionnaire</p>';
                                        $body .= '<p>Thank You!</p>';
                                        $body .= '<p>---------------------------------------------------------</p>';
                                        $body .= '<p>Automated Email; Please Do Not reply!</p>';
                                        $body .= '<p>---------------------------------------------------------</p>';
                                        $body .= $message_hf['footer'];
                                    } else {
                                        $email_template = $employment_reference_questionnaire_letter_check[0];
                                        $subject = $email_template['subject'];                                       
                                        $body = $message_hf['header'];
                                        $body .= $email_template['message_body'];
                                        $body .= $message_hf['footer'];                                        
                                        $body = str_replace('{{applicant_name}}', $applicantDetails['first_name'] . ' ' . $applicantDetails['last_name'], $body);
                                        $body = str_replace('{{reference_name}}', $reference['reference_name'], $body);
                                        $body = str_replace('{{reference_questionnaire_link}}', '<a target="_blank" href="' . $url . '">Applicant Reference Questionnaire</a>', $body);                
                                    }
                                    break;
                            }
                           
                            if (base_url() == 'http://localhost/automotoCI/') {

                                $emailData = array( 'date' => date('Y-m-d H:i:s'),
                                                    'subject' => $subject,
                                                    'email' => $referenceEmail,
                                                    'message' => $body,
                                                    'username' => $data['session']['employer_detail']['sid']
                                                    );

                                $this->users_model->save_email_logs($emailData);
                                $this->session->set_flashdata('message', '<b>Notification: </b>Email has been successfully Sent!');
                                redirect($reload_location, 'refresh');
                            } else {
                                $emailData = array( 'date' => date('Y-m-d H:i:s'),
                                                    'subject' => $subject,
                                                    'email' => $referenceEmail,
                                                    'message' => $body,
                                                    'username' => $data['session']['employer_detail']['sid']
                                                );

                                $this->users_model->save_email_logs($emailData);
                                sendMail(FROM_EMAIL_NOTIFICATIONS, $referenceEmail, $subject, $body, $company_name, NULL);
                                $this->session->set_flashdata('message', '<b>Notification: </b>Email has been successfully Sent!');
                                redirect($reload_location, 'refresh');
                            }
                        }

                        break;
                }
            }
            
            $reference_checks = array();
            switch ($type) {
                case 'employee':
                    $employer = $this->dashboard_model->get_company_detail($sid);
                    $reference_checks = $this->reference_checks_model->GetAllReferences($employer_id, $company_id, $type);
                    break;
                case 'applicant':
                    $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                    //$data['applicant_info'] = $applicant_info;
                    //getting Company accurate backgroud check
                    $data['company_background_check'] = checkCompanyAccurateCheck($data["session"]["company_detail"]["sid"]);
                    //Outsourced HR Compliance and Onboarding check
                    $data['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($data["session"]["company_detail"]["sid"]);

                    $employer = array(  'sid' => $applicant_info['sid'],
                                        'first_name' => $applicant_info['first_name'],
                                        'last_name' => $applicant_info['last_name'],
                                        'email' => $applicant_info['email'],
                                        'Location_Address' => $applicant_info['address'],
                                        'Location_City' => $applicant_info['city'],
                                        'Location_Country' => $applicant_info['country'],
                                        'Location_State' => $applicant_info['state'],
                                        'Location_ZipCode' => $applicant_info['zipcode'],
                                        'PhoneNumber' => $applicant_info['phone_number'],
                                        'profile_picture' => $applicant_info['pictures'],
                                        'user_type' => 'Applicant'
                                    );

                    $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                    $reference_checks = $this->reference_checks_model->GetAllReferences($employer_id, $company_id, $type);
                    break;
            }

            $data["employer"] = $employer;
            $data['user_sid'] = $employer_id;
            $data['company_sid'] = $company_id;
            $data['users_type'] = $type;
            $data['reference_checks'] = $reference_checks;
            $data_countries = db_get_active_countries();
            
            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            //Match User Company and Session Company
            /*
              $url = '';
              switch($type){
              case 'employee':
              $parent_sid = getEmployeeUserParent_sid($employer_id);
              if($parent_sid > 0){
              if($company_id != $parent_sid){
              $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company

              $url = base_url('employee_management');
              redirect($url, 'refresh');
              }
              }else{
              $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Does Not Exist In Db.

              $url = base_url('employee_management');
              redirect($url, 'refresh');
              }
              break;
              case 'applicant':
              $parent_sid = getApplicantsEmployer_sid($employer_id);
              if($parent_sid > 0){
              if($company_id != $parent_sid){
              $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Exist In Db But Not in Same Company.

              $url = base_url('application_tracking_system/active/all/all/all/all');
              redirect($url, 'refresh');
              }
              }else{
              $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!');// Applicant Does Not Exist In Db.

              $url = base_url('application_tracking_system/active/all/all/all/all');
              redirect($url, 'refresh');
              }
              break;
              } */

            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data['full_access'] = $full_access;
            $data['left_navigation'] = $left_navigation;
            $data['job_list_sid'] = $jobs_listing;

            if ($this->form_validation->run() === FALSE) {
                    $this->load->view('main/header', $data);
                    $this->load->view('reference_checks/reference_checks');
                    $this->load->view('main/footer');
                }
        } else {
            redirect('login', "refresh");
        }
    }

    public function edit($type = NULL, $sid = NULL, $jobs_listing = NULL, $reference_id = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'reference_checks');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $full_access = false;
            
            if ($employer_access_level == 'Admin') { //Check Access Level
                $full_access = true;
            }

            $employer = array();
            switch ($type) {
                case 'employee':
                    $data = employee_right_nav($sid); //Specific Employee
                    $backUrl = base_url('reference_checks') . '/employee/' . $sid;
                    $data['backUrl'] = $backUrl;
                    $employer_id = $sid;
                    $employer = $this->dashboard_model->get_company_detail($sid);
                    $reference = $this->reference_checks_model->GetReference($reference_id, $employer_id, $company_id, $type);

                    if (!empty($reference)) {
                        $reference = $reference[0];
                    }

                    $cancel_url = base_url('reference_checks/employee/' . $sid);
                    $data['employer'] = $employer;
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data['cancel_url'] = $cancel_url;
                    break;
                case 'applicant':                   
                    $data = applicant_right_nav($sid); //Specific Applicant
                    $backUrl = base_url('reference_checks') . '/applicant/' . $sid . '/' .$jobs_listing;
                    $data['backUrl'] = $backUrl;
                    $employer_id = $sid;
                    $applicant_info = $this->dashboard_model->get_applicants_details($sid);

                    $data_employer = array( 'sid' => $applicant_info['sid'],
                                            'first_name' => $applicant_info['first_name'],
                                            'last_name' => $applicant_info['last_name'],
                                            'email' => $applicant_info['email'],
                                            'Location_Address' => $applicant_info['address'],
                                            'Location_City' => $applicant_info['city'],
                                            'Location_Country' => $applicant_info['country'],
                                            'Location_State' => $applicant_info['state'],
                                            'Location_ZipCode' => $applicant_info['zipcode'],
                                            'PhoneNumber' => $applicant_info['phone_number']
                                        );

                    $cancel_url = base_url('reference_checks/applicant/' . $sid . '/' .$jobs_listing);
                    $data['employer'] = $data_employer;
                    $reference = $this->reference_checks_model->GetReference($reference_id, $employer_id, $company_id, $type);

                    if (!empty($reference)) {
                        $reference = $reference[0];
                    }

                    $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $data['cancel_url'] = $cancel_url;
                    break;
                default: //Logged In User
                    $backUrl = base_url('reference_checks');
                    $data['backUrl'] = $backUrl;
                    $employer_id = $data['session']['employer_detail']['sid'];
                    $employer = $this->dashboard_model->get_company_detail($employer_id);
                    $cancel_url = base_url('reference_checks');
                    $data['employer'] = $employer;
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                    $data['cancel_url'] = $cancel_url;
                    break;
            }

            $dataToSave = array();
            if (isset($_POST['perform_action'])) {
                if ($_POST['perform_action'] == 'save_work_reference') {
                    $reference_type = $_POST['reference_type'];
                    $reference_title = $_POST['reference_title'];
                    $reference_name = $_POST['reference_name'];
                    $organization_name = $_POST['organization_name'];
                    $department_name = $_POST['department_name'];
                    $branch_name = $_POST['branch_name'];
                    $reference_relation = $_POST['reference_relation'];
                    $work_period_start = $_POST['work_period_start'];
                    $work_period_end = $_POST['work_period_end'];
                    $reference_email = $_POST['reference_email'];
                    $reference_phone =  $this->input->post('txt_phonenumber', true) ? $this->input->post('txt_phonenumber', true) : $_POST['reference_phone'];
                    $work_other_information = $_POST['work_other_information'];
                    $best_time_to_call = $_POST['best_time_to_call'];

                    if ($work_period_start == '') {
                        $work_period_start = null;
                    } else {
                        $work_period_start = date('Y-m-d H:i:s', formatDateForDb($work_period_start));
                    }

                    if ($work_period_end == '') {
                        $work_period_end = null;
                    } else {
                        $work_period_end = date('Y-m-d H:i:s', formatDateForDb($work_period_end));
                    }

                    $dataToSave = array(
                                        'reference_type' => $reference_type,
                                        'reference_title' => $reference_title,
                                        'reference_name' => $reference_name,
                                        'organization_name' => $organization_name,
                                        'department_name' => $department_name,
                                        'branch_name' => $branch_name,
                                        'program_name' => '',
                                        'reference_relation' => $reference_relation,
                                        'period_start' => $work_period_start,
                                        'period_end' => $work_period_end,
                                        'reference_email' => $reference_email,
                                        'reference_phone' => $reference_phone,
                                        'other_information' => $work_other_information,
                                        'best_time_to_call' => $best_time_to_call
                                    );
                }

                if ($_POST['perform_action'] == 'save_personal_reference') {
                    $reference_type = $_POST['reference_type'];
                    $reference_title = $_POST['reference_title'];
                    $reference_name = $_POST['reference_name'];
                    //$organization_name = $_POST['organization_name'];
                    //$department_name = $_POST['department_name'];
                    //$branch_name = $_POST['branch_name'];
                    //$program_name = $_POST['program_name'];
                    $reference_relation = $_POST['reference_relation'];
                    //$work_period_start = $_POST['personal_period_start'];
                    //$work_period_end = $_POST['personal_period_end'];
                    $period = $_POST['relationship_period'];
                    $reference_email = $_POST['reference_email'];
                    $reference_phone = $this->input->post('txt_phonenumber', true) ? $this->input->post('txt_phonenumber', true) : $_POST['reference_phone'];
                    $work_other_information = $_POST['work_other_information'];
                    $best_time_to_call = $_POST['best_time_to_call'];

                    $dataToSave = array(
                                        'reference_type' => $reference_type,
                                        'reference_title' => $reference_title,
                                        'reference_name' => $reference_name,
                                        'organization_name' => '',
                                        'department_name' => '',
                                        'branch_name' => '',
                                        'program_name' => '',
                                        'reference_relation' => $reference_relation,
                                        'period_start' => date('Y-m-d H:i:s', formatDateForDb('01-01-1970')),
                                        'period_end' => date('Y-m-d H:i:s', formatDateForDb('01-01-1970')),
                                        'period' => $period,
                                        'reference_email' => $reference_email,
                                        'reference_phone' => $reference_phone,
                                        'other_information' => $work_other_information,
                                        'best_time_to_call' => $best_time_to_call
                                    );
                }

                if ($_POST['perform_action'] == 'save_other_reference') {
                    $reference_type = $_POST['reference_type'];
                    $reference_title = $_POST['reference_title'];
                    $reference_name = $_POST['reference_name'];
                    $organization_name = '';
                    $department_name = '';
                    $branch_name = '';
                    $program_name = '';
                    $reference_relation = $_POST['reference_relation'];
                    $work_period_start = '';
                    $work_period_end = '';
                    $period = $_POST['period'];
                    $reference_email = $_POST['reference_email'];
                    $reference_phone = $this->input->post('txt_phonenumber', true) ? $this->input->post('txt_phonenumber', true) : $_POST['reference_phone'];
                    $work_other_information = $_POST['work_other_information'];
                    $best_time_to_call = $_POST['best_time_to_call'];

                    $dataToSave = array(
                                        'reference_type' => $reference_type,
                                        'reference_title' => $reference_title,
                                        'reference_name' => $reference_name,
                                        'organization_name' => $organization_name,
                                        'department_name' => $department_name,
                                        'branch_name' => $branch_name,
                                        'program_name' => $program_name,
                                        'reference_relation' => $reference_relation,
                                        'period_start' => date('Y-m-d H:i:s', formatDateForDb('01-01-1970')),
                                        'period_end' => date('Y-m-d H:i:s', formatDateForDb('01-01-1970')),
                                        'period' => '',
                                        'reference_email' => $reference_email,
                                        'reference_phone' => $reference_phone,
                                        'other_information' => $work_other_information,
                                        'best_time_to_call' => $best_time_to_call
                                    );
                }

                if ($reference_id == NULL) {
                    $this->reference_checks_model->Save(null, $sid, $company_id, $type, $dataToSave);
                } else {
                    $this->reference_checks_model->Save($reference_id, $sid, $company_id, $type, $dataToSave);
                }

                $url = 'reference_checks' . '/' . $type . '/' . $sid. '/' .$jobs_listing;
                redirect($url, 'refresh');
            }

            $data['reference'] = $reference;
            $data['title'] = 'Reference Checks';
            $data['left_navigation'] = $left_navigation;
            $data['employer_access_level'] = $employer_access_level;
            $data['full_access'] = $full_access;
            $data['job_list_sid'] = $jobs_listing;
            $this->load->view('main/header', $data);
            $this->load->view('reference_checks/reference_checks_edit');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function questionnaire($type = NULL, $sid = NULL, $jobs_listing = NULL, $reference_id = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'reference_checks');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $full_access = false;
            
            if ($employer_access_level == 'Admin') { //Check Access Level
                $full_access = true;
            }

            $employer = array();

            switch ($type) {
                case 'employee':
                    $data = employee_right_nav($sid); //Specific Employee
                    $backUrl = base_url('reference_checks') . '/employee/' . $sid;
                    $reloadLocation = 'reference_checks' . '/employee/' . $sid;
                    $data['backUrl'] = $backUrl;
                    $employer_id = $sid;
                    $employer = $this->dashboard_model->get_company_detail($sid);
                    $reference = $this->reference_checks_model->GetReference($reference_id, $employer_id, $company_id, $type);

                    if (!empty($reference)) {
                        $reference = $reference[0];
                    }

                    $data["employer"] = $employer;
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee';
                    break;
                case 'applicant':
                    $data = applicant_right_nav($sid); //Specific Applicant
                    $backUrl = base_url('reference_checks') . '/applicant/' . $sid . '/' .$jobs_listing;
                    $reloadLocation = 'reference_checks' . '/applicant/' . $sid . '/' .$jobs_listing;
                    $data['backUrl'] = $backUrl;
                    $employer_id = $sid;
                    $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                    $job_details = $this->dashboard_model->get_listing($applicant_info['job_sid'], $company_id);
                    $data_employer = array(
                                            'sid' => $applicant_info['sid'],
                                            'first_name' => $applicant_info['first_name'],
                                            'last_name' => $applicant_info['last_name'],
                                            'email' => $applicant_info['email'],
                                            'Location_Address' => $applicant_info['address'],
                                            'Location_City' => $applicant_info['city'],
                                            'Location_Country' => $applicant_info['country'],
                                            'Location_State' => $applicant_info['state'],
                                            'Location_ZipCode' => $applicant_info['zipcode'],
                                            'PhoneNumber' => $applicant_info['phone_number'],
                                            'job_title' => $job_details['Title']
                                        );

                    $data['employer'] = $data_employer;
                    $reference = $this->reference_checks_model->GetReference($reference_id, $employer_id, $company_id, $type);
                    
                    if (!empty($reference)) {
                        $reference = $reference[0];
                    }

                    $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';

                    break;
                default: //Logged In User
                    $backUrl = base_url('reference_checks');
                    $reloadLocation = 'reference_checks';
                    $data['backUrl'] = $backUrl;
                    $employer_id = $data['session']['employer_detail']['sid'];
                    $employer = $this->dashboard_model->get_company_detail($employer_id);
                    $data["employer"] = $employer;
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                    break;
            }

            //Handle Post
            if (isset($_POST['perform_action'])) {
                switch ($_POST['perform_action']) {
                    case 'save_work_reference_questionnaire_information':
                        $dataToSave = array(
                            'position' => $_POST['position'],
                            'work_period_start' => $_POST['work_period_start'],
                            'work_period_end' => $_POST['work_period_end'],
                            'final_salary' => $_POST['final_salary'],
                            'duties_description' => $_POST['duties_description'],
                            'performance' => $_POST['performance'],
                            'late_or_absent' => $_POST['late_or_absent'],
                            'teamwork' => $_POST['teamwork'],
                            'follow_directions' => $_POST['follow_directions'],
                            'assignments_performance' => $_POST['assignments_performance'],
                            'assignments_performance_timely' => $_POST['assignments_performance_timely'],
                            'decision_making_and_work_independently' => $_POST['decision_making_and_work_independently'],
                            'written_and_verbal_communication' => $_POST['written_and_verbal_communication'],
                            'duties_best_performed' => $_POST['duties_best_performed'],
                            'areas_of_improvement' => $_POST['areas_of_improvement'],
                            'disciplinary_record' => $_POST['disciplinary_record'],
                            'dishonesty_insubordination' => $_POST['dishonesty_insubordination'],
                            'reason_for_leaving' => $_POST['reason_for_leaving'],
                            'would_re_employ' => $_POST['would_re_employ'],
                            'referee_name' => $_POST['referee_name'],
                            'referee_title' => $_POST['referee_title'],
                            'conducted_date' => $_POST['conducted_date'],
                            'should_accept' => $_POST['should_accept'],
                            //Additional Fields for Making the save / load work for both work and personal reference.
                            'period_known' => '',
                            'personal_setting' => '',
                            'how_well_you_know' => '',
                            'brief_description_of_success' => '',
                            'strengths_and_weaknesses' => '',
                            'writing_skills' => '',
                            'leadership' => '',
                            'punctual' => '',
                            'work_attitude' => '',
                            'outstanding_abilities' => '',
                            'follow_instructions' => '',
                            'self_starter_or_motivated_by_others' => '',
                            'stressful_situations' => '',
                            'difficult_people' => '',
                            'tactful_manner' => '',
                            'accomplishments' => '',
                            'development_areas' => '',
                            'advice' => ''
                        );

                        $conducted_by = $_POST['conducted_by'];
                        $this->reference_checks_model->UpdateQuestionnairInformation($reference_id, $employer_id, $company_id, $type, $dataToSave, $conducted_by);
                        redirect($reloadLocation, 'refresh');
                        break;
                    case 'save_personal_reference_questionnaire_information':
                        $dataToSave = array(
                            'period_known' => $_POST['period_known'],
                            'personal_setting' => $_POST['personal_setting'],
                            'how_well_you_know' => $_POST['how_well_you_know'],
                            'brief_description_of_success' => $_POST['brief_description_of_success'],
                            'strengths_and_weaknesses' => $_POST['strengths_and_weaknesses'],
                            'writing_skills' => $_POST['writing_skills'],
                            'leadership' => $_POST['leadership'],
                            'punctual' => $_POST['punctual'],
                            'work_attitude' => $_POST['work_attitude'],
                            'outstanding_abilities' => $_POST['outstanding_abilities'],
                            'follow_instructions' => $_POST['follow_instructions'],
                            'self_starter_or_motivated_by_others' => $_POST['self_starter_or_motivated_by_others'],
                            'stressful_situations' => $_POST['stressful_situations'],
                            'difficult_people' => $_POST['difficult_people'],
                            'tactful_manner' => $_POST['tactful_manner'],
                            'accomplishments' => $_POST['accomplishments'],
                            'development_areas' => $_POST['development_areas'],
                            'advice' => $_POST['advice'],
                            'should_accept' => $_POST['should_accept'],
                            'referee_name' => $_POST['referee_name'],
                            'referee_title' => $_POST['referee_title'],
                            'conducted_date' => $_POST['conducted_date'],
                            //Additional Fields for Making the save / load work for both work and personal reference.
                            'position' => '',
                            'work_period_start' => '',
                            'work_period_end' => '',
                            'final_salary' => '',
                            'duties_description' => '',
                            'performance' => '',
                            'late_or_absent' => '',
                            'teamwork' => '',
                            'follow_directions' => '',
                            'assignments_performance' => '',
                            'assignments_performance_timely' => '',
                            'decision_making_and_work_independently' => '',
                            'written_and_verbal_communication' => '',
                            'duties_best_performed' => '',
                            'areas_of_improvement' => '',
                            'disciplinary_record' => '',
                            'dishonesty_insubordination' => '',
                            'reason_for_leaving' => '',
                            'would_re_employ' => ''
                        );

                        $conducted_by = $_POST['conducted_by'];
                        $this->reference_checks_model->UpdateQuestionnairInformation($reference_id, $employer_id, $company_id, $type, $dataToSave, $conducted_by);
                        redirect($reloadLocation, 'refresh');
                        break;
                }
            }


            if (!empty($reference['questionnaire_information'])) {
                $data['questionnaire_information'] = unserialize($reference['questionnaire_information']);
            } else {
                $data['questionnaire_information'] = array();
            }

            $data['questionnare_for'] = $type;
            $data['reference'] = $reference;
            $data['title'] = 'Reference Checks Questionnaire';
            $data['left_navigation'] = $left_navigation;
            $data['employer_access_level'] = $employer_access_level;
            $data['full_access'] = $full_access;
            $data['reference_status'] = $reference['status'];
            $data['job_list_sid'] = $jobs_listing;
            $this->load->view('main/header', $data);
            $this->load->view('reference_checks/reference_checks_questionnaire');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }
}