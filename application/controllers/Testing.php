<?php defined('BASEPATH') || exit('No direct script access allowed');

ini_set('memory_limit', -1);
set_time_limit(0);

class Testing extends CI_Controller{
    //
    public function __construct(){
        parent::__construct();
        //
        $this->load->model('test_model', 'tm');
    }
	//
    function jobs(){
        //
        $jobs = $this->db->select('
            portal_job_listings.sid,
            portal_job_listings.status,
            portal_job_listings.active,
            portal_job_listings.approval_status,
            portal_job_listings.approval_status_change_datetime,
            portal_job_listings.organic_feed,
            portal_job_listings.activation_date,
            portal_job_listings.deactivation_date,
            portal_job_listings.published_on_career_page,
            portal_job_listings.expiration_date,
            users.sid as company_id,
            users.active as company_active,
            users.has_job_approval_rights,
            users.is_paid
        ')
        ->join('users', 'users.sid = portal_job_listings.user_sid', 'inner')
        ->get('portal_job_listings')
        ->result_array();
        //
        header('Content-Type: application/json');
        echo json_encode($jobs);
    }

    //
    function sj(){
        //
        if(!is_cli()){
            echo "Finished";
            exit(0);
        }
        //
        $ids = $this->tm->jobIds();
        $jobs = $this->tm->getJobs();
        //
        if(empty($jobs)){
            echo "Finished..";
            exit(0);
        }
        $isEmpty = empty($ids);
        //
        $u = 0;
        $i = 0;
        //
        foreach($jobs as $job){
            //
            if(!$isEmpty && in_array($job['sid'], $ids)){
                // Update
                $sid = $job['sid'];
                //
                unset($job['sid']);
                //
                $this->tm->updateJob($sid, $job);
                //
                $u++;
            } else{
                // Insert
                $this->tm->insertJob($job);
                //
                $i++;
            }
        }
        //
        echo "Inserted: {$i} \n";
        echo "Updated: {$u} \n";
        exit(0);
    }

    //
    function sja(){
        //
        if(!is_cli()){
            echo "Finished";
            exit(0);
        }
        //
        $ids = $this->tm->applicantIds();
        $jobs = $this->tm->getApplicants();
        //
        if(empty($jobs)){
            echo "Finished.";
            exit(0);
        }
        $isEmpty = empty($ids);
        //
        $u = 0;
        $i = 0;
        //
        foreach($jobs as $job){
            //
            if(!$isEmpty && in_array($job['sid'], $ids)){
                // Update
                $sid = $job['sid'];
                //
                unset($job['sid']);
                //
                $this->tm->updateApplicant($sid, $job);
                //
                $u++;
            } else{
                // Insert
                $this->tm->insertApplicant($job);
                //
                $i++;
            }
        }
        //
        echo "Inserted: {$i} \n";
        echo "Updated: {$u} \n";
        exit(0);
    }
    
    //
    function sajl(){
        //
        if(!is_cli()){
            echo "Finished";
            exit(0);
        }
        //
        $ids = $this->tm->applicantJobIds();
        $jobs = $this->tm->getApplicantsJob();
        //
        if(empty($jobs)){
            echo "Finished.";
            exit(0);
        }
        $isEmpty = empty($ids);
        //
        $u = 0;
        $i = 0;
        //
        foreach($jobs as $job){
            //
            if(!$isEmpty && in_array($job['sid'], $ids)){
                // Update
                $sid = $job['sid'];
                //
                unset($job['sid']);
                //
                $this->tm->updateApplicantJob($sid, $job);
                //
                $u++;
            } else{
                // Insert
                $this->tm->insertApplicantJob($job);
                //
                $i++;
            }
        }
        //
        echo "Inserted: {$i} \n";
        echo "Updated: {$u} \n";
        exit(0);
    }
}
