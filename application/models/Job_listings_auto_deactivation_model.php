<?php

class Job_listings_auto_deactivation_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_all_companies()
    {
        $this->db->select('sid');
        $this->db->select('CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->from('users');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }


    function get_all_expiring_jobs($start, $end)
    {
        $this->db->select('sid');
        $this->db->select('Title');
        $this->db->select('active');
        $this->db->where('active', 1);
        $this->db->where('`expiration_date` BETWEEN \'' . $start . '\' AND \'' . $end . '\'');
        $this->db->from('portal_job_listings');
        $jobs_obj = $this->db->get();
        $jobs_arr = $jobs_obj->result_array();
        $jobs_obj->free_result();

        return $jobs_arr;
    }

    function set_active_status($sids, $status)
    {
        $this->db->where_in('sid', $sids);
        $this->db->set('active', $status);
        $this->db->from('portal_job_listings');
        $this->db->update();
    }

    function active_jobs_with_expiry($today_end_str)
    {

        $this->db->select('
            portal_job_listings.*,
            users.CompanyName,
            users.has_applicant_approval_rights
        ');


        $lastDate = date('Y-m-d h:i:s', strtotime($today_end_str . "-7 days"));

        $this->db->group_start();
        $this->db->where('portal_job_listings.expiration_date !=', NULL);
        $this->db->where('portal_job_listings.expiration_date <=', $today_end_str);
        $this->db->where('portal_job_listings.expiration_date >=', $lastDate);
        $this->db->group_end();

        $this->db->where('portal_job_listings.active', 1);
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'inner');

        $jobs_obj = $this->db->get('portal_job_listings');
        $jobs_arr = $jobs_obj->result_array();
        $jobs_obj->free_result();

        //
        return $jobs_arr;
    }



    function get_notifications_status($company_sid, $notifications_type = 'approval_rights_notifications')
    {
        $this->db->select($notifications_type);
        $this->db->where('company_sid', $company_sid);
        $result_row = $this->db->get('notifications_emails_configuration')->result_array();

        if (!empty($result_row)) {
            return $result_row[0]['approval_rights_notifications'];
        } else {
            return 0;
        }
    }

    function get_notification_emails($company_id, $notifications_type)
    {
        $this->db->select('contact_name, email, contact_no, employer_sid');
        $this->db->where('company_sid', $company_id);
        $this->db->where('notifications_type', $notifications_type);
        $this->db->where('status', 'active');
        $result = $this->db->get('notifications_emails_management')->result_array();
        return $result;
    }

    function get_company_name($sid)
    {
        $this->db->select('CompanyName');
        $this->db->where('sid', $sid);
        $this->db->from('users');
        $result_obj = $this->db->get();
        $result_arr = $result_obj->result_array();
        $result_obj->free_result();

        if (!empty($result_arr)) {
            return $result_arr[0]['CompanyName'];
        }
    }


    //

    function active_jobs_with_expiry_old($today_end_str)
    {
        $this->db->select('
            portal_job_listings.*,
            users.CompanyName,
            users.has_applicant_approval_rights
        ');
        $this->db->group_start();
        $this->db->where('portal_job_listings.expiration_date !=', NULL);
        $this->db->where('portal_job_listings.expiration_date <=', $today_end_str);
        $this->db->group_end();
        $this->db->where('portal_job_listings.active', 1);
        $this->db->from('users', 'users.sid = portal_job_listings.user_sid', 'inner');
        $this->db->from('portal_job_listings');
        $this->db->limit(1);
        $jobs_obj = $this->db->get();
        $jobs_arr = $jobs_obj->result_array();
        $jobs_obj->free_result();

        return $jobs_arr;
    }
}
