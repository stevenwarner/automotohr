<?php

class Advanced_reporting_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all_companies()
    {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('terminated_status', 0);
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }

    //
    function get_applicants($company_sid, $keyword, $count_only = false, $limit = null, $offset = null)
    {

        $companyIds = explode(',', $company_sid);

        if (!in_array('all', $companyIds)) {
            $this->db->where_in('portal_applicant_jobs_list.company_sid', $companyIds);
        }

        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.status');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.score');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('portal_applicant_jobs_list.main_referral');
        $this->db->select('users.CompanyName');
        $this->db->select('portal_job_applications.address');
        $this->db->select('count(portal_applicant_jobs_list.portal_job_applications_sid) as total_jobs_applied');

        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);

        if (!empty($keyword) && $keyword != 'all') {

            $multiple_keywords = explode(',', $keyword);
            $this->db->group_start();
            for ($i = 0; $i < count($multiple_keywords); $i++) {

                $tK = preg_replace('/\s+/', '|', strtolower($multiple_keywords[$i]));
                //
                if (preg_match("/\s+/", $multiple_keywords[$i])) {
                    $this->db->or_where("LOWER(concat(portal_job_applications.first_name,' ', portal_job_applications.last_name)) = ", strtolower($multiple_keywords[$i]));
                    $this->db->or_where("LOWER(concat(portal_job_applications.first_name,' ',portal_job_applications.middle_name,' ', portal_job_applications.last_name)) = ", strtolower($multiple_keywords[$i]));
                    $this->db->or_where("LOWER(concat(portal_job_applications.first_name,' ',portal_job_applications.middle_name)) = ", strtolower($multiple_keywords[$i]));
                } else {
                    $this->db->or_where("(lower(portal_job_applications.first_name) regexp '" . ($tK) . "' or lower(portal_job_applications.last_name) regexp '" . ($tK) .   "%' or portal_job_applications.email LIKE '" . $keyword . "')  ", false, false);
                }

                $phoneRegex = strpos($multiple_keywords[$i], '@') !== false ? '' : preg_replace('/[^0-9]/', '', $multiple_keywords[$i]);
                $this->db->or_like('portal_job_applications.email', $multiple_keywords[$i]);
                if ($phoneRegex) {
                    $this->db->or_like('REGEXP_REPLACE(portal_job_applications.phone_number, "[^0-9]", "")', preg_replace('/[^0-9]/', '', $multiple_keywords[$i]), false);
                }
                $this->db->or_like('portal_job_applications.desired_job_title', $multiple_keywords[$i]);
                $this->db->or_like('portal_job_listings.Title', $multiple_keywords[$i]);

                $this->db->or_like('portal_job_applications.address', $multiple_keywords[$i]);
                $this->db->or_like('portal_job_applications.city', $multiple_keywords[$i]);
            }

            $this->db->group_end();
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        $this->db->join('portal_job_listings', 'portal_job_listings.sid=portal_applicant_jobs_list.job_sid', 'left');
        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('application_status', 'portal_applicant_jobs_list.status_sid = application_status.sid', 'left');
        $this->db->join('users', 'users.sid = portal_applicant_jobs_list.company_sid', 'left');

        $this->db->group_by('portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'Desc');

        if ($count_only == false) {
            $applications = $this->db->get('portal_applicant_jobs_list')->result_array();
            return $applications;
        } else {
            $count = $this->db->count_all_results('portal_applicant_jobs_list');
            return $count;
        }
    }


    //
    function get_applicants_jobs($applicant_sid)
    {

        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('users.CompanyName');
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->where('portal_applicant_jobs_list.portal_job_applications_sid', $applicant_sid);

        $this->db->join('portal_job_listings', 'portal_job_listings.sid=portal_applicant_jobs_list.job_sid', 'left');
        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('application_status', 'portal_applicant_jobs_list.status_sid = application_status.sid', 'left');
        $this->db->join('users', 'users.sid = portal_applicant_jobs_list.company_sid', 'left');

        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'Asc');

        $applications = $this->db->get('portal_applicant_jobs_list')->result_array();
        // $sql=$this->db->last_query();
        //  die($sql); 
        return $applications;
    }
}
