<?php

class bulk_resume_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    /**
     * get resumes according to
     * companies and jobs
     *
     * @param $employer_id Integer
     * @param $job_sid Integer
     * @param $date String
     * @param $page Integer
     * @param $limit Integer
     *
     * @return Array
     */
    function get_all_resume_by_job($employer_id, $job_sid = 'all', $date = '', $page = 1, $limit = 100, $aws = FALSE) {
        // turn off the cache
        $this->db->cache_off();
        // set limit starting point
        $start_limit = $page == 1 ? 0 : ( (($page * $limit) - $limit) );
        // select column
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.resume');
        // select columns  
        // when applicant ids are provided
        if($aws){
            $this->db->select('portal_job_applications.first_name');
            $this->db->select('portal_job_applications.last_name');
            $this->db->select('portal_job_applications.email');
        }
        else $this->db->select('concat(portal_job_applications.first_name, " ", portal_job_applications.last_name) AS fullname');
        // select column   
        $this->db->select('portal_job_applications.resume');
        // select column
        // when applicant ids are provided
        if(!$aws) $this->db->select('portal_applicant_jobs_list.job_sid');
        // select table
        $this->db->from('portal_job_applications');
        // set join
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'inner');
        // set condition
        // on company id
        // when applicant ids are not provided
        if(!$aws) $this->db->where('portal_job_applications.employer_sid', $employer_id);
        // set condition
        // date -  date column converts to Y-m
        // when applicant ids are not provided
        if(!$aws) $this->db->where('date_format(portal_applicant_jobs_list.date_applied, "%Y-%m") = ', $date);
        // set condition
        // applicant without resume will not list in results
        // when applicant ids are provided
        $this->db->group_start();
        $this->db->where('portal_job_applications.resume != ""', NULL);
        $this->db->where('portal_job_applications.resume IS NOT NULL', NULL);
        $this->db->group_end();
        // group by jobs id
        // when applicant ids are not provided
        if(!$aws) $this->db->group_by('portal_applicant_jobs_list.portal_job_applications_sid');
        // order by application id - DESC
        $this->db->order_by('portal_job_applications.sid', 'DESC');
        // set limit 
        // when applicant ids are not provided
        if(!$aws) $this->db->limit( $limit, $start_limit );
        // set condition 
        // only get record that matches applicant ids
        // when applicant ids are provided
        if($aws && is_array($aws)){
            $this->db->where_in(
                'portal_job_applications.sid', 
                $aws
            );
        }
        // set condition 
        // only get record that matches applicant id
        // when applicant ids are provided
        if($aws && !is_array($aws) && $aws != 'all'){
            $this->db->where(
                'portal_job_applications.sid', 
                $aws
            );
        }
        // set condition
        // get records according to job ids
        $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
        // get main records
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        // return record if application ids are not set
        if($aws) return $records_arr;
        // counted records
        // only for applicant search
        $this->db
        ->select('portal_applicant_jobs_list.portal_job_applications_sid')
        ->from('portal_job_applications')
        ->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'inner')
        ->where('portal_job_applications.employer_sid', $employer_id)
        ->where('date_format(portal_applicant_jobs_list.date_applied, "%Y-%m") = ', $date)
        ->group_start()
        ->where('portal_job_applications.resume != ""', NULL)
        ->where('portal_job_applications.resume IS NOT NULL', NULL)
        ->group_end()
        ->where('portal_applicant_jobs_list.job_sid', $job_sid)
        ->order_by('portal_job_applications.sid', 'DESC');
        // return records and total records
        return array('Records' => $records_arr, 'Count' => $this->db->count_all_results());
    }


    function get_all_resumes($employer_id, $job_sid = 'all', $start_date = '', $end_date = '') {
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.resume');

        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->where('portal_job_applications.employer_sid', $employer_id);

        if ($job_sid != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
        }

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied <=', $end_date);
        }

        $this->db->group_start();
        $this->db->where('portal_job_applications.resume !=', NULL);
        $this->db->where('portal_job_applications.resume !=', '');
        $this->db->group_end();

        $this->db->group_by('portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->order_by('portal_job_applications.sid', 'DESC');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');

        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function record_exception($company_id, $employer_id, $object_type, $exception_message) {
        $data_to_insert = array('company_sid' => $company_id,
                                'employer_sid' => $employer_id,
                                'resume' => $object_type,
                                'exception' => $exception_message,
                                'date_time' => date('Y-m-d H:i:s')
                            );
        
        $this->db->insert('bulk_zip_download_exceptions', $data_to_insert);
    }

    function get_all_jobs($company_sid) {
        $this->db->select('sid');
        $this->db->select('title');
        $this->db->select('active');
        $this->db->select('activation_date');
        $this->db->where('user_sid', $company_sid);
        $this->db->order_by('activation_date', 'DESC');

        $records_obj = $this->db->get('portal_job_listings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return ($records_arr);
    }
}