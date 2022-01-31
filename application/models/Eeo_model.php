<?php

class eeo_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_jobs_by_company_id($company_sid)
    {
        $this->db->select('CONCAT(first_name, " " ,last_name) AS Name,date_applied as Date,us_citizen as US Citizen,visa_status as Visa Status,group_status as Group Status,veteran as Veteran,disability as Disability,gender as Gender');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('eeo_form', 'Yes');
        $this->db->join('portal_eeo_form', 'portal_job_applications.sid = portal_eeo_form.application_sid');
        return $this->db->get('portal_job_applications');
    }

    function get_total_candidates_count($employer_id)
    {
        $this->db->select('portal_applicant_jobs_list.sid');
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_listings.sid');
        $this->db->select('portal_eeo_form.sid');
        $this->db->where('portal_applicant_jobs_list.company_sid', $employer_id);
        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');
        $this->db->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid');
        $this->db->join('portal_eeo_form', 'portal_applicant_jobs_list.sid = portal_eeo_form.portal_applicant_jobs_list_sid');
        return $this->db->get('portal_applicant_jobs_list')->num_rows();
    }

    function get_eeo_candidates($employer_id, $limit = null, $start = null)
    {
        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.eeo_form');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        //$this->db->select('portal_job_listings.Title');     
        $this->db->select('portal_eeo_form.us_citizen');
        $this->db->select('portal_eeo_form.visa_status');
        $this->db->select('portal_eeo_form.group_status');
        $this->db->select('portal_eeo_form.veteran');
        $this->db->select('portal_eeo_form.disability');
        $this->db->select('portal_eeo_form.gender');
        $this->db->where('portal_applicant_jobs_list.company_sid', $employer_id);

        if ($limit != null) {
            $this->db->limit($limit, $start);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');
        //$this->db->join('portal_job_listings','portal_applicant_jobs_list.job_sid = portal_job_listings.sid');
        $this->db->join('portal_eeo_form', 'portal_applicant_jobs_list.sid = portal_eeo_form.portal_applicant_jobs_list_sid');
        $this->db->order_by("portal_applicant_jobs_list.date_applied", "DESC");
        return $this->db->get('portal_applicant_jobs_list')->result_array();
    }

    function get_job_title_by_type($job_sid, $applicant_type, $desired_job_title)
    {
        $job_title = '';

        if ($applicant_type == 'Applicant') {
            $job_title = get_job_title($job_sid);
        } else if ($applicant_type == 'Talent Network' || $applicant_type == 'Imported Resume') {
            if ($desired_job_title != NULL && $desired_job_title != '') {
                $job_title = $desired_job_title;
            } else {
                $job_title = 'Job Not Applied';
            }
        } else if ($applicant_type == 'Manual Candidate') {
            if ($job_sid != 0) {
                $job_title = get_job_title($job_sid);
            } else {
                $job_title = 'Job Not Applied';
            }
        }

        return $job_title;
    }

    function get_all_eeo_applicants($keyword, $opt_status, $start_date, $end_date, $company_id, $records_per_page = null, $my_offset = 0, $count_only = false)
    {

        $this->db->select('portal_applicant_jobs_list.sid as application_list_sid');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.eeo_form');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.ip_address');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');

        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');

        $this->db->select('portal_eeo_form.*');

        $this->db->select('portal_job_listings.Title as job_title');

        $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');

        if ($keyword != 'all' && $keyword != null && !empty($keyword)) {
            $keyword = trim($keyword);
            $this->db->like("CONCAT((portal_job_applications.first_name),(' '),(portal_job_applications.last_name))", $keyword);
        }

        if (($start_date != 'all' && $start_date != null) && ($end_date != 'all' && $end_date != null)) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if (($start_date != 'all' && $start_date != null) && ($end_date != 'all' && $end_date == null)) {
            $this->db->where('portal_applicant_jobs_list.date_applied >=', $start_date);
        } else if (($start_date != 'all' && $start_date == null) && ($end_date != 'all' && $end_date != null)) {
            $this->db->where('portal_applicant_jobs_list.date_applied <=', $end_date);
        }

        $this->db->where('portal_applicant_jobs_list.company_sid', $company_id);

        if ($opt_status != 'all' && !empty($opt_status)) {
            $opt_status = $opt_status == 'other' ? null : $opt_status;
            $this->db->where('portal_applicant_jobs_list.eeo_form', $opt_status);
        }

        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');

        if ($records_per_page != null && $count_only == false) {
            $this->db->limit($records_per_page, $my_offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_eeo_form', 'portal_applicant_jobs_list.sid = portal_eeo_form.portal_applicant_jobs_list_sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $this->db->from('portal_applicant_jobs_list');

        if (!$count_only) {
            $record_obj = $this->db->get();
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
        } else {
            $record_arr = $this->db->count_all_results();
        }

        //my_print_r($this->db->last_query(), '39.42.6.226');

        return $record_arr;
    }

    public function insert_eeo_record($users_type, $users_sid, $data = array())
    {
        $this->db->where('users_type', $users_type);
        $this->db->where('application_sid', $users_sid);
        $this->db->set('is_latest', 0);
        $this->db->update('portal_eeo_form');

        if (!isset($data['is_latest'])) {
            $data['is_latest'] = 1;
        }

        $this->db->insert('portal_eeo_form', $data);
    }

    public function update_eeo_form_status($users_type, $users_sid, $status)
    {
        if ($users_type == 'applicant') {
            $this->db->where('portal_job_applications_sid', $users_sid);
            $this->db->set('eeo_form', $status);
            $this->db->update('portal_applicant_jobs_list');
        } else if ($users_type == 'employee') {
//            $this->db->where('sid', $users_sid);
//            $this->db->set('eeo_form_status', $status);
//            $this->db->update('users');
        }
    }

    public function get_eeo_form_status($users_type, $users_sid)
    {
        $form_status = null;
        if ($users_type == 'applicant') {
            $this->db->select('eeo_form');
            $this->db->where('portal_job_applications_sid', $users_sid);
            $this->db->where('eeo_form', 'Yes');
            $this->db->limit(1);
            $this->db->order_by('sid', 'DESC');

            $record_obj = $this->db->get('portal_applicant_jobs_list');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            if (!empty($record_arr)) {
                $form_status = $record_arr[0]['eeo_form'];
            } else {
                $form_status = 'No';
            }
        } else if ($users_type == 'employee') {
            $this->db->where('users_type', 'employee');
            $this->db->where('application_sid', $users_sid);
            $this->db->from('portal_eeo_form');
            $record_obj = $this->db->get();
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            if (!empty($record_arr)) {
                $form_status = 'Yes';
            } else{
                $form_status = 'No';
            }
//            $this->db->select('eeo_form_status');
//            $this->db->where('sid', $users_sid);
//            $record_obj = $this->db->get('users');
//            $record_arr = $record_obj->result_array();
//            $record_obj->free_result();
//
//            if (!empty($record_arr)) {
//                $form_status = $record_arr[0]['eeo_form_status'];
//            }
        }

        return $form_status;
    }

    public function get_latest_eeo_record($user_type, $user_sid)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid', $user_sid);
        //$this->db->where('is_latest', 1);

        $this->db->order_by('sid', 'DESC');
        $this->db->limit(1);

        $record_obj = $this->db->get('portal_eeo_form');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $record_arr = $record_arr[0];

            return $record_arr;
        } else {
            return array();
        }
    }

    function get_user_eeo_form_info ($user_sid, $user_type) {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid', $user_sid);
        $result = $this->db->get('portal_eeo_form')->row_array();

        $return_data = array();

        if (!empty($result)) {
            $return_data = $result;
        }

        return $return_data;
    }
}
