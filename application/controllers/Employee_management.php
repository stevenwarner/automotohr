<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_management extends Public_Controller {

    private $limit = 100; 
    public function __construct() {
        parent::__construct();
        $this->load->model('employee_model');
        $this->load->model('dashboard_model');
        $this->load->model('application_tracking_system_model');
        $this->form_validation->set_error_delimiters('<p class="error"><i class="fa fa-exclamation-circle"></i> ', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->library("pagination");
    }

    public function archived_employee() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'employee_management'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $keyword = '';
            $employee_type = 'active';

            if (isset($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
            }

            if (isset($_GET['employee_type'])) {
                $employee_type = $_GET['employee_type'];
            }

            $data['archived'] = 1;
            $data['keyword'] = $keyword;
            $data['employee_type'] = $employee_type;
//            $data["employees"] = $this->employee_model->get_active_employees_detail($company_id, $employer_id, $keyword, 1);
            $data["employees"] = $this->employee_model->get_inactive_employees_detail($company_id, $employer_id, $keyword, 1);
            $data['title'] = "Archived Employee / Team Members";

            if (isset($_POST['deactivate_employees']) && $_POST['deactivate_employees'] == 'true') {
                $deactivate_fields = $_POST['ej_check'];

                foreach ($deactivate_fields as $key => $value) {
                    $this->employee_model->deactivate_employee_by_id($value);
                }

                $this->session->set_flashdata('message', '<b>Success: </b>Employee(s) / Team Member(s) Deactivated!');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            if (isset($_POST['activate_employees']) && $_POST['activate_employees'] == 'true') {
                $deactivate_fields = $_POST['ej_check'];

                foreach ($deactivate_fields as $key => $value) {
                    $this->employee_model->activate_employee_by_id($value);
                }

                $this->session->set_flashdata('message', '<b>Success: </b>Employee(s) / Team Member(s) Activated!');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/archive_employee_management');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function terminated_employee() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'employee_management'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $keyword = '';
            $employee_type = 'active';
            $data['employer_id'] = $employer_id = $data['session']['employer_detail']['sid'];

            if (isset($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
            }

            if (isset($_GET['employee_type'])) {
                $employee_type = $_GET['employee_type'];
            }

            $keyword = '';
            $order_by = '';
            $order = '';
            $employee_type = 'active';

            if (isset($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
            }

            if (isset($_GET['employee_type'])) {
                $employee_type = $_GET['employee_type'];
            }

            if (isset($_GET['order_by'])) {
                $order_by = $_GET['order_by'];
            }

            if (isset($_GET['order'])) {
                $order = $_GET['order'];
            }
            if(empty($order_by)){
                $order_by = 'sid';
            }
            if(empty($order)){
                $order = 'desc';
            }
            $data['order_by'] = $order_by;
            $data['order'] = $order;
            //
            if($order_by == 'termination_date'){
                $order_by = 'terminated_employees.termination_date';
            }

            $data['archived'] = 0;
            if(empty($order_by)){
                $order_by = 'users.sid';
            }

            $data['archived'] = 1;
            $data['keyword'] = $keyword;
            $data['employee_type'] = $employee_type;
//            $data["employees"] = $this->employee_model->get_active_employees_detail($company_id, $employer_id, $keyword, 1);
            $data["employees"] = $this->employee_model->get_terminated_employees_detail($company_id, $employer_id, $keyword, 1, $order_by, $order);
            $data['title'] = "Terminated Employee / Team Members";

            if (isset($_POST['deactivate_employees']) && $_POST['deactivate_employees'] == 'true') {
                $deactivate_fields = $_POST['ej_check'];

                foreach ($deactivate_fields as $key => $value) {
                    $this->employee_model->deactivate_employee_by_id($value);
                }

                $this->session->set_flashdata('message', '<b>Success: </b>Employee(s) / Team Member(s) Deactivated!');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            if (isset($_POST['activate_employees']) && $_POST['activate_employees'] == 'true') {
                $deactivate_fields = $_POST['ej_check'];

                foreach ($deactivate_fields as $key => $value) {
                    $this->employee_model->activate_employee_by_id($value);
                }

                $this->session->set_flashdata('message', '<b>Success: </b>Employee(s) / Team Member(s) Activated!');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/terminated_employee');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function employee_management() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'employee_management'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $data['employer_id'] = $employer_id = $data['session']['employer_detail']['sid'];
            $ems_status = $data['session']['company_detail']['ems_status'];
            $keyword = '';
            $order_by = '';
            $order = '';
            $employee_type = 'active';

            if (isset($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
            }

            if (isset($_GET['employee_type'])) {
                $employee_type = $_GET['employee_type'];
            }

            if (isset($_GET['order_by'])) {
                $order_by = $_GET['order_by'];
            }

            if (isset($_GET['order'])) {
                $order = $_GET['order'];
            }

            $data['archived'] = 0;
            $data['ems_status'] = $ems_status;
            $data['keyword'] = $keyword;
            $data['employee_type'] = $employee_type;
            $data['order_by'] = $order_by;
            $data['order'] = $order;
            if(empty($order_by)){
                $order_by = 'sid';
            }
            if(empty($order)){
                $order = 'desc';
            }

            if (isset($_GET['department']) && $_GET['department'] > 0) {
                $employees_list = array();
                $department_sid = $_GET['department'];
                $department_supervisor = $this->employee_model->get_all_department_supervisor($department_sid);
                if (!empty($department_supervisor)) {
                    // if ($department_supervisor != $employer_id) {
                        array_push($employees_list, $department_supervisor);
                    // }
                }

                $department_teamleads = $this->employee_model->get_all_department_teamleads($company_id, $department_sid);
                if (!empty($department_teamleads)) {
                    foreach ($department_teamleads as $teamlead) {
                        // if ($teamlead['team_lead'] != $employer_id) {
                            array_push($employees_list, $teamlead['team_lead']);
                        // }
                    }
                }

                $department_employees = $this->employee_model->get_all_employees_from_department($department_sid);
                if (!empty($department_employees)) {
                    foreach ($department_employees as $department_employee) {
                        // if ($department_employee['employee_sid'] != $employer_id) {
                            array_push($employees_list, $department_employee['employee_sid']);
                        // }
                    }
                }

                $employees_list = array_unique($employees_list);

                $data['employees'] = array();
                if(sizeof($employees_list)) $data['employees'] = $this->employee_model->get_these_employees_detail($employees_list, $order_by, $order);
                $data['department_sid'] = $department_sid;
            } else {
                $data['employees'] = $this->employee_model->get_active_employees_detail($company_id, $employer_id, $keyword,0 , $order_by, $order );
            }

            $data['offline_employees'] = $this->employee_model->get_inactive_employees_detail($company_id, $employer_id, $keyword,0 , $order_by, $order);
            $data['title'] = 'Employee / Team Members';

            if (isset($_POST['deactivate_employees']) && $_POST['deactivate_employees'] == 'true') {
                $deactivate_fields = $_POST['ej_check'];

                foreach ($deactivate_fields as $key => $value) {
                    $this->employee_model->deactivate_employee_by_id($value);
                }

                $this->session->set_flashdata('message', '<b>Success: </b>Employee(s) / Team Member(s) Deactivated!');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            if (isset($_POST['activate_employees']) && $_POST['activate_employees'] == 'true') {
                $deactivate_fields = $_POST['ej_check'];

                foreach ($deactivate_fields as $key => $value) {
                    $this->employee_model->activate_employee_by_id($value);
                }

                $this->session->set_flashdata('message', '<b>Success: </b>Employee(s) / Team Member(s) Activated!');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }

            $departments = $this->employee_model->get_all_departments($company_id);
            $data['departments'] = $departments;
            //
            $this->load->model('timeoff_model');
            //
            $data['teamMemberIds'] = [];
            if($data['session']['employer_detail']['access_level_plus'] != 1 && $data['session']['employer_detail']['pay_plan_flag'] != 1){
                $data['teamMemberIds'] = $this->timeoff_model->getEmployeeTeamMemberIds($data['session']['employer_detail']['sid']);
            }

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/employee_management');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function invite_colleagues() { 
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'dashboard', 'employee_management'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Add Employees / Team Members";
            $data['formpost'] = $_POST;
            //$data["access_levels"] = db_get_enum_values('users', 'access_level');

            $data["access_levels"] = $this->dashboard_model->get_security_access_levels();
            $data['send_mail'] = '1';

            if (isset($_POST['formsubmit']) && !isset($_POST['send_mail'])) {
                $data['send_mail'] = '0';
            }

            if ($this->input->post('employeeType') == 'direct_hiring') {
                $this->form_validation->set_rules('username', 'Username', 'trim|xss_clean|min_length[5]|required|is_unique[users.username]');
//                $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required');
            }

            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('email', 'email', 'trim|xss_clean|required|callback_if_user_exists_ci_validation');
            $this->form_validation->set_rules('access_level', 'Access Level', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean');
            $this->form_validation->set_rules('job_title', 'Job Title', 'trim|xss_clean');
            $this->form_validation->set_rules('registration_date', 'Starting Date', 'trim|xss_clean');
            $this->form_validation->set_message('is_unique', '%s is already registered!');

            if ($this->form_validation->run() === FALSE) {
                if (validation_errors() != false) {
                    $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                }

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/invite_colleagues');
                $this->load->view('main/footer');
            } else {
                $session_data['session'] = $this->session->userdata('logged_in');
                $company_information = $session_data["session"]["company_detail"];
                $company_sid = $company_information['sid'];
                $company_name = $company_information['CompanyName'];
                $pictures = upload_file_to_aws('profile_picture', $company_sid, 'profile_picture', '', AWS_S3_BUCKET_NAME);
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');
                $email = $this->input->post('email');
                $job_title = $this->input->post('job_title');
                $access_level = $this->input->post('access_level');
                $registration_date = $this->input->post('registration_date');
                $employee_type = $this->input->post('employeeType');
                $username = $this->input->post('username');
                $location_country = $this->input->post('Location_Country');
                $location_state = $this->input->post('Location_State');
                $location_city = $this->input->post('Location_City');
                $location_zipcode = $this->input->post('Location_ZipCode');
                $location_address = $this->input->post('Location_Address');
                $phonenumber = $this->input->post('PhoneNumber');
                $company_description = $this->input->post('CompanyDescription');
                $send_welcome_email = $this->input->post('send_welcome_email');
                $employment_status = $this->input->post('employee-status');
                $employment_type = $this->input->post('employee-type');
                $password = random_key(9);
                // $start_date = DateTime::createFromFormat('m-d-Y', $registration_date)->format('Y-m-d H:i:s');
                $start_date = reset_datetime(array('datetime' => $registration_date, '_this' => $this, 'from_format' => 'm-d-Y', 'format' => 'Y-m-d H:i:s'));
                $verification_key = random_key() . "_csvImport";
                $salt = generateRandomString(48);
                $user_information = array();
                $user_information['first_name'] = $first_name;
                $user_information['last_name'] = $last_name;
                $user_information['email'] = $email;
                $user_information['job_title'] = $job_title;
                $user_information['access_level'] = $access_level;
                $user_information['registration_date'] = $start_date;
                $user_information['joined_at'] = $start_date;
                $user_information['active'] = 1;
                $user_information['verification_key'] = $verification_key;
                $user_information['parent_sid'] = $company_id;
                $user_information['Location_Country'] = $location_country;
                $user_information['Location_State'] = $location_state;
                $user_information['Location_City'] = $location_city;
                $user_information['Location_ZipCode'] = $location_zipcode;
                $user_information['Location_Address'] = $location_address;
                $user_information['PhoneNumber'] = $phonenumber;
                $user_information['CompanyDescription'] = $company_description;
                $user_information['salt'] = $salt;
                $user_information['employee_status'] = $employment_status;
                $user_information['employee_type'] = $employment_type;
                $user_information['created_by'] = $data['session']['employer_detail']['sid'];

                if (!empty($pictures) && $pictures != 'error') {
                    $user_information['profile_picture'] = $pictures;
                }

                if ($employee_type == 'direct_hiring') {
                    $user_information['username'] = $username;
                    $employee_sid = $this->employee_model->add_employee($user_information);
                    $replacement_array['firstname'] = $first_name;
                    $replacement_array['lastname'] = $last_name;
                    $replacement_array['first_name'] = $first_name;
                    $replacement_array['last_name'] = $last_name;
                    $replacement_array['email'] = $email;
                    $replacement_array['username'] = $username;
                    $replacement_array['company_name'] = $company_name;
                    $replacement_array['create_password_link'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "employee_management/generate_password/" . $salt . '">Create Your Password</a>';
                } else {
                    $link = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url('employee_registration') . '/' . $verification_key . '">' . 'Update Login Credentials' . '</a>';
                    $employee_sid = $this->employee_model->add_employee($user_information);
                    $replacement_array['firstname'] = $first_name;
                    $replacement_array['lastname'] = $last_name;
                    $replacement_array['first_name'] = $first_name;
                    $replacement_array['last_name'] = $last_name;
                    $replacement_array['email'] = $email;
                    $replacement_array['username'] = $username;
                    $replacement_array['company_name'] = $company_name;
                    $replacement_array['create_password_link'] = $link;
                }

                if ($send_welcome_email == 1) {
                    log_and_send_templated_email(NEW_EMPLOYEE_TEAM_MEMBER_NOTIFICATION, $email, $replacement_array);
                }
                
                $this->session->set_flashdata('message', '<b>Success: </b>New Employee team member has been added successfully. ');
                redirect(base_url('employee_management'));
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function send_offer_letter_documents($sid = NULL) {
        if($this->session->userdata('logged_in')['company_detail']['ems_status'] == 1)
            redirect('dashboard');
        if ($sid != NULL) {
            if ($this->session->userdata('logged_in')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'dashboard', array('employee_send_documents', 'send_documents_onboarding_request')); // Param2: Redirect URL, Param3: Function Name
                $company_id = $data['session']['company_detail']['sid'];
                $employer_id = $data['session']['employer_detail']['sid'];
                $userDataObject = $this->employee_model->get_user_detail($sid, $company_id);
                $this->load->model('hr_document_model');

                if ($userDataObject->num_rows() > 0) {
                    $userData = $userDataObject->result_array();
                    $data['userData'] = $userData = $userData[0];
                    $data['user_id'] = $sid;
                    $data['title'] = "Send Offer Letter - Add Employees / Team Members";

                    if (empty($userData['username']) && empty($userData['password']) && $userData['active'] == 0) {
                        $data['employeeType'] = 'onboarding';
                    } else {
                        $data['employeeType'] = 'direct';

                        if (!empty($userData['key'])) {
                            $decodedPassword = decode_string($userData['key']);
                        } else {
                            $decodedPassword = "No password Avaliable, Please register Employee again.";
                        }
                    }

                    $data['hr_documents'] = $this->employee_model->get_hr_documents($company_id);
                    $alreadySentDocs = $this->employee_model->get_already_sent_documents($sid, $company_id);

                    foreach ($data['hr_documents'] as $key => $document) {
                        $document['alreadySent'] = 'false';

                        foreach ($alreadySentDocs as $docs) {
                            if ($docs['document_sid'] == $document['sid']) {
                                $document['alreadySent'] = 'true';
                            }
                        }

                        $data['hr_documents'][$key] = $document;
                    }

                    //get offer letters of the company ==>>starts
                    $data['offerLetters'] = $this->hr_document_model->getCompanyOfferLetters($company_id);
                    $alreadySentOfferLetter = $this->employee_model->get_already_sent_offer_letters($sid, $company_id);

                    foreach ($data['hr_documents'] as $key => $document) {
                        $document['alreadySent'] = 'false';

                        foreach ($alreadySentDocs as $docs) {
                            if ($docs['document_sid'] == $document['sid']) {
                                $document['alreadySent'] = 'true';
                            }
                        }

                        $data['hr_documents'][$key] = $document;
                    }
                    //get offer letters of the company ==>>ends
                    $companyname = $data["session"]["company_detail"]["CompanyName"];

                    foreach ($data['offerLetters'] as $key => $offer_letter) { //checking if offer letter is already sent or not?
                        $offer_letter['alreadySent'] = 'false';

                        foreach ($alreadySentOfferLetter as $offerLetter) {
                            if ($offerLetter['document_sid'] == $offer_letter['sid']) {
                                $offer_letter['alreadySent'] = 'true';
                            }
                        }

                        $data['offerLetters'][$key] = $offer_letter;
                        //coverting offer letter into View
                        $offerLetterBody = $offer_letter['letter_body'];
                        $offerLetterBody = str_replace('{{firstname}}', ucfirst($userData['first_name']), $offerLetterBody);
                        $offerLetterBody = str_replace('{{lastname}}', ucfirst($userData['last_name']), $offerLetterBody);
                        $offerLetterBody = str_replace('{{company_name}}', $companyname, $offerLetterBody);
                        $offerLetterBody = str_replace('{{position}}', ucfirst($userData['job_title']), $offerLetterBody);
                        $offerLetterBody = str_replace('{{start_date}}', month_date_year($userData['registration_date']), $offerLetterBody);
                        $offerLetterBody = str_replace('{{date}}', month_date_year(date('Y-m-d')), $offerLetterBody);

                        if (!empty($userData['username']) && !empty($userData['password']) && $userData['active'] == 1) {
                            $offerLetterBody = str_replace('{{username}}', $userData['username'], $offerLetterBody);
                            $offerLetterBody = str_replace('{{password}}', $decodedPassword, $offerLetterBody);
                        }

                        replace_magic_quotes($offerLetterBody);


                        $offer_letter['letter_body'] = $offerLetterBody;
                        $data['offerLettersView'][$key] = $offer_letter;
                    }

                    $this->form_validation->set_message('required', 'Please check %s to proceed!');
                    $this->form_validation->set_rules('send_mail', 'Send Email', 'required');

                    if ($this->form_validation->run() === FALSE) {
                        if (validation_errors() != false) {
                            $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                        }

                        $this->load->view('main/header', $data);
                        $this->load->view('manage_employer/send_offer_letter_documents');
                        $this->load->view('main/footer');
                    } else {
                        $formpost = $this->input->post(NULL, TRUE);
                        $dataToSave['company_sid'] = $company_id;
                        $dataToSave['sent_on'] = date('Y-m-d H:i:s');
                        $dataToSave['sender_sid'] = $employer_id;
                        $dataToSave['receiver_sid'] = $sid;
                        $docToUserObj = $this->employee_model->check_random_string_exits($sid, NULL);

                        if ($docToUserObj->num_rows() > 0) {
                            $res = $docToUserObj->result_array();
                            $dataToSave['verification_key'] = $res[0]['verification_key'];
                        } else {
                            $dataToSave['verification_key'] = generateRandomString(70);
                        }

                        if (isset($_POST['send_mail'])) { // check if send_mail is checked.
                            $company_data = $this->dashboard_model->get_company_detail($company_id);
                            $companyname = $company_data['CompanyName'];
                            $message_hf = (message_header_footer($company_id, $companyname));
                            $from = FROM_EMAIL_ACCOUNTS;
                            $to = $userData['email'];
                            $subject = "Welcome to " . $companyname;

                            if ($this->input->post('offer_letter_check') === null || $this->input->post('offer_letter_id') == 0) {
                                $emailTemplateData = $this->hr_document_model->get_admin_or_company_email_template(HR_DOCUMENTS_NOTIFICATION, $company_id);
                                $emailTemplateBody = $emailTemplateData['text'];
                                $emailTemplateBody = str_replace('{{username}}', ucwords($userData['first_name'] . ' ' . $userData['last_name']), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{baseurl}}', base_url(), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{verification_key}}', $dataToSave['verification_key'], $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{company_name}}', $companyname, $emailTemplateBody);
                                replace_magic_quotes($emailTemplateBody);
                                $from = $emailTemplateData['from_email'];
                                $to = $userData['email'];
                                $subject = $emailTemplateData['subject'];
                                $from_name = $emailTemplateData['from_name'];
                                $body = $emailTemplateBody;
                            } else {// send offer letter details to user
                                $offerLetterId = $this->input->post('offer_letter_id');
                                $offerLetterObj = $this->employee_model->get_offer_detail($offerLetterId);

                                if ($offerLetterObj->num_rows() > 0) {
                                    $data = $offerLetterObj->result_array();
                                    $offerLetterBody = $data[0]['letter_body'];
                                    $offerLetterBody = str_replace('{{email}}', $userData['email'], $offerLetterBody);
                                    $offerLetterBody = str_replace('{{firstname}}', ucfirst($userData['first_name']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{first_name}}', ucfirst($userData['first_name']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{lastname}}', ucfirst($userData['last_name']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{last_name}}', ucfirst($userData['last_name']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{company_name}}', $companyname, $offerLetterBody);
                                    $offerLetterBody = str_replace('{{position}}', ucfirst($userData['job_title']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{job_title}}', ucfirst($userData['job_title']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{start_date}}', month_date_year($userData['registration_date']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{date}}', month_date_year(date('Y-m-d')), $offerLetterBody);

                                    replace_magic_quotes($offerLetterBody);

                                    replace_magic_quotes(
                                        $offerLetterBody,
                                        array('{{signature}}', '{{inital}}', '{{sign_date}}', '{{text}}'),
                                        array('')
                                    );

                                    if (empty($userData['username']) && empty($userData['password']) && $userData['active'] == 0) {
                                        $body = $message_hf['header']
                                                . '<h2 style="width:100%; margin:10px 0;">Dear ' . ucfirst($userData['first_name']) . ' ' . $userData['last_name'] . ',</h2>'
                                                . '<br><br><b>You have received this email from' . ucfirst($companyname) . '. we are here to help you with this process'
                                                . '<br><br>Your Company HR Administrator has requested that we collect the following:'
                                                . '<ul>'
                                                . '<li>Offer Letter Acknowledgement</li>'
                                                . '<li>Information of Employee</li>'
                                                . '<li>Additional HR Documents</li>'
                                                . '</ul>'
                                                . '<br>To accept the offer letter please '
                                                . '<a href="' . base_url('employee_registration') . '/' . $dataToSave['verification_key'] . '">Click here</a>.<br><br>'
                                                . $message_hf['footer'];
                                    } else {
                                        $offerLetterBody = str_replace('{{username}}', $userData['username'], $offerLetterBody);
                                        $offerLetterBody = str_replace('{{password}}', $decodedPassword, $offerLetterBody);


                                        $emailTemplateData = get_email_template(HR_DOCUMENTS_NOTIFICATION_WITHOUT_USERNAME);
                                        $hrDocumentsNotificationBody = $emailTemplateData['text'];
                                        $hrDocumentsNotificationBody = str_replace('{{username}}', ucwords($userData['first_name'] . ' ' . $userData['last_name']), $hrDocumentsNotificationBody);
                                        $hrDocumentsNotificationBody = str_replace('{{baseurl}}', base_url(), $hrDocumentsNotificationBody);
                                        $hrDocumentsNotificationBody = str_replace('{{verification_key}}', $dataToSave['verification_key'], $hrDocumentsNotificationBody);
                                        $hrDocumentsNotificationBody = str_replace('{{company_name}}', $companyname, $hrDocumentsNotificationBody);
                                        replace_magic_quotes($hrDocumentsNotificationBody);
                                        $from = $emailTemplateData['from_email'];
                                        $to   = $userData['email'];
                                        $subject = $emailTemplateData['subject'];
                                        $from_name = $emailTemplateData['from_name'];
                                        $body      = $offerLetterBody . $hrDocumentsNotificationBody;
                                    }

                                    //saving offer letter record in hr_user_document table
                                    $dataToSave['document_sid'] = $offerLetterId;
                                    $dataToSave['document_type'] = 'offerletter';
                                    $dataToSave['offer_letter_name'] = $data[0]['letter_name'];
                                    $dataToSave['offer_letter_body'] = $offerLetterBody;
                                    $this->employee_model->saveUserDocument('offerletter', $dataToSave);
                                }
                            }
                        }

                        if (isset($formpost['document']) && !empty($formpost['document'])) { //To attach multiple DOCS in an email
                            $files = $this->employee_model->getDocuments($formpost['document']);

                            foreach ($formpost['document'] as $documentId) {
                                $dataToSave['document_sid'] = $documentId;
                                $dataToSave['document_type'] = 'document';
                                $dataToSave['offer_letter_name'] = NULL;
                                $dataToSave['offer_letter_body'] = NULL;
                                $this->employee_model->saveUserDocument('document', $dataToSave);
                            }

                            $this->session->set_flashdata('message', '<b>Success: </b>HR Document(s) sent successfully!');
                            sendMail($from, $to, $subject, $body, STORE_NAME);
                            //sendMailWithStringAttachment($from, $to, $subject, $body, STORE_NAME, $files);
                        } else if ($this->input->post('offer_letter_check') != null || $this->input->post('offer_letter_id') != 0) {
                            $this->session->set_flashdata('message', '<b>Success: </b>Offer Letter sent successfully!');
                            sendMail($from, $to, $subject, $body, STORE_NAME);
                        }
                        //$this->session->set_flashdata('message', '<b>Success: </b>Selected file(s) sent successfully!');
                        $emailLog['subject'] = $subject;
                        $emailLog['email'] = $to;
                        $emailLog['message'] = $body;
                        $emailLog['date'] = date('Y-m-d H:i:s');
                        $emailLog['admin'] = 'admin';
                        $emailLog['status'] = 'Delivered';
                        save_email_log_common($emailLog);
                        redirect("employee_management");
                    }
                } else {
                    $this->session->set_flashdata('message', '<b>Error: </b>No such user exists,Please select a valid user!');
                    redirect(base_url('employee_management'));
                }
            } else {
                redirect(base_url('login'), "refresh");
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error: </b>No user selected!');
            redirect(base_url('employee_management'));
        }
    }

    public function send_hr_documents($sid = NULL) {
        if ($sid != NULL) {
            if ($this->session->userdata('logged_in')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'dashboard', array('employee_send_documents', 'send_documents_onboarding_request')); // Param2: Redirect URL, Param3: Function Name
                $company_id = $data['session']['company_detail']['sid'];
                $employer_id = $data['session']['employer_detail']['sid'];
                $userDataObject = $this->employee_model->get_user_detail($sid, $company_id);
                echo 'I am in Employee Management controller';
                exit;
                $this->load->model('hr_document_model');

                if ($userDataObject->num_rows() > 0) {
                    $userData = $userDataObject->result_array();
                    $data['userData'] = $userData = $userData[0];
                    $data['user_id'] = $sid;
                    $data['title'] = "Send Offer Letter - Add Employees / Team Members";

                    if (empty($userData['username']) && empty($userData['password']) && $userData['active'] == 0) {
                        $data['employeeType'] = 'onboarding';
                    } else {
                        $data['employeeType'] = 'direct';

                        if (!empty($userData['key'])) {
                            $decodedPassword = decode_string($userData['key']);
                        } else {
                            $decodedPassword = "No password Avaliable, Please register Employee again.";
                        }
                    }

                    $data['hr_documents'] = $this->employee_model->get_hr_documents($company_id);
                    $alreadySentDocs = $this->employee_model->get_already_sent_documents($sid, $company_id);

                    foreach ($data['hr_documents'] as $key => $document) {
                        $document['alreadySent'] = 'false';

                        foreach ($alreadySentDocs as $docs) {
                            if ($docs['document_sid'] == $document['sid']) {
                                $document['alreadySent'] = 'true';
                            }
                        }

                        $data['hr_documents'][$key] = $document;
                    }

                    //get offer letters of the company ==>>starts
                    $data['offerLetters'] = $this->hr_document_model->getCompanyOfferLetters($company_id);
                    $alreadySentOfferLetter = $this->employee_model->get_already_sent_offer_letters($sid, $company_id);

                    foreach ($data['hr_documents'] as $key => $document) {
                        $document['alreadySent'] = 'false';

                        foreach ($alreadySentDocs as $docs) {
                            if ($docs['document_sid'] == $document['sid']) {
                                $document['alreadySent'] = 'true';
                            }
                        }

                        $data['hr_documents'][$key] = $document;
                    }
                    //get offer letters of the company ==>>ends
                    $companyname = $data["session"]["company_detail"]["CompanyName"];

                    foreach ($data['offerLetters'] as $key => $offer_letter) { //checking if offer letter is already sent or not?
                        $offer_letter['alreadySent'] = 'false';

                        foreach ($alreadySentOfferLetter as $offerLetter) {
                            if ($offerLetter['document_sid'] == $offer_letter['sid']) {
                                $offer_letter['alreadySent'] = 'true';
                            }
                        }

                        $data['offerLetters'][$key] = $offer_letter;
                        //coverting offer letter into View
                        $offerLetterBody = $offer_letter['letter_body'];
                        $offerLetterBody = str_replace('{{firstname}}', ucfirst($userData['first_name']), $offerLetterBody);
                        $offerLetterBody = str_replace('{{lastname}}', ucfirst($userData['last_name']), $offerLetterBody);
                        $offerLetterBody = str_replace('{{company_name}}', $companyname, $offerLetterBody);
                        $offerLetterBody = str_replace('{{position}}', ucfirst($userData['job_title']), $offerLetterBody);
                        $offerLetterBody = str_replace('{{start_date}}', month_date_year($userData['registration_date']), $offerLetterBody);
                        $offerLetterBody = str_replace('{{date}}', month_date_year(date('Y-m-d')), $offerLetterBody);
                        replace_magic_quotes($offerLetterBody);
                        if (!empty($userData['username']) && !empty($userData['password']) && $userData['active'] == 1) {
                            $offerLetterBody = str_replace('{{username}}', $userData['username'], $offerLetterBody);
                            $offerLetterBody = str_replace('{{password}}', $decodedPassword, $offerLetterBody);
                        }

                        $offer_letter['letter_body'] = $offerLetterBody;
                        $data['offerLettersView'][$key] = $offer_letter;
                    }

                    $this->form_validation->set_message('required', 'Please check %s to proceed!');
                    $this->form_validation->set_rules('send_mail', 'Send Email', 'required');

                    if ($this->form_validation->run() === FALSE) {
                        if (validation_errors() != false) {
                            $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                        }

                        $this->load->view('main/header', $data);
                        $this->load->view('manage_employer/send_offer_letter_documents');
                        $this->load->view('main/footer');
                    } else {
                        $formpost = $this->input->post(NULL, TRUE);
                        $dataToSave['company_sid'] = $company_id;
                        $dataToSave['sent_on'] = date('Y-m-d H:i:s');
                        $dataToSave['sender_sid'] = $employer_id;
                        $dataToSave['receiver_sid'] = $sid;
                        $docToUserObj = $this->employee_model->check_random_string_exits($sid, NULL);

                        if ($docToUserObj->num_rows() > 0) {
                            $res = $docToUserObj->result_array();
                            $dataToSave['verification_key'] = $res[0]['verification_key'];
                        } else {
                            $dataToSave['verification_key'] = generateRandomString(70);
                        }

                        if (isset($_POST['send_mail'])) { // check if send_mail is checked.
                            $company_data = $this->dashboard_model->get_company_detail($company_id);
                            $companyname = $company_data['CompanyName'];
                            $message_hf = (message_header_footer($company_id, $companyname));
                            $from = FROM_EMAIL_ACCOUNTS;
                            $to = $userData['email'];
                            $subject = "Welcome to " . $companyname;

                            if ($this->input->post('offer_letter_check') === null || $this->input->post('offer_letter_id') == 0) {
                                $emailTemplateData = $this->hr_document_model->get_admin_or_company_email_template(HR_DOCUMENTS_NOTIFICATION, $company_id);
                                $emailTemplateBody = $emailTemplateData['text'];
                                $emailTemplateBody = str_replace('{{username}}', ucwords($userData['first_name'] . ' ' . $userData['last_name']), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{baseurl}}', base_url(), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{verification_key}}', $dataToSave['verification_key'], $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{company_name}}', $companyname, $emailTemplateBody);
                                replace_magic_quotes($emailTemplateBody);
                                $from = $emailTemplateData['from_email'];
                                $to = $userData['email'];
                                $subject = $emailTemplateData['subject'];
                                $from_name = $emailTemplateData['from_name'];
                                $body = $emailTemplateBody;
                            } else {// send offer letter details to user
                                $offerLetterId = $this->input->post('offer_letter_id');
                                $offerLetterObj = $this->employee_model->get_offer_detail($offerLetterId);

                                if ($offerLetterObj->num_rows() > 0) {
                                    $data = $offerLetterObj->result_array();
                                    $offerLetterBody = $data[0]['letter_body'];
                                    $offerLetterBody = str_replace('{{firstname}}', ucfirst($userData['first_name']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{lastname}}', ucfirst($userData['last_name']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{company_name}}', $companyname, $offerLetterBody);
                                    $offerLetterBody = str_replace('{{position}}', ucfirst($userData['job_title']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{start_date}}', month_date_year($userData['registration_date']), $offerLetterBody);
                                    $offerLetterBody = str_replace('{{date}}', month_date_year(date('Y-m-d')), $offerLetterBody);
                                    replace_magic_quotes($offerLetterBody);
                                    if (empty($userData['username']) && empty($userData['password']) && $userData['active'] == 0) {
                                        $body = $message_hf['header']
                                                . '<h2 style="width:100%; margin:10px 0;">Dear ' . ucfirst($userData['first_name']) . ' ' . $userData['last_name'] . ',</h2>'
                                                . '<br><br><b>You have received this email from' . ucfirst($companyname) . '. we are here to help you with this process'
                                                . '<br><br>Your Company HR Administrator has requested that we collect the following:'
                                                . '<ul>'
                                                . '<li>Offer Letter Acknowledgement</li>'
                                                . '<li>Information of Employee</li>'
                                                . '<li>Additional HR Documents</li>'
                                                . '</ul>'
                                                . '<br>To accept the offer letter please '
                                                . '<a href="' . base_url('employee_registration') . '/' . $dataToSave['verification_key'] . '">Click here</a>.<br><br>'
                                                . $message_hf['footer'];
                                    } else {
                                        $offerLetterBody = str_replace('{{username}}', $userData['username'], $offerLetterBody);
                                        $offerLetterBody = str_replace('{{password}}', $decodedPassword, $offerLetterBody);
                                        $emailTemplateData = get_email_template(HR_DOCUMENTS_NOTIFICATION_WITHOUT_USERNAME);
                                        $hrDocumentsNotificationBody = $emailTemplateData['text'];
                                        $hrDocumentsNotificationBody = str_replace('{{username}}', ucwords($userData['first_name'] . ' ' . $userData['last_name']), $hrDocumentsNotificationBody);
                                        $hrDocumentsNotificationBody = str_replace('{{baseurl}}', base_url(), $hrDocumentsNotificationBody);
                                        $hrDocumentsNotificationBody = str_replace('{{verification_key}}', $dataToSave['verification_key'], $hrDocumentsNotificationBody);
                                        $hrDocumentsNotificationBody = str_replace('{{company_name}}', $companyname, $hrDocumentsNotificationBody);
                                        replace_magic_quotes($hrDocumentsNotificationBody);
                                        $from = $emailTemplateData['from_email'];
                                        $to = $userData['email'];
                                        $subject = $emailTemplateData['subject'];
                                        $from_name = $emailTemplateData['from_name'];
                                        $body = $offerLetterBody . $hrDocumentsNotificationBody;
                                    }
                                    //saving offer letter record in hr_user_document table
                                    $dataToSave['document_sid'] = $offerLetterId;
                                    $dataToSave['document_type'] = 'offerletter';
                                    $dataToSave['offer_letter_name'] = $data[0]['letter_name'];
                                    $dataToSave['offer_letter_body'] = $offerLetterBody;
                                    $this->employee_model->saveUserDocument('offerletter', $dataToSave);
                                }
                            }
                        }


                        if (isset($formpost['document']) && !empty($formpost['document'])) { //To attach multiple DOCS in an email
                            $files = $this->employee_model->getDocuments($formpost['document']);

                            foreach ($formpost['document'] as $documentId) {
                                $dataToSave['document_sid'] = $documentId;
                                $dataToSave['document_type'] = 'document';
                                $dataToSave['offer_letter_name'] = NULL;
                                $dataToSave['offer_letter_body'] = NULL;
                                $this->employee_model->saveUserDocument('document', $dataToSave);
                            }

                            $this->session->set_flashdata('message', '<b>Success: </b>HR Document(s) sent successfully!');
                            sendMail($from, $to, $subject, $body, STORE_NAME);
                            //sendMailWithStringAttachment($from, $to, $subject, $body, STORE_NAME, $files);
                        } else if ($this->input->post('offer_letter_check') != null || $this->input->post('offer_letter_id') != 0) {
                            $this->session->set_flashdata('message', '<b>Success: </b>Offer Letter sent successfully!');
                            sendMail($from, $to, $subject, $body, STORE_NAME);
                        }
                        //$this->session->set_flashdata('message', '<b>Success: </b>Selected file(s) sent successfully!');
                        $emailLog['subject'] = $subject;
                        $emailLog['email'] = $to;
                        $emailLog['message'] = $body;
                        $emailLog['date'] = date('Y-m-d H:i:s');
                        $emailLog['admin'] = 'admin';
                        $emailLog['status'] = 'Delivered';
                        save_email_log_common($emailLog);
                        redirect("employee_management");
                    }
                } else {
                    $this->session->set_flashdata('message', '<b>Error: </b>No such user exists,Please select a valid user!');
                    redirect(base_url('employee_management'));
                }
            } else {
                redirect(base_url('login'), "refresh");
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error: </b>No user selected!');
            redirect(base_url('employee_management'));
        }
    }

    function alpha_dash_space($str) {
        if ($str != "") {
            if (!preg_match("/^([-0-9])+$/i", $str)) {
                $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain numeric characters and dashes.');
                return FALSE;
            } else {
                return TRUE;
            }
        } else
            return TRUE;
    }

    function deactivate_single_employee() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deactivate_single_employee') {
            $sid = $_REQUEST['del_id'];
            $data = $this->employee_model->deactivate_employee_by_id($sid);
            echo $data;
            exit;
        }
    }

    function delete_single_employee() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_single_employee') {
            $sid = $_REQUEST['del_id'];
            $data = $this->employee_model->delete_employee_by_id($sid);
            echo $data;
            exit;
        }
    }

    function archive_single_employee() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'archive_single_employee') {
            $sid = $_REQUEST['archive_id'];
            $data_array = array('archived' => 1);
            $data = $this->employee_model->archive_employee_by_id($sid, $data_array);
            echo $data;
            exit;
        }
    }

    function reactivate_single_employee() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore_employee') {
            $sid = $_REQUEST['id'];
            $data_array = array('archived' => 0);
            $data = $this->employee_model->archive_employee_by_id($sid, $data_array);
            echo $data;
            exit;
        }
    }

    function revert_termination_single_employee() {
        if ($this->input->is_ajax_request()) {
            if($this->input->post('action') == 'restore_employee'){
                $sid = $this->input->post('id');
                $data_array = array('archived' => 0, 'active' => 1, 'terminated_status' => 0);
                $data = $this->employee_model->archive_employee_by_id($sid, $data_array);
                echo $data;
                exit;
            } else{
                echo 'Error, Please try Again!';
                exit;
            }
        } else{
            echo 'Error, Please try Again!';
            exit;
        }
    }

    function revert_employee_back_to_applicant() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'revert_applicant') {
            $applicant_sid = $_REQUEST['revert_id'];
            $user_sid = $_REQUEST['id'];
            //echo 'applicant sid: '.$applicant_sid .' - user id: '. $user_sid;
            //exit;
            $data = $this->employee_model->update_applicant_status($applicant_sid);
//            $data = 'success';
            if ($data == 'success') { // custom function to revert employee to applicant
                //Revert table is maintaining for the record of employees who got reverted
                $data = $this->employee_model->revert_employee_back_to_applicant($user_sid,$applicant_sid);
                $this->session->set_flashdata('message', '<strong>Success:</strong> You have moved this person back to the Applicant Tracking system');
                echo 'success';
                exit;
            } else {
                echo 'error';
                exit;
            }
        }
    }

    public function employee_profile($sid = NULL) {
        if ($sid == NULL) {
            $this->session->set_flashdata('message', '<b>Error:</b> No Employee found!');
            redirect('employee_management', 'refresh');
        } else {
            if ($this->session->userdata('logged_in')) {
                $data = employee_right_nav($sid);
                // Added on: 04-07-2019
                $data['show_timezone'] = $data['session']['company_detail']['timezone'];
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'employee_management', 'employee_profile'); // Param2: Redirect URL, Param3: Function Name
                $company_id = $data["session"]["company_detail"]["sid"];
                $employer_access_level = $data["session"]["employer_detail"]["access_level"];
                $data['access_level_plus'] = $data["session"]["employer_detail"]["access_level_plus"];
                $employer_id = $sid;
                $data['title'] = "Employee / Team Members Profile";
                $data['employer_sid'] = $security_sid;
                $data['main_employer_id'] = $security_sid;
                $data['employer'] = $this->dashboard_model->get_company_detail($employer_id);

                //
                if(!empty($data['employer']['full_employment_application'])){
                    //
                    $updateArray = [];
                    //
                    $fullEmploymentForm = unserialize($data['employer']['full_employment_application']);
                    // Check for DOB
                    if(!empty($fullEmploymentForm['TextBoxDOB']) && empty($data['employer']['dob'])){
                        $data['employer']['dob'] = $updateArray['dob'] = DateTime::createfromformat('m-d-Y',$fullEmploymentForm['TextBoxDOB'])->format('Y-m-d');
                    }
                    // Check for SSN
                    if(!empty($fullEmploymentForm['TextBoxSSN']) && empty($data['employer']['ssn'])){
                        $data['employer']['ssn'] = $updateArray['ssn'] = $fullEmploymentForm['TextBoxSSN'];
                    }
                    //
                    if($updateArray){
                        $this->db->where('sid', $data['employer']['sid'])
                        ->update('users', $updateArray);
                    }
                }
                $employee_detail = $data['employer'];

                // Check and set the company sms module
                // phone number
                company_sms_phonenumber(
                    $data['session']['company_detail']['sms_module_status'],
                    $company_id,
                    $data,
                    $this
                );

                if ($data['employer']['department_sid'] > 0) {
                    $department_name = $this->employee_model->get_department_name($data['employer']['department_sid']);                   
                    $data['department_name'] = $department_name;
                }

                if ($data['employer']['team_sid'] > 0) {
                    $team_name = $this->employee_model->get_team_name($data['employer']['team_sid']);
                    $data['team_name'] = $team_name;
                }

                if(isset($data['team_name']) && $data['team_name'] == ''){
                    // Fetch employee team and department
                    $t = $this->employee_model->fetch_department_teams($employer_id);
                    if(sizeof($t)){
                        $data['employer']['department_sid'] = $t['department_sid'];
                        $data['employer']['team_sid'] = $t['team_sid'];
                        //
                        $department_name = $this->employee_model->get_department_name($data['employer']['department_sid']);
                        $data['department_name'] = $department_name;
                        $team_name = $this->employee_model->get_team_name($data['employer']['team_sid']);
                        $data['team_name'] = $team_name;
                    }
                } 

                if (empty($data['employer']['employee_number']) || $data['employer']['employee_number'] == '') {
                    $data['employer']['employee_number'] = $sid; 
                }

                $employee_assign_team = $this->employee_model->fetch_employee_assign_teams($employer_id);
                $data['team_names'] = [];
                $data['team_sids'] = [];
                if (!empty($employee_assign_team)) {
                    $data['team_names'] = $employee_assign_team['team_names'];
                    $data['team_sids'] = $employee_assign_team['team_sids'];
                }

                if (empty($data['employer'])) { // Employer does not exists - throw error
                    $this->session->set_flashdata('message', '<b>Error:</b> No Employee found!');
                    redirect('employee_management', 'refresh');
                }

                if (!$data['session']['employer_detail']['access_level_plus'] && !$data['session']['employer_detail']['pay_plan_flag']) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Module Not Accessable!');
                    redirect('employee_management', 'refresh');
                }

                $parent_sid = $data["employer"]["parent_sid"];

                if ($company_id != $parent_sid) { // Employer exists but does not belongs to this company - throw error
                    $this->session->set_flashdata('message', '<b>Error:</b> Employee does not exists in your company!');
                    redirect('employee_management', 'refresh');
                }

                $data['employer_id'] = $employer_id;
                $applicant_sid = $data['employer']['applicant_sid']; // check if this employee is moved from ATS
                $data['employer']['test'] = false;
                $data['applicant_jobs'] = $this->application_tracking_system_model->get_single_applicant_all_jobs($applicant_sid, $company_id);
                if (!empty($applicant_sid)) { //Questionare
                    // $applicant_info = $this->application_tracking_system_model->getApplicantData($applicant_sid);
                    // $data['employer']['score'] = $applicant_info['score'];
                    // $data['employer']['passing_score'] = $applicant_info['passing_score'];
                    // if ($applicant_info['questionnaire'] != NULL) {
                    // $myquestionnaire = unserialize($applicant_info['questionnaire']);
                    // $data['employer']['questionnaire'] = $myquestionnaire;
                    // $data['employer']['test'] = true;
                    //  }
                }

                $data['applicant_message'] = array();
                $data['applicant_all_ratings'] = NULL;
                $data['applicant_ratings_count'] = NULL;
                $data['applicant_events'] = '';
                $registration_date = $data['session']['employer_detail']['registration_date'];
                $rating_result = $this->application_tracking_system_model->getApplicantAllRating($employer_id, 'employee', $registration_date); //getting all rating of applicant
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee'); // getting applicant ratings - getting average rating of applicant

                if ($rating_result != NULL) {
                    $data['applicant_ratings_count'] = $rating_result->num_rows();
                    $data['applicant_all_ratings'] = $rating_result->result_array();
                }

                $company_accounts = $this->application_tracking_system_model->getCompanyAccounts($company_id); //fetching list of all sub-accounts
                $data['company_timezone'] = $company_timezone = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;
                foreach($company_accounts as $key => $company_account){
                    $company_accounts[$key]['timezone'] = !empty($company_account['timezone']) ? $company_account['timezone'] : $company_timezone;
                }
                $data['company_accounts'] = $company_accounts;
                if(!empty($data['session']['employer_detail']['timezone']))
                    $data['employer_timezone'] =   $data['session']['employer_detail']['timezone']; 
                else
                    $data['employer_timezone'] = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;
            
                $data['upcoming_events'] = $this->employee_model->get_employee_events($company_id, $sid, 'upcoming'); //Getting Events
                $to_id = $data['id'];
                $rawMessages = $this->application_tracking_system_model->get_sent_messages($to_id, NULL);

                if (!empty($rawMessages)) {
                    $i = 0;

                    foreach ($rawMessages as $message) {
                        if ($message['outbox'] == 1) {
                            $employerData = $this->application_tracking_system_model->getEmployerDetail($data["session"]["employer_detail"]["sid"]);
                            $message['profile_picture'] = $employerData['profile_picture'];
                            $message['first_name'] = $employerData['first_name'];
                            $message['last_name'] = $employerData['last_name'];
                            $message['username'] = $employerData['username'];
                        } else {
                            $message['profile_picture'] = $data['employer']['profile_picture'];
                            $message['first_name'] = $data['employer']['first_name'];
                            $message['last_name'] = $data['employer']['last_name'];
                            $message['username'] = "";
                        }

                        $allMessages[$i] = $message;
                        $i++;
                    }

                    $data['applicant_message'] = $allMessages;
                }

                $data['extra_info'] = unserialize($data['employer']['extra_info']);
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

                $data_states_encode = htmlentities(json_encode($data_states));
                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data['states'] = $data_states_encode;
                $data["access_level"] = db_get_enum_values('users', 'access_level');
                $data['employer']['state_name'] = '';
                $data['employer']['country_name'] = '';

                if (!empty($data['employer']['Location_State'])) { // get state name
                    $state_id = $data['employer']['Location_State'];
                    $country_state_info = db_get_state_name($state_id);
                    $data['employer']['state_name'] = $country_state_info['state_name'];
                    $data['employer']['country_name'] = $country_state_info['country_name'];
                }

                if (empty($data['employer']['country_name']) && !empty($data['employer']['Location_Country'])) {
                    $country_id = $data['employer']['Location_Country'];
                    $country_info = db_get_country_name($country_id);
                    $data['employer']['country_name'] = $country_info['country_name'];
                }

                $data['employee_notes'] = $this->employee_model->getEmployeeNotes($employer_id, $registration_date); //Getting Notes - table: portal_misc_notes, employers_sid=company id, applicant_job_sid= employee_sid - start here

                if ($this->input->post('email') == $data['employer']['email']) {
                    $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean');
                } else {
                    $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|callback_check_employee_email');
                    $this->form_validation->set_message('check_employee_email', 'Employee email already exists');
                }

                $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
                $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean');
                $this->form_validation->set_rules('access_level', 'Access Level', 'trim|xss_clean');
                //$this->form_validation->set_rules('CompanyDescription', 'Description', 'trim|xss_clean');
                //
                $data['_ssv'] = $_ssv = getSSV($data['session']['employer_detail']);
                
                if ($this->form_validation->run() === FALSE) { //checking if the form is submitted so i can open the form screen again
                    $this->load->model('portal_email_templates_model');
                    $data['edit_form'] = false;

                    if ($this->input->post()) {
                        $data['edit_form'] = true;
                    }

                    $data['notes_view'] = false; //checking if the form is submitted so i can open the notes form screen again

                    if (isset($_SESSION['show_notes']) && $_SESSION['show_notes'] == 'true') {
                        $data['notes_view'] = true;
                        $_SESSION['show_notes'] = 'false';
                    }

                    //checking if the form is submitted so i can open the notes form screen again
                    $data['show_event'] = false;

                    if (isset($_SESSION['show_event']) && $_SESSION['show_event'] == 'true') {
                        $data['show_event'] = true;
                        $_SESSION['show_event'] = 'false';
                    }

                    $data['show_message'] = false; //checking if the form is submitted so i can open the Messages form screen again

                    if (isset($_SESSION['show_message']) && $_SESSION['show_message'] == 'true') {
                        $data['show_message'] = true;
                        $_SESSION['show_message'] = 'false';
                    }

                    $portal_email_templates = $this->application_tracking_system_model->get_portal_email_templates($company_id);

                    foreach ($portal_email_templates as $key => $template) {
                        $portal_email_templates[$key]['attachments'] = $this->portal_email_templates_model->get_all_email_template_attachments($template['sid']);
                    }

                    $data['portal_email_templates'] = $portal_email_templates;

                    if (empty($data['employer']['resume'])) { // check if reseme is uploaded
                        $data['employer']['resume_link'] = "javascript:void(0);";
                        $data['resume_link_title'] = "No Resume found!";
                        if(!empty($data['employer']['applicant_sid']) && $data['employer']['applicant_sid'] != NULL){
                            $resume = $this->employee_model->check_for_resume($data['employer']['applicant_sid']);
                            if($resume != 0){
                                $data['employer']['resume_link'] = AWS_S3_BUCKET_URL . $resume;
                                $data['resume_link_title'] = $resume;
                            }
                        }
                    } else {
                        $data['employer']['resume_link'] = AWS_S3_BUCKET_URL . $data['employer']['resume'];
                        $data['resume_link_title'] = $data['employer']['resume'];
                    }

                    if (empty($data['employer']['cover_letter'])) { // check if cover letter is uploaded
                        $data['employer']["cover_link"] = "javascript:void(0)";
                        $data['cover_letter_title'] = "No Cover Letter found!";
                    } else {
                        $data['employer']["cover_link"] = AWS_S3_BUCKET_URL . $data['employer']['cover_letter'];
                        $data['cover_letter_title'] = $data['employer']['cover_letter'];
                    }
                    $data['employment_statuses'] = $this->application_tracking_system_model->getEmploymentStatuses();
                    $data['employment_types'] = $this->application_tracking_system_model->getEmploymentTypes();
                    $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $addresses = $this->employee_model->get_company_addresses($company_id);
                    $data['addresses'] = $addresses;
                    $departments = $this->employee_model->get_all_departments($company_id);
                    $data['departments'] = $departments;
                    $data['is_new_calendar'] = $this->call_old_event();
                    // Time off policies
                    $this->load->model('timeoff_model');
                    $this->load->helper('timeoff');
                    //
                    $data['policies'] = $this->timeoff_model->getEmployeePoliciesByEmployeeId($company_id, $employer_id);
                    $this->load->view('main/header', $data);
                    $this->load->view('manage_employer/employee_management/employee_profile_ats_view');
                    //$this->load->view('manage_employer/employee_profile_view');
                    $this->load->view('main/footer');
                } else {

                    $sid = $this->input->post('id');
                    $pictures = $this->input->post('old_profile_picture');

                    if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                        $file = explode(".", $_FILES['pictures']['name']);
                        $file_name = str_replace(" ", "-", $file[0]);
                        $pictures = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                        generate_image_compressed($_FILES['pictures']['tmp_name'], 'images/' . $pictures);
                        $aws = new AwsSdk();
                        $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                    }

                    $extra_info_arr = array();
                    $extra_info_arr['secondary_email'] = $this->input->post('secondary_email');
                    $extra_info_arr['secondary_PhoneNumber'] = $this->input->post('txt_secondary_phonenumber', true) ? $this->input->post('txt_secondary_phonenumber', true) : $this->input->post('secondary_PhoneNumber');
                    $extra_info_arr['other_email'] = $this->input->post('other_email');
                    $extra_info_arr['other_PhoneNumber'] = $this->input->post('txt_other_phonenumber', true) ? $this->input->post('txt_other_phonenumber', true) : $this->input->post('other_PhoneNumber');
                    $extra_info_arr['title'] = $this->input->post('title');
                    $extra_info_arr['office_location'] = $this->input->post('office_location');
                    $extra_info_arr['interests'] = $this->input->post('interests');
                    $extra_info_arr['short_bio'] = $this->input->post('short_bio');

                    $full_emp_app = array();
                   

                    $video_source = $this->input->post('video_source');
                    $video_id = '';

                    if ($video_source != 'no_video') {
                        if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                            $random = generateRandomString(5);
                            $company_id = $data['session']['company_detail']['sid'];
                            $target_file_name = basename($_FILES["upload_video"]["name"]);
                            $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                            $target_dir = "assets/uploaded_videos/";
                            $target_file = $target_dir . $file_name;
                            $filename = $target_dir . $company_id;

                            if (!file_exists($filename)) {
                                mkdir($filename);
                            }

                            if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                            } else {

                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                redirect('employee_management/employee_profile', 'refresh');
                            }

                            $video_id = $file_name;
                        } else {
                            $video_id = $this->input->post('yt_vm_video_url');

                            if ($video_source == 'youtube') {
                                $url_prams = array();
                                parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                                if (isset($url_prams['v'])) {
                                    $video_id = $url_prams['v'];
                                } else {
                                    $video_id = '';
                                }
                            } else if ($video_source == 'vimeo') {
                                $video_id = $this->vimeo_get_id($video_id);
                            } else if ($video_source == 'uploaded' && $this->input->post('pre_upload_video_url') != '') {
                                $video_id = $this->input->post('pre_upload_video_url');
                            }
                        }
                    }

                    $extra_info = serialize($extra_info_arr);
                    $date_of_birth = $this->input->post('DOB');

                    if (!empty($date_of_birth)) {
                        $DOB = date('Y-m-d', strtotime(str_replace('-', '/', $date_of_birth)));
                    } else {
                        $DOB = '';
                    }

                    $notified_by = $this->input->post('notified_by', true);
                    if($notified_by == '' || !sizeof($notified_by)) $notified_by = 'email';
                    else $notified_by = implode(',', $notified_by);
   
                    $data_to_insert = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'Location_Country' => $this->input->post('Location_Country'),
                        'Location_State' => $this->input->post('Location_State'),
                        'Location_City' => $this->input->post('Location_City'),
                        'Location_ZipCode' => $this->input->post('Location_ZipCode'),
                        'Location_Address' => $this->input->post('Location_Address'),
                        'shift_start_time' => $this->input->post('shift_start_time'),
                        'shift_end_time' => $this->input->post('shift_end_time'),
                        'employee_status' => $this->input->post('employee-status'),
                        'employee_type' => $this->input->post('employee-type'),
                        'PhoneNumber' => $this->input->post('txt_phonenumber') ? $this->input->post('txt_phonenumber', true) : $this->input->post('PhoneNumber', true),
                        //'access_level' => $this->input->post('access_level'),
                        'profile_picture' => $pictures,
                        'video_type' => $video_source,
                        'YouTubeVideo' => $video_id,
                        'job_title' => $this->input->post('job_title'),
                        'extra_info' => $extra_info,
                        'linkedin_profile_url' => $this->input->post('linkedin_profile_url'),
                        'email' => $this->input->post('email'),
                        'ssn' => $this->input->post('SSN'),
                        'employee_number' => $this->input->post('employee_number'),
                        'department_sid' => $this->input->post('department'),
                        // 'team_sid' => $this->input->post('team')
                    );

                    //
                    if(preg_match(XSYM_PREG, $data_to_insert['ssn'])) unset($data_to_insert);

                    // Update dept/team table
                    $department = $this->input->post('department');
                    $teams = $this->input->post('teams');

                    if (isset($teams) && !empty($teams) && $department != 0) {
                        $old_ssign_teams = $this->employee_model->getAllAssignedTeams($employer_id);
                        $add_team_sids = array();
                        $delete_team_sids = array();
                        
                        foreach ($teams as $team) {
                            if (!in_array($team, $old_ssign_teams)) {
                                array_push($add_team_sids, $team);
                            } 
                        }

                        if ($old_ssign_teams) {
                            foreach ($old_ssign_teams as $old_team) {
                                if (!in_array($old_team, $teams)) {
                                    array_push($delete_team_sids, $old_team);
                                } 
                            }
                        }

                        $event_array = array();

                        if (!empty($add_team_sids)) {
                            foreach ($add_team_sids as $add_team_sid) {
                                $this->employee_model->addEmployeeToTeam(
                                    $department,
                                    $add_team_sid,
                                    $employer_id
                                );
                            }

                            $event_array['add_team_sids'] = $add_team_sids;
                        }

                        if (!empty($delete_team_sids)) {
                            foreach ($delete_team_sids as $delete_team_sid) {
                                $this->employee_model->removeEmployeeFromTeam(
                                    $delete_team_sid,
                                    $employer_id
                                );
                            }
                            $event_array['remove_team_sids'] = $delete_team_sids;
                        }


                        $maintain_employee_team_history = array();
                        $maintain_employee_team_history['employee_sid'] = $security_sid;
                        $maintain_employee_team_history['event_data'] = serialize($event_array);
                        $this->employee_model->manageEmployeeTeamHistory($maintain_employee_team_history);
                    }

                    // $this->employee_model->departmentTeamExists(
                    //     $data_to_insert['department_sid'],
                    //     $data_to_insert['team_sid'],
                    //     $employer_id
                    // );
                    
                    //
                    if($DOB != '' && !preg_match(XSYM_PREG, $DOB)) $data_to_insert['dob'] = $DOB;

                    $data_to_insert['user_shift_hours'] = $this->input->post('shift_hours');
                    $data_to_insert['user_shift_minutes'] = $this->input->post('shift_mins');

                    if(IS_NOTIFICATION_ENABLED == 1 && $this->input->post('notified_by', true) && $data['phone_sid'] != '') $data_to_insert['notified_by'] = $notified_by;

                    // _e($this->input->post('joining_date', true), true, true);
                    // Check if joining date is set
                    if($this->input->post('joining_date')){
                        $data_to_insert['joined_at'] = DateTime::createFromFormat('m-d-Y', $this->input->post('joining_date', true))->format('Y-m-d');
                    }

                    // Added on: 25-06-2019
                    if(IS_TIMEZONE_ACTIVE){
                        $new_timezone = $this->input->post('timezone', true);
                        if($new_timezone != '') $data_to_insert['timezone'] = $new_timezone;
                    }
//Ful Employment Application Form Update data
                    $full_emp_app = isset($employee_detail['full_employment_application']) && !empty($employee_detail['full_employment_application']) ? unserialize($employee_detail['full_employment_application']) : array();
                    $full_emp_app['PhoneNumber'] = $this->input->post('PhoneNumber');
                    $full_emp_app['TextBoxTelephoneOther'] = $this->input->post('other_PhoneNumber');
                    $full_emp_app['TextBoxAddressStreetFormer3'] = $this->input->post('other_email');
                    $data_to_insert['full_employment_application'] = serialize($full_emp_app);
                    $this->dashboard_model->update_user($sid, $data_to_insert);
                    // Handle timeoff policies
                    $this->load->model('timeoff_model');
                    $this->timeoff_model->updateEmployeePolicies(
                        $company_id,
                        $employer_id,
                        $this->input->post('policies')
                    );
                    //
                    $this->session->set_flashdata('message', '<b>Success:</b> Employee / Team Member Profile is updated successfully');
                    redirect("employee_profile/" . $sid, "location");
                }
            } else {
                redirect(base_url('login'), "refresh");
            }
        }
    }

    public function upload_attachment($user_sid) {
        if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') { //uploading Resume to AWS if any
            $file = explode(".", $_FILES['resume']['name']);
            $file_name = str_replace(" ", "-", $file[0]);
            $resume = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

            if ($_FILES['resume']['size'] == 0) {
            // if ($_FILES['cover_letter']['size'] == 0) {
                $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                redirect("employee_profile/" . $user_sid, 'location');
            }
            $aws = new AwsSdk();
            $aws->putToBucket($resume, $_FILES['resume']['tmp_name'], AWS_S3_BUCKET_NAME);
            $user_data['resume'] = $resume;
        } else {
            $user_data['resume'] = $this->input->post('old_resume');
        }

        if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['name'] != '') { //uploading cover letter to AWS
            $file = explode(".", $_FILES["cover_letter"]["name"]);
            $file_name = str_replace(" ", "-", $file[0]);
            $letter = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
            if ($_FILES['cover_letter']['size'] == 0) {
                $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                redirect('employee_profile/' . $user_sid, 'location');
            }
            $aws = new AwsSdk();
            $aws->putToBucket($letter, $_FILES['cover_letter']['tmp_name'], AWS_S3_BUCKET_NAME);
            $user_data['cover_letter'] = $letter;
        } else {
            $user_data['cover_letter'] = $this->input->post('old_letter');
        }

        $result = $this->dashboard_model->update_user($user_sid, $user_data);
        $this->session->set_flashdata('message', '<b>Success:</b> Attachment(s) uploaded successfully');
        redirect('employee_profile/' . $user_sid, 'location');
    }

    public function check_employee_email($email) {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];
        $result = $this->dashboard_model->validate_employee_email($company_sid, $email);

        if ($result == true) {
            return false;
        } else {
            return true;
        }
    }

    public function insert_notes() {
        if ($this->input->post()) { //check if insert notes
            $formpost = $_POST;
            $_SESSION['show_notes'] = 'true';
            $employers_sid = $formpost['employers_sid'];
            $applicant_job_sid = $formpost['applicant_job_sid'];
            $applicant_email = $formpost['applicant_email'];
            $action = $formpost['action'];

            if ($action == 'add_note') {
                $notes = $formpost['notes'];
                $this->employee_model->employeeInsertNote($employers_sid, $applicant_job_sid, $applicant_email, $notes);
                $this->session->set_flashdata('message', '<b>Success:</b> Note added successfully');
                redirect('employee_profile/' . $applicant_job_sid);
            } else {
                $note_sid = $formpost['sid'];
                $notes = $formpost['my_edit_notes'];
                $this->employee_model->employeeUpdateNote($note_sid, $employers_sid, $applicant_job_sid, $applicant_email, $notes);
                $this->session->set_flashdata('message', '<b>Success:</b> Note updated successfully');
                redirect('employee_profile/' . $applicant_job_sid);
            }
        } else
            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
    }

    public function employee_login_credentials($sid = NULL) {
        if ($sid == NULL) {
            $this->session->set_flashdata('message', '<b>Error:</b> No Employee found!');
            redirect('employee_management', 'refresh');
        } else {
            if ($this->session->userdata('logged_in')) {
                $data = employee_right_nav($sid);
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'employee_management', 'employee_login_credentials'); // Param2: Redirect URL, Param3: Function Name
                $company_id = $data["session"]["company_detail"]["sid"];
                $employer_id = $sid;
                $employer_access_level = $data['session']['employer_detail']['access_level'];
                $data['employer_access_level'] = $employer_access_level;
                $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $full_access = false;

                if ($employer_access_level == 'Admin') {
                    $full_access = true;
                }

                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee'); // getting applicant ratings - getting average rating of applicant
                $data['full_access'] = $full_access;
                $data['title'] = 'Employee / Team Members Login Credentials';
                $data['employer'] = $this->dashboard_model->get_company_detail($employer_id);

                if (empty($data['employer'])) { // Employer does not exists - throw error
                    $this->session->set_flashdata('message', '<b>Error:</b> No Employee found!');
                    redirect('employee_management', 'refresh');
                }
                if ($data['employer']['is_executive_admin']) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> Action Not Allowed!');
                    redirect('employee_management', 'refresh');
                }

                $parent_sid = $data['employer']['parent_sid'];

                if ($company_id != $parent_sid) { // Employer exists but does not belongs to this company - throw error
                    $this->session->set_flashdata('message', '<b>Error:</b> No Employee found!');
                    redirect('employee_management', 'refresh');
                }

                if ($data['employer']['username'] != $this->input->post('username')) {
                    $this->form_validation->set_rules('username', 'User Name', 'required|min_length[5]|trim|xss_clean|is_unique[users.username]');
                } else {
                    $this->form_validation->set_rules('username', 'User Name', 'required|min_length[5]|trim|xss_clean');
                }

                if ($data['employer']['email'] != $this->input->post('email')) {
                    $this->form_validation->set_rules('email', 'E-Mail Address', 'trim|xss_clean|valid_email|callback_if_user_exists_ci_validation');
                } else {
                    $this->form_validation->set_rules('email', 'E-Mail Address', 'required|trim|xss_clean|valid_email');
                }

                $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|matches[cpassword]');
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|xss_clean');
                $this->form_validation->set_message('is_unique', '%s is not available, Please try again!');

                if ($this->form_validation->run() === FALSE) {
                    $this->load->view('main/header', $data);
                    $this->load->view('manage_employer/employee_login_credentials_view');
                    $this->load->view('main/footer');
                } else {
                    $sid = $this->input->post('id');
                    $password = $this->input->post('password');

                    if (empty($password)) {
                        $data = array('username' => $this->input->post('username'),
                            'email' => $this->input->post('email'));
                    } else {
                        $data = array('username' => $this->input->post('username'),
                            'password' => do_hash($this->input->post('password'), 'md5'),
                            'email' => $this->input->post('email'));
                    }

                    $this->dashboard_model->update_user($sid, $data);
                    $this->session->set_flashdata('message', '<b>Success:</b> Login credentials updated successfully');
                    redirect('employee_management', 'location');
                }
            } else {
                redirect(base_url('login'), 'refresh');
            }
        }
    }

    public function login_password() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_detail = $data['session']['company_detail'];
            $employer_detail = $data['session']['employer_detail'];
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'login_password'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $company_detail['sid'];
            $employer_id = $employer_detail['sid'];
            $data['title'] = 'Login Credentials';
            $data['employer'] = $employer_detail;
            $data['employee'] = $employer_detail;
