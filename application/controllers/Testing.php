<?php defined('BASEPATH') || exit('No direct script access allowed');

ini_set('memory_limit', -1);
set_time_limit(0);

class Testing extends CI_Controller{
    //
    public function __construct(){
        parent::__construct();
        //
        $this->load->model('test_model', 'tm');
        $this->load->model('application_tracking_system_model', 'atsm');
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

    function email_fix () {
        $portal_job_applications = array(
            array('email' => 'prosaifhasi@gmail.coooom'),
            array('email' => 'daniellev0120@gmail.cpm'),
            array('email' => 'juniormurchison69@gmail'),
            array('email' => 'agostiniroberto0@gmail.om'),
            array('email' => 'ANDREWCARDONA46@GMAI.COM'),
            array('email' => 'christophercamat@gmail.cm'),
            array('email' => 'kaylaertzner11@gmail.c'),
            array('email' => 'jomikka1613@gmailcom'),
            array('email' => 'kelly.c.douglas@gmaik.con'),
            array('email' => 'xitlali.g.sanchez@gmailc.om'),
            array('email' => 'krupadalal007@gmail.con'),
            array('email' => 'georgerod10@gmailcom'),
            array('email' => 'noah.andujar@gmail.con'),
            array('email' => 'ramos.luis34@gmail.con'),
            array('email' => 'accaciacarter@gmail.con'),
            array('email' => 'ryanhalligan1995@gmail.con'),
            array('email' => 'dannilerae@gmai.com'),
            array('email' => 'markfarrar44@gmail'),
            array('email' => 'moalademi@gmail.con'),
            array('email' => 'ratul.rashad@gmail.con'),
            array('email' => 'ratul.rashad@gmail.con'),
            array('email' => 'krupadalal007@gmail.con'),
            array('email' => 'krupadalal007@gmail.con'),
            array('email' => 'krupadalal007@gmail.con')
        );

        foreach ($portal_job_applications as $key => $applicant) {
            $applicant_id = $key+1; 
            $fixer = fixEmailAddress($applicant['email'], 'gmail');
            //
            $date_to_insert = array();
            $date_to_insert['applicant_id'] = $applicant_id;
            $date_to_insert['email'] = $applicant['email'];
            $date_to_insert['updated_at'] = date('Y-m-d H:i:s');
            //
            $this->load->model('application_tracking_system_model');
            $this->application_tracking_system_model->mantain_incorrect_email_log($date_to_insert);
            //
            echo $fixer.'<br>';
        }
    }
}
