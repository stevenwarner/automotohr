<?php
define('PJL', 'portal_job_listings');
define('PJA', 'portal_job_applications');
define('PAJL', 'portal_applicant_jobs_list');

class Test_model extends CI_Model
{
    //
    //
    function __construct()
    {
        //
        parent::__construct();
        //
        // $this->db = $this->load->database('ac', true);
        $this->db2 = $this->load->database('ahr', true);
    }


    //
    // function jobIds(){
    //     $ids = $this->db
    //     ->select('sid')
    //     ->get(PJL)
    //     ->result_array();
    //     //
    //     return !empty($ids) ? array_column($ids, 'sid') : [];
    // }

    // //
    // function getJobs(){
    //     $ids = $this->db2
    //     ->order_by('sid', 'desc')
    //     ->get(PJL)
    //     ->result_array();
    //     //
    //     return $ids;
    // }

    // //
    // function updateJob($sid, $data){
    //     $this->db
    //     ->where('sid', $sid)
    //     ->update(PJL, $data);
    // }

    // //
    // function insertJob($data){
    //     $this->db
    //     ->insert(PJL, $data);
    // }


    // //
    // function applicantIds(){
    //     $ids = $this->db
    //     ->select('sid')
    //     ->get(PJA)
    //     ->result_array();
    //     //
    //     return !empty($ids) ? array_column($ids, 'sid') : [];
    // }

    // //
    // function getApplicants(){
    //     $ids = $this->db2
    //     ->order_by('sid', 'desc')
    //     ->get(PJA)
    //     ->result_array();
    //     //
    //     return $ids;
    // }

    // //
    // function updateApplicant($sid, $data){
    //     $this->db
    //     ->where('sid', $sid)
    //     ->update(PJA, $data);
    // }

    // //
    // function insertApplicant($data){
    //     $this->db
    //     ->insert(PJA, $data);
    // }


    // //
    // function applicantJobIds(){
    //     $ids = $this->db
    //     ->select('sid')
    //     ->get(PAJL)
    //     ->result_array();
    //     //
    //     return !empty($ids) ? array_column($ids, 'sid') : [];
    // }

    // //
    // function getApplicantsJob(){
    //     $ids = $this->db2
    //     ->order_by('sid', 'desc')
    //     ->get(PAJL)
    //     ->result_array();
    //     //
    //     return $ids;
    // }

    // //
    // function updateApplicantJob($sid, $data){
    //     $this->db
    //     ->where('sid', $sid)
    //     ->update(PAJL, $data);
    // }

    // //
    // function insertApplicantJob($data){
    //     $this->db
    //     ->insert(PAJL, $data);
    // }

    function getEEOCRecords()
    {
        $this->db2->select('sid,last_sent_at,assigned_at');
        $this->db2->from('portal_eeo_form');
        $result = $this->db2->get()->result_array();

        return $result;
    }

    function updateEEOCTime($sid, $datetime)
    {
        $data_to_update = array();
        $data_to_update['assigned_at'] = $datetime;
        $this->db->where('sid', $sid);
        $this->db->update('portal_eeo_form', $data_to_update);
    }



    //
    function getRehiredemployees()
    {
        $this->db2->select('sid');
        $this->db->where('general_status', 'rehired');
        $this->db->where('active', 0);
        $this->db2->from('users');
        $result = $this->db2->get()->result_array();

        return $result;
    }


    //
    function updateEmployee($sid)
    {
        $data_to_update = array();
        $data_to_update['active'] = 1;
        $this->db->where('sid', $sid);
        $this->db->update('users', $data_to_update);
    }
}
