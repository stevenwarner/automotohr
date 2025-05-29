<?php

class Applicant_interview_model extends CI_Model
{
    function get_applicant_data($sid) {
        $this->db->select('portal_applicant_jobs_list.sid as jobs_list_sid, portal_applicant_jobs_list.*, portal_job_applications.*, users.CompanyName, users.profile_picture, users.Logo');
        $this->db->where('portal_applicant_jobs_list.sid', $sid);
        $this->db->join('users', 'users.sid = portal_applicant_jobs_list.company_sid');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->order_by('portal_applicant_jobs_list.sid', 'desc')->get()->row_array();
    }
}