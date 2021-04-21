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
        //
        $a = $this->db
        ->select('sid, email')
        ->where('email regexp "@gma"', NULL, NULL)
        ->where('email not regexp "@gmail.com"', NULL, NULL)
        ->where('email not regexp ".edu"', NULL, NULL)
        ->where('email not regexp ".net"', NULL, NULL)
        ->where('email not regexp ".org"', NULL, NULL)
        ->where('email not regexp ".us"', NULL, NULL)
        ->where('email not regexp ".ca"', NULL, NULL)
        ->where('email not regexp ".de"', NULL, NULL)
        ->get('portal_job_applications');
        //
        $pa = $a->result_array();
        $a->free_result();
        //
        if(empty($pa)) {
            exit(0);
        }
        //
        foreach($pa as $p){
            //
            $oldEmail = $p['email'];
            //
            $newEmail = fixEmailAddress($p['email'], 'gmail');
            //
            $date_to_insert = array();
            $date_to_insert['applicant_id'] = $p['sid'];
            $date_to_insert['email'] = $oldEmail;
            $date_to_insert['updated_at'] = date('Y-m-d H:i:s');
            //
            $this->db->insert('fix_email_address_log', $date_to_insert);
            //
            $this->db->where('sid', $p['sid'])->update('portal_job_applications', [ 'email' => $newEmail ]);
            //
            echo $newEmail.'<br>';
        }

        //
        die('Till here');
    }

    //
    function tos(){
        //
        $records = $this->db
        ->select('sid, questions')
        ->get('performance_management_templates')
        ->result_array();
        //
        //
        foreach($records as $record){
            //
            $ques = json_decode($record['questions'], true);
            //
            foreach($ques as $k => $sq){
                //
                if($sq['question_type'] == 'text-rating'){
                    $ques[$k]['question_type'] = 'text-n-rating';
                }
            }
            //
            $this->db
            ->where('sid', $record['sid'])
            ->update('performance_management_templates', [
                'questions' => json_encode($ques)
            ]);             
        }
    }
}
