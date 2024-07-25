<?php defined('BASEPATH') or exit('No direct script access allowed');

class Hire_onboarding_applicant_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function check_email_exists($sid, $email)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->where('email', $email);
        $this->db->from('users');
        $result = $this->db->get()->result_array();
        if (empty($result)) { // email not registered under this company account
            // now check if this email exist against any employee on current company
            $this->db->select('*');
            $this->db->where('parent_sid', $sid);
            $this->db->where('email', $email);
            $this->db->from('users');
            $result2 = $this->db->get()->result_array();
            if (empty($result2)) { // Email address is not found. It can be used for registration purpose
                return 'success';
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }
    }

    function get_applicant_profile($sid)
    {
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.address');
        $this->db->select('portal_job_applications.country');
        $this->db->select('portal_job_applications.city');
        $this->db->select('portal_job_applications.state');
        $this->db->select('portal_job_applications.zipcode');
        $this->db->select('portal_job_applications.resume');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.YouTube_Video');
        $this->db->select('portal_job_applications.full_employment_application');
        $this->db->select('portal_job_applications.hired_sid');
        $this->db->select('portal_job_applications.hired_status');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.verification_key');
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.extra_info');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_job_applications.job_fit_category_sid');
        $this->db->select('portal_job_applications.ssn');
        $this->db->select('portal_job_applications.dob');
        $this->db->select('portal_job_applications.employee_status');
        $this->db->select('portal_job_applications.employee_type');
        $this->db->select('portal_job_applications.marital_status');
        $this->db->select('portal_job_applications.gender');
        $this->db->select('portal_job_applications.ip_address');
        $this->db->select('portal_job_applications.video_type');
        $this->db->select('portal_job_applications.middle_name');
        $this->db->select('portal_job_applications.nick_name');
        $this->db->select('portal_job_applications.languages_speak');
        $this->db->select('portal_job_applications.salary_benefits');
        $this->db->select('portal_job_applications.workers_compensation_code');
        $this->db->select('portal_job_applications.eeoc_code');
        $this->db->select('portal_job_applications.uniform_top_size');
        $this->db->select('portal_job_applications.uniform_bottom_size');
        $this->db->select('portal_job_applications.employee_number');

        //

        $this->db->select('portal_applicant_jobs_list.*, portal_applicant_jobs_list.sid as portal_applicant_jobs_list_sid');

        $this->db->join('portal_applicant_jobs_list', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');

        $this->db->where('portal_job_applications.sid', $sid);

        $result = $this->db->get('portal_job_applications')->result_array();

        if (empty($result)) { // applicant does not exits
            return 'error';
        } else {
            return $result[0];
        }
    }

    function insert_company_employee($employer_data, $sid, $hired_job_sid = 0)
    {
        $this->db->insert('users', $employer_data);
        $user_id = $this->db->insert_id(); // check if insert was successful


        //
        if ($employer_data['department_sid'] != 0 && $employer_data['team_sid'] != 0) {
            $team_information['department_sid'] = $employer_data['department_sid'];
            $team_information['team_sid'] = $employer_data['team_sid'];
            $team_information['employee_sid'] = $user_id;
            $team_information['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('departments_employee_2_team', $team_information);
        }




        if ($this->db->affected_rows() == '1') { // now update applications table
            $this->db->where('sid', $sid);
            $data_applicant = array(
                'hired_sid' => $user_id,
                'hired_status' => '1',
                'hired_date' => date('Y-m-d'),
                'hired_job_sid' => $hired_job_sid,
                'is_onboarding' => 0
            );

            $this->db->update('portal_job_applications', $data_applicant);
            $this->db->trans_complete();
            // was there any update or error?
            if ($this->db->affected_rows() == '1') {
                return $user_id;
            } else {
                if ($this->db->trans_status() === FALSE) {
                    return 'error';
                }
                return $user_id;
            }
        } else {
            return 'error';
        }
    }

    function get_applicant_emergency_contacts($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('emergency_contacts')->result_array();
        if (count($result) > 0) {
            foreach ($result as $employee) {
                $insert_emergency_contact = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'first_name' => $employee['first_name'],
                    'last_name' => $employee['last_name'],
                    'email' => $employee['email'],
                    'Location_Country' => $employee['Location_Country'],
                    'Location_State' => $employee['Location_State'],
                    'Location_City' => $employee['Location_City'],
                    'Location_ZipCode' => $employee['Location_ZipCode'],
                    'Location_Address' => $employee['Location_Address'],
                    'PhoneNumber' => $employee['PhoneNumber'],
                    'Relationship' => $employee['Relationship'],
                    'priority' => $employee['priority']
                );
                $this->db->insert('emergency_contacts', $insert_emergency_contact);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_emergency_contacts($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('users_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('emergency_contacts')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('emergency_contacts')->result_array();
        if (count($result) > 0) {
            foreach ($result as $employee) {
                $insert_emergency_contact = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'first_name' => $employee['first_name'],
                    'last_name' => $employee['last_name'],
                    'email' => $employee['email'],
                    'Location_Country' => $employee['Location_Country'],
                    'Location_State' => $employee['Location_State'],
                    'Location_City' => $employee['Location_City'],
                    'Location_ZipCode' => $employee['Location_ZipCode'],
                    'Location_Address' => $employee['Location_Address'],
                    'PhoneNumber' => $employee['PhoneNumber'],
                    'Relationship' => $employee['Relationship'],
                    'priority' => $employee['priority']
                );
                $this->db->insert('emergency_contacts', $insert_emergency_contact);
            }
            return $result;
        } else {
            return array();
        }
        //        }
    }

    function get_applicant_equipment_information($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('equipment_information')->result_array();
        if (count($result) > 0) {
            foreach ($result as $equipment_information) {
                $insert_equipment_information = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'equipment_details' => $equipment_information['equipment_details']
                );
                $this->db->insert('equipment_information', $insert_equipment_information);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_equipment_information($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('users_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('equipment_information')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('equipment_information')->result_array();
        if (count($result) > 0) {
            foreach ($result as $equipment_information) {
                $insert_equipment_information = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'equipment_type' => $equipment_information['equipment_type'],
                    'brand_name' => $equipment_information['brand_name'],
                    'imei_no' => $equipment_information['imei_no'],
                    'model' => $equipment_information['model'],
                    'issue_date' => $equipment_information['issue_date'],
                    'color' => $equipment_information['color'],
                    'notes' => $equipment_information['notes']
                );
                $this->db->insert('equipment_information', $insert_equipment_information);
            }
            return $result;
        } else {
            return array();
        }
        //        }
    }

    function get_applicant_dependant_information($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('dependant_information')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                https: //www.youtube.com/watch?v=eUhB6kmy0_E
                $insert_dependant_information = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'company_sid' => $info['company_sid'],
                    'dependant_details' => $info['dependant_details']
                );
                $this->db->insert('dependant_information', $insert_dependant_information);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_dependant_information($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('users_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('dependant_information')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('dependant_information')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_dependant_information = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'company_sid' => $info['company_sid'],
                    'dependant_details' => $info['dependant_details']
                );
                $this->db->insert('dependant_information', $insert_dependant_information);
            }
            return $result;
        } else {
            return array();
        }
        //        }
    }

    function get_applicant_license_information($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('license_information')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_license_information = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'license_type' => $info['license_type'],
                    'license_details' => $info['license_details'],
                    'license_file' => $info['license_file']
                );
                $this->db->insert('license_information', $insert_license_information);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_license_information($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('users_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('license_information')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('license_information')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_license_information = array(
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'license_type' => $info['license_type'],
                    'license_details' => $info['license_details']
                );
                $this->db->insert('license_information', $insert_license_information);
            }
            return $result;
        } else {
            return array();
        }
        //        }
    }

    function get_applicant_background_check($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('background_check_orders')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_background_check = array(
                    'employer_sid' => $info['employer_sid'],
                    'company_sid' => $info['company_sid'],
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'product_sid' => $info['product_sid'],
                    'package_id' => $info['package_id'],
                    'package_response' => $info['package_response'],
                    'order_response' => $info['order_response'],
                    'order_response_status' => $info['order_response_status'],
                    'date_applied' => $info['date_applied'],
                    'date_response_received' => $info['date_response_received'],
                    'product_price' => $info['product_price'],
                    'product_name' => $info['product_name'],
                    'product_type' => $info['product_type'],
                    'product_image' => $info['product_image']
                );
                $this->db->insert('background_check_orders', $insert_background_check);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_background_check($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('users_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('background_check_orders')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('background_check_orders')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_background_check = array(
                    'employer_sid' => $info['employer_sid'],
                    'company_sid' => $info['company_sid'],
                    'users_sid' => $hired_id,
                    'users_type' => 'employee',
                    'product_sid' => $info['product_sid'],
                    'package_id' => $info['package_id'],
                    'package_response' => $info['package_response'],
                    'order_response' => $info['order_response'],
                    'order_response_status' => $info['order_response_status'],
                    'date_applied' => $info['date_applied'],
                    'date_response_received' => $info['date_response_received'],
                    'product_price' => $info['product_price'],
                    'product_name' => $info['product_name'],
                    'product_type' => $info['product_type'],
                    'product_image' => $info['product_image']
                );
                $this->db->insert('background_check_orders', $insert_background_check);
            }
            return $result;
        } else {
            return 0;
        }
        //        }
    }

    function get_applicant_misc_notes($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('portal_misc_notes')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_misc_notes = array(
                    'employers_sid' => $info['employers_sid'],
                    'applicant_job_sid' => $hired_id,
                    'users_type' => 'employee',
                    'applicant_email' => $info['applicant_email'],
                    'notes' => $info['notes'],
                    // 'attachments' => $info['attachments'],
                    // 'attachment_name' => $info['attachment_name'],
                    'insert_date' => $info['insert_date'],
                    'modified_date' => $info['modified_date']
                );
                $this->db->insert('portal_misc_notes', $insert_misc_notes);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_misc_notes($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('applicant_job_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('portal_misc_notes')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('portal_misc_notes')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_misc_notes = array(
                    'employers_sid' => $info['employers_sid'],
                    'applicant_job_sid' => $hired_id,
                    'users_type' => 'employee',
                    'applicant_email' => $info['applicant_email'],
                    'notes' => $info['notes'],
                    // 'attachments' => $info['attachments'],
                    // 'attachment_name' => $info['attachment_name'],
                    'insert_date' => $info['insert_date'],
                    'modified_date' => $info['modified_date']
                );
                $this->db->insert('portal_misc_notes', $insert_misc_notes);
            }
            return $result;
        } else {
            return 0;
        }
        //        }

    }

    function get_applicant_private_message($email, $hired_id, $applicant_sid)
    {

        $this->db->select('*');
        $this->db->where('to_id', $email);
        $this->db->where('job_id', $applicant_sid);
        $result = $this->db->get('private_message')->result_array();

        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_private_message = array(
                    'from_id' => $info['from_id'],
                    'to_id' => $hired_id,
                    'from_type' => 'employer',
                    'to_type' => 'employer',
                    'users_type' => 'employee',
                    'date' => $info['date'],
                    'status' => $info['status'],
                    'subject' => $info['subject'],
                    'message' => $info['message'],
                    'outbox' => $info['outbox'],
                    'anonym' => $info['anonym'],
                    'attachment' => $info['attachment'],
                    'job_id' => ''
                );
                $this->db->insert('private_message', $insert_private_message);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_applicant_private_message($email, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('to_id', $email);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('private_message')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_private_message = array(
                    'from_id' => $info['from_id'],
                    'to_id' => $hired_id,
                    'from_type' => 'employer',
                    'to_type' => 'employer',
                    'users_type' => 'employee',
                    'date' => $info['date'],
                    'status' => $info['status'],
                    'subject' => $info['subject'],
                    'message' => $info['message'],
                    'outbox' => $info['outbox'],
                    'anonym' => $info['anonym'],
                    'attachment' => $info['attachment'],
                    'job_id' => ''
                );
                $this->db->insert('private_message', $insert_private_message);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function get_applicant_rating($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('portal_applicant_rating')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_applicant_rating = array(
                    'company_sid' => $info['company_sid'],
                    'employer_sid' => $info['employer_sid'],
                    'users_type' => 'employee',
                    'applicant_job_sid' => $hired_id,
                    'applicant_email' => $info['applicant_email'],
                    'rating' => $info['rating'],
                    'comment' => $info['comment'],
                    'date_added' => $info['date_added'],
                    'attachment' => $info['attachment'],
                    'attachment_extension' => $info['attachment_extension']
                );
                $this->db->insert('portal_applicant_rating', $insert_applicant_rating);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_rating($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('applicant_job_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('portal_applicant_rating')->result_array();
        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('portal_applicant_rating')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_applicant_rating = array(
                    'company_sid' => $info['company_sid'],
                    'employer_sid' => $info['employer_sid'],
                    'users_type' => 'employee',
                    'applicant_job_sid' => $hired_id,
                    'applicant_email' => $info['applicant_email'],
                    'rating' => $info['rating'],
                    'comment' => $info['comment'],
                    'date_added' => $info['date_added'],
                    'attachment' => $info['attachment'],
                    'attachment_extension' => $info['attachment_extension']
                );
                $this->db->insert('portal_applicant_rating', $insert_applicant_rating);
            }
            return $result;
        } else {
            return 0;
        }
        //        }
    }

    function get_applicant_schedule_event($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('portal_schedule_event')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_schedule_event = array(
                    'companys_sid' => $info['companys_sid'],
                    'employers_sid' => $info['employers_sid'],
                    'applicant_email' => $info['applicant_email'],
                    'applicant_job_sid' => $hired_id,
                    'users_type' => 'employee',
                    'title' => $info['title'],
                    'category' => $info['category'],
                    'date' => $info['date'],
                    'description' => $info['description'],
                    'eventstarttime' => $info['eventstarttime'],
                    'eventendtime' => $info['eventendtime'],
                    'interviewer' => $info['interviewer'],
                    'commentCheck' => $info['commentCheck'],
                    'messageCheck' => $info['messageCheck'],
                    'comment' => $info['comment'],
                    'subject' => $info['subject'],
                    'message' => $info['message'],
                    'messageFile' => $info['messageFile'],
                    'address' => $info['address'],
                    'goToMeetingCheck' => $info['goToMeetingCheck'],
                    'meetingId' => $info['meetingId'],
                    'meetingCallNumber' => $info['meetingCallNumber'],
                    'meetingURL' => $info['meetingURL'],
                    'created_on' => $info['created_on']
                );
                $this->db->insert('portal_schedule_event', $insert_schedule_event);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_schedule_event($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('applicant_job_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('portal_schedule_event')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('portal_schedule_event')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_schedule_event = array(
                    'companys_sid' => $info['companys_sid'],
                    'employers_sid' => $info['employers_sid'],
                    'applicant_email' => $info['applicant_email'],
                    'applicant_job_sid' => $hired_id,
                    'users_type' => 'employee',
                    'title' => $info['title'],
                    'category' => $info['category'],
                    'date' => $info['date'],
                    'description' => $info['description'],
                    'eventstarttime' => $info['eventstarttime'],
                    'eventendtime' => $info['eventendtime'],
                    'interviewer' => $info['interviewer'],
                    'commentCheck' => $info['commentCheck'],
                    'messageCheck' => $info['messageCheck'],
                    'comment' => $info['comment'],
                    'subject' => $info['subject'],
                    'message' => $info['message'],
                    'messageFile' => $info['messageFile'],
                    'address' => $info['address'],
                    'goToMeetingCheck' => $info['goToMeetingCheck'],
                    'meetingId' => $info['meetingId'],
                    'meetingCallNumber' => $info['meetingCallNumber'],
                    'meetingURL' => $info['meetingURL'],
                    'created_on' => $info['created_on']
                );
                $this->db->insert('portal_schedule_event', $insert_schedule_event);
            }
            return $result;
        } else {
            return 0;
        }
        //        }
    }

    function get_applicant_background_check_order($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('background_check_orders')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $info['sid'] = 'NULL';
                $info['users_sid'] = $hired_id;
                $info['users_type'] = 'employee';
                $this->db->insert('background_check_orders', $info);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_background_check_order($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('users_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('background_check_orders')->result_array();
        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('users_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('background_check_orders')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $info['sid'] = 'NULL';
                $info['users_sid'] = $hired_id;
                $info['users_type'] = 'employee';
                $this->db->insert('background_check_orders', $info);
            }
            return $result;
        } else {
            return 0;
        }
        //        }
    }

    function get_applicant_attachments($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('portal_applicant_attachments')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_applicant_attachments = array(
                    'employer_sid' => $info['employer_sid'],
                    'applicant_job_sid' => $hired_id,
                    'users_type' => 'employee',
                    'original_name' => $info['original_name'],
                    'uploaded_name' => $info['uploaded_name'],
                    'date_uploaded' => $info['date_uploaded']
                );
                $this->db->insert('portal_applicant_attachments', $insert_applicant_attachments);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_attachments($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('applicant_job_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('portal_applicant_attachments')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('portal_applicant_attachments')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_applicant_attachments = array(
                    'employer_sid' => $info['employer_sid'],
                    'applicant_job_sid' => $hired_id,
                    'users_type' => 'employee',
                    'original_name' => $info['original_name'],
                    'uploaded_name' => $info['uploaded_name'],
                    'date_uploaded' => $info['date_uploaded']
                );
                $this->db->insert('portal_applicant_attachments', $insert_applicant_attachments);
            }
            return $result;
        } else {
            return 0;
        }
        //        }
    }

    function get_reference_checks($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('reference_checks')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_reference_checks = array(
                    'company_sid' => $info['company_sid'],
                    'user_sid' => $hired_id,
                    'users_type' => 'employee',
                    'organization_name' => $info['organization_name'],
                    'department_name' => $info['department_name'],
                    'branch_name' => $info['branch_name'],
                    'program_name' => $info['program_name'],
                    'period_start' => $info['period_start'],
                    'period_end' => $info['period_end'],
                    'period' => $info['period'],
                    'reference_type' => $info['reference_type'],
                    'reference_name' => $info['reference_name'],
                    'reference_title' => $info['reference_title'],
                    'reference_relation' => $info['reference_relation'],
                    'reference_email' => $info['reference_email'],
                    'reference_phone' => $info['reference_phone'],
                    'best_time_to_call' => $info['best_time_to_call'],
                    'other_information' => $info['other_information'],
                    'questionnaire_information' => $info['questionnaire_information'],
                    'questionnaire_conducted_by' => $info['questionnaire_conducted_by'],
                    'verified' => $info['verified'],
                    'status' => $info['status']
                );
                $this->db->insert('reference_checks', $insert_reference_checks);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_reference_checks($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('user_sid', $hired_id);
        //        $this->db->where('users_type', 'employee');
        //        $result = $this->db->get('reference_checks')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('users_type', 'applicant');
        $result = $this->db->get('reference_checks')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                $insert_reference_checks = array(
                    'company_sid' => $info['company_sid'],
                    'user_sid' => $hired_id,
                    'users_type' => 'employee',
                    'organization_name' => $info['organization_name'],
                    'department_name' => $info['department_name'],
                    'branch_name' => $info['branch_name'],
                    'program_name' => $info['program_name'],
                    'period_start' => $info['period_start'],
                    'period_end' => $info['period_end'],
                    'period' => $info['period'],
                    'reference_type' => $info['reference_type'],
                    'reference_name' => $info['reference_name'],
                    'reference_title' => $info['reference_title'],
                    'reference_relation' => $info['reference_relation'],
                    'reference_email' => $info['reference_email'],
                    'reference_phone' => $info['reference_phone'],
                    'best_time_to_call' => $info['best_time_to_call'],
                    'other_information' => $info['other_information'],
                    'questionnaire_information' => $info['questionnaire_information'],
                    'questionnaire_conducted_by' => $info['questionnaire_conducted_by'],
                    'verified' => $info['verified'],
                    'status' => $info['status']
                );
                $this->db->insert('reference_checks', $insert_reference_checks);
            }
            return $result;
        } else {
            return 0;
        }
        //        }
    }

    function get_onboarding_configuration($sid, $hired_sid)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('user_type', 'applicant');
        $records_obj = $this->db->get('onboarding_applicants_configuration');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $record) {
                unset($record['sid']);
                $record['user_sid'] = $hired_sid;
                $record['user_type'] = 'employee';
                $record['employer_sid'] = 0;

                $this->db->insert('onboarding_applicants_configuration', $record);
            }

            return $records_arr;
        } else {
            return 0;
        }
    }

    function update_onboarding_configuration($sid, $hired_sid)
    {
        //        $this->db->select('sid');
        //        $this->db->where('user_sid', $hired_sid);
        //        $this->db->where('user_type', 'employee');
        //        $records_obj = $this->db->get('onboarding_applicants_configuration')->result_array();

        //        if(sizeof($records_obj) == 0){
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('user_type', 'applicant');
        $records_obj = $this->db->get('onboarding_applicants_configuration');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $record) {
                unset($record['sid']);
                $record['user_sid'] = $hired_sid;
                $record['user_type'] = 'employee';
                $record['employer_sid'] = 0;

                $this->db->insert('onboarding_applicants_configuration', $record);
            }

            return $records_arr;
        } else {
            return 0;
        }
        //        }
    }

    function get_documents($sid, $hired_sid)
    {
        //Fillable Forms
        //W4
        $this->db->select('*');
        $this->db->where('employer_sid', $sid);
        $this->db->where('user_type', 'applicant');
        $records_obj = $this->db->get('form_w4_original');
        $forms = $records_obj->result_array();
        $records_obj->free_result();
        if (!empty($forms)) {
            $w4OldSid = $forms[0]['sid'];

            unset($forms[0]['sid']);
            $forms[0]['employer_sid'] = $hired_sid;
            $forms[0]['user_type'] = 'employee';
            $this->db->insert('form_w4_original', $forms[0]);
            $newW4Id = $this->db->insert_id();

            //W4 History
            $this->db->select('*');
            $this->db->where('form_w4_ref_sid', $w4OldSid);
            $records_obj = $this->db->get('form_w4_original_history');
            $formsW4History = $records_obj->result_array();
            if (!empty($formsW4History)) {
                foreach ($formsW4History as $formsOldRow) {
                    unset($formsOldRow['sid']);
                    $formsOldRow['form_w4_ref_sid'] = $newW4Id;
                    $formsOldRow['employer_sid'] = $hired_sid;
                    $formsOldRow['user_type'] = 'employee';
                    $this->db->insert('form_w4_original_history', $formsOldRow);
                }
            }

            // W4 Trail
            $this->db->select('*');
            $this->db->where('document_sid', $w4OldSid);
            $this->db->where('document_type', 'w4');
            $records_obj = $this->db->get('verification_documents_track');
            $formsW4Trail = $records_obj->result_array();

            if (!empty($formsW4Trail)) {
                foreach ($formsW4Trail as $formsOldTrailRow) {
                    unset($formsOldTrailRow['sid']);
                    $formsOldTrailRow['document_sid'] = $newW4Id;
                    $this->db->insert('verification_documents_track', $formsOldTrailRow);
                }
            }
        }

        //W9
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('user_type', 'applicant');
        $records_obj = $this->db->get('applicant_w9form');
        $forms = $records_obj->result_array();
        $records_obj->free_result();
        if (!empty($forms)) {

            $w9OldSid = $forms[0]['sid'];

            unset($forms[0]['sid']);
            $forms[0]['user_sid'] = $hired_sid;
            $forms[0]['user_type'] = 'employee';
            $this->db->insert('applicant_w9form', $forms[0]);
            $newW9Id = $this->db->insert_id();

            //W9 Old History
            $this->db->select('*');
            $this->db->where('w9form_ref_sid', $w9OldSid);
            $records_obj = $this->db->get('applicant_w9form_history');
            $formsW9History = $records_obj->result_array();

            if (!empty($formsW9History)) {
                foreach ($formsW9History as $formsOldRow) {
                    unset($formsOldRow['sid']);
                    $formsOldRow['w9form_ref_sid'] = $newW9Id;
                    $formsOldRow['user_sid'] = $hired_sid;
                    $formsOldRow['user_type'] = 'employee';
                    $this->db->insert('applicant_w9form_history', $formsOldRow);
                }
            }

            // W9 Trail
            $this->db->select('*');
            $this->db->where('document_sid', $w9OldSid);
            $this->db->where('document_type', 'w9');
            $records_obj = $this->db->get('verification_documents_track');
            $formsW9Trail = $records_obj->result_array();

            if (!empty($formsW9Trail)) {
                foreach ($formsW9Trail as $formsOldTrailRow) {
                    unset($formsOldTrailRow['sid']);
                    $formsOldTrailRow['document_sid'] = $newW9Id;
                    $this->db->insert('verification_documents_track', $formsOldTrailRow);
                }
            }
        }

        //I9
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('user_type', 'applicant');
        $records_obj = $this->db->get('applicant_i9form');
        $forms = $records_obj->result_array();
        $records_obj->free_result();
        if (!empty($forms)) {

            $I9OldSid = $forms[0]['sid'];

            unset($forms[0]['sid']);
            $forms[0]['user_sid'] = $hired_sid;
            $forms[0]['user_type'] = 'employee';
            $forms[0]['emp_app_sid'] = $hired_sid;
            $this->db->insert('applicant_i9form', $forms[0]);

            $newI9Id = $this->db->insert_id();

            //I9 Old History
            $this->db->select('*');
            $this->db->where('i9form_ref_sid', $I9OldSid);
            $records_obj = $this->db->get('applicant_i9form_history');
            $formsI9History = $records_obj->result_array();
            if (!empty($formsI9History)) {
                foreach ($formsI9History as $formsOldRow) {
                    unset($formsOldRow['sid']);
                    $formsOldRow['i9form_ref_sid'] = $newI9Id;
                    $formsOldRow['user_sid'] = $hired_sid;
                    $formsOldRow['user_type'] = 'employee';
                    $this->db->insert('applicant_i9form_history', $formsOldRow);
                }
            }

            // I9 Trail
            $this->db->select('*');
            $this->db->where('document_sid', $I9OldSid);
            $this->db->where('document_type', 'i9');
            $records_obj = $this->db->get('verification_documents_track');
            $formsI9Trail = $records_obj->result_array();
            if (!empty($formsI9Trail)) {
                foreach ($formsI9Trail as $formsOldTrailRow) {
                    unset($formsOldTrailRow['sid']);
                    $formsOldTrailRow['document_sid'] = $newI9Id;
                    $this->db->insert('verification_documents_track', $formsOldTrailRow);
                }
            }
        }


        //General Documents
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('user_type', 'applicant');
        //        $records_obj = $this->db->get('documents_assignment');
        $records_obj = $this->db->get('documents_assigned');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $record) {

                $oldSid = 0;
                if ($record['document_sid'] == 0) {
                    $oldSid = $record['sid'];
                }

                unset($record['sid']);
                $record['user_sid'] = $hired_sid;
                $record['user_type'] = 'employee';

                $this->db->insert('documents_assigned', $record);
                $newSid = $this->db->insert_id();

                //
                if ($oldSid != 0) {
                    $this->db->select('*');
                    $this->db->where('document_sid', $oldSid);
                    $records_obj = $this->db->get('documents_2_category');
                    $records_arr = $records_obj->result_array();
                    $records_obj->free_result();

                    if (!empty($records_arr)) {
                        foreach ($records_arr as $record) {
                            $record['document_sid'] = $newSid;
                            $this->db->insert('documents_2_category', $record);
                        }
                    }
                }
            }
            //
            $this->load->model('hr_documents_management_model');
            //
            $this->hr_documents_management_model->moveDocumentsHistory($sid, $hired_sid);
            //
            return $records_arr;
        } else {
            return 0;
        }
    }

    function update_documents($sid, $hired_sid)
    {
        $return_array = [
            'W4' => '',
            'W9' => '',
            'I9' => '',
            'documents' => "",
        ];
        //Fillable Forms
        //W4
        $this->db->select('sid');
        $this->db->where('employer_sid', $hired_sid);
        $this->db->where('user_type', 'employee');
        $records_obj = $this->db->get('form_w4_original')->result_array();
        //
        if (sizeof($records_obj) == 0) {
            $this->db->select('*');
            $this->db->where('employer_sid', $sid);
            $this->db->where('user_type', 'applicant');
            $records_obj = $this->db->get('form_w4_original');
            $forms = $records_obj->result_array();
            $records_obj->free_result();
            if (!empty($forms)) {
                unset($forms[0]['sid']);
                $forms[0]['employer_sid'] = $hired_sid;
                $forms[0]['user_type'] = 'employee';
                $this->db->insert('form_w4_original', $forms[0]);
                //
                $return_array['W4'] = $forms[0];
            }
        }
        //W9
        $this->db->select('sid');
        $this->db->where('user_sid', $hired_sid);
        $this->db->where('user_type', 'employee');
        $records_obj = $this->db->get('applicant_w9form')->result_array();
        //
        if (sizeof($records_obj) == 0) {
            $this->db->select('*');
            $this->db->where('user_sid', $sid);
            $this->db->where('user_type', 'applicant');
            $records_obj = $this->db->get('applicant_w9form');
            $forms = $records_obj->result_array();
            $records_obj->free_result();
            if (!empty($forms)) {
                unset($forms[0]['sid']);
                $forms[0]['user_sid'] = $hired_sid;
                $forms[0]['user_type'] = 'employee';
                $this->db->insert('applicant_w9form', $forms[0]);
                //
                $return_array['W9'] = $forms[0];
            }
        }
        //I9
        $this->db->select('sid');
        $this->db->where('user_sid', $hired_sid);
        $this->db->where('user_type', 'employee');
        $records_obj = $this->db->get('applicant_i9form')->result_array();
        //
        if (sizeof($records_obj) == 0) {
            $this->db->select('*');
            $this->db->where('user_sid', $sid);
            $this->db->where('user_type', 'applicant');
            $records_obj = $this->db->get('applicant_i9form');
            $forms = $records_obj->result_array();
            $records_obj->free_result();
            if (!empty($forms)) {
                unset($forms[0]['sid']);
                $forms[0]['user_sid'] = $hired_sid;
                $forms[0]['user_type'] = 'employee';
                $forms[0]['emp_app_sid'] = $hired_sid;
                $this->db->insert('applicant_i9form', $forms[0]);
                //
                $return_array['I9'] = $forms[0];
            }
        }

        //General Documents
        $this->db->select('sid');
        $this->db->where('user_sid', $hired_sid);
        $this->db->where('user_type', 'employee');
        $records_obj = $this->db->get('documents_assigned')->result_array();

        if (sizeof($records_obj) == 0) {
            $this->db->select('*');
            $this->db->where('user_sid', $sid);
            $this->db->where('user_type', 'applicant');
            //        $records_obj = $this->db->get('documents_assignment');
            $records_obj = $this->db->get('documents_assigned');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($records_arr)) {
                foreach ($records_arr as $record) {
                    unset($record['sid']);
                    $record['user_sid'] = $hired_sid;
                    $record['user_type'] = 'employee';

                    //                $this->db->insert('documents_assignment', $record);
                    $this->db->insert('documents_assigned', $record);
                }
                //
                $return_array['documents'] = $records_arr;
            }
        } else {
            $already_sid = array();
            foreach ($records_obj as $obj) {
                $already_sid[] = $obj['sid'];
            }
            $this->db->select('*');
            $this->db->where('user_sid', $sid);
            $this->db->where('user_type', 'applicant');
            $records_obj = $this->db->get('documents_assigned');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($records_arr)) {
                foreach ($records_arr as $record) {
                    if (!in_array($record['sid'], $already_sid)) {
                        unset($record['sid']);
                        $record['user_sid'] = $hired_sid;
                        $record['user_type'] = 'employee';
                        $this->db->insert('documents_assigned', $record);
                    }
                }

                //
                $return_array['documents'] = $records_arr;
                //
                $this->load->model('hr_documents_management_model');
                //
                $this->hr_documents_management_model->moveDocumentsHistory($sid, $hired_sid);
            }
        }
        //
        return $return_array;
    }

    function set_onboarding_as_completed($company_sid, $applicant_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);

        $this->db->set('onboarding_complete_date', date('Y-m-d H:i:s'));
        $this->db->set('onboarding_status', 'completed');

        $this->db->update('onboarding_applicants');
    }

    function get_direct_deposit_information($applicant_sid, $hired_sid)
    {
        $this->db->select('*');
        $this->db->where('users_type', 'applicant');
        $this->db->where('users_sid', $applicant_sid);
        $records_obj = $this->db->get('bank_account_details');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $record) {
                unset($record['sid']);
                $record['users_type'] = 'employee';
                $record['users_sid'] = $hired_sid;

                $this->db->insert('bank_account_details', $record);
            }

            return $records_arr;
        } else {
            return 0;
        }
    }

    function update_employee_direct_deposit_information($applicant_sid, $hired_sid)
    {
        //        $this->db->select('sid');
        //        $this->db->where('users_type', 'employee');
        //        $this->db->where('users_sid', $hired_sid);
        //        $records_obj = $this->db->get('bank_account_details')->result_array();

        //        if(sizeof($records_obj) == 0){
        $this->db->select('*');
        $this->db->where('users_type', 'applicant');
        $this->db->where('users_sid', $applicant_sid);
        $records_obj = $this->db->get('bank_account_details');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $record) {
                unset($record['sid']);
                $record['users_type'] = 'employee';
                $record['users_sid'] = $hired_sid;

                $this->db->insert('bank_account_details', $record);
            }

            return $records_arr;
        } else {
            return array();
        }
        //        }
    }

    function get_company_information($company_sid)
    {
        $this->db->select('CompanyName');
        $this->db->where('sid', $company_sid);

        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_applicant_e_signature($sid, $hired_id)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('user_type', 'applicant');
        $result = $this->db->get('e_signatures_data')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                unset($info['sid']);

                $info['user_sid'] = $hired_id;
                $info['user_type'] = 'employee';
                $this->db->insert('e_signatures_data', $info);
            }
            return $result;
        } else {
            return 0;
        }
    }

    function update_employee_e_signature($sid, $hired_id)
    {
        //        $this->db->select('sid');
        //        $this->db->where('user_sid', $hired_id);
        //        $this->db->where('user_type', 'employee');
        //        $result = $this->db->get('e_signatures_data')->result_array();

        //        if(sizeof($result) == 0){
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('user_type', 'applicant');
        $result = $this->db->get('e_signatures_data')->result_array();
        if (count($result) > 0) {
            foreach ($result as $info) {
                unset($info['sid']);

                $info['user_sid'] = $hired_id;
                $info['user_type'] = 'employee';
                $this->db->insert('e_signatures_data', $info);
            }
            return $result;
        } else {
            return array();
        }
        //        }
    }

    /**
     * Fetch all active training sessions
     * Created on: 14-05-2019
     *
     * @param $company_sid Integer
     * @param $applicant_sid Integer
     * @param $employee_sid Integer
     *
     * @return Array
     */
    function update_applicant_training_sessions_to_employee($company_sid, $applicant_sid, $employee_sid)
    {

        $result = $this->db
            ->select('
            GROUP_CONCAT(learning_center_training_sessions_assignments.sid) as sid,
            GROUP_CONCAT(learning_center_training_sessions.sid) as learning_center_training_sessions
        ')
            ->from('learning_center_training_sessions')
            ->join('learning_center_training_sessions_assignments', 'learning_center_training_sessions.sid = learning_center_training_sessions_assignments.training_session_sid', 'left')
            ->where('company_sid', $company_sid)
            ->where('user_sid', $applicant_sid)
            ->get();
        $result_arr = $result->row_array();
        $result->free_result();

        if (!empty($records_arr['sid'])) {
            if (!sizeof($result_arr)) return false;

            $this->db
                ->where('sid IN (' . $result_arr['sid'] . ')', null)
                ->update(
                    'learning_center_training_sessions_assignments',
                    array(
                        'user_sid' => $employee_sid,
                        'user_type' => 'employee'
                    )
                );

            if (preg_match('/applybuz/', base_url())) {
                $result = $this->db
                    ->select('sid')
                    ->select('interviewer')
                    ->from('portal_schedule_event')
                    ->where('learning_center_training_sessions IN (' . $result_arr['learning_center_training_sessions'] . ')', null)
                    ->get();
                $result2_arr = $result->result_array();
                $result->free_result();
                //
                if (!sizeof($result2_arr)) return false;
                //
                foreach ($result2_arr as $k0 => $v0) {
                    $this->db
                        ->where('sid', $v0['sid'])
                        ->update('portal_schedule_event', array(
                            'interviewer' => ($v0['interviewer'] . ",$employee_sid"
                            )
                        ));
                }
                return $result2_arr;
            }
        }
    }

    function copy_eeoc_as_user($user_sid, $new_employee_id)
    {
        //Get applicant EEOC Form
        $this->db->where('users_type', 'applicant');
        $this->db->where('application_sid', $user_sid);
        $record_obj = $this->db->get('portal_eeo_form');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        //Make all previous submitted as 0, Add new one for employee
        $this->db->where('users_type', 'applicant');
        $this->db->where('application_sid', $user_sid);
        $this->db->set('is_latest', 0);
        $this->db->update('portal_eeo_form');

        if (!empty($record_arr)) {
            $eeocOldSid = $record_arr[0]['sid'];

            if (!$record_arr[0]["us_citizen"]) {
                $record_arr[0]["is_opt_out"] = 1;
                $record_arr[0]["last_completed_on"] = getSystemDate();
                $record_arr[0]["is_expired"] = 1;
            }

            unset($record_arr[0]['sid']);
            $record_arr[0]['users_type'] = 'employee';
            $record_arr[0]['application_sid'] = $new_employee_id;
            $record_arr[0]['is_latest'] = 1;
            $this->db->insert('portal_eeo_form', $record_arr[0]);

            $neweeocId = $this->db->insert_id();

            //Eeoc History
            $this->db->select('*');
            $this->db->where('eeo_form_sid', $eeocOldSid);
            $records_obj = $this->db->get('portal_eeo_form_history');
            $formsEeocHistory = $records_obj->result_array();
            if (!empty($formsEeocHistory)) {
                foreach ($formsEeocHistory as $formsOldRow) {
                    unset($formsOldRow['sid']);
                    $formsOldRow['eeo_form_sid'] = $neweeocId;
                    $formsOldRow['application_sid'] = $new_employee_id;
                    $formsOldRow['users_type'] = 'employee';
                    $this->db->insert('portal_eeo_form_history', $formsOldRow);
                }
            }

            // Eeoc Trail
            $this->db->select('*');
            $this->db->where('document_sid', $eeocOldSid);
            $this->db->where('document_type', 'eeoc');
            $records_obj = $this->db->get('verification_documents_track');
            $formsEeocTrail = $records_obj->result_array();

            if (!empty($formsEeocTrail)) {
                foreach ($formsEeocTrail as $formsOldTrailRow) {
                    unset($formsOldTrailRow['sid']);
                    $formsOldTrailRow['document_sid'] = $neweeocId;
                    $this->db->insert('verification_documents_track', $formsOldTrailRow);
                }
            }
        }



        //Check applicant eeoc form status (mean filled or not)
        //        $form_status = 'No';
        //        $this->db->select('eeo_form');
        //        $this->db->where('portal_job_applications_sid', $user_sid);
        //        $this->db->where('eeo_form', 'Yes');
        //        $this->db->limit(1);
        //        $this->db->order_by('sid', 'DESC');
        //        $record_obj = $this->db->get('portal_applicant_jobs_list');
        //        $record_arr = $record_obj->result_array();
        //        $record_obj->free_result();
        //        if (!empty($record_arr)) {
        //            $form_status = $record_arr[0]['eeo_form'];
        //        }
        //Update new employee eeoc form status (mean filled or not)
        //        $this->db->where('sid', $new_employee_id);
        //        $this->db->set('eeo_form_status', $form_status);
        //        $this->db->update('users');
    }

    function update_employee_copy_eeoc_as_user($user_sid, $new_employee_id)
    {
        //Get applicant EEOC Form
        $this->db->where('users_type', 'employee');
        $this->db->where('application_sid', $new_employee_id);
        $record_obj = $this->db->get('portal_eeo_form')->result_array();

        if (sizeof($record_obj) == 0) {
            $this->db->where('users_type', 'applicant');
            $this->db->where('application_sid', $user_sid);
            $record_obj = $this->db->get('portal_eeo_form');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            //Make all previous submitted as 0, Add new one for employee
            $this->db->where('users_type', 'applicant');
            $this->db->where('application_sid', $user_sid);
            $this->db->set('is_latest', 0);
            $this->db->update('portal_eeo_form');
            unset($record_arr[0]['sid']);
            $record_arr[0]['users_type'] = 'employee';
            $record_arr[0]['application_sid'] = $new_employee_id;
            $record_arr[0]['is_latest'] = 1;
            $this->db->insert('portal_eeo_form', $record_arr[0]);

            return $record_arr[0];
        } else {
            return array();
        }
    }

    function is_username_exist($username)
    {
        $this->db->select('*');
        $this->db->where('username', $username);

        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return 1;
        } else {
            return 0;
        }
    }

    function get_details_by_applicant_sid($applicant_sid)
    {
        $this->db->select('first_name, last_name, email');
        $this->db->where('sid', $applicant_sid);

        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_for_resume($employee_sid)
    {
        $this->db->select("resume");
        $this->db->where("portal_job_applications_sid", $employee_sid);
        $this->db->where('resume <>', '');
        $this->db->where('resume <>', NULL);
        $this->db->order_by("last_update", 'DESC');
        $result = $this->db->get("portal_applicant_jobs_list")->result_array();

        if (sizeof($result) > 0) {
            return $result[0]['resume'];
        } else {  //Check Applications Table for resume
            $this->db->select("resume");
            $this->db->where("sid", $employee_sid);
            $this->db->where('resume <>', '');
            $this->db->where('resume <>', NULL);
            $result = $this->db->get("portal_job_applications")->result_array();
            if (sizeof($result) > 0) {
                return $result[0]['resume'];
            }
        }
        return 0;
    }

    function get_employee_profile($employee_sid)
    {
        $this->db->where('sid', $employee_sid);
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    function update_company_employee($employer_data, $applicant_sid, $employee_sid, $hired_job_sid = 0, $update_flag = 0)
    {
        // update applications table
        $this->db->where('sid', $applicant_sid);
        $data_applicant = array(
            'hired_sid' => $employee_sid,
            'hired_status' => '1',
            'hired_date' => date('Y-m-d'),
            'hired_job_sid' => $hired_job_sid,
            'is_onboarding' => 0
        );
        //
        $this->db->update('portal_job_applications', $data_applicant);
        //
        //update user if changed
        //        if($update_flag){
        $this->db->where('sid', $employee_sid);
        $this->db->update('users', $employer_data);

        //
        if ($employer_data['department_sid'] != 0 && $employer_data['team_sid'] != 0) {
            $team_information['department_sid'] = $employer_data['department_sid'];
            $team_information['team_sid'] = $employer_data['team_sid'];
            $team_information['employee_sid'] = $employee_sid;
            $team_information['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('departments_employee_2_team', $team_information);
        }

        //        }
    }

    function save_merge_applicant_info($applicant_data, $applicant_sid, $employee_sid)
    {
        // insert merge table record
        $merge_record = array(
            'portal_job_applications_sid' => $applicant_sid,
            'employee_sid' => $employee_sid,
            'created_date' => date('Y-m-d H:i:s'),
            'is_revert' => 0,
            'data_update' => serialize($applicant_data)
        );
        //
        $this->db->insert('applicant_merge_employee_record', $merge_record);
    }


    // 
    function copyApplicantFFEAToEmployee(
        $applicantSid,
        $employeeSid
    ) {
        //
        $a = $this->db
            ->select('company_sid, verification_key, status, status_date')
            ->where('user_sid', $applicantSid)
            ->where('user_type', 'applicant')
            ->get('form_full_employment_application');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (!$b || !count($b)) return false;
        //
        $b['user_sid'] = $employeeSid;
        $b['user_type'] = 'employee';
        //
        $this->db
            ->insert('form_full_employment_application', $b);
        //
        return $this->db->insert_id();
    }

    //
    function getJobTitleById($jobSid)
    {
        $a = $this->db
            ->select('Title')
            ->where('sid', $jobSid)
            ->get('portal_job_listings');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return array_key_exists('Title', $b) ? $b['Title'] : '';
    }


    function update_groups($sid, $hired_id)
    {

        $this->db->select('*');
        $this->db->where('applicant_sid', $sid);
        $result = $this->db->get('documents_group_2_employee')->result_array();
        if (count($result) > 0) {
            foreach ($result as $groups_information) {
                $insert_groups_information = array(
                    'employer_sid' => $hired_id,
                    'group_sid' => $groups_information['group_sid'],
                    'company_sid' => $groups_information['company_sid'],
                    'assigned_date' => $groups_information['assigned_date'],
                    'assign_status' => $groups_information['assign_status'],
                    'revoke_by' => $groups_information['revoke_by']
                );

                $this->db->insert('documents_group_2_employee', $insert_groups_information);
            }

            return $result;
        } else {
            return array();
        }
    }


    function form_full_employment_application($sid, $hired_id)
    {

        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $this->db->where('user_type', 'applicant');
        $result = $this->db->get('form_full_employment_application')->result_array();

        if (count($result) > 0) {
            foreach ($result as $full_employment_information) {
                $insert_full_employment_information = array(
                    'user_sid' => $hired_id,
                    'user_type' => 'employee',
                    'company_sid' => $full_employment_information['company_sid'],
                    'verification_key' => $full_employment_information['verification_key'],
                    'status' => $full_employment_information['status'],
                    'status_date' => $full_employment_information['status_date']

                );

                $this->db->insert('form_full_employment_application', $insert_full_employment_information);
            }

            return $result;
        } else {
            return 0;
        }
    }



    //
    function get_applicant_department_team($company_sid, $applicant_sid)
    {
        $this->db->select('department_sid,team_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $result = $this->db->get('onboarding_applicants')->row_array();
        return $result;
    }
}
