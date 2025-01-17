<?php defined('BASEPATH') or exit('No direct script access allowed');

class Emergency_contacts extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('emergency_contacts_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library('pagination');
    }

    public function index($type = NULL, $sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $company_id                                                         = $data['session']['company_detail']['sid'];
            $company_detail                                                     = $data['session']['company_detail'];
            $employer_access_level                                              = $data['session']['employer_detail']['access_level'];
            $employer_details                                                   = $data['session']['employer_detail'];

            if ($sid == NULL && $type == NULL) {
                $employer_id                                                    = $employer_details['sid'];
                $parent_sid                                                     = $company_id;

                if ($company_id != $parent_sid) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                    $url = base_url('employee_management');
                    redirect($url, 'refresh');
                }

                $left_navigation                                                = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title']                                                  = 'Emergency Contacts';
                $reload_location                                                = 'emergency_contacts';
                $type                                                           = 'employee';
                $data['employer']                                               = $company_detail;
                $data['emp_app_sid']                                            = $employer_id;
                $data['return_title_heading']                                   = 'My Profile';
                $data['return_title_heading_link']                              = base_url('my_profile');
                $data['applicant_average_rating']                               = $this->emergency_contacts_model->getApplicantAverageRating($employer_id, 'employee'); // getting applicant ratings - getting average rating of applicant
                $load_view                                                      = check_blue_panel_status(false, 'self');
            } else if ($type == 'employee') {


                if (!checkIfAppIsEnabled('etm')) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                    redirect(base_url('dashboard'), "refresh");
                }
                
                check_access_permissions($security_details, 'employee_management', 'employee_emergency_contacts'); // Param2: Redirect URL, Param3: Function Name
                $data                                                           = employee_right_nav($sid, $data);
                $employer_id                                                    = $sid;
                $parent_sid                                                     = $company_id;

                if ($company_id != $parent_sid) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                    $url = base_url('employee_management');
                    redirect($url, 'refresh');
                }

                $left_navigation                                                = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title']                                                  = 'Employee / Team Members Emergency Contacts';
                $reload_location                                                = 'emergency_contacts/employee/' . $sid;
                //                $data['employer']                                               = $employer_details;
                $data['employer']                                               = $this->emergency_contacts_model->get_company_detail($employer_id);;
                $data['emp_app_sid']                                            = $sid;
                $data['return_title_heading']                                   = 'Employee Profile';
                $data['return_title_heading_link']                              = base_url() . 'employee_profile/' . $sid;
                $load_view                                                      = check_blue_panel_status(false, $type);
            } else if ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_emergency_contacts'); // Param2: Redirect URL, Param3: Function Name                
                $data                                                           = applicant_right_nav($sid, $jobs_listing);
                $employer_id                                                    = $sid;
                $left_navigation                                                = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title']                                                  = 'Applicant Emergency Contacts';
                $reload_location                                                = 'emergency_contacts/applicant/' . $sid . '/' . $jobs_listing;
                $data['return_title_heading']                                   = 'Applicant Profile';
                $data['return_title_heading_link']                              = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                //                $data['company_background_check']                               = checkCompanyAccurateCheck($company_id);
                //                $data['kpa_onboarding_check']                                   = checkCompanyKpaOnboardingCheck($company_id); //Outsourced HR Compliance and Onboarding check
                $applicant_info                                                 = $this->emergency_contacts_model->get_applicants_details($sid);
                $parent_sid                                                     = $applicant_info['company_sid'];
                $data['emp_app_sid']                                            = $sid;

                if ($parent_sid > 0) {
                    if ($company_id != $parent_sid) {
                        $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Exist In Db But Not in Same Company.
                        $url = base_url('application_tracking_system/active/all/all/all/all');
                        redirect($url, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Does Not Exist In Db.
                    $url = base_url('application_tracking_system/active/all/all/all/all');
                    redirect($url, 'refresh');
                }

                $data_employer = array(
                    'sid'                                   => $applicant_info['sid'],
                    'first_name'                            => $applicant_info['first_name'],
                    'last_name'                             => $applicant_info['last_name'],
                    'email'                                 => $applicant_info['email'],
                    'Location_Address'                      => $applicant_info['address'],
                    'Location_City'                         => $applicant_info['city'],
                    'Location_Country'                      => $applicant_info['country'],
                    'Location_State'                        => $applicant_info['state'],
                    'Location_ZipCode'                      => $applicant_info['zipcode'],
                    'PhoneNumber'                           => $applicant_info['phone_number'],
                    'profile_picture'                       => $applicant_info['pictures'],
                    'user_type'                             => ucwords($type)
                );

                $data['applicant_notes']                                        = $this->emergency_contacts_model->getApplicantNotes($sid); //Getting Notes
                $data['applicant_average_rating']                               = $this->emergency_contacts_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
                $data['employer']                                               = $data_employer;
                $load_view                                                      =  check_blue_panel_status(false, $type);
            }

            $data['load_view']                                                  = $load_view;
            $data['type']                                                       = $type;
            $data['employer_access_level']                                      = $employer_access_level;
            $full_access                                                        = false;
            $data['questions_sent']                                             = $this->emergency_contacts_model->check_sent_video_questionnaires($sid, $company_id);
            $data['questions_answered']                                         = $this->emergency_contacts_model->check_answered_video_questionnaires($sid, $company_id);

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Contact found!');
                redirect('dashboard', 'refresh');
            }

            $emergency_contacts                                                 = $this->emergency_contacts_model->get_emergency_contacts($type, $employer_id);
            $emergency_contacts_count                                           = count($emergency_contacts);
            $data['full_access']                                                = $full_access;
            $data['left_navigation']                                            = $left_navigation;
            $data_countries                                                     = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']]                                     = db_get_active_states($value['sid']);
            }

            $url = '';
            $data_states_encode                                                 = htmlentities(json_encode($data_states));
            $data['active_countries']                                           = $data_countries;
            $data['active_states']                                              = $data_states;
            $data['states']                                                     = $data_states_encode;
            $data['emergency_contacts']                                         = $emergency_contacts;
            $data['emergency_contacts_count']                                   = $emergency_contacts_count;
            $data['employee']                                                   = $employer_details;
            $data['company_sid']                                                = $company_id;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            $data['job_list_sid']                                               = $jobs_listing;

            if ($this->form_validation->run() === FALSE) {

                // Check and set the company sms module
                // phone number
                company_sms_phonenumber(
                    $data['session']['company_detail']['sms_module_status'],
                    $company_id,
                    $data,
                    $this
                );

                $data['company_sid'] = $company_id;
                $data['user_sid'] = $sid;
                $data['user_type'] = $type;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/emergency_contacts');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit_emergency_contacts($sid = NULL, $type = NULL, $user_sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $company_id                                                         = $data['session']['company_detail']['sid'];
            $employer_id                                                        = $data['session']['employer_detail']['sid'];
            $employer_access_level                                              = $data['session']['employer_detail']['access_level'];
            $company_detail                                                     = $data['session']['company_detail'];
            $employer_details                                                   = $data['session']['employer_detail'];
            $data['type']                                                       = $type;
            $data['user_sid']                                                   = $user_sid;

            if ($sid == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Emergency contact not found!');

                if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                } else {
                    redirect(base_url('dashboard'), 'refresh');
                }
            }
            $emergency_contacts                                                 = $this->emergency_contacts_model->emergency_contacts_details($sid);

            if (!empty($emergency_contacts)) {
                $emergency_contacts_details = $emergency_contacts[0];
            } else { // emergency contact does not exists.
                if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                } else {
                    redirect(base_url('dashboard'), 'refresh');
                }
            }

            $users_sid                                                          = $emergency_contacts_details['users_sid']; // this is the SID of employee or applicant who has added the emergency contact./
            $type                                                               = $emergency_contacts_details['users_type'];

            if ($users_sid == $employer_id) { // employee is editing his own emergency contact. it is allowed. 
                $full_access                                                    = true;
                $data                                                           = employee_right_nav($sid, $data);
                $security_sid                                                   = $employer_id;
                $security_details                                               = db_get_access_level_details($security_sid);
                $data['security_details']                                       = $security_details;
                $left_navigation                                                = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title']                                                  = 'My Emergency Contacts';
                $reload_location                                                = 'emergency_contacts/';
                $data['employer']                                               = $company_detail;
                $cancel_url                                                     = 'emergency_contacts/';
                $data['return_title_heading']                                   = 'Emergency Contact';
                $data['return_title_heading_link']                              = base_url('emergency_contacts');
                $load_view                                                      = check_blue_panel_status(false, 'self');
                $data['employee']                                               = $employer_details;
            } else {
                if ($type == 'employee') {
                    $parent_sid = getEmployeeUserParent_sid($employer_id);

                    if ($company_id != $parent_sid) {
                        $this->session->set_flashdata('message', '<b>Error:</b> Emergency contact not found!');

                        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                        } else {
                            redirect(base_url('employee_management'), 'refresh');
                        }
                    }

                    $data                                                       = employee_right_nav($user_sid, $data);
                    $security_sid                                               = $employer_id;
                    $security_details                                           = db_get_access_level_details($security_sid);
                    $data['security_details']                                   = $security_details;
                    check_access_permissions($security_details, 'employee_management', 'employee_emergency_contacts');  // Param2: Redirect URL, Param3: Function Name
                    $left_navigation                                            = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data['title']                                              = 'Edit Employee / Team Members Emergency Contacts';
                    $reload_location                                            = 'emergency_contacts/employee/' . $user_sid;
                    $cancel_url                                                 = 'emergency_contacts/employee/' . $user_sid;
                    $data['return_title_heading']                               = 'Emergency Contact';
                    $data['return_title_heading_link']                          = base_url('emergency_contacts/employee/') . $employer_id;
                    $load_view                                                  = check_blue_panel_status(false, $type);
                    $data['employer']                                           = $this->emergency_contacts_model->get_company_detail($user_sid);
                }

                if ($type == 'applicant') {
                    $data                                                       = applicant_right_nav($user_sid, $jobs_listing);
                    $security_sid                                               = $employer_id;
                    $security_details                                           = db_get_access_level_details($security_sid);
                    $data['security_details']                                   = $security_details;
                    check_access_permissions($security_details, 'application_tracking', 'applicant_emergency_contacts');  // Param2: Redirect URL, Param3: Function Name
                    $left_navigation                                            = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $data['title']                                              = 'Edit Applicant Emergency Contacts';
                    $reload_location                                            = 'emergency_contacts/applicant/' . $user_sid . '/' . $jobs_listing;
                    $cancel_url                                                 = 'emergency_contacts/applicant/' . $user_sid . '/' . $jobs_listing;
                    $applicant_info                                             = $this->emergency_contacts_model->get_applicants_details($user_sid);
                    //                    $applicant_info                                             = $this->emergency_contacts_model->get_applicants_details($employer_id);

                    $data_employer = array(
                        'sid'                               => $applicant_info['sid'],
                        'first_name'                            => $applicant_info['first_name'],
                        'last_name'                             => $applicant_info['last_name'],
                        'email'                                 => $applicant_info['email'],
                        'Location_Address'                      => $applicant_info['address'],
                        'Location_City'                         => $applicant_info['city'],
                        'Location_Country'                      => $applicant_info['country'],
                        'Location_State'                        => $applicant_info['state'],
                        'Location_ZipCode'                      => $applicant_info['zipcode'],
                        'PhoneNumber'                           => $applicant_info['phone_number'],
                        'profile_picture'                       => $applicant_info['pictures'],
                        'user_type'                             => 'Applicant'
                    );

                    $data['applicant_average_rating']                           = $this->emergency_contacts_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                    $data['employer']                                           = $data_employer;
                    $data['return_title_heading']                               = 'Back To Emergency Contact';
                    $data['return_title_heading_link']                          = base_url() . 'emergency_contacts/applicant/' . $emergency_contacts_details['users_sid'] . '/' . $jobs_listing;
                    $load_view                                                  = check_blue_panel_status(false, $type);
                }
            }

            $data['load_view']                                                  = $load_view;
            $data['employer_access_level']                                      = $employer_access_level;
            $full_access                                                        = false;

            if ($employer_access_level == 'Admin') {
                $full_access                                                    = true;
            }

            $data_countries                                                     = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']]                                     = db_get_active_states($value['sid']);
            }

            $data['full_access']                                                = $full_access;
            $data['left_navigation']                                            = $left_navigation;
            $data_states_encode                                                 = htmlentities(json_encode($data_states));
            $data['active_countries']                                           = $data_countries;
            $data['active_states']                                              = $data_states;
            $data['states']                                                     = $data_states_encode;
            $data['emergency_contacts']                                         = $emergency_contacts_details;
            $data['cancel_url']                                                 = $cancel_url;
            $data['job_list_sid']                                               = $jobs_listing;
            $data['emp_id']                                                     = $jobs_listing;
            $data['sid']                                                        = $sid;
            //            $data['employee']                                                   = $employer_details;
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            if ($data['contactOptionsStatus']['emergency_contact_email_status'] == 1) {
                $this->form_validation->set_rules('email', 'email', 'trim|xss_clean|required|valid_email');
            }
            $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
            if ($data['contactOptionsStatus']['emergency_contact_phone_number_status'] == 1) {
                $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean|required');
            }
            $this->form_validation->set_rules('Relationship', 'Relationship', 'trim|xss_clean|required');
            $this->form_validation->set_rules('priority', 'Priority', 'trim|xss_clean|required');
            $this->form_validation->set_message('is_unique', '%s is already registered!');

            if ($this->form_validation->run() === FALSE) {
                if (validation_errors() != false) {
                    $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                }

                // Check and set the company sms module
                // phone number
                company_sms_phonenumber(
                    $data['session']['company_detail']['sms_module_status'],
                    $company_id,
                    $data,
                    $this
                );

                $data['contactOptionsStatus'] = getEmergencyContactsOptionsStatus($company_id);

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/edit_emergency_contacts');
                $this->load->view('main/footer');
            } else {
                //
                if ($type == 'employee') {
                    //
                    $this->load->model('2022/User_model', 'em');
                    //
                    $_POST['sid'] = $sid;
                    //
                    $this->em->handleGeneralDocumentChange(
                        'emergencyContact',
                        $this->input->post(null, true),
                        null,
                        $user_sid,
                        $this->session->userdata('logged_in')['employer_detail']['sid']
                    );
                }
                //
                $update_data = array(
                    'first_name'                            => $this->input->post('first_name'),
                    'last_name'                             => $this->input->post('last_name'),
                    'email'                                 => $this->input->post('email'),
                    'Location_Country'                      => $this->input->post('Location_Country'),
                    'Location_State'                        => $this->input->post('Location_State'),
                    'Location_City'                         => $this->input->post('Location_City'),
                    'Location_ZipCode'                      => $this->input->post('Location_ZipCode'),
                    'Location_Address'                      => $this->input->post('Location_Address'),
                    'PhoneNumber'                           => $this->input->post('txt_phonenumber') ? $this->input->post('txt_phonenumber') : $this->input->post('PhoneNumber'),
                    'Relationship'                          => $this->input->post('Relationship'),
                    'priority'                              => $this->input->post('priority')
                );

                $sid                                                            = $this->input->post('sid');
                $result                                                         = $this->emergency_contacts_model->edit_emergency_contacts($update_data, $sid);

                $data['session'] = $this->session->userdata('logged_in');
                $this->load->model('direct_deposit_model');
                $userData = $this->direct_deposit_model->getUserData($sid, $type);

                //
                $cpArray = [];
                $cpArray['company_sid'] = $company_id;
                $cpArray['user_sid'] = $sid;
                $cpArray['user_type'] = $type;
                $cpArray['document_sid'] = 0;
                $cpArray['document_type'] = 'emergency_contacts';
                //
                checkAndInsertCompletedDocument($cpArray);

                //
                checkAndUpdateDD($sid, $type, $company_id, 'emergency_contacts');

                if ($type == 'employee') {
                    $this->load->model('direct_deposit_model');
                    $this->load->model('hr_documents_management_model');
                    $userData = $this->direct_deposit_model->getUserData($sid, $type);
                    //
                    $doSend = false;
                    //
                    if (array_key_exists('document_sent_on', $userData)) {
                        //
                        $doSend = false;
                        //
                        if (empty($userData['document_sent_on']) || $userData['document_sent_on'] > date('Y-m-d 23:59:59', strtotime('now'))) {
                            $doSend = true;
                            //
                            $this->hr_documents_management_model->update_employee($sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                        }
                    }

                    // Only send if dosend is true
                    if ($doSend == true) {
                        // Send document completion alert
                        broadcastAlert(
                            DOCUMENT_NOTIFICATION_TEMPLATE,
                            'general_information_status',
                            'emergency_contacts',
                            $company_id,
                            $data['session']['company_detail']['CompanyName'],
                            $userData['first_name'],
                            $userData['last_name'],
                            $sid,
                            [],
                            $type
                        );
                    }
                }
                redirect($reload_location, "location");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_emergency_contacts($type = NULL, $sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $company_id                                                         = $data['session']['company_detail']['sid'];
            $employer_access_level                                              = $data['session']['employer_detail']['access_level'];

            if ($sid == NULL && $type == NULL) {
                $employer_id                                                    = $data['session']['employer_detail']['sid'];
                $left_navigation                                                = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title']                                                  = 'Add Emergency Contacts';
                $reload_location                                                = 'emergency_contacts';
                $type                                                           = 'employee';
                $data['employer']                                               = $this->emergency_contacts_model->get_company_detail($employer_id);
                $data['return_title_heading']                                   = 'Emergency Contact';
                $data['return_title_heading_link']                              = base_url('emergency_contacts');
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating']                               = $this->emergency_contacts_model->getApplicantAverageRating($employer_id, 'employee');
            } else if ($type == 'employee') {
                $data                                                           = employee_right_nav($sid, $data);
                check_access_permissions($security_details, 'employee_management', 'employee_emergency_contacts');
                $employer_id                                                    = $sid;
                $left_navigation                                                = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title']                                                  = 'Employee / Team Members Add Emergency Contacts';
                $reload_location                                                = 'emergency_contacts/employee/' . $sid;
                $data['employer']                                               = $this->emergency_contacts_model->get_company_detail($employer_id);
                $data['return_title_heading']                                   = 'Back To Emergency Contact';
                $data['return_title_heading_link']                              = base_url('emergency_contacts/employee') . '/' . $sid;
            } else if ($type == 'applicant') {
                $data                                                           = applicant_right_nav($sid, $jobs_listing);
                check_access_permissions($security_details, 'application_tracking', 'applicant_emergency_contacts');  // Param2: Redirect URL, Param3: Function Name
                $employer_id                                                    = $sid;
                $left_navigation                                                = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title']                                                  = 'Applicant Add Emergency Contacts';
                $reload_location                                                = 'emergency_contacts/applicant/' . $sid . '/' . $jobs_listing;
                $applicant_info                                                 = $this->emergency_contacts_model->get_applicants_details($sid);

                $data_employer = array(
                    'sid'                                   => $applicant_info['sid'],
                    'first_name'                            => $applicant_info['first_name'],
                    'last_name'                             => $applicant_info['last_name'],
                    'email'                                 => $applicant_info['email'],
                    'Location_Address'                      => $applicant_info['address'],
                    'Location_City'                         => $applicant_info['city'],
                    'Location_Country'                      => $applicant_info['country'],
                    'Location_State'                        => $applicant_info['state'],
                    'Location_ZipCode'                      => $applicant_info['zipcode'],
                    'PhoneNumber'                           => $applicant_info['phone_number'],
                    'profile_picture'                       => $applicant_info['pictures']
                );

                $data['applicant_notes']                                        = $this->emergency_contacts_model->getApplicantNotes($sid); //Getting Notes
                $data['applicant_average_rating']                               = $this->emergency_contacts_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
                $data['employer']                                               = $data_employer;
                $data['return_title_heading']                                   = 'Back To Emergency Contact';
                $data['return_title_heading_link']                              = base_url('emergency_contacts/applicant/') . $sid . '/' . $jobs_listing;
            }

            $data['employer_access_level']                                      = $employer_access_level;
            $full_access                                                        = false;

            if ($employer_access_level == 'Admin') {
                $full_access                                                    = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Contact found!');
                redirect('dashboard', 'refresh');
            }

            $data['full_access']                                                = $full_access;
            $data['left_navigation']                                            = $left_navigation;
            $data_countries                                                     = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']]                                     = db_get_active_states($value['sid']);
            }

            $data_states_encode                                                 = htmlentities(json_encode($data_states));
            $data['active_countries']                                           = $data_countries;
            $data['active_states']                                              = $data_states;
            $data['states']                                                     = $data_states_encode;
            $data['job_list_sid']                                               = $jobs_listing;

            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            $data['contactOptionsStatus']['emergency_contact_email_status'] == 1 ? $this->form_validation->set_rules('email', 'email', 'trim|xss_clean|required|valid_email') : '';
            $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
            $data['contactOptionsStatus']['emergency_contact_phone_number_status'] == 1 ? $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean|required') : '';
            $this->form_validation->set_rules('Relationship', 'Relationship', 'trim|xss_clean|required');
            $this->form_validation->set_rules('priority', 'Priority', 'trim|xss_clean|required');
            $this->form_validation->set_message('is_unique', '%s is already registered!');

            if ($this->form_validation->run() === FALSE) {
                if (validation_errors() != false) {
                    $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                }

                // Check and set the company sms module
                // phone number
                company_sms_phonenumber(
                    $data['session']['company_detail']['sms_module_status'],
                    $company_id,
                    $data,
                    $this
                );

                $data['contactOptionsStatus'] = getEmergencyContactsOptionsStatus($company_id);

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/add_emergency_contacts');
                $this->load->view('main/footer');
            } else {
                $insert_data = array(
                    'users_sid'                             => $employer_id,
                    'users_type'                            => $type,
                    'first_name'                            => $this->input->post('first_name'),
                    'last_name'                             => $this->input->post('last_name'),
                    'email'                                 => $this->input->post('email'),
                    'Location_Country'                      => $this->input->post('Location_Country'),
                    'Location_State'                        => $this->input->post('Location_State'),
                    'Location_City'                         => $this->input->post('city'),
                    'Location_ZipCode'                      => $this->input->post('Location_ZipCode'),
                    'Location_Address'                      => $this->input->post('Location_Address'),
                    'PhoneNumber'                           => $this->input->post('txt_phonenumber') ? $this->input->post('txt_phonenumber') : $this->input->post('PhoneNumber'),
                    'Relationship'                          => $this->input->post('Relationship'),
                    'priority'                              => $this->input->post('priority')
                );

                $this->emergency_contacts_model->add_emergency_contacts($insert_data);

                $data['session'] = $this->session->userdata('logged_in');
                $this->load->model('direct_deposit_model');

                $userData = $this->direct_deposit_model->getUserData($sid, $type);

                //
                $cpArray = [];
                $cpArray['company_sid'] = $company_id;
                $cpArray['user_sid'] = $sid;
                $cpArray['user_type'] = $type;
                $cpArray['document_sid'] = 0;
                $cpArray['document_type'] = 'emergency_contacts';
                //
                checkAndInsertCompletedDocument($cpArray);

                //
                checkAndUpdateDD($sid, $type, $company_id, 'emergency_contacts');

                if ($type == 'employee') {
                    $this->load->model('direct_deposit_model');
                    $this->load->model('hr_documents_management_model');
                    $userData = $this->direct_deposit_model->getUserData($sid, $type);
                    //
                    $doSend = false;
                    //
                    if (array_key_exists('document_sent_on', $userData)) {
                        //
                        $doSend = false;
                        //
                        if (empty($userData['document_sent_on']) || $userData['document_sent_on'] > date('Y-m-d 23:59:59', strtotime('now'))) {
                            $doSend = true;
                            //
                            $this->hr_documents_management_model->update_employee($sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                        }
                    }

                    // Only send if dosend is true
                    if ($doSend == true) {
                        // Send document completion alert
                        broadcastAlert(
                            DOCUMENT_NOTIFICATION_TEMPLATE,
                            'general_information_status',
                            'emergency_contacts',
                            $company_id,
                            $data['session']['company_detail']['CompanyName'],
                            $userData['first_name'],
                            $userData['last_name'],
                            $sid,
                            [],
                            $type
                        );
                    }
                }
                redirect($reload_location, 'location');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function ajax_handler()
    {
        if ($this->input->is_ajax_request()) {
            $users_type = $this->input->post('users_type');
            $users_sid = $this->input->post('users_sid');
            $contact_sid = $this->input->post('contact_sid');
            //
            if ($users_type == 'employee') {
                //
                $this->load->model('2022/User_model', 'em');
                //
                $this->em->saveDifference([
                    'user_sid' => $users_sid,
                    'employer_sid' => ($users_sid == $this->session->userdata('logged_in')['employer_detail']['sid']
                        ? 0 : $this->session->userdata('logged_in')['employer_detail']['sid']),
                    'history_type' => 'emergencyContact',
                    'profile_data' => json_encode(['action' => 'delete']),
                    'created_at' => date('Y-m-d H:i:s', strtotime('now'))
                ]);
            }
            $this->emergency_contacts_model->delete_emergency_contact($users_type, $users_sid, $contact_sid);
            echo 'deleted';
        } else {
            echo 'unauthorized';
        }
    }
}
