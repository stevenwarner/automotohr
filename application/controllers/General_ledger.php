<?php

defined('BASEPATH') or exit('No direct script access allowed');

class General_ledger extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->model('general_ledger_model');
    }

    public function index($start_date = 'all', $end_date = 'all')
    {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');

        $data['session'] = $this->session->userdata('logged_in');
        $data['sanitizedView'] = true;
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'my_settings', 'bulk_resume'); // Param2: Redirect URL, Param3: Function Name
        $company_sid = $data['session']['company_detail']['sid'];
        $company_name = strtolower(clean($data['session']['company_detail']['CompanyName']));
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['title'] = 'General Ledger Report';
        $start_date = urldecode($start_date);
        $end_date = urldecode($end_date);
        //
        if (!empty($start_date) && $start_date != 'all') {
            $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d');
        } else {
            $start_date_applied = date('Y-m-d 00:00:00');
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d');
        } else {
            $end_date_applied = date('Y-m-d');
        }
        //
        $between = '';
        $url = base_url('general_ledger');
        //
        if ($start_date_applied != NULL && $end_date_applied != NULL) {
            $between = "processed_date between '" . $start_date_applied . "' and '" . $end_date_applied . "'";
            $url = $url . '/' . $start_date . '/' . $end_date;
        }
        //
        $generalLedger = $this->general_ledger_model->getCompanyPayrolls($company_sid, $between);
        //
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("daterangepicker", "js"),
        ];

        // set bundle
        $data["appJs"] = bundleJs([
            "v1/general_ledger"
        ], "public/v1/shifts/", "general_ledger", false);
        //
        $data['company_sid'] = $company_sid;
        $data['company_name'] = $company_name;
        $data['employer_sid'] = $employer_sid;
        $data['generalLedgerCount'] = count($generalLedger);
        $data['generalLedger'] = $generalLedger;
        $data['url'] = $url;
        //
        $this->load->view('main/header', $data);
        $this->load->view('general_ledger/index');
        $this->load->view('main/footer');
    }

    public function getPayrollDetail($id)
    {
        //
        if (!$this->session->userdata('logged_in')) {
            return SendResponse(400, [
                "errors" => [
                    "Session is expired."
                ]
            ]);
        }
        //
        $data["payrollDetail"] = $this->general_ledger_model->getPayroll($id);
        // _e($data['payrollDetail'],true);
        //
        return SendResponse(
            200,
            [
                "view" =>
                $this->load->view(
                    "general_ledger/partials/payroll_detail",
                    $data,
                    true
                )
            ]
        );
    }

    public function actionHandler($start_date = 'all', $end_date = 'all', $action = 'print')
    {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');

        $data['session'] = $this->session->userdata('logged_in');
        $data['sanitizedView'] = true;
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'my_settings', 'bulk_resume'); // Param2: Redirect URL, Param3: Function Name
        $company_sid = $data['session']['company_detail']['sid'];
        $company_name = strtolower(clean($data['session']['company_detail']['CompanyName']));
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['title'] = 'General Ledger Report';
        $start_date = urldecode($start_date);
        $end_date = urldecode($end_date);
        //
        if (!empty($start_date) && $start_date != 'all') {
            $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d');
        } else {
            $start_date_applied = date('Y-m-d 00:00:00');
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d');
        } else {
            $end_date_applied = date('Y-m-d');
        }
        //
        $between = '';
        $url = base_url('general_ledger');
        //
        if ($start_date_applied != NULL && $end_date_applied != NULL) {
            $between = "processed_date between '" . $start_date_applied . "' and '" . $end_date_applied . "'";
        }
        //
        $generalLedger = $this->general_ledger_model->getCompanyPayrolls($company_sid, $between, false);
        //
        if ($action == 'export') {
            $export_data = array();
            $i = 0;
            $rows = '';

            foreach ($generalLedger as $key => $ledger) {
                //
                $payrollDetail = json_decode($ledger['totals'], true);
                //
                $export_data[$i]['payroll_date'] = formatDateToDB($ledger['processed_date'], DB_DATE_WITH_TIME, SITE_DATE);
                $export_data[$i]['company_debit'] = "$".$payrollDetail['company_debit'];
                $export_data[$i]['net_pay_debit'] = "$".$payrollDetail['net_pay_debit'];
                $export_data[$i]['tax_debit'] = "$".$payrollDetail['tax_debit'];
                $export_data[$i]['reimbursement_debit'] = "$".$payrollDetail['reimbursement_debit'];
                $export_data[$i]['child_support_debit'] = "$".$payrollDetail['child_support_debit'];
                $export_data[$i]['reimbursements'] = "$".$payrollDetail['reimbursements'];
                $export_data[$i]['net_pay'] = "$".$payrollDetail['net_pay'];
                $export_data[$i]['gross_pay'] = "$".$payrollDetail['gross_pay'];
                $export_data[$i]['employee_bonuses'] = "$".$payrollDetail['employee_bonuses'];
                $export_data[$i]['employee_commissions'] ="$".$payrollDetail['employee_commissions'];
                $export_data[$i]['employee_cash_tips'] = "$".$payrollDetail['employee_cash_tips'];
                $export_data[$i]['additional_earnings'] = "$".$payrollDetail['additional_earnings'];
                $export_data[$i]['owners_draw'] = "$".$payrollDetail['owners_draw'];
                $export_data[$i]['check_amount'] = "$".$payrollDetail['check_amount'];
                $export_data[$i]['employer_taxes'] = "$".$payrollDetail['employer_taxes'];
                $export_data[$i]['employee_taxes'] = "$".$payrollDetail['employee_taxes'];
                $export_data[$i]['benefits'] = "$".$payrollDetail['benefits'];
                $export_data[$i]['employee_benefits_deductions'] = "$".$payrollDetail['employee_benefits_deductions'];
                $export_data[$i]['deferred_payroll_taxes'] = "$".$payrollDetail['deferred_payroll_taxes'];
                $export_data[$i]['other_deductions'] = "$".$payrollDetail['other_deductions'];
                //
                $rows .= $export_data[$i]['payroll_date'] . ',' . $export_data[$i]['company_debit'] . ',' . $export_data[$i]['net_pay_debit'] . ',' . $export_data[$i]['tax_debit'] . ',' . $export_data[$i]['reimbursement_debit'] . ',' . $export_data[$i]['child_support_debit'] . ',' . $export_data[$i]['reimbursements'] . ',' . $export_data[$i]['net_pay'] . ',' . $export_data[$i]['gross_pay'] . ',' . $export_data[$i]['employee_bonuses'] . ',' . $export_data[$i]['employee_commissions'] . ',' . $export_data[$i]['employee_cash_tips'] . ',' . $export_data[$i]['additional_earnings'] . ',' . $export_data[$i]['owners_draw'] . ',' . $export_data[$i]['check_amount'] . ',' . $export_data[$i]['employer_taxes'] . ',' . $export_data[$i]['employee_taxes'] . ',' . $export_data[$i]['benefits'] . ',' . $export_data[$i]['employee_benefits_deductions'] . ',' . $export_data[$i]['deferred_payroll_taxes'] . ',' . $export_data[$i]['other_deductions'] . PHP_EOL;
                $i++;
            }

            $header_row = 'Payroll Date,Company Debit,Net Pay Debit,Tax Debit,Reimbursement Debit,Child Support Debit,Reimbursements,Net Pay,Gross Pay,Employee Bonuses,Employee Commissions,Employee Cash Tips,Additional Earnings,Owners Draw,Check Amount,Employer Taxes,Employee taxes,Benefits,Employee Benefits Deductions,Deferred Payroll Taxes,Other Deductions';
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
            header('Content-Disposition: attachment; filename="general_ledger_' . date('Y_m_d-H:i:s') . '.csv"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . $file_size); // provide file size
            header('Connection: close');
            echo $header_row . ',' . PHP_EOL;
            echo $rows;
            exit;
        } else {
            $data['company_details'] = $data['session']['company_detail'];
            $data['generalLedgerCount'] = count($generalLedger);
            $data['generalLedger'] = $generalLedger;
            $data['action'] = $action;
            //
            $this->load->view('general_ledger/print_ledger', $data);
        }
    }

    public function importLedger()
    {
        if ($this->session->userdata('logged_in')) {
            $data['sanitizedView'] = true;
            $data['title'] = 'Import Ledger From CSV File';
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'import_csv'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_id = $data['session']['employer_detail']['sid'];
            // $data['employerData'] = $this->import_csv_model->getEmployerDetail($employer_id);

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
                    }
                }

                if ($count > 0) {
                    $this->session->set_flashdata('message', '<b>Success:</b> You have successfuly imported ' . $count . ' employees.');
                } else if ($already_exists > 0) {
                    $this->session->set_flashdata('message', '<b>Error:</b> (' . $already_exists . ') employee(s) already exist in the system.');
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> System could not detect Employee Email address, Please make sure that the heading title for it is "Email"');
                }

                redirect('employee_management?employee_type=offline&keyword=', 'refresh');
            }

            // set common files bundle
            $data["pageCSS"] = [
                getPlugin("mFileUploader", "css"),
                getPlugin("alertify", "css")
            ];
            $data["pageJs"] = [
                getPlugin("mFileUploader", "js"),
                getPlugin("alertify", "js")
            ];

            // set bundle
            $data["appJs"] = bundleJs([
                "v1/import_ledger"
            ], "public/v1/shifts/", "import_ledger", false);

            $this->load->view('main/header', $data);
            $this->load->view('general_ledger/import_ledger');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }
}
