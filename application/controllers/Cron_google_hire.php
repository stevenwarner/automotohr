<?php
/**
 * Cron job for Google hire 
 *  
 * 
 * created_at: 18-03-2019
 */
class Cron_google_hire extends CI_Controller{
	/**
	 * Set mode
	 * 1 = Test
	 * 0 = Production
	 * 
	 **/
	private $is_test  = 1;
    /**
     * Set hashing method
     */
    private $hash_type  = 'RS256';
    /**
     * Set auth url
     */
    private $auth_url  = 'https://www.googleapis.com/oauth2/v4/token';
    /**
     * Holds access token
     */
    private $access_token = '';


    
    function __construct() {
        parent::__construct();
        $this->load->model('Cron_google_hire_model', 'google_hire');
    }

	
	/**
	 * Sends request to the google to add records
	 *
	 * @return Void
	 */
	function index(){
        $this->new_index();
        return;
        // $active_jobs = $this->get_indeed_organic_job();
        // $active_jobs = $this->get_jobs_for_google_hire();
        $this->authorise();
        _e($this->access_token, true);
        // _e(sizeof($active_jobs), true);
        // _e($active_jobs, true);
        if(!$this->access_token) exit(0);
        _e('--end--', true, true);
        die();
         // get last record from 
        // database
        $result = $this->db
        ->select('ids')
        ->from('cron_google_hire_jobs')
        ->order_by('created_at', 'DESC')
        ->limit(1)
        ->get();
        // fetch single row
        $result_row = $result->row_array()['ids'];
        // free result from memory and 
        // set result variable to null
        $result = $result->free_result();
        // set defaults
        $inactive_jobs_sids = $cron_exec = $cron_exec_removed = false;
        // check if the result row is null
        if($result_row != 'NULL' || $result_row != ''):
            // get inactive jobs where ids match
            $inactive_jobs_sids = $this->get_jobs_for_google_hire(explode(',',$result_row));
            _e($inactive_jobs_sids, true);
            // send de-activated jobs request
             // if(sizeof($inactive_jobs_sids)) $cron_exec_removed = $this->google_hire_api($inactive_jobs_sids, 'remove'); /*Hassan comment area*/
        endif;
        // send all active jobs request
        $active_jobs = $this->get_jobs_for_google_hire();
        $active_jobs_string = $this->multi_single_array($active_jobs, 'sid', ',');
        // check if active jobs are available
        if(!sizeof($active_jobs)): 
            $active_jobs_string = 'NULL';
        else: 
            $cron_exec = $this->google_hire_api($active_jobs, 'add'); /*Hassan Comment area*/
        endif;
        _e($active_jobs, true, true);
        die;
        // insert record into database
        $exec = $this->db->insert('cron_google_hire_jobs', array(
            'ids' => $active_jobs_string,
            'created_at' => date('Y-m-d H:i:s')
        ));
        // for test purpose only
        if($this->is_test):
            //
            _e('Removed Jobs id', true);
            _e(implode(',', $inactive_jobs_sids));

            _e('Removed Cron', true);
            _e($cron_exec_removed);

            _e('Insert Cron', true);
            _e($cron_exec);

            _e('Active Job ids', true);
            _e( $active_jobs);

            _e('Record inserted', true);
            _e((int)$exec);
        else:
            _e('Worked.....', true);
        endif;
    }


    private function new_index(){
        // Get the access token from Google
        $this->authorise();
        // Terminate execution if no access token found
        if(!$this->access_token) exit(0);
        // Fetch all active jobs
        $active_jobs = $this->getIndeedOrganicJob();
        //
        if(!sizeof($active_jobs)) return;
        $this->google_hire_api($active_jobs, 'add');
        //
        mail(TO_EMAIL_DEV, 'Google Job API - Triggered: ' . date('Y-m-d H:i:s'), print_r($active_jobs, true));
        //
        exit(0);
    }

