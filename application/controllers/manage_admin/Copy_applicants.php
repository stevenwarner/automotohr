<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Copy_applicants extends Admin_Controller {

    private $limit = 10;
    private $applicantLimit = 10;
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/copy_applicants_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    /** This index function is replicate now **/
    public function index() {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, 'manage_admin', 'copy_applicants');
        $this->data['page_title'] = 'Copy Applicants To Another Company Account';
        $active_companies = $this->copy_applicants_model->get_all_companies();
        $this->data['active_companies'] = $active_companies;
        $applicants_type = array(   0 => 'Active Applicants',
                                    1 => 'Archived Applicants',
                                    2 => 'All Applicants');
        $this->data['applicants_type'] = $applicants_type;
        $this->form_validation->set_rules('copy_from', 'Copy From', 'trim|xss_clean|required|numeric');
        $this->form_validation->set_rules('applicants_type', 'Applicants Type', 'trim|xss_clean|required|numeric');
        $this->form_validation->set_rules('copy_to', 'Copy To', 'trim|xss_clean|required|numeric');

        $this->data['source'] = '';
        $this->data['destination'] = '';
        $this->data['type'] = '';

        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/company/copy_applicants_main', 'admin_master');
        } else {
            // Added on 20-05-2019
            $this->applicant_copy_process();
            // die('asdas');
            // $applicants = $this->copy_applicants_model->get_all_applicants($_POST['copy_from'], $_POST['applicants_type'], $_POST['copy_to']);

            // if (sizeof($applicants) > 0) {
            //     $copied = 0;

            //     foreach ($applicants as $applicant) {
            //         $applicant['source_company_name'] = $this->copy_applicants_model->get_company_data($applicant['source_company_sid']);

            //         if (isset($applicant['targeted_company_sid'])) {
            //             $copied++;
            //             $applicant['targeted_company_name'] = $this->copy_applicants_model->get_company_data($applicant['targeted_company_sid']);
            //         } else {
            //             $applicant['reason'] = "Applicant Already Exist";
            //         }

            //         $applicant['created_date'] = date('Y-m-d H:i:s');
            //         $this->copy_applicants_model->insert_copied_record($applicant);
            //     }

            //     $remaining = sizeof($applicants) - $copied;
            //     $this->session->set_flashdata('message', '<b>Success: </b> ' . $copied . ' Applicants Copied Successfully Out Of ' . sizeof($applicants) . '! ' . $remaining . ' Already exists');
            // } else {
            //     $applicant['status'] = 0;
            //     $applicant['reason'] = 'No Applicants Found';
            //     $applicant['created_date'] = date('Y-m-d H:i:s');
            //     $applicant['source_company_sid'] = $_POST['copy_from'];
            //     $applicant['source_company_name'] = $this->copy_applicants_model->get_company_data($_POST['copy_from']);
            //     $this->copy_applicants_model->insert_copied_record($applicant);
            //     $this->session->set_flashdata('message', '<b>Success:</b> No Applicants are available to copy!');
            // }

            // redirect(base_url('manage_admin/copy_applicants'), 'refresh');
        }
    }


    /**
     * Handles copy applicants
     * Created on: 21-08-2019
     *
     * @uses db_get_admin_access_level_details
     * @uses check_access_permissions
     *
     * @return VOID
     */
    public function index_new() {
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_applicants');
        $this->data['page_title'] = 'Copy Applicants To Another Company Account';
        $this->data['active_companies'] = $this->copy_applicants_model->get_all_companies();
        $this->render('manage_admin/company/copy_applicants_new', 'admin_master');
    }



    /**
     * Fetch jobs by company id
     * Created on: 21-08-2019
     *
     * @accepts POST
     * fromCompanyId
     * page
     * jobType
     * -1 = 'All'
     * 1 = 'Active'
     * 0 = 'InActive'
     *
     * @return JSON
     */
    public function fetch_jobs() {
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'copy_applicants');
        // Check foir ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);
        // Set return array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request';
        // Save post
        $formpost = $this->input->post(NULL, TRUE);
        // Check for required indexes
        if(!isset($formpost['fromCompanyId']) || !isset($formpost['jobType'])|| !isset($formpost['page'])){
            $resp['Response'] = 'Indexes are missing from request';
            $this->resp($resp);
        }
        //
        $jobs = $this->copy_applicants_model->fetchJobsByCompanyId_new($formpost['fromCompanyId'], $formpost['jobType'],$formpost['applicantKeyword'], $formpost['page'], $this->limit);
        if(!$jobs){
            $resp['Response'] = 'No jobs found.';
            $this->resp($resp);
        }
        // FWhen page is 1 send back total pages records
        $resp['Status'] = TRUE;
        $resp['Response'] = 'Proceed';
        if($formpost['page'] == 1){
            $resp['Limit'] = $this->limit;
            $resp['Records'] = $jobs['Jobs'];
            $resp['TotalPages'] = ceil( $jobs['JobCount'] / $this->limit );
            $resp['TotalRecords'] = $jobs['JobCount'];
        } else $resp['Records'] = $jobs;

        $this->resp($resp);
    }

