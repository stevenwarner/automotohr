<?php

class Applicant_interview_model extends CI_Model
{
    function get_applicant_data($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->order_by('sid', 'desc')->get()->row_array();
    }
}