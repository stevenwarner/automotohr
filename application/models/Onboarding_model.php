<?php

class Onboarding_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_company_welcome_videos($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employee_sid', NULL);
        $this->db->where('applicant_sid', NULL);
        $records_obj = $this->db->get('onboarding_welcome_video');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    function get_company_welcome_video($welcome_video_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $welcome_video_sid);
        $this->db->where('employee_sid', NULL);
        $this->db->where('applicant_sid', NULL);
        $records_obj = $this->db->get('onboarding_welcome_video');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr[0];
        }

        return $return_data;
    }

    function get_all_office_locations($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('onboarding_office_locations');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_custom_office_records($company_sid, $user_sid, $user_type, $custom_type, $active_only = 0)
    {
        $this->db->select('*');
        $this->db->where('custom_type', $custom_type);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'employee') {
            $this->db->where('employee_sid', $user_sid);
        } else {
            $this->db->where('applicant_sid', $user_sid);
        }

        if ($active_only == 1) {
            $this->db->where('status', 1);
        }

        $this->db->where('is_custom', 1);
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('onboarding_custom_assignment');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function insert_office_location($data_to_insert)
    {
        $this->db->insert('onboarding_office_locations', $data_to_insert);
    }

    function custom_assignment_insert_data($data_to_insert)
    {
        $this->db->insert('onboarding_custom_assignment', $data_to_insert);
        return $this->db->insert_id();
    }

    function custom_assignment_update_record($sid, $data_array)
    {
        $this->db->where('sid', $sid);
        $this->db->update('onboarding_custom_assignment', $data_array);
    }

    function get_edit_location($company_sid, $location_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $location_sid);
        $records_obj = $this->db->get('onboarding_office_locations');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_custom_record($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('onboarding_custom_assignment');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_e_signature($company_sid, $employer_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $employer_sid);
        $records_obj = $this->db->get('e_signatures_data');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function update_location($sid, $data_array)
    {
        $this->db->where('sid', $sid);
        $this->db->update('onboarding_office_locations', $data_array);
    }

    function getOfficeHours($company_sid, $hours_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $hours_sid);
        $records_obj = $this->db->get('onboarding_office_timings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function updateOfficeHours($sid, $data_array)
    {
        $this->db->where('sid', $sid);
        $this->db->update('onboarding_office_timings', $data_array);
    }

    function checkEmployeer($company_sid, $employer_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->from('onboarding_people_to_meet');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            return true;
        } else {
            return false;
        }
    }

    function checkemployerBeforeEdit($company_sid, $employer_sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $records_obj = $this->db->get('onboarding_people_to_meet');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function getPersonToMeet($company_sid, $person_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $person_sid);
        $records_obj = $this->db->get('onboarding_people_to_meet');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function updatePersonToMeet($sid, $data_array)
    {
        $this->db->where('sid', $sid);
        $this->db->update('onboarding_people_to_meet', $data_array);
    }

    function getItemToBring($company_sid, $item_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $item_sid);
        $records_obj = $this->db->get('onboarding_what_to_bring');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function updateItemToBring($sid, $data_array)
    {
        $this->db->where('sid', $sid);
        $this->db->update('onboarding_what_to_bring', $data_array);
    }

    function delete_office_location($company_sid, $office_location_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $office_location_sid);
        $this->db->delete('onboarding_office_locations');
    }

    function get_all_getting_started_sections($company_sid, $section_sids = null, $section_unique_ids = null)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if ($section_sids != null && is_array($section_sids)) {
            $this->db->where_in('sid', $section_sids);
        }

        if ($section_unique_ids != null && is_array($section_unique_ids)) {
            $this->db->where_in('section_unique_id', $section_unique_ids);
        }

        $records_obj = $this->db->get('onboarding_getting_started_sections');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_company_instructions($company_sid, $employee_sid = 0, $applicant_sid = 0)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if ($employee_sid > 0) {
            $this->db->where('employee_sid', $employee_sid);
        }

        if ($applicant_sid > 0) {
            $this->db->where('applicant_sid', $applicant_sid);
        }

        $records_obj = $this->db->get('onboarding_instructions');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if ($employee_sid > 0 || $applicant_sid > 0) {
            if (empty($records_arr)) {
                $this->db->select('*');
                $this->db->where('company_sid', $company_sid);
                $records_obj = $this->db->get('onboarding_instructions');
                $records_arr = $records_obj->result_array();
                $records_obj->free_result();
            }
        }

        return $records_arr;
    }

    function insert_update_welcome_video($data, $sid = 0)
    {
        if ($sid > 0) {
            $this->db->where('sid', $sid);
            $this->db->update('onboarding_welcome_video', $data);
        } else {
            $this->db->insert('onboarding_welcome_video', $data);
        }
    }

    function delete_welcome_video($sid, $company_sid)
    {
        $this->db->where('sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', NULL);
        $this->db->where('employee_sid', NULL);
        $this->db->delete('onboarding_welcome_video');
    }

    function undefault_welcome_video_default_status($data, $company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', NULL);
        $this->db->where('employee_sid', NULL);
        $this->db->update('onboarding_welcome_video', $data);
    }

    function change_welcome_video_status($data, $sid, $company_sid)
    {
        $this->db->where('sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', NULL);
        $this->db->where('employee_sid', NULL);
        $this->db->update('onboarding_welcome_video', $data);
    }

    function insert_update_onboarding_instructions($data, $sid)
    {
        if ($sid > 0) {
            $this->db->where('sid', $sid);
            $this->db->update('onboarding_instructions', $data);
        } else {
            $this->db->insert('onboarding_instructions', $data);
        }
    }

    function save_update_setup_onboarding_instructions($instructions, $user_type, $user_sid, $company_sid, $modified_by_sid)
    {
        $data = array();
        $data['instructions'] = $instructions;
        $data['company_sid'] = $company_sid;
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['modified_by_sid'] = $modified_by_sid;

        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);

        if ($user_type == 'applicant') {
            $data['applicant_sid'] = $user_sid;
            $this->db->where('applicant_sid', $user_sid);
        } else {
            $data['employee_sid'] = $user_sid;
            $this->db->where('employee_sid', $user_sid);
        }

        $records_obj = $this->db->get('onboarding_instructions');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (empty($records_arr)) { // record does not exist, add it to database
            $this->db->insert('onboarding_instructions', $data);
        } else {
            $sid = $records_arr['0']['sid'];
            $this->db->where('sid', $sid);
            $this->db->update('onboarding_instructions', $data);
        }
    }

    function insert_getting_started_section($data_to_insert)
    {
        $this->db->insert('onboarding_getting_started_sections', $data_to_insert);
    }

    function update_getting_started_section($section_sid, $data_to_update)
    {
        $this->db->where('sid', $section_sid);
        $this->db->update('onboarding_getting_started_sections', $data_to_update);
    }

    function get_single_getting_started_section($section_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $section_sid);
        $record_obj = $this->db->get('onboarding_getting_started_sections');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function delete_getting_started_section($company_sid, $section_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $section_sid);
        $this->db->delete('onboarding_getting_started_sections');
    }

    function insert_office_timings($data_to_insert)
    {
        $this->db->insert('onboarding_office_timings', $data_to_insert);
    }

    function get_all_office_timings($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('onboarding_office_timings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function delete_office_timing($company_sid, $office_timing_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $office_timing_sid);
        $this->db->delete('onboarding_office_timings');
    }

    function insert_what_to_bring_item($data_to_insert)
    {
        $this->db->insert('onboarding_what_to_bring', $data_to_insert);
    }

    function get_all_what_to_bring_items($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('onboarding_what_to_bring');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_target_what_to_bring_item($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('onboarding_what_to_bring');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function disable_default_record($company_sid, $user_type, $user_sid, $custom_type)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'employee') {
            $this->db->where('employee_sid', $user_sid);
        } else {
            $this->db->where('applicant_sid', $user_sid);
        }

        $this->db->where('custom_type', $custom_type);
        $this->db->where('is_custom', 0);
        $this->db->set('status', 0);
        $this->db->update('onboarding_custom_assignment');
    }

    function enable_default_record($company_sid, $user_type, $user_sid, $custom_type, $parent_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'employee') {
            $this->db->where('employee_sid', $user_sid);
        } else {
            $this->db->where('applicant_sid', $user_sid);
        }

        $this->db->where('custom_type', $custom_type);
        $this->db->where('parent_sid', $parent_sid);
        $this->db->where('is_custom', 0);
        $this->db->set('status', 1);
        $this->db->update('onboarding_custom_assignment');
    }

    function default_record_item_exist($company_sid, $user_type, $user_sid, $custom_type, $parent_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'employee') {
            $this->db->where('employee_sid', $user_sid);
        } else {
            $this->db->where('applicant_sid', $user_sid);
        }

        $this->db->where('custom_type', $custom_type);
        $this->db->where('parent_sid', $parent_sid);
        $this->db->where('is_custom', 0);

        $records_obj = $this->db->get('onboarding_custom_assignment');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return true;
        } else {
            return false;
        }
    }

    function delete_what_to_bring_item($company_sid, $what_to_bring_item_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $what_to_bring_item_sid);
        $this->db->delete('onboarding_what_to_bring');
    }

    function insert_useful_links_record($data_to_insert)
    {
        $this->db->insert('onboarding_useful_links', $data_to_insert);
    }

    function get_all_links($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('onboarding_useful_links');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_edit_link($company_sid, $useful_link_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $useful_link_sid);
        $records_obj = $this->db->get('onboarding_useful_links');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_usefull_link($sid, $data_array)
    {
        $this->db->where('sid', $sid);
        $this->db->update('onboarding_useful_links', $data_array);
    }

    function delete_useful_link($company_sid, $useful_link_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $useful_link_sid);
        $this->db->delete('onboarding_useful_links');
    }

    function delete_ems_notification($company_sid, $ems_notification_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $ems_notification_sid);
        $this->db->delete('ems_dashboard_notification');
        $this->db->where('ems_notification_sid', $ems_notification_sid);
        $this->db->delete('ems_dashboard_notification_assigned');
    }

    function get_all_employees($company_sid)
    {
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('sid');
        $this->db->select('PhoneNumber as phone');
        $this->db->select('email');
        $this->db->select('job_title, access_level, access_level_plus, pay_plan_flag, is_executive_admin');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('is_executive_admin', 0);
        $this->db->where('username !=', '');
        $this->db->where('active', 1);
        $this->db->where('archived', 0);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function insert_people_to_meet_record($data_to_insert)
    {
        $this->db->insert('onboarding_people_to_meet', $data_to_insert);
    }

    function get_all_people_to_meet($company_sid)
    {
        $this->db->select('onboarding_people_to_meet.*');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');
        $this->db->select('users.profile_picture');
        $this->db->join('users', 'users.sid = onboarding_people_to_meet.employer_sid', 'left');
        $this->db->where('onboarding_people_to_meet.company_sid', $company_sid);
        $this->db->where('users.active', 1);
        $this->db->where('users.archived', 0);
        $this->db->where('users.terminated_status', 0);

        $records_obj = $this->db->get('onboarding_people_to_meet');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function delete_people_to_meet_record($company_sid, $person_to_meet_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $person_to_meet_sid);
        $this->db->delete('onboarding_people_to_meet');
    }

    function get_applicant_information($applicant_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $applicant_sid);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $record_arr = $record_arr[0];

            if (isset($record_arr['extra_info']) && !empty($record_arr['extra_info'])) {
                $extra_info = unserialize($record_arr['extra_info']);
                // it will check if the array is empty or not
                // then stop the flush of the array
                if ($extra_info) {
                    $record_arr = array_merge($record_arr, $extra_info);
                }
            }

            return $record_arr;
        } else {
            return array();
        }
    }

    function update_applicant_information($company_sid, $applicant_sid, $data_to_update)
    {
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('sid', $applicant_sid);
        $this->db->update('portal_job_applications', $data_to_update);
    }

    function get_applicant_emergency_contacts($user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $records_obj = $this->db->get('emergency_contacts');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function insert_applicant_emergency_contact($data_to_insert)
    {
        $this->db->insert('emergency_contacts', $data_to_insert);
    }

    function delete_emergency_contact($users_type, $users_sid, $contact_sid)
    {
        $this->db->where('users_type', $users_type);
        $this->db->where('users_sid', $users_sid);
        $this->db->where('sid', $contact_sid);
        $this->db->delete('emergency_contacts');
    }

    function get_license_details($user_type, $user_sid, $license_type)
    {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->where('license_type', $license_type);
        $this->db->limit(1);

        $record_obj = $this->db->get('license_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function save_license_information($user_type, $user_sid, $license_type, $license_details, $dateOfBirth = array())
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->where('license_type', $license_type);
        $this->db->from('license_information');
        $record_count = $this->db->count_all_results();
        if ($record_count > 0) {
            $this->db->where('users_type', $user_type);
            $this->db->where('users_sid', $user_sid);
            $this->db->where('license_type', $license_type);

            if (isset($license_details['users_type'])) {
                unset($license_details['users_type']);
            }

            if (isset($license_details['users_sid'])) {
                unset($license_details['users_sid']);
            }

            $this->db->update('license_information', $license_details);
            if (!empty($dateOfBirth) && $user_sid != null && ($user_type == 'applicant' || $user_type == 'Applicant')) {
                $this->db->where('sid', $user_sid)
                    ->update('portal_job_applications', $dateOfBirth);
            }
        } else {
            $this->db->insert('license_information', $license_details);
            if (!empty($dateOfBirth) && $user_sid != null && ($user_type == 'applicant' || $user_type == 'Applicant')) {
                $this->db->where('sid', $user_sid)
                    ->update('portal_job_applications', $dateOfBirth);
            }
        }
    }

    function get_dependant_information($user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('users_sid', $user_sid);
        $this->db->where('users_type', $user_type);
        $this->db->where('have_dependents', '1');

        $records_obj = $this->db->get('dependant_information');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $key => $record) {
                if (isset($record['dependant_details'])) {
                    $dependant = unserialize($record['dependant_details']);
                    unset($record['dependant_details']);
                    $new_data = array_merge($record, $dependant);
                    unset($records_arr[$key]['dependant_details']);
                    $records_arr[$key] = $new_data;
                }
            }

            return $records_arr;
        } else {
            return array();
        }
    }

    function insert_dependent_information($data_to_save)
    {
        $this->db->insert('dependant_information', $data_to_save);
    }

    function delete_dependent_information($user_type, $user_sid, $dependent_sid)
    {
        //        $this->db->where('users_type', $user_type);
        //        $this->db->where('users_sid', $user_sid);
        $this->db->where('sid', $dependent_sid);
        $this->db->delete('dependant_information');
    }

    function save_bank_details($user_type, $user_sid, $bank_details)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->from('bank_account_details');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            $this->db->where('users_type', $user_type);
            $this->db->where('users_sid', $user_sid);

            if (isset($bank_details['users_type'])) {
                unset($bank_details['users_type']);
            }

            if (isset($bank_details['users_sid'])) {
                unset($bank_details['users_sid']);
            }

            $this->db->update('bank_account_details', $bank_details);
        } else {
            $this->db->insert('bank_account_details', $bank_details);
        }
    }

    function get_bank_details($user_type, $user_sid)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);

        $record_obj = $this->db->get('bank_account_details');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function save_eeoc($user_type, $user_sid, $eeoc_details)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid', $user_sid);
        $this->db->from('portal_eeo_form');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            $this->db->where('users_type', $user_type);
            $this->db->where('application_sid', $user_sid);

            if (isset($eeoc_details['users_type'])) {
                unset($eeoc_details['users_type']);
            }

            if (isset($eeoc_details['application_sid'])) {
                unset($eeoc_details['application_sid']);
            }

            $this->db->update('portal_eeo_form', $eeoc_details);
        } else {
            $this->db->insert('portal_eeo_form', $eeoc_details);
        }
    }

    function get_eeoc($user_type, $user_sid)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid', $user_sid);

        $record_obj = $this->db->get('portal_eeo_form');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_eeoc($user_type, $user_sid, $dataToUpdate)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid', $user_sid);
        $this->db->from('portal_eeo_form');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            $this->db->where('users_type', $user_type);
            $this->db->where('application_sid', $user_sid);
            $this->db->update('portal_eeo_form', $dataToUpdate);
        }
    }

    function save_required_equipment($user_type, $user_sid, $equipment_details)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->from('onboarding_required_equipments');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            $this->db->where('users_type', $user_type);
            $this->db->where('users_sid', $user_sid);

            if (isset($equipment_details['users_type'])) {
                unset($equipment_details['users_type']);
            }

            if (isset($equipment_details['users_sid'])) {
                unset($equipment_details['users_sid']);
            }

            $this->db->update('onboarding_required_equipments', $equipment_details);
        } else {
            $this->db->insert('onboarding_required_equipments', $equipment_details);
        }
    }

    function get_required_equipment($user_type, $user_sid)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);

        $record_obj = $this->db->get('onboarding_required_equipments');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_full_employement_application($applicant_sid, $application_data)
    {
        $this->db->where('sid', $applicant_sid);
        $this->db->set('full_employment_application', serialize($application_data));
        $this->db->update('portal_job_applications');
    }

    function mark_applicant_for_onboarding($applicant_sid)
    {
        $this->db->where('sid', $applicant_sid);
        $this->db->set('is_onboarding', 1);
        $this->db->update('portal_job_applications');
    }

    function update_applicant_status_type($applicant_sid, $employee_data)
    {
        $this->db->where('sid', $applicant_sid);
        $this->db->update('portal_job_applications', $employee_data);
    }

    function save_onboarding_applicant($company_sid, $applicant_sid, $data_to_save)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->from('onboarding_applicants');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            $this->db->where('company_sid', $company_sid);
            $this->db->where('applicant_sid', $applicant_sid);

            if (isset($data_to_save['company_sid'])) {
                unset($data_to_save['company_sid']);
            }

            if (isset($data_to_save['applicant_sid'])) {
                unset($data_to_save['applicant_sid']);
            }

            $this->db->update('onboarding_applicants', $data_to_save);
        } else {
            $this->db->insert('onboarding_applicants', $data_to_save);
        }
    }

    function update_emailSent_date($unique_sid, $data)
    {
        $this->db->where('unique_sid', $unique_sid);
        $this->db->update('onboarding_applicants', $data);
    }

    function save_onboarding_links_config($data_to_insert)
    {
        $this->db->insert('useful_links_assignment', $data_to_insert);
    }

    function is_assign_link_configuration($link_sid, $user_sid, $company_sid, $user_type)
    {
        $this->db->where('link_sid', $link_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        } else {
            $this->db->where('employee_sid', $user_sid);
        }

        $this->db->from('useful_links_assignment');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    function inactive_useful_configuration_links($link_sid, $company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('link_sid', $link_sid);
        $this->db->set('link_status', 0);
        $this->db->update('useful_links_assignment');
    }

    function configuration_link_update_status($user_sid, $company_sid, $user_type)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        } else {
            $this->db->where('employee_sid', $user_sid);
        }

        $this->db->set('assign_status', 0);
        $this->db->update('useful_links_assignment');
    }

    function active_useful_configuration_link($link_sid, $user_sid, $company_sid, $user_type, $data_to_update)
    {
        $this->db->where('link_sid', $link_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        } else {
            $this->db->where('employee_sid', $user_sid);
        }

        $this->db->update('useful_links_assignment', $data_to_update);
        // echo $this->db->last_query().'<br><pre>'; echo '</pre>';
    }

    function save_onboarding_config($company_sid, $user_type, $user_sid, $section, $data_to_save)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('section', $section);
        $this->db->from('onboarding_applicants_configuration');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            $this->db->where('company_sid', $company_sid);
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('section', $section);

            if (isset($data_to_save['company_sid'])) {
                unset($data_to_save['company_sid']);
            }

            if (isset($data_to_save['user_sid'])) {
                unset($data_to_save['user_sid']);
            }

            if (isset($data_to_save['user_type'])) {
                unset($data_to_save['user_type']);
            }

            if (isset($data_to_save['section'])) {
                unset($data_to_save['section']);
            }

            $this->db->update('onboarding_applicants_configuration', $data_to_save);
        } else {
            $this->db->insert('onboarding_applicants_configuration', $data_to_save);
        }
    }

    function get_company_info($company_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $company_sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_details_by_unique_sid($unique_sid, $onboarding_status = 'in_process')
    {
        $this->db->select('*');
        $this->db->where('unique_sid', $unique_sid);
        $this->db->where('onboarding_status', $onboarding_status);

        $record_obj = $this->db->get('onboarding_applicants');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $record_arr = $record_arr[0];
            $applicant_sid = $record_arr['applicant_sid'];
            $applicant_info = $this->get_applicant_information($applicant_sid);
            $record_arr['applicant_info'] = $applicant_info;
            $company_sid = $record_arr['company_sid'];
            $company_info = $this->get_company_info($company_sid);
            $record_arr['company_info'] = $company_info;
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_assign_useful_links($user_type, $user_sid, $company_sid)
    {
        $this->db->select('link_sid, link_description');
        $this->db->where('user_type', $user_type);
        $this->db->where('company_sid', $company_sid);

        if ($user_type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        } else {
            $this->db->where('employee_sid', $user_sid);
        }

        $this->db->where('link_status', 1);
        $this->db->where('assign_status', 1);
        $record_obj = $this->db->get('useful_links_assignment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function onboarding_assign_useful_links($user_sid, $company_sid, $type = 'applicant')
    {
        $this->db->select('link_sid, link_title, link_url, link_description');
        $this->db->where('user_type', $type);

        if ($type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        } else {
            $this->db->where('employee_sid', $user_sid);
        }

        $this->db->where('company_sid', $company_sid);
        $this->db->where('link_status', 1);
        $this->db->where('assign_status', 1);
        $record_obj = $this->db->get('useful_links_assignment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_onboarding_configuration_welcome_video($company_sid, $user_sid, $user_type)
    {
        $this->db->select('video_source, video_url, is_active');
        $this->db->where('company_sid', $company_sid);

        if ($user_type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        } else if ($user_type == 'employee') {
            $this->db->where('employee_sid', $user_sid);
        }

        $record_obj = $this->db->get('onboarding_welcome_video');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            if ($record_arr[0]['is_active'] == 1) {

                $return_data = $record_arr[0];
            }
        } else {
            $this->db->select('video_source, video_url');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('applicant_sid', NULL);
            $this->db->where('employee_sid', NULL);
            $this->db->where('is_active', 1);
            $company_record_obj = $this->db->get('onboarding_welcome_video');
            $company_record_arr = $company_record_obj->result_array();
            $company_record_obj->free_result();

            if (!empty($company_record_arr)) {
                $return_data = $company_record_arr[0];
            }
        }

        return $return_data;
    }

    function get_onboarding_setup_welcome_video($company_sid, $user_sid, $user_type)
    {
        $this->db->select('sid, video_source, video_url, is_active, is_custom');
        $this->db->where('company_sid', $company_sid);

        if ($user_type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        } else if ($user_type == 'employee') {
            $this->db->where('employee_sid', $user_sid);
        }

        $record_obj = $this->db->get('onboarding_welcome_video');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr[0];
        } else {
            $this->db->select('video_source, video_url, is_active');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('applicant_sid', NULL);
            $this->db->where('employee_sid', NULL);
            $this->db->where('is_default', 1);
            $company_record_obj = $this->db->get('onboarding_welcome_video');
            $company_record_arr = $company_record_obj->result_array();
            $company_record_obj->free_result();

            if (!empty($company_record_arr)) {
                $welcome_video = $company_record_arr[0];
                $welcome_video_insert_applicant_data = array();
                $welcome_video_insert_applicant_data['company_sid'] = $company_sid;

                if ($user_type == 'applicant') {
                    $welcome_video_insert_applicant_data['applicant_sid'] = $user_sid;
                } else if ($user_type == 'employee') {
                    $welcome_video_insert_applicant_data['employee_sid'] = $user_sid;
                }

                $welcome_video_insert_applicant_data['is_active'] = $welcome_video['is_active'];
                $welcome_video_insert_applicant_data['video_source'] = $welcome_video['video_source'];
                $welcome_video_insert_applicant_data['video_url'] = $welcome_video['video_url'];
                $this->db->insert('onboarding_welcome_video', $welcome_video_insert_applicant_data);
                $last_insert = $this->db->insert_id();

                $this->db->select('sid, video_source, video_url, is_active');
                $this->db->where('sid', $last_insert);
                $applicant_record_obj = $this->db->get('onboarding_welcome_video');
                $applicant_record_arr = $applicant_record_obj->result_array();
                $applicant_record_obj->free_result();
                $return_data = $applicant_record_arr[0];
            }
        }

        return $return_data;
    }

    function get_welcome_videos_collection($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', NULL);
        $this->db->where('employee_sid', NULL);
        $this->db->where('is_active', 1);

        $record_obj = $this->db->get('onboarding_welcome_video');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }

    function is_welcome_video_exist($company_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        if ($user_type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        } else if ($user_type == 'employee') {
            $this->db->where('employee_sid', $user_sid);
        }


        $record_obj = $this->db->get('onboarding_welcome_video');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr[0];
        }

        return $return_data;
    }

    function get_assign_welcome_video($sid, $company_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', NULL);
        $this->db->where('employee_sid', NULL);
        $this->db->where('is_active', 1);


        $record_obj = $this->db->get('onboarding_welcome_video');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr[0];
        }

        return $return_data;
    }

    function get_onboarding_configuration($user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        // $this->db->where('section', '!=', $user_sid);
        $records_obj = $this->db->get('onboarding_applicants_configuration');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        // echo $this->db->last_query().'<br><pre>'; print_r($records_arr); echo '</pre>';
        foreach ($records_arr as $key => $record) {
            $items = unserialize($record['items']);
            $section = $record['section'];
            //            echo '<br>section: '.$section;
            switch ($section) {
                case 'sections':
                    $table_name = 'onboarding_getting_started_sections';
                    break;
                case 'locations':
                    $table_name = 'onboarding_office_locations';
                    break;
                case 'timings':
                    $table_name = 'onboarding_office_timings';
                    break;
                case 'people':
                    $table_name = 'onboarding_people_to_meet';
                    break;
                case 'items':
                    $table_name = 'onboarding_what_to_bring';
                    break;
                case 'documents':
                    $table_name = 'hr_documents';
                    break;
            }

            if (!empty($items)) {
                $this->db->select('*');
                $this->db->where_in('sid', $items);
                $rec_obj = $this->db->get($table_name);
                $rec_arr = $rec_obj->result_array();
                $rec_obj->free_result();
            } else {
                $rec_arr = array();
            }

            if ($section == 'people') {
                foreach ($rec_arr as $rec_k => $item) {
                    $employer_sid = $item['employer_sid'];
                    $this->db->select('sid');
                    $this->db->select('first_name');
                    $this->db->select('last_name');
                    $this->db->select('email');
                    $this->db->select('PhoneNumber');
                    $this->db->select('profile_picture');
                    $this->db->where('sid', $employer_sid);
                    $this->db->where('active', 1);
                    $this->db->where('archived', 0);
                    $this->db->where('terminated_status', 0);
                    $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                    $employer_obj = $this->db->get('users');
                    $employer_arr = $employer_obj->result_array();
                    $employer_obj->free_result();

                    if (!empty($employer_arr)) {
                        $rec_arr[$rec_k]['employee_info'] = $employer_arr[0];
                    } else {
                        $rec_arr[$rec_k]['employee_info'] = array();
                    }
                }
            }

            $records_arr[$key]['items_details'] = $rec_arr;
        }

        return $records_arr;
    }

    public function get_onboarding_applicants($applicant_sid)
    {
        $this->db->select('*');
        $this->db->where('applicant_sid', $applicant_sid);

        $record_obj = $this->db->get('onboarding_applicants');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    public function get_ems_notifications($company_sid, $applicant_sid)
    {
        $this->db->select('title,description,video_source,video_status,video_url,image_status,assigned_to,image_code');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->where('assigned_to', 'all');
        $this->db->order_by('sort_order', 'ASC');
        $result = $this->db->get('ems_dashboard_notification')->result_array();

        $this->db->select('title,description,video_source,video_status,video_url,image_status,assigned_to,image_code');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('ems_dashboard_notification_assigned.employee_sid', $applicant_sid);
        $this->db->where('status', 1);
        $this->db->where('assigned_to', 'specific');
        $this->db->join('ems_dashboard_notification_assigned', 'ems_dashboard_notification_assigned.ems_notification_sid=ems_dashboard_notification.sid', 'left');
        $this->db->order_by('sort_order', 'ASC');
        $result2 = $this->db->get('ems_dashboard_notification')->result_array();
        $final_result = array_merge($result, $result2);
        return $final_result;
    }

    public function initialize_section_status_information($company_sid, $user_type, $user_sid)
    {
        $sections = array();
        $sections[] = 'general_information';
        $sections[] = 'bank_details';
        $sections[] = 'emergency_contacts';
        $sections[] = 'occupational_license';
        $sections[] = 'drivers_license';
        $sections[] = 'dependents';
        $sections[] = 'full_employment_application';
        $sections[] = 'eeoc_form';
        $sections[] = 'required_equipments';
        $sections[] = 'documents';

        foreach ($sections as $section) {
            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['user_type'] = $user_type;
            $data_to_insert['user_sid'] = $user_sid;
            $data_to_insert['section'] = $section;
            $data_to_insert['save_count'] = 0;
            $data_to_insert['employer_sid'] = 0;
            $this->db->where('company_sid', $company_sid);
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('section', $section);
            $this->db->from('onboarding_applicants_progress');
            $count = $this->db->count_all_results();

            if ($count <= 0) {
                $this->db->insert('onboarding_applicants_progress', $data_to_insert);
            }
        }
    }

    public function increment_section_save_count($user_sid, $user_type, $section)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('section', $section);
        $this->db->set('save_count', 'save_count + 1', false);
        $this->db->update('onboarding_applicants_progress');
    }

    public function decrement_section_save_count($applicant_sid, $section)
    {
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('section', $section);
        $this->db->set('save_count', 'save_count - 1', false);
        $this->db->update('onboarding_applicants_progress');
    }

    public function get_onboarding_progress_percentage($user_type, $user_sid, $total_sections_count)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('save_count >', 0);
        $this->db->from('onboarding_applicants_progress');
        $count = $this->db->count_all_results();
        $progress = 100 * ($count / $total_sections_count);

        return $progress;
    }

    public function get_hr_documents($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('hr_documents');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    public function get_documents_information($documents_sids = array())
    {
        if (!empty($documents_sids)) {
            $this->db->select('*');
            $this->db->where_in('sid', $documents_sids);

            $records_obj = $this->db->get('hr_documents');
            $records_arr = $records_obj->result_array();
            $records_obj->free_results();

            return $records_arr;
        } else {
            return array();
        }
    }

    public function get_employee_info($company_sid, $employee_sid)
    {
        $this->db->select('sid');
        $this->db->select('access_level');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('PhoneNumber');
        $this->db->select('YouTubeVideo');
        $this->db->select('profile_picture');
        $this->db->select('email');
        $this->db->select('cell_number');
        $this->db->select('linkedin_profile_url');
        $this->db->select('extra_info');
        $this->db->select('job_title');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $employee_sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    public function insert_default_sections($company_sid)
    {
        $section_data = array();
        $section_data['company_sid'] = $company_sid;
        $section_data['section_title'] = 'Welcome';
        $section_data['section_unique_id'] = 'section-1';
        $section_data['section_content'] = htmlentities('<p>First we would like to Welcome you to our team.</p><p>This area is going to help guide you through the Onboarding steps. We will help you learn about the company, walk you through completing your onboarding documentation, connect with your new team members, answer any questions you may have and prepare you for success.</p><p>You will notice some helpful tabs above. Be sure to select and complete the information in each of the Tabs above and complete your Onboarding</p>');
        $section_data['section_sort_order'] = 10;
        $this->db->insert('onboarding_getting_started_sections', $section_data);

        $section_data = array();
        $section_data['company_sid'] = $company_sid;
        $section_data['section_title'] = 'Day 1';
        $section_data['section_unique_id'] = 'section-2';
        $section_data['section_content'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor eu sem in porttitor. Ut tristique faucibus odio ut auctor. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas lobortis sapien dolor, ac lobortis odio accumsan in. Nunc accumsan rutrum ullamcorper. Integer est metus, facilisis eu nulla quis, fringilla venenatis dui. Pellentesque vel semper arcu, vel mollis enim. Pellentesque at metus hendrerit, euismod orci quis, viverra ex. Integer gravida hendrerit eros eu convallis. Aenean facilisis eleifend erat ut tristique. Cras elementum tincidunt ex eu elementum. Suspendisse tincidunt nibh fringilla mauris faucibus, quis cursus tellus gravida.';
        $section_data['section_sort_order'] = 20;
        $this->db->insert('onboarding_getting_started_sections', $section_data);

        $section_data = array();
        $section_data['company_sid'] = $company_sid;
        $section_data['section_title'] = 'Week 1';
        $section_data['section_unique_id'] = 'section-3';
        $section_data['section_content'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor eu sem in porttitor. Ut tristique faucibus odio ut auctor. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas lobortis sapien dolor, ac lobortis odio accumsan in. Nunc accumsan rutrum ullamcorper. Integer est metus, facilisis eu nulla quis, fringilla venenatis dui. Pellentesque vel semper arcu, vel mollis enim. Pellentesque at metus hendrerit, euismod orci quis, viverra ex. Integer gravida hendrerit eros eu convallis. Aenean facilisis eleifend erat ut tristique. Cras elementum tincidunt ex eu elementum. Suspendisse tincidunt nibh fringilla mauris faucibus, quis cursus tellus gravida.';
        $section_data['section_sort_order'] = 30;
        $this->db->insert('onboarding_getting_started_sections', $section_data);

        $section_data = array();
        $section_data['company_sid'] = $company_sid;
        $section_data['section_title'] = 'Month 1';
        $section_data['section_unique_id'] = 'section-4';
        $section_data['section_content'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor eu sem in porttitor. Ut tristique faucibus odio ut auctor. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas lobortis sapien dolor, ac lobortis odio accumsan in. Nunc accumsan rutrum ullamcorper. Integer est metus, facilisis eu nulla quis, fringilla venenatis dui. Pellentesque vel semper arcu, vel mollis enim. Pellentesque at metus hendrerit, euismod orci quis, viverra ex. Integer gravida hendrerit eros eu convallis. Aenean facilisis eleifend erat ut tristique. Cras elementum tincidunt ex eu elementum. Suspendisse tincidunt nibh fringilla mauris faucibus, quis cursus tellus gravida.';
        $section_data['section_sort_order'] = 40;
        $this->db->insert('onboarding_getting_started_sections', $section_data);

        $section_data = array();
        $section_data['company_sid'] = $company_sid;
        $section_data['section_title'] = 'Year 1';
        $section_data['section_unique_id'] = 'section-5';
        $section_data['section_content'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor eu sem in porttitor. Ut tristique faucibus odio ut auctor. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas lobortis sapien dolor, ac lobortis odio accumsan in. Nunc accumsan rutrum ullamcorper. Integer est metus, facilisis eu nulla quis, fringilla venenatis dui. Pellentesque vel semper arcu, vel mollis enim. Pellentesque at metus hendrerit, euismod orci quis, viverra ex. Integer gravida hendrerit eros eu convallis. Aenean facilisis eleifend erat ut tristique. Cras elementum tincidunt ex eu elementum. Suspendisse tincidunt nibh fringilla mauris faucibus, quis cursus tellus gravida.';
        $section_data['section_sort_order'] = 50;
        $this->db->insert('onboarding_getting_started_sections', $section_data);
    }

    public function sign_document($company_sid, $user_type, $user_sid, $document_assignment_sid, $data_to_update)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('sid', $document_assignment_sid);
        $this->db->update('documents_assignment', $data_to_update);
    }

    public function get_employee_information($company_sid, $employee_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('PhoneNumber');
        $this->db->select('profile_picture');
        $this->db->select('active');
        $this->db->select('parent_sid');
        $this->db->select('is_executive_admin');
        $this->db->select('cover_letter');
        $this->db->select('resume');
        $this->db->select('emp_offer_letter_key');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $employee_sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    public function get_security_access_levels()
    {
        $this->db->select('access_level');
        $this->db->where('status', 1);
        $access_levels = $this->db->get('security_access_level')->result_array();
        $my_return = array();

        foreach ($access_levels as $access_level) {
            $my_return[] = $access_level['access_level'];
        }

        return $my_return;
    }

    public function check_if_user_already_exists($email_address, $company_sid)
    {
        $this->db->select('sid');
        $this->db->where('email', $email_address);
        $this->db->where('parent_sid', $company_sid);
        $this->db->from('users');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_job_list_data($job_list_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $job_list_sid);
        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function emergency_contacts_details($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('emergency_contacts')->result_array();
        return $result;
    }

    function edit_emergency_contacts($data, $sid)
    {
        $this->db->where('sid', $sid);
        $this->db->update('emergency_contacts', $data);
        $result = $this->db->affected_rows();

        if ($result != 1) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Could not update emergency contact, Please try Again!');
            $result = 'success';
        } else {
            $this->session->set_flashdata('message', '<b>Success: </b>Emergency contact updated successfully.');
            $result = 'fail';
        }

        return $result;
    }

    function dependant_details($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('dependant_information')->result_array();
        return $result;
    }

    function update_dependant_info($dependant_id, $dependantData)
    {
        $this->db->where('sid', $dependant_id)
            ->update('dependant_information', $dependantData);
    }

    function get_single_office_locations($location_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $location_sid);
        $records_obj = $this->db->get('onboarding_office_locations');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_updated_sections($company_sid, $user_type, $user_sid)
    {
        $data_to_return = array();
        $data_to_return['getting_started'] = 1;
        $data_to_return['my_profile'] = $this->check_if_profile_updated($company_sid, $user_type, $user_sid);
        $data_to_return['e_signature'] = $this->check_if_e_signature_updated($company_sid, $user_type, $user_sid);
        $data_to_return['documents'] = $this->check_if_documents_updated($company_sid, $user_type, $user_sid);
        $data_to_return['license_info'] = $this->check_if_licenses_updated($company_sid, $user_type, $user_sid);
        $data_to_return['direct_deposit'] = $this->check_if_direct_deposit_updated($company_sid, $user_type, $user_sid);
        $data_to_return['dependents'] = $this->check_if_dependents_updated($company_sid, $user_type, $user_sid);
        $data_to_return['emergency_contacts'] = $this->check_if_emergency_contacts_updated($company_sid, $user_type, $user_sid);
        $data_to_return['eeoc_form'] = $this->check_if_eeoc_form_updated($company_sid, $user_type, $user_sid);
        return $data_to_return;
    }

    function check_if_profile_updated($company_sid, $user_type, $user_sid)
    {
        $this->db->select('sid');
        $this->db->where('sid', $user_sid);
        $this->db->where('employer_sid', $company_sid);
        $this->db->group_start();
        $this->db->where('address !=', '');
        $this->db->or_where('pictures !=', '');
        $this->db->group_end();
        $this->db->from('portal_job_applications');
        return $this->db->count_all_results();
    }

    function check_if_e_signature_updated($company_sid, $user_type, $user_sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('is_active', 1);
        $this->db->where('user_consent', 1);
        $this->db->from('e_signatures_data');
        return $this->db->count_all_results();
    }

    function check_if_documents_updated($company_sid, $user_type, $user_sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent', 1);
        $this->db->or_where('acknowledged', 1);
        $this->db->or_where('downloaded', 1);
        $this->db->or_where('uploaded', 1);
        $this->db->group_end();
        $this->db->from('documents_assignment');

        return $this->db->count_all_results();
    }

    function check_if_licenses_updated($company_sid, $user_type, $user_sid)
    {
        $this->db->select('sid');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->from('license_information');
        return $this->db->count_all_results();
    }

    function check_if_direct_deposit_updated($company_sid, $user_type, $user_sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->from('bank_account_details');
        return $this->db->count_all_results();
    }

    function check_if_dependents_updated($company_sid, $user_type, $user_sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->from('dependant_information');
        return $this->db->count_all_results();
    }

    function check_if_emergency_contacts_updated($company_sid, $user_type, $user_sid)
    {
        $this->db->select('sid');
        //$this->db->where('company_sid', $company_sid);
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->from('emergency_contacts');
        return $this->db->count_all_results();
    }

    function get_all_offer_letters($company_sid, $employeeSid = false)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('archive', 0);
        $this->db->group_start();
        $this->db->where('is_specific', 0);
        if ($employeeSid) $this->db->or_where('is_specific', $employeeSid);
        $this->db->group_end();
        $this->db->from('offer_letter');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function check_if_eeoc_form_updated($company_sid, $user_type, $user_sid)
    {
        $this->db->select('sid');
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid', $user_sid);
        $this->db->from('portal_eeo_form');
        return $this->db->count_all_results();
    }

    function check_company_eeoc_form_status($company_sid)
    {
        $this->db->select('eeo_form_status');
        $this->db->where('user_sid', $company_sid);
        $this->db->from('portal_employer');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            $records_arr = $records_arr[0];
            $eeoc_status = $records_arr['eeo_form_status'];

            return $eeoc_status;
        } else {
            return 0;
        }
    }

    function check_company_ssn_dob($company_sid)
    {
        $this->db->select('ssn_required,dob_required');
        $this->db->where('user_sid', $company_sid);
        $this->db->from('portal_employer');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            $records_arr = $records_arr[0];
            return $records_arr;
        } else {
            return 0;
        }
    }

    function assign_online_videos($data)
    {
        $this->db->insert('learning_center_online_videos_assignments', $data);
        return $this->db->insert_id();
    }

    function update_assign_online_videos($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('learning_center_online_videos_assignments', $data);
    }

    function update_specific_online_videos($user_sid, $user_type, $data)
    {
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->update('learning_center_online_videos_assignments', $data);
    }

    function unassign_online_videos($user_sid, $user_type, $data)
    {
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->update('learning_center_online_videos_assignments', $data);
    }

    function check_already_assign($user_sid, $user_type, $id)
    {
        $this->db->select('sid');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('learning_center_online_videos_sid', $id);
        $result_array = $this->db->get('learning_center_online_videos_assignments')->result_array();
        return $result_array;
    }

    function assign_training($data)
    {
        $this->db->insert('learning_center_training_sessions_assignments', $data);
        return $this->db->insert_id();
    }

    function update_specific_training_session($user_sid, $user_type, $data)
    {
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->update('learning_center_training_sessions_assignments', $data);
    }

    function unassign_training_session($user_sid, $user_type, $data)
    {
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->update('learning_center_training_sessions_assignments', $data);
    }

    function check_already_assign_training($user_sid, $user_type, $id)
    {
        $this->db->select('sid');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('training_session_sid', $id);
        $result_array = $this->db->get('learning_center_training_sessions_assignments')->result_array();
        return $result_array;
    }

    function update_assign_training($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('learning_center_training_sessions_assignments', $data);
    }

    function get_w4_form($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('status', 1);
        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_w9_form_assigned($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        //        $this->db->where('status', 1);
        $this->db->from('applicant_w9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_w9_form($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('status', 1);
        $this->db->from('applicant_w9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_w4_form_exist($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_w9_form_exist($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->from('applicant_w9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_original_w4_form_assigned($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        //        $this->db->where('status', 1);
        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_original_w4_form($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('status', 1);
        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function deactivate_w4_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->set('status', 0);
        $this->db->from('form_w4_original');
        $this->db->update();
    }

    function activate_w4_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->set('status', 1);
        $this->db->from('form_w4_original');
        $this->db->update();
    }

    function deactivate_i9_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('status', 0);
        $this->db->from('applicant_i9form');
        $this->db->update();
    }

    function activate_i9_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('status', 1);
        $this->db->from('applicant_i9form');
        $this->db->update();
    }

    function deactivate_w9_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('status', 0);
        $this->db->from('applicant_w9form');
        $this->db->update();
    }

    function activate_w9_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('status', 1);
        $this->db->from('applicant_w9form');
        $this->db->update();
    }

    function insert_w4_form_record($data_to_insert)
    {
        $this->db->insert('form_w4_original', $data_to_insert);
    }

    function get_i9_form_assigned($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        //        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_i9_form($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function insert_i9_form_record($data_to_insert)
    {
        $data_to_insert['emp_app_sid'] = $data_to_insert['user_sid'];
        $this->db->insert('applicant_i9form', $data_to_insert);
    }

    function insert_w9_form_record($data_to_insert)
    {
        $data_to_insert['emp_app_sid'] = $data_to_insert['user_sid'];
        $this->db->insert('applicant_i9form', $data_to_insert);
    }

    function get_old_system_documents($user_type, $user_sid)
    {
        if ($user_type == 'applicant') {
            $this->db->select('sid');
            $this->db->where('applicant_sid', $user_sid);
            $this->db->from('users');
            $user_obj = $this->db->get();
            $user_arr = $user_obj->result_array();
            $user_obj->free_result();

            if (!empty($user_arr)) {
                $user_sid = $user_arr[0]['sid'];
            } else { // old documents not found
                return array();
                $user_sid = -1;
            }
        }

        $this->db->select('hr_user_document.*');
        $this->db->select('hr_documents.document_name');
        $this->db->select('hr_documents.document_original_name');
        $this->db->select('hr_documents.document_type');
        $this->db->select('hr_documents.document_description');
        $this->db->select('hr_documents.onboarding');
        $this->db->select('hr_documents.action_required');
        $this->db->select('hr_documents.to_all_employees');
        $this->db->select('hr_documents.archive');
        $this->db->join('hr_documents', 'hr_user_document.document_sid = hr_documents.sid', 'left');
        $this->db->where('hr_user_document.receiver_sid', $user_sid);
        $this->db->from('hr_user_document');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function insert_dashboard_notification($data)
    {
        $this->db->insert('ems_dashboard_notification', $data);
        return $this->db->insert_id();
    }

    function insert_assigned_configuration($data)
    {
        $this->db->insert('ems_dashboard_notification_assigned', $data);
        return $this->db->insert_id();
    }

    function get_all_ems_notification($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('ems_dashboard_notification');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_ems_notification($id, $data)
    {
        $this->db->where('sid', $id);
        $this->db->update('ems_dashboard_notification', $data);
    }

    function get_ems_notification_by_id($sid)
    {
        $this->db->select('*');
        $this->db->where('ems_dashboard_notification.sid', $sid);
        //        $this->db->join('ems_dashboard_notification_assigned','ems_dashboard_notification_assigned.ems_notification_sid = ems_dashboard_notification.sid','left');
        $records_obj = $this->db->get('ems_dashboard_notification');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $records_arr[0]['assigned_emp'] = array();

        if ($records_arr[0]['assigned_to'] == 'specific') {
            $this->db->select('employee_sid');
            $this->db->where('ems_dashboard_notification_assigned.ems_notification_sid', $sid);
            $result = $this->db->get('ems_dashboard_notification_assigned')->result_array();

            foreach ($result as $sid) {
                $records_arr[0]['assigned_emp'][] = $sid['employee_sid'];
            }
        }

        return $records_arr;
    }

    function delete_assigned_configuration($selected, $sid)
    {
        $this->db->where('ems_notification_sid', $sid);
        $this->db->where('employee_sid', $selected);
        $this->db->delete('ems_dashboard_notification_assigned');
    }

    function get_equipment_info($type, $users_sid)
    {
        $this->db->where('users_type', $type);
        $this->db->where('users_sid', $users_sid);
        $this->db->where('delete_flag', 0);
        return $this->db->get('equipment_information')->result_array();
    }

    function get_assigned_custom_office_record_sids($company_sid, $user_sid, $user_type, $custom_type, $is_custom = 0)
    {
        if ($is_custom != 2) {
            $this->db->select('parent_sid');
            $this->db->where('is_custom', $is_custom);
        } else {
            $this->db->select('*');
        }

        $this->db->where('custom_type', $custom_type);
        $this->db->where('user_type', $user_type);
        $this->db->where('status', 1);

        if ($user_type == 'employee') {
            $this->db->where('employee_sid', $user_sid);
        } else {
            $this->db->where('applicant_sid', $user_sid);
        }

        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('onboarding_custom_assignment');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_user_name($company_sid, $user_sid)
    {
        $this->db->select('first_name, last_name');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $user_sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_assigned_documents($company_sid, $user_type, $user_sid = null, $status = 1, $fetch_offer_letter = 1)
    {
        $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);

        if ($fetch_offer_letter) {
            $this->db->where('documents_assigned.document_type <>', 'offer_letter');
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function set_offer_letter_verification_key($sid, $verification_key, $type = 'applicant')
    {
        $this->db->where('sid', $sid);

        if ($type == 'applicant') {
            $dataToUpdate = array();
            $dataToUpdate['verification_key'] = $verification_key;
            $this->db->update('portal_job_applications', $dataToUpdate);
        } else {

            $dataToUpdate = array();
            $dataToUpdate['emp_offer_letter_key'] = $verification_key;
            $this->db->update('users', $dataToUpdate);
        }
    }

    function get_offer_letter($company_sid, $offer_letter_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $offer_letter_sid);
        $this->db->from('offer_letter');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function check_if_document_already_assigned($user_type, $user_sid, $document_type, $document_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function insert_documents_assignment_record_history($data_to_insert)
    {
        $this->db->insert('documents_assigned_history', $data_to_insert);
    }

    function update_assign_offer_letter($user_type, $user_sid, $document_type, $data_to_update)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        $this->db->update('documents_assigned', $data_to_update);
    }

    function insert_documents_assignment_record($data_to_insert)
    {
        $this->db->insert('documents_assigned', $data_to_insert);
        return $this->db->insert_id();
    }

    function save_email_logs($data)
    {
        $this->db->insert('email_log', $data);
    }

    function get_required_document_info($company_sid, $user_sid, $user_type, $document_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('sid', $document_sid);
        $this->db->where('status', 1);
        $this->db->where('archive', 0);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function check_applicant_exist($verification_key, $type = 'a')
    {
        $this->db->select('*');
        if ($type == 'a') {
            $this->db->where('verification_key', $verification_key);
            $record_obj = $this->db->get('portal_job_applications');
        } else {
            $this->db->where('emp_offer_letter_key', $verification_key);
            $record_obj = $this->db->get('users');
        }
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function check_applicant_offer_letter_exist($company_sid, $user_type, $user_sid, $document_type)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function check_my_offer_letter_exist($company_sid, $user_type, $user_sid, $document_type)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        $this->db->where('status', 1);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function check_offer_letter_moved($document_sid, $document_type)
    {
        $this->db->select('*');;
        $this->db->where('doc_sid', $document_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assigned_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    function disable_all_previous_letter($company_sid, $user_type, $user_sid, $document_type)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_type', $document_type);
        $this->db->set('status', 0);
        $this->db->set('archive', 1);
        $this->db->update('documents_assigned');
    }

    function get_offer_letter_feature_info($document_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $document_sid);

        $record_obj = $this->db->get('offer_letter');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file)
    {

        $now = date('Y-m-d H:i:s');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_sid', $document_sid);
        $this->db->where('document_type', $document_type);
        $this->db->where('status', 1);
        $this->db->set('user_consent', 1);
        $this->db->set('uploaded', 1);
        $this->db->set('uploaded_date', $now);
        $this->db->set('uploaded_file', $uploaded_file);
        $this->db->update('documents_assigned');
    }

    function get_applicant_company_info($company_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $company_sid);

        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_assign_offer_letter_info($offer_letter_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $offer_letter_sid);

        $record_obj = $this->db->get('offer_letter');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function revoke_applicant_offer_letter($offer_letter_sid, $data_to_update)
    {
        $this->db->where('sid', $offer_letter_sid);
        $this->db->update('documents_assigned', $data_to_update);
    }

    function get_assigned_offer_letter($company_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', 'offer_letter');
        $this->db->where('status', 1);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $record_arr['signers'] = [];
            //
            $a = $this->db
                ->select('group_concat(assigned_to_sid) as signers')
                ->where('document_assigned_sid', $record_arr[0]['sid'])
                ->get('authorized_document_assigned_manager');
            //
            $b = $a->row_array();
            $a = $a->free_result();
            //
            if (!empty($b)) {
                $record_arr[0]['signers'] = $b['signers'];
            }
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_assigned_and_signed_offer_letter($company_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', 'offer_letter');
        $this->db->where('status', 1);
        $this->db->where('user_consent', 1);
        $this->db->where('archive', 0);
        $this->db->order_by('uploaded_date', 'DESC');
        $this->db->order_by('signature_timestamp', 'DESC');

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function is_offer_letter_assign($company_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', 'offer_letter');
        $this->db->where('status', 1);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return 1;
        } else {
            return 0;
        }
    }

    function get_signed_offer_letter_from_history($company_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', 'offer_letter');
        $this->db->where('status', 1);
        $this->db->where('user_consent', 1);
        $this->db->order_by('uploaded_date', 'DESC');
        $this->db->order_by('signature_timestamp', 'DESC');

        $record_obj = $this->db->get('documents_assigned_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_applicant_resume_history($company_sid, $user_sid)
    {
        $this->db->select('old_resume_s3_name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('job_sid', NULL);
        $this->db->where('job_type', NULL);
        $this->db->where('request_status <>', 0);
        $this->db->where('old_resume_s3_name <>', NULL);
        $this->db->order_by('sid', 'DESC');

        $record_obj = $this->db->get('resume_request_logs');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_current_resume($company_sid, $applicant_sid)
    {
        $this->db->select('resume');
        $this->db->where('sid', $applicant_sid);
        $this->db->where('employer_sid', $company_sid);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr[0]['resume'];
        } else {
            return array();
        }
    }

    function get_current_resume_old($company_sid, $user_sid)
    {
        $this->db->select('resume');
        $this->db->where('sid', $user_sid);
        $this->db->where('employer_sid', $company_sid);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['resume'];
        } else {
            $this->db->select('portal_applicant_jobs_list.resume');
            $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');
            $this->db->where('portal_job_applications.sid', $applicant_sid);
            $this->db->where('portal_job_applications.employer_sid', $company_sid);
            $record_obj = $this->db->get('portal_job_applications');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            if (sizeof($record_arr)) return $record_arr[0]['resume'];
            return array();
        }
    }

    function get_this_resume_history($company_sid, $user_sid, $job_sid, $job_type)
    {
        $this->db->select('old_resume_s3_name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('job_sid', $job_sid);
        $this->db->where('job_type', $job_type);
        $this->db->where('request_status <>', 0);
        $this->db->where('old_resume_s3_name <>', NULL);
        $this->db->order_by('sid', 'DESC');

        $record_obj = $this->db->get('resume_request_logs');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr;
        }
    }

    function get_current_resume_by_job_detail($portal_job_applications_sid, $company_sid)
    {
        $this->db->select('portal_applicant_jobs_list.sid,portal_applicant_jobs_list.job_sid,portal_job_listings.title,portal_applicant_jobs_list.resume,portal_applicant_jobs_list.desired_job_title,portal_applicant_jobs_list.last_update')
            ->where('portal_applicant_jobs_list.portal_job_applications_sid', $portal_job_applications_sid)
            ->where('portal_applicant_jobs_list.company_sid', $company_sid)
            ->where('portal_applicant_jobs_list.resume <>', '')
            ->where('portal_applicant_jobs_list.resume <>', NULL)
            ->order_by('portal_applicant_jobs_list.last_update', 'DESC')
            ->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');

        $record_obj = $this->db->get('portal_applicant_jobs_list');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    /**
     * Fettcha ll resume istory
     *
     * @param int $company_sid 
     * @param int $company_sid 
     * @param int $company_sid 
     * 
     * @uses testFunction
     * 
     * @return array
     *
     */
    function get_old_resume($company_sid, $user_type, $user_sid)
    {
        $this->db->select('resume_request_logs.sid,resume_request_logs.requested_date as request_date,resume_request_logs.job_sid,portal_job_listings.title,resume_request_logs.old_resume_s3_name,resume_request_logs.job_type,portal_applicant_jobs_list.desired_job_title')
            ->where('resume_request_logs.company_sid', $company_sid)
            ->where('resume_request_logs.user_type', $user_type)
            ->where('resume_request_logs.user_sid', $user_sid)
            ->where('resume_request_logs.old_resume_s3_name <>', null)
            ->where('resume_request_logs.old_resume_s3_name <>', 'error')
            // $this->db->where('resume_request_logs.job_sid <>',0);
            ->join('portal_job_listings', 'portal_job_listings.sid = resume_request_logs.job_sid', 'left')
            ->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.sid = resume_request_logs.job_sid', 'left')
            ->order_by('resume_request_logs.sid', 'DESC');
        $record_obj = $this->db->get('resume_request_logs');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return sizeof($record_arr) ? $record_arr : array();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_old_resume_old($company_sid, $user_type, $user_sid)
    {
        $this->db->select('old_resume_s3_name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->order_by('sid', 'DESC');

        $record_obj = $this->db->get('resume_request_logs');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_current_request_date($company_sid, $user_type, $user_sid)
    {
        $this->db->select('requested_date');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('request_status', 1);
        $this->db->where('is_respond', 0);

        $record_obj = $this->db->get('resume_request_logs');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['requested_date'];
        } else {
            return array();
        }
    }

    function reset_old_resume($company_sid, $user_sid)
    {
        $this->db->where('sid', $user_sid);
        $this->db->where('employer_sid', $company_sid);
        $this->db->set('resume', NULL);
        $this->db->update('portal_job_applications');
    }

    function deactivate_old_resume_request($company_sid, $user_type, $user_sid, $job_sid, $job_type)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('job_sid', $job_sid);
        $this->db->where('job_type', $job_type);
        $this->db->where('request_status', 1);
        $this->db->set('request_status', 0);
        $this->db->update('resume_request_logs');
    }

    function deactivate_old_resume_request_old($company_sid, $user_type, $user_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('request_status', 0);
        $this->db->update('resume_request_logs');
    }

    function get_user_resume_request($company_sid, $user_type, $user_sid, $job_sid, $job_type)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('job_sid', $job_sid);
        $this->db->where('job_type', $job_type);
        $this->db->where('request_status', 1);

        $record_obj = $this->db->get('resume_request_logs');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_user_resume_request_old($company_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('request_status', 1);

        $record_obj = $this->db->get('resume_request_logs');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function insert_resume_request($data_to_insert)
    {
        $this->db->insert('resume_request_logs', $data_to_insert);
    }

    function update_resume_request($company_sid, $user_sid, $user_type, $job_sid, $job_type, $data_to_update)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('request_status', 1);
        $this->db->where('is_respond', 0);
        $this->db->where('job_sid', $job_sid);
        $this->db->where('job_type', $job_type);
        $this->db->update('resume_request_logs', $data_to_update);
    }

    function update_resume_request_old($company_sid, $user_sid, $user_type, $data_to_update)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('request_status', 1);
        $this->db->where('is_respond', 0);
        $this->db->update('resume_request_logs', $data_to_update);
    }

    function update_applicant_resume($applicant_sid, $resume)
    {
        $this->db->where('sid', $applicant_sid);
        $this->db->set('resume', $resume);
        $this->db->update('portal_job_applications');
    }

    function get_send_resume_template($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('template_code', 'send-resume-request');
        $this->db->where('is_custom', 0);

        $record_obj = $this->db->get('portal_email_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_single_job_detail($portal_job_applications_sid, $company_sid, $job_sid, $job_type = '')
    {
        $this->db->select('resume');

        if ($job_type == 'job') {
            $this->db->where('job_sid', $job_sid);
        } else if ($job_type == 'desired_job') {
            $this->db->where('sid', $job_sid);
        } else if ($job_type == 'job_not_applied') {
            $this->db->where('job_sid', $job_sid);
            $this->db->where('desired_job_title', NULL);
        }

        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
        $this->db->where('company_sid', $company_sid);

        $record_obj = $this->db->get('portal_applicant_jobs_list');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['resume'];
        } else {
            return '';
        }
    }

    function update_resume_applicant_job_list($portal_job_applications_sid, $company_sid, $job_sid, $job_type, $resume)
    {
        if ($job_type == 'job') {
            $this->db->where('job_sid', $job_sid);
        } else if ($job_type == 'desired_job') {
            $this->db->where('sid', $job_sid);
        } else if ($job_type == 'job_not_applied') {
            $this->db->where('job_sid', $job_sid);
            $this->db->where('desired_job_title', NULL);
        }

        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
        $this->db->where('company_sid', $company_sid);

        $this->db->update('portal_applicant_jobs_list', array('resume' => $resume, 'last_update' => date('Y-m-d')));
    }

    function get_job_title($job_sid, $user_sid)
    {
        $this->db->select('Title');
        $this->db->where('sid', $job_sid);
        // $this->db->where('user_sid', $user_sid);

        $record_obj = $this->db->get('portal_job_listings');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['Title'];
        } else {
            return '';
        }
    }

    function get_desired_job_title($job_listing_sid, $company_sid)
    {
        $this->db->select('desired_job_title');
        $this->db->where('sid', $job_listing_sid);
        $this->db->where('company_sid', $company_sid);

        $record_obj = $this->db->get('portal_applicant_jobs_list');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['desired_job_title'];
        } else {
            return array();
        }
    }

    function get_current_assigned_offer_letter($company_sid, $user_type, $user_sid = null, $status = 1)
    {
        $this->db->select('documents_assigned.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.document_type', 'offer_letter');
        $this->db->where('documents_assigned.archive', 0);

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->join('offer_letter', 'offer_letter.sid = documents_assigned.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    //
    function delete_from_oirignal_table($sid)
    {
        $this->db
            ->where('sid', $sid)
            ->delete('documents_assigned');
    }



    function get_company_disclosure($company_sid, $employee_sid = 0, $applicant_sid = 0)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if ($employee_sid > 0) {
            $this->db->where('employee_sid', $employee_sid);
        }

        if ($applicant_sid > 0) {
            $this->db->where('applicant_sid', $applicant_sid);
        }

        $records_obj = $this->db->get('onboarding_disclosure');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if ($employee_sid > 0 || $applicant_sid > 0) {
            if (empty($records_arr)) {
                $this->db->select('*');
                $this->db->where('company_sid', $company_sid);
                $records_obj = $this->db->get('onboarding_disclosure');
                $records_arr = $records_obj->result_array();
                $records_obj->free_result();
            }
        }

        return $records_arr;
    }

    function insert_update_onboarding_disclosure($data, $sid)
    {
        if ($sid > 0) {
            $this->db->where('sid', $sid);
            $this->db->update('onboarding_disclosure', $data);
        } else {
            $this->db->insert('onboarding_disclosure', $data);
        }
    }


    function save_update_setup_onboarding_disclosure($disclosure, $user_type, $user_sid, $company_sid, $modified_by_sid)
    {
        $data = array();
        $data['disclosure'] = $disclosure;
        $data['company_sid'] = $company_sid;
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['modified_by_sid'] = $modified_by_sid;

        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);

        if ($user_type == 'applicant') {
            $data['applicant_sid'] = $user_sid;
            $this->db->where('applicant_sid', $user_sid);
        } else {
            $data['employee_sid'] = $user_sid;
            $this->db->where('employee_sid', $user_sid);
        }

        $records_obj = $this->db->get('onboarding_disclosure');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (empty($records_arr)) { // record does not exist, add it to database
            $this->db->insert('onboarding_disclosure', $data);
        } else {
            $sid = $records_arr['0']['sid'];
            $this->db->where('sid', $sid);
            $this->db->update('onboarding_disclosure', $data);
        }
    }


    public function get_notification_email_configuration($companySid)
    {
        return $this->db
            ->where('company_sid', $companySid)
            ->where('general_information_status', 1)
            ->get('notifications_emails_configuration')->count_all_results();
    }


    /**
     * 
     */
    public function getPrimaryAddress(
        int $companyId
    )
    {
        return $this->db
        ->select(
            '
                location_address,
                location_telephone,
                location_fax
            '
        )
        ->where([
            'company_sid' => $companyId,
            'is_primary' => 1
        ])
        ->get('onboarding_office_locations')
        ->row_array();
    }
}
