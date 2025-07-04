<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Hire_applicant extends Public_Controller {
    //private $security_details;
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'), "refresh");
        }
        //$data['session']                                                        = $this->session->userdata('logged_in');
        //$security_sid                                                           = $data['session']['employer_detail']['sid'];
        //$security_details                                                       = db_get_access_level_details($security_sid);
        //$data['security_details']                                               = $security_details; 
        //check_access_permissions($security_details, 'my_settings', 'hire_applicant'); // Param2: Redirect URL, Param3: Function Name
    }

    /**
     * Hire Applicant function will move applicant data and convert his type from employee
     */
    function hire_applicant() {
        $sid                                                                    = $_POST['id'];
        $email                                                                  = $_POST['email'];
        $job_sid                                                                = $_POST['job_sid'];
        $company_sid                                                            = $_POST['cid'];
        $action                                                                 = $_POST['action'];
        $get_company_detail                                                     = $this->session->userdata('logged_in');
        $company_detail                                                         = $get_company_detail["company_detail"];
        $company_name                                                           = $company_detail['CompanyName'];
        /*
        Step No:1, Check if this email address is already registered in the company.
        */
        $applicant_exists                                                       = db_check_email_exists($company_sid, $email);
        
        if ($applicant_exists == 'success') { // The applicant can be added to company as employee
            $applicant_profile_info                                             = db_get_applicant_profile($sid);
            
            if (empty($applicant_profile_info)) {
                    $array[0]                                                   = "error";
                    $array[1]                                                   = "Error! Could not hire applicant, Please try again!";
                    echo json_encode($array);
                    exit();
            } else {
                $employer_data = array();
                $employer_data['first_name']                                    = $applicant_profile_info['first_name'];
                $employer_data['last_name']                                     = $applicant_profile_info['last_name'];
                $employer_data['active']                                        = 1;
                $employer_data['registration_date']                             = date('Y-m-d H:i:s');
                $employer_data['access_level']                                  = 'Employee';
                $employer_data['profile_picture']                               = $applicant_profile_info['pictures'];
                $employer_data['full_employment_application']                   = $applicant_profile_info['full_employment_application'];
                $employer_data['Location_Country']                              = $applicant_profile_info['country'];
                $employer_data['Location_State']                                = $applicant_profile_info['state'];
                $employer_data['Location_City']                                 = $applicant_profile_info['city'];
                $employer_data['Location_Address']                              = $applicant_profile_info['address'];
                $employer_data['PhoneNumber']                                   = $applicant_profile_info['phone_number'];
                $employer_data['CompanyName']                                   = $company_name;
                $employer_data['Location_ZipCode']                              = $applicant_profile_info['zipcode'];
                $employer_data['YouTubeVideo']                                  = $applicant_profile_info['YouTube_Video'];
                $employer_data['job_title']                                     = $applicant_profile_info['desired_job_title'];
                $employer_data['resume']                                        = $applicant_profile_info['resume'];
                $employer_data['cover_letter']                                  = $applicant_profile_info['cover_letter'];
                $employer_data['applicant_sid']                                 = $sid;
                $employer_data['email']                                         = $email;
                $employer_data['parent_sid']                                    = $company_sid;
                $employer_data['active']                                        = 0;
                $employer_data['extra_info']                                    = $applicant_profile_info['extra_info'];
                $employer_data['linkedin_profile_url']                          = $applicant_profile_info['linkedin_profile_url'];
                
                // insert employer data to table and get its ID
                $employee_result                                                = db_insert_company_employee($employer_data, $sid, $job_sid);
                
                if ($employee_result == 'error') { // There was some issue.
                    $array[0]                                                   = "error";
                    $array[1]                                                   = "Error! Could not hire applicant, Please try again!";
                    $array[2]                                                   = "applicant_profile/" . $sid;
                    echo json_encode($array);
                } else { // now move all other information 
                    // 1) emergency_contacts
                    $applicant_emergency_contacts                               = db_get_applicant_emergency_contacts($sid, $employee_result);
                    // 2) equipment_information
                    $applicant_equipment_information                            = db_get_applicant_equipment_information($sid, $employee_result);
                    // 3) dependant_information
                    $applicant_dependant_information                            = db_get_applicant_dependant_information($sid, $employee_result);
                    // 4) license_information
                    $applicant_license_information                              = db_get_applicant_license_information($sid, $employee_result);
                    // 5) background_check_orderss
                    $applicant_background_check                                 = db_get_applicant_background_check($sid, $employee_result);
                    // 6) portal_misc_notes
                    $applicant_misc_notes                                       = db_get_applicant_misc_notes($sid, $employee_result);
                    // 7) private_message
                    $applicant_private_message                                  = db_get_applicant_private_message($email, $employee_result);
                    // 8) portal_applicant_rating
                    $applicant_rating                                           = db_get_applicant_rating($sid, $employee_result);
                    // 9) calendar events - portal_schedule_event
                    $applicant_schedule_event                                   = db_get_applicant_schedule_event($sid, $employee_result);
                    // 9) background check orders
                    $applicant_schedule_event                                   = db_get_applicant_license_information($sid, $employee_result);
                    // 10) portal_applicant_attachments
                    $applicant_applicant_attachments                            = db_get_applicant_attachments($sid, $employee_result);
                    // 11) reference_checks
                    $applicant_reference_checks                                 = db_get_reference_checks($sid, $employee_result);

                    $this->session->set_flashdata('message', '<b>Success:</b> Applicant is successfully hired!');
                    $array[0]                                                   = "success";
                    $array[1]                                                   = "Success! Applicant is successfully hired!";
                    $array[2]                                                   = base_url("send_offer_letter_documents/" . $employee_result);
                    echo json_encode($array);
                }
            }
        } else { // Applicant can't move to company
            $array[0]                                                           = "error";
            $array[1]                                                           = "Error! The E-Mail address of the applicant is already registered at your company as employee!";
            echo json_encode($array);
        }
        exit;
    }
}