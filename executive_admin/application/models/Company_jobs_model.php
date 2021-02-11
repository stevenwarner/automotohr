<?php

class Company_Jobs_Model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_executive_admin_company_sids($executive_admin_sid)
    {
        $company_sids = array();
        if ($executive_admin_sid > 0) {
            $this->db->select('company_sid');
            $this->db->where('executive_admin_sid', $executive_admin_sid);

            $executive_admin_companies = $this->db->get('executive_user_companies')->result_array();

            if (!empty($executive_admin_companies)) {
                foreach ($executive_admin_companies as $company) {
                    $company_sids[] = $company['company_sid'];
                }
            }
        }

        return $company_sids;
    }

    // function to get jobs
    function get_company_jobs($company_sid, $executive_admin_sid = 0)
    {
        $company_sids = $this->get_executive_admin_company_sids($executive_admin_sid);

        if (in_array($company_sid, $company_sids)) {
            $this->db->select('*');
            $this->db->where('user_sid', $company_sid);
            return $this->db->get('portal_job_listings')->result_array();
        } else {
            return array();
        }
    }

    function get_company_job_applicants($company_sid, $executive_admin_sid = 0)
    {
//        $this->db->select('*');
//        $this->db->where('employer_sid', $company_id);
//        return $this->db->get('portal_job_applications')->result_array();

        $company_sids = $this->get_executive_admin_company_sids($executive_admin_sid);

        if (in_array($company_sid, $company_sids)) {

            $this->db->select('portal_applicant_jobs_list.job_sid');
            $this->db->select('portal_applicant_jobs_list.date_applied');
            $this->db->select('portal_job_applications.first_name');
            $this->db->select('portal_job_applications.last_name');
            $this->db->select('portal_job_applications.email');

            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
            $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');
            $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid');

            return $this->db->get('portal_applicant_jobs_list')->result_array();

//        $this->db->select('*');
//        $this->db->where('employer_sid', $company_id);
//        $this->db->from('portal_job_applications');
//        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_applications.job_sid');
//        return $this->db->get()->result_array();
        } else {
            return array();
        }
    }

}
