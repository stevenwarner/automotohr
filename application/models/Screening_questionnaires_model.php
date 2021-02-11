<?php
class screening_questionnaires_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert_questionnaires($data) {
        $this->db->insert('portal_screening_questionnaires', $data);
    }

    function update_questionnaires($sid, $data) {
        $this->db->where('sid', $sid);
        $this->db->update('portal_screening_questionnaires', $data);
    }

    function delete_questionnaires($sid) {
        $this->db->where('sid', $sid);
        $this->db->delete('portal_screening_questionnaires');
    }

    function get_all_questionnaires($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $result = array();
        $records_obj = $this->db->get('portal_screening_questionnaires');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            $result = $records_arr[0];
        }
        
        return $result;
    }

    function get_all_questionnaires_by_employer($employer_sid) {
        $this->db->select('*');
        $this->db->where('employer_sid', $employer_sid);
        $this->db->order_by('sid', 'desc');

        $records_obj = $this->db->get('portal_screening_questionnaires');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        return $records_arr;
    }

    function insert_questions($data) {
        $this->db->insert('portal_questions', $data);
        return $this->db->insert_id();
    }

    function update_questions($sid, $data) {
        $this->db->where('sid', $sid);
        $this->db->update('portal_questions', $data);
    }

    function delete_questions($sid) {
        $this->db->where('sid', $sid);
        $this->db->delete('portal_questions');
    }

    function get_all_questions($sid) {
        $this->db->select('*');
        $this->db->where('questionnaire_sid', $sid);
        $this->db->order_by("sid", "desc");
        
        $records_obj = $this->db->get('portal_questions');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        return $records_arr;
    }

    function insert_question_option($data) {
        $this->db->insert('portal_question_option', $data);
    }

    function delete_question_option($sid) {
        $this->db->where('questions_sid', $sid);
        $this->db->delete('portal_question_option');
    }

    function get_all_question_option($sid) {
        $this->db->select('*');
        $this->db->where('questions_sid', $sid);
        $this->db->order_by('sid', 'ASC');

        $records_obj = $this->db->get('portal_question_option');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        return $records_arr;
    }
    
    function update_old_questionnaire_records() { // recheck the logic again
        $this->db->select('*');
        $this->db->where('result_status', NULL);
        $records_obj = $this->db->get('portal_question_option');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        exit;
//        echo '<pre>';
//        print_r($records_arr);
//        exit;
        foreach($records_arr as $record_arr) {
        $sid = $record_arr['sid'];
        $questions_sid = $record_arr['questions_sid'];
        $score = $record_arr['score'];
        
        $this->db->select('questionnaire_sid');
        $this->db->where('sid', $questions_sid);
        $obj = $this->db->get('portal_questions');
        $arr = $obj->result_array();
        $obj->free_result();
        
        if(!empty($arr)) {
            $questionnaire_sid = $arr[0]['questionnaire_sid'];
        
            $this->db->select('passing_score');
            $this->db->where('sid', $questionnaire_sid);
            $obj1 = $this->db->get('portal_screening_questionnaires');
            $arr1 = $obj1->result_array();
            $obj1->free_result();
            
                if(!empty($arr1)) {
                    $passing_score = $arr1[0]['passing_score'];
                    echo '<br>sid: '.$sid.' questions_sid: '.$questions_sid.' score: '.$score.' questionnaire_sid: '.$questionnaire_sid.' passing_score: '.$passing_score;
                    
                    if($score >= $passing_score) {
                        echo '<br> PASS';
                        $result_status = 'Pass';
                        
                        $update_data = array(
                                                'questionnaire_sid' => $questionnaire_sid,
                                                'result_status' => $result_status
                                            );
                        
                        $this->db->where('sid', $sid);
                        $this->db->update('portal_question_option', $update_data);
                        echo '<br>'.$this->db->last_query().'<hr>';
                    } else {
                        echo '<br> Fail';
                        $result_status = 'Fail';
                        
                        $update_data = array(
                                                'questionnaire_sid' => $questionnaire_sid,
                                                'result_status' => $result_status
                                            );
                        
                        $this->db->where('sid', $sid);
                        $this->db->update('portal_question_option', $update_data);
                        echo '<br>'.$this->db->last_query().'<hr>';
                    }
                }
            }
        }
        exit;
        //return $records_arr;
    }
}