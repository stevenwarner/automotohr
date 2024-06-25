<?php defined('BASEPATH') or exit('No direct script access allowed');

class Job_feeds_management extends Admin_Controller
{

    private $indeedProductIds;
    private $ziprecruiterProductIds;

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library("pagination");

        $this->load->model('manage_admin/job_feeds_management_model');

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        //
        $this->indeedProductIds = array(1, 21);
        $this->ziprecruiterProductIds = array(2);
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'job_feeds_management';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Job Feeds Management';

        $pending_jobs = $this->job_feeds_management_model->get_all_pending_jobs(0, 0);
        $this->data['pending_jobs'] = $pending_jobs;

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/job_feeds_management/index');
        } else {
            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'activate_job_on_feed':
                    $company_sid = $this->input->post('company_sid');
                    $job_sid = $this->input->post('job_sid');
                    $product_sid = $this->input->post('product_sid');
                    $no_of_days = $this->input->post('no_of_days');
                    $jobs_to_feed_sid = $this->input->post('jobs_to_feed_sid');

                    $today = new DateTime();
                    $expiration = clone $today;
                    $expiration->modify('+' . $no_of_days . ' days');

                    $data_to_update = array();
                    $data_to_update['activation_date'] = $today->format('Y-m-d H:i:s');
                    $data_to_update['expiry_date'] = $expiration->format('Y-m-d H:i:s');
                    $data_to_update['activation_status'] = 1;

                    $this->job_feeds_management_model->update_jobs_to_feed($jobs_to_feed_sid, $company_sid, $product_sid, $job_sid, $data_to_update);
                    // Set Xml Job
                    $this->addUpdateXML(
                        $job_sid,
                        $company_sid,
                        $product_sid,
                        $jobs_to_feed_sid,
                        $data_to_update['activation_date']
                    );

                    $this->session->set_flashdata('message', 'Job Successfully Activated!');

                    redirect('manage_admin/job_feeds_management', 'refresh');
                    break;
                case 'refund_product':
                    $company_sid = $this->input->post('company_sid');
                    $job_sid = $this->input->post('job_sid');
                    $product_sid = $this->input->post('product_sid');
                    $no_of_days = $this->input->post('no_of_days');
                    $employer_sid = $this->input->post('employer_sid');
                    $jobs_to_feed_sid = $this->input->post('jobs_to_feed_sid');

                    //Generate Refund Invoice
                    $quantity = 1;
                    $invoice_sid = $this->job_feeds_management_model->generate_new_market_place_refund_invoice($company_sid, $employer_sid, $product_sid, $quantity, $no_of_days);

                    //Mark Request as Refunded
                    $this->job_feeds_management_model->mark_jobs_to_feed_request_as_refunded($jobs_to_feed_sid, $invoice_sid);

                    $product_info = db_get_products_details($product_sid);

                    //Insert New Invoice Track Entry
                    $data_to_insert = array();
                    $data_to_insert['company_sid'] = $company_sid;
                    $data_to_insert['employer_sid'] = $employer_sid;
                    $data_to_insert['invoice_sid'] = $invoice_sid;
                    $data_to_insert['product_sid'] = $product_sid;
                    $data_to_insert['date_purchased'] = date('Y-m-d H:i:s');
                    $data_to_insert['quantity_purchased'] = 1;
                    $data_to_insert['product_name'] = $product_info['name'];
                    $data_to_insert['price'] = $product_info['price'];
                    $data_to_insert['item_type'] = 'refunded';

                    $this->job_feeds_management_model->insert_invoice_track_initial_record($data_to_insert);

                    $this->session->set_flashdata('message', '<strong>Success</strong> Product "' . $product_info['name'] . '" Successfully Refunded!');

                    redirect('manage_admin/job_feeds_management', 'refresh');

                    break;
                case 'mark_as_read':
                    $pending_job_sid = $this->input->post('pending_job_sid');

                    $this->job_feeds_management_model->set_read_status($pending_job_sid, 1);

                    $this->session->set_flashdata('message', '<strong>Success</strong> Read Status Successfully Updated!');

                    redirect('manage_admin/job_feeds_management', 'refresh');
                    break;
                case 'mark_as_unread':
                    $pending_job_sid = $this->input->post('pending_job_sid');

                    $this->job_feeds_management_model->set_read_status($pending_job_sid, 0);

                    $this->session->set_flashdata('message', '<strong>Success</strong> Read Status Successfully Updated!');

                    redirect('manage_admin/job_feeds_management', 'refresh');
                    break;
            }
        }
    }

    public function jobs_active_on_feeds()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'job_feeds_management';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Job Feeds Management - Active Jobs';

        $pending_jobs = $this->job_feeds_management_model->get_all_pending_jobs(1);
        $this->data['pending_jobs'] = $pending_jobs;

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/job_feeds_management/jobs_active_on_feeds');
        } else {
        }
    }

    public function refunded_requests()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'job_feeds_management';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Job Feeds Management - Refunded Requests';

        $refunded_requests = $this->job_feeds_management_model->get_all_pending_jobs(0, 1);
        $this->data['refunded_requests'] = $refunded_requests;

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/job_feeds_management/refunded_jobs_to_feed_requests');
        } else {
        }
    }


    /**
     * Create or update xml
     * Created on: 05-08-2019
     *
     * @param $jobSid           Integer
     * @param $companySid       Integer
     * @param $productSid       Integer
     * @param $jobsToFeedSid    Integer
     * @param $activationDate   String
     *
     * @return VOID
     */
    function addUpdateXML(
        $jobSid,
        $companySid,
        $productSid,
        $jobsToFeedSid,
        $activationDate
    ) {
        //
        $this->load->model('job_listings_visibility_model');
        $formpost = array();
        // Get organic field column
        $formpost['organic_feed'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'organic_feed');

        $deleteXmlJob = FALSE;
        // Organic is 0 then delete job row
        if (
            (int)$formpost['organic_feed'] === 0 ||
            !$this->job_listings_visibility_model->isMainCompany($jobSid)
        ) $deleteXmlJob = TRUE;
        //
        $uid = $jobSid;
        // Check for Approval Job Management check
        if ((int)$this->job_listings_visibility_model->isApprovalManagementActive($companySid) === 1 && (int)$this->job_listings_visibility_model->isJobApproved($jobSid) != 1) $deleteXmlJob = TRUE;
        // Delete the job from XML
        if ($deleteXmlJob) {
            $this->job_listings_visibility_model->deleteXMlJobById($jobSid);
            return;
        }
        //
        $formpost['publish_date'] = $activationDate;
        //
        $result = $this->job_listings_visibility_model->getUidOfJob($companySid, $jobSid);
        //
        if ($result === 0) $uid = $jobSid;
        else {
            $uid = $result['uid'];
            $formpost['publish_date'] = $result['publish_date'];
        }
        //
        $companyPortal = $this->job_listings_visibility_model->getPortalDetails($companySid);
        //
        $formpost['Title'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'Title');
        $formpost['JobCategory'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'JobCategory');
        $formpost['JobDescription'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'JobDescription');
        $formpost['Location_Country'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'Location_Country');
        $formpost['Location_State'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'Location_State');
        $formpost['Location_City'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'Location_City');
        $formpost['Location_ZipCode'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'Location_ZipCode');
        $formpost['Salary'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'Salary');
        $formpost['JobRequirements'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'JobRequirements');
        $formpost['SalaryType'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'JobRequirements');
        //
        if (in_array($productSid, $this->indeedProductIds))
            $this->indeedOrganicDB(
                $formpost,
                $uid,
                $companySid,
                $companyPortal,
                $jobSid
            );
        if (in_array($productSid, $this->ziprecruiterProductIds))
            $this->zipRecruiterOrganicDB(
                $formpost,
                $uid,
                $companySid,
                $companyPortal,
                $jobSid
            );
    }


    /**
     * Set, delete indeed job
     * Created on: 07-08-2019
     *
     * @param $formpost         Array
     * @param $uid              String
     * @param $companySid       Integer
     * @param $companyPortal    Array
     * @param $jobSid           Integer
     *
     * @return VOID
     */
    private function indeedOrganicDB(
        $formpost,
        $uid,
        $companySid,
        $companyPortal,
        $jobSid
    ) {
        // Check if product is active
        $is_product_purchased = $this->job_listings_visibility_model->isPurchasedJob($jobSid, $this->indeedProductIds);
        if ((int)$is_product_purchased === 0) {
            // Update xml job indeed check to 0
            $this->job_listings_visibility_model->updateXmlJob(
                array('is_indeed_job' => 0),
                array('job_sid' => $jobSid)
            );
            return;
        }
        // Check if job exists in database
        $xmlJobId = $this->job_listings_visibility_model->getXmlJobId($jobSid);
        // If not found then insert
        if (!$xmlJobId) {
            $insertDataArray = array('job_sid' => $jobSid, 'is_indeed_job' => 1);
            $xmlJobId = $this->job_listings_visibility_model->insertXmlJob($insertDataArray);
        }
        //
        $job = xml_create_job(
            $uid,
            $companySid,
            $companyPortal['sub_domain'],
            $companyPortal['CompanyName'],
            $companyPortal['job_title_location'],
            $formpost,
            'indeed',
            $this
        );
        // Update
        $this->job_listings_visibility_model->updateXmlJob(array('company_sid' => $companySid, 'job_content' => $job, 'is_indeed_job' => 1, 'is_ziprecruiter_job' => 0), array('sid' => $xmlJobId));
    }

    /**
     * Set, delete Zip Recruiter job
     * Created on: 07-08-2019
     *
     * @param $formpost         Array
     * @param $uid              String
     * @param $companySid       Integer
     * @param $companyPortal    Array
     * @param $jobSid           Integer
     *
     * @return VOID
     */
    private function zipRecruiterOrganicDB(
        $formpost,
        $uid,
        $companySid,
        $companyPortal,
        $jobSid
    ) {
        // Check if product is active
        $is_product_purchased = $this->job_listings_visibility_model->isPurchasedJob($jobSid, $this->ziprecruiterProductIds);
        if ((int)$is_product_purchased === 0) {
            // Update xml job indeed check to 0
            $this->job_listings_visibility_model->updateXmlJob(
                array('is_ziprecruiter_job' => 0),
                array('job_sid' => $jobSid)
            );
            return;
        }
        // Check if job exists in database
        $xmlJobId = $this->job_listings_visibility_model->getXmlJobId($jobSid);
        // If not found then insert
        if (!$xmlJobId) {
            $insertDataArray = array('job_sid' => $jobSid, 'is_ziprecruiter_job' => 1);
            $xmlJobId = $this->job_listings_visibility_model->insertXmlJob($insertDataArray);
        }
        //
        $job = xml_create_job(
            $uid,
            $companySid,
            $companyPortal['sub_domain'],
            $companyPortal['CompanyName'],
            $companyPortal['job_title_location'],
            $formpost,
            'ziprecruiter',
            $this
        );
        // Update
        $this->job_listings_visibility_model->updateXmlJob(array('company_sid' => $companySid, 'job_content' => $job, 'is_ziprecruiter_job' => 1, 'is_indeed_job' => 0), array('sid' => $xmlJobId));
    }

    /**
     * generate Indeed access tokens
     */
    public function generateIndeedToken()
    {
        // load library
        $this->load->library("Indeed_lib");

        $response = $this->indeed_lib->generateAccessToken();
        //
        if (isset($response["error"])) {
            $this->session->set_flashdata("indeed_error", $response["error"]);
        } else {
            //
            $this->session->set_flashdata("indeed_success", "Access token has been generated successfully.");
        }
        //
        return redirect("manage_admin/job_feeds_management");
    }
}
