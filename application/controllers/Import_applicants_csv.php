<?php defined('BASEPATH') or exit('No direct script access allowed');
ini_set('max_execution_time', 800);
class Import_applicants_csv extends Public_Controller
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
            $data['title'] = 'Import Applicants From CSV File';
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'import_applicants_csv');
            $company_id = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $data['employerData'] = $this->import_csv_model->getEmployerDetail($employer_id);
            $all_status = $this->import_csv_model->get_default_status_sid_and_text($employer_id);
            $status_sid = $all_status['status_sid'];
            $status = $all_status['status_name'];
            $insertRecord = false;

            if (isset($_POST['action']) && $_POST['action'] == 'upload_file') { //Handle File Upload
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
                $phoneNumberTitles = array('phone_number', 'phoneNumber', 'phone-number', 'PhoneNumber', 'Phone', 'Contact Number', 'Contact', 'PrimaryNumber'); //for PhoneNumber
                $addressTitles = array('address', 'Address', 'Street Address'); // for Location_Address
                $cityTitles = array('city', 'City'); // for Location_city
                $zipCodeTitles = array('zip_code', 'zipCode', 'zip-code', 'ZipCode', 'zip'); // for Location_ZipCode
                $state = array('state');
                $country = array('country');
                $profile_picture = array('profile', 'profile picture', 'profile_picture', 'profile-picture', 'profilepicture', 'profile picture url', 'profile_picture_url', 'profile-picture-url', 'profilepictureurl');
                $resume = array('resume', 'cv', 'resume url', 'resume_url', 'resume-url', 'resumeurl');
                $cover_letter = array('cover letter', 'cover_letter', 'coverletterurl', 'cover_letter_url', 'cover-letter-url', 'cover_letter-url');
                $jobTitleTitles = array('job_title', 'jobTitle', 'job-title', 'JobTitle', 'Job Title', 'job title', 'job'); // for Job_title
                $allowedTitles = array_merge($firstNameTitles, $lastNameTitles, $emailAddressTitles, $phoneNumberTitles, $addressTitles, $cityTitles, $zipCodeTitles, $state, $country, $profile_picture, $resume, $cover_letter, $jobTitleTitles);
                $correctTitles = array();
                $correctTitleslowercase = array();

                foreach ($titles as $title) {
                    $lower_title = strtolower($title);
                    $lower_allowedTitles = array_map('strtolower', $allowedTitles);

                    if (in_array($lower_title, $lower_allowedTitles)) {
                        $correctTitles[] = $title;
                        $correctTitleslowercase[] = strtolower($title);
                    }
                }

                if (
                    in_array('email', $correctTitleslowercase) ||
                    in_array('email_address', $correctTitleslowercase) ||
                    in_array('emailaddress', $correctTitleslowercase) ||
                    in_array('email-address', $correctTitleslowercase) ||
                    in_array('e-mail', $correctTitleslowercase) ||
                    in_array('e mail', $correctTitleslowercase)
                ) {
                    $count = 0;

                    foreach ($records as $record) {
                        $recordToInsert = array();
                        $insertRecord = true;
                        $applicant_jobs_list_data = array();
                        $state_info = array();

                        foreach ($correctTitles as $title) {
                            if ((strpos(strtolower($title), 'first') !== false) || (strpos(strtolower($title), 'fname') !== false)) {
                                $recordToInsert['first_name'] = $record[$title];
                            }

                            if ((strpos(strtolower($title), 'last') !== false) || (strpos(strtolower($title), 'lname') !== false)) {
                                $recordToInsert['last_name'] = $record[$title];
                            }

                            if ((strpos(strtolower($title), 'email') !== false) || (strpos(strtolower($title), 'e-mail') !== false) || (strpos(strtolower($title), 'e_mail') !== false) || (strpos(strtolower($title), 'e mail') !== false)) {
                                $recordToInsert['email'] = $record[$title];
                                $email = $record[$title];
                            }

                            if ((strpos(strtolower($title), 'phone') !== false) || (strpos(strtolower($title), 'number') !== false)) {
                                $recordToInsert['phone_number'] = $record[$title];
                            }

                            if ((strpos(strtolower($title), 'address') !== false) || (strpos(strtolower($title), 'Street Address') !== false)) {
                                $recordToInsert['address'] = $record[$title];
                            }

                            if (strpos(strtolower($title), 'city') !== false) {
                                $recordToInsert['city'] = $record[$title];
                            }

                            if ((strpos(strtolower($title), 'zip') !== false) || (strpos(strtolower($title), 'zipcode') !== false)) {
                                $recordToInsert['zipcode'] = $record[$title];
                            }

                            if (strpos(strtolower($title), 'state') !== false) {
                                $state = $record[$title];

                                if ($state != null || $state != '') {
                                    $state_info = $this->import_csv_model->get_state_and_country_id($state);
                                }

                                if (!empty($state_info)) {
                                    $recordToInsert['state'] = $state_info['sid'];
                                    $recordToInsert['country'] = $state_info['country_sid'];
                                }
                            }


                            if (strpos(strtolower($title), 'country') !== false) {
                                $country = $record[$title];

                                if (strtolower($country) == 'United States') {
                                    $recordToInsert['country'] = 227;
                                }

                                if (strtolower($country) == 'Canada') {
                                    $recordToInsert['country'] = 38;
                                }
                            }

                            if ((strpos(strtolower($title), 'profile') !== false) || (strpos(strtolower($title), 'picture') !== false)) {
                                $profile_pic_url = $record[$title];

                                if ($profile_pic_url != '' || $profile_pic_url != NULL) {
                                    $recordToInsert['pictures'] = $this->import_csv_model->verify_url_data($profile_pic_url);
                                }
                            }

                            if ((strpos(strtolower($title), 'resume') !== false) || (strpos(strtolower($title), 'cv') !== false)) {
                                $resume_url = $record[$title];

                                if ($resume_url != '' || $resume_url != NULL) {
                                    $recordToInsert['resume'] = $this->import_csv_model->verify_url_data($resume_url);
                                }
                            }

                            if ((strpos(strtolower($title), 'cover') !== false) || (strpos(strtolower($title), 'letter') !== false)) {
                                $cover_letter = $record[$title];

                                if ($cover_letter != '' || $cover_letter != NULL) {
                                    $recordToInsert['cover_letter'] = $this->import_csv_model->verify_url_data($cover_letter);
                                }
                            }

                            if ((strpos(strtolower($title), 'job') !== false) || (strpos(strtolower($title), 'jobTitle') !== false)) {
                                $applicant_jobs_list_data['desired_job_title'] = $record[$title];
                            }
                        }

                        $recordToInsert['employer_sid'] = $company_id;
                        $applicant_jobs_list_data['company_sid'] = $company_id;
                        $applicant_jobs_list_data['date_applied'] = date("Y-m-d H:i:s");

                        if ($insertRecord === true) { //Insert applicant record in Applicant Profile
                            $applicant_sid = $this->import_csv_model->insert_new_applicant($company_id, $email, $recordToInsert);
                            $applicant_jobs_list_data['portal_job_applications_sid'] = $applicant_sid;
                            $applicant_jobs_list_data['status'] = $status;
                            $applicant_jobs_list_data['status_sid'] = $status_sid;
                            $applicant_jobs_list_data['applicant_source'] = 'csv imported data';
                            $applicant_jobs_list_data['applicant_type'] = 'Manual Candidate';
                            $this->import_csv_model->insert_new_applicant_job_record($company_id, $applicant_sid, $applicant_jobs_list_data); //Insert applicant record in Jobs list
                            $count++;
                        }
                    }
                }

                if ($count > 0) {
                    $this->session->set_flashdata('message', '<b>Success:</b> You have successfuly imported ' . $count . ' applicants.');
                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> Please provide proper CSV file.');
                    redirect('import_applicants_csv', 'refresh');
                }
            }
            $this->load->view('main/header', $data);
            $this->load->view('import_applicants_csv/index');
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
        $employerId = $session['employer_detail']['sid'];
        $companyName = $session['company_detail']['CompanyName'];
        $allStatus   = $this->import_csv_model->getDefaultStatusSidAndText($employerId);
        $status_sid  = $allStatus['status_sid'];
        $status      = $allStatus['status_name'];
        // Set default response array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request made.';
        // Check for a valid AJAX request
        if (!$this->input->is_ajax_request()) exit(0);
        //
        $formpost = $this->input->post(NULL, TRUE);
        // _e($formpost, true, true);
        //
        switch ($formpost['action']) {
            case 'add_applicants':
                set_time_limit(0);
                // Default array
                $failCount = $insertCount = $existCount = 0;
                //
                foreach ($formpost['applicants'] as $k0 => $v0) {
                    // Set default insert array
                    $insertArray = array();
                    // If email is not set then skip
                    if (!isset($v0['email'])) {
                        $failCount++;
                        continue;
                    }
                    // Clean
                    $to = $insertArray['email'] = isset($v0['email']) ? trim(strtolower($v0['email'])) : NULL;
                    // Check for empty
                    if ($insertArray['email'] === null || trim($insertArray['email']) == '') {
                        $failCount++;
                        continue;
                    }
                    // Check for job title
                    if (isset($v0['desired_job_title']) && ($v0['desired_job_title'] != '' || $v0['desired_job_title'] != null)) $applicant_jobs_list_data['desired_job_title'] = trim($v0['desired_job_title']);
                    //
                    $insertArray['first_name'] = isset($v0['first_name']) ? trim(ucwords(strtolower($v0['first_name']))) : NULL;
                    $insertArray['last_name']  = isset($v0['last_name'])  ? trim(ucwords(strtolower($v0['last_name'])))  : NULL;
                    $insertArray['phone_number'] = trim(preg_replace('/[^0-9+]/', '', $v0['phone_number']));
                    $insertArray['address'] = trim($v0['address']);
                    $insertArray['city']    = trim($v0['city']);
                    $insertArray['zipcode'] = trim($v0['zipcode']);

                    $insertArray['dob'] = isset($v0['dob']) ? trim(DateTime::createFromFormat('m/d/Y', $v0['dob'])->format('Y-m-d')) : NULL;
                    $insertArray['ssn'] = isset($v0['ssn']) ? trim($v0['ssn']) : NULL;
                    $insertArray['marital_status'] = isset($v0['marital_status']) ? trim($v0['marital_status']) : NULL;
                    $insertArray['gender'] = isset($v0['gender']) ? trim($v0['gender']) : NULL;


                    //
                    $insertArray['employee_number'] = isset($v0['employee_number']) ? trim($v0['employee_number']) : '';


                    // Check for state
                    if (isset($v0['state'])) {
                        if ($v0['state'] != null || $v0['state'] != '') {
                            $stateInfo = $this->import_csv_model->get_state_and_country_id(trim($v0['state']));
                            //
                            if (sizeof($stateInfo)) {
                                $insertArray['state'] = $stateInfo['sid'];
                                $insertArray['country'] = $stateInfo['country_sid'];
                            }
                        }
                    }
                    // Check for country
                    if (isset($v0['country'])) {
                        if (strtolower(trim($v0['country'])) == 'united states') $insertArray['country'] = 227;
                        else if (strtolower(trim($v0['country'])) == 'canada') $insertArray['country'] = 38;
                    }
                    // Check profile image
                    if (isset($v0['pictures'])) {
                        $v0['pictures'] = trim($v0['pictures']);
                        if ($v0['pictures'] != NULL && $v0['pictures'] != '')
                            $insertArray['pictures'] = $this->import_csv_model->verify_url_data($v0['pictures']);
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
                    $applicant_jobs_list_data['company_sid'] = $companyId;
                    $applicant_jobs_list_data['date_applied'] = date("Y-m-d H:i:s");

                    $insertArray['employer_sid'] = $companyId;
                    $insertArray['date_applied'] = date("Y-m-d H:i:s", strtotime('now'));
                    // _e($insertArray, true);

                    $applicant_jobs_list_data['applicant_source'] = isset($v0['applicant_source']) ? trim(ucwords(strtolower($v0['applicant_source']))) : NULL;

                    if (isset($v0['date_applied']) && !empty($v0['date_applied']) && $v0['date_applied'] != NULL && preg_match('/[0-9]{2}/', $v0['date_applied'])) {

                        $formatForDate = 'Y-m-d H:i:s';
                        //
                        $dateApplied = explode(' -', $v0['date_applied']);
                        //
                        $applicant_jobs_list_data['date_applied'] = date("Y-m-d H:i:s",strtotime($dateApplied[0]));
                        // $applicant_jobs_list_data['date_applied'] = formatDateToDB($dateApplied[0], $formatForDate);
                        $insertArray['date_applied'] = $applicant_jobs_list_data['date_applied'];
                    } else {
                        $applicant_jobs_list_data['date_applied'] = date("Y-m-d H:i:s", strtotime('now'));
                        $insertArray['date_applied'] = date("Y-m-d H:i:s", strtotime('now'));
                    }

                    $applicantStatuses = [
                        'Not Contacted Yet' => 'notcontactedyet',
                        'Left Message' => 'leftmessage',
                        'Contacted' => 'contacted',
                        'Candidate Responded' => 'candidateresponded',
                        'Interviewing' => 'interviewing',
                        'Submitted' => 'submitted',
                        'Qualifying' => 'qualifying',
                        'Ready to Hire' => 'readytohire',
                        'do_not_hire' => 'donothire',
                        'Offered Job' => 'offeredjob',
                        'Client Declined' => 'clientdeclined',
                        'Not In Consideration' => 'notinconsideration',
                        'Future Opportunity' => 'futureopportunity'
                    ];

                    if (isset($v0['status']) && !empty($v0['status']) && $v0['status'] != NULL) {
                        $jobStatus = preg_replace('/\s+/', '', strtolower($v0['status']));
                        $jobStatusValue = array_search($jobStatus, $applicantStatuses);
                        if ($jobStatusValue != '') {
                            $applicant_jobs_list_data['status'] = $jobStatusValue;
                        } else {
                            $applicant_jobs_list_data['status'] = $status;
                        }
                    } else {
                        $applicant_jobs_list_data['status'] = $status;
                    }

                    //
                    $applicantId = $this->import_csv_model->insert_new_applicant($companyId, $insertArray['email'], $insertArray);
                    $applicant_jobs_list_data['portal_job_applications_sid'] = $applicantId;
                    $applicant_jobs_list_data['status_sid'] = $status_sid;
                    // $applicant_jobs_list_data['applicant_source'] = 'csv imported data';
                    $applicant_jobs_list_data['applicant_type'] = 'Manual Candidate';
                    $applicant_jobs_list_data['job_sid'] = '0';
                    //
                    if (isset($v0['desired_job_title'])) {
                        $job_title = trim($v0['desired_job_title']);
                        if ($job_title != NULL || $job_title != '')
                            $applicant_jobs_list_data['desired_job_title'] = $job_title;
                    }
                    $this->import_csv_model->insert_new_applicant_job_record($companyId, $applicantId, $applicant_jobs_list_data); //Insert applicant record in Jobs list
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
}