    /**
     * Get access token
     *
     * @return Resource
     */
    private function authorise(){
        // Set credenstials file name
        $cred_filename = 'google_hire_applybuz.json';
        if(!preg_match('/applybuz|localhost/', base_url()))
            $cred_filename = 'google_hire.json';

        // Loads configutaion file
        $auth = @json_decode(@file_get_contents(ROOTPATH.'../protected_files/'.$cred_filename));

        // Generate JWT token
        $this->load->library('Mjwt');
        // $jwt = new MJWT();
        $mjwt = $this->mjwt;
        //
        $assertion = $mjwt::encode(array(
                "iss" => $auth->client_email,
                "scope" => "https://www.googleapis.com/auth/indexing",
                "aud" => $this->auth_url,
                "exp" => strtotime("+30 minutes"),
                "iat" => strtotime("now")
            ),
            $auth->private_key,
            $this->hash_type,
            $auth->private_key_id
        );
        //
        $grant_type = urlencode('urn:ietf:params:oauth:grant-type:jwt-bearer');
        $post = "grant_type=$grant_type&assertion=$assertion";
        //
        $curl_auth = curl_init($this->auth_url);
        curl_setopt_array(
            $curl_auth, 
            array(
                CURLOPT_POST => strlen($post),
                CURLOPT_POSTFIELDS => urldecode(utf8_encode($post)),
                CURLOPT_RETURNTRANSFER  => 1,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                )
            )
        );

        $result = curl_exec($curl_auth);
        curl_close($curl_auth);

        $result = @json_decode($result, true);

        // _e($result, true);
        if(!isset($result['access_token'])) exit(0);
        $this->access_token = $result['access_token'];

        // $curl2 = curl_init("https://www.googleapis.com/oauth2/v1/tokeninfo?access_token={$this->access_token}");
        // _e(curl_exec($curl2), true, true);
        // curl_close($curl2);

