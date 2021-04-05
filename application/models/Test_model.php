<?php 
define('PJL', 'portal_job_listings') ;
define('PJA', 'portal_job_applicants') ;
define('PAJL', 'portal_applicant_jobs_list') ;

class Test_model extends CI_Model {
    //
    //
    function __construct() {
        //
        parent::__construct();
        //
        $this->db = $this->load->database('ac', true);
        $this->db2 = $this->load->database('ahr', true);
    }


    //
    function jobIds(){
        $ids = $this->db
        ->select('sid')
        ->get(PJL)
        ->result_array();
        //
        return !empty($ids) ? array_column($ids, 'sid') : [];
    }
   
    //
    function getJobs(){
        $ids = $this->db2
        ->order_by('sid', 'desc')
        ->get(PJL)
        ->result_array();
        //
        return !empty($ids) ? array_column($ids, 'sid') : [];
    }

    //
    function updateJob($sid, $data){
        $this->db
        ->where('sid', $sid)
        ->update(PJL, $data);
    }
    
    //
    function insertJob($data){
        $this->db
        ->insert(PJL, $data);
    }


    //
    function applicantIds(){
        $ids = $this->db
        ->select('sid')
        ->get(PJA)
        ->result_array();
        //
        return !empty($ids) ? array_column($ids, 'sid') : [];
    }

    //
    function getApplicants(){
        $ids = $this->db2
        ->order_by('sid', 'desc')
        ->get(PJA)
        ->result_array();
        //
        return !empty($ids) ? array_column($ids, 'sid') : [];
    }

    //
    function updateApplicant($sid, $data){
        $this->db
        ->where('sid', $sid)
        ->update(PJA, $data);
    }
    
    //
    function insertApplicant($data){
        $this->db
        ->insert(PJA, $data);
    }


    //
    function applicantJobIds(){
        $ids = $this->db
        ->select('sid')
        ->get(PAJL)
        ->result_array();
        //
        return !empty($ids) ? array_column($ids, 'sid') : [];
    }

    //
    function getApplicantsJob(){
        $ids = $this->db2
        ->order_by('sid', 'desc')
        ->get(PAJL)
        ->result_array();
        //
        return !empty($ids) ? array_column($ids, 'sid') : [];
    }

    //
    function updateApplicantJob($sid, $data){
        $this->db
        ->where('sid', $sid)
        ->update(PAJL, $data);
    }
    
    //
    function insertApplicantJob($data){
        $this->db
        ->insert(PAJL, $data);
    }
}