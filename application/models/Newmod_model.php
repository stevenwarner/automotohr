<?php

class Newmod_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function sendApplicantsToRemarket($start_datetime,$end_datetime){
        $where = "date_applied BETWEEN '".$start_datetime."' AND '".$end_datetime."'";

        $this->db->select('*');
        $this->db->where($where);
        //$this->db->order_by('date_applied','desc');
        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        foreach($records_arr as $applicant_info){
            echo $applicant_info['portal_job_applications_sid'].",";
            echo $applicant_info['date_applied']."   :   ";
            sleep(3);
            $this->get_remarket_company_settings($applicant_info);
        }
     }
    public function get_remarket_company_settings($job_list) {
        if($job_list['job_sid'] > 0){
            $this->db->select('status');
            $this->db->where('company_sid', $job_list['company_sid']);
            $record_obj = $this->db->get('remarket_company_settings');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            if(isset($record_arr[0])){
                $data = $record_arr[0];
            }else{
                $record_arr['status'] = 0;
                $data = $record_arr;
            }
            $data['questionnaire'] = '';
            if(isset($job_list['questionnaire'])){
                $data['questionnaire'] = $job_list['questionnaire'];
                unset($job_list['questionnaire']);
            }
            $data['talent_and_fair_data'] = '';
            if(isset($job_list['talent_and_fair_data'])){
                $data['talent_and_fair_data'] = $job_list['talent_and_fair_data'];
                unset($job_list['talent_and_fair_data']);
            }
            
            $data['portal_applicant_jobs_list'] = json_encode($job_list);
            $this->db->select('*');
            $this->db->where('sid', $job_list['portal_job_applications_sid']);
            $record_obj = $this->db->get('portal_job_applications');
            $data['portal_job_applications'] = json_encode($record_obj->row_array());
            $record_obj->free_result();
            send_settings_to_remarket(REMARKET_PORTAL_SAVE_APPLICANT_URL,$data);
        }
        
    }
}