        return $this;
    }


	/**
	 * hit google index api
	 *
	 * @param $jobs Array
	 * @param $type String Optional 
	 * @param $is_test Bool Optional
	 *
	 * @return Array
	 */
	private function google_hire_api($jobs, $type='add'){
        if(!sizeof($jobs)) return false;
        $max = 1;
        foreach ($jobs as $k0 => $v0) {
            if($v0['sub_domain'] == '') continue;
            // TODO
            // dns record verification 
            // confirm subdomain links
            $response = $this->make_request(
                "https://indexing.googleapis.com/v3/urlNotifications:".( $type == 'add' ? 'publish' : 'unpublish' )."",
                array("url"=>'https://'.$v0['sub_domain'].'/job_details/'.$v0['sid'], "type" => ($type == 'add' ? 'URL_UPDATED' : 'URL_DELETED'))
            );
            $this->google_hire->addProccessedId($v0['sid']);
            sleep(2);
            // _e($response, true, true);
        }
    }


    /**
     * Make a curl request
     *
     * @param $url String 
     * @param $post Array
     *
     * @return Array
     */
    private function make_request($url, $post){
        $post = @json_encode($post);
        //
        $curl = curl_init();

        curl_setopt_array(
            $curl, 
            array(
                CURLOPT_URL => $url,
                CURLOPT_POST => 1,
                // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                // CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => $post,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                // CURLOPT_HEADER => 1,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "content-length: ".strlen($post)."",
                    "Authorization: Bearer {$this->access_token}",
                    "Host: indexing.googleapis.com"
                )
            )
        );

        $result = curl_exec($curl);

        return $result;

        return @json_decode($result_array, true);
    }

	/**
	 * get records from database
	 *
	 * @param $is_active Bool|Array Optional
	 * @param $platform String (Default|Indeed|Zip)
	 * @param $is_organic Bool Optional
	 *
	 * @return Array
	 */
	private function get_jobs_for_google_hire( $is_active = 1, $platform = 'default', $is_organic = TRUE ){
        
        if(is_array($is_active)):
        	$inactive_sids_array = $is_active;
        	$is_active = 0;
        endif;

        $default_status = "pending,approved,rejected";
        // check for organic or platform base jobs
        if($platform != 'default' && $is_organic):
            $sids_from_jobs_to_feed =$this->db
            ->select("portal_job_listings.sid")
            ->from("jobs_to_feed")
            ->join("portal_job_listings", "portal_job_listings.sid = jobs_to_feed.job_sid", 'inner')
            ->where("portal_job_listings.active", $is_active)
            ->where("jobs_to_feed.expiry_date > ", date('Y-m-d H:i:s'))
            // ->where_in("jobs_to_feed.product_sid", $platform == 'indeed' ? '1' : '3,4' )
            ->where_in("jobs_to_feed.product_sid", $platform == 'indeed' ? array(1,21) : array(3,4) )
            ->get_compiled_select();
        endif;

        // check user_sid in companies sid
        $this->db
        ->select("sid")
        ->from("users")
        ->where("parent_sid", 0)
        ->where("is_paid", 1)
        ->where("career_site_listings_only", 0)
        ->where("active", 1);
        if($is_active):
        	$this->db->where("(expiry_date > '2016-04-20 13:26:27' OR expiry_date IS NULL)", NULL);
        endif;
        $user_sids_in_users =$this->db->get_compiled_select();

        _e($user_sids_in_users, true, true);

        // for active companies
        if($is_active):
            $sb3 =$this->db
            ->select("users.has_applicant_approval_rights")
            ->from("users")
            ->where("users.sid = portal_job_listings.user_sid", NULL)
            ->get_compiled_select();
        endif;

        // Main Query
       	$this->db
        ->select('portal_job_listings.sid, portal_employer.sub_domain')
        ->from('portal_job_listings')
        ->join('portal_employer', 'portal_employer.user_sid = portal_job_listings.user_sid', 'inner')
        ->where('portal_job_listings.active', $is_active)
        ->where('portal_job_listings.organic_feed', (int)$is_organic);
        if($is_active):
           $this->db->where("FIND_IN_SET(portal_job_listings.status, IF((".($sb3).") = 1, 'approved', '".($default_status)."'))", NULL);
        endif;
        if($platform != 'default' && $is_organic):
        	$this->db->where_not_in("portal_job_listings.sid",  $sids_from_jobs_to_feed, false);
        endif;
        $this->db
        ->where_in("portal_job_listings.user_sid",  $user_sids_in_users, false);
        // check for in
        if(isset($inactive_sids_array)):
        	$this->db->where_in("portal_job_listings.sid",  $inactive_sids_array);
    	endif;
        $this->db->order_by("portal_job_listings.sid",  'DESC');

        $result = $this->db->get();
        $result_data = $result->result_array();
        $result->free_result();
        return $result_data;
    }

    /**
     * Get Indeed organic jobs from database
     *
     * @return Array
     */
    private function getIndeedOrganicJob(){
        // Get last proccessed ids
        $ids = $this->google_hire->getLastProcessedIds(0);
        // _e($ids, true, true);
        $purchasedJobs = $this->google_hire->get_all_company_jobs_indeed();
        $i = 0;
        $featuredArray[$i] = "";
        
        foreach ($purchasedJobs as $purchased) {
            $featuredArray[$i] = $purchased['sid'];
            $i++;
        }
        
        $jobData = $this->google_hire->get_all_company_jobs_indeed_organic($featuredArray);       
        $activeCompaniesArray = $this->google_hire->get_all_active_companies();
        $listedJobArray = array();
        //
        $jobsArray = array();
        $maxRecords = 200;
        $curRecord = 0;

        foreach ($jobData as $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                $company_id = $job['user_sid'];
                $companyPortal = $this->google_hire->get_portal_detail($company_id);
                
                if(empty($companyPortal)) {
                    continue;
                }
                
                $companydata = $this->google_hire->get_company_name_and_job_approval($company_id);
                $companyName = $companydata['CompanyName'];
                $has_job_approval_rights = $companydata['has_job_approval_rights'];
                                
                if($has_job_approval_rights ==  1) {
                    $approval_right_status = $job['approval_status'];
                    
                    if($approval_right_status != 'approved') {
                        continue;
                    }
                }
                
                $uid = $job['sid'];
                $feed_data = $this->google_hire->fetch_uid_from_job_sid($uid);
                
                if(!empty($feed_data)){
                    $uid = $feed_data['uid'];
                }

                if(in_array($uid, $ids)) continue;
                else{
                    if($curRecord >= $maxRecords) break;
                    $curRecord++;                    
                }
         
                $jobsArray[] = array('sid' => $uid, 'sub_domain' => $companydata['sub_domain']);
            }
        }
        //

        return $jobsArray;
    }

	/**
	 * Convert multi array to single array
	 *
	 * @param $array Array
	 * @param $key String Optional
	 * @param $implode Bool Optional
	 *
	 * @return String|Array
	 */
	private function multi_single_array($array, $key = 'id', $implode = FALSE){
		if(!sizeof($array)) return $array;
		$return_array = array();
		// loop through array
		foreach ($array as $k0 => $v0):
			if(isset($v0[$key])) $return_array[] = $v0[$key];
		endforeach;
		// check for implode and return
		return $implode ? implode($implode, $return_array) : $return_array;
	}    

}