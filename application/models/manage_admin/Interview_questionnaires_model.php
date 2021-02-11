<?php

class Interview_questionnaires_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_questionnaires($company_sid = 0){
        $this->db->where('status !=', 'deleted');
        $this->db->where('company_sid', $company_sid);

        $data_rows = $this->db->get('interview_questionnaires')->result_array();

        if(!empty($data_rows)){
            foreach($data_rows as $key => $data_row) {
                $sections_count = $this->count_sections($data_row['sid']);
                $data_rows[$key]['sections_count'] = $sections_count;
            }
        }

        return $data_rows;
    }

    public function get_questionnaire($sid, $company_sid = 0){
        $this->db->where('sid', $sid);

        if($company_sid > 0){
            $this->db->where('company_sid', $company_sid);
        }

        $data_row = $this->db->get('interview_questionnaires')->result_array();

        if(!empty($data_row)){
            $data_row = $data_row[0];
            return $data_row;
        } else {
            return array();
        }
    }


    public function insert_questionnaire($data_row){
        $this->db->insert('interview_questionnaires', $data_row);
    }

    public function update_questionnaire($sid, $data_row){
        $this->db->where('sid', $sid);
        $this->db->update('interview_questionnaires', $data_row);
    }


    public function get_questionnaire_sections($questionnaire_sid){
        $this->db->where('questionnaire_sid', $questionnaire_sid);
        $this->db->where('status !=', 'deleted');

        $this->db->order_by('sort_order', 'ASC');
        $data_rows = $this->db->get('interview_questionnaire_sections')->result_array();

        if(!empty($data_rows)){
            foreach($data_rows as $key => $data_row){
                $questions_count = $this->count_questions($data_row['sid']);
                $data_rows[$key]['questions_count'] = $questions_count;

                $candidate_questions = $this->get_questionnaire_section_questions($data_row['sid'], 'candidate');
                $data_rows[$key]['candidate_questions'] = $candidate_questions;

                $interviewer_questions = $this->get_questionnaire_section_questions($data_row['sid'], 'interviewer');
                $data_rows[$key]['interviewer_questions'] = $interviewer_questions;
            }
        }

        return $data_rows;
    }

    public function get_questionnaire_section($questionnaire_section_sid){
        $this->db->where('sid', $questionnaire_section_sid);
        $data_row = $this->db->get('interview_questionnaire_sections')->result_array();

        if(!empty($data_row)){
            return $data_row[0];
        } else {
            return array();
        }
    }

    public function insert_questionnaire_section($data_row){
        $this->db->insert('interview_questionnaire_sections', $data_row);
    }

    public function update_questionnaire_section($sid, $data_row){
        $this->db->where('sid', $sid);
        $this->db->update('interview_questionnaire_sections', $data_row);
    }

    public function insert_questionnaire_section_question($data_row){
        $this->db->insert('interview_questionnaire_section_questions', $data_row);
    }

    public function update_questionnaire_section_question($sid, $data_row){
        $this->db->where('sid', $sid);
        $this->db->update('interview_questionnaire_section_questions', $data_row);
    }

    public function count_sections($interview_questionnaire_sid){
        $this->db->where('questionnaire_sid', $interview_questionnaire_sid);
        $this->db->where('status !=', 'deleted');
        return $this->db->get('interview_questionnaire_sections')->num_rows();
    }

    public function count_questions($questionnaire_section_sid){
        $this->db->where('questionnaire_section_sid', $questionnaire_section_sid);
        return $this->db->get('interview_questionnaire_section_questions')->num_rows();
    }

    public function get_questionnaire_section_questions($questionnaire_section_sid, $questions_for = null){
        $this->db->where('questionnaire_section_sid', $questionnaire_section_sid);

        $this->db->where('status !=', 'deleted');

        if($questions_for != null){
            $this->db->where('question_for', $questions_for);
        }

        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('interview_questionnaire_section_questions')->result_array();
    }

    public function get_questionnaire_section_question($questionnaire_section_question_sid){
        $this->db->where('sid', $questionnaire_section_question_sid);
        $data_row = $this->db->get('interview_questionnaire_section_questions')->result_array();

        if(!empty($data_row)){
            $data_row = $data_row[0];

            return $data_row;
        } else {
            return array();
        }
    }

    public function delete_questionnaire_section_question($questionnaire_section_question_sid){
        $data_to_update = array();
        $data_to_update['status'] = 'deleted';

        $this->db->where('sid', $questionnaire_section_question_sid);
        $this->db->update('interview_questionnaire_section_questions', $data_to_update);
    }

    public function delete_questionnaire_section($questionnaire_section_sid){
        $data_to_update = array();
        $data_to_update['status'] = 'deleted';

        $this->db->where('sid', $questionnaire_section_sid);
        $this->db->update('interview_questionnaire_sections', $data_to_update);
    }
    
    public function check_unique_title($title, $company_sid) {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('LOWER(title)', strtolower($title));
        $this->db->where('status <>', 'deleted');
        $result = $this->db->get('interview_questionnaires')->result_array();
        
        if(!empty($result)){
            return 'not_allowed';
        } else {
            return 'allowed';
        }
    }
    
    public function delete_questionnaire($questionnaire_sid, $company_sid){
        $data_to_update = array();
        $data_to_update['status'] = 'deleted';

        $this->db->where('sid', $questionnaire_sid);
        $this->db->update('interview_questionnaires', $data_to_update);
        
        $update_array = array();
        $update_array['interview_questionnaire_sid'] = 0;
        $this->db->where('interview_questionnaire_sid', $questionnaire_sid);
        $this->db->where('user_sid', $company_sid);
        $this->db->update('portal_job_listings', $update_array);
    }
    
    public function get_questionnaire_data_simple($questionnaire_sid){
        $this->db->where('sid', $questionnaire_sid);
        return $this->db->get('interview_questionnaires')->result_array();
    }

    public function get_questionnaire_data($questionnaire_sid, $company_sid = 0){
        $this->db->select('*');
        $this->db->where('sid', $questionnaire_sid);
        $this->db->where('status', 'active');

        if($company_sid > 0) {
            $this->db->where('company_sid', $company_sid);
        }

        $questionnaire_data = $this->db->get('interview_questionnaires')->result_array();

        if(!empty($questionnaire_data)){
            $questionnaire_data = $questionnaire_data[0];

            $this->db->select('*');
            $this->db->where('questionnaire_sid', $questionnaire_sid);
            $this->db->where('status', 'active');
            $this->db->order_by('sort_order', 'ASC');

            $sections = $this->db->get('interview_questionnaire_sections')->result_array();

            if(!empty($sections)){
                foreach($sections as $sec_key => $section){

                    $candidate_questions = $this->get_questions($questionnaire_sid, $section['sid'], 'candidate');
                    $sections[$sec_key]['candidate_questions'] = $candidate_questions;

                    $interviewer_questions = $this->get_questions($questionnaire_sid, $section['sid'], 'interviewer');
                    $sections[$sec_key]['interviewer_questions'] = $interviewer_questions;
                }

                $questionnaire_data['sections'] = $sections;
            } else {
                $questionnaire_data['sections'] = array();
            }
        }

        return $questionnaire_data;
    }


    public function get_questions($questionnaire_sid, $section_sid, $question_for = 'candidate'){
        $this->db->select('*');
        $this->db->where('question_for', $question_for);
        $this->db->where('questionnaire_sid', $questionnaire_sid);
        $this->db->where('questionnaire_section_sid', $section_sid);
        $this->db->where('status', 'active');

        $this->db->order_by('sort_order', 'ASC');

        $questions = $this->db->get('interview_questionnaire_section_questions')->result_array();


        foreach($questions as $ques_key => $question){
            $options_array = array();
            if(!empty($question['answer_options']) && ($question['answer_type'] == 'mca_m' || $question['answer_type'] == 'mca_s')){
                $options = explode(',', $question['answer_options']);
            } else {
                $options = array();
            }

            $questions[$ques_key]['answer_options'] = $options;
        }

        return $questions;
    }

    public function clone_questionnaire($title, $short_description, $questionnaire_sid, $company_sid){
        $this->db->where('sid', $questionnaire_sid);
        $questionnaire = $this->db->get('interview_questionnaires')->result_array();

        if(!empty($questionnaire)){
            $questionnaire = $questionnaire[0];

            $questionnaire['company_sid'] = $company_sid;
            $questionnaire['title'] = $title;
            $questionnaire['short_description'] = $short_description;
            $questionnaire['created_date'] = date('Y-m-d H:i:s');

            unset($questionnaire['sid']);

            $this->db->insert('interview_questionnaires', $questionnaire);
            $cloned_questionnaire_sid = $this->db->insert_id();
            $this->db->where('questionnaire_sid', $questionnaire_sid);
            $this->db->where('status', 'active');
            $questionnaire_sections = $this->db->get('interview_questionnaire_sections')->result_array();

            if(!empty($questionnaire_sections)){
                foreach($questionnaire_sections as $section){
                    $section['questionnaire_sid'] = $cloned_questionnaire_sid;
                    $section['company_sid'] = $company_sid;

                    $section_sid = $section['sid'];

                    unset($section['sid']);

                    $this->db->insert('interview_questionnaire_sections', $section);

                    $cloned_section_sid = $this->db->insert_id();

                    $this->db->where('questionnaire_sid', $questionnaire_sid);
                    $this->db->where('questionnaire_section_sid', $section_sid);
                    $this->db->where('status', 'active');

                    $questionnaire_section_questions = $this->db->get('interview_questionnaire_section_questions')->result_array();

                    if(!empty($questionnaire_section_questions)){
                        foreach($questionnaire_section_questions as $question){
                            $question['questionnaire_sid'] = $cloned_questionnaire_sid;
                            $question['questionnaire_section_sid'] = $cloned_section_sid;
                            $question['company_sid'] = $company_sid;

                            unset($question['sid']);

                            $this->db->insert('interview_questionnaire_section_questions', $question);
                        }
                    }
                }
            }
        }
    }

    public function insert_default_question($question_text, $category = 1){

        $data_to_insert = array();
        $data_to_insert['question_text'] = $question_text;
        $data_to_insert['created_date'] = date('Y-m-d H:i:s');
        $data_to_insert['answer_required'] = 0;
        $data_to_insert['status'] = 'active';
        $data_to_insert['question_type'] = 'system_default';
        $data_to_insert['question_category'] = $category;

        $this->db->insert('interview_questionnaire_section_questions', $data_to_insert);
    }

    public function update_default_question($question_sid, $question_text, $category = 1){
        $data_to_update = array();
        $data_to_update['question_text'] = $question_text;
        $data_to_update['modified_date'] = date('Y-m-d H:i:s');
        $data_to_update['answer_required'] = 0;
        $data_to_update['status'] = 'active';
        $data_to_update['question_category'] = $category;

        $this->db->where('sid', $question_sid);
        $this->db->where('question_type', 'system_default');

        $this->db->update('interview_questionnaire_section_questions', $data_to_update);

    }

    /**
     * @param null $status
     * @param int $category
     * @return mixed
     *
     * Categories
     *
     * 0 = Please Select
     * 1 = Basic Interview Questions
     * 2 = Behavioral Interview Questions
     * 3 = Brainteasers
     * 4 = More Questions About You
     * 5 = Salary Questions
     */
    public function get_default_questions_categorized($status = null){
        $my_return = array();
        for($count = 1; $count <= 5; $count++){
            $questions = $this->get_default_questions($status, $count);

            $category = array();
            $category['id'] = $count;

            switch($count){
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

    public function get_default_questions($status = null, $category = 0){
        $this->db->where('question_type', 'system_default');
        if($status == null) {
            $this->db->where('status !=', 'deleted');
        } else {
            $this->db->where('status', $status);
        }

        if($category > 0){
            $this->db->where('question_category', $category);
        }
        return $this->db->get('interview_questionnaire_section_questions')->result_array();
    }

    public function get_default_question($question_sid){
        $this->db->where('sid', $question_sid);
        $this->db->where('question_type', 'system_default');
        $question = $this->db->get('interview_questionnaire_section_questions')->result_array();

        if(!empty($question)){
            return $question[0];
        } else {
            return array();
        }
    }

    public function delete_default_question($question_sid){
        $this->db->where('sid', $question_sid);
        $this->db->where('question_type', 'system_default');

        $data_to_update = array();
        $data_to_update['status']  =  'deleted';

        $this->db->update('interview_questionnaire_section_questions', $data_to_update);
    }

    public function clone_default_questions($questionnaire_sid, $questionnaire_section_sid, $question_sids = array(), $company_sid = 0){
        $this->db->select('*');
        $this->db->where('sid IN ( ' . implode(',', $question_sids) . ' ) ');

        $questions_to_clone = $this->db->get('interview_questionnaire_section_questions')->result_array();

        foreach($questions_to_clone as $question){
            $question['questionnaire_sid'] = $questionnaire_sid;
            $question['questionnaire_section_sid'] = $questionnaire_section_sid;
            $question['company_sid'] = $company_sid;
            $question['created_date'] = date('Y-m-d H:i:s');
            $question['question_type'] = 'user_defined';
            $question['status'] = 'active';

            unset($question['sid']);

            $this->db->insert('interview_questionnaire_section_questions', $question);

        }

    }


    public function get_applicant_information($company_sid, $applicant_sid){
        /*

        $this->db->select('portal_applicant_jobs_list.*');
        $this->db->select('portal_applicant_jobs_list.sid as portal_applicant_jobs_list_sid');

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
        $this->db->select('portal_job_applications.extra_info');
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_job_applications.job_fit_category_sid');

        */


        $this->db->select('*');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('sid', $applicant_sid);

        $applicant_info = $this->db->get('portal_job_applications')->result_array();

        if(!empty($applicant_info)){
            return $applicant_info[0];
        } else {
            return array();
        }
    }

    public function get_job_information($company_sid, $job_sid){
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);
        $this->db->where('sid', $job_sid);

        $job_info = $this->db->get('portal_job_listings')->result_array();

        if(!empty($job_info)){
            return $job_info[0];
        } else {
            return array();
        }
    }

    public function insert_questionnaire_score_data($data, $company_sid = 0, $employer_sid = 0, $candidate_type = '', $candidate_sid = 0, $questionnaire_sid = 0){
        if($company_sid > 0 && $employer_sid > 0 && $candidate_type != '' && $candidate_sid > 0){
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $employer_sid);
            $this->db->where('candidate_type', $candidate_type);
            $this->db->where('candidate_sid', $candidate_sid);
            $this->db->where('questionnaire_sid', $questionnaire_sid);
            $this->db->delete('interview_questionnaire_score');
        }

        $this->db->insert('interview_questionnaire_score', $data);
    }

    public function get_questionnaire_score_data($company_sid, $employer_sid, $candidate_sid, $candidate_type, $job_sid, $questionnaire_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('candidate_sid', $candidate_sid);
        $this->db->where('candidate_type', $candidate_type);

        if($questionnaire_sid > 0) {
            $this->db->where('questionnaire_sid', $questionnaire_sid);
        }

        if($job_sid > 0) {
            $this->db->where('job_sid', $job_sid);
        }

        $this->db->limit(1);

        $questionnaire_score_data = $this->db->get('interview_questionnaire_score')->result_array();

        if(!empty($questionnaire_score_data)){
            $questionnaire_score_data = $questionnaire_score_data[0];

            $employer_sid = $questionnaire_score_data['employer_sid'];

            $this->db->select('first_name');
            $this->db->select('last_name');
            $this->db->where('sid', $employer_sid);
            $conducted_by = $this->db->get('users')->result_array();

            if(!empty($conducted_by)){
                $questionnaire_score_data['interview_conducted_by'] = $conducted_by[0];
            }

            return $questionnaire_score_data;

        } else {
            return array();
        }
    }

    public function get_questionnaire_score_data_candidate_specific($company_sid, $candidate_sid, $candidate_type, $job_sid, $questionnaire_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('candidate_sid', $candidate_sid);
        $this->db->where('candidate_type', $candidate_type);

        if($questionnaire_sid > 0) {
            $this->db->where('questionnaire_sid', $questionnaire_sid);
        }

        if($job_sid > 0) {
            $this->db->where('job_sid', $job_sid);
        }


        $questionnaire_score_data = $this->db->get('interview_questionnaire_score')->result_array();

        if(!empty($questionnaire_score_data)) {
            foreach ($questionnaire_score_data as $key => $score) {
                $this->db->select('first_name, last_name, job_title');
                $this->db->where('sid', $score['employer_sid']);

                $employer_data = $this->db->get('users')->result_array();

                if(!empty($employer_data)){
                    $questionnaire_score_data[$key]['scored_by'] = $employer_data[0];
                } else {
                    $questionnaire_score_data[$key]['scored_by'] = array();
                }
            }
        }

        return $questionnaire_score_data;
    }

    public function get_questionnaire_average_score($company_sid, $user_sid, $user_type, $job_sid){
        $my_return = array();

        $candidate_scores = $this->get_questionnaire_score_data_candidate_specific($company_sid, $user_sid, $user_type, $job_sid, 0);


        $candidate_total_score = 0;
        $job_relevancy_total_score = 0;
        $overall_score = 0;

        $total_star_rating = 0;

        $candidate_average_score = 0;
        $job_relevancy_average_score = 0;
        $overall_average_score = 0;
        $average_star_rating = 0;

        if(!empty($candidate_scores)){
            foreach($candidate_scores as $key => $score){
                $candidate_total_score += $score['candidate_score'];
                $job_relevancy_total_score += $score['job_relevancy_score'];
                $overall_score += $score['candidate_overall_score'] + $score['job_relevancy_overall_score'];
                $total_star_rating +=  $score['star_rating'];
            }
        }

        $score_count = count($candidate_scores);

        if($score_count > 0){
            $candidate_average_score = $candidate_total_score / $score_count;
            $job_relevancy_average_score = $job_relevancy_total_score / $score_count;
            $overall_average_score = (($overall_score * 10) / 2) / $score_count;
            $average_star_rating = $total_star_rating / $score_count;
        }

        $my_return['candidate_score'] = $candidate_average_score;
        $my_return['job_relevancy_score'] = $job_relevancy_average_score;
        $my_return['overall_score'] = $overall_average_score;
        $my_return['star_rating'] = $average_star_rating;

        return $my_return;
    }
}
