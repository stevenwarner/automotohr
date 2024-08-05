<?php defined('BASEPATH') or exit('No direct script access allowed');

class Form_full_employment_application extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('form_full_employment_application_model');
        $this->load->model('manage_admin/documents_model');
        $this->load->model('dashboard_model');
    }

    public function index($verification_key = null)
    {
        if ($verification_key != null) {

            $request_details = $this->form_full_employment_application_model->get_form_request($verification_key);

            if (!empty($request_details) && $request_details['status'] == "sent") {
                $data = array();
                $data['page_title'] = 'Full Employment Application';
                $data['request_details'] = $request_details;
                $company_sid = $request_details['company_sid'];
                $user_type = $request_details['user_type'];
                $user_sid = $request_details['user_sid'];
                $company_details = $this->form_full_employment_application_model->get_company_details($company_sid);

                $user_info = $this->form_full_employment_application_model->get_user_information($company_sid, $user_type, $user_sid);
                $field_names = array();

                if ($user_type == 'applicant') {
                    $field_names[] = 'first_name';
                    $field_names[] = 'last_name';
                    $field_names[] = 'email';
                    $field_names[] = 'employee_type';
                } else {
                    $field_names[] = 'first_name';
                    $field_names[] = 'last_name';
                    $field_names[] = 'email';
                    $field_names[] = 'Location_Address';
                    $field_names[] = 'Location_City';
                    $field_names[] = 'Location_State';
                    $field_names[] = 'Location_ZipCode';
                    $field_names[] = 'PhoneNumber';
                    $field_names[] = 'employee_type';
                    $field_names[] = 'job_title';
                }

                //
                $birthDate = date('Y-m-d', strtotime('now'));
                //
                $data['birthDate'] = '';
                if (!empty($user_info['dob']) && $user_info['dob'] != '0000-00-00') {
                    $birthDate = $user_info['dob'];
                    $data['birthDate'] = DateTime::createfromformat('Y-m-d', $birthDate)->format('m-d-Y');
                }
                //
                $birthDate = DateTime::createfromformat('Y-m-d', $birthDate);
                $todayDate = DateTime::createfromformat('Y-m-d', date('Y-m-d', strtotime('now')));
                //
                $data['above18'] = $todayDate->diff($birthDate)->y;

                $drivers_license = $this->dashboard_model->get_license_details($user_type, $user_sid, 'drivers');
                if (!empty($drivers_license)) {
                    $drivers_license['license_details'] = unserialize($drivers_license['license_details']);
                }
                $data['drivers_license_details'] = isset($drivers_license['license_details']) ? $drivers_license['license_details'] : array();

                $data['extra_info'] = unserialize($user_info['extra_info']);
                //                if ($user_info['full_employment_application'] == '') {
                //                    $data['user_info'] = $user_info;
                //                } else {
                $filtered_user_fields = array();
                foreach ($user_info as $key => $value) {
                    if ($user_type == 'applicant') {
                        switch ($key) {
                            case 'address':
                                $filtered_user_fields['Location_Address'] = $value;
                                break;
                            case 'city':
                                $filtered_user_fields['Location_City'] = $value;
                                break;
                            case 'state':
                                $filtered_user_fields['Location_State'] = $value;
                                break;
                            case 'country':
                                $filtered_user_fields['Location_Country'] = $value;
                                break;
                            case 'zipcode':
                                $filtered_user_fields['Location_ZipCode'] = $value;
                                break;
                            case 'phone_number':
                                $filtered_user_fields['PhoneNumber'] = $value;
                                break;
                            default:
                                if (in_array($key, $field_names)) {
                                    $filtered_user_fields[$key] = $value;
                                }
                                break;
                        }
                    } else {
                        if (in_array($key, $field_names)) {
                            $filtered_user_fields[$key] = $value;
                        }
                    }
                }

                $form_data = !empty(unserialize($user_info['full_employment_application'])) ? unserialize($user_info['full_employment_application']) : array();
                $user_info = array_merge($filtered_user_fields, $form_data);

                //                echo '<pre>';print_r($user_info);die();
                if (!empty($company_details)) {
                    $data['company_name'] = $company_details['CompanyName'];
                }

                $data['user_info'] = $user_info;
                //                }

                $data['page_title'] = 'Full Employment Application';
                $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxNameMiddle', 'Middle Name', 'trim|xss_clean');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('suffix', 'Suffix', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxSSN', 'TextBoxSSN', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxDOB', 'Date of Birth', 'required|trim|xss_clean');
                //$this->form_validation->set_rules('email', 'Email Address', 'valid_email|required|trim|xss_clean|is_unique[users.email]');


                if (isset($_POST['TextBoxAddressEmailConfirm']) && strpos($_POST['TextBoxAddressEmailConfirm'], '*') == false) {
                    $this->form_validation->set_rules('TextBoxAddressEmailConfirm', 'Confirm Email Address', 'valid_email|required|trim|xss_clean');
                }

                $this->form_validation->set_rules('Location_Address', 'Address', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressLenghtCurrent', 'How Long', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_City', 'City', 'required|trim|xss_clean');
                //$this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'required|trim|xss_clean');
                $this->form_validation->set_rules('CheckBoxAddressInternationalCurrent', 'Non USA Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressStreetFormer1', 'Former Residence', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressLenghtFormer1', 'How Long?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressCityFormer1', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListAddressStateFormer1', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressZIPFormer1', 'Zip Code', 'trim|xss_clean');
                $this->form_validation->set_rules('CheckBoxAddressInternationalFormer1', 'Non USA Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressStreetFormer2', 'Former Residence', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressLenghtFormer2', 'How Long?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressCityFormer2', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListAddressStateFormer2', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressZIPFormer2', 'Zip Code', 'trim|xss_clean');
                $this->form_validation->set_rules('CheckBoxAddressInternationalFormer2', 'Non USA Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressStreetFormer3', 'Other Mailing Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressCityFormer3', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListAddressStateFormer3', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressZIPFormer3', 'Zip Code', 'trim|xss_clean');
                $this->form_validation->set_rules('PhoneNumber', 'Primary Telephone', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxTelephoneMobile', 'Mobile Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxTelephoneOther', 'Other Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListPostionTime', 'Job position', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxPositionDesired', 'more position', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxWorkBeginDate', 'Begin Date', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxWorkCompensation', 'Expected Compensation', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListWorkTransportation', 'Have Transportation', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListWorkOver18', '18 years or older?', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListAliases', 'Any other names', 'trim|xss_clean');
                $this->form_validation->set_rules('nickname_or_othername_details', 'other name explaination', 'trim|xss_clean');
                //$this->form_validation->set_rules('RadioButtonListCriminalWrongDoing', 'ever plead Guilty', 'trim|xss_clean');
                //$this->form_validation->set_rules('RadioButtonListCriminalBail', 'been arrested?', 'trim|xss_clean');
                //$this->form_validation->set_rules('arrested_pending_trail_details', 'been arrested details', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListDriversLicenseQuestion', 'Drivers License Question', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxDriversLicenseNumber', 'Drivers License Number', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListDriversState', 'Drivers State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxDriversLicenseExpiration', 'Expiration date', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListDriversLicenseTraffic', 'Drivers License Plead Guilty', 'trim|xss_clean');
                $this->form_validation->set_rules('license_guilty_details', 'license guilty details', 'trim|xss_clean');
                $this->form_validation->set_rules('is_already_employed', 'is_already_employed', 'trim|xss_clean');
                $this->form_validation->set_rules('previous_company_name', 'previous_company_name', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationHighSchoolName', 'Education High School Name', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEducationHighSchoolGraduated', 'High School Graduated', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationHighSchoolCity', 'Education City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolState', 'Education State', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedMonthBegin', 'Dates of Attendance', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedYearBegin', 'Year', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedYearEnd', 'Year', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationCollegeName', 'College / University', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEducationCollegeGraduated', 'Did you graduate?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationCollegeCity', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeState', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedMonthBegin', 'Month begin', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedYearBegin', 'Year', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedYearEnd', 'Year End', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationCollegeMajor', 'Major', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationCollegeDegree', 'Degree Earned', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationOtherName', 'Other School', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEducationOtherGraduated', 'Did you graduate?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationOtherCity', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherState', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedMonthBegin', 'Dates of Attendance', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedYearBegin', 'Strat Year', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedYearEnd', 'Year End', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationOtherMajor', 'Other Major', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationOtherDegree', 'Other Degree', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseName', 'Professional License Type', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseIssuingAgencyState', 'Issuing Agency/State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseNumber', 'License Number', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerName1', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition1', 'Position/Title', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress1', 'Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerCity1', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerState1', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber1', 'Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1', 'Dates of Employment', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1', 'Year Begin', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1', 'Year End', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin1', 'Starting Compensation', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd1', 'Ending Compensation', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor1', 'Supervisor', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact1_0', 'May we contact this employer?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave1', 'Employer Reason Leave', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerName2', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition2', 'Position/Title', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress2', 'Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerCity2', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerState2', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber2', 'Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2', 'Dates of Employment', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2', 'Year Begin', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2', 'Year End', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin2', 'Starting Compensation', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd2', 'Ending Compensation', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor2', 'Supervisor', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact2_0', 'May we contact this employer?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave2', 'Employer Reason Leave', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerName3', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition3', 'Position/Title', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress3', 'Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerCity3', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerState3', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber3', 'Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3', 'Dates of Employment', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3', 'Year Begin', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3', 'Year End', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin3', 'Starting Compensation', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd3', 'Ending Compensation', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor3', 'Supervisor', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact3_0', 'May we contact this employer?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave3', 'Employer Reason Leave', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEverTerminated', 'Employment Ever Terminated', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEverTerminatedReason', 'Ever Terminated Reason', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEverResign', 'Employment Ever Resign', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEverResignReason', 'Employment Resign Reason', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentGaps', 'Employer Gaps', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerNoContact', 'Employer No Contact', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceName1', ' Reference Name', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAcquainted1', 'Reference Acquainted', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAddress1', 'Reference Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceCity1', 'Reference City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListReferenceState1', 'Reference State', 'trim|xss_clean');
                //
                if (isset($_POST['TextBoxReferenceTelephoneNumber1']) && strpos($_POST['TextBoxReferenceTelephoneNumber1'], '*') == false) {
                    $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber1', 'Telephone Number', 'trim|xss_clean');
                }
                //
                if (isset($_POST['TextBoxReferenceEmail1']) && strpos($_POST['TextBoxReferenceEmail1'], '*') == false) {
                    $this->form_validation->set_rules('TextBoxReferenceEmail1', 'Reference Email', 'valid_email|trim|xss_clean');
                }
                //
                $this->form_validation->set_rules('TextBoxReferenceName2', 'Reference Name', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAcquainted2', 'Reference Acquainted', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAddress2', 'Reference Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceCity2', 'Reference City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListReferenceState2', 'Reference State', 'trim|xss_clean');
                //
                if (isset($_POST['TextBoxReferenceTelephoneNumber2']) && strpos($_POST['TextBoxReferenceTelephoneNumber2'], '*') == false) {
                    $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber2', 'Telephone Number', 'trim|xss_clean');
                }
                //
                if (isset($_POST['TextBoxReferenceEmail2']) && strpos($_POST['TextBoxReferenceEmail2'], '*') == false) {
                    $this->form_validation->set_rules('TextBoxReferenceEmail2', 'Reference Email', 'valid_email|trim|xss_clean');
                }
                //
                $this->form_validation->set_rules('TextBoxReferenceName3', 'Reference Name', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAcquainted3', 'Reference Acquainted', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAddress3', 'Reference Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceCity3', 'Reference City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListReferenceState3', 'Reference State', 'trim|xss_clean');
                //
                if (isset($_POST['TextBoxReferenceTelephoneNumber3']) && strpos($_POST['TextBoxReferenceTelephoneNumber3'], '*') == false) {
                    $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber3', 'Telephone Number', 'trim|xss_clean');
                }
                //
                if (isset($_POST['TextBoxReferenceEmail3']) && strpos($_POST['TextBoxReferenceEmail3'], '*') == false) {
                    $this->form_validation->set_rules('TextBoxReferenceEmail3', 'Reference Email', 'valid_email|trim|xss_clean');
                }
                //
                $this->form_validation->set_rules('TextBoxAdditionalInfoWorkExperience', 'Additional Work Experience Information', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAdditionalInfoWorkTraining', 'Additional Work Training Information', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAdditionalInfoWorkConsideration', 'Additional Work Consideration Information', 'trim|xss_clean');
                $this->form_validation->set_rules('CheckBoxAgreement1786', 'CheckBoxAgreement1786', 'required|trim|xss_clean');
                $this->form_validation->set_rules('CheckBoxAgree', 'Acknowledge Agree', 'required|trim|xss_clean');
                $this->form_validation->set_rules('CheckBoxTerms', 'Terms of Acceptance', 'required|trim|xss_clean');

                //
                $ei = unserialize($company_details['extra_info']);
                //
                $data['eight_plus'] = 0;
                $data['affiliate'] = 0;
                $data['d_license'] = 0;
                $data['l_employment'] = 0;
                $data['ssn_required'] = $data['session']['portal_detail']['ssn_required'];
                $data['dob_required'] = $data['session']['portal_detail']['dob_required'];

                if (isset($ei['affiliate'])) {
                    $data['affiliate'] = $ei['affiliate'];
                }
                if (isset($ei['18_plus'])) {
                    $data['eight_plus'] = $ei['18_plus'];
                }
                if (isset($ei['d_license'])) {
                    $data['d_license'] = $ei['d_license'];
                }
                if (isset($ei['l_employment'])) {
                    $data['l_employment'] = $ei['l_employment'];
                }
                //
                if ($data['ssn_required'] == 1) {
                    //
                    $this->form_validation->set_rules('TextBoxSSN', 'TextBoxSSN', 'required|trim|xss_clean');
                }
                //
                if ($data['dob_required'] == 1) {
                    //
                    $this->form_validation->set_rules('TextBoxDOB', 'Date of Birth', 'required|trim|xss_clean');
                }

                //
                if ($data['d_license'] && $this->input->post('RadioButtonListDriversLicenseQuestion', true) != 'No') {
                    $this->form_validation->set_rules('TextBoxDriversLicenseNumber', 'License Number', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxDriversLicenseExpiration', 'License Expiration Date', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListDriversCountry', 'License Country', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListDriversState', 'License State', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('RadioButtonListDriversLicenseTraffic', 'guilty', 'required|trim|xss_clean');

                    if ($this->input->post('RadioButtonListDriversLicenseTraffic', true) != 'No') {
                        $this->form_validation->set_rules('license_guilty_details_violation', 'Violation', 'required|trim|xss_clean');
                    }
                }

                //
                if ($data['l_employment']) {
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerName1', 'Employment Type', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition1', 'Position', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress1', 'Address', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerCountry1', 'Country', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerState1', 'State', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerCity1', 'City', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber1', 'Telephone', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1', 'Employment Start Month', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1', 'Employment Start Year', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1', 'Employment End Month', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1', 'Employment End Year', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor1', 'Supervisor', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact1_0', 'Contact', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave1', 'Reason', 'required|trim|xss_clean');
                }

                //
                if ($data['eight_plus']) {
                    $this->form_validation->set_rules('RadioButtonListWorkOver18', '18 years', 'required|trim|xss_clean');
                }

                //
                if ($data['affiliate']) {
                    $this->form_validation->set_rules('is_already_employed', 'Already Employed', 'required|trim|xss_clean');
                }

                if ($this->form_validation->run() == false) {
                    //
                    if (!empty(validation_errors())) {
                        sendMail(
                            FROM_EMAIL_NOTIFICATIONS,
                            'mubashir.saleemi123@gmail.com',
                            'Form Full Application Validation Error',
                            @json_encode(validation_errors())
                        );
                    }
                    //
                    $data['states'] = db_get_active_states(227);
                    $data['starting_year_loop'] = 1930;
                    $suffix_values = array();
                    $suffix_values[] = array('key' => 'Junior', 'value' => 'JR');
                    $suffix_values[] = array('key' => 'Senior', 'value' => 'SR');
                    $suffix_values[] = array('key' => 'II', 'value' => '2');
                    $suffix_values[] = array('key' => 'III', 'value' => '3');
                    $suffix_values[] = array('key' => 'IV', 'value' => '4');
                    $suffix_values[] = array('key' => 'V', 'value' => 'V');
                    $data['suffix_values'] = $suffix_values;
                    $months = array();
                    $months[] = 'January';
                    $months[] = 'February';
                    $months[] = 'March';
                    $months[] = 'April';
                    $months[] = 'May';
                    $months[] = 'June';
                    $months[] = 'July';
                    $months[] = 'August';
                    $months[] = 'September';
                    $months[] = 'October';
                    $months[] = 'November';
                    $months[] = 'December';
                    $data['months'] = $months;
                    $form_status = $request_details['status'];
                    if ($form_status == 'signed') {
                        $readonly_check = ' readonly="readonly" ';
                        $disabled_check = ' disabled="disabled" ';
                    } else {
                        $readonly_check = '';
                        $disabled_check = '';
                    }

                    $data_countries = db_get_active_countries(); //Get Countries and States - Start

                    foreach ($data_countries as $value) {
                        $data_states[$value['sid']] = db_get_active_states($value['sid']);
                    }

                    $data['active_countries'] = $data_countries;
                    $data['active_states'] = $data_states;
                    $data_states_encode = htmlentities(json_encode($data_states));
                    $data['states'] = $data_states_encode;
                    $ip_track = $this->documents_model->get_document_ip_tracking_record($company_sid, 'full_employment_application', $user_sid, $user_type);
                    $data['ip_track'] = $ip_track;
                    $data['readonly_check'] = $readonly_check;
                    $data['disabled_check'] = $disabled_check;
                    $data['user_sid'] = $user_sid;
                    $data['user_type'] = $user_type;

                    $this->load->view('form_full_employment_application/index', $data);
                } else {

                    $field_names = array();
                    $field_names[] = 'first_name';
                    $field_names[] = 'last_name';
                    $field_names[] = 'email';
                    $field_names[] = 'Location_Address';
                    $field_names[] = 'Location_City';
                    $field_names[] = 'Location_State';
                    $field_names[] = 'Location_ZipCode';
                    $field_names[] = 'PhoneNumber';
                    $formpost = $this->input->post(NULL, TRUE);
                    $full_employment_application = array();
                    $driving_no = '';
                    $driving_exp = '';
                    //
                    // remove staric from user info add on 09/02/2022
                    //
                    foreach ($formpost as $f_key => $f_value) {
                        if (strpos($f_value, '*') !== false) {
                            $formpost[$f_key] = $user_info[$f_key];
                        }
                    }
                    //
                    foreach ($formpost as $key => $value) {
                        if (!in_array($key, $field_names)) {
                            $full_employment_application[$key] = $value;
                        }
                        if ($key == 'TextBoxDriversLicenseNumber') {
                            $driving_no = $value;
                        } elseif ($key == 'TextBoxDriversLicenseExpiration') {
                            $driving_exp = $value;
                        }
                    }
                    $drive_data = $data['drivers_license_details'];
                    if (!empty($driving_no)) {
                        $drive_data['license_number'] = $driving_no;
                    }
                    if (!empty($driving_exp)) {
                        $drive_data['license_expiration_date'] = $driving_exp;
                    }
                    if (!empty($driving_no) || !empty($driving_exp)) {
                        if (sizeof($drivers_license)) {
                            $drivers_license_serial_data['license_details'] = serialize($drive_data);
                            $this->dashboard_model->update_license_info($drivers_license['sid'], $drivers_license_serial_data);
                        } else {
                            $drivers_license = array();
                            $drivers_license['users_sid'] = $user_sid;
                            $drivers_license['users_type'] = $user_type;
                            $drivers_license['license_type'] = 'drivers';
                            $drivers_license['license_details'] = serialize($drive_data);
                            $this->dashboard_model->save_license_info($drivers_license);
                        }
                    }

                    $full_employment_application['client_ip'] = getUserIP();
                    $full_employment_application['client_signature_timestamp'] = date('Y-m-d H:i:s');
                    $notifications_status = get_notifications_status($company_sid);
                    $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                    $applicant_notifications_status = 0;
                    //echo '<pre>'; print_r($full_employment_application); exit;
                    if (!empty($notifications_status)) {
                        $applicant_notifications_status = $notifications_status['employment_application'];
                        //                        $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
                    }
                    $applicant_notification_contacts = array();

                    if ($user_type == 'applicant') {
                        $applicant_profile = db_get_applicant_profile($user_sid);
                        $profile_link = '<a style="background-color: #15c; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url('applicant_profile/' . $applicant_profile['portal_job_applications_sid'] . '/' . $applicant_profile['sid']) . '"> Applicant Profile</a>';
                    } elseif ($user_type == 'employee') {
                        $applicant_profile = db_get_employee_profile($user_sid)[0];
                        $profile_link = '<a style="background-color: #15c; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url('employee_profile/' . $user_sid) . '"> Employee Profile</a>';
                    }
                    //
                    $folder = APPPATH . '../../applicant/FFA';
                    //
                    if (!is_dir($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    // 
                    $categories_file = fopen($folder . '/FFA_' . $user_type . '_' . $user_sid . '_' . date('Y_m_d_H_i_s') . '.json', 'w');
                    //
                    fwrite($categories_file, json_encode($full_employment_application));
                    //
                    fclose($categories_file);

                    $data['extra_info']['other_email'] = $formpost['TextBoxAddressStreetFormer3'];
                    $data['extra_info']['other_PhoneNumber'] = $formpost['TextBoxTelephoneOther'];

                    if ($user_type == 'employee') {
                        $dataToUpdate = array(
                            'first_name' => $formpost['first_name'],
                            'last_name' => $formpost['last_name'],
                            'email' => $formpost['email'],
                            'Location_Address' => $formpost['Location_Address'],
                            'Location_City' => $formpost['Location_City'],
                            'Location_State' => $formpost['Location_State'],
                            'Location_Country' => $formpost['Location_Country'],
                            'Location_ZipCode' => $formpost['Location_ZipCode'],
                            'PhoneNumber' => isset($formpost['txt_phonenumber']) ? $formpost['txt_phonenumber'] : $formpost['PhoneNumber'],
                            'extra_info' => serialize($data['extra_info']),
                            'full_employment_application' => serialize($full_employment_application)
                        );
                    } else {
                        $dataToUpdate = array(
                            'first_name' => $formpost['first_name'],
                            'last_name' => $formpost['last_name'],
                            'email' => $formpost['email'],
                            'address' => $formpost['Location_Address'],
                            'city' => $formpost['Location_City'],
                            'state' => $formpost['Location_State'],
                            'country' => $formpost['Location_Country'],
                            'zipcode' => $formpost['Location_ZipCode'],
                            'phone_number' => $formpost['PhoneNumber'],
                            'referred_by_name' => $full_employment_application['TextBoxReferenceName1'],
                            'referred_by_email' => $full_employment_application['TextBoxReferenceEmail1'],
                            'extra_info' => serialize($data['extra_info']),
                            'full_employment_application' => serialize($full_employment_application)
                        );
                    }
                    //
                    //
                    if (isset($formpost['TextBoxDOB']) && !empty($formpost['TextBoxDOB'])) {
                        $DOB = date('Y-m-d', strtotime(str_replace('-', '/', $formpost['TextBoxDOB'])));
                        $dataToUpdate['dob'] = $DOB;
                    }
                    //
                    if (isset($formpost['TextBoxNameMiddle']) && !empty($formpost['TextBoxNameMiddle'])) {
                        $dataToUpdate['middle_name'] = $formpost['TextBoxNameMiddle'];
                    }
                    //
                    if (isset($formpost['TextBoxSSN']) && !empty($formpost['TextBoxSSN'])) {
                        $dataToUpdate['ssn'] = $formpost['TextBoxSSN'];
                    }
                    //
                    //  $this->form_full_employment_application_model->update_applicant($user_sid, $data);
                    $this->form_full_employment_application_model->update_form_details($company_sid, $user_sid, $user_type, $dataToUpdate);
                    $this->form_full_employment_application_model->update_form_status($verification_key, 'signed');
                    $this->documents_model->insert_document_ip_tracking_record($company_sid, $user_sid, getUserIP(), 'full_employment_application', 'signed', $_SERVER['HTTP_USER_AGENT'], $user_sid, $user_type);

                    if ($applicant_notifications_status == 1) {

                        $applicant_notification_contacts = get_notification_email_contacts($company_sid, 'employment_application', 0);

                        if (!empty($applicant_notification_contacts)) {
                            foreach ($applicant_notification_contacts as $contact) {
                                //
                                $employer_sid = $contact['employer_sid'];
                                // For applicant
                                if (
                                    $user_type == 'applicant' && // User type must be applicant
                                    !empty($contact['employer_sid']) && // Has to be an employer
                                    in_array(strtolower(trim($contact['access_level'])), ['hiring manager', 'manager']) && // Role should be hiring manager or manager
                                    !getEmployerAssignJobs($contact['employer_sid'], $user_sid) // Has the job or candidate visibility
                                ) {
                                    continue;
                                }
                                //
                                $sms_notify = 0;
                                $contact_no = 0;
                                if ($company_sms_notification_status) {
                                    if ($contact['employer_sid'] != 0) {
                                        $notify_by = get_employee_sms_status($this, $contact['employer_sid']);
                                        if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                            $contact_no = $notify_by['PhoneNumber'];
                                            $sms_notify = 1;
                                        }
                                    } else {
                                        if (!empty($contact['contact_no'])) {
                                            $contact_no = $contact['contact_no'];
                                            $sms_notify = 1;
                                        }
                                    }
                                    if ($sms_notify) {
                                        $this->load->library('Twilioapp');
                                        // Send SMS
                                        $sms_template = get_company_sms_template($this, $company_sid, 'employment_application');
                                        $replacement_sms_array = array(); //Send Payment Notification to admin.
                                        $replacement_sms_array['applicant_name'] = $applicant_profile['first_name'] . ' ' . $applicant_profile['last_name'];
                                        $replacement_sms_array['contact_name'] = ucwords(strtolower($contact['contact_name']));
                                        $sms_body = 'This sms is to inform you that ' . $applicant_profile['first_name'] . ' ' . $applicant_profile['last_name'] . '�has completed and signed their full employment application.';
                                        if (sizeof($sms_template) > 0) {
                                            $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_sms_array);
                                        }
                                        sendSMS(
                                            $contact_no,
                                            $sms_body,
                                            trim(ucwords(strtolower($contact['contact_name']))),
                                            $contact['email'],
                                            $this,
                                            $sms_notify,
                                            $company_sid
                                        );
                                    }
                                }
                                $replacement_array['firstname'] = $applicant_profile['first_name'];
                                $replacement_array['lastname'] = $applicant_profile['last_name'];
                                $replacement_array['email'] = $applicant_profile['email'];
                                $applicant_link = $profile_link;
                                $replacement_array['link'] = $applicant_link;
                                log_and_send_templated_email(FULL_EMPLOYMENT_APPLICATION_SIGNED, $contact['email'], $replacement_array);
                            }
                        }
                    }


                    $this->session->set_flashdata('message', '"FOR TAKING THE TIME TO COMPLETE THIS DOCUMENT"');
                    redirect('thank_you', 'refresh');
                }
            } else {
                redirect('', 'refresh');
            }
        } else {
            redirect('', 'refresh');
        }
    }

    public function send_form()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'dashboard', 'application_tracking'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $this->form_validation->set_rules('company_sid', 'Company SID', 'required|trim');
            $this->form_validation->set_rules('user_type', 'User Type', 'required|trim');
            $this->form_validation->set_rules('user_sid', 'User SID', 'required|trim');

            if ($this->form_validation->run() == false) {
                redirect('dashboard', 'refresh');
            } else {
                $user_type = $this->input->post('user_type');
                $company_sid = $this->input->post('company_sid');
                $user_sid = $this->input->post('user_sid');

                switch (strtolower($user_type)) {
                    case 'applicant':
                        $applicant_info = $this->form_full_employment_application_model->get_user_information($company_sid, $user_type, $user_sid);
                        $user_name = ucwords($applicant_info['first_name'] . ' ' . $applicant_info['last_name']);
                        $user_email = $applicant_info['email'];
                        $request_details = $this->form_full_employment_application_model->get_form_request_details($company_sid, $user_type, $user_sid);

                        $verification_key = random_key(80);
                        if (empty($request_details)) {
                            $status = 'sent';
                            $this->form_full_employment_application_model->create_form_request($company_sid, $user_type, $user_sid, $verification_key, $status);
                        } else {
                            $pre_verification_key = $request_details['verification_key'];
                            $update_arr = array('verification_key' => $verification_key, 'status' => 'sent', 'status_date' => date('Y-m-d H:i:s'));
                            $this->form_full_employment_application_model->update_form($pre_verification_key, $update_arr);
                        }

                        $link_html = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" target="_blank" href="' . base_url('form_full_employment_application/' . $verification_key) . '">Full Employment Application</a>';
                        $replacement_array = array();
                        $replacement_array['user_name'] = $user_name;
                        $replacement_array['company_name'] = ucwords($company_name);
                        $replacement_array['link'] = $link_html;
                        $company_email_header_footer = message_header_footer($company_sid, ucwords($company_name));
                        log_and_send_templated_email(FULL_EMPLOYMENT_APPLICATION_REQUEST, $user_email, $replacement_array, $company_email_header_footer);
                        $list_sid = $this->input->post('list_sid');
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Full employment Application Successfully Sent!');
                        redirect('applicant_profile/' . $user_sid . '/' . $list_sid, 'refresh');
                        break;
                    case 'employee':
                        $employee_info = $this->form_full_employment_application_model->get_user_information($company_sid, $user_type, $user_sid);
                        $user_name = ucwords($employee_info['first_name'] . ' ' . $employee_info['last_name']);
                        $user_email = $employee_info['email'];
                        $request_details = $this->form_full_employment_application_model->get_form_request_details($company_sid, $user_type, $user_sid);

                        $verification_key = random_key(80);
                        if (!empty($request_details)) {
                            $pre_verification_key = $request_details['verification_key'];
                            $update_arr = array('verification_key' => $verification_key, 'status' => 'sent', 'status_date' => date('Y-m-d H:i:s'));
                            $this->form_full_employment_application_model->update_form($pre_verification_key, $update_arr);
                        } else {
                            $status = 'sent';
                            $this->form_full_employment_application_model->create_form_request($company_sid, $user_type, $user_sid, $verification_key, $status);
                        }

                        $link_html = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" target="_blank" href="' . base_url('form_full_employment_application/' . $verification_key) . '">Full Employment Application</a>';
                        $replacement_array = array();
                        $replacement_array['user_name'] = $user_name;
                        $replacement_array['company_name'] = ucwords($company_name);
                        $replacement_array['link'] = $link_html;
                        $company_email_header_footer = message_header_footer($company_sid, ucwords($company_name));
                        log_and_send_templated_email(FULL_EMPLOYMENT_APPLICATION_REQUEST, $user_email, $replacement_array, $company_email_header_footer);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Full employment Application Successfully Sent!');
                        redirect('employee_profile/' . $user_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function  applicant_full_employment_application($sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data = applicant_right_nav($sid);
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'application_tracking_system/active/all/all/all/all', array('view_applicant_full_employment_application', 'send_applicant_full_employment_application')); // Param2: Redirect URL, Param3: Function Name
            $data['load_view'] = false;
            //
            $data['_ssv'] = $_ssv = getSSV($data['session']['employer_detail']);
            //
            if ($sid > 0) {

                $applicant_info = $this->form_full_employment_application_model->get_applicants_details($sid);
                $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
                //$this->form_validation->set_rules('TextBoxNameMiddle', 'Middle Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('suffix', 'Suffix', 'trim|xss_clean');
                // convert both the emails to lower
                $postEmail = $_POST["email"] ?  strtolower($_POST["email"]) : "";
                $applicant_info['email'] = strtolower($applicant_info['email']);

                if ($postEmail && $postEmail == $applicant_info['email']) {
                    $this->form_validation->set_rules('email', 'Email Address', 'required|trim|xss_clean');
                } else {
                    $this->form_validation->set_rules('email', 'Email Address', 'required|trim|xss_clean|unique[users.email]');
                }

                $this->form_validation->set_rules('TextBoxAddressEmailConfirm', 'Confirm Email Address', 'required|trim|xss_clean');
                $this->form_validation->set_rules('Location_Address', 'Address', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressLenghtCurrent', 'How Long', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_City', 'City', 'required|trim|xss_clean');
                $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'required|trim|xss_clean');
                //$this->form_validation->set_rules('CheckBoxAddressInternationalCurrent', 'Non USA Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressStreetFormer1', 'Former Residence', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressLenghtFormer1', 'How Long?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressCityFormer1', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListAddressCountryFormer1', 'Former Country', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListAddressStateFormer1', 'Former State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressZIPFormer1', 'Zip Code', 'trim|xss_clean');
                //$this->form_validation->set_rules('CheckBoxAddressInternationalFormer1', 'Non USA Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressStreetFormer2', 'Former Residence', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressLenghtFormer2', 'How Long?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressCityFormer2', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListAddressStateFormer2', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressZIPFormer2', 'Zip Code', 'trim|xss_clean');
                $this->form_validation->set_rules('CheckBoxAddressInternationalFormer2', 'Non USA Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressStreetFormer3', 'Other Mailing Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressCityFormer3', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListAddressStateFormer3', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAddressZIPFormer3', 'Zip Code', 'trim|xss_clean');
                $this->form_validation->set_rules('PhoneNumber', 'Primary Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxTelephoneMobile', 'Mobile Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxTelephoneOther', 'Other Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListPostionTime', 'Job position', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxPositionDesired', 'more position', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxWorkBeginDate', 'Begin Date', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxWorkCompensation', 'Expected Compensation', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListWorkTransportation', 'Have Transportation', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListWorkOver18', '18 years or older?', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListAliases', 'Any other names', 'trim|xss_clean');
                $this->form_validation->set_rules('nickname_or_othername_details', 'other name explaination', 'trim|xss_clean');
                //$this->form_validation->set_rules('RadioButtonListCriminalWrongDoing', 'ever plead Guilty', 'trim|xss_clean');
                //$this->form_validation->set_rules('RadioButtonListCriminalBail', 'been arrested?', 'trim|xss_clean');
                //$this->form_validation->set_rules('arrested_pending_trail_details', 'been arrested details', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListDriversLicenseQuestion', 'Drivers License Question', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxDriversLicenseNumber', 'Drivers License Number', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListDriversState', 'Drivers State', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListDriversCountry', 'Drivers Country', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxDriversLicenseExpiration', 'Expiration date', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListDriversLicenseTraffic', 'Drivers License Plead Guilty', 'trim|xss_clean');
                $this->form_validation->set_rules('license_guilty_details', 'license guilty details', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationHighSchoolName', 'Education High School Name', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEducationHighSchoolGraduated', 'High School Graduated', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationHighSchoolCity', 'Education City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolState', 'Education State', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolCountry', 'Education Country', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedMonthBegin', 'Dates of Attendance', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedYearBegin', 'Year', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedYearEnd', 'Year', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationCollegeName', 'College / University', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEducationCollegeGraduated', 'Did you graduate?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationCollegeCity', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeState', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeCountry', 'Country', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedMonthBegin', 'Month begin', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedYearBegin', 'Year', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedYearEnd', 'Year End', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationCollegeMajor', 'Major', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationCollegeDegree', 'Degree Earned', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationOtherName', 'Other School', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEducationOtherGraduated', 'Did you graduate?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationOtherCity', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherState', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherCountry', 'Country', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedMonthBegin', 'Dates of Attendance', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedYearBegin', 'Strat Year', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedYearEnd', 'Year End', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationOtherMajor', 'Other Major', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationOtherDegree', 'Other Degree', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseName', 'Professional License Type', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseIssuingAgencyState', 'Issuing Agency/State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseNumber', 'License Number', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerName1', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition1', 'Position/Title', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress1', 'Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerCity1', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerState1', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerCountry1', 'Country', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber1', 'Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1', 'Dates of Employment', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1', 'Year Begin', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1', 'Year End', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin1', 'Starting Compensation', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd1', 'Ending Compensation', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor1', 'Supervisor', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact1_0', 'May we contact this employer?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave1', 'Employer Reason Leave', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerName2', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition2', 'Position/Title', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress2', 'Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerCity2', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerState2', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber2', 'Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2', 'Dates of Employment', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2', 'Year Begin', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2', 'Year End', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin2', 'Starting Compensation', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd2', 'Ending Compensation', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor2', 'Supervisor', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact2_0', 'May we contact this employer?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave2', 'Employer Reason Leave', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerName3', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition3', 'Position/Title', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress3', 'Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerCity3', 'City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerState3', 'State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber3', 'Telephone', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3', 'Dates of Employment', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3', 'Year Begin', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3', 'Month End', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3', 'Year End', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin3', 'Starting Compensation', 'trim|xss_clean');
                //                $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd3', 'Ending Compensation', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor3', 'Supervisor', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact3_0', 'May we contact this employer?', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave3', 'Employer Reason Leave', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEverTerminated', 'Employment Ever Terminated', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEverTerminatedReason', 'Ever Terminated Reason', 'trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEverResign', 'Employment Ever Resign', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEverResignReason', 'Employment Resign Reason', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentGaps', 'Employer Gaps', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerNoContact', 'Employer No Contact', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceName1', ' Reference Name', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAcquainted1', 'Reference Acquainted', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAddress1', 'Reference Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceCity1', 'Reference City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListReferenceState1', 'Reference State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber1', 'Telephone Number', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceEmail1', 'Reference Email', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceName2', 'Reference Name', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAcquainted2', 'Reference Acquainted', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAddress2', 'Reference Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceCity2', 'Reference City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListReferenceState2', 'Reference State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber2', 'Telephone Number', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceEmail2', 'Reference Email', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceName3', 'Reference Name', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAcquainted3', 'Reference Acquainted', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceAddress3', 'Reference Address', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceCity3', 'Reference City', 'trim|xss_clean');
                $this->form_validation->set_rules('DropDownListReferenceState3', 'Reference State', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber3', 'Telephone Number', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxReferenceEmail3', 'Reference Email', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAdditionalInfoWorkExperience', 'Additional Work Experience Information', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAdditionalInfoWorkTraining', 'Additional Work Training Information', 'trim|xss_clean');
                $this->form_validation->set_rules('TextBoxAdditionalInfoWorkConsideration', 'Additional Work Consideration Information', 'trim|xss_clean');
                $this->form_validation->set_rules('CheckBoxAgreement1786', 'CheckBoxAgreement1786', 'required|trim|xss_clean');
                $this->form_validation->set_rules('CheckBoxAgree', 'Acknowledge Agree', 'required|trim|xss_clean');
                //
                $ei = unserialize($data['session']['company_detail']['extra_info']);
                //
                $data['eight_plus'] = 0;
                $data['affiliate'] = 0;
                $data['d_license'] = 0;
                $data['l_employment'] = 0;
                $data['ssn_required'] = $data['session']['portal_detail']['ssn_required'];
                $data['dob_required'] = $data['session']['portal_detail']['dob_required'];
                //
                if ($data['ssn_required'] == 1) {
                    //
                    $this->form_validation->set_rules('TextBoxSSN', 'TextBoxSSN', 'required|trim|xss_clean');
                }
                //
                if ($data['dob_required'] == 1) {
                    //
                    $this->form_validation->set_rules('TextBoxDOB', 'Date of Birth', 'required|trim|xss_clean|callback_check_age');
                }

                if (isset($ei['affiliate'])) {
                    $data['affiliate'] = $ei['affiliate'];
                }
                if (isset($ei['18_plus'])) {
                    $data['eight_plus'] = $ei['18_plus'];
                }
                if (isset($ei['d_license'])) {
                    $data['d_license'] = $ei['d_license'];
                }
                if (isset($ei['l_employment'])) {
                    $data['l_employment'] = $ei['l_employment'];
                }

                //
                if ($data['d_license'] && $this->input->post('RadioButtonListDriversLicenseQuestion', true) != 'No') {

                    $this->form_validation->set_rules('TextBoxDriversLicenseNumber', 'License Number', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxDriversLicenseExpiration', 'License Expiration Date', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListDriversCountry', 'License Country', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListDriversState', 'License State', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('RadioButtonListDriversLicenseTraffic', 'guilty', 'required|trim|xss_clean');
                }

                //
                if ($data['l_employment']) {
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerName1', 'Employment Type', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition1', 'Position', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress1', 'Address', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerCountry1', 'Country', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerState1', 'State', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerCity1', 'City', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber1', 'Telephone', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1', 'Employment Start Month', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1', 'Employment Start Year', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1', 'Employment End Month', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1', 'Employment End Year', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor1', 'Supervisor', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact1_0', 'Contact', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave1', 'Reason', 'required|trim|xss_clean');
                }

                //
                if ($data['eight_plus']) {
                    $this->form_validation->set_rules('RadioButtonListWorkOver18', '18 years', 'required|trim|xss_clean');
                }

                //
                if ($data['affiliate']) {
                    $this->form_validation->set_rules('is_already_employed', 'Already Employed', 'required|trim|xss_clean');
                }

                $drivers_license = $this->form_full_employment_application_model->get_license_details('applicant', $sid, 'drivers');
                if (!empty($drivers_license)) {
                    $drivers_license['license_details'] = unserialize($drivers_license['license_details']);
                }
                $data['drivers_license_details'] = isset($drivers_license['license_details']) ? $drivers_license['license_details'] : array();
                //
                $fullEmployementForm = unserialize($applicant_info['full_employment_application']);
                $extras = unserialize($applicant_info['extra_info']);

                if ($this->form_validation->run() === FALSE) {
                    //
                    if (!empty(validation_errors())) {
                        sendMail(
                            FROM_EMAIL_NOTIFICATIONS,
                            'mubashir.saleemi123@gmail.com',
                            'Form Full Application Validation Error',
                            @json_encode(validation_errors())
                        );
                    }
                    //
                    $company_id = $data["session"]["company_detail"]["sid"];
                    $employer_id = $data["session"]["employer_detail"]["sid"];
                    $company_name = $data['session']['company_detail']['CompanyName'];
                    $employer_access_level = $data["session"]["employer_detail"]["access_level"];
                    $full_employment_app_print = $data["session"]["portal_detail"]["full_employment_app_print"];

                    if (!empty($applicant_info)) {
                        if ($applicant_info['employer_sid'] == $company_id) {
                            $data["return_title_heading"] = "Applicant Profile";
                            $data["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                            $data['questions_sent'] = $this->form_full_employment_application_model->check_sent_video_questionnaires($sid, $company_id);
                            $data['questions_answered'] = $this->form_full_employment_application_model->check_answered_video_questionnaires($sid, $company_id);
                            $data['title'] = 'Applicant Full Employment Application';
                            $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                            $data['employer_access_level'] = $employer_access_level;
                            $full_access = false;

                            if ($employer_access_level == 'Admin') {
                                $full_access = true;
                            }

                            $data['company_name'] = $company_name;
                            $data['starting_year_loop'] = 1930;
                            $data['full_access'] = $full_access;
                            $data['left_navigation'] = $left_navigation;
                            $data['company'] = $this->form_full_employment_application_model->get_portal_detail($company_id);
                            $data['full_employment_app_print'] = $full_employment_app_print;
                            // $data['applicant_info'] = $applicant_info;
                            // getting Company accurate backgroud check
                            $data['company_background_check'] = checkCompanyAccurateCheck($data["session"]["company_detail"]["sid"]);
                            //Outsourced HR Compliance and Onboarding check
                            $data['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($data["session"]["company_detail"]["sid"]);

                            if (isset($_POST['action']) && $_POST['action'] == 'true') {
                                $data['formpost'] = $_POST;
                            } else {
                                $data['formpost'] = unserialize($applicant_info['full_employment_application']);
                            }

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
                                'employee_status' => $applicant_info['employee_status'],
                                'employee_type' => $applicant_info['employee_type'],
                                'user_type' => 'Applicant',
                                'referred_by_name' => $applicant_info['referred_by_name'],
                                'referred_by_email' => $applicant_info['referred_by_email'],
                                'dob' => $applicant_info['dob'],
                                'ssn' => $applicant_info['ssn'],
                                'extra_info' => $extras
                            );
                            $data['employer'] = $data_employer;
                            //
                            $birthDate = date('Y-m-d', strtotime('now'));
                            //
                            if (!empty($data["employer"]['dob']) && $data["employer"]['dob'] != '0000-00-00') {
                                $birthDate = $data["employer"]['dob'];
                            }
                            //
                            $birthDate = DateTime::createfromformat('Y-m-d', $birthDate);
                            $todayDate = DateTime::createfromformat('Y-m-d', date('Y-m-d', strtotime('now')));
                            //
                            $data['above18'] = $todayDate->diff($birthDate)->y;
                            //
                            $data['applicant_average_rating'] = $this->form_full_employment_application_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                            //Get Countries and States - Start
                            $data_countries = db_get_active_countries();

                            foreach ($data_countries as $value) {
                                $data_states[$value['sid']] = db_get_active_states($value['sid']);
                            }

                            $data['active_countries'] = $data_countries;
                            $data['active_states'] = $data_states;
                            $data_states_encode = htmlentities(json_encode($data_states));
                            $data['states'] = $data_states_encode;
                            //Get Countries and States - End
                            $ip_track = $this->documents_model->get_document_ip_tracking_record($company_id, 'full_employment_application', 'signed', $sid, 'applicant');
                            $data['ip_track'] = $ip_track;
                            $data['job_list_sid'] = $jobs_listing;
                            $this->load->view('main/header', $data);
                            $this->load->view('manage_employer/full_employment_application');
                            $this->load->view('main/footer');
                        } else {
                            $this->session->set_flashdata('message', '<b>Error:</b> Applicant does not exist in your company! ' . $applicant_info['employer_sid'] . ' = ' . $employer_id);
                            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> Applicant not found!');
                        redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                    }
                } else {

                    $company_sid = $data["session"]["company_detail"]["sid"];
                    $employer_sid = $data["session"]["employer_detail"]["sid"];
                    $formpost = $this->input->post(NULL, TRUE);
                    $reload_location = 'applicant_full_employment_application/' . $sid . '/' . $jobs_listing;
                    $full_employment_application = array();
                    $driving_no = '';
                    $driving_exp = '';

                    foreach ($formpost as $key => $value) {
                        if (
                            $key != 'action' &&
                            $key != 'first_name' &&
                            $key != 'last_name' &&
                            $key != 'email' &&
                            $key != 'Location_Address' &&
                            $key != 'Location_City' &&
                            $key != 'Location_State' &&
                            $key != 'Location_Country' &&
                            $key != 'Location_ZipCode' &&
                            $key != 'PhoneNumber' &&
                            $key != 'txt_phonenumber' &&
                            $key != 'txt_mobilenumber' &&
                            $key != 'txt_othernumber' &&
                            $key != 'txt_employeenumber_one' &&
                            $key != 'txt_employeenumber_two' &&
                            $key != 'txt_employeenumber_three' &&
                            $key != 'txt_referencenumber_one' &&
                            $key != 'txt_referencenumber_two' &&
                            $key != 'txt_referencenumber_three'
                        ) { // exclude these values from array
                            $full_employment_application[$key] = $value;
                            if ($key == 'TextBoxDriversLicenseNumber') {
                                $driving_no = $value;
                            } elseif ($key == 'TextBoxDriversLicenseExpiration') {
                                $driving_exp = $value;
                            }
                        }
                    }

                    $full_employment_application['client_ip'] = getUserIP();
                    $full_employment_application['client_signature_timestamp'] = date('Y-m-d H:i:s');

                    // Reset phone numbers
                    $full_employment_application['TextBoxTelephoneMobile'] = isset($formpost['txt_mobilenumber']) ? $formpost['txt_mobilenumber'] : $full_employment_application['TextBoxTelephoneMobile'];
                    $full_employment_application['TextBoxTelephoneOther']  = isset($formpost['txt_othernumber']) ? $formpost['txt_othernumber'] : $full_employment_application['TextBoxTelephoneOther'];
                    $full_employment_application['TextBoxEmploymentEmployerPhoneNumber1'] = isset($formpost['txt_employeenumber_one']) ? $formpost['txt_employeenumber_one'] : $full_employment_application['TextBoxEmploymentEmployerPhoneNumber1'];
                    $full_employment_application['TextBoxEmploymentEmployerPhoneNumber2'] = isset($formpost['txt_employeenumber_two']) ? $formpost['txt_employeenumber_two'] : $full_employment_application['TextBoxEmploymentEmployerPhoneNumber2'];
                    $full_employment_application['TextBoxEmploymentEmployerPhoneNumber3'] = isset($formpost['txt_employeenumber_three']) ? $formpost['txt_employeenumber_three'] : $full_employment_application['TextBoxEmploymentEmployerPhoneNumber3'];
                    $full_employment_application['TextBoxReferenceTelephoneNumber1'] = isset($formpost['txt_referencenumber_one']) ? $formpost['txt_referencenumber_one'] : $full_employment_application['TextBoxReferenceTelephoneNumber1'];
                    $full_employment_application['TextBoxReferenceTelephoneNumber2'] = isset($formpost['txt_referencenumber_two']) ? $formpost['txt_referencenumber_two'] : $full_employment_application['TextBoxReferenceTelephoneNumber2'];
                    $full_employment_application['TextBoxReferenceTelephoneNumber3'] = isset($formpost['txt_referencenumber_three']) ? $formpost['txt_referencenumber_three'] : $full_employment_application['TextBoxReferenceTelephoneNumber3'];
                    $drive_data = $data['drivers_license_details'];
                    if (!empty($driving_no)) {
                        $drive_data['license_number'] = $driving_no;
                    }
                    if (!empty($driving_exp)) {
                        $drive_data['license_expiration_date'] = $driving_exp;
                    }
                    // Only update driver license info if
                    // the logged inuser is PP or ALP
                    if ($_ssv) {
                        if (preg_match(XSYM_PREG, $full_employment_application['TextBoxDriversLicenseNumber'])) $full_employment_application['TextBoxDriversLicenseNumber'] = $fullEmployementForm['TextBoxDriversLicenseNumber'];
                        if (preg_match(XSYM_PREG, $full_employment_application['TextBoxDriversLicenseExpiration'])) $full_employment_application['TextBoxDriversLicenseExpiration'] = $fullEmployementForm['TextBoxDriversLicenseExpiration'];
                        if (preg_match(XSYM_PREG, $full_employment_application['TextBoxSSN'])) $full_employment_application['TextBoxSSN'] = $fullEmployementForm['TextBoxSSN'];
                        if (preg_match(XSYM_PREG, $full_employment_application['TextBoxDOB'])) $full_employment_application['TextBoxDOB'] = $fullEmployementForm['TextBoxDOB'];
                        //
                        if (preg_match(XSYM_PREG, $driving_no)) $drive_data['license_number'] =  $data['drivers_license_details']['license_number'];
                        if (preg_match(XSYM_PREG, $driving_exp)) $drive_data['license_expiration_date'] =  $data['drivers_license_details']['license_expiration_date'];
                    }
                    //
                    if (!empty($driving_no) || !empty($driving_exp)) {
                        if (sizeof($drivers_license)) {
                            $drivers_license_serial_data['license_details'] = serialize($drive_data);
                            $this->form_full_employment_application_model->update_license_info($drivers_license['sid'], $drivers_license_serial_data);
                        } else {
                            $drivers_license = array();
                            $drivers_license['users_sid'] = $sid;
                            $drivers_license['users_type'] = 'applicant';
                            $drivers_license['license_type'] = 'drivers';
                            $drivers_license['license_details'] = serialize($drive_data);
                            $this->form_full_employment_application_model->save_license_info($drivers_license);
                        }
                    }
                    $this->documents_model->insert_document_ip_tracking_record($company_sid, $employer_sid, getUserIP(), 'full_employment_application', 'pre_filled', $_SERVER['HTTP_USER_AGENT'], $sid, 'applicant');
                    $id = $formpost['sid'];
                    $extras['other_email'] = $formpost['TextBoxAddressStreetFormer3'];
                    $extras['other_PhoneNumber'] = $formpost['TextBoxTelephoneOther'];
                    $data = array(
                        'first_name' => $formpost['first_name'],
                        'last_name' => $formpost['last_name'],
                        'email' => $formpost['email'],
                        'address' => $formpost['Location_Address'],
                        'city' => $formpost['Location_City'],
                        'state' => $formpost['Location_State'],
                        'country' => $formpost['Location_Country'],
                        'zipcode' => $formpost['Location_ZipCode'],
                        'phone_number' => $formpost['PhoneNumber'],
                        'referred_by_name' => $full_employment_application['TextBoxReferenceName1'],
                        'referred_by_email' => $full_employment_application['TextBoxReferenceEmail1'],
                        'extra_info' => serialize($extras),
                        'full_employment_application' => serialize($full_employment_application)
                    );
                    //
                    if (isset($formpost['TextBoxDOB']) && !empty($formpost['TextBoxDOB'])) {
                        $DOB = date('Y-m-d', strtotime(str_replace('-', '/', $formpost['TextBoxDOB'])));
                        $data['dob'] = $DOB;
                    }
                    //
                    if (isset($formpost['TextBoxNameMiddle']) && !empty($formpost['TextBoxNameMiddle'])) {
                        $data['middle_name'] = $formpost['TextBoxNameMiddle'];
                    }
                    //
                    if (isset($formpost['TextBoxSSN']) && !empty($formpost['TextBoxSSN'])) {
                        $data['ssn'] = $formpost['TextBoxSSN'];
                    }
                    //

                    //
                    if (!$_ssv && !empty($formpost['TextBoxDOB'])) $data['dob'] = DateTime::createFromFormat('m-d-Y', $formpost['TextBoxDOB'])->format('Y-d-m');

                    $this->form_full_employment_application_model->update_applicant($id, $data);
                    $this->form_full_employment_application_model->update_applicant_form_status($sid, $company_sid, 'applicant', 'signed');
                    $this->session->set_flashdata('message', '<b>Success:</b> Full employment form updated successfully');
                    redirect($reload_location, "location");
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Applicant not found!');
                redirect('application_tracking_system/active/all/all/all/all', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function match_confirm_email($email, $sid, $type)
    {
        $user_email = $this->form_full_employment_application_model->get_user_email_address($sid, $type);

        $result = "not_matched";

        if (strtolower($user_email) == strtolower($email)) {
            $result = "matched";
        }
        echo $result;
        exit(0);
    }

    //
    public function check_age($TextBoxDOB)
    {
        if (underAge(formatDateToDB($TextBoxDOB, 'm/d/Y', DB_DATE))) {
            $this->form_validation->set_message('check_age', UNDER_AGE_MESSAGE);
            return false;
        } else {
            return true;
        }
    }
}
