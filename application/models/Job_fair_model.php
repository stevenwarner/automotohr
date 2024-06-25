<?php
class Job_fair_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_job_fair_data($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $record_obj = $this->db->get('job_fairs_recruitment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function save_job_fair($data)
    {
        $this->db->where('company_sid', $data['company_sid']);
        $result = $this->db->get('job_fairs_recruitment')->num_rows();

        if ($result > 0) {
            $this->db->where('company_sid', $data['company_sid']);
            $result = $this->db->update('job_fairs_recruitment', $data);
        } else { // insert
            $result = $this->db->insert('job_fairs_recruitment', $data);
        }

        return $result;
    }

    function fetch_job_fair_forms($company_sid, $job_fairs_recruitment_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('job_fairs_forms');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr)) { // insert default form data in database
            $data_to_insert = array();
            $data_to_insert = array(
                'job_fairs_recruitment_sid' => $job_fairs_recruitment_sid,
                'company_sid' => $company_sid,
                'form_name' => 'Default Job Fair Form',
                'status' => 1
            );
            //$this->db->insert('job_fairs_forms', $data_to_insert);
            $job_fairs_forms_sid = $this->add_new_data($data_to_insert, 'job_fairs_forms');
            $this->add_default_fair_fields($job_fairs_forms_sid, $company_sid);

            $this->db->select('*');
            $this->db->where('company_sid', $company_sid);
            $record_obj = $this->db->get('job_fairs_forms');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
        }

        return $record_arr;
    }

    function add_new_data($data, $table_name)
    {
        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    function update_form_data($data, $table_name, $job_fairs_forms_sid, $company_sid, $field_id)
    {
        $this->db->where('job_fairs_forms_sid', $job_fairs_forms_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('field_id', $field_id);
        $this->db->update($table_name, $data);
        return 1;
    }

    function add_default_fair_fields($job_fairs_forms_sid, $company_sid)
    {
        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'first_name',
            'field_name' => 'First Name',
            'field_type' => 'default',
            'field_priority' => 'mandatory',
            'field_display_status' => 1,
            'is_required' => 1,
            'question_type' => 'string',
            'sort_order' => 0
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'last_name',
            'field_name' => 'Last Name',
            'field_type' => 'default',
            'field_priority' => 'mandatory',
            'field_display_status' => 1,
            'is_required' => 1,
            'question_type' => 'string',
            'sort_order' => 1
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'email',
            'field_name' => 'Email Address',
            'field_type' => 'default',
            'field_priority' => 'mandatory',
            'field_display_status' => 1,
            'is_required' => 1,
            'question_type' => 'string',
            'sort_order' => 2
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'country',
            'field_name' => 'Country',
            'field_type' => 'default',
            'field_display_status' => 0,
            'is_required' => 0,
            'question_type' => 'list',
            'sort_order' => 3
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'state',
            'field_name' => 'State',
            'field_type' => 'default',
            'field_display_status' => 0,
            'is_required' => 0,
            'question_type' => 'list',
            'sort_order' => 4
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'city',
            'field_name' => 'City',
            'field_type' => 'default',
            'field_display_status' => 0,
            'is_required' => 0,
            'question_type' => 'string',
            'sort_order' => 5
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'phone_number',
            'field_name' => 'Best Contact Number',
            'field_type' => 'default',
            'field_display_status' => 0,
            'is_required' => 0,
            'question_type' => 'string',
            'sort_order' => 6
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'desired_job_title',
            'field_name' => 'Desired Job Title',
            'field_type' => 'default',
            'field_display_status' => 0,
            'is_required' => 0,
            'question_type' => 'string',
            'sort_order' => 7
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'college_university_name',
            'field_name' => 'College / University Name',
            'field_type' => 'default',
            'field_display_status' => 0,
            'is_required' => 0,
            'question_type' => 'string',
            'sort_order' => 8
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'resume',
            'field_name' => 'Upload a Resume (.pdf .docx .doc .rtf .jpg .jpe .jpeg .png .gif)',
            'field_type' => 'default',
            'field_display_status' => 0,
            'is_required' => 0,
            'question_type' => 'file',
            'sort_order' => 9
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'job_interest_text',
            'field_name' => 'What types of jobs are you interested in? (max 128 characters)',
            'field_type' => 'default',
            'field_display_status' => 0,
            'is_required' => 0,
            'question_type' => 'string',
            'sort_order' => 10
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);

        $data_for_job_fairs_forms_questions = array();
        $data_for_job_fairs_forms_questions = array(
            'job_fairs_forms_sid' => $job_fairs_forms_sid,
            'company_sid' => $company_sid,
            'field_id' => 'profile_picture',
            'field_name' => 'Upload a Profile Picture (.jpg .jpe .jpeg .png .gif)',
            'field_type' => 'default',
            'field_display_status' => 0,
            'is_required' => 0,
            'question_type' => 'file',
            'sort_order' => 11
        );
        $this->db->insert('job_fairs_forms_questions', $data_for_job_fairs_forms_questions);
    }

    function fetch_default_fair_form($company_sid, $form_type = 'default')
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('form_type', $form_type);

        $record_obj = $this->db->get('job_fairs_forms');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr[0];
    }

    function get_default_video_form_fields($company_sid, $job_fairs_forms_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('job_fairs_forms_sid', $job_fairs_forms_sid);
        $this->db->where('field_type', 'default');
        $this->db->where('field_id', 'video_resume');

        $record_obj = $this->db->get('job_fairs_forms_questions');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function fetch_default_form_fields($company_sid, $job_fairs_forms_sid, $type = 'custom', $field_type = 'custom')
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('job_fairs_forms_sid', $job_fairs_forms_sid);
        $this->db->where('field_type', $field_type);
        $this->db->order_by('sort_order', 'ASC');

        $record_obj = $this->db->get('job_fairs_forms_questions');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr) && $field_type == 'default') {
            $this->add_default_fair_fields($job_fairs_forms_sid, $company_sid);

            $this->db->select('*');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('job_fairs_forms_sid', $job_fairs_forms_sid);
            $this->db->where('field_type', $field_type);
            $this->db->order_by('sort_order', 'ASC');

            $record_obj = $this->db->get('job_fairs_forms_questions');
            $record_arr1 = $record_obj->result_array();
            $record_obj->free_result();

            return $record_arr1;
        } else {
            return $record_arr;
        }
    }

    function inactivate_all_fair_form($company_sid = NULL)
    {
        if ($company_sid != NULL) {
            $data = array('status' => 0);
            $this->db->where('company_sid', $company_sid);
            $this->db->update('job_fairs_forms', $data);
        }
    }

    function fetch_main_form_data($company_sid, $sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('job_fairs_forms');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function update_job_fairs_forms($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('job_fairs_forms', $data);
    }

    function fetch_job_fairs_forms_questions_option($job_fairs_forms_sid)
    {
        $this->db->select('questions_sid, value');
        $this->db->where('job_fairs_forms_sid', $job_fairs_forms_sid);
        $record_obj = $this->db->get('job_fairs_forms_questions_option');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function change_status($company_sid, $form_sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $result = $this->db->get('job_fairs_forms')->result_array();

        if (sizeof($result) > 0) {
            $result = $result[0]['sid'];
        } else {
            $result = 0;
        }

        $this->db->where('company_sid', $company_sid);
        $this->db->update('job_fairs_forms', array('status' => 0));
        $this->db->where('sid', $form_sid);
        $this->db->update('job_fairs_forms', array('status' => 1));

        return $result;
    }

    function delete_custom_field($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->delete('job_fairs_forms_questions');

        $this->db->where('questions_sid', $sid);
        $this->db->delete('job_fairs_forms_questions_option');
    }

    function fetch_custom_fields($field_sid)
    {
        $this->db->select('job_fairs_forms_questions.*,job_fairs_forms_questions_option.value');
        $this->db->where('job_fairs_forms_questions.sid', $field_sid);
        $this->db->join('job_fairs_forms_questions_option', 'job_fairs_forms_questions_option.questions_sid = job_fairs_forms_questions.sid', 'left');
        $result = $this->db->get('job_fairs_forms_questions')->result_array();

        return $result;
    }


    function update_question_data($data, $sid, $table_name)
    {
        $this->db->where('sid', $sid);
        $this->db->update($table_name, $data);
    }

    function delete_previous_option($sid)
    {
        $this->db->where('questions_sid', $sid);
        $this->db->delete('job_fairs_forms_questions_option');
    }

    function fetch_active_job_fair_title($company_sid)
    {
        $this->db->select('title');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);

        $record_obj = $this->db->get('job_fairs_forms');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr)) {
            $this->db->select('title');
            $this->db->where('company_sid', $company_sid);
            $record_obj = $this->db->get('job_fairs_recruitment');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
        } else {
            if (($record_arr[0]['title'] == NULL  || $record_arr[0]['title'] == '')) {
                $this->db->select('title');
                $this->db->where('company_sid', $company_sid);
                $record_obj = $this->db->get('job_fairs_recruitment');
                $record_arr = $record_obj->result_array();
                $record_obj->free_result();
            }
        }

        return $record_arr;
    }

    function get_custom_templates($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->where('is_custom', 1);
        return $this->db->get('portal_email_templates')->result_array();
    }

    /**
     * 
     */
    function getAllEmployees($companyId)
    {
        //
        return
            $this->db
            ->select('
            sid,
            first_name,
            last_name,
            access_level,
            access_level_plus,
            pay_plan_flag,
            is_executive_admin,
            job_title
        ')
            ->where_in('access_level', ['manager', 'hiring manager', 'admin'])
            ->where('active', 1)
            ->where('terminated_status', 0)
            ->where('parent_sid', $companyId)
            ->order_by('LOWER(first_name)', 'ASC')
            ->get('users')
            ->result_array();
    }



    //
    function fetch_job_fairs_forms_question_option_byid($questions_sid, $job_fairs_forms_sid)
    {
        $this->db->select('value');
        $this->db->where('questions_sid', $questions_sid);
        $this->db->where('job_fairs_forms_sid', $job_fairs_forms_sid);

        $record_obj = $this->db->get('job_fairs_forms_questions_option');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }
}
