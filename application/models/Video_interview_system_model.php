<?php

class Video_interview_system_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_video_questionnaires($company_sid, $status = NULL, $limit = null, $start = null)
    {
        $this->db->select('*');

        if ($status != NULL) {
            $this->db->where('status', $status);
        }

        $this->db->where('company_sid', $company_sid);
        $this->db->where('template_sid', 0);

        if ($limit != null) {
            $this->db->limit($limit, $start);
        }

        $this->db->order_by('sid', 'DESC');
        return $this->db->get('video_interview_questions')->result_array();
    }

    function get_video_questionnaires_count($company_sid, $status = NULL)
    {
        if ($status != NULL) {
            $this->db->where('status', $status);
        }

        $this->db->where('company_sid', $company_sid);
        $this->db->where('template_sid', 0);
        return $this->db->get('video_interview_questions')->num_rows();
    }

    function get_questionnaire_templates($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        //$this->db->where('status', 'active');
        return $this->db->get('video_interview_questions_templates')->result_array();
    }

    function get_questionnaire_template($company_sid, $template_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $template_sid);
        $data = $this->db->get('video_interview_questions_templates')->result_array();

        if (!empty($data)) {
            return $data[0];
        } else {
            return 0;
        }
    }

    function add_video_questionnaire($data, $type = NULL)
    {
        if ($type == 'default') { // remove this check to implement uniqueness of title/text on all type of questions.
            $this->db->select('question_text, video_title');
            $this->db->where('company_sid', $data['company_sid']);
            $this->db->where('template_sid', $data['template_sid']);
            $result = $this->db->get('video_interview_questions')->result_array();

            if ($data['question_type'] == 'video') {
                $title = strtolower($data['video_title']);
            } else {
                $title = strtolower($data['question_text']);
            }

            foreach ($result as $question) {
                if (($data['question_type'] == 'text' && $title == strtolower($question['question_text'])) || ($data['question_type'] == 'video' && $title == strtolower($question['video_title']))) {
                    return 'exists';
                }
            }
        }

        return $this->db->insert('video_interview_questions', $data);
    }

    function get_template_questions($company_sid, $template_sid)
    {
        $this->db->select('video_interview_questions.*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('template_sid', $template_sid);
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('video_interview_questions')->result_array();
    }

    function send_video_questionnaire($data)
    {
        return $this->db->insert('video_interview_questions_sent', $data);
    }

    function add_questionnaire_template($data)
    {
        return $this->db->insert('video_interview_questions_templates', $data);
    }

    function save_rating($data)
    {
        return $this->db->insert('video_interview_questions_rating', $data);
    }

    function update_video_questionnaire($data, $question_sid)
    {
        $this->db->where('sid', $question_sid);
        return $this->db->update('video_interview_questions', $data);
    }

    function edit_questionnaire_template($data, $template_sid)
    {
        $this->db->where('sid', $template_sid);
        return $this->db->update('video_interview_questions_templates', $data);
    }

    function get_video_questionnaire($question_sid, $company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $question_sid);
        $data = $this->db->get('video_interview_questions')->result_array();

        if (!empty($data)) {
            return $data[0];
        } else {
            return 0;
        }
    }

    function get_sent_video_questionnaires($applicant_sid, $company_sid)
    {
        $this->db->select('question_sid');
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('company_sid', $company_sid);
        $data = $this->db->get('video_interview_questions_sent')->result_array();
        $result = array();

        foreach ($data as $question) {
            $result[] = $question['question_sid'];
        }

        return $result;
    }

    function get_applicant_verification_key($applicant_sid, $company_sid)
    {
        $this->db->select('verification_key');
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->limit(1);
        $data = $this->db->get('video_interview_questions_sent')->result_array();

        if (!empty($data) && isset($data[0]['verification_key'])) {
            return $data[0]['verification_key'];
        } else {
            return 0;
        }
    }

    function validate_record_key($verification_key)
    {
        $this->db->where('verification_key', $verification_key);
        $this->db->limit(1);
        $data = $this->db->get('video_interview_questions_sent')->result_array();

        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }

    function get_question_type($question_sid)
    {
        $this->db->select('question_type');
        $this->db->where('sid', $question_sid);
        $data = $this->db->get('video_interview_questions')->result_array();

        if (!empty($data) && isset($data[0]['question_type'])) {
            return $data[0]['question_type'];
        } else {
            return 0;
        }
    }

    function get_applicant_data($applicant_sid)
    {
        $this->db->select('first_name, last_name, employer_sid, email');
        $this->db->where('sid', $applicant_sid);
        $data = $this->db->get('portal_job_applications')->result_array();

        if (!empty($data)) {
            return $data[0];
        } else {
            return 0;
        }
    }

    function check_valid_applicant($applicant_sid, $company_sid)
    {
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('sid', $applicant_sid);
        $data = $this->db->get('portal_job_applications')->result_array();

        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }

    function check_valid_question($question_sid, $company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $question_sid);
        $data = $this->db->get('video_interview_questions')->result_array();

        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }

    function get_questionnaire_response($question_sid, $applicant_sid, $company_sid)
    {
        $this->db->select('video_interview_questions.*');
        $this->db->select('video_interview_questions_sent.*');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->where('video_interview_questions.sid', $question_sid);
        $this->db->where('video_interview_questions.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);
        $this->db->join('video_interview_questions_sent', 'video_interview_questions_sent.question_sid = video_interview_questions.sid');
        $this->db->join('portal_job_applications', 'video_interview_questions_sent.applicant_sid = portal_job_applications.sid');
        $data = $this->db->get('video_interview_questions')->result_array();

        if (!empty($data)) {
            return $data[0];
        } else {
            return 0;
        }
    }

    function delete_video_questionnaire($question_sid, $company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $question_sid);
        return $this->db->delete('video_interview_questions');
    }

    function delete_questionnaire_template($template_sid, $company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $template_sid);
        return $this->db->delete('video_interview_questions_templates');
    }

    function change_video_questionnaire_status($question_sid, $company_sid, $update_data)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $question_sid);
        return $this->db->update('video_interview_questions', $update_data);
    }

    function change_questionnaire_template_status($template_sid, $company_sid, $update_data)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $template_sid);
        return $this->db->update('video_interview_questions_templates', $update_data);
    }

    function get_company_name($verification_key)
    {
        $this->db->select('company_sid');
        $this->db->where('verification_key', $verification_key);
        $this->db->limit(1);
        $data = $this->db->get('video_interview_questions_sent')->result_array();

        if (!empty($data) && isset($data[0]['company_sid'])) {
            $this->db->select('CompanyName, Logo');
            $this->db->where('sid', $data[0]['company_sid']);
            $data = $this->db->get('users')->result_array();

            if (!empty($data) && isset($data[0]['CompanyName'])) {
                return $data[0];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function get_applicant_questionnaire($verification_key, $count = null, $latest = null)
    {
        $this->db->select('video_interview_questions.video_name');
        $this->db->select('video_interview_questions.question_text');
        $this->db->select('video_interview_questions.question_type');
        $this->db->select('video_interview_questions.video_title');
        $this->db->select('video_interview_questions.company_sid');
        $this->db->select('video_interview_questions.video_source');
        $this->db->select('video_interview_questions.video_id');
        $this->db->select('video_interview_questions_sent.sid as sent_record_sid');
        $this->db->select('video_interview_questions_sent.question_sid');
        $this->db->select('video_interview_questions_sent.applicant_sid');
        $this->db->select('video_interview_questions_sent.resent_status');
        $this->db->select('video_interview_questions_sent.resent_note');
        $this->db->select('video_interview_questions_sent.is_latest');
        $this->db->where('video_interview_questions_sent.status', 'unanswered');
        $this->db->where('video_interview_questions_sent.verification_key', $verification_key);

        if ($latest !== null) {
            $this->db->where('video_interview_questions_sent.is_latest', $latest);
        }

        $this->db->join('video_interview_questions_sent', 'video_interview_questions_sent.question_sid = video_interview_questions.sid');

        if ($count == null) {
            //$this->db->order_by('video_interview_questions.sid', 'DESC');
            $this->db->order_by('video_interview_questions_sent.question_order', 'ASC');
            $this->db->limit(1);
            $data = $this->db->get('video_interview_questions')->result_array();
            if (!empty($data)) {
                return $data[0];
            } else {
                return 0;
            }
        } else {
            return $this->db->get('video_interview_questions')->num_rows();
        }
    }

    function get_video_interview_system_notification_list($company_sid, $type, $status)
    {
        $this->db->select('
            notifications_emails_management.sid, 
            notifications_emails_management.contact_name, 
            notifications_emails_management.email,
            notifications_emails_management.employer_sid,
            users.active,
            users.terminated_status
        ');
        $this->db->where('notifications_emails_management.notifications_type', $type);
        $this->db->where('notifications_emails_management.company_sid', $company_sid);
        $this->db->where('notifications_emails_management.status', $status);
        $this->db->join('users', 'notifications_emails_management.employer_sid = users.sid', 'left');
        $b = $this->db->get('notifications_emails_management')->result_array();
        if(count($b)){
            foreach ($b as $key => $v) {
                if($v['employer_sid'] != 0 && $v['employer_sid'] != null){
                    if($v['active'] == 0 || $v['terminated_status'] == 1) unset($b[$key]);
                }
            }
            // Reset the array indexes
            $b = array_values($b);
        }
        return $b;
    }

    function get_answered_questions_count($verification_key)
    {
        $this->db->where('status', 'answered');
        $this->db->group_by('question_sid');
        $this->db->where('verification_key', $verification_key);
        return $this->db->get('video_interview_questions_sent')->num_rows();
    }

    function update_questionnaire_response($sent_record_sid, $update_array, $questionnaire, $verification_key)
    {
        $this->db->where('sid', $sent_record_sid);
        $this->db->where('question_sid', $questionnaire['question_sid']);
        $this->db->where('applicant_sid', $questionnaire['applicant_sid']);
        $this->db->where('verification_key', $verification_key);
        return $this->db->update('video_interview_questions_sent', $update_array);
    }

    function get_answered_applicant_questionnaires($applicant_sid, $company_sid)
    {
        $this->db->select('video_interview_questions.*');
        $this->db->select('video_interview_questions_sent.*');
        $this->db->where('video_interview_questions_sent.status', 'answered');
        $this->db->where('video_interview_questions.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);
        $this->db->join('video_interview_questions_sent', 'video_interview_questions_sent.question_sid = video_interview_questions.sid');
        $this->db->order_by('video_interview_questions.sid', 'DESC');
    }

    function get_answered_video_questionnaires($applicant_sid, $company_sid)
    {
        $this->db->select('video_interview_questions_sent.question_sid, video_interview_questions_sent.question_type, video_interview_questions_sent.text_response, video_interview_questions_sent.video_response');
        $this->db->select('video_interview_questions.question_text, video_interview_questions.video_title');
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);
        $this->db->where('video_interview_questions_sent.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.status', 'answered');
        $this->db->join('video_interview_questions', 'video_interview_questions_sent.question_sid = video_interview_questions.sid');
        $this->db->order_by('video_interview_questions_sent.question_order', 'ASC');
        return $this->db->get('video_interview_questions_sent')->result_array();
    }

    public function get_default_questions_categorized($status = null)
    {
        $my_return = array();
        for ($count = 1; $count <= 5; $count++) {
            $questions = $this->get_default_questions($status, $count);
            $category = array();
            $category['id'] = $count;

            switch ($count) {
                case 1:
                    $category['name'] = 'Basic Interview Questions';
                    break;
                case 2:
                    $category['name'] = 'Behavioral Interview Questions';
                    break;
                case 3:
                    $category['name'] = 'Brainteasers';
                    break;
                case 4:
                    $category['name'] = 'More Questions About You';
                    break;
                case 5:
                    $category['name'] = 'Salary Questions';
                    break;
            }

            $category['questions'] = $questions;
            $my_return[] = $category;
        }

        return $my_return;
    }

    public function get_default_questions($status = null, $category = 0)
    {
        $this->db->where('question_type', 'system_default');
        $this->db->where('status', $status);
        $this->db->where('company_sid', 0);

        if ($category > 0) {
            $this->db->where('question_category', $category);
        }

        return $this->db->get('interview_questionnaire_section_questions')->result_array();
    }

    public function get_applicants_details($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('portal_job_applications')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function check_sent_video_questionnaires($applicant_sid, $company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->limit(1);
        $data = $this->db->get('video_interview_questions_sent')->result_array();

        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }

    function check_answered_video_questionnaires($applicant_sid, $company_sid)
    {
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);
        $this->db->where('video_interview_questions_sent.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.status', 'answered');
        $this->db->join('video_interview_questions', 'video_interview_questions_sent.question_sid = video_interview_questions.sid');
        $data = $this->db->get('video_interview_questions_sent')->num_rows();

        if ($data > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_company_video_questionnaire_templates($company_sid, $get_questions = false)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 'active');
        $templates_obj = $this->db->get('video_interview_questions_templates');
        $templates_arr = $templates_obj->result_array();
        $templates_obj->free_result();

        if ($get_questions == true) {
            foreach ($templates_arr as $key => $template) {
                $template_sid = $template['sid'];

                $questions_arr = $this->get_company_video_questions($company_sid, $template_sid);

                $templates_arr[$key]['questions'] = $questions_arr;
            }
        }

        return $templates_arr;
    }

    function get_company_video_questions($company_sid, $template_sid = 0)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('template_sid', $template_sid);
        $this->db->where('status', 'active');
        $questions_obj = $this->db->get('video_interview_questions');
        $questions_arr = $questions_obj->result_array();
        $questions_obj->free_result();

        if (!empty($questions_arr)) {
            return $questions_arr;
        } else {
            return array();
        }
    }

    function get_applicant_questions($company_sid, $applicant_sid)
    {
        $this->db->select('video_interview_questions_sent.*');
        $this->db->where('video_interview_questions_sent.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);

        $this->db->select('video_interview_questions.question_text');
        $this->db->select('video_interview_questions.video_name');
        $this->db->select('video_interview_questions.video_type');
        $this->db->select('video_interview_questions.video_title');

        $this->db->join('video_interview_questions', 'video_interview_questions.sid = video_interview_questions_sent.question_sid', 'left');

        $questions_obj = $this->db->get('video_interview_questions_sent');
        $questions_arr = $questions_obj->result_array();
        $questions_obj->free_result();

        if (!empty($questions_arr)) {
            foreach ($questions_arr as $key => $question) {
                $this->db->select('video_interview_questions_rating.*');
                $this->db->where('video_interview_questions_rating.company_sid', $question['company_sid']);
                $this->db->where('video_interview_questions_rating.applicant_sid', $question['applicant_sid']);
                $this->db->where('video_interview_questions_rating.question_sent_sid', $question['sid']);

                $this->db->select('users.first_name');
                $this->db->select('users.last_name');

                $this->db->join('users', 'video_interview_questions_rating.employer_sid = users.sid', 'left');

                $scores_obj = $this->db->get('video_interview_questions_rating');
                $scores_arr = $scores_obj->result_array();
                $scores_obj->free_result();

                $questions_arr[$key]['scores'] = $scores_arr;
            }
        }

        return $questions_arr;
    }

    function get_distinct_questions($company_sid, $applicant_sid)
    {
        $this->db->distinct();
        $this->db->select('video_interview_questions_sent.question_sid');
        $this->db->select('video_interview_questions.*');

        $this->db->where('video_interview_questions_sent.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);

        $this->db->join('video_interview_questions', 'video_interview_questions.sid = video_interview_questions_sent.question_sid', 'left');

        $ids_obj = $this->db->get('video_interview_questions_sent');
        $ids_arr = $ids_obj->result_array();
        $ids_obj->free_result();

        return $ids_arr;
    }

    function save_comment_or_rating($company_sid, $employer_sid, $applicant_sid, $question_sent_sid, $comment, $rating = 0, $rating_type = 'comment')
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('question_sent_sid', $question_sent_sid);
        $this->db->where('rating_type', $rating_type);
        $this->db->delete('video_interview_questions_rating');

        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['employer_sid'] = $employer_sid;
        $data_to_insert['applicant_sid'] = $applicant_sid;
        $data_to_insert['question_sent_sid'] = $question_sent_sid;
        $data_to_insert['comment'] = $comment;
        $data_to_insert['rating'] = $rating;
        $data_to_insert['date_added'] = date('Y-m-d H:i:s');
        $data_to_insert['rating_type'] = $rating_type;

        $this->db->insert('video_interview_questions_rating', $data_to_insert);
    }

    function get_applicant_video_questionnaire_rating($company_sid, $employer_sid, $applicant_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('rating_type', 'rating');

        $this->db->order_by('sid', 'DESC');
        $this->db->limit(1);

        $record_obj = $this->db->get('video_interview_questions_rating');
        $record_arr = $record_obj->result_array();
        if (!empty($record_arr)) {
            return $record_arr[0]['rating'];
        } else {
            return 0;
        }
    }

    function set_sent_configuration_status($verification_key, $status)
    {
        $this->db->where('public_key', $verification_key);
        $this->db->set('status', $status);
        $this->db->from('video_interview_questions_sent_config');
        $this->db->update();
    }

    function add_sent_configuration($data_to_insert)
    {
        $this->db->insert('video_interview_questions_sent_config', $data_to_insert);
    }

    function get_sent_configuration($verification_key)
    {
        $this->db->where('status', 1);
        $this->db->where('public_key', $verification_key);

        $this->db->from('video_interview_questions_sent_config');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function set_sent_question_status($applicant_sid, $question_sid, $is_latest)
    {
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('question_sid', $question_sid);
        $this->db->set('is_latest', $is_latest);
        $this->db->from('video_interview_questions_sent');
        $this->db->update();
    }
}