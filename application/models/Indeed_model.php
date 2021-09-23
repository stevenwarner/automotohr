<?php

class Indeed_model extends CI_Model {
    function __construct() { parent::__construct(); }
    //

    /**
     * Insert job budget
     * @param Array $post
     * @return Integer
     */
    function insertJobBudget($post){
        $insertArray = array();
        $insertArray['budget'] = $post['budget'];
        $insertArray['job_sid'] = $post['jobSid'];
        $insertArray['charged_by'] = $post['employeeSid'];
        $insertArray['start_date'] = date('Y-m-d');
        if($post['budgetPerDay'] != 0)
        $insertArray['expire_date'] = date('Y-m-d', strtotime('+'.( $post['budgetPerDay'] ).' days'));
        else $insertArray['expire_date'] = date('Y-m-d', strtotime('+30 days'));
        $insertArray['budget_days'] = $post['budgetPerDay'];
        if($post['phoneNumber'] != '')
        $insertArray['phone_number'] = $post['phoneNumber'];
        // _e($insertArray, true, true);
        //
        $this->db->insert('portal_job_indeed', $insertArray);
        //
        $insertSid = $this->db->insert_id();
        //
        $this->db
        ->where('sid', $post['jobSid'])
        ->update('portal_job_listings', array(
            'indeed_sponsored' => 1
        ));
        //
        return $insertSid;
    }

