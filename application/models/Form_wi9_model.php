<?php

class Form_wi9_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function insert_form_data($form_name, $data, $employer_sid)
    {
        if ($form_name == 'w4') {
            $this->db->select('sid');
            $this->db->where('employee_sid', $employer_sid);
            $result = $this->db->get('applicant_w4form')->result_array();
            if (sizeof($result) > 0) {
                $this->db->where('sid', $result[0]['sid']);
                $this->db->update('applicant_w4form', $data);
            } else {
                $this->db->insert('applicant_w4form', $data);
            }
        } else {
            $this->db->select('sid');
            $this->db->where('emp_app_sid', $employer_sid);
            $result = $this->db->get('applicant_i9form')->result_array();
            if (sizeof($result) > 0) {
                $this->db->where('sid', $result[0]['sid']);
                $this->db->update('applicant_i9form', $data);
            } else {
                $data['emp_app_sid'] = $data['user_sid'];
                $this->db->insert('applicant_i9form', $data);
            }
        }
        return $this->db->insert_id();
    }

    function fetch_form($form_name, $user_type, $user_sid)
    {
        //        $this->db->select('*, "1" as status, "1" as user_consent, "1" as manual, employee_sid as user_sid');
        //        $this->db->where('employee_sid', $user_sid);
        //        $this->db->where('document_type ', $form_name);
        //        $this->db->from('eev_documents');
        //
        //        $a = $this->db->get();
        //        $b = $a->row_array();
        //        $a->free_result();
        //
        //        if(sizeof($b)) return $b;

        $this->db->select('*');
        if ($form_name == 'w4') {
            $this->db->where('user_type', $user_type);
            $this->db->where('employer_sid', $user_sid);
            $this->db->where('status', 1);
            $this->db->from('form_w4_original');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } elseif ($form_name == 'w9') {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('status', 1);
            $this->db->from('applicant_w9form');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } else {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('status', 1);
            $this->db->from('applicant_i9form');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        }
        if (sizeof($records_arr) > 0) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_user($user_sid, $company_sid)
    {
        $this->db->select('parent_sid');
        $this->db->where('sid', $user_sid);
        $result = $this->db->get('users')->result_array();

        if ($result[0]['parent_sid'] == $company_sid) {
            return true;
        } else {
            return false;
        }
    }

    function insert_form_docs($docs_data)
    {
        $this->db->insert('form_i9_docs', $docs_data);
        return $this->db->insert_id();
    }

    function form_docs($form_id)
    {
        $this->db->select('sid,file_code,upload_date,file_name,verified');
        $this->db->where('form_id', $form_id);
        //        $this->db->where('applicant_sid <>',NULL);
        $files = $this->db->get('form_i9_docs')->result_array();
        return $files;
    }

    function mark_as_verified($doc_id, $data)
    {
        $this->db->where('sid', $doc_id);
        $this->db->update('form_i9_docs', $data);
    }

    function getApplicantAverageRating($app_id, $users_type = NULL, $date = NULL)
    {
        $this->db->where('applicant_job_sid', $app_id);

        if ($users_type != NULL) {
            $this->db->where('users_type', $users_type);
        }

        if ($date != NULL) { // get all rating after his/her hiring date.
            $this->db->where('date_added >', $date);
        }

        $this->db->from('portal_applicant_rating');
        $rows = $this->db->count_all_results();

        //        echo $this->db->last_query(); exit;
        if ($rows > 0) {
            $this->db->select_sum('rating');
            $this->db->where('applicant_job_sid', $app_id);
            $this->db->where('users_type', $users_type);
            $this->db->from('portal_applicant_rating');
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            $data = round($records_arr[0]['rating'] / $rows, 2);
            return $data;
        }
    }

    function getApplicantNotes($app_id)
    {
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $app_id);
        $this->db->order_by('sid', 'DESC');
        $this->db->from('portal_misc_notes');
        $records_obj = $this->db->get();
        $result = $records_obj->result_array();
        $records_obj->free_result();

        return $result;
    }

    function get_employee_information($company_sid, $employee_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $employee_sid);
        $this->db->where('parent_sid', $company_sid);
        $this->db->from('users');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_applicants_details($sid)
    {
        $this->db->select('sid, employer_sid as company_sid, first_name, last_name, email, address, city, country, state, zipcode, phone_number, pictures');
        $this->db->where('sid', $sid);
        $this->db->from('portal_job_applications');
        $data = array();

        $records_obj = $this->db->get();
        $result = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($result)) {
            $data = $result[0];
        }

        return $data;
    }

    function update_form($form_type, $user_type, $user_sid, $data_to_update)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('status', 1);

        if ($form_type == 'w4') {
            $this->db->where('employer_sid', $user_sid);
            $this->db->update('form_w4_original', $data_to_update);
        } elseif ($form_type == 'w9') {
            $this->db->where('user_sid', $user_sid);
            $this->db->update('applicant_w9form', $data_to_update);
        } else if ($form_type == 'i9') {
            $this->db->where('user_sid', $user_sid);
            $this->db->update('applicant_i9form', $data_to_update);
        }
    }

    function do_manual_upload_form($sid, $upload_form_array, $form = 'w4')
    {
        $this->db->where('sid', $sid);
        if ($form == 'w4') {
            $this->db->update('form_w4_original', $upload_form_array);
        } else if ($form == 'w9') {
            $this->db->update('applicant_w9form', $upload_form_array);
        }
    }

    function check_i9_exist($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
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

    function i9_forms_history($data_to_insert)
    {
        $this->db->insert('applicant_i9form_history', $data_to_insert);
    }

    function delete_i9_form($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->delete('applicant_i9form');
    }


    /**
     * get I9 form by user id and
     * user type
     *
     * @param int $userId
     * @param string $userType
     * @param string $section Optional
     * @return array
     */
    public function getI9Form(int $userId, string $userType, string $section = 'all'): array
    {
        //
        $columns = $section == 'all' ? '*' : [
            'applicant_i9form.sid',
            'applicant_i9form.user_consent',
        ];
        //
        if ($section == 'section1') {
            $columns[] = 'applicant_i9form.section1_last_name';
            $columns[] = 'applicant_i9form.section1_first_name';
            $columns[] = 'applicant_i9form.section1_middle_initial';
            $columns[] = 'applicant_i9form.section1_other_last_names';
            $columns[] = 'applicant_i9form.section1_address';
            $columns[] = 'applicant_i9form.section1_apt_number';
            $columns[] = 'applicant_i9form.section1_city_town';
            $columns[] = 'applicant_i9form.section1_state';
            $columns[] = 'applicant_i9form.section1_zip_code';
            $columns[] = 'applicant_i9form.section1_date_of_birth';
            $columns[] = 'applicant_i9form.section1_social_security_number';
            $columns[] = 'applicant_i9form.section1_emp_email_address';
            $columns[] = 'applicant_i9form.section1_emp_telephone_number';
            $columns[] = 'applicant_i9form.section1_emp_signature';
            $columns[] = 'applicant_i9form.section1_today_date';
            $columns[] = 'applicant_i9form.section1_alien_registration_number';
            $columns[] = 'applicant_i9form.section1_penalty_of_perjury';
            $columns[] = 'applicant_i9form.section1_preparer_or_translator';
            $columns[] = 'applicant_i9form.section1_preparer_json';
        }
        //
        $record =
            $this->db
            ->select($columns)
            ->where([
                'applicant_i9form.user_sid' => $userId,
                'applicant_i9form.user_type' => $userType,
            ])
            ->get('applicant_i9form')
            ->row_array();
        //
        if ($record) {
            //
            if (!empty($record['section1_alien_registration_number'])) {
                $record = array_merge(
                    $record,
                    unserialize($record['section1_alien_registration_number'])
                );
            }
            //
            $record['section1_date_of_birth'] = trim(explode(' ', $record['section1_date_of_birth'])[0]);
            $record['section1_today_date'] = trim(explode(' ', $record['section1_today_date'])[0]);
        }
        
        // check and merge records
        return checkI9RecordWithProfile(
            $userId,
            $userType,
            $record
        );
    }

    /**
     * get I9 form by id
     *
     * @param int $formId
     * @param array $extra
     * @return array
     */
    public function getI9FormById(int $formId, array $extra = []): array
    {
        //
        $columns = array_merge(
            ['user_sid', 'user_type', 'status'],
            $extra
        );
        return
            $this->db
            ->select($columns)
            ->where([
                'applicant_i9form.sid' => $formId
            ])
            ->get('applicant_i9form')
            ->row_array();
    }

    /**
     * get applicant info by ID
     *
     * @param int $applicantId
     * @return array
     */
    function getApplicantInformation($applicantId)
    {
        $this->db->select('*');
        $this->db->where('sid', $applicantId);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $record_arr = $record_arr[0];

            if (isset($record_arr['extra_info']) && !empty($record_arr['extra_info'])) {
                $extra_info = unserialize($record_arr['extra_info']);
                $record_arr = array_merge($record_arr, $extra_info);
            }

            return $record_arr;
        } else {
            return array();
        }
    }

    /**
     * get company detail by ID
     *
     * @param int $sid
     * @return array
     */
    function getCompanyDetail($sid) {
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();
        if (!empty($result)) {
            return $result[0];
        }
    }

    function getApplicantUniqueId($applicantId, $companyId, $onboarding_status = 'in_process')
    {   
        $this->db->select('unique_sid');
        $this->db->where('applicant_sid', $applicantId);
        $this->db->where('company_sid', $companyId);
        $this->db->where('onboarding_status', $onboarding_status);

        $record_obj = $this->db->get('onboarding_applicants');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr['unique_sid'];
        } else {
            return array();
        }
    }

    /**
     * Check if I9 form is assigned
     * to the user
     *
     * @version 1.0
     *
     * @param string $userType
     * @param int $userId
     * @return int
     */
    public function isI9FormAssigned(
        $userType,
        $userId
    ): int {
        //
        return $this->db
            ->where([
                'user_sid' => $userId,
                'user_type' => $userType,
                'status' => 1
            ])
            ->count_all_results('applicant_i9form');
    }
}
