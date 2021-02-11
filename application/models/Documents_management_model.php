<?php

class Documents_management_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function insert_documents_uploads_record($data_to_insert)
    {
        $this->db->insert('documents_uploads', $data_to_insert);
    }

    function update_documents_uploads_record($document_sid, $data_to_update)
    {
        $this->db->where('sid', $document_sid);
        $this->db->update('documents_uploads', $data_to_update);
    }

    function get_all_documents($company_sid, $archive_status = null)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if ($archive_status !== null) {
            $this->db->where('archive', $archive_status);
        }

        $records_obj = $this->db->get('documents_uploads');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        foreach ($records_arr as $key => $record) {
            $preview_url = AWS_S3_BUCKET_URL . $record['s3_file_name'];
            $download_url = $preview_url;

            $records_arr[$key]['preview_url'] = $preview_url;
            $records_arr[$key]['download_url'] = $download_url;
        }

        return $records_arr;
    }

    function get_uploaded_generated_documents($company_sid, $archive_status = null)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if ($archive_status !== null) {
            $this->db->where('archive', $archive_status);
        }

        $records_obj = $this->db->get('documents_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        foreach ($records_arr as $key => $record) {
            $preview_url = AWS_S3_BUCKET_URL . $record['uploaded_document_s3_name'];
            $download_url = $preview_url;

            $records_arr[$key]['preview_url'] = $preview_url;
            $records_arr[$key]['download_url'] = $download_url;
        }

        return $records_arr;
    }

    function delete_uploaded_document($document_sid)
    {
        $this->db->where('sid', $document_sid);
        $this->db->delete('documents_uploads');
    }

    function set_archive_status_document_record($document_sid, $archive_status)
    {
        $this->db->where('sid', $document_sid);
        $this->db->set('archive', $archive_status);
        $this->db->update('documents_uploads');
    }

    function get_uploaded_document_record($company_sid, $document_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $document_sid);

        $record_obj = $this->db->get('documents_uploads');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function insert_generated_document_record($data_to_insert)
    {
        $this->db->insert('documents_generated', $data_to_insert);
        $this->db->insert('documents_generated_history', $data_to_insert);
    }

    function update_generated_document_record($document_sid, $data_to_update)
    {
        $this->db->where('sid', $document_sid);
        $this->db->update('documents_generated', $data_to_update);
        $this->db->insert('documents_generated_history', $data_to_update);
    }

    function get_all_generated_documents($company_sid, $archive_status = null, $target_user_type = null)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        if ($archive_status !== null) {
            $this->db->where('archive', $archive_status);
        }

        if ($target_user_type !== null) {
            $this->db->where_in('target_user_type', array('general', $target_user_type));
        }

        $records_obj = $this->db->get('documents_generated');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_generated_document($company_sid, $document_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $document_sid);

        $records_obj = $this->db->get('documents_generated');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function set_archive_status_generated_document_record($document_sid, $archive_status)
    {
        $this->db->where('sid', $document_sid);
        $this->db->set('archive', $archive_status);
        $this->db->update('documents_generated');
    }

    function delete_generated_document($document_sid)
    {
        $this->db->where('sid', $document_sid);
        $this->db->delete('documents_generated');
    }

    function get_employee_information($company_sid, $employee_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('PhoneNumber as phone');

        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $employee_sid);
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_applicant_information($company_sid, $applicant_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('pictures');
        $this->db->select('phone_number as phone');

        $this->db->where('employer_sid', $company_sid);
        $this->db->where('sid', $applicant_sid);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_assigned_documents($company_sid, $user_type, $user_sid = null, $is_offer_letter = null)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_sid != null) {
            $this->db->where('user_sid', $user_sid);
        }

        $this->db->where('status', 1);

        if (!is_null($is_offer_letter)) {
            $this->db->where('is_offer_letter', $is_offer_letter);
        }

        $record_obj = $this->db->get('documents_assignment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function get_all_assigned_documents($company_sid, $user_type, $user_sid = null, $status = 1) {
        $this->db->select('documents_assigned.*');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        if($status){
            $this->db->where('status', $status);
        }
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function insert_documents_assignment_record($data_to_insert)
    {
        $this->db->insert('documents_assignment', $data_to_insert);
    }

    function delete_documents_assignment_record($document_type, $document_sid, $user_type, $user_sid)
    {
        $this->db->where('document_type', $document_type);
        $this->db->where('document_sid', $document_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->delete('documents_assignment');
    }

    function delete_all_documents_assignemnt_records($document_type, $user_type, $user_sid)
    {
        $this->db->where('document_type', $document_type);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->delete('documents_assignment');
    }

    function get_assigned_document($user_type, $user_sid, $document_sid, $document_type = null, $is_offer_letter = null, $status = null)
    {
        $this->db->select('*');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_sid', $document_sid);

        if (!is_null($status)) {
            $this->db->where('status', $status);
        }
        

        if (!is_null($is_offer_letter)) {
            $this->db->where('is_offer_letter', $is_offer_letter);
        }

        if (!is_null($document_type)) {
            $this->db->where('document_type', $document_type);
        }

        $records_obj = $this->db->get('documents_assignment');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function set_assigned_document_status($user_type, $user_sid, $document_type, $document_sid, $status, $is_offer_letter = null)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        $this->db->where('document_sid', $document_sid);

        if (!is_null($is_offer_letter)) {
            $this->db->where('is_offer_letter', $is_offer_letter);
        }

        $this->db->set('status', $status);

        $this->db->update('documents_assignment');
    }

    function update_assign_offer_letter($user_type, $user_sid, $document_type, $document_sid, $letter_body)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        $this->db->where('document_sid', $document_sid);
        $this->db->set('document_content', $letter_body);
        $this->db->set('status', 1);
        $this->db->update('documents_assignment');
    }

    function set_assigned_offer_letter_status($user_type, $user_sid, $document_type )
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);

        $this->db->set('status', 0);

        $this->db->update('documents_assignment');
    }

    function check_if_document_already_assigned($user_type, $user_sid, $document_type, $document_sid, $is_offer_letter = null)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        $this->db->where('document_sid', $document_sid);

        if (!is_null($is_offer_letter)) {
            $this->db->where('is_offer_letter', $is_offer_letter);
        }

        $this->db->from('documents_assignment');

        $count = $this->db->count_all_results();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_all_document_already_assigned($user_type, $user_sid, $document_type)
    {
        $this->db->select('document_sid');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assignment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function update_download_status($user_type, $user_sid, $document_type, $document_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        $this->db->where('document_sid', $document_sid);
        $this->db->where('status', 1);

        $this->db->set('downloaded', 1);
        $this->db->set('downloaded_date', date('Y-m-d H:i:s'));

        $this->db->update('documents_assignment');
    }

    function update_acknowledge_status($user_type, $user_sid, $document_type, $document_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        $this->db->where('document_sid', $document_sid);
        $this->db->where('status', 1);

        $this->db->set('acknowledged', 1);
        $this->db->set('acknowledged_date', date('Y-m-d H:i:s'));

        $this->db->update('documents_assignment');
    }

    function update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file)
    {
        $now = date('Y-m-d H:i:s');

        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        $this->db->where('document_sid', $document_sid);
        $this->db->where('status', 1);

        $this->db->set('uploaded', 1);
        $this->db->set('uploaded_date', $now);
        $this->db->set('uploaded_file', $uploaded_file);

        $this->db->update('documents_assignment');

        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['document_type'] = $document_type;
        $data_to_insert['document_sid'] = $document_sid;
        $data_to_insert['user_type'] = $user_type;
        $data_to_insert['user_sid'] = $user_sid;
        $data_to_insert['uploaded_date'] = $now;
        $data_to_insert['uploaded_file'] = $uploaded_file;

        $this->db->insert('documents_upload_history', $data_to_insert);
    }

    function copy_old_documents_to_new_documents_management($company_sid, $employer_sid)
    {
        $this->db->select('*');
        $this->db->from('hr_documents');
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
echo 'Documents Management Model - copy_old_documents_to_new_documents_management - Line no:  435<pre>'; print_r($records_arr); exit;
        foreach ($records_arr as $record) {
            $company_sid = $record['company_sid'];
            $employer__sid = $record['employer_sid'];
            $document_original_name = $record['document_original_name'];
            $document_type = $record['document_type'];
            $document_description = $record['document_description'];
            $action_required = $record['action_required'];
            $s3_file_name = $record['document_name'];
            $archive = $record['archive'];

            $this->db->select('sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('s3_file_name', $s3_file_name);
            $this->db->from('documents_uploads');
            $count = $this->db->count_all_results();

            if ($count == 0) {
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['employer_sid'] = $employer__sid;
                $data_to_insert['document_name'] = $document_original_name;
                $data_to_insert['document_original_name'] = $document_original_name . '.' . $document_type;
                $data_to_insert['document_type'] = $document_type;
                $data_to_insert['document_description'] = $document_description;
                $data_to_insert['action_required'] = $action_required;
                $data_to_insert['s3_file_name'] = $s3_file_name;
                $data_to_insert['archive'] = $archive;
                $data_to_insert['date_uploaded'] = date('Y-m-d H:i:s');
                $data_to_insert['unique_key'] = generateRandomString(32);

                $this->db->insert('documents_uploads', $data_to_insert);
            }
        }


        // $this->db->select('*');
        // $this->db->from('offer_letter');
        // $this->db->where('company_sid', $company_sid);

        // $records_obj = $this->db->get();
        // $records_arr = $records_obj->result_array();
        // $records_obj->free_result();

        // foreach ($records_arr as $record) {
        //     $company_sid = $record['company_sid'];
        //     $document_title = $record['letter_name'];
        //     $document_content = htmlentities($record['letter_body']);
        //     $archive = $record['archive'];

        //     $this->db->select('sid');
        //     $this->db->where('document_title', $document_title);
        //     $this->db->from('documents_generated');
        //     $count = $this->db->count_all_results();

        //     if ($count == 0) {
        //         $data_to_insert = array();
        //         $data_to_insert['company_sid'] = $company_sid;
        //         $data_to_insert['employer_sid'] = $employer_sid;
        //         $data_to_insert['document_title'] = $document_title;
        //         $data_to_insert['document_content'] = $document_content;
        //         $data_to_insert['created_date'] = date('Y-m-d H:m:s');
        //         $data_to_insert['archive'] = $archive;
        //         $data_to_insert['target_user_type'] = 'applicant';

        //         $this->db->insert('documents_generated', $data_to_insert);
        //     }
        // }
    }


    function get_pending_assigned_documents($company_sid, $user_type, $document_type)
    {
        $this->db->select('documents_assignment.*');

        if ($user_type == 'employee') {
            $this->db->select('users.first_name');
            $this->db->select('users.last_name');
        } else if ($user_type == 'applicant') {
            $this->db->select('portal_job_applications.first_name');
            $this->db->select('portal_job_applications.last_name');
        }

        $this->db->where('documents_assignment.company_sid', $company_sid);
        $this->db->where('documents_assignment.user_type', $user_type);

        $this->db->where('documents_assignment.document_type', $document_type);


        if ($document_type == 'uploaded') {
            $this->db->group_start();
            $this->db->where('documents_assignment.acknowledged', 0);
            $this->db->or_where('documents_assignment.downloaded', 0);
            $this->db->or_where('documents_assignment.uploaded', 0);
            $this->db->group_end();
        } else if ($document_type == 'generated') {
            $this->db->group_start();
            $this->db->where('documents_assignment.signature', '');
            $this->db->group_end();
        }

        if ($user_type == 'employee') {
            $this->db->join('users', 'users.sid = documents_assignment.user_sid', 'left');
        } else if ($user_type == 'applicant') {
            $this->db->join('users', 'portal_job_applications.sid = documents_assignment.user_sid', 'left');
        }

        $this->db->where('status', 1);

        $record_obj = $this->db->get('documents_assignment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    public function get_all_offer_letters($company_sid, $archive = null)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if (!is_null($archive)) {
            $this->db->where('archive', $archive);
        }

        $this->db->from('offer_letter');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    public function add_new_offer_letter($offer_letter_data)
    {
        $this->db->insert('offer_letter', $offer_letter_data);
    }

    public function update_offer_letter($offer_letter_sid, $offer_letter_data)
    {
        $this->db->where('sid', $offer_letter_sid);
        $this->db->update('offer_letter', $offer_letter_data);
    }

    public function get_offer_letter($company_sid, $offer_letter_sid)
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

    public function set_offer_letter_archive_status($offer_letter_sid, $archive_status)
    {
        $this->db->where('sid', $offer_letter_sid);
        $this->db->set('archive', $archive_status);
        $this->db->update('offer_letter');
    }

    public function delete_offer_letter($offer_letter_sid)
    {
        $this->db->where('sid', $offer_letter_sid);
        $this->db->delete('offer_letter');
    }

    public function get_old_sent_documents($company_sid, $receiver_sid = NULL)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('acknowledged_date', null);
        if ($receiver_sid != NULL) {
            $this->db->where('receiver_sid', $receiver_sid);
        }
        $this->db->from('hr_user_document');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        foreach ($records_arr as $key => $record) {
            $this->db->select('*');
            $this->db->where('sid', $record['document_sid']);
            $this->db->from('hr_documents');
            $document_obj = $this->db->get();
            $document_arr = $document_obj->result_array();
            $document_obj->free_result();

            if (isset($document_arr[0])) {
                $record['document'] = $document_arr[0];
            } else {
                $record['document'] = array();
            }

            $this->db->select('first_name');
            $this->db->select('last_name');
            $this->db->select('email');
            $this->db->where('sid', $record['receiver_sid']);
            $this->db->from('users');
            $user_obj = $this->db->get();
            $user_arr = $user_obj->result_array();
            $user_obj->free_result();

            if (isset($user_arr[0])) {
                $record['user'] = $user_arr[0];
            } else {
                $record['user'] = array();
            }

            $records_arr[$key] = $record;
        }


        return $records_arr;
    }

    public function fetch_form($form_name, $user_type, $user_sid)
    {
        $this->db->select('*');
        if ($form_name == 'w4') {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('status', 1);
            $this->db->from('applicant_w4form');

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

    public function get_old_document($company_sid, $employer_sid, $record_id)
    {
        $this->db->select('hr_user_document.*');
        $this->db->select('hr_documents.document_name');
        $this->db->select('hr_documents.document_original_name');
        $this->db->select('hr_documents.document_type as document_extension');
        $this->db->select('hr_documents.document_description');
        $this->db->select('hr_documents.onboarding');
        $this->db->select('hr_documents.action_required');
        $this->db->select('hr_documents.to_all_employees');
        $this->db->select('hr_documents.archive');

        $this->db->join('hr_documents', 'hr_user_document.document_sid = hr_documents.sid', 'left');

        $this->db->where('hr_user_document.sid', $record_id);
        $this->db->where('hr_user_document.company_sid', $company_sid);
        $this->db->where('hr_user_document.receiver_sid', $employer_sid);

        $this->db->from('hr_user_document');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    public function update_old_system_document_status($company_sid, $employer_sid, $record_sid, $status_field, $status)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('receiver_sid', $employer_sid);
        $this->db->where('sid', $record_sid);
        $this->db->set($status_field, $status);
        $this->db->set($status_field . '_date', date('Y-m-d H:i:s'));
        $this->db->from('hr_user_document');
        $this->db->update();
    }

    public function update_uploaded_file_name($company_sid, $employer_sid, $record_sid, $uploaded_file)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('receiver_sid', $employer_sid);
        $this->db->where('sid', $record_sid);
        $this->db->set('uploaded_file', $uploaded_file);
        $this->db->from('hr_user_document');
        $this->db->update();
    }

    function getEmployeesDetails($employees) {
        return $this->db->select('sid,first_name,last_name,email')->where_in('sid', $employees)->order_by("sid", "desc")->get('users')->result_array();
    }

    function removeVerificationKey($document_id) {
        $res = $this->db->get_where('hr_user_document', array('hr_user_document.sid' => $document_id))->result_array();
        $res = $res[0];

        if ($res['document_type'] == 'document') {
            $data = $this->db->get_where('hr_documents', array('sid' => $res['document_sid']))->result_array();
            $data = $data[0];
            if ($data['action_required'] == 1) {
                if ($res['acknowledged'] == 1 && $res['uploaded'] == 1) {
                    return "true";
                } else {
                    return "false";
                }
            } else {
                if ($res['acknowledged'] == 1) {
                    return "true";
                } else {
                    return "false";
                }
            }
        } else {
            if ($res['acknowledged'] == 1) {
                return "true";
            } else {
                return "false";
            }
        }
    }

    function getAllEmployeesWithPendingAction($company_sid, $document_type) {
        return $this->db->select('sid,receiver_sid,document_sid')
            ->where('company_sid', $company_sid)
            ->where('document_type', $document_type)
            ->order_by("sid", "desc")
            ->get('hr_user_document')->result_array();
    }

    function getEmployeePendingActionDocument($company_sid, $document_type, $employee_id) {
        return $this->db->select('*,hr_user_document.sid as userDocumentSid')
            ->where('hr_user_document.company_sid', $company_sid)
            ->where('hr_user_document.document_type', $document_type)
            ->where('hr_user_document.receiver_sid', $employee_id)
            ->join('hr_documents', 'hr_user_document.document_sid =hr_documents.sid')
            ->get('hr_user_document')->result_array();
    }

    function getEmployerDetail($id) {
        $this->db->where('sid', $id);
        return $this->db->get('users')->row_array();
    }

    function getUserDocument($userDocumentSid) {
        return $this->db->select('*, hr_user_document.verification_key as ver_key')->join('users', 'hr_user_document.receiver_sid = users.sid')
            ->get_where('hr_user_document', array('hr_user_document.sid' => $userDocumentSid))->result_array();
    }

    function get_admin_or_company_email_template($sid, $company_sid) {
        $this->db->select('*');
        $this->db->where('admin_template_sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('portal_email_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)){
            $data = array();
            $data['from_email']                                                 = $record_arr[0]['from_email'];
            $data['subject']                                                    = $record_arr[0]['subject'];
            $data['from_name']                                                  = $record_arr[0]['from_name'];
            $data['text']                                                       = $record_arr[0]['message_body'];
            return $data;
        } else { // company does not have its own version
            $this->db->select('*');
            $this->db->where('sid', $sid);
            $record_obj = $this->db->get('email_templates');
            $record_arr = $record_obj->row_array();
            $record_obj->free_result();
            if (count($record_arr) > 0) {
                return $record_arr;
            } else {
                return 0;
            }
        }
    }

}