    function getJobBudgetAndExpireOldBudget($jobSid){
        $result =
        $this->db
        ->select('
            sid as budget_sid,
            budget,
            budget_days,
            expire_date,
            phone_number
        ')
        ->from('portal_job_indeed')
        ->where('job_sid', $jobSid)
        ->where('expire_date > CURDATE() OR expire_date IS NULL', null)
        ->order_by('sid', 'DESC')
        ->limit(1)
        ->get();
        //
        $budget = $result->row_array();
        $result = $result->free_result();
        //
        if(!sizeof($budget)) $this->db->where('sid', $jobSid)->update('portal_job_listings', array('indeed_sponsored'=> 0));
        return $budget;
    }

    /**
     * Get Indeed Paid jobs
     * @return Array
     */
    function getIndeedPaidJobIds(){
        $result = $this->db
        ->select('job_sid, budget')
        ->from('portal_job_indeed')
        ->order_by('sid', 'DESC')
        ->get();
        //
        $jobs = $result->result_array();
        $result = $result->free_result();
        //
        if(sizeof($jobs)){
            $ids = array();
            $budget = array();
            //
            foreach ($jobs as $k0 => $v0) {
                // Set budget
                if(isset($budget[$v0['job_sid']])) $budget[$v0['job_sid']] += $v0['budget'];
                else $budget[$v0['job_sid']] = $v0['budget'];

                // Set Ids
                $ids[] = $v0['job_sid'];
            }
            //
            return array(
                'Ids' => $ids,
                'Budget' => $budget
            );
        }
        return array(
            'Ids' => array(),
            'Budget' => array()
        );
    }

    /**
    * Get active Indeed paid jobs
    * @param Array $paidJobIds
    * @return Array
     */
    function getIndeedPaidJobs($paidJobIds = array()){
        $result = $this->db->where('active', 1)
        // ->where_in('sid', $paidJobIds)
        ->where('indeed_sponsored', 1)
        ->order_by('sid', 'desc')
        ->get('portal_job_listings');
        //
        $jobs = $result->result_array();
        $result = $result->free_result();
        //
        return $jobs;
    }


    /**
     * Get active Indeed organic jobs
     * @param Array $paidJobIds
     * @return Array
     */
    function getIndeedOrganicJobs($paidJobIds = array()){
         $this->db->where('active', 1)
        ->where('organic_feed', 1);
        // ->where('indeed_sponsored', 0)
        if(!empty($paidJobIds)){
            $this->db->where_not_in('sid', $paidJobIds);
        }
        $this->db->order_by('sid', 'desc');
        $result =$this->db->get('portal_job_listings');
        //
        $jobs = $result->result_array();
        $result = $result->free_result();
        //
        return $jobs;
    }


    /**
     * Get all active companies
     * @return Array
     */
    function getAllActiveCompanies($feedSid){
        $result = $this->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `is_paid`='1' AND `career_site_listings_only` = 0 AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();
        if (count($result) > 0) {
            $data = array();
            foreach ($result as $r) {
                // Check if feed is allowed
                if($this->db
                    ->where('company_sid', $r['sid'])
                    ->where('feed_sid', $feedSid)
                    ->where('status', 0)
                    ->count_all_results('feed_restriction')
                ){
                    continue;
                }
                $data[] = $r['sid'];
            }
            return $data;
        } else {
            return 0;
        }
    }

    /**
     * Get company details
     * @param  Integer $companySid
     * @return Array
     */
    function getPortalDetail($companySid){
        $result =$this->db
        ->where('user_sid', $companySid)
        ->get('portal_employer');
        //
        $portal = $result->row_array();
        $result = $result->free_result();
        //
        return $portal;
    }

    /**
     * Get company approval rights status
     * @param  Integer $companySid
     * @return Array
     */
    function getCompanyNameAndJobApproval($companySid) {
        $result = $this->db
        ->select('CompanyName, has_job_approval_rights, ContactName as full_name, PhoneNumber as phone_number, email')
        ->where('sid', $companySid)
        ->get('users');
        //
        $record = $result->row_array();
        $result = $result->free_result();
        //
        return $record;
    }

    /**
     * Get UID of Job
     * @param  Integer $jobSid
     * @return Array
     */
    function fetchUidFromJobSid($jobSid){
        $result = $this->db
        ->select('uid, publish_date')
        ->where('job_sid', $jobSid)
        ->where('active', 1)
        ->order_by('sid', 'desc')
        ->limit(1)
        ->get('portal_job_listings_feeds_data');
        //
        $record = $result->row_array();
        $result = $result->free_result();
        //
        return $record;
    }

    function getPhonenumber($jobSid, $companySid){
        $r = array('email' => '', 'phone_number' => '');
        $a = $this->db
        ->select('phone_number')
        ->where('job_sid', $jobSid)
        ->get('portal_job_indeed');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        $a = $this->db
        ->select('PhoneNumber')
        ->where('parent_sid', $companySid)
        ->where("PhoneNumber != ''", null)
        ->order_by('is_primary_admin', 'DESC')
        ->get('users');
        //
        $c = $a->row_array();
        $a = $a->free_result();

        //
        // $a = $this->db
        // ->select('email')
        // ->where('parent_sid', $companySid)
        // ->where("email != ''", null)
        // ->order_by('is_primary_admin', 'DESC')
        // ->get('users');
        // //
        // $d = $a->row_array();
        // $a = $a->free_result();
        //
        if(sizeof($b)) $r['phone_number'] = $b['phone_number'];
        // if(sizeof($d)) $r['email'] = $d['email'];
        if(!sizeof($b) && sizeof($c)) $r['phone_number'] = $c['PhoneNumber'];
        //
        return $r;
    }

    /**
     * Budget details
     * @param Array $formpost
     * @return Decimal
     */
    function isBudgetExists($formpost){
        $result = $this->db
        ->select('budget')
        ->where('sid', $formpost['budgetSid'])
        ->get('portal_job_indeed');
        //
        $budget = $result->row_array();
        $result = $result->free_result();
        //
        return sizeof($budget) ? $budget['budget'] : 0;
    }

    /**
     * Update budget details
     * @return VOID
     */
    function updateBudget($post){
        $updateArray = array();
        $updateArray['budget'] = $post['budget'];
        $updateArray['job_sid'] = $post['jobSid'];
        $updateArray['charged_by'] = $post['employeeSid'];
        if($post['budgetPerDay'] != 0)
        $updateArray['expire_date'] = date('Y-m-d', strtotime('+'.( $post['budgetPerDay'] ).' days'));
        else $insertArray['expire_date'] = date('Y-m-d', strtotime('+30 days'));
        $updateArray['budget_days'] = $post['budgetPerDay'];
        if($post['phoneNumber'] != '')
        $updateArray['phone_number'] = $post['phoneNumber'];
        //
        $this->db
        ->where('sid', $post['budgetSid'])
        ->update('portal_job_indeed', $updateArray);
        //
        $this->db
        ->where('sid', $post['jobSid'])
        ->update('portal_job_listings', array(
            'indeed_sponsored' => 1
        ));
    }

    //
    function GetCompanyIndeedDetails($companyId, $jobId){
        //
        $r = ['Phone' => '', 'Email' => '', 'Name' => ''];
        //
        $details = $this->db->where('company_sid', $companyId)->get('company_indeed_details')->row_array();
        //
        if(!empty($details)){
            $r['Name'] = $details['contact_name'];
            $r['Email'] = $details['contact_email'];
            $r['Phone'] = $details['contact_phone'];
        } else{
            $details = $this->db
            ->select('phone_number')
            ->where('job_sid', $jobId)
            ->get('portal_job_indeed')
            ->row_array();
            //
            if(!empty($details)){
                $r['Phone'] = $details['phone_number'];
            }
        }

        return $r;
    }
}