//fetch_applicants_new added on 12/16/2019

    public function fetch_applicants_new() {
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'copy_applicants');
        // Check foir ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);
        // Set return array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request';
        // Save post
        $formpost = $this->input->post(NULL, TRUE);

        // Check for required indexes
        if(!isset($formpost['fromCompanyId']) || !isset($formpost['jobType'])|| !isset($formpost['page'])){
            $resp['Response'] = 'Indexes are missing from request';
            $this->resp($resp);
        }
        //
        $applicants = $this->copy_applicants_model->fetchApplicantsByCompanyId_new($formpost['fromCompanyId'], $formpost['jobType'],$formpost['applicantKeyword'], $formpost['page'], $this->limit);
        if(!$applicants){
            $resp['Response'] = 'No jobs found.';
            $this->resp($resp);
        }
        // FWhen page is 1 send back total pages records
        $resp['Status'] = TRUE;
        $resp['Response'] = 'Proceed';
        if($formpost['page'] == 1){
            $resp['Limit'] = $this->limit;
            $resp['Records'] = $applicants['Applicants'];
            $resp['TotalPages'] = ceil( $applicants['ApplicantsCount'] / $this->limit );
            $resp['TotalRecords'] = $applicants['ApplicantsCount'];
        } else $resp['Records'] = $applicants;

        $this->resp($resp);
    }

