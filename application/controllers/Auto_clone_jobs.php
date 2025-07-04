<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_clone_jobs extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('auto_clone_model');
    }

    public function index($verification_key = NULL) {
        // check for duplicate SELECT uid, COUNT(*) c FROM `portal_job_listings_feeds_data` GROUP BY uid HAVING c > 1
        // 0 0 1-30/3 * * it should be for every 3 days. Each Month counter is reset if you type 0 0 */6 * *
        if ($verification_key == 'dw6btPzuoHI9d5TEIKBKDGWwNoGEUlRuSidW8wQ4zSUHIl9gBxRx18Z3Aqk4HV7ZNCbu2ZfkjFVLHWINnz5uzMkUfIiINdZ19nJi') {
            $this->smart_indeed_clone();
            /* $counter_days = $this->auto_clone_model->check_autofeed_days_counter();
              //echo $counter_days;
              if($counter_days == 30) {
              $this->auto_clone_model->reset_counter_data();
              $all_jobs_data = $this->auto_clone_model->get_all_active_jobs();

              for($i=0; $i<count($all_jobs_data); $i++){
              $job_sid = $all_jobs_data[$i]['sid'];
              $company_sid = $all_jobs_data[$i]['user_sid'];
              $this->auto_clone_model->update_job_uid($job_sid, $company_sid);
              }

              mail(TO_EMAIL_DEV, 'Auto Clone Jobs Executed: '.date('Y-m-d H:i:s'), print_r($all_jobs_data, true));
              } else {
              $this->auto_clone_model->update_counter_data();
              } */
        }
    }

    function bulk_insert_previous_jobs() {
        //$this->auto_clone_model->insert_clone_data();
    }

    function check_for_applicants() {
        $applicants_data = $this->auto_clone_model->get_job_applicants();

        foreach ($applicants_data as $key => $value) {
            $sid = $value['sid'];
            $email = $value['email'];
            $portal_job_applications_sid = $value['portal_job_applications_sid'];
            $job_sid = $value['job_sid'];
            $user_sid = $value['user_sid'];
            echo "<hr><br>email: " . $email . " = sid: " . $sid . " = portal_job_applications_sid: " . $portal_job_applications_sid . " = job_sid: " . $job_sid . " = user_sid: " . $user_sid;
        }
    }

    function smart_clone_job() {
        // step 1: Get all active accounts
        // step 2: get all active companies and their approval right status
        // step 3: get rid of all jobs that belongs to inactive companies. 
        // step 4: if job approval right is enabled then filter out all not approved jobs
        // step 5: check the published date of each job in 'portal_job_listings_feeds_data' and filter out all jobs published today
        // step 6: get old jobs and clone them

        $today_start = date('Y-m-d 00:00:01');
        $today_end = date('Y-m-d 11:59:59');
//            $jobs_published_today                                               = $this->auto_clone_model->get_active_published_today($today_start, $today_end);
//            $get_active_uids                                                    = $this->auto_clone_model->get_active_uids($today_start, $jobs_published_today);
    }

    function sync_portal_job_feed() {
        /* $all_jobs_data = $this->auto_clone_model->get_active_uids();

          foreach($all_jobs_data as $key => $ajd) { //offload all jobs inactive jobs and jobs of inactive company
          $sid = $ajd['sid'];
          $job_sid = $ajd['job_sid'];
          $company_sid = $ajd['company_sid'];
          $this->auto_clone_model->update_company_job_status($job_sid, $company_sid, $sid);
          echo $key.' = '.$this->db->last_query().'<br>';
          }

          $all_jobs_data = $this->auto_clone_model->get_active_uids();

          foreach($all_jobs_data as $key => $ajd) {
          $job_sid = $ajd['job_sid'];
          $company_sid = $ajd['company_sid'];
          //                $this->auto_clone_model->check_duplicate_key($job_sid);
          $this->auto_clone_model->verify_each_job($job_sid, $company_sid);
          }
         */
    }

    public function smart_indeed_clone() {
			return;
        $today_start = date("Y-m-d 00:00:01"); //"2018-06-23 00:00:00";
        $today_end = date("Y-m-d 23:59:59"); //"2018-06-23 11:59:59";
        $week_start = date('Y-m-d 00:00:01', strtotime('-7 days'));
        $did_we_clone = false;
        $can_be_cloned = array();
        $cloned_data = array();
        $max_allowed_cloned_jobs = 50;
        $purchasedJobs = $this->auto_clone_model->get_all_company_jobs_indeed();
        $i = 0;
        $featuredArray[$i] = "";

        foreach ($purchasedJobs as $purchased) {
            $featuredArray[$i] = $purchased['sid'];
            $i++;
        }

        $jobData = $this->auto_clone_model->get_all_company_jobs_indeed_organic($featuredArray);
        $activeCompaniesArray = $this->auto_clone_model->get_all_active_companies();

        foreach ($jobData as $key => $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                $company_id = $job['user_sid'];
                $companyPortal = $this->auto_clone_model->get_portal_detail($company_id);

                if (empty($companyPortal)) {
                    continue;
                }

                $companydata = $this->auto_clone_model->get_company_name_and_job_approval($company_id);
                $has_job_approval_rights = $companydata['has_job_approval_rights'];

                if ($has_job_approval_rights == 1) {
                    $approval_right_status = $job['approval_status'];

                    if ($approval_right_status != 'approved') {
                        continue;
                    }
                }

                $uid = $job['sid'];
                $publish_date = $job['activation_date'];
                $feed_data = $this->auto_clone_model->fetch_uid_from_job_sid($uid);

                if (!empty($feed_data)) {
                    $uid = $feed_data['uid'];
                    $publish_date = $feed_data['publish_date'];
                }

                if ($today_end >= $publish_date && $today_start <= $publish_date) { // get the count for jobs published today
                    $max_allowed_cloned_jobs--;
                    continue;
                } else {
                    if ($today_end >= $publish_date && $week_start <= $publish_date) { // don't clone any jobs that were published in last week
                        continue;
                    } else {
                        $can_be_cloned[] = $job;
                    }
                }
            }
        }

        $company_cloned_ids = array();

        if (!empty($can_be_cloned) && $max_allowed_cloned_jobs > 0) {
            foreach ($can_be_cloned as $kcbc => $vcbc) {
                $job_sid = $vcbc['sid'];
                $company_sid = $vcbc['user_sid'];

                if (!in_array($company_sid, $company_cloned_ids) && $max_allowed_cloned_jobs > 0) {
                    $company_cloned_ids[] = $company_sid;
                    $this->auto_clone_model->update_job_uid($job_sid, $company_sid);
                    $cloned_data[] = array('job ID' => $job_sid, 'Company ID' => $company_sid);
                    $max_allowed_cloned_jobs--;
                    $did_we_clone = true;
                    unset($can_be_cloned[$kcbc]);
                }
            }
        }

        if (!empty($can_be_cloned) && $max_allowed_cloned_jobs > 0) {
            $company_cloned_ids = array();

            foreach ($can_be_cloned as $kcbc => $vcbc) {
                $job_sid = $vcbc['sid'];
                $company_sid = $vcbc['user_sid'];

                if (!in_array($company_sid, $company_cloned_ids) && $max_allowed_cloned_jobs > 0) {
                    $company_cloned_ids[] = $company_sid;
                    $this->auto_clone_model->update_job_uid($job_sid, $company_sid);
                    $max_allowed_cloned_jobs--;
                    $cloned_data[] = array('job ID' => $job_sid, 'Company ID' => $company_sid);
                    unset($can_be_cloned[$kcbc]);
                }
            }
        }

        if ($did_we_clone) {
            mail(TO_EMAIL_DEV, 'Auto Clone Jobs Executed: ' . date('Y-m-d H:i:s'), print_r($cloned_data, true));
        } else {
            mail(TO_EMAIL_DEV, 'Auto Clone Jobs not Executed: ' . date('Y-m-d H:i:s'), print_r($cloned_data, true));
        }
    }

}