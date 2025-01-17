<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dependents extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dependents_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library('pagination');
    }


    public function index($type = NULL, $sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_id = $data['session']['company_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];

            if ($sid == NULL && $type == NULL) {
                $employer_id = $data['session']['employer_detail']['sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'Dependent Details';
                $reload_location = 'dependants';
                $type = 'employee';
                $data['return_title_heading'] = "My Profile";
                $data['return_title_heading_link'] = base_url() . 'my_profile';
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($employer_id, 'employee');
                $load_view = check_blue_panel_status(false, 'self');
            } elseif ($type == 'employee') {

                if (!checkIfAppIsEnabled('etm')) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                    redirect(base_url('dashboard'), "refresh");
                }

                check_access_permissions($security_details, 'employee_management', 'employee_dependants');  // Param2: Redirect URL, Param3: Function Name
                $data = employee_right_nav($sid);
                $employer_id = $sid;
                $data['security_details'] = $security_details;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = 'Employee / Team Members Dependent Details';
                $reload_location = 'dependants/employee/' . $sid;
                $data['return_title_heading'] = "Employee Profile";
                $data['return_title_heading_link'] = base_url() . 'employee_profile/' . $sid;
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($sid, 'employee');
                $load_view = check_blue_panel_status(false, $type);
            } elseif ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking_system/active/all/all/all/all', 'applicant_dependants');  // Param2: Redirect URL, Param3: Function Name
                $data = applicant_right_nav($sid);
                $employer_id = $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant Dependent Details';
                $reload_location = 'dependants/applicant/' . $sid . '/' . $jobs_listing;
                $data['return_title_heading'] = "Applicant Profile";
                $data['return_title_heading_link'] = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $load_view = check_blue_panel_status(false, $type);
            }

            $data['employer_access_level'] = $employer_access_level;
            $data['sid'] = $sid;
            $full_access = false;
            $data['questions_sent'] = $this->dependents_model->check_sent_video_questionnaires($sid, $company_id);
            $data['questions_answered'] = $this->dependents_model->check_answered_video_questionnaires($sid, $company_id);

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No dependent found!');
                redirect('dashboard', 'refresh');
            }

            $dependant_info_data = $this->dependents_model->get_dependant_info($type, $employer_id);
            $dependant_count = count($dependant_info_data);

            foreach ($dependant_info_data as $key => $dependant_data) {
                $dependant_info_data[$key] = array_merge($dependant_data, unserialize($dependant_data['dependant_details']));
                //$dependant_info_data[$key]['sid'] = $dependant_data['sid'];
            }

            $data['emergency_contacts'] = $dependant_info_data;
            $data['dependant_count'] = $dependant_count;
            $data['full_access'] = $full_access;
            $data['left_navigation'] = $left_navigation;
            $data['type'] = $type;

            if ($type == 'employee') {
                $data["employer"] = $this->dependents_model->get_company_detail($employer_id);
            }

            if ($type == 'applicant') {
                $applicant_info = $this->dependents_model->get_applicants_details($sid);
                //                $data['applicant_info'] = $applicant_info;
                //getting Company accurate backgroud check
                $data['company_background_check'] = checkCompanyAccurateCheck($data['session']['company_detail']['sid']);

                //Outsourced HR Compliance and Onboarding check
                $data['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($data['session']['company_detail']['sid']);
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
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => 'Applicant'
                );

                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;
            }


            $url = '';
            switch ($type) {
                case 'employee':
                    $parent_sid = getEmployeeUserParent_sid($employer_id);
                    if ($parent_sid > 0) {
                        if ($company_id != $parent_sid) {
                            $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                            $url = base_url('employee_management');
                            redirect($url, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Does Not Exist In Db.
                        $url = base_url('employee_management');
                        redirect($url, 'refresh');
                    }
                    break;
                case 'applicant':
                    $parent_sid = getApplicantsEmployer_sid($employer_id);
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
                    break;
            }

            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data['employee'] = $data['session']['employer_detail'];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            $data['job_list_sid'] = $jobs_listing;
            $data['load_view'] = $load_view;

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

                $data['dependents_yes_text'] = $this->lang->line('dependents_yes_text');
                $data['dependents_no_text'] = $this->lang->line('dependents_no_text');

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/dependants');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'delete_dependent':
                        $users_sid = $this->input->post('users_sid');
                        $dependant_sid = $this->input->post('dependent_sid');
                        $users_type = 'employee'; // Need to check it why its hardcoded to employee

                        //
                        $this->load->model('2022/User_model', 'em');
                        //
                        $this->em->saveDifference([
                            'user_sid' => $users_sid,
                            'employer_sid' => ($users_sid == $this->session->userdata('logged_in')['employer_detail']['sid']
                                ? 0 : $this->session->userdata('logged_in')['employer_detail']['sid']),
                            'history_type' => 'dependent',
                            'profile_data' => json_encode(['action' => 'delete']),
                            'created_at' => date('Y-m-d H:i:s', strtotime('now'))
                        ]);


                        $this->dependents_model->delete_dependant($users_type, $users_sid, $dependant_sid, $company_id);

                        redirect($reload_location, 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit_dependant_information($sid = NULL, $type = NULL, $user_sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_id = $data['session']['company_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $employer_details  = $data['session']['employer_detail'];
            $data['type']      = $type;
            $data['user_sid']  = $user_sid;

            if ($sid == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Dependent not found!');
                if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                } else {
                    redirect(base_url('dashboard'), "refresh");
                }
            }

            $dependantData = $this->dependents_model->dependant_details($sid);
            //            $check_id = $this->dependents_model->check_authenticity($company_id, $sid, 'dependants');

            if (!empty($dependantData)) {
                $dependant_details = $dependantData[0];
            } else { // emergency contact does not exists.
                if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                } else {
                    redirect(base_url('settings'), "refresh");
                }
            }

            if ($company_id != $dependant_details['company_sid']) {
                $this->session->set_flashdata('message', '<b>Failed: </b>Not Authorized!');
                redirect(base_url('dependants'), "refresh");
            }

            $this->load->model('dashboard_model');

            //            if ($jobs_listing == NULL) {
            //                $security_sid = $data['session']['employer_detail']['sid'];
            //                $security_details = db_get_access_level_details($security_sid);
            //                $data['security_details'] = $security_details;
            //                $employer_id = $data['session']['employer_detail']['sid'];
            //                $data['employee'] = $data["session"]["employer_detail"];
            //                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
            //                $data['title'] = 'Edit Dependent Information';
            //                $reload_location = 'dependants';
            //                $type = 'employee';
            //                $data['employer'] = $this->dependents_model->get_company_detail($employer_id);
            //                $cancel_url = 'dependants';
            //                $data["return_title_heading"] = "Back to Dependent";
            //                $data["return_title_heading_link"] = base_url() . 'dependants';
            //                // getting applicant ratings - getting average rating of applicant
            //                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($employer_id, 'employee');
            //
            //                $data['sid'] = $sid;
            //                $load_view                                                      = check_blue_panel_status(false, 'self');
            //                $data['employer']                                               = $employer_details;
            //            } else
            if ($type == NULL) {
                // Emergency contact belongs to logged in employer.
                $employer_id = $data['session']['employer_detail']['sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'Edit Dependent Information';
                $reload_location = 'dependants';
                $type = 'employee';
                $data['employer'] = $this->dependents_model->get_company_detail($employer_id);
                $cancel_url = 'dependants';
                $data["return_title_heading"] = "Back to Dependent";
                $data["return_title_heading_link"] = base_url() . 'dependants';
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($employer_id, 'employee');
                $load_view                                                      = check_blue_panel_status(false, 'self');
                $data['employee']                                               = $employer_details;
            } else if ($dependant_details['users_type'] == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_depandants');  // Param2: Redirect URL, Param3: Function Name
                $data = employee_right_nav($dependant_details['users_sid']);
                // Emergency contact belongs to employee team members
                $employer_id = $dependant_details['users_sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = 'Edit Employee / Team Members Dependent Information';
                $reload_location = 'dependants/employee/' . $dependant_details['users_sid'];
                $data['employer'] = $this->dependents_model->get_company_detail($employer_id);
                $cancel_url = 'dependants/employee/' . $employer_id;
                $data["return_title_heading"] = "Back to Dependent";
                $data["return_title_heading_link"] = base_url() . 'dependants/employee/' . $dependant_details['users_sid'];
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($dependant_details['users_sid'], 'employee');
                $load_view        = check_blue_panel_status(false, $type);
                $data['employer'] = $this->dashboard_model->get_company_detail($user_sid);
            } else if ($dependant_details['users_type'] == 'applicant') {
                check_access_permissions($security_details, 'application_tracking_system/active/all/all/all/all', 'applicant_dependants');  // Param2: Redirect URL, Param3: Function Name
                $data = applicant_right_nav($dependant_details['users_sid']);
                // Emergency contact belongs to applicant
                $employer_id = $dependant_details['users_sid'];
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Edit Applicant Dependent Information';
                $reload_location = 'dependants/applicant/' . $dependant_details['users_sid'] . '/' . $jobs_listing;
                $cancel_url = 'dependants/applicant/' . $employer_id . '/' . $jobs_listing;
                $applicant_info = $this->dependents_model->get_applicants_details($dependant_details['users_sid']);
                //                $data['applicant_info'] = $applicant_info;
                //getting Company accurate backgroud check
                $data['company_background_check'] = checkCompanyAccurateCheck($data["session"]["company_detail"]["sid"]);
                //Outsourced HR Compliance and Onboarding check
                $data['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($data["session"]["company_detail"]["sid"]);
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
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => 'Applicant'
                );

                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;
                $data["return_title_heading"] = "Back to Dependent";
                $data["return_title_heading_link"] = base_url() . 'dependants/applicant/' . $dependant_details['users_sid'] . '/' . $jobs_listing;
                $load_view        = check_blue_panel_status(false, $type);
            }

            $data['employer_access_level'] = $employer_access_level;
            $full_access = false;

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            $data['full_access'] = $full_access;
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }
            $data['load_view'] = $load_view;
            $dependant_data = unserialize($dependant_details['dependant_details']);
            $dependant_data['sid'] = $dependant_details['sid'];
            $data['left_navigation'] = $left_navigation;
            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data['dependant_info'] = $dependant_data;
            $data['cancel_url'] = $cancel_url;
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('phone', 'Phone Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('relationship', 'Relationship', 'trim|xss_clean|required');

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

                $data['job_list_sid'] = $jobs_listing;
                $data['sid'] = $sid;
                //                if ($load_view == 'old') {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/dependants_edit');
                $this->load->view('main/footer');
                //                } else {
                //                    $this->load->view('onboarding/on_boarding_header', $data);
                //                    $this->load->view('onboarding/edit_dependents');
                //                    $this->load->view('onboarding/on_boarding_footer');
                //                }
            } else {
                //
                if ($type == 'employee') {
                    //
                    $this->load->model('2022/User_model', 'em');
                    //
                    $_POST['sid'] = $sid;
                    //
                    $this->em->handleGeneralDocumentChange(
                        'dependent',
                        $this->input->post(null, true),
                        null,
                        $user_sid,
                        $this->session->userdata('logged_in')['employer_detail']['sid']
                    );
                }
                //
                $formpost = $this->input->post(null, true);
                if (isset($formpost['txt_phonenumber'])) {
                    $formpost['phone'] = $formpost['txt_phonenumber'];
                    unset($formpost['txt_phonenumber']);
                }
                $dependantDataToSave['dependant_details'] = serialize($formpost);
                $this->dependents_model->update_dependant_info($sid, $dependantDataToSave);

                //
                $cpArray = [];
                $cpArray['company_sid'] = $company_id;
                $cpArray['user_sid'] = $sid;
                $cpArray['user_type'] = $type;
                $cpArray['document_sid'] = 0;
                $cpArray['document_type'] = 'dependents';
                //
                checkAndInsertCompletedDocument($cpArray);

                //
                checkAndUpdateDD($sid, $type, $company_id, 'dependents');

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
                    //
                } else $doSend = true;

                // Only send if dosend is true
                if ($doSend == true) {
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_TEMPLATE,
                        'general_information_status',
                        'dependent_details',
                        $company_id,
                        $data['session']['company_detail']['CompanyName'],
                        $userData['first_name'],
                        $userData['last_name'],
                        $sid,
                        [],
                        $type

                    );
                }
                $this->session->set_flashdata('message', '<b>Success:</b> Dependent info updated successfully');
                redirect($reload_location, "location");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_dependant_information($type = NULL, $sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_id = $data['session']['company_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];

            if ($sid == NULL && $type == NULL) {
                $employer_id = $data['session']['employer_detail']['sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'Add Dependent Information';
                $reload_location = 'dependants';
                $type = 'employee';
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($employer_id, 'employee');
                $data["return_title_heading"] = "Dependants";
                $data["return_title_heading_link"] = base_url() . 'dependants';
            } else if ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_depandants');  // Param2: Redirect URL, Param3: Function Name
                $data = employee_right_nav($sid);
                $employer_id = $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = 'Employee / Team Members Add Dependent Information';
                $reload_location = 'dependants/employee/' . $sid;
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($sid, 'employee');
                $data["return_title_heading"] = "Back to Dependent";
                $data["return_title_heading_link"] = base_url() . 'dependants/employee/' . $sid;
            } else if ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking_system/active/all/all/all/all', 'applicant_dependants');  // Param2: Redirect URL, Param3: Function Name
                $data = applicant_right_nav($sid);
                $employer_id = $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant Add Dependent Information';
                $reload_location = 'dependants/applicant/' . $sid . '/' . $jobs_listing;
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($employer_id, 'applicant');
                $data["return_title_heading"] = "Back to Dependent";
                $data["return_title_heading_link"] = base_url() . 'dependants/applicant/' . $sid . '/' . $jobs_listing;
            }

            $data['employer_access_level'] = $employer_access_level;
            $full_access = false;

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Dependent found!');
                redirect('dashboard', 'refresh');
            }
            if ($type == 'employee') {
                $data['employer'] = $this->dependents_model->get_company_detail($employer_id);
            }

            if ($type == 'applicant') {
                $applicant_info = $this->dependents_model->get_applicants_details($sid);
                //                $data['applicant_info'] = $applicant_info;
                //getting Company accurate backgroud check
                $data['company_background_check'] = checkCompanyAccurateCheck($data["session"]["company_detail"]["sid"]);

                //Outsourced HR Compliance and Onboarding check
                $data['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($data["session"]["company_detail"]["sid"]);
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
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => 'Applicant'
                );

                $data['applicant_average_rating'] = $this->dependents_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;
            }

            $data['full_access'] = $full_access;
            $data['left_navigation'] = $left_navigation;
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data['left_navigation'] = $left_navigation;
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('phone', 'Phone Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('relationship', 'Relationship', 'trim|xss_clean|required');
            $data['job_list_sid'] = $jobs_listing;

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

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/dependants_view');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(null, true);

                if (isset($formpost['txt_phonenumber'])) {
                    $formpost['phone'] = $formpost['txt_phonenumber'];
                    unset($formpost['txt_phonenumber']);
                }
                $dependantData['users_sid'] = $employer_id;
                $dependantData['users_type'] = $type;
                $dependantData['company_sid'] = $company_id;
                $dependantData['dependant_details'] = serialize($formpost);

                if (isDontHaveDependens($company_id, $employer_id, $type) > 0) {
                    isDontHaveDependensDelete($company_id, $employer_id, $type);
                }

                $this->dependents_model->save_dependant_info($dependantData);

                //
                $cpArray = [];
                $cpArray['company_sid'] = $company_id;
                $cpArray['user_sid'] = $sid;
                $cpArray['user_type'] = $type;
                $cpArray['document_sid'] = 0;
                $cpArray['document_type'] = 'dependents';
                //
                checkAndInsertCompletedDocument($cpArray);
                //
                checkAndUpdateDD($sid, $type, $company_id, 'dependents');

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
                    //
                } else $doSend = true;

                // Only send if dosend is true
                if ($doSend == true) {
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_TEMPLATE,
                        'general_information_status',
                        'dependent_details',
                        $company_id,
                        $data['session']['company_detail']['CompanyName'],
                        $userData['first_name'],
                        $userData['last_name'],
                        $sid,
                        [],
                        $type
                    );
                }
                $this->session->set_flashdata('message', '<b>Success:</b> Dependent info saved successfully');
                redirect(base_url($reload_location));
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    //
    public function add_dependant_information_donthave($type = NULL, $sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_id = $data['session']['company_detail']['sid'];

            if ($sid == NULL && $type == NULL) {
                $employer_id = $data['session']['employer_detail']['sid'];
                $reload_location = 'dependants';
                $type = 'employee';
            } else if ($type == 'employee') {
                $employer_id = $sid;
                $reload_location = 'dependants/employee/' . $sid;
            } else if ($type == 'applicant') {
                $employer_id = $sid;
                $reload_location = 'dependants/applicant/' . $sid . '/' . $jobs_listing;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Dependent found!');
                redirect('dashboard', 'refresh');
            }

            $dependantData['users_sid'] = $employer_id;
            $dependantData['users_type'] = $type;
            $dependantData['company_sid'] = $company_id;
            $dependantData['dependant_details'] = serialize([]);
            $dependantData['have_dependents'] = 0;

            haveDependensDelete($company_id, $employer_id, $type);

            if (isDontHaveDependens($company_id, $employer_id, $type) > 0) {
                $this->session->set_flashdata('message', '<strong>Success</strong> Saved!');
                redirect(base_url($reload_location));
            }

            $this->dependents_model->save_dependant_info($dependantData);
            checkAndUpdateDD($sid, $type, $company_id, 'dependents');

            $this->session->set_flashdata('message', '<b>Success:</b> Saved!');
            redirect(base_url($reload_location));
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