////////////////////
    /**
     * Fetch all applicants
     * Created on: 21-08-2019
     *
     * @accepts POST
     * 'fromCompanyId'
     * 'toCompanyId'
     * 'jobId'
     * 'archieved'
     * 'active'
     *
     * @return JSON
     */
    function fetch_applicants_by_job(){
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'copy_applicants');
        // Check foir ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);
        // Set return array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request';
        // Save post
        $formpost = $this->input->post(NULL, TRUE);
        // Check for required indexes
        if(!isset($formpost['fromCompanyId']) || !isset($formpost['toCompanyId']) || !isset($formpost['jobId']) || !isset($formpost['archieved']) || !isset($formpost['active'])){
            $resp['Response'] = 'Indexes are missing from request';
            $this->resp($resp);
        }
        //
        $applicants = $this->copy_applicants_model->fetchApplicantCountByJobId(
            $formpost['fromCompanyId'],
            $formpost['jobId'],
            $formpost['archieved'],
            $formpost['active']
        );
        if(!$applicants){
            $resp['Response'] = 'No applicants found.';
            $this->resp($resp);
        }
        // When page is 1 send back total pages records
        $resp['Status'] = TRUE;
        $resp['Response'] = 'Proceed';
        $resp['Limit'] = $this->applicantLimit;
        $resp['TotalPages'] = ceil( $applicants / $this->applicantLimit );
        $resp['TotalRecords'] = $applicants;

        $this->resp($resp);
    }
    /**
     * Copy job with applicants
     * Created on: 21-08-2019
     *
     * @accepts POST
     * 'fromCompanyId'
     * 'toCompanyId'
     * 'jobId'
     * 'archieved'
     * 'active'
     *
     *
     * @return JSON
     */

    function copy_job_with_applicants(){
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'copy_applicants');
        // Check foir ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);
        // Set return array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request';
        // Save post
        $formpost = $this->input->post(NULL, TRUE);
        // Check for required indexes
        if(!isset($formpost['fromCompanyId']) || !isset($formpost['toCompanyId']) || !isset($formpost['jobId']) || !isset($formpost['archieved']) || !isset($formpost['active'])){
            $resp['Response'] = 'Indexes are missing from request';
            $this->resp($resp);
        }
        //
        $copiedApplicants = $existedApplicants = $failedApplicants = 0;
        //
        $applicants = $this->copy_applicants_model->fetchApplicantByJobId(
            $formpost['fromCompanyId'],
            $formpost['jobId'],
            $formpost['archieved'],
            $formpost['active'],
            $formpost['page']
        );
        //
        if(!$applicants){
            $resp['Response'] = 'No applicants found.';
            $this->resp($resp);
        }
        $j = 0;
        $date = date('Y-m-d H:i:s', strtotime('now'));
        $source_company_sid = $formpost['fromCompanyId'];
        $target_company_sid = $formpost['toCompanyId'];
        // Loop through records
        foreach ($applicants as $applicant) {
            $copy_applicants        = array();
            $new_applicant_sid      = '';
            $new_job_listing_sid    = '';
            $old_applicant_sid      = $applicant['sid'];
            //Keeping Record Of Old Applicant Data
            $copied_applicant[$j]['status'] = 0;
            $copied_applicant[$j]['created_date'] = $date;
            $copied_applicant[$j]['source_applicant_sid'] = $old_applicant_sid;
            $copied_applicant[$j]['source_company_sid']   = $source_company_sid;
            // If applicant already exists
            if ($this->copy_applicants_model->check_applicant($applicant['email'], $target_company_sid)) { $existedApplicants++; continue;}
            //
            unset($applicant['sid']);
            $applicant['employer_sid'] = $target_company_sid;
            $new_applicant_sid = $this->copy_applicants_model->_insert('portal_job_applications', $applicant);
            //Keeping Record Of Successful Copied
            $copied_applicant[$j]['status']                 = 1;
            $copied_applicant[$j]['targeted_applicant_sid'] = $new_applicant_sid;
            $copied_applicant[$j]['targeted_company_sid']   = $target_company_sid;
            $job = $this->copy_applicants_model->getJobById($old_applicant_sid);
            // Add job records
            if (sizeof($job)) {
                unset($job['sid'], $job['employer_sid']);
                $status = $this->copy_applicants_model->get_new_company_status($target_company_sid);

                $job['status_sid']                  = $status['sid'];
                $job['status']                      = $status['name'];
                $job['company_sid']                 = $target_company_sid;
                $job['portal_job_applications_sid'] = $new_applicant_sid;
                // $job['date_applied'] = date('Y-m-d H:i:s', strtotime('now'));

                if ($job['job_sid'] != 0) {
                    $job['desired_job_title'] = $this->copy_applicants_model->get_job_title($job['job_sid']);
                    $job['job_sid'] = 0;
                }

                $new_job_listing_sid = $this->copy_applicants_model->_insert('portal_applicant_jobs_list', $job);

//                $target_company_ems = $this->copy_applicants_model->check_ems_status($target_company_sid);

                if ($applicant['is_onboarding'] == 1) {
                    $onboarding_data = array();
                    $onboarding_data['company_sid'] = $target_company_sid;
                    $onboarding_data['employer_sid'] = $this->ion_auth->user()->row()->id;
                    $onboarding_data['applicant_sid'] = $new_applicant_sid;
                    $onboarding_data['job_list_sid'] = $new_job_listing_sid;
                    $onboarding_data['unique_sid'] = random_key(80);
                    $onboarding_data['onboarding_start_date'] = date('Y-m-d H:i:s');
                    $onboarding_data['onboarding_status'] = 'in_process';
                    $onboarding_data['job_sid'] = 0;

//                    $this->copy_applicants_model->mark_applicant_for_onboarding($new_applicant_sid);
                    $this->copy_applicants_model->save_onboarding_applicant($onboarding_data);
                }
//                else {
//                    $this->copy_applicants_model->un_mark_applicant_for_onboarding($new_applicant_sid);
//                }

//                if($target_company_ems == 1){     //Like mubashir bhai said, do this but comment this.If targeted company's ems is off then unmark it from onboarding
//                    $this->copy_applicants_model->un_mark_applicant_for_onboarding($new_applicant_sid);
//                }

            }
            // Copy Emergency Contacts
            $this->copy_applicants_model->copy_applicant_emergency_contacts($old_applicant_sid, $new_applicant_sid);
            // Copy Applicant Equipment Information
            $this->copy_applicants_model->copy_applicant_equipment_information($old_applicant_sid, $new_applicant_sid);
            // Copy Applicant Dependent Information
            $this->copy_applicants_model->copy_applicant_dependant_information($old_applicant_sid, $new_applicant_sid, $target_company_sid);
            // Copy Applicant License Information
            $this->copy_applicants_model->copy_applicant_license_information($old_applicant_sid, $new_applicant_sid);
            // Copy Applicant Reference Check
            $this->copy_applicants_model->copy_reference_checks($old_applicant_sid, $new_applicant_sid, $target_company_sid);

            // Save record in database
            $copied_applicant[$j]['source_company_name'] = $this->copy_applicants_model->get_company_data($source_company_sid);

            if (isset($copied_applicant[$j]['targeted_company_sid'])) {
                $copied_applicant[$j]['targeted_company_name'] = $this->copy_applicants_model->get_company_data($copied_applicant[$j]['targeted_company_sid']);
            } else
                $copied_applicant[$j]['reason'] = "Applicant Already Exist";

            $copied_applicant[$j]['created_date'] = $date;
            $copied_applicant[$j]['insert_id'] = $this->copy_applicants_model->_insert('applicant_copied_by_admin', $copied_applicant[$j]);

            $j++;
            $copiedApplicants++;
        }

        $resp['Status'] = TRUE;
        $resp['Response'] = 'Proceed';
        $resp['existedApplicants'] = $existedApplicants;
        $resp['failedApplicants'] = $failedApplicants;
        $resp['copiedApplicants'] = $copiedApplicants;
        $this->resp($resp);
    }

    ////move_applicants_new added on 12/18/2019
    function move_applicants_new(){
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'copy_applicants');
        // Check foir ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);
        // Set return array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request';
        // Save post
        $formpost = $this->input->post(NULL, TRUE);
        // Check for required indexes
        if(!isset($formpost['sid']) || !isset($formpost['applicantId']) || !isset($formpost['jobTitle']) || !isset($formpost['applicant_name']) || !isset($formpost['applicant_email'])|| !isset($formpost['job_id'])|| !isset($formpost['JobStatus'])){
            $resp['Response'] = 'Indexes are missing from request';
            $this->resp($resp);
        }
        //
        //

        $resp['Status'] = TRUE;
        $resp['Response'] = 'Proceed';
        $resp['existedApplicants'] =0;
        $resp['failedApplicants'] =0;
        $resp['copiedApplicants'] =0;
        // $this->resp($resp);
        $applicant=$this->copy_applicants_model->get_applicant_data(
         $formpost['applicantId']
        );

        $j = 0;
        $date = date('Y-m-d H:i:s', strtotime('now'));
        $source_company_sid = $formpost['fromCompanyId'];
        $target_company_sid = $formpost['toCompanyId'];

            $copy_applicants = array();
            $old_applicant_sid = $applicant['sid'];
            //Keeping Record Of Old Applicant Data
            $copied_applicant[$j]['status'] = 0;
            $copied_applicant[$j]['created_date'] = $date;
            $copied_applicant[$j]['source_applicant_sid'] = $old_applicant_sid;
            $copied_applicant[$j]['source_company_sid']   = $source_company_sid;
            // If applicant already exists
            if ($this->copy_applicants_model->check_applicant($applicant['email'], $target_company_sid)) { $resp['existedApplicants']=1; $this->resp($resp);}
            //
            unset($applicant['sid']);
            $applicant['employer_sid'] = $target_company_sid;
            $new_applicant_sid = $this->copy_applicants_model->_insert('portal_job_applications', $applicant);
            if(sizeof($new_applicant_sid)==0)
            {
                $resp['failedApplicants']=1;
                $this->resp($resp);
            }
            //Keeping Record Of Successful Copied
            $copied_applicant[$j]['targeted_applicant_sid'] = $new_applicant_sid;
            $copied_applicant[$j]['targeted_company_sid'] = $target_company_sid;
            $copied_applicant[$j]['status'] = 1;
            $job = $this->copy_applicants_model->getJobById($old_applicant_sid);


            // Add job records
            if (sizeof($job)) {
                unset($job['sid'], $job['employer_sid']);
                $job['company_sid'] = $target_company_sid;
                $job['portal_job_applications_sid'] = $new_applicant_sid;
                $status = $this->copy_applicants_model->get_new_company_status($target_company_sid);
                $job['status_sid'] = $status['sid'];
                $job['status'] = $status['name'];
                // $job['date_applied'] = date('Y-m-d H:i:s', strtotime('now'));

                if ($job['job_sid'] != 0) {
                    $job['desired_job_title'] = $this->copy_applicants_model->get_job_title($job['job_sid']);
                    $job['job_sid'] = 0;
                }
                $new_job_listing_sid = $this->copy_applicants_model->_insert('portal_applicant_jobs_list', $job);


                //                $target_company_ems = $this->copy_applicants_model->check_ems_status($target_company_sid);

                if ($applicant['is_onboarding'] == 1) {
                    $onboarding_data = array();
                    $onboarding_data['company_sid'] = $target_company_sid;
                    $onboarding_data['employer_sid'] = $this->ion_auth->user()->row()->id;
                    $onboarding_data['applicant_sid'] = $new_applicant_sid;
                    $onboarding_data['job_list_sid'] = $new_job_listing_sid;
                    $onboarding_data['unique_sid'] = random_key(80);
                    $onboarding_data['onboarding_start_date'] = date('Y-m-d H:i:s');
                    $onboarding_data['onboarding_status'] = 'in_process';
                    $onboarding_data['job_sid'] = 0;

                    //                    $this->copy_applicants_model->mark_applicant_for_onboarding($new_applicant_sid);
                    $this->copy_applicants_model->save_onboarding_applicant($onboarding_data);
                }
                //                else {
                //                    $this->copy_applicants_model->un_mark_applicant_for_onboarding($new_applicant_sid);
                //                }

                //                if($target_company_ems == 1){     //As mubashir bhai said, do this but comment this.If targeted company's ems is off then unmark it from onboarding
                //                    $this->copy_applicants_model->un_mark_applicant_for_onboarding($new_applicant_sid);
                //                }
            }
            // Copy Emergency Contacts
            $this->copy_applicants_model->copy_applicant_emergency_contacts($old_applicant_sid, $new_applicant_sid);
            // Copy Applicant Equipment Information
            $this->copy_applicants_model->copy_applicant_equipment_information($old_applicant_sid, $new_applicant_sid);
            // Copy Applicant Dependent Information
            $this->copy_applicants_model->copy_applicant_dependant_information($old_applicant_sid, $new_applicant_sid, $target_company_sid);
            // Copy Applicant License Information
            $this->copy_applicants_model->copy_applicant_license_information($old_applicant_sid, $new_applicant_sid);
            // Copy Applicant Reference Check
            $this->copy_applicants_model->copy_reference_checks($old_applicant_sid, $new_applicant_sid, $target_company_sid);

            // Save record in database
            $copied_applicant[$j]['source_company_name'] = $this->copy_applicants_model->get_company_data($source_company_sid);

            if (isset($copied_applicant[$j]['targeted_company_sid'])) {
                $copied_applicant[$j]['targeted_company_name'] = $this->copy_applicants_model->get_company_data($copied_applicant[$j]['targeted_company_sid']);
            } else
                $copied_applicant[$j]['reason'] = "Applicant Already Exist";

            $copied_applicant[$j]['created_date'] = $date;
            $copied_applicant[$j]['insert_id'] = $this->copy_applicants_model->_insert('applicant_copied_by_admin', $copied_applicant[$j]);

            $copied_applicant[$j];
            $resp['copiedApplicants']=1;
            $this->resp($resp);


    }

    /**
     * Revert job with apllicants
     * Created on: 22-08-2019
     *
     * @accepts POST
     * jobId
     * toCompanyId
     *
     * @return VOID
     */
    function revoke_job(){
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'copy_applicants');
        // Check foir ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);

        $formpost = $this->input->post(NULL, TRUE);
        // _e($formpost, true);
        $this->copy_applicants_model->revert($formpost['jobId'], $formpost['toCompanyId']);
    }

    /**
     * Track job
     * Created on: 22-08-2019
     *
     * @accepts POST
     * jobId
     * jobTitle
     * toCompanyId
     * fromCompanyId
     * copiedApplicants
     * existedApplicants
     * failedApplicants
     *
     * @return VOID
     */
    function track_job(){
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'copy_applicants');
        // Check foir ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);
        //
        $formpost = $this->input->post(NULL, TRUE);
        //
        $this->copy_applicants_model->_insert(
            'copy_applicant_tracking',
            array(
                'token' => $formpost['token'],
                'job_sid' => $formpost['jobId'],
                'job_title' => $formpost['jobTitle'],
                'admin_sid' => $this->ion_auth->user()->row()->id,
                'to_company_id' => $formpost['toCompanyId'],
                'from_company_id' => $formpost['fromCompanyId'],
                'failed_applicants' => $formpost['failedApplicants'],
                'copied_applicants' => $formpost['copiedApplicants'],
                'existed_applicants' => $formpost['existedApplicants']
            )
        );
    }




    public function fetch_applicants($source_id, $app_type, $dest_id = NULL, $job_id = NULL) {
        $this->data['page_title'] = 'Copy Applicants to another Company account';
        $active_companies = $this->copy_applicants_model->get_all_companies();
        $this->data['active_companies'] = $active_companies;

        $applicants_type = array(   0 => 'Active Applicants',
                                    1 => 'Archived Applicants',
                                    2 => 'All Applicants');


        $this->data['applicants_type'] = $applicants_type;
        $this->data['source'] = $source_id;
        $this->data['destination'] = $dest_id;
        $this->data['type'] = $app_type;

       // if($job_id==NULL){
       //     $this->data['applicants'] = $this->copy_applicants_model->get_company_applicants($source_id,$app_type);
       // }else{
       //     $this->data['applicants'] = $this->copy_applicants_model->get_company_with_job($source_id,$job_id);
       // }

        if ($job_id == 1) {
            $this->data['applicants'] = $this->copy_applicants_model->get_company_applicants($source_id, $app_type);
        } else if ($job_id == NULL || $job_id == 0) {
            $this->data['applicants'] = array();
        } else {
            $this->data['applicants'] = $this->copy_applicants_model->get_company_with_job($source_id, $job_id);
        }

        $this->data['jobs'] = $this->copy_applicants_model->get_company_jobs($source_id);

        if (isset($_POST['form_action']) && $_POST['form_action'] == 'copy_selected') {
            $_POST['copy_from'] = $source_id;
            $_POST['applicants_type'] = $app_type;
            $this->applicant_copy_process();
            // $selected_ids = $this->input->post('checkit');
            // $destination_id = $this->input->post('copy_to');
            // $applicants = $this->copy_applicants_model->copy_selected($source_id, $selected_ids, $destination_id);

            // if (sizeof($applicants) > 0) {
            //     $copied = 0;

            //     foreach ($applicants as $applicant) {
            //         $applicant['source_company_name'] = $this->copy_applicants_model->get_company_data($applicant['source_company_sid']);

            //         if (isset($applicant['targeted_company_sid'])) {
            //             $copied++;
            //             $applicant['targeted_company_name'] = $this->copy_applicants_model->get_company_data($applicant['targeted_company_sid']);
            //         } else {
            //             $applicant['reason'] = "Applicant Already Exist";
            //         }

            //         $applicant['created_date'] = date('Y-m-d H:i:s');
            //         $this->copy_applicants_model->insert_copied_record($applicant);
            //     }

            //     $remaining = sizeof($applicants) - $copied;
            //     $this->session->set_flashdata('message', '<b>Success: </b> ' . $copied . ' Applicants Copied Successfully Out Of ' . sizeof($applicants) . '! ' . $remaining . ' Already exists');
            // } else {
            //     $applicant['status'] = 0;
            //     $applicant['reason'] = 'No Applicants Found';
            //     $applicant['created_date'] = date('Y-m-d H:i:s');
            //     $applicant['source_company_sid'] = $source_id;
            //     $applicant['source_company_name'] = $this->copy_applicants_model->get_company_data($source_id);
            //     $this->copy_applicants_model->insert_copied_record($applicant);
            //     $this->session->set_flashdata('message', '<b>Success:</b> No Applicants are available to copy!');
            // }

            // redirect(base_url('manage_admin/copy_applicants/fetch_applicants/' . $source_id . '/' . $app_type . '/' . $dest_id), 'refresh');
        }

        $this->render('manage_admin/company/copy_applicants_main', 'admin_master');
    }

    /**
     * Send response
     *
     * @param $array Array
     *
     * @return JSON
     */
    private function resp($array){
        header('content-type: application/json');
        echo json_encode($array);
        exit(0);
    }

    /**
     * This function is Replicate now and its call from replicate index function.
     * Copy applicant from one company to another
     * Created on: 20-05-2019
     *
     * @accepts POST
     *
     * @return VOID
     **/
    private function applicant_copy_process(){
        $post_data = $this->input->post(NULL, TRUE);
        $ids_list = array();
        if(isset( $post_data['checkit'] )) $ids_list = $post_data['checkit'];
        //
        $page = 0;
        $source_company_sid = $post_data['copy_from'];
        $target_company_sid = $post_data['copy_to'];

        $results = $this->copy_applicants_model->get_all_applicants_new(
            $post_data['copy_from'],
            $post_data['applicants_type'],
            $page,
            $this->limit,
            true,
            $ids_list
        );

        if(!$results) {
            $this->session->set_flashdata('message', '<b>Error: </b> No Applicant(s) are available to copy!');
            redirect(base_url('manage_admin/copy_applicants'), 'refresh');
        }

        $chunks = ceil($results['TotalApplicants'] / $this->limit);
        //
        $copied_applicant = array();
        $date = date('Y-m-d H:i:s');
        $j = $copied = 0;
        // Start recording transaction
        $this->copy_applicants_model->trans('trans_begin');
        // Loop through chunks
        for($i = 0; $i < $chunks; $i++) {
            $page++;
            // get chunk
            $applicants = $this->copy_applicants_model->get_all_applicants_new(
                $post_data['copy_from'],
                $post_data['applicants_type'],
                $page,
                $this->limit,
                false,
                $ids_list
            );
            //
            if(!$applicants) continue;
            // Loop through records
            foreach ($applicants as $applicant) {
                $copy_applicants = array();
                $old_applicant_sid = $applicant['sid'];
                //Keeping Record Of Old Applicant Data
                $copied_applicant[$j]['status'] = 0;
                $copied_applicant[$j]['created_date'] = $date;
                $copied_applicant[$j]['source_applicant_sid'] = $old_applicant_sid;
                $copied_applicant[$j]['source_company_sid']   = $source_company_sid;
                // If applicant already exists
                if ($this->copy_applicants_model->check_applicant($applicant['email'], $target_company_sid)) continue;
                //
                unset($applicant['sid']);
                $applicant['employer_sid'] = $target_company_sid;
                //
                $new_applicant_sid = $this->copy_applicants_model->_insert('portal_job_applications', $applicant);
                //Keeping Record Of Successful Copied
                $copied_applicant[$j]['targeted_applicant_sid'] = $new_applicant_sid;
                $copied_applicant[$j]['targeted_company_sid'] = $target_company_sid;
                $copied_applicant[$j]['status'] = 1;
                $job_list = $this->copy_applicants_model->get_applicant_job_list($old_applicant_sid);
                // Add job records
                if (sizeof($job_list)) {
                    foreach ($job_list as $job) {
                        unset($job['sid']);
                        $job['company_sid'] = $target_company_sid;
                        $job['portal_job_applications_sid'] = $new_applicant_sid;
                        $status = $this->copy_applicants_model->get_new_company_status($target_company_sid);
                        $job['status_sid'] = $status['sid'];
                        $job['status'] = $status['name'];

                        if ($job['job_sid'] != 0) {
                            $job['desired_job_title'] = $this->copy_applicants_model->get_job_title($job['job_sid']);
                            $job['job_sid'] = 0;
                        }

                        $this->copy_applicants_model->_insert('portal_applicant_jobs_list', $job);
                    }
                }
                // Copy Emergency Contacts
                $this->copy_applicants_model->get_applicant_emergency_contacts($old_applicant_sid, $new_applicant_sid);
                // Copy Applicant Equipment Information
                $this->copy_applicants_model->get_applicant_equipment_information($old_applicant_sid, $new_applicant_sid);
                // Copy Applicant Dependent Information
                $this->copy_applicants_model->get_applicant_dependant_information($old_applicant_sid, $new_applicant_sid, $target_company_sid);
                // Copy Applicant License Information
                $this->copy_applicants_model->get_applicant_license_information($old_applicant_sid, $new_applicant_sid);
                // Copy Applicant Reference Check
                $this->copy_applicants_model->get_reference_checks($old_applicant_sid, $new_applicant_sid, $target_company_sid);

                // Save record in database
                $copied_applicant[$j]['source_company_name'] = $this->copy_applicants_model->get_company_data($source_company_sid);

                if (isset($copied_applicant[$j]['targeted_company_sid'])) {
                    $copied++;
                    $copied_applicant[$j]['targeted_company_name'] = $this->copy_applicants_model->get_company_data($copied_applicant[$j]['targeted_company_sid']);
                } else
                    $copied_applicant[$j]['reason'] = "Applicant Already Exist";

                $copied_applicant[$j]['created_date'] = $date;
                $copied_applicant[$j]['insert_id'] = $this->copy_applicants_model->_insert('applicant_copied_by_admin', $copied_applicant[$j]);

                $j++;
            }
        }

        //
        $this->copy_applicants_model->trans(
            $this->copy_applicants_model->trans('trans_status') === FALSE ? 'trans_rollback' : 'trans_commit'
        );

        if($copied != 0){
            $remaining = $results['TotalApplicants'] - $copied;
            $this->session->set_flashdata('message', '<b>Success: </b> ' . $copied . ' Applicants Copied Successfully Out Of ' . $results['TotalApplicants'] . '! ' . $remaining . ' Already exists');
        }else{
            $applicant = array();
            $applicant['status'] = 0;
            $applicant['reason'] = 'No Applicants Found';
            $applicant['created_date'] = $date;
            $applicant['source_company_sid'] = $post_data['copy_from'];
            $applicant['source_company_name'] = $this->copy_applicants_model->get_company_data($post_data['copy_from']);
            $this->copy_applicants_model->insert_copied_record($applicant);
            $this->session->set_flashdata('message', '<b>Error:</b> No Applicant(s) are available to copy!');
        }

        redirect(base_url('manage_admin/copy_applicants'), 'refresh');

        _e('here', true, true);
    }



    /**
     * This function is Replicate now and its call from view "copy_applications_main" which is also replicate.
     * Copy applicant from one company to another
     * Created on: 20-05-2019
     *
     * @accepts POST
     *
     * @return VOID
     **/
    function move_applicants(){
        if(!$this->input->is_ajax_request()) redirect('/');
        if($this->input->method() != 'post') redirect('/');
        //
        $return_array = array();
        $return_array['Status'] = FALSE;
        $return_array['Response'] = 'Oops! invalid request made.';
        $post_data = $this->input->post(NULL, TRUE);
        if(!sizeof($post_data)) $this->resp($return_array);
        //
        $ids_list = array();
        if(isset( $post_data['checkit'] )) $ids_list = $post_data['checkit'];
        //
        $page = $post_data['page'];
        $source_company_sid = $post_data['copy_from'];
        $target_company_sid = $post_data['copy_to'];

        if(!isset($post_data['total'] )){
            $results = $this->copy_applicants_model->get_all_applicants_new(
                $post_data['copy_from'],
                $post_data['applicants_type'],
                1,
                $this->limit,
                true,
                $ids_list
            );

            $return_array['Status'] = true;
            $return_array['Response'] = 'Proceed.';
            $return_array['Total']  = $results['TotalApplicants'];
            $return_array['Limit']  = $this->limit;
            $return_array['Chunks']  =  ceil($results['TotalApplicants'] / $this->limit);
            $this->resp($return_array);
        }

        $copied_applicant = array();
        $date = date('Y-m-d H:i:s');
        $j = $copied = 0;
        // Start recording transaction
        $this->copy_applicants_model->trans('trans_begin');
        // get chunk
        $applicants = $this->copy_applicants_model->get_all_applicants_new(
            $post_data['copy_from'],
            $post_data['applicants_type'],
            $page,
            $this->limit,
            false,
            $ids_list
        );



        if(!$applicants) {
            $return_array['Response'] = 'No Applicants are avaiable to copy.';
            $this->resp($return_array);
        }
        // Loop through records
        foreach ($applicants as $applicant) {
            $copy_applicants = array();
            $old_applicant_sid = $applicant['sid'];
            //Keeping Record Of Old Applicant Data
            $copied_applicant[$j]['status'] = 0;
            $copied_applicant[$j]['created_date'] = $date;
            $copied_applicant[$j]['source_applicant_sid'] = $old_applicant_sid;
            $copied_applicant[$j]['source_company_sid']   = $source_company_sid;
            // If applicant already exists
            if ($this->copy_applicants_model->check_applicant($applicant['email'], $target_company_sid)) continue;
            //
            unset($applicant['sid']);
            $applicant['employer_sid'] = $target_company_sid;
            //
            $new_applicant_sid = $this->copy_applicants_model->_insert('portal_job_applications', $applicant);
            //Keeping Record Of Successful Copied
            $copied_applicant[$j]['targeted_applicant_sid'] = $new_applicant_sid;
            $copied_applicant[$j]['targeted_company_sid'] = $target_company_sid;
            $copied_applicant[$j]['status'] = 1;
            $job_list = $this->copy_applicants_model->get_applicant_job_list($old_applicant_sid);
            // Add job records
            if (sizeof($job_list)) {
                foreach ($job_list as $job) {
                    unset($job['sid']);
                    $job['company_sid'] = $target_company_sid;
                    $job['portal_job_applications_sid'] = $new_applicant_sid;
                    $status = $this->copy_applicants_model->get_new_company_status($target_company_sid);
                    $job['status_sid'] = $status['sid'];
                    $job['status'] = $status['name'];
                    // $job['date_applied'] = date('Y-m-d H:i:s', strtotime('now'));

                    if ($job['job_sid'] != 0) {
                        $job['desired_job_title'] = $this->copy_applicants_model->get_job_title($job['job_sid']);
                        $job['job_sid'] = 0;
                    }

                    $this->copy_applicants_model->_insert('portal_applicant_jobs_list', $job);
                }
            }
            // Copy Emergency Contacts
            $this->copy_applicants_model->copy_applicant_emergency_contacts($old_applicant_sid, $new_applicant_sid);
            // Copy Applicant Equipment Information
            $this->copy_applicants_model->copy_applicant_equipment_information($old_applicant_sid, $new_applicant_sid);
            // Copy Applicant Dependent Information
            $this->copy_applicants_model->copy_applicant_dependant_information($old_applicant_sid, $new_applicant_sid, $target_company_sid);
            // Copy Applicant License Information
            $this->copy_applicants_model->copy_applicant_license_information($old_applicant_sid, $new_applicant_sid);
            // Copy Applicant Reference Check
            $this->copy_applicants_model->copy_reference_checks($old_applicant_sid, $new_applicant_sid, $target_company_sid);

            // Save record in database
            $copied_applicant[$j]['source_company_name'] = $this->copy_applicants_model->get_company_data($source_company_sid);

            if (isset($copied_applicant[$j]['targeted_company_sid'])) {
                $copied++;
                $copied_applicant[$j]['targeted_company_name'] = $this->copy_applicants_model->get_company_data($copied_applicant[$j]['targeted_company_sid']);
            } else
                $copied_applicant[$j]['reason'] = "Applicant Already Exist";

            $copied_applicant[$j]['created_date'] = $date;
            $copied_applicant[$j]['insert_id'] = $this->copy_applicants_model->_insert('applicant_copied_by_admin', $copied_applicant[$j]);

            $j++;
        }

        //
        $this->copy_applicants_model->trans(
            $this->copy_applicants_model->trans('trans_status') === FALSE ? 'trans_rollback' : 'trans_commit'
        );

        if($copied != 0){
            $remaining = $post_data['total'] - $copied;
            $return_array['Status'] = TRUE;
            $return_array['Copied'] = $copied;
            $return_array['Response'] = '<b>Success:</b> copied!';
            // $this->session->set_flashdata('message', '<b>Success: </b> ' . $copied . ' Applicants Copied Successfully Out Of ' . $results['TotalApplicants'] . '! ' . $remaining . ' Already exists');
        }else{
            $applicant = array();
            $applicant['status'] = 0;
            $applicant['reason'] = 'No Applicants Found';
            $applicant['created_date'] = $date;
            $applicant['source_company_sid'] = $post_data['copy_from'];
            $applicant['source_company_name'] = $this->copy_applicants_model->get_company_data($post_data['copy_from']);
            $this->copy_applicants_model->insert_copied_record($applicant);
            $return_array['Status'] = TRUE;
            $return_array['Response'] = '<b>Error:</b> Applicant(s) exists!';
        }


        $this->resp($return_array);

        // redirect(base_url('manage_admin/copy_applicants'), 'refresh');

        _e('here', true, true);
    }

    /**
     * This function is Replicate now and its call from view "copy_applications_main" which is also replicate.
     * Fetch applicants
     * Created on: 22-05-2019
     *
     * @accepts GET
     *
     * @param $page Intgeger
     * @param $copy_from Intgeger
     * @param $copy_to Intgeger
     * @param $applicants_type Intgeger
     * @param $job_sid Intgeger
     *
     * @return JSON
     **/
    function fetch_applicants_ajax($page, $copy_from, $copy_to, $applicants_type, $job_sid){
        if(!$this->input->is_ajax_request()) redirect('/');
        if($this->input->method() != 'get') redirect('/');
        //
        $return_array = array();
        $return_array['Status'] = FALSE;
        $return_array['Response'] = 'Oops! invalid request made.';
        $post_data = array(
            'copy_from' => $copy_from,
            'copy_to' => $copy_to,
            'applicants_type' => $applicants_type,
            'job_sid' => $job_sid,
            'page' => $page
        );
        if(!sizeof($post_data)) $this->resp($return_array);
        //
        $page = $post_data['page'];
        $source_company_sid = $post_data['copy_from'];

        if($page == 0){
            $results = $this->copy_applicants_model->get_all_applicants_new(
                $post_data['copy_from'],
                $post_data['applicants_type'],
                1,
                $this->limit,
                true,
                array(),
                '*',
                $job_sid
            );

            $return_array['Status'] = true;
            $return_array['Response'] = 'Proceed.';
            $jobs = $this->copy_applicants_model->get_company_jobs($post_data['copy_from']);
            $tmp = array();
            if(sizeof($jobs)){
                foreach ($jobs as $k0 => $v0) {
                    $tmp[$v0['sid']] = $v0;
                }
            }
            $return_array['Jobs'] = $jobs;
            $return_array['Total']  = $results['TotalApplicants'];
            $return_array['Limit']  = $this->limit;
            $return_array['Chunks']  =  ceil($results['TotalApplicants'] / $this->limit);
            $this->resp($return_array);
        }

        // get chunk
        $applicants = $this->copy_applicants_model->get_all_applicants_new(
            $post_data['copy_from'],
            $post_data['applicants_type'],
            $page,
            $this->limit,
            false,
            array(),
            "
                portal_job_applications.sid,
                portal_job_applications.first_name,
                portal_job_applications.last_name,
                portal_job_applications.pictures,
                portal_job_applications.email
            ",
            $job_sid
        );

        if(!$applicants || !sizeof($applicants)){
            $return_array['Response'] = '<b>Error:</b> No Applicant(s) are available to copy!';
            $this->resp($return_array);
        }
        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'Proceed';
        $return_array['Applicants'] = $applicants;
        $return_array['Copied'] = count($applicants);
        $this->resp($return_array);

    }

}