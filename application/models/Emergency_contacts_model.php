<?php
class Emergency_contacts_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function getApplicantAverageRating($app_id, $users_type = NULL, $date = NULL) {
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
    
    function get_applicants_details($sid) {
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
    
    function getApplicantNotes($app_id) {
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $app_id);
        $this->db->order_by('sid', 'DESC');
        $this->db->from('portal_misc_notes');
        $records_obj = $this->db->get();
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        return $result;
    }
    
    function check_sent_video_questionnaires($applicant_sid, $company_sid) {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->limit(1);
        $this->db->from('video_interview_questions_sent');
        
        $records_obj = $this->db->get();
        $result = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }
    
    function check_answered_video_questionnaires($applicant_sid, $company_sid) {
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);
        $this->db->where('video_interview_questions_sent.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.status', 'answered');
        $this->db->join('video_interview_questions', 'video_interview_questions_sent.question_sid = video_interview_questions.sid');
        $this->db->from('video_interview_questions_sent');
        $data = $this->db->count_all_results();

        if ($data > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function get_emergency_contacts($type, $users_sid) {
        $this->db->where('users_type', $type);
        $this->db->where('users_sid', $users_sid);
        $this->db->order_by('priority', 'asc');
        $this->db->from('emergency_contacts');
        
        $records_obj = $this->db->get();
        $result = $records_obj->result_array();
        $records_obj->free_result();
        return $result;
    }
    
    function emergency_contacts_details($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->from('emergency_contacts');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    
    function getApplicantExtraAttachments($app_id, $employer_id, $users_type = NULL) {
        $result = $this->db->get_where('portal_applicant_attachments', array(
            'applicant_job_sid' => $app_id,
            /*'employer_sid' => $employer_id,*/  //This  brings only Employer Specific Files
            'users_type' => $users_type
        ));

        if ($result->num_rows() > 0) {
            $data = $result->result_array();
            return $data;
        }

    }
    
    function add_emergency_contacts($data) {
        $this->db->insert('emergency_contacts', $data);
        $result = $this->db->affected_rows();

        if ($result != 1) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Could not add emergency contact, Please try Again!');
            $result = 'success';
        } else {
            $this->session->set_flashdata('message', '<b>Success: </b>Emergency contact added successfully.');
            $result = 'fail';
        }

        return $result;
    }

    function delete_emergency_contact($users_type, $users_sid, $contact_sid) {
        $this->db->where('users_type', $users_type);
        $this->db->where('users_sid', $users_sid);
        $this->db->where('sid', $contact_sid);
        $this->db->delete('emergency_contacts');
    }
    
    function get_company_detail($sid) {
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function edit_emergency_contacts($data, $sid) {
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


    public function get_emergency_contacts_history($type, $users_sid) {
        $this->db->where('users_type', $type);
        $this->db->where('users_sid', $users_sid);
        $this->db->order_by('logged_at ', 'Desc');
        $this->db->from('emergency_contacts_history');
        $records_obj = $this->db->get();
        $result = $records_obj->result_array();
        $records_obj->free_result();
        return $result;
    }


}