//            echo $employer_id.'<pre>'; print_r($data['employer']); exit;
            $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee');

            if ($data['employer']['is_executive_admin'] == '1') {
                redirect('my_settings', 'refresh');
            }

            $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_personal';

            if ($data['employer']['username'] != $this->input->post('username')) {
                $this->form_validation->set_rules('username', 'User Name', 'required|min_length[5]|trim|xss_clean|is_unique[users.username]');
            } else {
                $this->form_validation->set_rules('username', 'User Name', 'required|min_length[5]|trim|xss_clean');
            }

            if ($data['employer']['email'] != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'E-Mail Address', 'trim|xss_clean|valid_email|callback_if_user_exists_ci_validation');
            } else {
                $this->form_validation->set_rules('email', 'E-Mail Address', 'required|trim|xss_clean|valid_email');
            }

            $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|matches[cpassword]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|xss_clean');
            $this->form_validation->set_message('is_unique', '%s is not available, Please try again!');
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/login_password_new');
                $this->load->view('main/footer');
            } else {
                $sid = $this->input->post('id');
                $password = $this->input->post('password');

                if (empty($password)) {
                    $data = array(
                        'username' => $this->input->post('username'),
                        'email' => $this->input->post('email')
                    );
                } else {
                    $data = array('username' => $this->input->post('username'),
                        'password' => do_hash($this->input->post('password'), 'md5'),
                        'email' => $this->input->post('email'));
                }

                //$data = array('password' => do_hash($this->input->post('password'), 'md5'));
                $this->dashboard_model->update_user($sid, $data);
                $employer = $this->dashboard_model->get_company_detail($sid);
                $session = $this->session->userdata('logged_in');
                $session['employer_detail'] = $employer;
                $this->session->set_userdata('logged_in', $session);
                $this->session->set_flashdata('message', '<b>Success:</b> Your Login credentials updated successfully');
                redirect('my_profile', 'location');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function my_profile() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_detail = $data['session']['company_detail'];
            $employer_detail = $data['session']['employer_detail'];
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_profile'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $company_detail['sid'];
            $employer_id = $employer_detail['sid'];
            $data = employee_right_nav($employer_id);
            $registration_date = $employer_detail['registration_date'];
            $data['title'] = 'My Profile';
//            $data['employer'] = $employer_detail;
            $data['employer'] = $this->dashboard_model->get_company_detail($employer_id);
            $employee_detail = $data['employer'];
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data['access_level'] = db_get_enum_values('users', 'access_level');
            $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_personal';
            //New my_profile view daata STARTS
            $data['applicant_message'] = array();
            $data['applicant_all_ratings'] = NULL;
            $data['applicant_ratings_count'] = NULL;
            $data['applicant_events'] = '';
            $rating_result = $this->application_tracking_system_model->getApplicantAllRating($employer_id, 'employee', $registration_date); //getting all rating of applicant
            $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee'); // getting applicant ratings - getting average rating of applicant

            if ($rating_result != NULL) {
                $data['applicant_ratings_count'] = $rating_result->num_rows();
                $data['applicant_all_ratings'] = $rating_result->result_array();
            }

            $data['company_accounts'] = $this->application_tracking_system_model->getCompanyAccounts($company_id); //fetching list of all sub-accounts
            $data['applicant_events'] = $this->application_tracking_system_model->getApplicantEvents($company_id, $employer_id, 'employee', $registration_date); //Getting Events
            $applicant_email = $data['email'];
            $rawMessages = $this->application_tracking_system_model->get_sent_messages($applicant_email, ""); // getting private messages of the user

            if (!empty($rawMessages)) {
                $i = 0;

                foreach ($rawMessages as $message) {
                    $employerData = $employer_detail;
                    $message['profile_picture'] = $employerData['profile_picture'];
                    $message['first_name'] = $employerData['first_name'];
                    $message['last_name'] = $employerData['last_name'];
                    $message['username'] = $employerData['username'];
                    $allMessages[$i] = $message;
                    $i++;
                }

                $data['applicant_message'] = $allMessages;
            }

            $data['employer']['state_name'] = '';
            $data['employer']['country_name'] = '';

            if (!empty($data['employer']['Location_State'])) { // get state name
                $state_id = $data['employer']['Location_State'];
                $country_state_info = db_get_state_name($state_id);
                $data['employer']['state_name'] = $country_state_info['state_name'];
                $data['employer']['country_name'] = $country_state_info['country_name'];
            }

            if (empty($data['employer']['country_name']) && !empty($data['employer']['Location_Country'])) {
                $country_id = $data['employer']['Location_Country'];
                $country_info = db_get_country_name($country_id);
                $data['employer']['country_name'] = $country_info['country_name'];
            }

            $data['employee_notes'] = $this->employee_model->getEmployeeNotes($employer_id, $registration_date); //Getting Notes - table: portal_misc_notes, employers_sid=company id, applicant_job_sid= employee_sid - start here
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean');
            //$this->form_validation->set_rules('access_level', 'Access Level', 'trim|xss_clean');

            if ($data['employer']['email'] != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'E-Mail Address', 'trim|xss_clean|valid_email|callback_if_user_exists_ci_validation');
            } else {
                $this->form_validation->set_rules('email', 'E-Mail Address', 'trim|xss_clean|valid_email');
            }

            if ($this->form_validation->run() === FALSE) {
                $data['edit_form'] = false;

                if ($this->input->post()) {
                    $data['edit_form'] = true;
                }

                $data['notes_view'] = false; //checking if the form is submitted so i can open the notes form screen again

                if (isset($_SESSION['show_notes']) && $_SESSION['show_notes'] == 'true') {
                    $data['notes_view'] = true;
                    $_SESSION['show_notes'] = 'false';
                }

                $data['show_event'] = false; //checking if the form is submitted so i can open the notes form screen again

                if (isset($_SESSION['show_event']) && $_SESSION['show_event'] == 'true') {
                    $data['show_event'] = true;
                    $_SESSION['show_event'] = 'false';
                }

                $data['show_message'] = false; //checking if the form is submitted so i can open the Messages form screen again

                if (isset($_SESSION['show_message']) && $_SESSION['show_message'] == 'true') {
                    $data['show_message'] = true;
                    $_SESSION['show_message'] = 'false';
                }

                $data['extra_info'] = unserialize($data['employer']['extra_info']); //Send extra Information.
                $data['employee'] = $data['employer'];
                // $zones = array();
                // $zones[] = ['name' => 'Hawaii-Aleutian Standard Time - HAST', 'value' => '-10:00|utc'];
                // $zones[] = ['name' => 'Alaska Standard Time - AKST', 'value' => '-9:00|utc'];
                // $zones[] = ['name' => 'Pacific Standard Time - PST', 'value' => '-8:00|utc'];
                // $zones[] = ['name' => 'Mountain Standard Time - MST', 'value' => '-7:00|utc'];
                // $zones[] = ['name' => 'Central Standard Time - CST', 'value' => '-6:00|utc'];
                // $zones[] = ['name' => 'Eastern Standard Time - EST', 'value' => '-5:00|utc'];
                // $zones[] = ['name' => 'Atlantic Standard Time - AST', 'value' => '-4:00|utc'];
                // $zones[] = ['name' => 'Newfoundland Standard Time - NST', 'value' => '-3:30|utc'];
                // $data['timezones'] = $zones;
                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $data['ssn_required'] = $data['session']['portal_detail']['ssn_required'];
                $data['dob_required'] = $data['session']['portal_detail']['dob_required'];
                // Added on: 04-07-2019
                $data['show_timezone'] = $data['session']['company_detail']['timezone'];

                // Check and set the company sms module
                // phone number
                company_sms_phonenumber(
                    $data['session']['company_detail']['sms_module_status'],
                    $company_id,
                    $data,
                    $this
                );

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/my_profile_new');
                $this->load->view('main/footer');
            } else {
                $sid = $this->input->post('id');
                $pictures = $this->input->post('old_profile_picture');
                $profile_picture = upload_file_to_aws('profile_picture', $company_id, 'profile_picture', $sid);

                if ($profile_picture != 'error') {
                    $pictures = $profile_picture;
                } else {
                    $pictures = '';
                }
                 $notified_by=$this->input->post("notified_by");
                $extra_info_arr = array();
                $extra_info_arr['secondary_email'] = $this->input->post('secondary_email');
                $extra_info_arr['secondary_PhoneNumber'] = $this->input->post('txt_secondary_phonenumber') ? $this->input->post('txt_secondary_phonenumber') : $this->input->post('secondary_PhoneNumber');
                $extra_info_arr['other_email'] = $this->input->post('other_email');
                $extra_info_arr['other_PhoneNumber'] = $this->input->post('txt_other_phonenumber') ? $this->input->post('txt_other_phonenumber') : $this->input->post('other_PhoneNumber');
                $extra_info_arr['title'] = $this->input->post('title');
                $extra_info_arr['division'] = $this->input->post('division');
                $extra_info_arr['department'] = $this->input->post('department');
                $extra_info_arr['office_location'] = $this->input->post('office_location');
                $extra_info_arr['interests'] = $this->input->post('interests');
                $extra_info_arr['short_bio'] = $this->input->post('short_bio');
                $extra_info = serialize($extra_info_arr);
                $video_source = $this->input->post('video_source');
                $video_id = '';

                // Check and set the company sms module
                // phone number
                company_sms_phonenumber(
                    $data['session']['company_detail']['sms_module_status'],
                    $company_id,
                    $comp,
                    $this
                );

                if ($video_source != 'no_video') {
                    if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                        $random = generateRandomString(5);
                        $company_id = $data['session']['company_detail']['sid'];
                        $target_file_name = basename($_FILES["upload_video"]["name"]);
                        $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/";
                        $target_file = $target_dir . $file_name;
                        $filename = $target_dir . $company_id;

                        if (!file_exists($filename)) {
                            mkdir($filename);
                        }

                        if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                        } else {

                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect('employee_management/my_profile', 'refresh');
                        }

                        $video_id = $file_name;
                    } else {
                        $video_id = $this->input->post('yt_vm_video_url');

                        if ($video_source == 'youtube') {
                            $url_prams = array();
                            parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $video_id = $url_prams['v'];
                            } else {
                                $video_id = '';
                            }
                        } else if ($video_source == 'vimeo') {
                            $video_id = $this->vimeo_get_id($video_id);
                        } else if ($video_source == 'uploaded' && $this->input->post('pre_upload_video_url') != '') {
                            $video_id = $this->input->post('pre_upload_video_url');
                        }
                    }
                }

                $date_of_birth = $this->input->post('dob');

                if (!empty($date_of_birth)) {
                    $DOB = date('Y-m-d', strtotime(str_replace('-', '/', $date_of_birth)));
                } else {
                    $DOB = '';
                }

                $data = array('first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'Location_Country' => $this->input->post('Location_Country'),
                    'Location_State' => $this->input->post('Location_State'),
                    'Location_City' => $this->input->post('Location_City'),
                    'Location_ZipCode' => $this->input->post('Location_ZipCode'),
                    'Location_Address' => $this->input->post('Location_Address'),
                    // 'PhoneNumber' => $this->input->post('PhoneNumber'),
                    'PhoneNumber' => $this->input->post('txt_phonenumber', true) ? $this->input->post('txt_phonenumber', true) : $this->input->post('PhoneNumber', true),
                    'video_type' => $video_source,
                    'YouTubeVideo' => $video_id,
                    'job_title' => $this->input->post('job_title'),
                    'extra_info' => $extra_info,
                    'linkedin_profile_url' => $this->input->post('linkedin_profile_url'),
                    'employee_number' => $this->input->post('employee_number'),
                    'ssn' => $this->input->post('ssn'),
                    'dob' => $DOB
                );

                if(IS_TIMEZONE_ACTIVE){
                    $data['timezone'] = $this->input->post('timezone', true);
                }

                if (!empty($pictures)) {
                    $data['profile_picture'] = $pictures;
                }

                // Added on: 26-06-2019
                if(IS_TIMEZONE_ACTIVE){
                    $new_timezone = $this->input->post('timezone', true);
                    if($new_timezone != '') $data['timezone'] = $new_timezone;
                }

                if(IS_NOTIFICATION_ENABLED == 1 && $comp['phone_sid'] != '' && $this->input->post('notified_by', true)){
                    if(!sizeof($this->input->post('notified_by', true))) $data['notified_by'] = 'email';
                    else $data['notified_by'] = implode(',', $this->input->post('notified_by', true));
                }

                //Ful Employment Application Form Update data
                $full_emp_app = isset($employee_detail['full_employment_application']) && !empty($employee_detail['full_employment_application']) ? unserialize($employee_detail['full_employment_application']) : array();
                $full_emp_app['PhoneNumber'] = $this->input->post('PhoneNumber');
                $full_emp_app['TextBoxTelephoneOther'] = $this->input->post('other_PhoneNumber');
                $full_emp_app['TextBoxAddressStreetFormer3'] = $this->input->post('other_email');
                $data['full_employment_application'] = serialize($full_emp_app);
                $this->dashboard_model->update_user($sid, $data);
                $employer = $this->dashboard_model->get_company_detail($sid);
                $session = $this->session->userdata('logged_in');
                $session['employer_detail'] = $employer;
                $this->session->set_userdata('logged_in', $session);
                $this->session->set_flashdata('message', '<b>Success:</b> Your Profile is updated successfully');
                redirect('my_profile', 'location');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function vimeo_get_id($str) {
        if ($str != "") {
            if($_SERVER['HTTP_HOST']=='localhost'){
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if(!empty($response)){
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return $response['video_id'];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return $response['video_id'];
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    function if_user_exists_ci_validation($str) {
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data['session']['company_detail']['sid'];
        $this->db->where('email', $str);
        $this->db->where('email <>', '');
        $this->db->where('parent_sid', $company_id);
        $userInfo = $this->db->get('users')->result_array();
        $return = FALSE;

        if (empty($userInfo)) {
            $return = TRUE;
        }

        $this->form_validation->set_message('if_user_exists_ci_validation', 'Provided email address is already in use!');
        return $return;
    }

    public function generate_password($key = NULL) {
        if($key != NULL) {
            $this->load->model('users_model');
            $check_exist = $this->users_model->check_key($key);

            if (!$check_exist) {
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Request!');
                redirect(base_url('login'), 'refresh');
            } else {
                if ($this->session->userdata('logged_in')) {
                    $this->session->unset_userdata('logged_in');
                    $this->session->unset_userdata('coupon_data');
                }

                $this->load->model('home_model');
                $data['home_page'] = $this->home_model->get_home_page_data();
                $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|matches[cpassword]');
                $this->form_validation->set_rules('cpassword', 'cPassword', 'trim|required|xss_clean');
                $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

                if ($this->form_validation->run() == FALSE) {
                    $data['page_title'] = 'Generate Password - Employee Team Member';
                    $this->load->view('main/static_header', $data);
                    $this->load->view('users/generate-password');
                    $this->load->view('main/footer');
                } else {
                    $password = $this->input->post('password');
                    $this->users_model->updatePass($password, $key);
                    $this->session->set_flashdata('message', 'Password generated successfully! Please login to access your account.');
                    redirect(base_url('login'), 'refresh');
                }
            }
        } else {
            $this->session->set_flashdata('message', 'Error: Your Security Key has Expired!, Please contact Administrator.');
            redirect(base_url('login'), 'refresh');
        }
    }

    function send_login_credentials() {
        $action = $this->input->post('action');
        $sid = $this->input->post('sid');
        $employee_details = $this->employee_model->get_employee_details($sid);

        if(!empty($employee_details)) {
            $first_name = $employee_details[0]['first_name'];
            $last_name = $employee_details[0]['last_name'];
            $username = $employee_details[0]['username'];
            $access_level = $employee_details[0]['access_level'];
            $email = $employee_details[0]['email'];
            $salt = $employee_details[0]['salt'];

            if($salt == NULL || $salt == '') {
                $salt = generateRandomString(48);

                $data = array('salt' => $salt);
                $this->employee_model->update_users($sid, $data);
            }

            if ($action == 'sendemail') {
                $replacement_array = array();
                $replacement_array['employer_name'] = ucwords($first_name . ' ' . $last_name);
                $replacement_array['access_level'] = ucwords($access_level);
                $replacement_array['company_name'] = $employee_details[1]['CompanyName'];
                $replacement_array['username'] = $username;
                $replacement_array['login_page'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="https://www.automotohr.com/login" target="_blank">Login page</a>';
                $replacement_array['firstname'] = $first_name;
                $replacement_array['lastname'] = $last_name;
                $replacement_array['first_name'] = $first_name;
                $replacement_array['last_name'] = $last_name;
                $replacement_array['email'] = $email;
                $replacement_array['create_password_link']  = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "employee_management/generate_password/" . $salt . '">Create Your Password</a>';
                log_and_send_templated_email(NEW_EMPLOYEE_TEAM_MEMBER_NOTIFICATION, $email, $replacement_array);
            }

            echo 'success';
        } else {
            echo 'error';
        }
    }


    /**
     * Check for old event check
     *
     * @return Bool
     */
    private function call_old_event(){
        $this->load->config('calendar_config');
        $calendar_opt = $this->config->item('calendar_opt');
        if($calendar_opt['show_new_calendar_to_all'])
            return true;
        if(
            ($calendar_opt['old_event_check'] && !$calendar_opt['ids_check'] && in_array($this->input->ip_address(), $calendar_opt['remote_ips'])) ||
            ($calendar_opt['old_event_check'] && $calendar_opt['ids_check'] && in_array($this->session->userdata('logged_in')['company_detail']['sid'], $calendar_opt['allowed_ids']))
        ){ return true; }

        return false;
    }

    public function change_complynet_status(){
        $sid = $this->input->post("sid");
        $status = $this->input->post("status");
        if($status){
            $data = array('complynet_status'=>1);
        }
        else{
            $data = array('complynet_status'=>0);
        }
        $this->dashboard_model->update_user($sid,$data);
        echo 'updated';
    }

    function get_all_department_teams ($department_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];

            $department_teams = $this->employee_model->get_all_department_related_teams($company_sid, $department_sid);
            if (!empty($department_teams)) {
                echo json_encode($department_teams);
            } else {
               echo 0;
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    /**
     * Handles AJAX requests
     * Created on: 18-07-2019
     *
     * @accepts POST
     *
     * @return JSON
     */
    function handler(){
        // Set default aray
        $resp['Status'] = false;
        $resp['Response'] = 'Invalid request.';
        //
        if(!sizeof($this->input->post()) || $this->input->method(TRUE) != 'POST') $this->resp($resp);
        //
        $form_data = $this->input->post(NULL, TRUE);
        // Load the twilio library
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        //
        // _e($form_data, true);
        // _e(IS_SANDBOX === 1, true);
        //
        $module = 'emp';
        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $employee_sid = $session['employer_detail']['sid'];


        switch ($form_data['action']) {
            case 'send_sms':
                // Double check - If SMS module is not active
                // then through an error
                if($session['company_detail']['sms_module_status'] == 0){
                    $resp['Response'] = 'SMS module is not active for this company.';
                    $this->resp($resp);
                }
                // Message send
                // Create message service and add phone number
                $message_body = $form_data['message'];
                // Set & Send Request
                $this
                ->twilio
                ->setMode(IS_SANDBOX === 1 ? 'sandbox' : 'production')
                ->setMessage($message_body);

                if(IS_SANDBOX != 1){
                    $this->twilio->setReceiverPhone($form_data['phone_e16']);
                    $this->twilio->setMessageServiceSID(get_company_sms_phonenumber($company_sid, $this)['message_service_sid']);
                }
                //
                $resp2 = $this->twilio->sendMessage();
                // Check & Handling Errors
                if(!is_array($resp2)){
                    $resp['Response'] = 'Failed to send SMS.';
                    $this->resp($resp);
                }
                if(isset($resp2['Error'])){
                    $resp['Response'] = 'Failed to send SMS.';
                    $this->resp($resp);
                }
                // Set Insert Array
                $insert_array = $resp2['DataArray'];
                $insert_array['module_slug'] = $module;
                $insert_array['company_id']  = $company_sid;
                $insert_array['sender_user_id'] = $employee_sid;
                $insert_array['sender_user_type'] = 'employee';
                $insert_array['receiver_user_id'] = isset($form_data['applicant_id']) ? $form_data['applicant_id'] : $form_data['id'];
                $insert_array['receiver_user_type'] = 'employee';
                //
                if(IS_SANDBOX === 1){
                    $insert_array['receiver_phone_number'] = $form_data['phone_e16'];
                }
                // Add data in database
                $insert_id = $this
                ->employee_model
                ->save_sent_message($insert_array);

                $resp['Status'] = TRUE;
                $resp['Response'] = 'SMS sent.';

                $this->resp($resp);
            break;

            case 'fetch_sms_employee':
                $records = $this
                ->employee_model
                ->fetch_sms(
                    $form_data['type'],
                    isset($form_data['applicant_id']) ? $form_data['applicant_id'] : $form_data['id'],
                    $company_sid,
                    $form_data['last_fetched_id'],
                    isset($form_data['module']) ? $form_data['module'] : '',
                    $this->limit
                );

                if(!$records){
                    $resp['Response'] = 'No record found.'; $this->resp($resp);
                }
                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed';
                $resp['Data'] = $records['Records'];
                $resp['LastId'] = $records['LastId'];
                $resp['Unread'] = $records['Unread'];
                $this->resp($resp);
            break;

            case 'update_phone_number':
                // Update applicant phonenumber
                $this
                ->employee_model
                ->employee_phone_number(
                    $form_data['phone_e16'],
                    $form_data['applicant_id']
                );
                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Phone number updated.';
                $resp['Phone'] = phonenumber_format($form_data['phone_e16']);
            break;
        }

        $this->resp($resp);
    }


    /**
     * Sends JSON
     * Created on: 23-07-2019
     *
     * @param $resp Array
     *
     * @return JSON
     */
    private function resp($resp){
        header('Content-Type: application/json');
        echo @json_encode($resp);
        exit(0);
    }

    /**
     * Delete file
     *
     */
    function delete_file() {
        $type = $this->input->post('type', true);

        $this->employee_model->delete_file($this->input->post('id', true), strtolower(preg_replace('/\s+/','_', $type)));

        $this->session->set_flashdata('message', '<b>Success:</b> ' . $type . ' removed successfully');
    }

    public function employee_goals($sid) {
        if ($this->session->userdata('logged_in')) {
            $data = employee_right_nav($sid);
            // Added on: 04-07-2019
            $data['show_timezone'] = $data['session']['company_detail']['timezone'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'employee_management', 'employee_profile'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_access_level = $data["session"]["employer_detail"]["access_level"];
            $data['access_level_plus'] = $data["session"]["employer_detail"]["access_level_plus"];
            $employer_id = $sid;
            $data['title'] = "Employee / Team Members Profile";
            $data['employer_sid'] = $security_sid;
            $data['main_employer_id'] = $security_sid;
            $data['employer'] = $this->dashboard_model->get_company_detail($employer_id);
            $employee_detail = $data['employer'];

            // Check and set the company sms module
            // phone number
            company_sms_phonenumber(
                $data['session']['company_detail']['sms_module_status'],
                $company_id,
                $data,
                $this
            );

            if ($data['employer']['department_sid'] > 0) {
                $department_name = $this->employee_model->get_department_name($data['employer']['department_sid']);                   
                $data['department_name'] = $department_name;
            }

            if ($data['employer']['team_sid'] > 0) {
                $team_name = $this->employee_model->get_team_name($data['employer']['team_sid']);
                $data['team_name'] = $team_name;
            }

            if(isset($data['team_name']) && $data['team_name'] == ''){
                // Fetch employee team and department
                $t = $this->employee_model->fetch_department_teams($employer_id);
                if(sizeof($t)){
                    $data['employer']['department_sid'] = $t['department_sid'];
                    $data['employer']['team_sid'] = $t['team_sid'];
                    //
                    $department_name = $this->employee_model->get_department_name($data['employer']['department_sid']);
                    $data['department_name'] = $department_name;
                    $team_name = $this->employee_model->get_team_name($data['employer']['team_sid']);
                    $data['team_name'] = $team_name;
                }
            } 

            if (empty($data['employer']['employee_number']) || $data['employer']['employee_number'] == '') {
                $data['employer']['employee_number'] = $sid; 
            }

            $employee_assign_team = $this->employee_model->fetch_employee_assign_teams($employer_id);
            $data['team_names'] = [];
            $data['team_sids'] = [];
            if (!empty($employee_assign_team)) {
                $data['team_names'] = $employee_assign_team['team_names'];
                $data['team_sids'] = $employee_assign_team['team_sids'];
            }

            if (empty($data['employer'])) { // Employer does not exists - throw error
                $this->session->set_flashdata('message', '<b>Error:</b> No Employee found!');
                redirect('employee_management', 'refresh');
            }

            if (!$data['session']['employer_detail']['access_level_plus'] && !$data['session']['employer_detail']['pay_plan_flag']) {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Module Not Accessable!');
                redirect('employee_management', 'refresh');
            }

            $parent_sid = $data["employer"]["parent_sid"];

            if ($company_id != $parent_sid) { // Employer exists but does not belongs to this company - throw error
                $this->session->set_flashdata('message', '<b>Error:</b> Employee does not exists in your company!');
                redirect('employee_management', 'refresh');
            }

            $data['employer_id'] = $employer_id;
            $applicant_sid = $data['employer']['applicant_sid']; // check if this employee is moved from ATS
            $data['employer']['test'] = false;
            $data['applicant_jobs'] = $this->application_tracking_system_model->get_single_applicant_all_jobs($applicant_sid, $company_id);
            if (!empty($applicant_sid)) { //Questionare
                // $applicant_info = $this->application_tracking_system_model->getApplicantData($applicant_sid);
                // $data['employer']['score'] = $applicant_info['score'];
                // $data['employer']['passing_score'] = $applicant_info['passing_score'];
                // if ($applicant_info['questionnaire'] != NULL) {
                // $myquestionnaire = unserialize($applicant_info['questionnaire']);
                // $data['employer']['questionnaire'] = $myquestionnaire;
                // $data['employer']['test'] = true;
                //  }
            }

            $data['applicant_message'] = array();
            $data['applicant_all_ratings'] = NULL;
            $data['applicant_ratings_count'] = NULL;
            $data['applicant_events'] = '';
            $registration_date = $data['session']['employer_detail']['registration_date'];
            $rating_result = $this->application_tracking_system_model->getApplicantAllRating($employer_id, 'employee', $registration_date); //getting all rating of applicant
            $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee'); // getting applicant ratings - getting average rating of applicant

            if ($rating_result != NULL) {
                $data['applicant_ratings_count'] = $rating_result->num_rows();
                $data['applicant_all_ratings'] = $rating_result->result_array();
            }

            $company_accounts = $this->application_tracking_system_model->getCompanyAccounts($company_id); //fetching list of all sub-accounts
            $data['company_timezone'] = $company_timezone = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;
            foreach($company_accounts as $key => $company_account){
                $company_accounts[$key]['timezone'] = !empty($company_account['timezone']) ? $company_account['timezone'] : $company_timezone;
            }
            $data['company_accounts'] = $company_accounts;
            if(!empty($data['session']['employer_detail']['timezone']))
                $data['employer_timezone'] =   $data['session']['employer_detail']['timezone']; 
            else
                $data['employer_timezone'] = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;
        
            $data['upcoming_events'] = $this->employee_model->get_employee_events($company_id, $sid, 'upcoming'); //Getting Events
            $to_id = $data['id'];
            $rawMessages = $this->application_tracking_system_model->get_sent_messages($to_id, NULL);

            if (!empty($rawMessages)) {
                $i = 0;

                foreach ($rawMessages as $message) {
                    if ($message['outbox'] == 1) {
                        $employerData = $this->application_tracking_system_model->getEmployerDetail($data["session"]["employer_detail"]["sid"]);
                        $message['profile_picture'] = $employerData['profile_picture'];
                        $message['first_name'] = $employerData['first_name'];
                        $message['last_name'] = $employerData['last_name'];
                        $message['username'] = $employerData['username'];
                    } else {
                        $message['profile_picture'] = $data['employer']['profile_picture'];
                        $message['first_name'] = $data['employer']['first_name'];
                        $message['last_name'] = $data['employer']['last_name'];
                        $message['username'] = "";
                    }

                    $allMessages[$i] = $message;
                    $i++;
                }

                $data['applicant_message'] = $allMessages;
            }

            $data['extra_info'] = unserialize($data['employer']['extra_info']);
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

            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data["access_level"] = db_get_enum_values('users', 'access_level');
            $data['employer']['state_name'] = '';
            $data['employer']['country_name'] = '';

            if (!empty($data['employer']['Location_State'])) { // get state name
                $state_id = $data['employer']['Location_State'];
                $country_state_info = db_get_state_name($state_id);
                $data['employer']['state_name'] = $country_state_info['state_name'];
                $data['employer']['country_name'] = $country_state_info['country_name'];
            }

            if (empty($data['employer']['country_name']) && !empty($data['employer']['Location_Country'])) {
                $country_id = $data['employer']['Location_Country'];
                $country_info = db_get_country_name($country_id);
                $data['employer']['country_name'] = $country_info['country_name'];
            }

            $data['employee_notes'] = $this->employee_model->getEmployeeNotes($employer_id, $registration_date); //Getting Notes - table: portal_misc_notes, employers_sid=company id, applicant_job_sid= employee_sid - start here
            //
            $data['_ssv'] = $_ssv = getSSV($data['session']['employer_detail']);
            
            //checking if the form is submitted so i can open the form screen again
            $this->load->model('portal_email_templates_model');
            $data['edit_form'] = false;

            if ($this->input->post()) {
                $data['edit_form'] = true;
            }

            $data['notes_view'] = false; //checking if the form is submitted so i can open the notes form screen again

            if (isset($_SESSION['show_notes']) && $_SESSION['show_notes'] == 'true') {
                $data['notes_view'] = true;
                $_SESSION['show_notes'] = 'false';
            }

            //checking if the form is submitted so i can open the notes form screen again
            $data['show_event'] = false;

            if (isset($_SESSION['show_event']) && $_SESSION['show_event'] == 'true') {
                $data['show_event'] = true;
                $_SESSION['show_event'] = 'false';
            }

            $data['show_message'] = false; //checking if the form is submitted so i can open the Messages form screen again

            if (isset($_SESSION['show_message']) && $_SESSION['show_message'] == 'true') {
                $data['show_message'] = true;
                $_SESSION['show_message'] = 'false';
            }

            $portal_email_templates = $this->application_tracking_system_model->get_portal_email_templates($company_id);

            foreach ($portal_email_templates as $key => $template) {
                $portal_email_templates[$key]['attachments'] = $this->portal_email_templates_model->get_all_email_template_attachments($template['sid']);
            }

            $data['portal_email_templates'] = $portal_email_templates;

            if (empty($data['employer']['resume'])) { // check if reseme is uploaded
                $data['employer']['resume_link'] = "javascript:void(0);";
                $data['resume_link_title'] = "No Resume found!";
                if(!empty($data['employer']['applicant_sid']) && $data['employer']['applicant_sid'] != NULL){
                    $resume = $this->employee_model->check_for_resume($data['employer']['applicant_sid']);
                    if($resume != 0){
                        $data['employer']['resume_link'] = AWS_S3_BUCKET_URL . $resume;
                        $data['resume_link_title'] = $resume;
                    }
                }
            } else {
                $data['employer']['resume_link'] = AWS_S3_BUCKET_URL . $data['employer']['resume'];
                $data['resume_link_title'] = $data['employer']['resume'];
            }

            if (empty($data['employer']['cover_letter'])) { // check if cover letter is uploaded
                $data['employer']["cover_link"] = "javascript:void(0)";
                $data['cover_letter_title'] = "No Cover Letter found!";
            } else {
                $data['employer']["cover_link"] = AWS_S3_BUCKET_URL . $data['employer']['cover_letter'];
                $data['cover_letter_title'] = $data['employer']['cover_letter'];
            }
            $data['employment_statuses'] = $this->application_tracking_system_model->getEmploymentStatuses();
            $data['employment_types'] = $this->application_tracking_system_model->getEmploymentTypes();
            $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
            $addresses = $this->employee_model->get_company_addresses($company_id);
            $data['addresses'] = $addresses;
            $departments = $this->employee_model->get_all_departments($company_id);
            $data['departments'] = $departments;
            $data['is_new_calendar'] = $this->call_old_event();
            // Time off policies
            $this->load->model('performance_management_model', 'pmm');
            $gd = $this->pmm->GetAllGoals($company_id);
            //
            $get = $this->input->get(NULL, TRUE);
            //
            $data['filter'] = [];
            $data['filter']['start_date'] = isset($get['start_date']) ? $get['start_date'] : '';
            $data['filter']['end_date'] = isset($get['end_date']) ? $get['end_date'] : '';
            $data['filter']['Types'] = isset($get['goal_types']) ? $get['goal_types'] : [];
            //
            $data['MyGoals'] = [];
            //
            $start_date = '';
            $end_date = '';
            //
            if(!empty($data['filter']['start_date'])){
                $start_date = formatDateToDB($data['filter']['start_date']);
            }
            //
            if(!empty($data['filter']['end_date'])){
                $end_date = formatDateToDB($data['filter']['end_date']);
            }
            //
            foreach($gd as $g){
                //
                if($g['employee_sid'] == $employee_detail['sid']){
                    //
                    if(!empty($data['filter']['Types']) && !in_array($g['status'],$data['filter']['Types'])){
                        continue;
                    }
                    //
                    if(!empty($start_date) && $g['start_date'] < $data['filter']['start_date'] ){
                        continue;
                    }
                    //
                    if(!empty($end_date) && $g['end_date'] > $data['filter']['end_date'] ){
                        continue;
                    }
                    $data['MyGoals'][] = $g;
                }
            }

            //
            $data['pp'] = 'Performance_management/theme2/';

            $data['companyId'] = $data['session']['company_detail']['sid'];
            $data['companyName'] = $data['session']['company_detail']['CompanyName'];
            $data['employerId'] = $data['session']['employer_detail']['sid'];
            $data['employerName'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
            $data['isSuperAdmin'] = $data['session']['employer_detail']['access_level_plus'];
            $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 ? 1 : 0;
            $data['employerRole'] = $data['session']['employer_detail']['access_level'] ;

            //
            $data['ne']= [];
            $data['ne'][$employee_detail['sid']] = $employee_detail;
    
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/employee_management/dts');
            //$this->load->view('manage_employer/employee_profile_view');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }


    public function employee_reviews($sid) {
        if ($this->session->userdata('logged_in')) {
            $data = employee_right_nav($sid);
            // Added on: 04-07-2019
            $data['show_timezone'] = $data['session']['company_detail']['timezone'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'employee_management', 'employee_profile'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_access_level = $data["session"]["employer_detail"]["access_level"];
            $data['access_level_plus'] = $data["session"]["employer_detail"]["access_level_plus"];
            $employer_id = $sid;
            $data['title'] = "Employee / Team Members Profile";
            $data['employer_sid'] = $security_sid;
            $data['main_employer_id'] = $security_sid;
            $data['employer'] = $this->dashboard_model->get_company_detail($employer_id);
            $employee_detail = $data['employer'];

            // Check and set the company sms module
            // phone number
            company_sms_phonenumber(
                $data['session']['company_detail']['sms_module_status'],
                $company_id,
                $data,
                $this
            );

            if ($data['employer']['department_sid'] > 0) {
                $department_name = $this->employee_model->get_department_name($data['employer']['department_sid']);                   
                $data['department_name'] = $department_name;
            }

            if ($data['employer']['team_sid'] > 0) {
                $team_name = $this->employee_model->get_team_name($data['employer']['team_sid']);
                $data['team_name'] = $team_name;
            }

            if(isset($data['team_name']) && $data['team_name'] == ''){
                // Fetch employee team and department
                $t = $this->employee_model->fetch_department_teams($employer_id);
                if(sizeof($t)){
                    $data['employer']['department_sid'] = $t['department_sid'];
                    $data['employer']['team_sid'] = $t['team_sid'];
                    //
                    $department_name = $this->employee_model->get_department_name($data['employer']['department_sid']);
                    $data['department_name'] = $department_name;
                    $team_name = $this->employee_model->get_team_name($data['employer']['team_sid']);
                    $data['team_name'] = $team_name;
                }
            } 

            if (empty($data['employer']['employee_number']) || $data['employer']['employee_number'] == '') {
                $data['employer']['employee_number'] = $sid; 
            }

            $employee_assign_team = $this->employee_model->fetch_employee_assign_teams($employer_id);
            $data['team_names'] = [];
            $data['team_sids'] = [];
            if (!empty($employee_assign_team)) {
                $data['team_names'] = $employee_assign_team['team_names'];
                $data['team_sids'] = $employee_assign_team['team_sids'];
            }

            if (empty($data['employer'])) { // Employer does not exists - throw error
                $this->session->set_flashdata('message', '<b>Error:</b> No Employee found!');
                redirect('employee_management', 'refresh');
            }

            if (!$data['session']['employer_detail']['access_level_plus'] && !$data['session']['employer_detail']['pay_plan_flag']) {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Module Not Accessable!');
                redirect('employee_management', 'refresh');
            }

            $parent_sid = $data["employer"]["parent_sid"];

            if ($company_id != $parent_sid) { // Employer exists but does not belongs to this company - throw error
                $this->session->set_flashdata('message', '<b>Error:</b> Employee does not exists in your company!');
                redirect('employee_management', 'refresh');
            }

            $data['employer_id'] = $employer_id;
            $applicant_sid = $data['employer']['applicant_sid']; // check if this employee is moved from ATS
            $data['employer']['test'] = false;
            $data['applicant_jobs'] = $this->application_tracking_system_model->get_single_applicant_all_jobs($applicant_sid, $company_id);
            if (!empty($applicant_sid)) { //Questionare
                // $applicant_info = $this->application_tracking_system_model->getApplicantData($applicant_sid);
                // $data['employer']['score'] = $applicant_info['score'];
                // $data['employer']['passing_score'] = $applicant_info['passing_score'];
                // if ($applicant_info['questionnaire'] != NULL) {
                // $myquestionnaire = unserialize($applicant_info['questionnaire']);
                // $data['employer']['questionnaire'] = $myquestionnaire;
                // $data['employer']['test'] = true;
                //  }
            }

            $data['applicant_message'] = array();
            $data['applicant_all_ratings'] = NULL;
            $data['applicant_ratings_count'] = NULL;
            $data['applicant_events'] = '';
            $registration_date = $data['session']['employer_detail']['registration_date'];
            $rating_result = $this->application_tracking_system_model->getApplicantAllRating($employer_id, 'employee', $registration_date); //getting all rating of applicant
            $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_id, 'employee'); // getting applicant ratings - getting average rating of applicant

            if ($rating_result != NULL) {
                $data['applicant_ratings_count'] = $rating_result->num_rows();
                $data['applicant_all_ratings'] = $rating_result->result_array();
            }

            $company_accounts = $this->application_tracking_system_model->getCompanyAccounts($company_id); //fetching list of all sub-accounts
            $data['company_timezone'] = $company_timezone = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;
            foreach($company_accounts as $key => $company_account){
                $company_accounts[$key]['timezone'] = !empty($company_account['timezone']) ? $company_account['timezone'] : $company_timezone;
            }
            $data['company_accounts'] = $company_accounts;
            if(!empty($data['session']['employer_detail']['timezone']))
                $data['employer_timezone'] =   $data['session']['employer_detail']['timezone']; 
            else
                $data['employer_timezone'] = !empty($data['session']['company_detail']['timezone']) ? $data['session']['company_detail']['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR;
        
            $data['upcoming_events'] = $this->employee_model->get_employee_events($company_id, $sid, 'upcoming'); //Getting Events
            $to_id = $data['id'];
            $rawMessages = $this->application_tracking_system_model->get_sent_messages($to_id, NULL);

            if (!empty($rawMessages)) {
                $i = 0;

                foreach ($rawMessages as $message) {
                    if ($message['outbox'] == 1) {
                        $employerData = $this->application_tracking_system_model->getEmployerDetail($data["session"]["employer_detail"]["sid"]);
                        $message['profile_picture'] = $employerData['profile_picture'];
                        $message['first_name'] = $employerData['first_name'];
                        $message['last_name'] = $employerData['last_name'];
                        $message['username'] = $employerData['username'];
                    } else {
                        $message['profile_picture'] = $data['employer']['profile_picture'];
                        $message['first_name'] = $data['employer']['first_name'];
                        $message['last_name'] = $data['employer']['last_name'];
                        $message['username'] = "";
                    }

                    $allMessages[$i] = $message;
                    $i++;
                }

                $data['applicant_message'] = $allMessages;
            }

            $data['extra_info'] = unserialize($data['employer']['extra_info']);
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

            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data["access_level"] = db_get_enum_values('users', 'access_level');
            $data['employer']['state_name'] = '';
            $data['employer']['country_name'] = '';

            if (!empty($data['employer']['Location_State'])) { // get state name
                $state_id = $data['employer']['Location_State'];
                $country_state_info = db_get_state_name($state_id);
                $data['employer']['state_name'] = $country_state_info['state_name'];
                $data['employer']['country_name'] = $country_state_info['country_name'];
            }

            if (empty($data['employer']['country_name']) && !empty($data['employer']['Location_Country'])) {
                $country_id = $data['employer']['Location_Country'];
                $country_info = db_get_country_name($country_id);
                $data['employer']['country_name'] = $country_info['country_name'];
            }

            $data['employee_notes'] = $this->employee_model->getEmployeeNotes($employer_id, $registration_date); //Getting Notes - table: portal_misc_notes, employers_sid=company id, applicant_job_sid= employee_sid - start here
            //
            $data['_ssv'] = $_ssv = getSSV($data['session']['employer_detail']);
            
            //checking if the form is submitted so i can open the form screen again
            $this->load->model('portal_email_templates_model');
            $data['edit_form'] = false;

            if ($this->input->post()) {
                $data['edit_form'] = true;
            }

            $data['notes_view'] = false; //checking if the form is submitted so i can open the notes form screen again

            if (isset($_SESSION['show_notes']) && $_SESSION['show_notes'] == 'true') {
                $data['notes_view'] = true;
                $_SESSION['show_notes'] = 'false';
            }

            //checking if the form is submitted so i can open the notes form screen again
            $data['show_event'] = false;

            if (isset($_SESSION['show_event']) && $_SESSION['show_event'] == 'true') {
                $data['show_event'] = true;
                $_SESSION['show_event'] = 'false';
            }

            $data['show_message'] = false; //checking if the form is submitted so i can open the Messages form screen again

            if (isset($_SESSION['show_message']) && $_SESSION['show_message'] == 'true') {
                $data['show_message'] = true;
                $_SESSION['show_message'] = 'false';
            }

            $portal_email_templates = $this->application_tracking_system_model->get_portal_email_templates($company_id);

            foreach ($portal_email_templates as $key => $template) {
                $portal_email_templates[$key]['attachments'] = $this->portal_email_templates_model->get_all_email_template_attachments($template['sid']);
            }

            $data['portal_email_templates'] = $portal_email_templates;

            if (empty($data['employer']['resume'])) { // check if reseme is uploaded
                $data['employer']['resume_link'] = "javascript:void(0);";
                $data['resume_link_title'] = "No Resume found!";
                if(!empty($data['employer']['applicant_sid']) && $data['employer']['applicant_sid'] != NULL){
                    $resume = $this->employee_model->check_for_resume($data['employer']['applicant_sid']);
                    if($resume != 0){
                        $data['employer']['resume_link'] = AWS_S3_BUCKET_URL . $resume;
                        $data['resume_link_title'] = $resume;
                    }
                }
            } else {
                $data['employer']['resume_link'] = AWS_S3_BUCKET_URL . $data['employer']['resume'];
                $data['resume_link_title'] = $data['employer']['resume'];
            }

            if (empty($data['employer']['cover_letter'])) { // check if cover letter is uploaded
                $data['employer']["cover_link"] = "javascript:void(0)";
                $data['cover_letter_title'] = "No Cover Letter found!";
            } else {
                $data['employer']["cover_link"] = AWS_S3_BUCKET_URL . $data['employer']['cover_letter'];
                $data['cover_letter_title'] = $data['employer']['cover_letter'];
            }
            $data['employment_statuses'] = $this->application_tracking_system_model->getEmploymentStatuses();
            $data['employment_types'] = $this->application_tracking_system_model->getEmploymentTypes();
            $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
            $addresses = $this->employee_model->get_company_addresses($company_id);
            $data['addresses'] = $addresses;
            $departments = $this->employee_model->get_all_departments($company_id);
            $data['departments'] = $departments;
            $data['is_new_calendar'] = $this->call_old_event();
            // Time off policies
            $this->load->model('performance_management_model', 'pmm');
            $gd = $this->pmm->GetAllGoals($company_id);
            //
            $get = $this->input->get(NULL, TRUE);
            //
            $data['filter'] = [];
            $data['filter']['start_date'] = isset($get['start_date']) ? $get['start_date'] : '';
            $data['filter']['end_date'] = isset($get['end_date']) ? $get['end_date'] : '';
            $data['filter']['Types'] = isset($get['goal_types']) ? $get['goal_types'] : [];
            $data['filter']['Reviewers'] = isset($get['reviewers']) ? $get['reviewers'] : [];
            //
            $data['MyReviews'] = [];
            //
            $start_date = '';
            $end_date = '';
            //
            $status = 'all';
            $reviewers = [];
            //
            if(!empty($data['filter']['start_date'])){
                $start_date = formatDateToDB($data['filter']['start_date']);
            }
            //
            if(!empty($data['filter']['end_date'])){
                $end_date = formatDateToDB($data['filter']['end_date']);
            }
            //
            if(!empty($data['filter']['Types'])){
                $status = $data['filter']['Types'];
            }
            //
            if(!empty($data['filter']['Reviewers'])){
                $reviewers = $data['filter']['Reviewers'];
            }
            $data['filter']['Id'] = $sid;
            //
            $this->load->helper('performance_management');

            //
            $data['MyReviews'] = $this->pmm->GetEmployeeReviews($sid, $start_date, $end_date, $status, $reviewers, $company_id);
            $data['employeesList'] = $this->pmm->GetAllEmployees($company_id);

            //
            $data['pp'] = 'Performance_management/theme2/';

            $data['companyId'] = $data['session']['company_detail']['sid'];
            $data['companyName'] = $data['session']['company_detail']['CompanyName'];
            $data['employerId'] = $data['session']['employer_detail']['sid'];
            $data['employerName'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
            $data['isSuperAdmin'] = $data['session']['employer_detail']['access_level_plus'];
            $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 ? 1 : 0;
            $data['employerRole'] = $data['session']['employer_detail']['access_level'] ;

            //
            $data['ne']= [];
            $data['ne'][$employee_detail['sid']] = $employee_detail;
    
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/employee_management/reviews');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    //
    function GetEmployeeProfile($employeeId){
        //
        $resp = [
            'Status' => false,
            'Msg' => 'Invalid Request'
        ];
        //
        if (!$this->session->userdata('logged_in')) {
            //
            $resp['Msg'] = 'Your session has expired';
            res($resp);
        }
        //
        if (!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {
            //
            $resp['Msg'] = 'You don\'t permission to the employee profile.';
            res($resp);
        }
        //
        $companyId = $this->session->userdata('logged_in')['company_detail']['sid'];
        // Fetch employees profile
        $record = $this->employee_model->GetEmployeeProfile($employeeId, $companyId);
        //
        if(empty($record)){
            $resp['Msg'] = 'Employee not found';
        } else{
            $resp['Status'] = true;
            $resp['Msg'] = 'Proceed.';
            $resp['Data'] = $this->load->view('quick_view', $record, true);
        }
        //
        res($resp);
    }
    
    
    //
    function GetAllEmployees(){
        //
        $resp = [
            'Status' => false,
            'Msg' => 'Invalid Request'
        ];
        //
        if (!$this->session->userdata('logged_in')) {
            //
            $resp['Msg'] = 'Your session has expired';
            res($resp);
        }
        //
        if (!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {
            //
            $resp['Msg'] = 'You don\'t permission to the employee profile.';
            res($resp);
        }
        //
        $companyId = $this->session->userdata('logged_in')['company_detail']['sid'];
        // Fetch employees profile
        $records = $this->employee_model->GetAllEmployees($companyId);
        //
        if(empty($records)){
            $resp['Msg'] = 'Employees not found';
        } else{
            $resp['Status'] = true;
            $resp['Msg'] = 'Proceed.';
            $resp['Data'] = $records;
        }
        //
        res($resp);
    }
}
