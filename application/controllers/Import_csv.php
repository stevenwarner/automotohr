<?php defined('BASEPATH') or exit('No direct script access allowed');

class Import_csv extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('path');
        $this->load->library('parse_csv');
        $this->load->model('import_csv_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = 'Import Employees From CSV File';
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'import_csv'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $data['employerData'] = $this->import_csv_model->getEmployerDetail($employer_id);

            if (isset($_POST['action']) && $_POST['action'] == 'upload_file') {
                $uploadPath = realpath(APPPATH . '../assets/import_csv/');
                $csvFile = uploadFile($company_name, $uploadPath, 'userfile', 500000, array('csv'));
                $myParsedFile = $this->parse_csv->ParseFile($csvFile);

                if (!empty($myParsedFile)) {
                    $records = $myParsedFile['data'];
                    $titles = $myParsedFile['titles'];
                }

                $firstNameTitles = array('first_name', 'firstName', 'first-name', 'FirstName', 'FName', 'First Name'); // for first_name
                $lastNameTitles = array('last_name', 'lastName', 'last-name', 'LastName', 'LName', 'last name'); //for last_name
                $emailAddressTitles = array('email', 'email_address', 'emailAddress', 'email-address', 'EmailAddress', 'Email', 'E-Mail', 'e-mail');
                $phoneNumberTitles = array('phone_number', 'phoneNumber', 'phone-number', 'PhoneNumber', 'Phone', 'Contact Number', 'Contact', 'Employee Telephone Number', 'Telephone Number', 'PrimaryNumber'); //for PhoneNumber
                $addressTitles = array('address', 'Address', 'Street Address', 'Employee Address Line 1', 'Address Line 1', 'Employee Address'); // for Location_Address
                $cityTitles = array('city', 'City', 'Employee City'); // for Location_city
                $zipCodeTitles = array('zip_code', 'zipCode', 'zip-code', 'ZipCode', 'zip', 'Employee ZIP'); // for Location_ZipCode
                $state = array('state, State', 'Employee State');
                $country = array('country, Country', 'Employee Country');
                $profile_picture = array('profile', 'profile picture', 'profile_picture', 'profile-picture', 'profilepicture', 'profile picture url', 'profile_picture_url', 'profile-picture-url', 'profilepictureurl');
                $access_level = array('Access Level', 'AccessLevel', 'Access_level', 'access-level');
                $jobTitleTitles = array('job_title', 'jobTitle', 'job-title', 'JobTitle', 'Job Title', 'job title', 'job'); // for Job_title
                $ssn = array('ssn', 'social security number', 'social-security-number', 'social_security_number', 'social_security', 'social-security', 'social security', 'Social Security'); // for social security
                $empid = array('empid', 'emp-id', 'emp_id', 'employee_number', 'employee number', 'employee-number', 'Employee Number'); // for employee Id
                $allowedTitles = array_merge($empid, $ssn, $firstNameTitles, $lastNameTitles, $emailAddressTitles, $phoneNumberTitles, $addressTitles, $cityTitles, $zipCodeTitles, $state, $country, $profile_picture, $access_level, $jobTitleTitles);
                $correctTitles = array();
                $correctTitleslowercase = array();
                $title_key_pair = array();
                $count = 0;
                $already_exists = 0;

                /**
                 * Clean entities from string
                 * Added on: 13-05-2019
                 * 
                 * @param $e String
                 * @param $lower Bool
                 *
                 * @return String
                 */
                if (!function_exists('strip_entities_from_code')) {
                    function strip_entities_from_code($e, $lower = true)
                    {
                        return
                            $lower ?
                            trim(preg_replace('/[^a-zA-Z]/', ' ',  utf8_decode(strtolower($e))))
                            :
                            trim(preg_replace('/[^a-zA-Z]/', ' ',  utf8_decode($e)));
                    }
                }

                foreach ($titles as $title) {
                    $lower_title = strip_entities_from_code($title);
                    $lower_allowedTitles = array_map('strip_entities_from_code', $allowedTitles);

                    if (in_array($lower_title, $lower_allowedTitles)) {
                        $clean_title = strip_entities_from_code($title, false);
                        $correctTitles[] = $clean_title;
                        $correctTitleslowercase[] = strip_entities_from_code($title, true);
                        $title_key_pair[$clean_title] = $title;
                    }
                }

                // if (    in_array('email', $correctTitleslowercase) ||
                //        in_array('email_address', $correctTitleslowercase) ||
                //        in_array('emailaddress', $correctTitleslowercase) ||
                //        in_array('email-address', $correctTitleslowercase) ||
                //        in_array('e-mail', $correctTitleslowercase) ||
                //        in_array('e mail', $correctTitleslowercase)
                // ) {
                foreach ($records as $record) {
                    $recordToInsert = array();
                    $insertRecord = true;

                    foreach ($correctTitles as $title) {
                        $original_title = $title_key_pair[$title];

                        if ((strpos(strtolower($title), 'first') !== false) || (strpos(strtolower($title), 'fname') !== false)) {
                            $firstName = ucwords(strtolower($record[$original_title]));
                            $recordToInsert['first_name'] = $firstName;
                        }

                        if ((strpos(strtolower($title), 'last') !== false) || (strpos(strtolower($title), 'lname') !== false)) {
                            $lastName = ucwords(strtolower($record[$original_title]));
                            $recordToInsert['last_name'] = $lastName;
                        }

                        if ((strpos(strtolower($title), 'email') !== false) || (strpos(strtolower($title), 'e-mail') !== false) || (strpos(strtolower($title), 'e_mail') !== false) || (strpos(strtolower($title), 'e mail') !== false)) {
                            $email = strtolower($record[$original_title]);
                            $recordToInsert['email'] = $email;
                            $to = $email;

                            if ($this->import_csv_model->CheckIfEmployeeExists($company_id, $email)) {
                                $insertRecord = false;
                                $already_exists++;
                            }
                        }

                        if ((strpos(strtolower($title), 'phone') !== false) || (strpos(strtolower($title), 'number') !== false && strpos(strtolower($title), 'security') === false && strpos(strtolower($title), 'employee') === false && strpos(strtolower($title), 'social') === false)) {
                            $recordToInsert['PhoneNumber'] = $record[$original_title];
                            $phoneNumber = $record[$original_title];
                        }

                        if ((strpos(strtolower($title), 'address') !== false) || (strpos(strtolower($title), 'Street Address') !== false)) {
                            $recordToInsert['Location_Address'] = $record[$original_title];
                        }

                        if (strpos(strtolower($title), 'city') !== false) {
                            $recordToInsert['Location_City'] = $record[$original_title];
                        }

                        if ((strpos(strtolower($title), 'zip') !== false) || (strpos(strtolower($title), 'zipcode') !== false)) {
                            $recordToInsert['Location_ZipCode'] = $record[$original_title];
                        }

                        if (strpos(strtolower($title), 'state') !== false) {
                            $state = $record[$original_title];

                            if ($state != null || $state != '') {
                                $state_info = $this->import_csv_model->get_state_and_country_id($state);
                            }

                            if (!empty($state_info)) {
                                $recordToInsert['Location_State'] = $state_info['sid'];
                                $recordToInsert['Location_Country'] = $state_info['country_sid'];
                            }
                        }

                        if (strpos(strtolower($title), 'country') !== false) {
                            $country = $record[$original_title];

                            if (strtolower($country) == 'United States') {
                                $recordToInsert['Location_Country'] = 227;
                            }

                            if (strtolower($country) == 'Canada') {
                                $recordToInsert['Location_Country'] = 38;
                            }
                        }

                        if ((strpos(strtolower($title), 'profile') !== false) || (strpos(strtolower($title), 'picture') !== false)) {
                            $profile_pic_url = $record[$original_title];
                            if ($profile_pic_url != NULL || $profile_pic_url != '') {
                                $recordToInsert['profile_picture'] = $this->import_csv_model->verify_url_data($profile_pic_url);
                            }
                        }

                        if ((strpos(strtolower($title), 'access level') !== false) || (strpos(strtolower($title), 'access_level') !== false) || (strpos(strtolower($title), 'access-level') !== false)) {
                            $access_level = strtolower($record[$title]);

                            if ($access_level != '' || $access_level != null) {
                                switch ($access_level) {
                                    case 'admin':
                                        $recordToInsert['access_level'] = 'Admin';
                                        break;
                                    case 'hiring manager':
                                        $recordToInsert['access_level'] = 'Hiring Manager';
                                        break;
                                    case 'executive':
                                        $recordToInsert['access_level'] = 'Executive';
                                        break;
                                    case 'recruiter':
                                        $recordToInsert['access_level'] = 'Recruiter';
                                        break;
                                    case 'manager':
                                        $recordToInsert['access_level'] = 'Manager';
                                        break;
                                    case 'employee':
                                        $recordToInsert['access_level'] = 'Employee';
                                        break;
                                    case 'user':
                                        $recordToInsert['access_level'] = 'Employee';
                                        break;
                                    default:
                                        $recordToInsert['access_level'] = 'Employee';
                                        break;
                                }
                            } else {
                                $recordToInsert['access_level'] = 'Employee';
                            }
                        }

                        if ((strpos(strtolower($title), 'job') !== false) || (strpos(strtolower($title), 'jobTitle') !== false)) {
                            $recordToInsert['job_title'] = $record[$original_title];
                            $jobTitle = $record[$original_title];
                        }

                        if (strpos(strtolower($title), 'security') !== false || strpos(strtolower($title), 'social') !== false || strpos(strtolower($title), 'ssn') !== false) {
                            $recordToInsert['ssn'] = $record[$original_title];
                            $ssn = $record[$original_title];
                        }

                        if (strpos(strtolower($title), 'empId') !== false || strpos(strtolower($title), 'employee_number') !== false || strpos(strtolower($title), 'employee_id') !== false) {
                            $recordToInsert['employee_number'] = $record[$original_title];
                            $emp_no = $record[$original_title];
                        }
                    }

                    if (!isset($recordToInsert['access_level'])) {
                        $recordToInsert['access_level'] = 'Employee';
                    }

                    if (!isset($recordToInsert['first_name'])) {
                        $first_name = NULL;
                    } else {
                        $first_name = $recordToInsert['first_name'];
                    }

                    if (!isset($recordToInsert['last_name'])) {
                        $last_name = NULL;
                    } else {
                        $last_name = $recordToInsert['last_name'];
                    }

                    if (!isset($recordToInsert['email']) || empty($recordToInsert['email'])) {
                        $existed_other = $this->import_csv_model->CheckIfEmployeeExistsWithOthers($company_id, $emp_no, $ssn, $phoneNumber);
                        if (!$existed_other) {
                            $insertRecord = false;
                            $already_exists++;
                        }
                        $email = NULL;
                    } else {
                        $email = $recordToInsert['email'];
                    }

                    $recordToInsert['username'] = $this->import_csv_model->generate_username($first_name, $last_name, $email);
                    $verificationKey = random_key() . '_csvImport';
                    $recordToInsert['parent_sid'] = $company_id;
                    $recordToInsert['registration_date'] = date("Y-m-d H:i:s");
                    $recordToInsert['active'] = 0;
                    $recordToInsert['verification_key'] = $verificationKey;
                    $recordToInsert['video_type'] = 'no_video';

                    if ($insertRecord === true) { //Insert User Record in Users Table
                        $employer_sid = $this->import_csv_model->InsertNewUser($recordToInsert);
                        $company_name = $data['session']['company_detail']['CompanyName']; //sending email to user
                        $count++;
                        // $emailTemplateData = get_email_template(CSV_EMPLOYER_IMPORT);
                        // $emailTemplateBody = convert_email_template($emailTemplateData['text'], $employer_sid);
                        // $emailTemplateBody = str_replace('{{company_name}}', $company_name, $emailTemplateBody);
                        // $subject = $emailTemplateData['subject'];

                        // $emailData = array( 'date' => date('Y-m-d H:i:s'),
                        //                     'subject' => $subject,
                        //                     'email' => $to,
                        //                     'message' => $emailTemplateBody,
                        //                     'username' => $data['session']['employer_detail']['sid']);

                        // if (base_url() == 'http://localhost/ahr/') {
                        //     save_email_log_common($emailData);
                        //     $count++;
                        // } else { //Send Email
                        //     save_email_log_common($emailData);
                        //     // sendMail($from, $to, $subject, $emailTemplateBody, $company_name); // outgoing emails are disabled
                        //     // sendMail(FROM_EMAIL_NOTIFICATIONS, $email, $subject, $messageBody, STORE_NAME, FROM_EMAIL_NOTIFICATIONS);
                        //     $count++;
                        // }
                    }
                }
                // }

                if ($count > 0) {
                    $this->session->set_flashdata('message', '<b>Success:</b> You have successfuly imported ' . $count . ' employees.');
                } else if ($already_exists > 0) {
                    $this->session->set_flashdata('message', '<b>Error:</b> (' . $already_exists . ') employee(s) already exist in the system.');
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> System could not detect Employee Email address, Please make sure that the heading title for it is "Email"');
                }

                redirect('employee_management?employee_type=offline&keyword=', 'refresh');
            }

            $this->load->view('main/header', $data);
            $this->load->view('import_csv/index');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }


    /**
     * Handle all ajax requests
     * Created on: 16-08-2019
     *
     * @accepts POST
     *
     * @uses resp
     *
     * @return JSON
     */
    function handler()
    {
        if (!$this->session->userdata('logged_in')) exit(0);
        $session = $this->session->userdata('logged_in');
        $companyId = $session['company_detail']['sid'];
        $companyTZ = $session['company_detail']['timezone'] != null || $session['company_detail']['timezone'] != '' ? $session['company_detail']['timezone'] : NULL;
        $employerId = $session['employer_detail']['sid'];
        $companyName = $session['company_detail']['CompanyName'];
        // Set default response array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request made.';
        // Check for a valid AJAX request
        if (!$this->input->is_ajax_request()) exit(0);
        //
        $formpost = $this->input->post(NULL, TRUE);
        //
        switch ($formpost['action']) {
            case 'add_employees':
                set_time_limit(0);
                // Default array

                $failCount = $insertCount = $existCount = 0;
                //
                foreach ($formpost['employees'] as $k0 => $v0) {
                    // Set default insert array
                    $insertArray = array();
                    $insertDriverinfo = array();
                    $licenseDetails = array();

                    if (count($v0) <= 1) {
                        continue;
                    }

                    //
                    if (isset($v0['license_type']) && !empty($v0['license_type'])) {
                        $licenseDetails['license_type'] = $v0['license_type'];
                        unset($v0['license_type']);
                    }
                    if (isset($v0['license_authority']) && !empty($v0['license_authority'])) {
                        $licenseDetails['license_authority'] = $v0['license_authority'];
                        unset($v0['license_authority']);
                    }
                    if (isset($v0['license_class']) && !empty($v0['license_class'])) {
                        $licenseDetails['license_class'] = $v0['license_class'];
                        unset($v0['license_class']);
                    }
                    if (isset($v0['license_number']) && !empty($v0['license_number'])) {
                        $licenseDetails['license_number'] = $v0['license_number'];
                        unset($v0['license_number']);
                    }
                    if (isset($v0['license_issue_date']) && !empty($v0['license_issue_date']) && $v0['license_issue_date'] != NULL && preg_match('/[0-9]{2}/', $v0['license_issue_date'])) {
                        $formatForDate = 'm/d/Y';
                        //
                        $t = explode('/', $v0['license_issue_date']);
                        if (strlen(end($t)) == 2) {
                            $formatForDate = 'm/d/y';
                        }

                        $licenseDetails['license_issue_date'] = formatDateToDB($v0['license_issue_date'], $formatForDate);
                        unset($v0['license_issue_date']);
                    }


                    if (isset($v0['license_expiration_date']) && !empty($v0['license_expiration_date']) && $v0['license_expiration_date'] != NULL && preg_match('/[0-9]{2}/', $v0['license_expiration_date'])) {

                        $formatForDate = 'm/d/Y';
                        //
                        $t = explode('/', $v0['license_expiration_date']);
                        if (strlen(end($t)) == 2) {
                            $formatForDate = 'm/d/y';
                        }
                        $licenseDetails['license_expiration_date'] = formatDateToDB($v0['license_expiration_date'], $formatForDate);
                        unset($v0['license_expiration_date']);
                    }

                    if (isset($v0['license_notes']) && !empty($v0['license_notes'])) {
                        $licenseDetails['license_notes'] = $v0['license_notes'];
                        unset($v0['license_notes']);
                    }


                    //
                    if (!isset($v0['status']) && isset($v0['termination_date']) && !empty($v0['termination_date'])) {
                        $v0['status'] = "terminated";
                    }
                    // Clean
                    $insertArray['email'] = isset($v0['email']) ? trim(strtolower($v0['email'])) : NULL;
                    //
                    if (empty($insertArray['email'])) {
                        if (isset($v0['secondary_email']) && !empty($v0['secondary_email'])) {
                            $insertArray['email'] = trim(strtolower($v0['secondary_email']));
                        } else if (isset($v0['other_email']) && !empty($v0['other_email'])) {
                            $insertArray['email'] = trim(strtolower($v0['other_email']));
                        }
                    }
                    //
                    $ssn = isset($v0['ssn']) && !empty($v0['ssn']) && $v0['ssn'] != NULL ? $v0['ssn'] : '';
                    $emp_no = isset($v0['employee_number']) && !empty($v0['employee_number']) && $v0['employee_number'] != NULL ? $v0['employee_number'] : '';
                    $phoneNumber = isset($v0['PhoneNumber']) && $v0['PhoneNumber'] != '' && $v0['PhoneNumber'] != null ? trim(preg_replace('/[^0-9+]/', '', $v0['PhoneNumber'])) : '';
                    //
                    $checkByColumn = 'email';
                    $checkByValue = $insertArray['email'];
                    // Employee exists will be checked against 
                    // email, ssn, employee number, and phone number
                    if (empty($insertArray['email'])) {
                        //
                        if (!empty($ssn)) {
                            //
                            $checkByColumn = 'ssn';
                            $checkByValue = $ssn;
                        } else if (!empty($emp_no)) {
                            //
                            $checkByColumn = 'employee_number';
                            $checkByValue = $emp_no;
                        } else if (!empty($phoneNumber)) {
                            //
                            $checkByColumn = 'PhoneNumber';
                            $checkByValue = $phoneNumber;
                        }
                    }
                    // lets check if employee already exists
                    $employeeArray = $this->import_csv_model->GetEmployee($companyId, $checkByColumn, $checkByValue);
                    // In case of exist update the employee
                    // only add the misisng information

                    if (!empty($employeeArray)) {
                        $employeeOldSid = $this->updateUser($employeeArray, $v0);

                        // Update Driver Information
                        $insertDriverinfo['license_details'] =  serialize($licenseDetails);
                        $this->import_csv_model->updatLicenseDetails($insertDriverinfo, $employeeOldSid);

                        $existCount++;
                        continue;
                    }
                    // Set default access level
                    $insertArray['access_level'] = 'Employee';
                    // Check for access level columns
                    if (isset($v0['access_level']) && ($v0['access_level'] != '' || $v0['access_level'] != null)) {
                        switch ($v0['access_level']) {
                            case 'user':
                                $insertArray['access_level'] = 'Employee';
                                break;
                            case 'admin':
                                $insertArray['access_level'] = 'Admin';
                                break;
                            case 'manager':
                                $insertArray['access_level'] = 'Manager';
                                break;
                            case 'employee':
                                $insertArray['access_level'] = 'Employee';
                                break;
                            case 'executive':
                                $insertArray['access_level'] = 'Executive';
                                break;
                            case 'recruiter':
                                $insertArray['access_level'] = 'Recruiter';
                                break;
                            case 'hiring manager':
                                $insertArray['access_level'] = 'Hiring Manager';
                                break;
                        }
                    }
                    // Check for job title
                    if (isset($v0['job_title']) && ($v0['job_title'] != '' || $v0['job_title'] != null)) {
                        $insertArray['job_title'] = trim($v0['job_title']);
                    }
                    //
                    $insertArray['first_name'] = isset($v0['first_name']) ? trim(ucwords(strtolower($v0['first_name']))) : NULL;
                    //
                    $insertArray['timezone'] = $companyTZ;
                    //
                    if (isset($v0['timezone'])) {
                        $insertArray['timezone'] = preg_replace('/[^a-zA-Z]/', '', $v0['timezone']);
                        if ($insertArray['timezone'] == '') $insertArray['timezone'] = $companyTZ;
                    }
                    $insertArray['timezone'] = strtoupper($insertArray['timezone']);
                    if ($insertArray['timezone'] == '') {
                        unserialize($insertArray['timezone']);
                    }
                    //
                    $insertArray['nick_name']  = isset($v0['nick_name'])  ? trim(ucwords(strtolower($v0['nick_name'])))  : NULL;
                    $insertArray['last_name']  = isset($v0['last_name'])  ? trim(ucwords(strtolower($v0['last_name'])))  : NULL;
                    $insertArray['PhoneNumber'] = isset($v0['PhoneNumber']) && $v0['PhoneNumber'] != '' && $v0['PhoneNumber'] != null ? trim(preg_replace('/[^0-9+]/', '', $v0['PhoneNumber'])) : '';
                    $insertArray['Location_Address'] = isset($v0['Location_Address']) ? trim($v0['Location_Address']) : '';
                    $insertArray['Location_City']    = isset($v0['Location_City']) ? trim($v0['Location_City']) : '';
                    $insertArray['Location_ZipCode'] = isset($v0['Location_ZipCode']) ? trim($v0['Location_ZipCode']) : '';

                    $insertArray['eeoc_code']  = isset($v0['eeoc_code'])  ? trim(strtolower($v0['eeoc_code']))  : '';
                    $insertArray['salary_benefits']  = isset($v0['salary_benefits'])  ? trim(strtolower($v0['salary_benefits']))  : '';
                    $insertArray['workers_compensation_code']  = isset($v0['workers_compensation_code'])  ? trim(strtolower($v0['workers_compensation_code']))  : '';





                    // Check for state
                    if (isset($v0['Location_State'])) {
                        if ($v0['Location_State'] != null || $v0['Location_State'] != '') {
                            $stateInfo = $this->import_csv_model->get_state_and_country_id(trim($v0['Location_State']));
                            //
                            if (sizeof($stateInfo)) {
                                $insertArray['Location_State'] = $stateInfo['sid'];
                                $insertArray['Location_Country'] = $stateInfo['country_sid'];
                            }
                        }
                    }
                    // Check for country
                    if (isset($v0['Location_Country'])) {
                        if (strtolower(trim($v0['Location_Country'])) == 'united states') $insertArray['Location_Country'] = 227;
                        else if (strtolower(trim($v0['Location_Country'])) == 'canada') $insertArray['Location_Country'] = 38;
                    }
                    // Check profile image
                    if (isset($v0['profile_picture'])) {
                        $v0['profile_picture'] = trim($v0['profile_picture']);
                        if ($v0['profile_picture'] != NULL || $v0['profile_picture'] != '')
                            $insertArray['profile_picture'] = $this->import_csv_model->verify_url_data($v0['profile_picture']);
                    }
                    // Check resume
                    if (isset($v0['resume'])) {
                        $v0['resume'] = trim($v0['resume']);
                        if ($v0['resume'] != NULL || $v0['resume'] != '')
                            $insertArray['resume'] = $this->import_csv_model->verify_url_data($v0['resume']);
                    }
                    // Check cover letter
                    if (isset($v0['cover_letter'])) {
                        $v0['cover_letter'] = trim($v0['cover_letter']);
                        if ($v0['cover_letter'] != NULL || $v0['cover_letter'] != '')
                            $insertArray['cover_letter'] = $this->import_csv_model->verify_url_data($v0['cover_letter']);
                    }
                    //
                    $insertArray['active'] = 0;
                    $insertArray['username'] = $this->generateUsername(
                        $insertArray['first_name'],
                        $insertArray['last_name'],
                        $insertArray['email']
                    );
                    $insertArray['parent_sid'] = $companyId;
                    $insertArray['CompanyName'] = $companyName;
                    $insertArray['video_type'] = 'no_video';
                    $insertArray['verification_key'] = random_key() . '_csvImport';
                    // Check joining date
                    if (isset($v0['joined_at']) && !empty($v0['joined_at']) && $v0['joined_at'] != NULL && preg_match('/[0-9]{2}/', $v0['joined_at'])) {
                        //
                        $formatForDate = 'm/d/Y';
                        //
                        $t = explode('/', $v0['joined_at']);
                        if (strlen(end($t)) == 2) {
                            $formatForDate = 'm/d/y';
                        }

                        $insertArray['joined_at'] = formatDateToDB($v0['joined_at'], $formatForDate);
                        $insertArray['registration_date'] = $insertArray['joined_at'] . ' 00:00:00';
                    } else {
                        $insertArray['registration_date'] = date("Y-m-d H:i:s", strtotime('now'));
                        $insertArray['joined_at'] = date("Y-m-d", strtotime('now'));
                    }
                    //New Fields Added By Adil
                    // Check employee number
                    if (isset($v0['employee_number']) && !empty($v0['employee_number']) && $v0['employee_number'] != NULL) {
                        $insertArray['employee_number'] = trim($v0['employee_number']);
                    }
                    // Check ssn
                    if (isset($v0['ssn']) && !empty($v0['ssn']) && $v0['ssn'] != NULL) {
                        $insertArray['ssn'] = trim($v0['ssn']);
                    }
                    // Check notified_by
                    if (isset($v0['notified_by']) && !empty($v0['notified_by']) && $v0['notified_by'] != NULL) {
                        $insertArray['notified_by'] = trim($v0['notified_by']);
                    }
                    // Check dob
                    if (isset($v0['dob']) && !empty($v0['dob']) && $v0['dob'] != NULL && preg_match('/[0-9]{2}/', $v0['dob'])) {
                        //
                        $formatForDate = 'm/d/Y';
                        //
                        $t = explode('/', $v0['dob']);
                        if (strlen(end($t)) == 2) {
                            $formatForDate = 'm/d/y';
                        }
                        $insertArray['dob'] = formatDateToDB($v0['dob'], $formatForDate);
                    }
                    // Check shift hours
                    if (isset($v0['user_shift_hours']) && !empty($v0['user_shift_hours']) && $v0['user_shift_hours'] != NULL) {
                        $insertArray['user_shift_hours'] = trim($v0['user_shift_hours']);
                    }
                    // Check shift minutes
                    if (isset($v0['user_shift_minutes']) && !empty($v0['user_shift_minutes']) && $v0['user_shift_minutes'] != NULL) {
                        $insertArray['user_shift_minutes'] = trim($v0['user_shift_minutes']);
                    }
                    //Fetch and assign Department and Team ids
                    if (isset($v0['team']) && !empty($v0['team']) && $v0['team'] != NULL) {
                        $depTeamSids = $this->import_csv_model->getDepartmentTeamIds($v0['team']);
                        if (sizeof($depTeamSids)) {
                            $insertArray['department_sid'] = $depTeamSids[0]['department_sid'];
                            $insertArray['team_sid'] = $depTeamSids[0]['sid'];
                        }
                    }
                    //For Extra Info
                    if (!empty($pre_emp['extra_info']) && $pre_emp['extra_info'] != NULL) { // Exists In DB
                        $extra_info = unserialize($pre_emp['extra_info']);
                        if (isset($v0['secondary_email']) && !empty($v0['secondary_email']) && $v0['secondary_email'] != NULL) {
                            $extra_info['secondary_email'] = $v0['secondary_email'];
                        }
                        if (isset($v0['other_email']) && !empty($v0['other_email']) && $v0['other_email'] != NULL) {
                            $extra_info['other_email'] = $v0['other_email'];
                        }
                        if (isset($v0['secondary_PhoneNumber']) && !empty($v0['secondary_PhoneNumber']) && $v0['secondary_PhoneNumber'] != NULL) {
                            $extra_info['secondary_PhoneNumber'] = $v0['secondary_PhoneNumber'];
                        }
                        if (isset($v0['other_PhoneNumber']) && !empty($v0['other_PhoneNumber']) && $v0['other_PhoneNumber'] != NULL) {
                            $extra_info['other_PhoneNumber'] = $v0['other_PhoneNumber'];
                        }
                        if (isset($v0['office_location']) && !empty($v0['office_location']) && $v0['office_location'] != NULL) {
                            $extra_info['office_location'] = $v0['office_location'];
                        }
                        $extra_info = serialize($extra_info);
                        $insertArray['extra_info'] = $extra_info;
                    } else { // Not stored in DB, Update with new entries
                        $extra_info = array();
                        if (isset($v0['secondary_email']) && !empty($v0['secondary_email']) && $v0['secondary_email'] != NULL) {
                            $extra_info['secondary_email'] = $v0['secondary_email'];
                        }
                        if (isset($v0['other_email']) && !empty($v0['other_email']) && $v0['other_email'] != NULL) {
                            $extra_info['other_email'] = $v0['other_email'];
                        }
                        if (isset($v0['secondary_PhoneNumber']) && !empty($v0['secondary_PhoneNumber']) && $v0['secondary_PhoneNumber'] != NULL) {
                            $extra_info['secondary_PhoneNumber'] = $v0['secondary_PhoneNumber'];
                        }
                        if (isset($v0['other_PhoneNumber']) && !empty($v0['other_PhoneNumber']) && $v0['other_PhoneNumber'] != NULL) {
                            $extra_info['other_PhoneNumber'] = $v0['other_PhoneNumber'];
                        }
                        if (isset($v0['office_location']) && !empty($v0['office_location']) && $v0['office_location'] != NULL) {
                            $extra_info['office_location'] = $v0['office_location'];
                        }
                        $extra_info = serialize($extra_info);
                        $insertArray['extra_info'] = $extra_info;
                    }
                    // Manage status
                    if (isset($v0['status']) && !empty($v0['status']) && preg_match('/active/i', $v0['status'])) {
                        $insertArray['active'] = 1;
                        $insertArray['general_status'] = 'active';
                    }
                    // Manage gender
                    if (isset($v0['gender']) && !empty($v0['gender'])) {
                        $insertArray['gender'] = strtolower($v0['gender']);
                    }
                    // Manage employment type
                    if (isset($v0['employment_type']) && !empty($v0['employment_type'])) {
                        $insertArray['employee_status'] = preg_match('/full/i', $v0['employment_type']) ? 'fulltime' : 'parttime';
                        $insertArray['employee_status'] = preg_match('/contractual/i', $v0['employment_type']) ? 'contractual' : $insertArray['employee_status'];
                    }
                    //New Fields End
                    // Insert employee

                    if (isset($v0['marital_status']) && !empty($v0['marital_status'])) {
                        $insertArray['marital_status'] = ucwords(trim($v0['marital_status']));
                    }

                    $employeeId = $this->import_csv_model->InsertNewUser($insertArray);
                    //
                    // Manage employee status
                    $this->manageEmployeeStatus($employeeId, $v0);


                    //Assigne Department Team
                    if (sizeof($depTeamSids)) {
                        $assigneDepartmentTeam = [];
                        $assigneDepartmentTeam['department_sid'] = $depTeamSids[0]['department_sid'];
                        $assigneDepartmentTeam['team_sid'] = $depTeamSids[0]['sid'];
                        $assigneDepartmentTeam['employee_sid'] = $employeeId;
                        $assigneDepartmentTeam['created_at'] =
                            $this->assigneDepartmentTeam($assigneDepartmentTeam);
                    }
                    // Add Driver Information

                    $insertDriverinfo['users_sid'] =  $employeeId;
                    $insertDriverinfo['license_type'] =  'drivers';
                    $insertDriverinfo['users_type '] =  'employee';
                    $insertDriverinfo['license_details'] =  serialize($licenseDetails);

                    $this->import_csv_model->InsertLicenseDetails($insertDriverinfo);

                    //
                    $insertCount++;
                }
                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed.';
                $resp['Inserted'] = $insertCount;
                $resp['Existed'] = $existCount;
                $resp['Failed'] = $failCount;
                //
                $this->resp($resp);
                break;
        }
        $this->resp($resp);
    }

    private function manageEmployeeStatus($employeeId, $data)
    {
        $session = $this->session->userdata('logged_in');
        $employerId = $session['employer_detail']['sid'];
        //
        if (preg_match('/terminat/i', $data['status'])) {
            $data_to_update = array();
            $data_to_update['terminated_status'] = 1;

            $this->import_csv_model->updateUserInfo($data_to_update, $employeeId);
        }
        //
        if (isset($data['status']) && !empty($data['status']) && preg_match('/terminat|rehire/i', $data['status'])) {
            //
            $statusArray = [];
            $statusArray['employee_status'] = 2;
            $statusArray['termination_date'] = '';
            $statusArray['status_change_date'] = '';
            $statusArray['employee_sid '] = $employeeId;
            $statusArray['changed_by'] = $employerId;
            $statusArray['ip_address'] = getUserIP();
            $statusArray['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $statusArray['created_at'] = date('Y-m-d H:i:s', strtotime('now'));
            //
            if (preg_match('/terminat/i', $data['status'])) {
                $statusArray['employee_status'] = 1;
                $statusArray['details'] = isset($data['termination_reason']) ? $data['termination_reason'] : '';
                $statusArray['status_change_date'] = $statusArray['termination_date'] = isset($data['termination_date']) && !empty($data['termination_date']) ? formatDateToDB($data['termination_date']) : NULL;
            }
            //
            if (preg_match('/rehire/i', $data['status'])) {
                $statusArray['employee_status'] = 8;
                $statusArray['details'] = isset($data['rehire_reason']) ? $data['rehire_reason'] : '';
                $statusArray['status_change_date'] = isset($data['rehire_date']) && !empty($data['rehire_date']) ? formatDateToDB($data['rehire_date']) : NULL;


                $this->import_csv_model->UpdateRehireDateInUsers(formatDateToDB($data['rehire_date']), $employeeId);
            }
            //
            $this->import_csv_model->AddEmployeeStatus($statusArray);
        }
    }

    function updateUser($pre_emp, $v0)
    {
        $insertArray = array();
        // Check for access level columns
        if (isset($v0['access_level']) && $v0['access_level'] != '' && $v0['access_level'] != null) {
            switch ($v0['access_level']) {
                case 'user':
                    $insertArray['access_level'] = 'Employee';
                    break;
                case 'admin':
                    $insertArray['access_level'] = 'Admin';
                    break;
                case 'manager':
                    $insertArray['access_level'] = 'Manager';
                    break;
                case 'employee':
                    $insertArray['access_level'] = 'Employee';
                    break;
                case 'executive':
                    $insertArray['access_level'] = 'Executive';
                    break;
                case 'recruiter':
                    $insertArray['access_level'] = 'Recruiter';
                    break;
                case 'hiring manager':
                    $insertArray['access_level'] = 'Hiring Manager';
                    break;
            }
        }
        // Check for job title
        if (empty($pre_emp['job_title']) && isset($v0['job_title']) && $v0['job_title'] != '' && $v0['job_title'] != null) $insertArray['job_title'] = trim($v0['job_title']);
        //
        if (empty($pre_emp['first_name']) && isset($v0['first_name']) && $v0['first_name'] != '' && $v0['first_name'] != null) $insertArray['first_name'] = trim(ucwords(strtolower($v0['first_name'])));
        //
        if (empty($pre_emp['timezone']) && isset($v0['timezone']) && $v0['timezone'] != '' && $v0['timezone'] != null) {
            $insertArray['timezone'] = preg_replace('/[^a-zA-Z]/', '', $v0['timezone']);
            $insertArray['timezone'] = strtoupper($insertArray['timezone']);
        }
        //
        if (empty($pre_emp['last_name']) && isset($v0['last_name']) && $v0['last_name'] != '' && $v0['last_name'] != null) $insertArray['last_name'] = trim(ucwords(strtolower($v0['last_name'])));
        if (empty($pre_emp['PhoneNumber']) && isset($v0['PhoneNumber']) && $v0['PhoneNumber'] != '' && $v0['PhoneNumber'] != null) $insertArray['PhoneNumber'] = trim(preg_replace('/[^0-9+]/', '', $v0['PhoneNumber']));
        if (empty($pre_emp['Location_Address']) && isset($v0['Location_Address']) && $v0['Location_Address'] != '' && $v0['Location_Address'] != null) $insertArray['Location_Address'] = trim($v0['Location_Address']);
        if (empty($pre_emp['Location_City']) && isset($v0['Location_City']) && $v0['Location_City'] != '' && $v0['Location_City'] != null) $insertArray['Location_City'] = trim($v0['Location_City']);
        if (empty($pre_emp['Location_ZipCode']) && isset($v0['Location_ZipCode']) && $v0['Location_ZipCode'] != '' && $v0['Location_ZipCode'] != null) $insertArray['Location_ZipCode'] = trim($v0['Location_ZipCode']);
        // Check for state
        if (empty($pre_emp['Location_State']) && isset($v0['Location_State']) && $v0['Location_State'] != null && $v0['Location_State'] != '') {
            $stateInfo = $this->import_csv_model->get_state_and_country_id(trim($v0['Location_State']));
            //
            if (sizeof($stateInfo)) {
                $insertArray['Location_State'] = $stateInfo['sid'];
                $insertArray['Location_Country'] = $stateInfo['country_sid'];
            }
        }
        // Check for country
        if (empty($pre_emp['Location_Country']) && isset($v0['Location_Country']) && $v0['Location_Country'] != null && $v0['Location_Country'] != '') {
            if (strtolower(trim($v0['Location_Country'])) == 'united states') $insertArray['Location_Country'] = 227;
            else if (strtolower(trim($v0['Location_Country'])) == 'canada') $insertArray['Location_Country'] = 38;
        }
        // Check profile image
        if (empty($pre_emp['profile_picture']) && isset($v0['profile_picture']) && !empty($v0['profile_picture']) && $v0['profile_picture'] != NULL) {
            $pp = $this->import_csv_model->verify_url_data(trim($v0['profile_picture']));
            if ($pp != NULL) {
                $insertArray['profile_picture'] = $pp;
            }
        }
        // Check resume
        if (empty($pre_emp['resume']) && isset($v0['resume']) && !empty($v0['resume']) && $v0['resume'] != NULL) {
            $resume = $this->import_csv_model->verify_url_data(trim($v0['resume']));
            if ($resume != NULL) {
                $insertArray['resume'] = $resume;
            }
        }
        // Check cover letter
        if (empty($pre_emp['cover_letter']) && isset($v0['cover_letter']) && !empty($v0['cover_letter']) && $v0['cover_letter'] != NULL) {
            $cl = $this->import_csv_model->verify_url_data($v0['cover_letter']);
            if ($cl != NULL) {
                $insertArray['cover_letter'] = $cl;
            }
        }
        // LinkedIn profile URL
        if (empty($pre_emp['linkedin_profile_url']) && isset($v0['linkedin_profile_url']) && !empty($v0['linkedin_profile_url']) && $v0['linkedin_profile_url'] != NULL) {
            $insertArray['linkedin_profile_url'] = trim($v0['linkedin_profile_url']);
        }
        // Check joining date
        if (empty($pre_emp['joined_at']) && isset($v0['joined_at']) && !empty($v0['joined_at']) && $v0['joined_at'] != NULL) {
            $insertArray['joined_at'] = date("Y-m-d H:i:s", strtotime($v0['joined_at']));
        }
        // Check employee number
        if (empty($pre_emp['employee_number']) && isset($v0['employee_number']) && !empty($v0['employee_number']) && $v0['employee_number'] != NULL) {
            $insertArray['employee_number'] = trim($v0['employee_number']);
        }
        // Check ssn
        if (empty($pre_emp['ssn']) && isset($v0['ssn']) && !empty($v0['ssn']) && $v0['ssn'] != NULL) {
            $insertArray['ssn'] = trim($v0['ssn']);
        }
        // Check notified_by
        if (empty($pre_emp['notified_by']) && isset($v0['notified_by']) && !empty(trim($v0['notified_by'])) && $v0['notified_by'] != NULL) {
            $insertArray['notified_by'] = trim($v0['notified_by']);
        }
        // Check dob
        if (empty($pre_emp['dob']) && isset($v0['dob']) && !empty($v0['dob']) && $v0['dob'] != NULL) {
            $insertArray['dob'] = date("Y-m-d H:i:s", strtotime($v0['dob']));
        }
        // Check shift hours
        if (empty($pre_emp['user_shift_hours']) && isset($v0['user_shift_hours']) && !empty($v0['user_shift_hours']) && $v0['user_shift_hours'] != NULL) {
            $insertArray['user_shift_hours'] = trim($v0['user_shift_hours']);
        }
        // Check shift minutes
        if (empty($pre_emp['user_shift_minutes']) && isset($v0['user_shift_minutes']) && !empty($v0['user_shift_minutes']) && $v0['user_shift_minutes'] != NULL) {
            $insertArray['user_shift_minutes'] = trim($v0['user_shift_minutes']);
        }
        //Fetch and assign Department and Team ids
        if (empty($pre_emp['team']) && isset($v0['team']) && !empty($v0['team']) && $v0['team'] != NULL) {
          
            $depTeamSids = $this->import_csv_model->getDepartmentTeamIds($v0['team']);
            
            if (sizeof($depTeamSids)) {

                if (sizeof($depTeamSids)) {
                    //
                    removeEmployeeAllDepartmentsTeams($pre_emp['sid'], $depTeamSids[0]['sid'], $depTeamSids[0]['department_sid']);

                    $assigneDepartmentTeam = [];
                    $assigneDepartmentTeam['department_sid'] = $depTeamSids[0]['department_sid'];
                    $assigneDepartmentTeam['team_sid'] = $depTeamSids[0]['sid'];
                    $assigneDepartmentTeam['employee_sid'] = $pre_emp['sid'];
                    $assigneDepartmentTeam['created_at'] = getSystemDate();
                    $this->import_csv_model->assigneDepartmentTeam($assigneDepartmentTeam);
                }

                $insertArray['department_sid'] = $depTeamSids[0]['department_sid'];
                $insertArray['team_sid'] = $depTeamSids[0]['sid'];
            }
        }

        //For Extra Info
        if (!empty($pre_emp['extra_info']) && $pre_emp['extra_info'] != NULL) { // Exists In DB
            $extra_info = unserialize($pre_emp['extra_info']);
            if (empty($pre_emp['secondary_email']) && isset($v0['secondary_email']) && !empty(trim($v0['secondary_email'])) && $v0['secondary_email'] != NULL) {
                $extra_info['secondary_email'] = $v0['secondary_email'];
            }
            if (empty($pre_emp['other_email']) && isset($v0['other_email']) && !empty(trim($v0['other_email'])) && $v0['other_email'] != NULL) {
                $extra_info['other_email'] = $v0['other_email'];
            }
            if (empty($pre_emp['secondary_PhoneNumber']) && isset($v0['secondary_PhoneNumber']) && !empty(trim($v0['secondary_PhoneNumber'])) && $v0['secondary_PhoneNumber'] != NULL) {
                $extra_info['secondary_PhoneNumber'] = $v0['secondary_PhoneNumber'];
            }
            if (empty($pre_emp['other_PhoneNumber']) && isset($v0['other_PhoneNumber']) && !empty(trim($v0['other_PhoneNumber'])) && $v0['other_PhoneNumber'] != NULL) {
                $extra_info['other_PhoneNumber'] = $v0['other_PhoneNumber'];
            }
            if (empty($pre_emp['office_location']) && isset($v0['office_location']) && !empty(trim($v0['office_location'])) && $v0['office_location'] != NULL) {
                $extra_info['office_location'] = $v0['office_location'];
            }
            $extra_info = serialize($extra_info);
            $insertArray['extra_info'] = $extra_info;
        } else { // Not stored in DB, Update with new entries
            $extra_info = array();
            if (empty($pre_emp['secondary_email']) && isset($v0['secondary_email']) && !empty(trim($v0['secondary_email'])) && $v0['secondary_email'] != NULL) {
                $extra_info['secondary_email'] = $v0['secondary_email'];
            }
            if (empty($pre_emp['other_email']) && isset($v0['other_email']) && !empty(trim($v0['other_email'])) && $v0['other_email'] != NULL) {
                $extra_info['other_email'] = $v0['other_email'];
            }
            if (empty($pre_emp['secondary_PhoneNumber']) && isset($v0['secondary_PhoneNumber']) && !empty(trim($v0['secondary_PhoneNumber'])) && $v0['secondary_PhoneNumber'] != NULL) {
                $extra_info['secondary_PhoneNumber'] = $v0['secondary_PhoneNumber'];
            }
            if (empty($pre_emp['other_PhoneNumber']) && isset($v0['other_PhoneNumber']) && !empty(trim($v0['other_PhoneNumber'])) && $v0['other_PhoneNumber'] != NULL) {
                $extra_info['other_PhoneNumber'] = $v0['other_PhoneNumber'];
            }
            if (empty($pre_emp['office_location']) && isset($v0['office_location']) && !empty(trim($v0['office_location'])) && $v0['office_location'] != NULL) {
                $extra_info['office_location'] = $v0['office_location'];
            }
            if (sizeof($extra_info)) {
                $extra_info = serialize($extra_info);
                $insertArray['extra_info'] = $extra_info;
            }
        }
        // Manage status
        if (empty($pre_emp['status']) && isset($v0['status']) && !empty($v0['status']) && preg_match('/active/i', $v0['status'])) {
            $insertArray['active'] = 1;
            $insertArray['general_status'] = 'active';
        }
        // Manage gender
        if (empty($pre_emp['gender']) && isset($v0['gender']) && !empty($v0['gender'])) {
            $insertArray['gender'] = strtolower($v0['gender']);
        }
        // Manage employment type
        if (empty($pre_emp['employee_status']) && isset($v0['employment_type']) && !empty($v0['employment_type'])) {
            $insertArray['employee_status'] = preg_match('/full/i', $v0['employment_type']) ? 'fulltime' : 'parttime';
            $insertArray['employee_status'] = preg_match('/contractual/i', $v0['employment_type']) ? 'contractual' : $insertArray['employee_status'];
        }


        if (empty($pre_emp['eeoc_code']) && isset($v0['eeoc_code']) && !empty($v0['eeoc_code'])) {
            $insertArray['eeoc_code'] = strtolower($v0['eeoc_code']);
        }

        if (empty($pre_emp['salary_benefits']) && isset($v0['salary_benefits']) && !empty($v0['salary_benefits'])) {
            $insertArray['salary_benefits'] = strtolower($v0['salary_benefits']);
        }

        if (empty($pre_emp['workers_compensation_code']) && isset($v0['workers_compensation_code']) && !empty($v0['workers_compensation_code'])) {
            $insertArray['workers_compensation_code'] = strtolower($v0['workers_compensation_code']);
        }

        //
        $this->manageEmployeeStatus($pre_emp['sid'], $v0);

        // Update employee
        if (!empty($insertArray)) {
            $this->import_csv_model->UpdateNewUser($pre_emp['sid'], $insertArray);
        }
        //
        return $pre_emp['sid'];
    }

    /**
     * Send JSON response
     *
     * @param $responseArray Array
     *
     * @return JSON
     */
    function resp($responseArray)
    {
        header('Content-type: application/json');
        echo json_encode($responseArray);
        exit(0);
    }

    /**
     * Generates username
     * Created on: 16-08-2019
     * 
     * @uses random_key
     * 
     * @param $firstName String|NULL
     * @param $lastName  String|NULL
     * @param $email     String|NULL
     * 
     * @return String
     */
    private function generateUsername($firstName, $lastName, $email)
    {
        $username = '';
        if ($firstName !== NULL) $username .= preg_replace('/[^A-z]/', '', $firstName);
        if ($lastName !== NULL) $username .= preg_replace('/[^A-z]/', '', $lastName);
        if ($email !== NULL) $username .= $email;
        return strtolower(trim($username != '' ? $username . '_' . random_key(5) : $username));
    }
}
