<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Time_check extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $date_func = date('Y-m-d H:i:s');

        $today = new DateTime();
        $datetime_func = $today->format('Y-m-d H:i:s');


        $message = "Server Time is" . PHP_EOL;
        $message .= "date() function : "  . $date_func . PHP_EOL;
        $message .= "DateTime() function : "  . $datetime_func . PHP_EOL;

        mail('j.taylor.title@gmail.com', 'AutomotoHr Debug - Time', $message);
    }

    public function fix_other_applicants(){
        $this->db->select('sub_domain');
        $this->db->from('portal_employer');
        $domains_obj = $this->db->get();
        $domains_arr = $domains_obj->result_array();
        $domains_obj->free_result();

        $this->db->select('sid');
        $domains = '';
        $this->db->group_start();
        foreach($domains_arr as $domain)
        {
            $this->db->not_like('applicant_source', $domain['sub_domain'] );
        }
        $this->db->not_like('applicant_source', 'career_website');
        $this->db->group_end();

        $this->db->from('portal_applicant_jobs_list');
        $applicants_obj = $this->db->get();
        $applicants_arr = $applicants_obj->result_array();
        $applicants_obj->free_result();

        echo '<pre>';
        echo $this->db->last_query();
        echo '</pre>';

        echo '<hr />';
        $all_sids = array();
        foreach($applicants_arr as $record){
            $all_sids[] = $record['sid'];
        }

        $sid_chunks = array_chunk($all_sids, 25000);

        foreach($sid_chunks as $sid_chunk){
            $this->db->set('eeo_form', null);
            $this->db->where_in('sid', $sid_chunk);
            $this->db->from('portal_applicant_jobs_list');
            $query = $this->db->get_compiled_update();

            echo '<pre>';
            print_r($query);
            echo '</pre>';
            echo '<hr />';
        }



    }

    public function add_visibility_records_for_none_applicants(){
        $this->db->select('sid');
        $this->db->where('parent_sid', 0);
        $this->db->from('users');

        $records_obj = $this->db->get();
        $company_records = $records_obj->result_array();
        $records_obj->free_result();

        foreach($company_records as $company_record){
            $this->db->where('job_sid', 0);
            $this->db->where('company_sid', $company_record['sid']);
            $this->db->where('employer_sid', 0);
            $this->db->from('portal_job_listings_visibility');
            $count = $this->db->count_all_results();

            if($count <= 0) {
                $data_to_insert = array();
                $data_to_insert['job_sid'] = 0;
                $data_to_insert['company_sid'] = $company_record['sid'];
                $data_to_insert['employer_sid'] = 0;
                $this->db->insert('portal_job_listings_visibility', $data_to_insert);

                echo 'Record Inserted for Company ID = ' . $company_record['sid'] . ' <br />';
            } else {
                echo 'Skipped Company ID = ' . $company_record['sid'] . ' <br />';
            }
        }
    }

}