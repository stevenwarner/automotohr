<?php defined('BASEPATH') or exit('No direct script access allowed');

class General_info extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('general_info_model');
    }

    //This function get all the below information against
    //login Employee which are:-
    //** Driver License Information
    //** Occupational License Information
    //** Dependent Peopel Information
    //** Emergency Contact Information
    //** Banking Information
    //** Equipment Information
    //after collection these information it will populate
    //in 'general_information/index.php' if user want to 
    //update its general information then this function 
    //update any above information which mention above.
    public function index($key = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['dob'] = $this->general_info_model->get_dob($security_sid);
            $company_sid = $data['session']['company_detail']['sid'];
            $employee_sid = $data['session']['employer_detail']['sid'];
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            $employer = $data['session']['employer_detail'];
            $data['title'] = 'General Info';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            $data['employee'] = $data['session']['employer_detail'];
            $data['company_id'] = $company_sid;
            $company_name = $data['session']['company_detail']['CompanyName'];

            if ($this->form_validation->run() == false) {

                $occupational_license = $this->general_info_model->get_license_details('employee', $employee_sid, 'occupational');
                if (!empty($occupational_license)) {
                    $occupational_license['license_details'] = unserialize($occupational_license['license_details']);
                }

                $data['occupational_license_details'] = $occupational_license;

                $drivers_license = $this->general_info_model->get_license_details('employee', $employee_sid, 'drivers');
                if (!empty($drivers_license)) {
                    $drivers_license['license_details'] = unserialize($drivers_license['license_details']);
                }

                $data['drivers_license_details'] = $drivers_license;

                $dependents = $this->general_info_model->get_dependant_information('employee', $employee_sid);
                $data['dependents_arr'] = $dependents;

                $emergency_contacts = $this->general_info_model->get_employee_emergency_contacts('employee', $employee_sid);
                $data['emergency_contacts'] = $emergency_contacts;

                $bank_details = $this->general_info_model->get_bank_details('employee', $employee_sid);
                $data['bank_details'] = $bank_details;

                $equipments = $this->general_info_model->get_equipment_info('employee', $employee_sid);
                $data['equipments'] = $equipments;

                $data_countries = db_get_active_countries();
                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data_states_encode = htmlentities(json_encode($data_states));
                $data['states'] = $data_states_encode;

                $license_types = array();
                $license_types['Sales License'] = 'Sales License';
                $license_types['Commercial Driver’s License'] = 'Commercial Driver’s License';
                $license_types['Non-commercial Driver’s License'] = 'Non-commercial Driver’s License';
                $license_types['Restricted Driver’s License'] = 'Restricted Driver’s License';
                $license_types['Basic Driver’s License'] = 'Basic Driver’s License';
                $license_types['Identification Card'] = 'Identification Card';
                $license_types['College Diploma'] = 'College Diploma';
                $license_types['Training'] = 'Training';
                $license_types['Other'] = 'Other';
                $data['license_types'] = $license_types;

                $license_classes = array();
                $license_classes['None'] = 'None';
                $license_classes['Class A'] = 'Class A';
                $license_classes['Class B'] = 'Class B';
                $license_classes['Class C'] = 'Class C';
                $license_classes['Other'] = 'Other';
                $data['license_classes'] = $license_classes;

                //
                $this->load->model('direct_deposit_model');

                $employee_number = $this->direct_deposit_model->get_user_extra_info('employee', $employee_sid, $company_sid);

                $data['data'] = $this->direct_deposit_model->getDDI('employee', $employee_sid, $company_sid);
                $data['dd_user_type'] = 'employee';
                $data['dd_user_sid'] = $employee_sid;
                $data['company_name'] = $company_name;
                $data['employee_number'] = $employee_number;
                $users_sign_info = get_e_signature($company_sid, $employee_sid, 'employee');
                $data['users_sign_info'] = $users_sign_info;
                $data['cn'] = $this->direct_deposit_model->getUserData($employee_sid, 'employee');
                $data['send_email_notification'] = 'yes';
                //
                $data['generalAssignments'] = $this->direct_deposit_model->getGeneralAssignments($company_sid, $employee_sid, 'employee');
                //
                $data['keyIndex'] = $key;

                $data['contactOptionsStatus'] = getEmergencyContactsOptionsStatus($company_sid);

                //
                $data['dependents_yes_text'] = $this->lang->line('dependents_yes_text');
                $data['dependents_no_text'] = $this->lang->line('dependents_no_text');

                if (checkIfAppIsEnabled(PAYROLL) && isEmployeeOnPayroll($employee_sid)) {
                    $this->load->model("v1/Payroll_model", "payroll_model");
                    // check and verify employee
                    $gustoEmployeeDetails = $this->payroll_model
                        ->getEmployeeDetailsForGusto(
                            $employee_sid
                        );
                    //
                    $companyGustoDetails = $this->payroll_model
                        ->getCompanyDetailsForGusto(
                            $company_sid,
                            ['status', 'added_historical_payrolls', 'is_ts_accepted']
                        );

                    // get the company onboard flow
                    $data['flow'] = gustoCall(
                        'getCompanyOnboardFlow',
                        $companyGustoDetails,
                        [
                            'flow_type' => "employee_earned_wage_access_enrollment",
                            "entity_type" => "Employee",
                            "entity_uuid" => $gustoEmployeeDetails['gusto_uuid']
                        ],
                        "POST"
                    )['url'];
                }

                $this->load->view('main/header', $data);
                $this->load->view('general_information/index.php');
                $this->load->view('main/footer');
            } else {
                //
                $this->load->model('2022/User_model', 'em');
                //
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'update_drivers_license_information':
                        $employee_sid = $this->input->post('employee_sid');
                        $license_type = $this->input->post('license_type');
                        $license_authority = $this->input->post('license_authority');
                        $license_class = $this->input->post('license_class');
                        $license_number = $this->input->post('license_number');
                        $license_issue_date = $this->input->post('license_issue_date');
                        $license_expiration_date = $this->input->post('license_expiration_date');
                        $license_indefinite = $this->input->post('license_indefinite');
                        $license_notes = $this->input->post('license_notes');
                        $license_file = upload_file_to_aws('license_file', 0, 'occupational_license_file', $employee_sid);
                        $data_to_serialize = array();
                        $data_to_serialize['license_type'] = $license_type;
                        $data_to_serialize['license_authority'] = $license_authority;
                        $data_to_serialize['license_class'] = $license_class;
                        $data_to_serialize['license_number'] = $license_number;
                        $data_to_serialize['license_issue_date'] = $license_issue_date;
                        $data_to_serialize['license_expiration_date'] = $license_expiration_date;
                        $data_to_serialize['license_indefinite'] = $license_indefinite;
                        $data_to_serialize['license_notes'] = $license_notes;

                        //
                        $this->em->handleGeneralDocumentChange(
                            'driversLicense',
                            $this->input->post(null, true),
                            $license_file,
                            $employee_sid,
                            $this->session->userdata('logged_in')['employer_detail']['sid']
                        );

                        $data_to_save = array();
                        $data_to_save['users_sid'] = $employee_sid;
                        $data_to_save['users_type'] = 'employee';
                        $data_to_save['license_type'] = 'drivers';
                        $data_to_save['license_details'] = serialize($data_to_serialize);

                        if ($license_file != 'error' && !empty($license_file)) {
                            $data_to_save['license_file'] = $license_file;
                        }

                        $licenseCheck = $this->general_info_model->check_user_license($employee_sid, 'employee', 'drivers');
                        $dateOfBirth['dob'] = (!empty($this->input->post('dob'))) ? date("Y-m-d", strtotime($this->input->post('dob'))) : null;

                        if (!empty($licenseCheck)) {
                            $license_id = $licenseCheck['sid'];
                            $this->general_info_model->update_license_info($license_id, $data_to_save, $dateOfBirth, $employee_sid);
                        } else {
                            $this->general_info_model->save_license_info($data_to_save, $dateOfBirth, $employee_sid);
                        }

                        $user_full_emp_app = $this->general_info_model->get_user_info($employee_sid);
                        $full_emp_form = array();
                        if (sizeof($user_full_emp_app)) {
                            $full_emp_form = !empty($user_full_emp_app[0]['full_employment_application']) && $user_full_emp_app[0]['full_employment_application'] != NULL ? unserialize($user_full_emp_app[0]['full_employment_application']) : array();
                        }
                        $full_emp_form['TextBoxDriversLicenseNumber'] = $license_number;
                        $full_emp_form['TextBoxDriversLicenseExpiration'] = $license_expiration_date;

                        $serial_form = array();
                        $serial_form['full_employment_application'] = serialize($full_emp_form);
                       
                        $this->general_info_model->update_user($employee_sid, $serial_form);

                        //
                        checkAndUpdateDD($employee_sid, 'employee', $company_sid, 'drivers_license');
                        $this->load->model('direct_deposit_model');
                        $this->load->model('hr_documents_management_model');
                        $userData = $this->direct_deposit_model->getUserData($employee_sid, 'employee');
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
                                $this->hr_documents_management_model->update_employee($employee_sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                            }
                            //
                        } else $doSend = true;


                        //
                        $cpArray = [];
                        $cpArray['company_sid'] = $company_sid;
                        $cpArray['user_sid'] = $employee_sid;
                        $cpArray['user_type'] = 'employee';
                        $cpArray['document_sid'] = 0;
                        $cpArray['document_type'] = 'driver_license';
                        //
                        checkAndInsertCompletedDocument($cpArray);

                        // Only send if dosend is true
                        if ($doSend == true) {

                            // Send document completion alert
                            broadcastAlert(
                                DOCUMENT_NOTIFICATION_TEMPLATE,
                                'general_information_status',
                                'driver_license',
                                $company_sid,
                                $data['session']['company_detail']['CompanyName'],
                                $data['session']['employer_detail']['first_name'],
                                $data['session']['employer_detail']['last_name'],
                                $employee_sid
                            );
                        }

                        $this->session->set_flashdata('message', '<strong>Success</strong> Driving License Information Updated!');
                        redirect('general_info', 'refresh');
                        break;
                    case 'update_occupational_license_information':
                        $employee_sid = $this->input->post('employee_sid');
                        $license_type = $this->input->post('license_type');
                        $license_authority = $this->input->post('license_authority');
                        $license_class = $this->input->post('license_class');
                        $license_number = $this->input->post('license_number');
                        $license_issue_date = $this->input->post('license_issue_date');
                        $license_expiration_date = $this->input->post('license_expiration_date');
                        $license_indefinite = $this->input->post('license_indefinite');
                        $license_notes = $this->input->post('license_notes');
                        $license_file = upload_file_to_aws('license_file', 0, 'occupational_license_file', $employee_sid);
                        $data_to_serialize = array();
                        $data_to_serialize['license_type'] = $license_type;
                        $data_to_serialize['license_authority'] = $license_authority;
                        $data_to_serialize['license_class'] = $license_class;
                        $data_to_serialize['license_number'] = $license_number;
                        $data_to_serialize['license_issue_date'] = $license_issue_date;
                        $data_to_serialize['license_expiration_date'] = $license_expiration_date;
                        $data_to_serialize['license_indefinite'] = $license_indefinite;
                        $data_to_serialize['license_notes'] = $license_notes;
                        $data_to_save = array();
                        $data_to_save['users_sid'] = $employee_sid;
                        $data_to_save['users_type'] = 'employee';
                        $data_to_save['license_type'] = 'occupational';
                        $data_to_save['license_details'] = serialize($data_to_serialize);

                        //
                        $this->em->handleGeneralDocumentChange(
                            'occupationalLicense',
                            $this->input->post(null, true),
                            $license_file,
                            $employee_sid,
                            $this->session->userdata('logged_in')['employer_detail']['sid']
                        );

                        if ($license_file != 'error' && !empty($license_file)) {
                            $data_to_save['license_file'] = $license_file;
                        }

                        $licenseCheck = $this->general_info_model->check_user_license($employee_sid, 'employee', 'occupational');
                        $dateOfBirth['dob'] = (!empty($this->input->post('dob'))) ? date("Y-m-d", strtotime($this->input->post('dob'))) : null;

                        if (!empty($licenseCheck)) {
                            $license_id = $licenseCheck['sid'];
                            $this->general_info_model->update_license_info($license_id, $data_to_save, $dateOfBirth, $employee_sid);
                        } else {
                            $this->general_info_model->save_license_info($data_to_save, $dateOfBirth, $employee_sid);
                        }

                        //
                        checkAndUpdateDD($employee_sid, 'employee', $company_sid, 'occupational_license');
                        $this->load->model('direct_deposit_model');
                        $this->load->model('hr_documents_management_model');
                        $userData = $this->direct_deposit_model->getUserData($employee_sid, 'employee');
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
                                $this->hr_documents_management_model->update_employee($employee_sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                            }
                            //
                        } else $doSend = true;

                        //
                        $cpArray = [];
                        $cpArray['company_sid'] = $company_sid;
                        $cpArray['user_sid'] = $employee_sid;
                        $cpArray['user_type'] = 'employee';
                        $cpArray['document_sid'] = 0;
                        $cpArray['document_type'] = 'occupational_license';
                        //
                        checkAndInsertCompletedDocument($cpArray);

                        // Only send if dosend is true
                        if ($doSend == true) {
                            // Send document completion alert
                            broadcastAlert(
                                DOCUMENT_NOTIFICATION_TEMPLATE,
                                'general_information_status',
                                'occupational_license',
                                $company_sid,
                                $data['session']['company_detail']['CompanyName'],
                                $data['session']['employer_detail']['first_name'],
                                $data['session']['employer_detail']['last_name'],
                                $employee_sid
                            );
                        }

                        $this->session->set_flashdata('message', '<strong>Success</strong> Occupational License Information Updated!');
                        redirect('general_info', 'refresh');
                        break;
                    case 'add_dependent':
                        $company_sid = $this->input->post('company_sid');
                        $employee_sid = $this->input->post('users_sid');
                        $first_name = $this->input->post('first_name');
                        $last_name = $this->input->post('last_name');
                        $address = $this->input->post('address');
                        $address_line = $this->input->post('address_line');
                        $Location_Country = $this->input->post('Location_Country');
                        $Location_State = $this->input->post('Location_State');
                        $city = $this->input->post('city');
                        $postal_code = $this->input->post('postal_code');
                        $phone = $this->input->post('phone');
                        $birth_date = $this->input->post('birth_date');
                        $relationship = $this->input->post('relationship');
                        $ssn = $this->input->post('ssn');
                        $gender = $this->input->post('gender');
                        $family_member = $this->input->post('family_member');
                        $data_to_serialize = array();
                        $data_to_serialize['first_name'] = $first_name;
                        $data_to_serialize['last_name'] = $last_name;
                        $data_to_serialize['address'] = $address;
                        $data_to_serialize['address_line'] = $address_line;
                        $data_to_serialize['Location_Country'] = $Location_Country;
                        $data_to_serialize['Location_State'] = $Location_State;
                        $data_to_serialize['city'] = $city;
                        $data_to_serialize['postal_code'] = $postal_code;
                        $data_to_serialize['phone'] = $phone;
                        $data_to_serialize['birth_date'] = $birth_date;
                        $data_to_serialize['relationship'] = $relationship;
                        $data_to_serialize['ssn'] = $ssn;
                        $data_to_serialize['gender'] = $gender;
                        $data_to_serialize['family_member'] = $family_member;
                        $data_to_save = array();
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['users_sid'] = $employee_sid;
                        $data_to_save['users_type'] = 'employee';
                        $data_to_save['dependant_details'] = serialize($data_to_serialize);

                        if (isDontHaveDependens($company_sid, $employee_sid, 'employee') > 0) {
                            isDontHaveDependensDelete($company_sid, $employee_sid, 'employee');
                        }

                        $this->general_info_model->insert_dependent_information($data_to_save);

                        //
                        checkAndUpdateDD($employee_sid, 'employee', $company_sid, 'dependents');
                        $this->load->model('direct_deposit_model');
                        $this->load->model('hr_documents_management_model');
                        $userData = $this->direct_deposit_model->getUserData($employee_sid, 'employee');
                        //
                        $doSend = false;

                        if (array_key_exists('document_sent_on', $userData)) {
                            //
                            $doSend = false;
                            //
                            if (empty($userData['document_sent_on']) || $userData['document_sent_on'] > date('Y-m-d 23:59:59', strtotime('now'))) {
                                $doSend = true;

                                //
                                $this->hr_documents_management_model->update_employee($employee_sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                            }
                            //
                        } else $doSend = true;

                        //
                        $cpArray = [];
                        $cpArray['company_sid'] = $company_sid;
                        $cpArray['user_sid'] = $employee_sid;
                        $cpArray['user_type'] = 'employee';
                        $cpArray['document_sid'] = 0;
                        $cpArray['document_type'] = 'dependent_details';
                        //
                        checkAndInsertCompletedDocument($cpArray);

                        // Only send if dosend is true
                        if ($doSend == true) {
                            // Send document completion alert
                            broadcastAlert(
                                DOCUMENT_NOTIFICATION_TEMPLATE,
                                'general_information_status',
                                'dependent_details',
                                $company_sid,
                                $data['session']['company_detail']['CompanyName'],
                                $data['session']['employer_detail']['first_name'],
                                $data['session']['employer_detail']['last_name'],
                                $employee_sid
                            );
                        }
                        $this->session->set_flashdata('message', '<strong>Success</strong> Dependent Added!');
                        redirect(base_url('general_info'), "location");
                        break;
                    case 'add_dependent_dont_have':
                        $company_sid = $this->input->post('company_sid');
                        $employee_sid = $this->input->post('users_sid');
                        $data_to_serialize = array();
                        $data_to_save = array();
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['users_sid'] = $employee_sid;
                        $data_to_save['users_type'] = 'employee';
                        $data_to_save['have_dependents'] = '0';

                        $data_to_save['dependant_details'] = serialize($data_to_serialize);

                        haveDependensDelete($company_sid, $employee_sid, 'employee');

                        if (isDontHaveDependens($company_sid, $employee_sid, 'employee') > 0) {
                            $this->session->set_flashdata('message', '<strong>Success</strong> Saved!');
                            redirect(base_url('general_info'), "location");
                            break;
                        }

                        $this->general_info_model->insert_dependent_information($data_to_save);

                        checkAndUpdateDD($employee_sid, 'employee', $company_sid, 'dependents');


                        $this->session->set_flashdata('message', '<strong>Success</strong> Saved!');
                        redirect(base_url('general_info'), "location");
                        break;
                    case 'delete_dependent':
                        //
                        $this->em->saveDifference([
                            'user_sid' => $employee_sid,
                            'employer_sid' => ($employee_sid == $this->session->userdata('logged_in')['employer_detail']['sid']
                                ? 0 : $this->session->userdata('logged_in')['employer_detail']['sid']),
                            'history_type' => 'dependent',
                            'profile_data' => json_encode(['action' => 'delete']),
                            'created_at' => date('Y-m-d H:i:s', strtotime('now'))
                        ]);
                        $dependent_sid = $this->input->post('dependent_sid');
                        $this->general_info_model->delete_dependent_information($dependent_sid);
                        $this->session->set_flashdata('message', '<strong>Success</strong> Dependent Deleted!');
                        redirect(base_url('general_info'), "location");
                        break;
                    case 'add_emergency_contact':
                        $company_sid = $this->input->post('company_sid');
                        $employee_sid = $this->input->post('users_sid');
                        $first_name = $this->input->post('first_name');
                        $last_name = $this->input->post('last_name');
                        $email = $this->input->post('email');
                        $PhoneNumber = $this->input->post('PhoneNumber');
                        $country = $this->input->post('contact_country');
                        $state = $this->input->post('contact_state');
                        $city = $this->input->post('city');
                        $Location_ZipCode = $this->input->post('Location_ZipCode');
                        $Location_Address = $this->input->post('Location_Address');
                        $Relationship = $this->input->post('Relationship');
                        $priority = $this->input->post('priority');
                        $data_to_insert = array();
                        $data_to_insert['users_sid'] = $employee_sid;
                        $data_to_insert['first_name'] = $first_name;
                        $data_to_insert['last_name'] = $last_name;
                        $data_to_insert['email'] = $email;
                        $data_to_insert['PhoneNumber'] = $PhoneNumber;
                        $data_to_insert['Location_Country'] = $country;
                        $data_to_insert['Location_State'] = $state;
                        $data_to_insert['Location_City'] = $city;
                        $data_to_insert['Location_ZipCode'] = $Location_ZipCode;
                        $data_to_insert['Location_Address'] = $Location_Address;
                        $data_to_insert['Relationship'] = $Relationship;
                        $data_to_insert['priority'] = $priority;
                        $data_to_insert['users_type'] = 'employee';
                        $this->general_info_model->insert_employee_emergency_contact($data_to_insert);
                        //
                        checkAndUpdateDD($employee_sid, 'employee', $company_sid, 'emergency_contacts');
                        $this->load->model('direct_deposit_model');
                        $this->load->model('hr_documents_management_model');
                        $userData = $this->direct_deposit_model->getUserData($employee_sid, 'employee');
                        //
                        $doSend = true;
                        //
                        if (array_key_exists('document_sent_on', $userData)) {
                            //
                            $doSend = false;
                            //
                            if (empty($userData['document_sent_on']) || $userData['document_sent_on'] > date('Y-m-d 23:59:59', strtotime('now'))) {
                                $doSend = true;
                                //
                                $this->hr_documents_management_model->update_employee($employee_sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                            }
                            //
                        }

                        //
                        $cpArray = [];
                        $cpArray['company_sid'] = $company_sid;
                        $cpArray['user_sid'] = $employee_sid;
                        $cpArray['user_type'] = 'employee';
                        $cpArray['document_sid'] = 0;
                        $cpArray['document_type'] = 'emergency_contacts';
                        //
                        checkAndInsertCompletedDocument($cpArray);

                        // Only send if dosend is true
                        if ($doSend == true) {
                            // Send document completion alert
                            broadcastAlert(
                                DOCUMENT_NOTIFICATION_TEMPLATE,
                                'general_information_status',
                                'emergency_contacts',
                                $company_sid,
                                $data['session']['company_detail']['CompanyName'],
                                $data['session']['employer_detail']['first_name'],
                                $data['session']['employer_detail']['last_name'],
                                $employee_sid
                            );
                        }
                        $this->session->set_flashdata('message', '<strong>Success</strong> Emergency Contact Successfully Added!');
                        redirect(base_url('general_info'), "location");
                        break;
                    case 'delete_emergency_contact':
                        $contact_sid = $this->input->post('contact_sid');
                        $this->general_info_model->delete_emergency_contact($contact_sid);
                        // Send document completion alert
                        // broadcastAlert(
                        //     DOCUMENT_NOTIFICATION_TEMPLATE,
                        //     'general_information_status',
                        //     'emergency_contact',
                        //     $company_sid,
                        //     $data['session']['company_detail']['CompanyName'],
                        //     $data['session']['employer_detail']['first_name'],
                        //     $data['session']['employer_detail']['last_name'],
                        //     $employee_sid
                        // );

                        //
                        $this->em->saveDifference([
                            'user_sid' => $employee_sid,
                            'employer_sid' => ($employee_sid == $this->session->userdata('logged_in')['employer_detail']['sid']
                                ? 0 : $this->session->userdata('logged_in')['employer_detail']['sid']),
                            'history_type' => 'emergencyContact',
                            'profile_data' => json_encode(['action' => 'delete']),
                            'created_at' => date('Y-m-d H:i:s', strtotime('now'))
                        ]);
                        $this->session->set_flashdata('message', '<strong>Success</strong> Emergency Contact Successfully Deleted!');
                        redirect(base_url('general_info'), "location");
                        break;
                    case 'update_bank_details':
                        $company_sid = $this->input->post('company_sid');
                        $users_sid = $this->input->post('users_sid');
                        $account_title = $this->input->post('account_title');
                        $routing_transaction_number = $this->input->post('routing_transaction_number');
                        $account_number = $this->input->post('account_number');
                        $financial_institution_name = $this->input->post('financial_institution_name');
                        $account_type = $this->input->post('account_type');
                        $enable_learbing_center_flag = $this->input->post('enable_learbing_center_flag');
                        $data_to_save = array();
                        $data_to_save['users_type'] = 'employee';
                        $data_to_save['users_sid'] = $users_sid;
                        $data_to_save['account_title'] = $account_title;
                        $data_to_save['routing_transaction_number'] = $routing_transaction_number;
                        $data_to_save['account_number'] = $account_number;
                        $data_to_save['financial_institution_name'] = $financial_institution_name;
                        $data_to_save['account_type'] = $account_type;
                        $data_to_save['company_sid'] = $company_sid;
                        $pictures = upload_file_to_aws('picture', $company_sid, 'picture', $users_sid, AWS_S3_BUCKET_NAME);

                        if (!empty($pictures) && $pictures != 'error') {
                            $data_to_save['voided_cheque'] = $pictures;
                        }

                        $this->general_info_model->save_bank_details('employee', $users_sid, $data_to_save);

                        //
                        checkAndUpdateDD($employee_sid, 'employee', $company_sid, 'direct_deposit');
                        $this->load->model('direct_deposit_model');
                        $this->load->model('hr_documents_management_model');
                        $userData = $this->direct_deposit_model->getUserData($employee_sid, 'employee');
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
                                $this->hr_documents_management_model->update_employee($employee_sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                            }
                            //
                        } else $doSend = true;

                        //
                        $cpArray = [];
                        $cpArray['company_sid'] = $company_sid;
                        $cpArray['user_sid'] = $employee_sid;
                        $cpArray['user_type'] = 'employee';
                        $cpArray['document_sid'] = 0;
                        $cpArray['document_type'] = 'direct_deposit_information';
                        //
                        checkAndInsertCompletedDocument($cpArray);

                        // Only send if dosend is true
                        if ($doSend == true) {
                            // Send document completion alert
                            broadcastAlert(
                                DOCUMENT_NOTIFICATION_TEMPLATE,
                                'general_information_status',
                                'direct_deposit_information',
                                $company_sid,
                                $data['session']['company_detail']['CompanyName'],
                                $data['session']['employer_detail']['first_name'],
                                $data['session']['employer_detail']['last_name'],
                                $employee_sid
                            );
                        }
                        $this->session->set_flashdata('message', '<strong>Success</strong> Direct Deposit Information Updated!');
                        redirect(base_url('general_info'), "location");
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    //This function get dependant contact sid and collect
    //data against that specific dependant contact sid and 
    //populate in 'edit_dependents_information' view and after
    //render dependant information by employee then update specific 
    //dependant contact information only.
    public function edit_dependant_information($dependant_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data['session']['company_detail']['sid'];
            $employee_sid = $data['session']['employer_detail']['sid'];

            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            $employer = $data['session']['employer_detail'];
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            $data['employee'] = $data['session']['employer_detail'];
            $data['company_sid'] = $company_sid;
            $data['title'] = 'Edit Dependant Information';


            $data['sid'] = $dependant_sid;
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $dependantData = $this->general_info_model->dependant_details($dependant_sid);

            if (!empty($dependantData)) {
                $dependant_details = $dependantData;
            } else {
                if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                } else {
                    redirect(base_url('general_info'), "refresh");
                }
            }

            $dependents = $this->general_info_model->get_dependant_information('employee', $employee_sid);
            $data['dependents'] = $dependents;
            $data['onboarding_flag'] = true;
            $dependant_data = unserialize($dependant_details['dependant_details']);
            $dependant_data['sid'] = $dependant_details['sid'];
            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data['dependant_info'] = $dependant_data;

            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('phone', 'Phone Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('relationship', 'Relationship', 'trim|xss_clean|required');

            if ($this->form_validation->run() === FALSE) {

                if (validation_errors() != false) {
                    $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                }

                $this->load->view('main/header', $data);
                $this->load->view('general_information/edit_dependents_information.php');
                $this->load->view('main/footer');
            } else {
                //
                $this->load->model('2022/User_model', 'em');
                //
                $this->em->handleGeneralDocumentChange(
                    'dependent',
                    $this->input->post(null, true),
                    null,
                    $employee_sid,
                    $this->session->userdata('logged_in')['employer_detail']['sid']
                );
                //
                $formpost = $this->input->post(null, true);
                $dependantDataToSave['dependant_details'] = serialize($formpost);
                $this->general_info_model->update_dependant_info($dependant_sid, $dependantDataToSave);
                //
                checkAndUpdateDD($employee_sid, 'employee', $company_sid, 'dependents');
                $this->load->model('direct_deposit_model');
                $this->load->model('hr_documents_management_model');
                $userData = $this->direct_deposit_model->getUserData($employee_sid, 'employee');
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
                        $this->hr_documents_management_model->update_employee($employee_sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
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
                        $company_sid,
                        $data['session']['company_detail']['CompanyName'],
                        $data['session']['employer_detail']['first_name'],
                        $data['session']['employer_detail']['last_name'],
                        $employee_sid
                    );
                }
                $this->session->set_flashdata('message', '<b>Success:</b> Dependent info updated successfully');
                redirect(base_url('general_info'), "location");
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    //This function get emergency contact sid and collect
    //data against that specific emergency contact sid and 
    //populate in 'edit_emergency_contact' view and after
    //render information by employee then update specific 
    //emergency contact only. 
    public function edit_emergency_contacts($contact_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid = $data['session']['company_detail']['sid'];
            $employee_sid = $data['session']['employer_detail']['sid'];

            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            $employer = $data['session']['employer_detail'];
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            $data['employee'] = $data['session']['employer_detail'];
            $data['company_sid'] = $company_sid;
            $data['title'] = 'Edit Emergency Contact';


            $data['sid'] = $contact_sid;
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data_states_encode = htmlentities(json_encode($data_states));
            $data['states'] = $data_states_encode;
            $emergency_contacts = $this->general_info_model->emergency_contacts_details($contact_sid);
            $data['emergency_contacts'] = $emergency_contacts;
            $data['company_sid'] = $company_sid;
            $data['employee_sid'] = $employee_sid;
            $data['onboarding_flag'] = true;
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('email', 'email', 'trim|xss_clean|required|valid_email');
            // $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('Relationship', 'Relationship', 'trim|xss_clean|required');
            $this->form_validation->set_rules('priority', 'Priority', 'trim|xss_clean|required');

            if ($this->form_validation->run() === FALSE) {

                if (validation_errors() != false) {
                    $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                }

                $data['contactOptionsStatus'] = getEmergencyContactsOptionsStatus($company_sid);


                $this->load->view('main/header', $data);
                $this->load->view('general_information/edit_emergency_contact.php');
                $this->load->view('main/footer');
            } else {
                //
                $this->load->model('2022/User_model', 'em');
                //
                $this->em->handleGeneralDocumentChange(
                    'emergencyContact',
                    $this->input->post(null, true),
                    null,
                    $employee_sid,
                    $this->session->userdata('logged_in')['employer_detail']['sid']
                );

                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');
                $email = $this->input->post('email');
                $country = $this->input->post('Location_Country');
                $state = $this->input->post('Location_State');
                $city = $this->input->post('Location_City');
                $zipCode = $this->input->post('Location_ZipCode');
                $address = $this->input->post('Location_Address');
                $phoneNumber = $this->input->post('PhoneNumber');
                $relationship = $this->input->post('Relationship');
                $priority = $this->input->post('priority');
                $data_to_update = array();
                $data_to_update['first_name'] = $first_name;
                $data_to_update['last_name'] = $last_name;
                $data_to_update['email'] = $email;
                $data_to_update['Location_Country'] = $country;
                $data_to_update['Location_State'] = $state;
                $data_to_update['Location_City'] = $city;
                $data_to_update['Location_ZipCode'] = $zipCode;
                $data_to_update['Location_Address'] = $address;
                $data_to_update['PhoneNumber'] = $phoneNumber;
                $data_to_update['Relationship'] = $relationship;
                $data_to_update['priority'] = $priority;

                $sid = $this->input->post('sid');
                $result = $this->general_info_model->edit_emergency_contacts($sid, $data_to_update);

                //
                checkAndUpdateDD($employee_sid, 'employee', $company_sid, 'emergency_contacts');
                $this->load->model('direct_deposit_model');
                $this->load->model('hr_documents_management_model');
                $userData = $this->direct_deposit_model->getUserData($employee_sid, 'employee');
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
                        $this->hr_documents_management_model->update_employee($employee_sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                    }
                    //
                } else $doSend = true;

                // Only send if dosend is true
                if ($doSend == true) {

                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_TEMPLATE,
                        'general_information_status',
                        'emergency_contacts',
                        $company_sid,
                        $data['session']['company_detail']['CompanyName'],
                        $data['session']['employer_detail']['first_name'],
                        $data['session']['employer_detail']['last_name'],
                        $employee_sid
                    );
                }

                redirect(base_url('general_info'), "location");
            }
        } else {
            redirect('login', "refresh");
        }
    }

    //This function get assign equipment sid and collect
    //data against that specific equipment sid and populate
    //in 'equipment_info_detail' view. If employee want to
    //acknowledge against that specific equipment then the
    //below function update acknowledgement status information. 
    public function view_equipment_information($equipment_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid = $data['session']['company_detail']['sid'];
            $employee_sid = $data['session']['employer_detail']['sid'];

            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            $employer = $data['session']['employer_detail'];
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            $data['employee'] = $data['session']['employer_detail'];
            $data['company_sid'] = $company_sid;
            $data['title'] = 'View Equipments Information';


            $data['sid'] = $equipment_sid;

            $equipment_info = $this->general_info_model->equipment_info_details($equipment_sid);
            $data['equipment_info'] = $equipment_info;
            $data['company_sid'] = $company_sid;
            $data['employee_sid'] = $employee_sid;
            $data['onboarding_flag'] = true;


            if ($this->form_validation->run() === FALSE) {

                if (validation_errors() != false) {
                    $this->session->set_flashdata('message', '<b>Failed: </b>Please check the form for errors and try again!');
                }

                $equipment_type = isset($equipment_info["equipment_type"]) ? $equipment_info["equipment_type"] : '';


                if ($equipment_info['acknowledgement_flag'] == 1) {
                    $acknowledgement_status = '<strong class="text-success">Equipment Status:</strong> You have successfully Acknowledged this ' . ucwords($equipment_type);
                    $acknowledgement_button_txt = 'Acknowledged';
                    $acknowledgement_button_css = 'btn-warning';
                    $perform_action = 'remove_document';
                } else {
                    $acknowledgement_status = '<strong class="text-danger">Equipment Status:</strong> You have not yet acknowledged this ' . ucwords($equipment_type);
                    $acknowledgement_button_txt = 'I Acknowledge';
                    $acknowledgement_button_css = 'btn-success';
                    $perform_action = 'acknowledge_document';
                }

                $acknowledgment_action_title = '<b>Equipment Action: Acknowledgement Required!</b>';
                $acknowledgment_action_desc = '<b>Acknowledge the receipt of this ' . ucwords($equipment_type) . '</b>';

                $data['acknowledgment_action_title'] = $acknowledgment_action_title;
                $data['acknowledgment_action_desc'] = $acknowledgment_action_desc;
                $data['acknowledgement_button_txt'] = $acknowledgement_button_txt;
                $data['acknowledgement_status'] = $acknowledgement_status;
                $data['acknowledgement_button_css'] = $acknowledgement_button_css;
                $data['perform_action'] = $perform_action;

                $this->load->view('main/header', $data);
                $this->load->view('general_information/equipment_info_detail.php');
                $this->load->view('main/footer');
            } else {

                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'acknowledge_document':
                        $equipment_sid = $this->input->post('equipment_sid');
                        $user_sid = $this->input->post('user_sid');
                        $user_type = $this->input->post('user_type');
                        $acknowledgement_flag = 1;
                        $acknowledgement_notes = $this->input->post('acknowledgement_notes');
                        $acknowledgement_datetime = date('Y-m-d H:i:s');
                        $acknowledge_by_ip = getUserIP();

                        $data_to_update = $arrayName = array();
                        $data_to_update['acknowledgement_flag'] = $acknowledgement_flag;
                        $data_to_update['acknowledgement_notes'] = $acknowledgement_notes;
                        $data_to_update['acknowledgement_datetime'] = $acknowledgement_datetime;
                        $data_to_update['acknowledge_by_ip'] = $acknowledge_by_ip;

                        $this->general_info_model->update_equipment_acknomledgement($data_to_update, $equipment_sid, $user_sid, $user_type);

                        // Send document completion alert
                        // broadcastAlert(
                        //     DOCUMENT_NOTIFICATION_ACTION_TEMPLATE,
                        //     'general_information_status',
                        //     'equipment_info_acknowledged',
                        //     $company_sid,
                        //     $data['session']['company_detail']['CompanyName'],
                        //     $data['session']['employer_detail']['first_name'],
                        //     $data['session']['employer_detail']['last_name'],
                        //     $employee_sid
                        // );


                        $this->session->set_flashdata('message', '<strong>Success</strong> Occupational License Information Updated!');
                        redirect(base_url('general_info/view_equipment_information/' . $equipment_sid), 'refresh');
                        break;
                    case 'remove_document':
                        $equipment_sid = $this->input->post('equipment_sid');
                        $user_sid = $this->input->post('user_sid');
                        $user_type = $this->input->post('user_type');
                        $acknowledgement_notes = $this->input->post('acknowledgement_notes');
                        die('Remove Equipment');

                        $this->session->set_flashdata('message', '<strong>Success</strong> Driving License Information Updated!');
                        redirect('general_info', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }
}
