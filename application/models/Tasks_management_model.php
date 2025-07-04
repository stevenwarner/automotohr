<?php
class tasks_management_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function primary_applicants_data($company_sid) {
        $this->db->select('sid, first_name, last_name, email, pictures, phone_number, resume, cover_letter, referred_by_name, referred_by_email, applicant_type');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('hired_status', 0);
        $record = $this->db->get('portal_job_applications');
        $record_array = $record->result_array();
        $record->free_result();
        return $record_array;
    }

    function applicants_jobs_list($company_sid) {
        $this->db->select('sid, portal_job_applications_sid, job_sid, date_applied, applicant_type, archived, desired_job_title');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('archived', 0);
        $record = $this->db->get('portal_applicant_jobs_list');
        $record_array = $record->result_array();
        $record->free_result();
        return $record_array;
    }

    function get_assigned_tasks($employer_id, $status, $type = null) {
        $this->db->select('assignment_management.*');
        $this->db->select('users.first_name as to_first_name');
        $this->db->select('users.last_name as to_last_name');
        $this->db->select('users.job_title');
        $this->db->select('users.access_level');
        $this->db->select('users.access_level_plus');
        $this->db->select('users.is_executive_admin');
        $this->db->select('users.pay_plan_flag');

        if ($type == null || $type == 'to_me') {
            $this->db->where('assignment_management.employer_sid', $employer_id);
        } else {
            $this->db->where('assignment_management.assigned_by_sid', $employer_id);
        }

        $this->db->where('assignment_management.status', $status);
        $this->db->order_by('assignment_management.assigned_date', 'DESC');
        $this->db->join('users', 'users.sid = assignment_management.employer_sid', 'left');
        $record = $this->db->get('assignment_management');
        $record_array = $record->result_array();
        $record->free_result();
        return $record_array;
    }

    function get_all_applicants($company_sid) {
        $this->db->select('pja.sid as applicant_sid, pja.first_name, pja.last_name, pja.email, pja.phone_number, pja.resume, pja.cover_letter'); // portal_job_applications
        $this->db->select('pajl.sid as list_sid, pajl.job_sid, pajl.date_applied, pajl.applicant_type'); // portal_applicant_jobs_list
        $this->db->select('pjl.Title as job_title'); // portal_job_listings
        $this->db->where('pja.employer_sid', $company_sid);
        $this->db->where('pajl.archived', 0);
        $this->db->where('pajl.applicant_type', 'Applicant');
        $this->db->join('portal_applicant_jobs_list as pajl', 'pajl.portal_job_applications_sid = pja.sid');
        $this->db->join('portal_job_listings as pjl', 'pjl.sid = pajl.job_sid');
        $record = $this->db->get('portal_job_applications as pja');
        $record_array = $record->result_array();
        $record->free_result();
        return $record_array;
    }

    function get_all_hiring_managers($company_sid, $access_level, $employer_sid) {
        $this->db->select('sid, email, active, first_name, last_name, access_level, access_level_plus, job_title, pay_plan_flag, is_executive_admin');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        //$this->db->where('is_executive_admin', 0);
        $this->db->where_in('access_level', $access_level);
        $this->db->where_not_in('sid', $employer_sid);
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        $record = $this->db->get('users');
        $record_array = $record->result_array();
        $record->free_result();
        return $record_array;
    }

    function get_all_active_jobs($company_sid) {
        $this->db->select('sid, active, status, company_name, Title, JobType, JobCategory, visible_to, approval_status, approval_status_by, approval_status_change_datetime');
        $this->db->where('user_sid', $company_sid);
        $this->db->where('active', 1);
        $record = $this->db->get('portal_job_listings');
        $record_array = $record->result_array();
        $record->free_result();
        return $record_array;
    }

    function get_applicant_name($applicant_sid) {
        $this->db->select('first_name, last_name');
        $this->db->where('sid', $applicant_sid);
        $record = $this->db->get('portal_job_applications');
        $record_array = $record->result_array();
        $record->free_result();
        $applicant_name = '';

        if (!empty($record_array)) {
            $applicant_name = $record_array[0]['first_name'] . ' ' . $record_array[0]['last_name'];
        }

        return $applicant_name;
    }

    function insert_data($data, $tablename) {
        $this->db->insert($tablename, $data);
        return $this->db->insert_id();
    }

    function get_assignment_details($sid, $company_sid) {
        $this->db->select('*');
        $this->db->where('assignment_management_sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('note_datetime', 'ASC');
        $record = $this->db->get('assignment_notes');
        $record_array = $record->result_array();
        $record->free_result();
        return $record_array;
    }

    function get_applicant_details($applicant_sid, $company_sid) {
        $this->db->select('employer_sid, first_name, last_name, pictures, email, phone_number, applicant_type, desired_job_title');
        $this->db->where('sid', $applicant_sid);
        $this->db->where('employer_sid', $company_sid);
        $record = $this->db->get('portal_job_applications');
        $record_array = $record->result_array();
        $record->free_result();
        return $record_array[0];
    }

    function check_if_applicant_already_assigned($applicant_sid, $employee_sid, $employer_sid) {
        $this->db->select('sid');
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('employer_sid', $employee_sid);
        $this->db->where('assigned_by_sid', $employer_sid);
        $this->db->from('assignment_management');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['sid'];
        } else {
            return 0;
        }
    }

    function get_employer_details($sid) {
        $this->db->select('email, active, first_name, last_name, access_level, is_executive_admin');
        $this->db->where('sid', $sid);
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }
    
    function unassign($sid){
        $data = array();
        $data['status'] = 'deleted';
        $this->db->where('sid', $sid);
        $this->db->update('assignment_management', $data);
    }

}
