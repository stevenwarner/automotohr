<?php defined('BASEPATH') or exit('No direct script access allowed');
ini_set('memory_limit', '520M');
class Job_listings extends Public_Controller
{
    private $indeedProductIds;
    private $ziprecruiterProductIds;
    private $res;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('multi_table_data');
        $this->load->model('dashboard_model');
        $this->load->model('indeed_model');
        $this->load->model('job_listings_visibility_model');
        $this->load->model('job_approval_rights_model');
        $this->load->model('hr_documents_management_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->library('pagination');
        $this->indeedProductIds = array(1, 21);
        $this->ziprecruiterProductIds = array(2);

        $this->res['Status'] = FALSE;
        $this->res['Response'] = 'Invalid request';
    }


    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('my_listings', 'location');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function add_listing()
    {
        if ($this->session->has_userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'add_listing'); // Param2: Redirect URL, Param3: Function Name
            $data['company_id'] = $company_id                                    = $data['session']['company_detail']['sid'];
            $employer_id                                                        = $data['session']['employer_detail']['sid'];
            $logged_in_user_sid                                                 = $data['session']['employer_detail']['sid'];
            $job_listing_template_group                                         = $data['session']['company_detail']['job_listing_template_group'];
            $ppjl_charge                                                        = $this->dashboard_model->get_pay_per_job_status($company_id);
            $career_site_listings_only                                          = $ppjl_charge['career_site_listings_only'];
            $per_job_listing_charge                                             = $ppjl_charge['per_job_listing_charge'];
            $data['per_job_listing_charge']                                     = $per_job_listing_charge;
            $data['career_site_listings_only']                                  = $career_site_listings_only;
            $allow_create_job                                                   = true;
            $allow_additional_purchase                                          = true;

            if ($per_job_listing_charge == 1) {
                $allow_create_job                                               = false;
                //                $allow_additional_purchase                                      = false;
                $product_type                                                   = 'pay-per-job';
                $purchasedProducts                                              = $this->dashboard_model->getPurchasedProducts($company_id, $product_type);
                $data['purchasedProducts']                                      = $purchasedProducts;
                $i                                                          = 0;
                $product_ids                                                = NULL;

                if (!empty($purchasedProducts)) {
                    foreach ($purchasedProducts as $product) {
                        $product_ids[$i]                                    = $product['product_sid'];
                        $i++;
                    }
                }

                $notPurchasedProducts                                       = $this->dashboard_model->notPurchasedProducts($product_ids, $product_type);
                $data['notPurchasedProducts']                               = $notPurchasedProducts;

                if (!empty($purchasedProducts)) {
                    $allow_create_job                                           = true;
                }
            }

            $data['allow_create_job']                                           = $allow_create_job;
            $data['allow_additional_purchase']                                  = $allow_additional_purchase;

            if (empty($job_listing_template_group)) {
                $job_listing_template_group                                     = 1;
            }

            $current_employees                                                  = $this->dashboard_model->GetAllUsersNew($company_id);
            $data['current_employees']                                          = $current_employees;

            $config = array(
                array(
                    'field' => 'Title',
                    'label' => 'Title',
                    'rules' => 'required' //|callback_unique_job_title
                ),
                array(
                    'field' => 'YouTube_Video',
                    'label' => 'YouTube Video',
                    'rules' => 'xss_clean|trim|callback_validate_youtube'
                ),
                array(
                    'field' => 'Vimeo_Video',
                    'label' => 'Vimeo Video',
                    'rules' => 'xss_clean|trim|callback_validate_vimeo'
                ),
                array(
                    'field' => 'JobDescription',
                    'label' => 'Job Description',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'Location_country',
                    'label' => 'Country Name',
                    'rules' => 'aplha,required'
                ),
                array(
                    'field' => 'Location_state',
                    'label' => 'State Name',
                    'rules' => 'aplha,required'
                ),
                array(
                    'field' => 'Location_city',
                    'label' => 'City Name',
                    'rules' => 'aplha,required'
                ),
                array(
                    'field' => 'Location_ZipCode',
                    'label' => 'ZipCode',
                    'rules' => 'numeric'
                ),
                array(
                    'field' => 'JobCategory[]',
                    'label' => 'Job Category',
                    'rules' => 'required'
                )
            );

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);
            //
            $data['hasNewModuleAccess'] = $hasNewModuleAccess = checkIfAppIsEnabled('indeed_sponsor', FALSE);
            //
            if ($this->form_validation->run() == FALSE) {
                $data_countries                                                 = db_get_active_countries();

                foreach ($data_countries as $value) {
                    $data_states[$value['sid']]                                 = db_get_active_states($value['sid']);
                }

                $data['active_countries']                                       = $data_countries;
                $data['active_states']                                          = $data_states;
                $data_states_encode                                             = htmlentities(json_encode($data_states));
                $data['states']                                                 = $data_states_encode;
                $assigned_industry                                              = $this->dashboard_model->check_is_assigned_industry($company_id);

                if ($assigned_industry == false) {
                    $categories                                                 = $this->multi_table_data->getJobCategory(); //job category list from DB
                } else {
                    $categories                                                 = $this->dashboard_model->get_industry_specific_categories($company_id, $company_id, $assigned_industry);
                }

                if (!empty($formpost['employees'])) { //Handle employees list
                    $employeesArray                                             = $formpost['employees'];
                    $data['employeesArray']                                     = $employeesArray;
                }

                $data['data_list']                                              = $categories;
                $data['screening_questions']                                    = $this->multi_table_data->getScreeningQuestionnaires($company_id); //Screening Questionnaires list from DB
                $data['title']                                                  = 'Add listing'; //page title
                $templateGroup                                                  = $this->dashboard_model->GetJobListingTemplateGroupDetail($job_listing_template_group); //Get Template Group Detail
                $availableTemplatesIds                                          = null;

                if (!empty($templateGroup[0]['templates'])) {
                    $availableTemplatesIds                                      = unserialize($templateGroup[0]['templates']);
                } else {
                    $availableTemplatesIds                                      = array();
                }

                if ($availableTemplatesIds == null || empty($availableTemplatesIds)) {
                    $availableTemplatesIds                                      = array();
                }

                $templates                                                      = array();
                $templates                                                      = $this->dashboard_model->GetJobListingTemplateDetail($availableTemplatesIds);
                $job_title_special_chars                                        = $this->dashboard_model->get_special_chars_status($company_id);
                $data['job_title_special_chars']                                = $job_title_special_chars;

                if (intval($job_listing_template_group) == 0) {
                    $data['templates']                                          = array();
                } else {
                    $data['templates']                                          = $templates;
                }

                $interview_questionnaires                                       = $this->dashboard_model->get_interview_questionnaires($company_id);
                $data['interview_questionnaires']                               = $interview_questionnaires;
                $all_job_logos                                                  = $this->dashboard_model->get_all_job_logos($company_id);
                $data['all_job_logos']                                          = $all_job_logos;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/add_listing_newest');
                $this->load->view('main/footer');
            } else {
                $formpost                                                       = $this->input->post(NULL, TRUE);

                if (!isset($formpost['interview_questionnaire_sid']) || $formpost['interview_questionnaire_sid'] == '') {
                    $formpost['interview_questionnaire_sid'] = 0;
                }

                //$formpost['interview_questionnaire_sid']                    = $formpost['interview_questionnaire_sid'] == '' ? 0 : $formpost['interview_questionnaire_sid'];

                if (!isset($formpost['questionnaire_sid']) || $formpost['questionnaire_sid'] == '') {
                    $formpost['questionnaire_sid'] = 0;
                }

                //                $formpost['questionnaire_sid']                                  = $formpost['questionnaire_sid'] == '' ? 0 : $formpost['questionnaire_sid'];
                $listing_data                                                   = array();

                if ($per_job_listing_charge == 1) {
                    $per_job_listing_error_flag                                 = false;
                    //                    echo '<pre>';
                    //                    print_r($formpost);

                    if ((isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it') && isset($formpost['pay_per_job_details']) && $formpost['pay_per_job_details'] != '') { // verify that product is purchased otherwise don't create error
                        $ppj_product_id                                         = $formpost['pay_per_job_details'];

                        if ($ppj_product_id > 0) { //verify that company has stock to purchase the product
                            $productCounter = $this->dashboard_model->checkPurchasedProductQty(array($ppj_product_id), $company_id, 'pay-per-job'); // it is required to check QTY

                            if (!empty($productCounter)) {
                                $ppj_expiry_days = $productCounter[0]['no_of_days'];
                                $ppj_remaining_qty = $productCounter[0]['remaining_qty'];
                                $ppj_product_name = $productCounter[0]['name'];

                                if ($ppj_remaining_qty > 0) { // allow job creation
                                    $listing_data['ppj_product_id']             = $ppj_product_id;
                                    $listing_data['ppj_expiry_days']            = $ppj_expiry_days;
                                } else { // Do not have the quatity to publish the job
                                    $per_job_listing_error_flag                 = true;
                                }
                            } else { // product not found error!
                                $per_job_listing_error_flag                     = true;
                            }
                        } else { // Product not purchased error
                            $per_job_listing_error_flag                         = true;
                        }
                        // Need forced expiration days. No of days from the product will be stored in it. it will check activation date then force activation will be implemented
                    }
                    //                    else {
                    //                        $per_job_listing_error_flag                             = true;
                    //                    }

                    if ($per_job_listing_error_flag) {
                        $this->session->set_flashdata('message', '<b>Error:</b> Job could not be created, Please try again');
                        redirect('my_listings', 'refresh');
                    }
                }
                //                DIE();

                //                echo '<pre>'; print_r($_POST); exit;
                foreach ($formpost as $key => $value) { //Arranging company detial
                    if (
                        $key != 'action' &&
                        $key != 'YouTube_Video' &&
                        $key != 'JobDescription' &&
                        $key != 'listing_status' &&
                        $key != 'published_on_career_page' &&
                        $key != 'employees' &&
                        $key != 'select_from_logo' &&
                        $key != 'expiration_date' &&
                        $key != 'Vimeo_Video' &&
                        $key != 'pay_per_job' &&
                        $key != 'yt_vm_video_url' &&
                        $key != 'upload_video' &&
                        $key != 'pay_per_job_details' &&
                        $key != 'p_with_main' &&
                        $key != 'is_free_checkout_mini' &&
                        $key != 'process_credit_card' &&
                        $key != 'cc_card_no' &&
                        $key != 'cc_expire_month' &&
                        $key != 'cc_expire_year' &&
                        $key != 'cc_type' &&
                        $key != 'cc_ccv' &&
                        $key != 'sponsor_indeed' &&
                        $key != 'indeedPackage' &&
                        $key != 'indeedPackageCustom' &&
                        $key != 'indeedPhoneNumber' &&
                        $key != 'indeedPackageDays' &&
                        $key != 'minSalary' &&
                        $key != 'maxSalary' &&
                        $key != 'sponsor_this_job'
                    ) { // exclude these values from array
                        if (is_array($value)) {
                            $value                                              = implode(',', $value);
                        }

                        $listing_data[$key]                                     = $value;
                    }
                }

                if (!empty($formpost['expiration_date'])) {
                    $expiration_date                                            = DateTime::createFromFormat('m/d/Y', $formpost['expiration_date'])->format('Y-m-d H:i:s');
                    $listing_data['expiration_date']                            = $expiration_date;
                }

                $job_title                                                      = $formpost['Title'];
                $video_source                                                   = $this->input->post('video_source');
                $video_id                                                       = '';

                if ($video_source != 'no_video') {
                    if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                        $random                                                 = generateRandomString(5);
                        $target_file_name                                       = basename($_FILES['upload_video']['name']);
                        $file_name                                              = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                        $target_dir                                             = "assets/uploaded_videos/";
                        $target_file                                            = $target_dir . $file_name;
                        $filename                                               = $target_dir . $company_id;

                        if (!file_exists($filename)) {
                            mkdir($filename);
                        }

                        if (move_uploaded_file($_FILES['upload_video']['tmp_name'], $target_file)) {
                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES['upload_video']['name']) . ' has been uploaded.');
                        } else {
                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect('job_listings/add_listing', 'refresh');
                        }

                        $video_id                                               = $file_name;
                    } else {
                        $video_id                                               = $this->input->post('yt_vm_video_url');

                        if ($video_source == 'youtube') {
                            $url_prams                                          = array();
                            parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $video_id                                       = $url_prams['v'];
                            } else {
                                $video_id                                       = '';
                            }
                        } else if ($video_source == 'vimeo') {
                            $video_id                                           = $this->vimeo_get_id($video_id);
                        }
                    }
                }

                if ($video_id != '') {
                    $listing_data['YouTube_Video']                              = $video_id;
                } else {
                    $listing_data['YouTube_Video']                              = NULL;
                }

                $listing_data['video_source']                                   = $video_source;
                $listing_data['user_sid']                                       = $company_id;
                $listing_data['job_creater_sid']                                = $employer_id;
                $listing_data['JobDescription']                                 = html_entity_decode($this->input->post('JobDescription', false));

                if (!empty($formpost['JobRequirements']) && $formpost['JobRequirements'] != "") {
                    $listing_data['JobRequirements']                            = html_entity_decode($this->input->post('JobRequirements', false));
                }

                if (isset($formpost['listing_status']) && !empty($formpost['listing_status']) && $formpost['listing_status'] != '') {
                    if (intval($formpost['listing_status']) == 1) {
                        $listing_data['active']                                 = 1;
                    } else {
                        $listing_data['active']                                 = 0;
                    }
                } else {
                    $listing_data['active']                                     = 0;
                }

                if (isset($formpost['published_on_career_page']) && !empty($formpost['published_on_career_page']) && $formpost['published_on_career_page'] != '') {
                    if (intval($formpost['published_on_career_page']) == 1) {
                        $listing_data['published_on_career_page']               = 1;
                    } else {
                        $listing_data['published_on_career_page']               = 0;
                    }
                } else {
                    $listing_data['published_on_career_page']                   = 0;
                }

                if ($per_job_listing_charge == 1 || $career_site_listings_only  == 1) {
                    $listing_data['published_on_career_page']                   = 1;
                }

                if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') { //uploading image to AWS
                    $uploaded_file_name                                         = upload_file_to_aws('pictures', $company_id, 'listing_logo');

                    if ($uploaded_file_name != 'error') {
                        $listing_data['pictures']                               = $uploaded_file_name;
                    }
                } else {
                    if ($formpost['select_from_logo'] != 'no_logo') {
                        $listing_data['pictures']                               = $formpost['select_from_logo'];
                    }
                }

                $job_approval_module_status                                     = get_job_approval_module_status($company_id);
                $employee_approval_module_status                                = 0;

                if ($job_approval_module_status == 0) {
                    $listing_data['approval_status']                            = 'approved';
                    $listing_data['approval_status_by']                         = $employer_id;
                    $approval_status_change_datetime                            = date('Y-m-d H:i:s');
                    $listing_data['activation_date']                            = date('Y-m-d H:i:s');
                    $listing_data['approval_status_change_datetime']            = $approval_status_change_datetime;

                    if ((isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it') && $per_job_listing_charge == 1) {
                        $listing_data['ppj_activation_date']                    = $approval_status_change_datetime;
                    }
                } else { // check if the creator of the job as rights to approve job
                    $employee_approval_module_status                            = get_job_approval_module_status($employer_id);

                    if ($employee_approval_module_status == 1) { // auto approve job as employee has job approval rights
                        $listing_data['approval_status']                        = 'approved';
                        $listing_data['approval_status_by']                     = $employer_id;
                        $approval_status_change_datetime                        = date('Y-m-d H:i:s');
                        $listing_data['activation_date']                        = date('Y-m-d H:i:s');
                        $listing_data['approval_status_change_datetime']        = $approval_status_change_datetime;

                        if ((isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it') && $per_job_listing_charge == 1) {
                            $listing_data['ppj_activation_date']                = $approval_status_change_datetime;
                        }
                    } else {
                        $listing_data['approval_status']                    = 'pending';
                    }
                }



                // Indeed Sponsor
                if ($hasNewModuleAccess && isset($formpost['sponsor_indeed']) && $formpost['sponsor_indeed'] == 'on') {
                    $listing['indeed_sponsored'] = 1;
                }

                //
                $listing_data["Salary"] = getSanitizeSalary(
                    $formpost["minSalary"],
                    $formpost["maxSalary"],
                );

                $jobId                                                          = $this->dashboard_model->add_listing($listing_data);  //Now call dashboard_model function to insert data in DB

                $this->load->model("Job_sync_api_model");
                $this->Job_sync_api_model->checkAndAddJob($jobId);
                if ($listing_data["organic_feed"] == 1) {
                    // load the indeed model
                    $this->load->model("Indeed_model", "indeed_model");
                    // $this->indeed_model->addJobToQueue(
                    //     $jobId,
                    //     $company_id,
                    //     $listing_data["approval_status"]
                    // );
                }
                //send new created job to remarket
                $this->sendJobDetailsToRemarket($listing_data, $jobId, $data['session']['company_detail']);

                if ($per_job_listing_charge == 1 && (isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it')) {
                    $this->dashboard_model->deduct_product_qty($listing_data['ppj_product_id'], $company_id, $listing_data['ppj_expiry_days']);
                }

                // Indeed Sponsor
                if ($hasNewModuleAccess && isset($formpost['sponsor_indeed']) && $formpost['sponsor_indeed'] == 'on') {
                    $this->indeed_model->insertJobBudget(array(
                        'budget' => $formpost['indeedPackageCustom'] != '' ? $formpost['indeedPackageCustom'] : $formpost['indeedPackage'],
                        'jobSid' => $jobId,
                        'employeeSid' => $employer_id,
                        'budgetPerDay' => $formpost['indeedPackageDays'],
                        'phoneNumber' => $formpost['indeedPhoneNumber']
                    ));
                }

                $record_data                                                    = array();
                $record_data['company_sid']                                     = $company_id;
                $record_data['company_name']                                    = $data['session']['company_detail']['CompanyName'];
                $record_data['portal_job_listings_sid']                         = $jobId;
                $record_data['active']                                          = $listing_data['active'];
                $record_data['created_by_name']                                 = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
                $record_data['created_by_sid']                                  = $employer_id;
                $record_data['create_date']                                     = date('Y-m-d H:i:s');
                $record_data['approval_status']                                 = 'pending';

                if (isset($listing_data['approval_status'])) {
                    $record_data['approval_status']                             = $listing_data['approval_status'];
                    $record_data['approval_status_by']                          = $employer_id;
                    $record_data['approval_status_change_datetime']             = date('Y-m-d H:i:s');
                }

                if ($listing_data['active']) {
                    $record_data['activation_date']                             = date('Y-m-d H:i:s');
                    $record_data['edit_place']                                  = 'Activated at the time of creation.';
                } else {
                    $record_data['deactive_date']                               = date('Y-m-d H:i:s');
                    $record_data['deactive_by_name']                            = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
                    $record_data['deactive_by_sid']                             = $employer_id;
                    $record_data['edit_place']                                  = 'De-Activated at the time of creation.';
                }

                $record_data['Title']                                           = $job_title;

                if (isset($listing_data['organic_feed']) && $career_site_listings_only == 0) {
                    $record_data['organic_feed']                                = 1;
                } else {
                    $record_data['organic_feed']                                = 0;
                }

                $this->dashboard_model->insert_jobs_records($record_data);

                if (!empty($formpost['employees'])) { //Handle Visibility to Employees Start
                    $employeesArray                                             = $formpost['employees'];
                } else {
                    $employeesArray                                             = array();
                    array_push($employeesArray, $logged_in_user_sid);
                }

                $this->job_listings_visibility_model->InsertNewVisibilityGroup($company_id, $jobId, $employeesArray);
                //$myListing = $this->dashboard_model->get_listing($jobId, $company_id); // no need for it we already have job title
                $employess_with_job_visibility                                  = $employeesArray;
                $notifications_status                                           = get_notifications_status($company_id);
                $company_sms_notification_status                                = get_company_sms_status($this, $company_id);
                $approval_management_email_notification                         = 0;

                if (($key = array_search($logged_in_user_sid, $employess_with_job_visibility)) !== false) { // exclude job creator from the list as he don't need to be notified for the email of new job
                    unset($employess_with_job_visibility[$key]);
                }

                if (!empty($notifications_status)) {
                    $approval_management_email_notification                     = $notifications_status['approval_rights_notifications'];
                }

                $email_sent_to_employees                                        = array();

                if ($job_approval_module_status == 1 && $approval_management_email_notification == 1) { //Send email to Users With Job Approval Rights + they are registered to recived notifications
                    $emailTemplateData                                          = get_email_template(NEW_LISTING_NOTIFICATION_TO_USER_WITH_APPROVAL_RIGHTS);
                    $employer_name                                              = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
                    $approval_management_email_contacts                         = get_notification_email_contacts($company_id, 'approval_management');

                    if (!empty($approval_management_email_contacts)) { // someone is registered to receive email. Please verify and send email
                        $employee_registered_to_receive_emails                  = array();

                        foreach ($approval_management_email_contacts as $key => $value) {
                            if ($value['employer_sid'] > 0) {
                                $employee_registered_to_receive_emails[]        = $value['employer_sid'];
                            }
                        }

                        $usersWithRights                                        = $this->job_approval_rights_model->GetUsersWithApprovalRights($company_id);
                        $alluserswithrights                                     = array();
                        $userrightsdetails                                      = array();

                        foreach ($usersWithRights as $uwr) {
                            $uwr_sid                                            = $uwr['sid'];
                            $alluserswithrights[]                               = $uwr_sid;
                            $userrightsdetails[$uwr_sid] = array(
                                'first_name' => $uwr['first_name'],
                                'last_name' => $uwr['last_name'],
                                'email'     => $uwr['email']
                            );
                        }

                        foreach ($employess_with_job_visibility as $visibility_employees_id) { // this the parent array with the employees who has visibility to job
                            if (in_array($visibility_employees_id, $employee_registered_to_receive_emails) && in_array($visibility_employees_id, $alluserswithrights)) { // check that the employee has rights to approve jobs and he has registered to recieve email
                                $email_sent_to_employees[] = $visibility_employees_id;
                                $userEmail = $userrightsdetails[$visibility_employees_id]['email'];
                                $userFullName = ucwords($userrightsdetails[$visibility_employees_id]['first_name'] . ' ' . $userrightsdetails[$visibility_employees_id]['last_name']);
                                $emailTemplateBody = $emailTemplateData['text'];
                                $emailTemplateBody = str_replace('{{job-title}}', ucwords($job_title), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{user-name}}', $userFullName, $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{creator-name}}', ucwords($employer_name), $emailTemplateBody);
                                $from = $emailTemplateData['from_email'];
                                $to = $userEmail;
                                $subject = $emailTemplateData['subject'];
                                $subject = str_replace('{{job-title}}', ucwords($job_title), $subject);
                                $from_name = $emailTemplateData['from_name'];
                                $body = EMAIL_HEADER
                                    . $emailTemplateBody
                                    . EMAIL_FOOTER;
                                log_and_sendEmail($from, $to, $subject, $body, $from_name);

                                //Send SMS Also
                                $sms_notify = 0;
                                $contact_no = 0;
                                if ($company_sms_notification_status) {
                                    $notify_by = get_employee_sms_status($this, $visibility_employees_id);
                                    if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                        $contact_no = $notify_by['PhoneNumber'];
                                        $sms_notify = 1;
                                    }
                                    if ($sms_notify) {
                                        $this->load->library('Twilioapp');
                                        // Send SMS
                                        $sms_template = get_company_sms_template($this, $company_id, 'new_job_listing');
                                        $replacement_sms_array = array(); //Send Payment Notification to admin.
                                        $replacement_sms_array['contact_name'] = ucwords(strtolower($userFullName));
                                        $sms_body = $job_title . ' is a new Job Listing added by ' . ucwords($employer_name);
                                        if (sizeof($sms_template) > 0) {
                                            $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_sms_array);
                                        }
                                        sendSMS(
                                            $contact_no,
                                            $sms_body,
                                            trim(ucwords(strtolower($userFullName))),
                                            $userEmail,
                                            $this,
                                            $sms_notify,
                                            $company_id
                                        );
                                    }
                                }
                            }
                        }
                    }
                }

                if (!empty($employee_registered_to_receive_emails)) {

                    if (($key = array_search($logged_in_user_sid, $employee_registered_to_receive_emails)) !== false) { // exclude job creator from the list as he don't need to be notified for the email of new job
                        unset($employee_registered_to_receive_emails[$key]);
                    }

                    foreach ($employee_registered_to_receive_emails as $ertre) {
                        if (!in_array($ertre, $email_sent_to_employees)) {
                            $emp_info = $this->job_approval_rights_model->getemployeedetails($ertre, $company_id);

                            if (!empty($emp_info)) {
                                $userEmail = $emp_info['email'];
                                $userFullName = ucwords($emp_info['first_name'] . ' ' . $emp_info['last_name']);
                                $emailTemplateBody = $emailTemplateData['text'];
                                $emailTemplateBody = str_replace('{{job-title}}', ucwords($job_title), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{user-name}}', $userFullName, $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{creator-name}}', ucwords($employer_name), $emailTemplateBody);
                                $from = $emailTemplateData['from_email'];
                                $to = $userEmail;
                                $subject = $emailTemplateData['subject'];
                                $subject = str_replace('{{job-title}}', ucwords($job_title), $subject);
                                $from_name = $emailTemplateData['from_name'];
                                $body = EMAIL_HEADER
                                    . $emailTemplateBody
                                    . EMAIL_FOOTER;
                                log_and_sendEmail($from, $to, $subject, $body, $from_name);
                            }
                        }
                    }
                }

                if ($job_approval_module_status == 1 && $employee_approval_module_status == 1) { //Send email to Users With Job Approval Rights if Job Approval Module is enabled end
                    $this->session->set_flashdata('message', '<b>Success:</b> New job has been Added Successfully.');
                } else if ($job_approval_module_status == 1 && $employee_approval_module_status == 0) {
                    $this->session->set_flashdata('message', '<b>Success:</b> New job has been Added Successfully. Currently it is waiting on an Authorized approval');
                } else {
                    $this->session->set_flashdata('message', '<b>Success:</b> New Job added successfully');
                }

                $formpost['sid'] = $jobId;
                // Added on: 05-08-2019
                // $this->addUpdateXML( $formpost['sid'], $employer_id );

                if ($formpost['action'] == "publish") {
                    redirect('add_listing_advertise/' . $jobId);
                } elseif ($formpost["action"] == "save") {
                    redirect("my_listings", "location");
                }
            } //else end for form submit success
        } //if end for session check success
        else {
            redirect(base_url('login'), 'refresh');
        } //else end for session check fail
    }

    public function my_listings($status = 'active', $searchKeyword = null, $currentPage = null)
    {
        if ($this->session->has_userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_listings'); // Param2: Redirect URL, Param3: Function Name
            $data['title'] = 'My Listings';
            $data['logged_in_user_sid'] = $employer_sid;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $records_per_page = 100;
                $baseUrl = '';
                $uri_segment = 4;
                $keywords = '';

                if ($searchKeyword != null) {
                    $data['search'] = true;

                    if ($searchKeyword != 'all') {
                        $data['search'] = false;
                        $data['searchValue'] = urldecode($searchKeyword);
                        $keywords = urldecode($searchKeyword);
                    } else {
                        $data['searchValue'] = '';
                        $keywords = '';
                    }

                    $baseUrl = base_url('my_listings') . '/' . $status . '/' . urlencode($searchKeyword);
                } else {
                    $data['search'] = false;
                    $data['searchValue'] = '';
                    $keywords = '';
                    $baseUrl = base_url('my_listings' . '/' . $status . '/all');
                }

                $page = $currentPage;
                $my_offset = 0;

                if ($page > 1) {
                    $my_offset = ($page - 1) * $records_per_page;
                }

                $total_records = 0;
                $job_listings = array();
                //                $archived_job_listings = array();

                if (is_admin($employer_sid)) {
                    if ($status == 'active') {
                        $job_listings = $this->job_listings_visibility_model->GetAllJobsCompanySpecific($company_sid, $keywords, $records_per_page, $my_offset, 1);
                        $total_records = $this->job_listings_visibility_model->GetAllJobsCountCompanySpecific($company_sid, $keywords, 1);
                    } else {
                        $job_listings = $this->job_listings_visibility_model->GetAllJobsCompanySpecific($company_sid, $keywords, $records_per_page, $my_offset, 0);
                        $total_records = $this->job_listings_visibility_model->GetAllJobsCountCompanySpecific($company_sid, $keywords, 0);
                    }
                } else {
                    if ($status == 'active') {
                        $job_listings = $this->job_listings_visibility_model->GetAllJobsCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords, $records_per_page, $my_offset, 1);
                        $total_records = $this->job_listings_visibility_model->GetAllJobsCountCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords, 1);
                    } else {
                        $job_listings = $this->job_listings_visibility_model->GetAllJobsCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords, $records_per_page, $my_offset, 0);
                        $total_records = $this->job_listings_visibility_model->GetAllJobsCountCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords, 0);
                    }
                }

                $config = array();
                $config['base_url'] = $baseUrl;
                $config['total_rows'] = $total_records;
                $config['per_page'] = $records_per_page;
                $config['uri_segment'] = $uri_segment;
                $choice = $config['total_rows'] / $config['per_page'];
                $config['num_links'] = ceil($choice);
                $config['use_page_numbers'] = true;
                $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
                $config['full_tag_close'] = '</ul></nav><!--pagination-->';
                $config['first_link'] = '&laquo; First';
                $config['first_tag_open'] = '<li class="prev page">';
                $config['first_tag_close'] = '</li>';
                $config['last_link'] = 'Last &raquo;';
                $config['last_tag_open'] = '<li class="next page">';
                $config['last_tag_close'] = '</li>';
                $config['next_link'] = '<i class="fa fa-angle-right"></i>';
                $config['next_tag_open'] = '<li class="next page">';
                $config['next_tag_close'] = '</li>';
                $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
                $config['prev_tag_open'] = '<li class="prev page">';
                $config['prev_tag_close'] = '</li>';
                $config['cur_tag_open'] = '<li class="active"><a href="">';
                $config['cur_tag_close'] = '</a></li>';
                $config['num_tag_open'] = '<li class="page">';
                $config['num_tag_close'] = '</li>';
                $this->pagination->initialize($config);
                $data['links']                                                  = $this->pagination->create_links();
                $data['listings_active']                                        = $job_listings;
                $data['job_approval_module_status']                             = get_job_approval_module_status($company_sid);
                $ppjl_charge                                                    = $this->dashboard_model->get_pay_per_job_status($company_sid);
                $career_site_listings_only                                      = $ppjl_charge['career_site_listings_only'];
                $per_job_listing_charge                                         = $ppjl_charge['per_job_listing_charge'];
                $data['per_job_listing_charge']                                 = $per_job_listing_charge;
                $data['career_site_listings_only']                              = $career_site_listings_only;

                if ($per_job_listing_charge == 1 && $career_site_listings_only == 1) {
                    $enable_advertise = TRUE;
                } else if ($per_job_listing_charge == 0 && $career_site_listings_only == 0) {
                    $enable_advertise = TRUE;
                } else {
                    $enable_advertise = FALSE;
                }

                $data['enable_advertise']                                       = false;
                //
                if ($status == 'inactive') {
                    //
                    if (!empty($job_listings)) {
                        //
                        $jobIds = array_column($job_listings, 'sid');
                        //
                        $jobLastStates = $this->dashboard_model->GetJobLastStateByIds($jobIds);
                        //
                        foreach ($job_listings as $index => $job) {
                            $job_listings[$index]['deactive_by_name'] = $jobLastStates[$job['sid']]['deactive_by_name'];
                            $job_listings[$index]['deactivation_date'] = $jobLastStates[$job['sid']]['deactive_date'];
                            $job_listings[$index]['totalapplicants'] = $jobLastStates[$job['sid']]['deactive_date'];
                        }
                    }
                }

                //
                foreach ($job_listings as $index => $job) {
                    $job_listings[$index]['totalapplicants'] = $this->job_listings_visibility_model->getApplicantsByJobId($job['sid'], $company_sid);
                }


                $data['listings_active'] = $job_listings;
                $data['url_status'] = $status;
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/my_listings');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'archive_job':
                        $job_sid = $this->input->post('job_sid');
                        $this->dashboard_model->archive_job($job_sid);
                        echo 'success';
                        exit;
                        break;
                }
            }
        } //if end for session check success
        else {
            redirect(base_url('login'), "refresh");
        } //else end for session check fail
    }

    public function edit_listing($id = NULL)
    {
        if ($id == NULL) {
            $this->session->set_flashdata('message', '<b>Error:</b> No job found!');
            redirect('my_listings', 'refresh');
        } else {
            if ($this->session->userdata('logged_in')) {
                $data['jobSid'] = $id;
                $data['session']                                                = $this->session->userdata('logged_in');
                $security_sid                                                   = $data['session']['employer_detail']['sid'];
                $employer_id                                                    = $data['session']['employer_detail']['sid'];
                $data['employeeSid'] = $employer_id;
                $security_details                                               = db_get_access_level_details($security_sid);
                $data['security_details']                                       = $security_details;
                check_access_permissions($security_details, 'dashboard', 'add_listing'); // Param2: Redirect URL, Param3: Function Name
                $data['title']                                                  = 'Edit Listing';
                $company_id                                                     = $data['session']['company_detail']['sid'];
                $logged_in_user_sid                                             = $data['session']['employer_detail']['sid'];
                $data['logged_in_user_sid']                                     = $logged_in_user_sid;
                $current_employees                                              = $this->dashboard_model->GetAllUsersNew($company_id);
                $data['current_employees']                                      = $current_employees;
                $id                                                             = $this->uri->segment(2);
                $myListing                                                      = $this->dashboard_model->get_listing($id, $company_id);
                $visible_to                                                     = $this->job_listings_visibility_model->GetEmployerIds($id);
                $jobs_approval_module_status                                    = get_job_approval_module_status($company_id);
                $has_rights                                                     = 0;



                if (!is_admin($employer_id) && $jobs_approval_module_status == 1) {
                    $employee_approval_module_status                            = get_job_approval_module_status($employer_id);

                    if ($employee_approval_module_status == 1) {
                        $has_rights                                             = 1;
                    }
                } else {
                    $has_rights                                                 = 1;
                }

                $data['has_rights']                                             = $has_rights;

                if (!empty($visible_to)) {
                    $data['employeesArray']                                     = $visible_to;
                } else {
                    $data['employeesArray']                                     = array($logged_in_user_sid);
                }

                $job_creater_sid                                                = $myListing['job_creater_sid'];
                $data['job_creater_details']                                    = '';

                if ($job_creater_sid > 0) { // get creator Name
                    $job_creator_credentials                                    = $this->dashboard_model->get_employee_name($job_creater_sid);
                    $data['job_creater_details']                                = 'Job Created by: <b>' . $job_creator_credentials['first_name'] . '&nbsp;' . $job_creator_credentials['last_name'] . '&nbsp;[' . $job_creator_credentials['email'] . ']</b>';
                }
                //
                $salaryBreakDown = breakSalary($myListing["Salary"], $myListing["SalaryType"]);
                $myListing["minSalary"] = $salaryBreakDown["min"];
                $myListing["maxSalary"] = $salaryBreakDown["max"];

                $data['listing']                                                = $myListing;
                $job_listing_template_group                                     = $data['session']['company_detail']['job_listing_template_group']; // templates code start

                if (empty($job_listing_template_group)) {
                    $job_listing_template_group                                 = 1;
                }

                $templateGroup                                                  = $this->dashboard_model->GetJobListingTemplateGroupDetail($job_listing_template_group);
                $availableTemplatesIds                                          = null;

                if (!empty($templateGroup[0]['templates'])) {
                    $availableTemplatesIds                                      = unserialize($templateGroup[0]['templates']);
                } else {
                    $availableTemplatesIds                                      = array();
                }

                if ($availableTemplatesIds == null || empty($availableTemplatesIds)) {
                    $availableTemplatesIds
                        = array();
                }

                $templates                                                      = array();

                foreach ($availableTemplatesIds as $templateId) {
                    $template                                                   = $this->dashboard_model->GetJobListingTemplateDetail($templateId);

                    if (!empty($template)) {
                        $templates[]                                            = $template[0];
                    }
                }

                if (intval($job_listing_template_group) == 0) {
                    $data['templates']                                          = array();
                } else {
                    $data['templates']                                          = $templates;
                } // templates code end

                if (empty($data['listing'])) {
                    $this->session->set_flashdata('message', '<b>Error:</b> You are not owner of this job!');
                    redirect('my_listings', 'refresh');
                }

                $config = array(
                    array(
                        'field' => 'Title',
                        'label' => 'Title',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'YouTube_Video',
                        'label' => 'YouTube Video',
                        'rules' => 'xss_clean|trim|callback_validate_youtube'
                    ),
                    array(
                        'field' => 'Vimeo_Video',
                        'label' => 'Vimeo Video',
                        'rules' => 'xss_clean|trim|callback_validate_vimeo'
                    ),
                    array(
                        'field' => 'JobDescription',
                        'label' => 'Job Description',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'Location_country',
                        'label' => 'Country Name',
                        'rules' => 'aplha,required'
                    ),
                    array(
                        'field' => 'Location_state',
                        'label' => 'State Name',
                        'rules' => 'aplha,required'
                    ),
                    array(
                        'field' => 'Location_city',
                        'label' => 'City Name',
                        'rules' => 'aplha,required'
                    ),
                    array(
                        'field' => 'Location_ZipCode',
                        'label' => 'ZipCode',
                        'rules' => 'numeric'
                    ),
                    array(
                        'field' => 'JobCategory[]',
                        'label' => 'Job Category',
                        'rules' => 'required'
                    )
                );

                $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                $this->form_validation->set_rules($config);

                $ppjl_charge                                                    = $this->dashboard_model->get_pay_per_job_status($company_id);
                $career_site_listings_only                                      = $ppjl_charge['career_site_listings_only'];
                $per_job_listing_charge                                         = $ppjl_charge['per_job_listing_charge'];
                $data['per_job_listing_charge']                                 = $per_job_listing_charge;
                $data['career_site_listings_only']                              = $career_site_listings_only;
                $data['sponsor_radio']                                          = 'no'; // if per job is disabled from manage_admin

                if ($per_job_listing_charge == 1) {
                    $allow_create_job                                               = false;
                    $product_type                                                   = 'pay-per-job';
                    $purchasedProducts                                              = $this->dashboard_model->getPurchasedProducts($company_id, $product_type);
                    $data['purchasedProducts']                                      = $purchasedProducts;
                    $i                                                          = 0;
                    $product_ids                                                = NULL;

                    if (!empty($purchasedProducts)) {
                        foreach ($purchasedProducts as $product) {
                            $product_ids[$i]                                    = $product['product_sid'];
                            $i++;
                        }
                    }

                    $notPurchasedProducts                                       = $this->dashboard_model->notPurchasedProducts($product_ids, $product_type);
                    $data['notPurchasedProducts']                               = $notPurchasedProducts;

                    if (!empty($purchasedProducts)) {
                        $allow_create_job                                           = true;
                    }

                    $expired = 0;
                    $data['sponsor_radio']  = $myListing['ppj_product_id'] == 0 ? 'no' : 'yes';

                    if ($data['sponsor_radio'] == 'yes' && date('Y-m-d H:i:s') >= date('Y-m-d H:i:s', strtotime($myListing['ppj_activation_date'] . ' + ' . $myListing['ppj_expiry_days'] . ' days '))) {
                        $data['sponsor_radio'] = 'no';
                        $expired = 1;
                    } else {
                        if ($myListing['ppj_product_id'] > 0) {
                            $data['productDetail'] = $this->dashboard_model->productDetail($myListing['ppj_product_id'])[0];
                            $exp_date = date('Y-m-d', strtotime($myListing['ppj_activation_date'] . ' + ' . $myListing['ppj_expiry_days'] . ' days '));
                            $todate = date('Y-m-d');
                            $diff = strtotime($exp_date) - strtotime($todate);
                            $data['expiring_in'] = abs(round($diff / 86400));
                            $expired = 2;
                        }
                    }
                }

                //
                $data['hasNewModuleAccess'] = $hasNewModuleAccess = checkIfAppIsEnabled('indeed_sponsor', FALSE);

                if ($this->form_validation->run() == FALSE) {
                    $data_countries                                             = db_get_active_countries();

                    foreach ($data_countries as $value) {
                        $data_states[$value['sid']]                             = db_get_active_states($value['sid']);
                    }

                    $data['active_countries']                                   = $data_countries;
                    $data['active_states']                                      = $data_states;
                    $data_states_encode                                         = htmlentities(json_encode($data_states));
                    $data['states']                                             = $data_states_encode;
                    $job_title_special_chars                                    = $this->dashboard_model->get_special_chars_status($company_id);
                    $data['job_title_special_chars']                            = $job_title_special_chars;
                    $assigned_industry                                          = $this->dashboard_model->check_is_assigned_industry($company_id);

                    if ($assigned_industry == false) {
                        $data['data_list']                                      = $this->multi_table_data->getJobCategory(); //job category list from DB
                    } else {
                        $data['data_list']                                      = $this->dashboard_model->get_industry_specific_categories($company_id, $employer_id, $assigned_industry);
                    }

                    $data['screening_questions']                                = $this->multi_table_data->getScreeningQuestionnaires($company_id); //Questionaier
                    $interview_questionnaires                                   = $this->dashboard_model->get_interview_questionnaires($company_id); //interview questionnaires
                    $data['interview_questionnaires']                           = $interview_questionnaires;
                    $all_job_logos                                              = $this->dashboard_model->get_all_job_logos($company_id);
                    $data['all_job_logos']                                      = $all_job_logos;
                    $sub_domain                                                 = $data['session']['portal_detail']['sub_domain'];
                    $job_url                                                    = 'https://' . $sub_domain . '/preview_job/' . $id;

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $job_url);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $contents = curl_exec($ch);

                    if (curl_errno($ch)) { // it is very important to find out the actual issue
                        $contents = '';
                    } else {
                        curl_close($ch);
                    }

                    if (!is_string($contents) || !strlen($contents)) {
                        $contents = 'Preview Not Available';
                    }

                    if ($hasNewModuleAccess) {
                        //Indeed package
                        $data['IndeedBudget'] = $this->checkAndUpdateIndeedBudget($data['jobSid']);
                        if (!sizeof($data['IndeedBudget'])) $data['listing']['indeed_sponsored'] = 0;
                    }

                    $preview_link                                               = base_url('preview_listing_iframe') . '/' . $id;
                    $data['preview_content']                                    = $contents;
                    $data['preview_link']                                       = $preview_link;

                    $this->load->view('main/header', $data);
                    $this->load->view('manage_employer/edit_listing_new');
                    $this->load->view('main/footer');
                } else {
                    $formpost = $this->input->post(NULL, TRUE);

                    $altertextarea = $formpost['JobDescription'] = $this->input->post('JobDescription', false);
                    $formpost['JobRequirements'] = $this->input->post('JobRequirements', false);
                    $previous_active = isset($formpost['listing_status_old']) ? $formpost['listing_status_old'] : '';
                    unset($formpost['listing_status_old']);

                    if (!isset($formpost['interview_questionnaire_sid']) || $formpost['interview_questionnaire_sid'] == '') {
                        $formpost['interview_questionnaire_sid'] = 0;
                    }

                    if (!isset($formpost['questionnaire_sid']) || $formpost['questionnaire_sid'] == '') {
                        $formpost['questionnaire_sid'] = 0;
                    }

                    if ($per_job_listing_charge == 1 && $formpost['listing_status']) {
                        $per_job_listing_error_flag                             = false;

                        if ($data['sponsor_radio'] == 'no' && (isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it') && isset($formpost['pay_per_job_details']) && $formpost['pay_per_job_details'] != '') { // verify that product is purchased otherwise don't create error
                            $ppj_product_id                                     = $formpost['pay_per_job_details'];

                            if ($ppj_product_id > 0) { //verify that company has stock to purchase the product
                                $productCounter                                 = $this->dashboard_model->checkPurchasedProductQty(array($ppj_product_id), $company_id, 'pay-per-job'); // it is required to check QTY
                                if (!empty($productCounter)) {
                                    $ppj_expiry_days                            = $productCounter[0]['no_of_days'];
                                    $ppj_remaining_qty                          = $productCounter[0]['remaining_qty'];
                                    $ppj_product_name                           = $productCounter[0]['name'];

                                    if ($ppj_remaining_qty > 0) { // allow job creation
                                        $listing_data['ppj_product_id']         = $ppj_product_id;
                                        $listing_data['ppj_expiry_days']        = $ppj_expiry_days;
                                        $listing_data['ppj_activation_date']    = NULL;
                                    } else { // Do not have the quatity to publish the job
                                        $per_job_listing_error_flag             = true;
                                    }
                                } else { // product not found error!
                                    $per_job_listing_error_flag                 = true;
                                }
                            } else { // Product not purchased error
                                $per_job_listing_error_flag                     = true;
                            }
                            // Need forced expiration days. No of days from the product will be stored in it. it will check activation date then force activation will be implemented
                        }

                        if ($expired == 2 && (isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'not_required')) { //Check for If user forcefully cancel sponsorship
                            $listing_data['ppj_activation_date']        = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' - 1 days '));
                            $listing_data['ppj_expiry_days']            = 0;
                            if ($myListing['ppj_activation_date'] == NULL && $myListing['approval_status'] == 'pending') { // Refund if Job is not activated
                                $this->job_approval_rights_model->refund_pay_per_job($myListing['Title'], $myListing['ppj_product_id']);
                            }
                        }

                        if ($per_job_listing_error_flag) {
                            $this->session->set_flashdata('message', '<b>Error:</b> Job could not be created, Please try again');
                            redirect('my_listings', 'refresh');
                        }
                    }



                    foreach ($formpost as $key => $value) { //Arranging company detial
                        if (
                            $key != 'old_picture' &&
                            $key != 'delete_image' &&
                            $key != 'sid' &&
                            $key != 'YouTube_Video' &&
                            $key != 'Vimeo_Video' &&
                            $key != 'JobDescription' &&
                            $key != 'listing_status' &&
                            $key != 'published_on_career_page' &&
                            $key != 'employees' &&
                            $key != 'visibility_perms' &&
                            $key != 'active' &&
                            $key != 'expiration_date' &&
                            $key != 'select_from_logo' &&
                            $key != 'yt_vm_video_url' &&
                            $key != 'upload_video' &&
                            $key != 'pre_upload_video_url' &&
                            $key != 'pay_per_job_details' &&
                            $key != 'p_with_main' &&
                            $key != 'is_free_checkout_mini' &&
                            $key != 'process_credit_card' &&
                            $key != 'cc_card_no' &&
                            $key != 'cc_expire_month' &&
                            $key != 'cc_expire_year' &&
                            $key != 'cc_type' &&
                            $key != 'cc_ccv' &&
                            $key != 'sponsor_indeed' &&
                            $key != 'indeedPackage' &&
                            $key != 'indeedPackageCustom' &&
                            $key != 'indeedPhoneNumber' &&
                            $key != 'indeedPackageDays' &&
                            $key != 'minSalary' &&
                            $key != 'maxSalary' &&
                            $key != 'sponsor_this_job'
                        ) { // exclude these values from array
                            if (is_array($value)) {
                                $value = implode(',', $value);
                            }

                            $listing_data[$key] = $value;
                        }
                    }
                    if (!empty($formpost['expiration_date'])) {
                        $expiration_date = DateTime::createFromFormat('m/d/Y', $formpost['expiration_date'])->format('Y-m-d H:i:s');
                        $listing_data['expiration_date'] = $expiration_date;
                    } else {
                        if ($per_job_listing_charge == 0) {
                            $listing_data['expiration_date'] = NULL;
                        }
                    }

                    if (!isset($formpost['organic_feed'])) {
                        $listing_data['organic_feed'] = 0;
                    }

                    $listing_data['JobDescription'] = html_entity_decode($altertextarea);

                    if (!empty($formpost['JobRequirements']) && $formpost['JobRequirements'] != "") {
                        $listing_data['JobRequirements'] = html_entity_decode($formpost['JobRequirements']);
                    }

                    $video_source = $this->input->post('video_source');
                    $video_id = '';

                    if ($video_source != 'no_video') {
                        if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                            $random = generateRandomString(5);
                            $target_file_name = basename($_FILES["upload_video"]["name"]);
                            $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                            $target_dir = "assets/uploaded_videos/";
                            $target_file = $target_dir . $file_name;
                            $filename = $target_dir . $company_id;

                            if (!file_exists($filename)) {
                                mkdir($filename);
                            }

                            if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {
                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                redirect('job_listings/edit_listing', 'refresh');
                            }

                            $video_id = $file_name;
                        } else {
                            $video_id = $this->input->post('yt_vm_video_url');

                            if ($video_source == 'youtube') {
                                $url_prams = array();
                                parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                                if (isset($url_prams['v'])) {
                                    $video_id = $url_prams['v'];
                                } else {
                                    $video_id = '';
                                }
                            } else if ($video_source == 'vimeo') {
                                $video_id = $this->vimeo_get_id($video_id);
                            } else if ($video_source == 'uploaded' && $this->input->post('pre_upload_video_url') != '') {
                                $video_id = $this->input->post('pre_upload_video_url');
                            }
                        }
                    }

                    if ($video_id != '') {
                        $listing_data['YouTube_Video'] = $video_id;
                    } else {
                        $listing_data['YouTube_Video'] = NULL;
                    }

                    $listing_data['video_source'] = $video_source;

                    if ($has_rights) {
                        if (!empty($formpost['listing_status']) && $formpost['listing_status'] != '') {
                            if (intval($formpost['listing_status']) == 1) {
                                if ($myListing['active'] != 1) {
                                    $this->dashboard_model->listing_tracking($id, 'Job Activated');
                                }

                                $listing_data['active'] = 1;
                            } else {
                                if ($myListing['active'] != 0) {
                                    $this->dashboard_model->listing_tracking($id, 'Job Activated');
                                }

                                $this->dashboard_model->listing_tracking($id, 'Job De-Activated');
                                $listing_data['active'] = 0;
                                $listing_data['deactivation_date'] = date('Y-m-d H:i:s');
                            }
                        } else {
                            if ($myListing['active'] != 0) {
                                $this->dashboard_model->listing_tracking($id, 'Job Activated');
                            }

                            $listing_data['active'] = 0;
                            $listing_data['deactivation_date'] = date('Y-m-d H:i:s');
                        }
                    }

                    if (isset($formpost['published_on_career_page']) && !empty($formpost['published_on_career_page']) && $formpost['published_on_career_page'] != '') {
                        if (intval($formpost['published_on_career_page']) == 1) {
                            $listing_data['published_on_career_page'] = 1;
                        } else {
                            $listing_data['published_on_career_page'] = 0;
                        }
                    } else {
                        $listing_data['published_on_career_page'] = 0;
                    }

                    if ($per_job_listing_charge == 1 || $career_site_listings_only  == 1) {
                        $listing_data['published_on_career_page']               = 1;
                    }

                    $insert_record = array();
                    $insert_record['active'] = $listing_data['active'];
                    $insert_record['portal_job_listings_sid'] = $id;
                    $insert_record['edit_date'] = date('Y-m-d H:i:s');
                    $insert_record['edit_by_name'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
                    $insert_record['edit_by_sid'] = $data['session']['employer_detail']['sid'];

                    if ($previous_active == $listing_data['active']) {
                        if ($previous_active == 0) {
                            $insert_record['edit_place'] = 'Simple Edit From Job Details Page. Previously Deactivated';
                        } else {
                            $insert_record['edit_place'] = 'Simple Edit From Job Details Page. Previously Activated';
                        }
                    } else {
                        if ($previous_active == 1) {
                            $insert_record['edit_place'] = 'Simple Edit Plus Deactivated From Job Details Page';
                            $insert_record['deactive_by_name'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
                            $insert_record['deactive_by_sid'] = $data['session']['employer_detail']['sid'];
                            $insert_record['deactive_date'] = date('Y-m-d H:i:s');
                        }

                        if ($previous_active == 0) {
                            $insert_record['edit_place'] = 'Simple Edit Plus Activated From Job Details Page';
                            $insert_record['activation_date'] = date('Y-m-d H:i:s');
                        }
                    }

                    if (!isset($formpost['organic_feed']) && $career_site_listings_only == 1) { // herererere
                        $insert_record['organic_feed'] = 0;
                    } else {
                        $insert_record['organic_feed'] = 1;
                    }

                    $insert_record['Title'] = $listing_data['Title'];
                    $insert_record['company_sid'] = $company_id;
                    $insert_record['company_name'] = $data['session']['company_detail']['CompanyName'];
                    $this->dashboard_model->insert_jobs_records($insert_record);
                    $listing_data['user_sid'] = $company_id;

                    if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') { //uploading image to AWS
                        $uploaded_file_name = upload_file_to_aws('pictures', $company_id, 'listing_logo');

                        if ($uploaded_file_name != 'error') {
                            $listing_data['pictures'] = $uploaded_file_name;
                        }
                    } else {
                        if ($this->input->post('delete_image') == "0") {
                            if ($formpost['select_from_logo'] == $formpost['old_picture']) {
                                $listing_data['pictures'] = $formpost['old_picture'];
                            } else if ($formpost['select_from_logo'] != 'no_logo' && ($formpost['select_from_logo'] != $formpost['old_picture'])) {
                                $listing_data['pictures'] = $formpost['select_from_logo'];
                            } else if ($formpost['select_from_logo'] == 'no_logo') {
                                $listing_data['pictures'] = NULL;
                            }
                        } else {
                            $listing_data['pictures'] = NULL;
                        }
                    }

                    $modifications_list = array();
                    $job_approval_module_status                                 = get_job_approval_module_status($company_id);
                    $employee_approval_module_status                            = 0;

                    if ($formpost['listing_status']) {
                        if ($job_approval_module_status == 0) {
                            $listing_data['approval_status']                        = 'approved';
                            $listing_data['approval_status_by']                     = $logged_in_user_sid;
                            $approval_status_change_datetime                        = date('Y-m-d H:i:s');
                            $listing_data['approval_status_change_datetime']        = $approval_status_change_datetime;

                            if (($data['sponsor_radio'] == 'no' || $myListing['approval_status'] == 'pending') && (isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it') && $per_job_listing_charge == 1) {
                                $listing_data['ppj_activation_date']                = $approval_status_change_datetime;
                            }
                        } else { // check if the creator of the job as rights to approve job
                            $employee_approval_module_status = get_job_approval_module_status($logged_in_user_sid);

                            if ($employee_approval_module_status == 1) { // auto approve job as employee has job approval rights
                                $listing_data['approval_status']                    = 'approved';
                                $listing_data['approval_status_by']                 = $logged_in_user_sid;
                                $approval_status_change_datetime                    = date('Y-m-d H:i:s');
                                $listing_data['approval_status_change_datetime']    = $approval_status_change_datetime;

                                if (($data['sponsor_radio'] == 'no' || $myListing['approval_status'] == 'pending') && (isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it') && $per_job_listing_charge == 1) {
                                    $listing_data['ppj_activation_date']            = $approval_status_change_datetime;
                                }
                            }
                        }
                    }

                    foreach ($listing_data as $cus_key => $cus_value) {
                        if ($cus_value != $myListing[$cus_key]) {
                            $modifications_list[$cus_key] = 'Modified to: (' . $cus_value . ') - From: (' . $myListing[$cus_key] . ')';
                        }
                    }

                    if (!empty($modifications_list)) { // keep track of the modifications at portal_job_listings_modifications
                        $datatoinsert = array();
                        $datatoinsert['job_sid'] = $id;
                        $datatoinsert['company_sid'] = $data['session']['company_detail']['sid'];
                        $datatoinsert['employer_sid'] = $data['session']['employer_detail']['sid'];
                        $datatoinsert['employer_name'] = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
                        $datatoinsert['employer_email'] = $data['session']['employer_detail']['email'];
                        $datatoinsert['modifications_list'] = serialize($modifications_list);
                        $datatoinsert['modified_datetime'] = date('Y-m-d H:i:s');
                        $this->dashboard_model->add_modifications_record($datatoinsert);

                        //send new updated job to remarket
                        $this->sendJobDetailsToRemarket($listing_data, $formpost['sid'], $data['session']['company_detail'], $myListing['JobCategory']);
                    }

                    if ($hasNewModuleAccess) {
                        if (!isset($formpost['sponsor_indeed'])) {
                            $listing_data['indeed_sponsored'] = 0;
                        }
                    }
                    //
                    $listing_data["Salary"] = getSanitizeSalary(
                        $formpost["minSalary"],
                        $formpost["maxSalary"],
                    );

                    $this->dashboard_model->update_listing($formpost['sid'], $listing_data); //Now call dashboard_model function to insert data in DB
                    $this->load->model("Job_sync_api_model");
                    $this->Job_sync_api_model->checkAndAddJob($formpost["sid"]);
                    if ($listing_data["organic_feed"] == 1) {
                        // load the indeed model
                        $this->load->model("Indeed_model", "indeed_model");
                        // $this->indeed_model->updateJobToQueue(
                        //     $formpost['sid'],
                        //     $company_id
                        // );
                    }

                    if ($formpost['listing_status']) {
                        if ($per_job_listing_charge == 1 && $data['sponsor_radio'] == 'no' && (isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it')) {
                            $this->dashboard_model->deduct_product_qty($listing_data['ppj_product_id'], $company_id, $listing_data['ppj_expiry_days']);
                        }
                    }

                    if (isset($formpost['employees']) && !empty($formpost['employees'])) { //Handle Visibility to Employees Start
                        $employeesArray = $formpost['employees'];
                    } else {
                        $employeesArray = array();
                        array_push($employeesArray, $logged_in_user_sid);
                    }
                    // Added on: 05-08-2019
                    // $this->addUpdateXML( $formpost['sid'], $employer_id, false );

                    $this->job_listings_visibility_model->UpdateExistingVisibilityGroup($company_id, $id, $employeesArray); //Handle Visibility to Employees End
                    $this->session->set_flashdata('message', '<b>Success:</b> Job updated successfully');
                    redirect('my_listings', 'location');
                } //else end for form submit success
            } else {
                redirect(base_url('login'), "refresh");
            } //else end for session check fail
        }
    }

    public function clone_listing($id = NULL)
    {
        if ($id == NULL) {
            $this->session->set_flashdata('message', '<b>Error:</b> No job found!');
            redirect('my_listings', 'refresh');
        } else {
            if ($this->session->userdata('logged_in')) {
                $data['session']                                                = $this->session->userdata('logged_in');
                $security_sid                                                   = $data['session']['employer_detail']['sid'];
                $security_details                                               = db_get_access_level_details($security_sid);
                $data['security_details']                                       = $security_details;
                check_access_permissions($security_details, 'my_listings', 'clone_listing'); // Param2: Redirect URL, Param3: Function Name
                $data['title']                                                  = 'Clone Listing';
                $company_id                                                     = $data['session']['company_detail']['sid'];
                $logged_in_user_sid                                             = $data['session']['employer_detail']['sid'];
                $job_listing_template_group                                     = $data['session']['company_detail']['job_listing_template_group'];
                $data['logged_in_user_sid']                                     = $logged_in_user_sid;
                $current_employees                                              = $this->dashboard_model->GetAllUsersNew($company_id);
                $data['current_employees']                                      = $current_employees;

                if (empty($job_listing_template_group)) {
                    $job_listing_template_group                                 = 1;
                }

                $templateGroup                                                  = $this->dashboard_model->GetJobListingTemplateGroupDetail($job_listing_template_group);
                $availableTemplatesIds                                          = null;

                if (!empty($templateGroup[0]['templates'])) {
                    $availableTemplatesIds                                      = unserialize($templateGroup[0]['templates']);
                } else {
                    $availableTemplatesIds                                      = array();
                }

                if ($availableTemplatesIds == null || empty($availableTemplatesIds)) {
                    $availableTemplatesIds                                      = array();
                }

                $templates                                                      = array();

                foreach ($availableTemplatesIds as $templateId) {
                    $template                                                   = $this->dashboard_model->GetJobListingTemplateDetail($templateId);

                    if (!empty($template)) {
                        $templates[]                                            = $template[0];
                    }
                }

                if (intval($job_listing_template_group) == 0) {
                    $data['templates']                                          = array();
                } else {
                    $data['templates']                                          = $templates;
                } // templates code end

                $config = array(
                    array(
                        'field' => 'Title',
                        'label' => 'Title',
                        'rules' => 'required' //|callback_unique_job_title
                    ),
                    array(
                        'field' => 'YouTube_Video',
                        'label' => 'YouTube Video',
                        'rules' => 'xss_clean|trim|callback_validate_youtube'
                    ),
                    array(
                        'field' => 'Vimeo_Video',
                        'label' => 'Vimeo Video',
                        'rules' => 'xss_clean|trim|callback_validate_vimeo'
                    ),
                    array(
                        'field' => 'JobDescription',
                        'label' => 'Job Description',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'Location_country',
                        'label' => 'Country Name',
                        'rules' => 'aplha,required'
                    ),
                    array(
                        'field' => 'Location_state',
                        'label' => 'State Name',
                        'rules' => 'aplha,required'
                    ),
                    array(
                        'field' => 'Location_city',
                        'label' => 'City Name',
                        'rules' => 'aplha,required'
                    ),
                    array(
                        'field' => 'Location_ZipCode',
                        'label' => 'ZipCode',
                        'rules' => 'numeric'
                    ),
                    array(
                        'field' => 'JobCategory[]',
                        'label' => 'Job Category',
                        'rules' => 'required'
                    )
                );

                $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                $this->form_validation->set_rules($config);

                $ppjl_charge                                                    = $this->dashboard_model->get_pay_per_job_status($company_id);
                $career_site_listings_only                                      = $ppjl_charge['career_site_listings_only'];
                $per_job_listing_charge                                         = $ppjl_charge['per_job_listing_charge'];
                $data['per_job_listing_charge']                                 = $per_job_listing_charge;
                $data['career_site_listings_only']                              = $career_site_listings_only;
                $allow_create_job                                               = true;

                if ($per_job_listing_charge == 1) {
                    $allow_create_job                                               = false;
                    $product_type                                                   = 'pay-per-job';
                    $purchasedProducts                                              = $this->dashboard_model->getPurchasedProducts($company_id, $product_type);
                    $data['purchasedProducts']                                      = $purchasedProducts;
                    $i                                                          = 0;
                    $product_ids                                                = NULL;

                    if (!empty($purchasedProducts)) {
                        foreach ($purchasedProducts as $product) {
                            $product_ids[$i]                                    = $product['product_sid'];
                            $i++;
                        }
                    }

                    $notPurchasedProducts                                       = $this->dashboard_model->notPurchasedProducts($product_ids, $product_type);
                    $data['notPurchasedProducts']                               = $notPurchasedProducts;

                    if (!empty($purchasedProducts)) {
                        $allow_create_job                                           = true;
                    }
                }

                if ($this->form_validation->run() == FALSE) { //calling Model function
                    $id                                                         = $this->uri->segment(2);
                    $myListing                                                  = $this->dashboard_model->get_listing($id, $company_id);
                    $visible_to                                                 = $this->job_listings_visibility_model->GetEmployerIds($id);

                    if (!empty($visible_to)) {
                        $data['employeesArray']                                 = $visible_to;
                    } else {
                        $data['employeesArray']                                 = array($logged_in_user_sid);
                    }

                    $salaryBreakDown = breakSalary($myListing["Salary"], $myListing["SalaryType"]);
                    $myListing["minSalary"] = $salaryBreakDown["min"];
                    $myListing["maxSalary"] = $salaryBreakDown["max"];

                    $data['listing']                                            = $myListing;

                    if (empty($data['listing'])) {
                        $this->session->set_flashdata('message', '<b>Error:</b> You are not owner of this job!');
                        redirect('my_listings', 'refresh');
                    }

                    $data_countries                                             = db_get_active_countries();

                    foreach ($data_countries as $value) {
                        $data_states[$value['sid']]                             = db_get_active_states($value['sid']);
                    }

                    $data['active_countries']                                   = $data_countries;
                    $data['active_states']                                      = $data_states;
                    $data_states_encode                                         = htmlentities(json_encode($data_states));
                    $data['states']                                             = $data_states_encode;
                    $assigned_industry                                          = $this->dashboard_model->check_is_assigned_industry($company_id);

                    if ($assigned_industry == false) {
                        $data['data_list']                                      = $this->multi_table_data->getJobCategory(); //job category list from DB
                    } else {
                        $data['data_list']                                      = $this->dashboard_model->get_industry_specific_categories($company_id, $logged_in_user_sid, $assigned_industry);
                    }

                    $job_title_special_chars                                    = $this->dashboard_model->get_special_chars_status($company_id);
                    $data['job_title_special_chars']                            = $job_title_special_chars;
                    $data['screening_questions']                                = $this->multi_table_data->getScreeningQuestionnaires($company_id);  //Questionaier
                    $interview_questionnaires                                   = $this->dashboard_model->get_interview_questionnaires($company_id); //Interview Questionnaires
                    $data['interview_questionnaires']                           = $interview_questionnaires;
                    $all_job_logos                                              = $this->dashboard_model->get_all_job_logos($company_id);
                    $data['all_job_logos']                                      = $all_job_logos;

                    $this->load->view('main/header', $data);
                    $this->load->view('manage_employer/clone_listing_new');
                    $this->load->view('main/footer');
                } else {
                    $formpost                                                   = $this->input->post(NULL, TRUE);
                    $formpost['JobDescription']                                 = $this->input->post('JobDescription', false);
                    $formpost['JobRequirements']                                = $this->input->post('JobRequirements', false);

                    if (!isset($formpost['interview_questionnaire_sid']) || $formpost['interview_questionnaire_sid'] == '') {
                        $formpost['interview_questionnaire_sid'] = 0;
                    }

                    if (!isset($formpost['questionnaire_sid']) || $formpost['questionnaire_sid'] == '') {
                        $formpost['questionnaire_sid'] = 0;
                    }

                    $listing_data                                               = array();

                    if ($per_job_listing_charge == 1) {
                        $per_job_listing_error_flag                             = false;

                        if ((isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it') && isset($formpost['pay_per_job_details']) && $formpost['pay_per_job_details'] != '') { // verify that product is purchased otherwise don't create error
                            $ppj_product_id                                     = $formpost['pay_per_job_details'];

                            if ($ppj_product_id > 0) { //verify that company has stock to purchase the product
                                $productCounter                                 = $this->dashboard_model->checkPurchasedProductQty(array($ppj_product_id), $company_id, 'pay-per-job'); // it is required to check QTY

                                if (!empty($productCounter)) {
                                    $ppj_expiry_days                            = $productCounter[0]['no_of_days'];
                                    $ppj_remaining_qty                          = $productCounter[0]['remaining_qty'];
                                    $ppj_product_name                           = $productCounter[0]['name'];

                                    if ($ppj_remaining_qty > 0) { // allow job creation
                                        $listing_data['ppj_product_id']         = $ppj_product_id;
                                        $listing_data['ppj_expiry_days']        = $ppj_expiry_days;
                                    } else { // Do not have the quatity to publish the job
                                        $per_job_listing_error_flag             = true;
                                    }
                                } else { // product not found error!
                                    $per_job_listing_error_flag                 = true;
                                }
                            } else { // Product not purchased error
                                $per_job_listing_error_flag                     = true;
                            }
                            // Need forced expiration days. No of days from the product will be stored in it. it will check activation date then force activation will be implemented
                        }

                        if ($per_job_listing_error_flag) {
                            $this->session->set_flashdata('message', '<b>Error:</b> Job could not be created, Please try again');
                            redirect('my_listings', 'refresh');
                        }
                    }

                    foreach ($formpost as $key => $value) { //Arranging company detial
                        if (
                            $key != 'old_picture' &&
                            $key != 'delete_image' &&
                            $key != 'YouTube_Video' &&
                            $key != 'Vimeo_Video' &&
                            $key != 'JobDescription' &&
                            $key != 'listing_status' &&
                            $key != 'published_on_career_page' &&
                            $key != 'employees' &&
                            $key != 'visibility_perms' &&
                            $key != 'expiration_date' &&
                            $key != 'select_from_logo' &&
                            $key != 'yt_vm_video_url' &&
                            $key != 'upload_video' &&
                            $key != 'pre_upload_video_url' &&
                            $key != 'pay_per_job_details' &&
                            $key != 'p_with_main' &&
                            $key != 'is_free_checkout_mini' &&
                            $key != 'process_credit_card' &&
                            $key != 'cc_card_no' &&
                            $key != 'cc_expire_month' &&
                            $key != 'cc_expire_year' &&
                            $key != 'cc_type' &&
                            $key != 'cc_ccv' &&
                            $key != 'minSalary' &&
                            $key != 'maxSalary' &&
                            $key != 'sponsor_this_job'
                        ) { // exclude these values from array
                            if (is_array($value)) {
                                $value                                          = implode(',', $value);
                            }

                            $listing_data[$key]                                 = $value;
                        }
                    }

                    if (!empty($formpost['expiration_date'])) {
                        $expiration_date                                        = DateTime::createFromFormat('m/d/Y', $formpost['expiration_date'])->format('Y-m-d H:i:s');
                        $listing_data['expiration_date']                        = $expiration_date;
                    }

                    $listing_data['JobDescription']                             = html_entity_decode($formpost['JobDescription']);

                    if (!empty($formpost['JobRequirements']) && $formpost['JobRequirements'] != "") {
                        $listing_data['JobRequirements']                        = html_entity_decode($formpost['JobRequirements']);
                    }

                    $video_source                                               = $this->input->post('video_source');
                    $video_id                                                   = '';

                    if ($video_source != 'no_video') {
                        if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                            $random = generateRandomString(5);
                            $target_file_name = basename($_FILES["upload_video"]["name"]);
                            $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                            $target_dir = "assets/uploaded_videos/";
                            $target_file = $target_dir . $file_name;
                            $filename = $target_dir . $company_id;

                            if (!file_exists($filename)) {
                                mkdir($filename);
                            }

                            if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {
                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                redirect('job_listings/clone_listing', 'refresh');
                            }

                            $video_id = $file_name;
                        } else {
                            $video_id = $this->input->post('yt_vm_video_url');

                            if ($video_source == 'youtube') {
                                $url_prams = array();
                                parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                                if (isset($url_prams['v'])) {
                                    $video_id = $url_prams['v'];
                                } else {
                                    $video_id = '';
                                }
                            } else if ($video_source == 'vimeo') {
                                $video_id = $this->vimeo_get_id($video_id);
                            } else if ($video_source == 'uploaded' && $this->input->post('pre_upload_video_url') != '') {
                                $video_id = $this->input->post('pre_upload_video_url');
                            }
                        }
                    }

                    if ($video_id != '') {
                        $listing_data['YouTube_Video']                          = $video_id;
                    } else {
                        $listing_data['YouTube_Video']                          = NULL;
                    }

                    $listing_data['video_source']                               = $video_source;

                    if (!empty($formpost['listing_status']) && $formpost['listing_status'] != '') {
                        if (intval($formpost['listing_status']) == 1) {
                            $listing_data['active']                             = 1;
                        } else {
                            $listing_data['active']                             = 0;
                        }
                    } else {
                        $listing_data['active']                                 = 0;
                    }

                    if (isset($formpost['published_on_career_page']) && !empty($formpost['published_on_career_page']) && $formpost['published_on_career_page'] != '') {
                        if (intval($formpost['published_on_career_page']) == 1) {
                            $listing_data['published_on_career_page']           = 1;
                        } else {
                            $listing_data['published_on_career_page']           = 0;
                        }
                    } else {
                        $listing_data['published_on_career_page']               = 0;
                    }

                    if ($per_job_listing_charge == 1 || $career_site_listings_only  == 1) {
                        $listing_data['published_on_career_page']               = 1;
                    }

                    $listing_data['user_sid']                                   = $company_id;

                    if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') { //uploading image to AWS
                        $uploaded_file_name                                     = upload_file_to_aws('pictures', $company_id, 'listing_logo');

                        if ($uploaded_file_name != 'error') {
                            $listing_data['pictures']                           = $uploaded_file_name;
                        }
                    } else {
                        if ($this->input->post('delete_image') == "0") {
                            if ($formpost['select_from_logo'] == $formpost['old_picture']) {
                                $listing_data['pictures']                       = $formpost['old_picture'];
                            } else if ($formpost['select_from_logo'] != 'no_logo' && ($formpost['select_from_logo'] != $formpost['old_picture'])) {
                                $listing_data['pictures']                       = $formpost['select_from_logo'];
                            } else if ($formpost['select_from_logo'] == 'no_logo') {
                                $listing_data['pictures']                       = NULL;
                            }
                        } else {
                            $listing_data['pictures']                           = NULL;
                        }
                    }
                    //Now call dashboard_model function to insert data in DB
                    $listing_data['job_creater_sid']                            = $logged_in_user_sid;
                    $job_approval_module_status                                 = get_job_approval_module_status($company_id);
                    $employee_approval_module_status                            = 0;

                    if ($job_approval_module_status == 0) {
                        $listing_data['approval_status']                        = 'approved';
                        $listing_data['approval_status_by']                     = $logged_in_user_sid;
                        $approval_status_change_datetime                        = date('Y-m-d H:i:s');
                        $listing_data['approval_status_change_datetime']        = $approval_status_change_datetime;
                        $listing_data['activation_date']                        = date('Y-m-d H:i:s');

                        if ((isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it') && $per_job_listing_charge == 1) {
                            $listing_data['ppj_activation_date']                = $approval_status_change_datetime;
                        }
                    } else { // check if the creator of the job as rights to approve job
                        $employee_approval_module_status = get_job_approval_module_status($logged_in_user_sid);

                        if ($employee_approval_module_status == 1) { // auto approve job as employee has job approval rights
                            $listing_data['approval_status']                    = 'approved';
                            $listing_data['approval_status_by']                 = $logged_in_user_sid;
                            $approval_status_change_datetime                    = date('Y-m-d H:i:s');
                            $listing_data['approval_status_change_datetime']    = $approval_status_change_datetime;
                            $listing_data['activation_date']                    = date('Y-m-d H:i:s');

                            if ((isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it') && $per_job_listing_charge == 1) {
                                $listing_data['ppj_activation_date']            = $approval_status_change_datetime;
                            }
                        } else {
                            $listing_data['approval_status']                    = 'pending';
                        }
                    }

                    //
                    $listing_data["Salary"] = getSanitizeSalary(
                        $formpost["minSalary"],
                        $formpost["maxSalary"],
                    );

                    $jobId                                                      = $this->dashboard_model->add_listing($listing_data);
                    $this->load->model("Job_sync_api_model");
                    $this->Job_sync_api_model->checkAndAddJob($jobId);
                    if ($listing_data["organic_feed"] == 1) {
                        // load the indeed model
                        $this->load->model("Indeed_model", "indeed_model");
                        // $this->indeed_model->addJobToQueue(
                        //     $jobId,
                        //     $company_id
                        // );
                    }
                    //send new cloned job to remarket
                    $this->sendJobDetailsToRemarket($listing_data, $jobId, $data['session']['company_detail']);

                    if ($per_job_listing_charge == 1 && (isset($formpost['sponsor_this_job']) && $formpost['sponsor_this_job'] == 'sponsor_it')) {
                        $this->dashboard_model->deduct_product_qty($listing_data['ppj_product_id'], $company_id, $listing_data['ppj_expiry_days']);
                    }

                    if (!empty($formpost['employees'])) { //Handle Visibility to Employees Start
                        $employeesArray                                         = $formpost['employees'];
                    } else {
                        $employeesArray                                         = array();
                        array_push($employeesArray, $logged_in_user_sid);
                    }

                    $this->job_listings_visibility_model->InsertNewVisibilityGroup($company_id, $jobId, $employeesArray); //Handle Visibility to Employees End
                    $this->session->set_flashdata('message', '<b>Success:</b> Job cloned successfully');
                    redirect('my_listings', 'location');
                } //else end for form submit success
            } else {
                redirect(base_url('login'), 'refresh');
            } //else end for session check fail
        }
    }

    public function job_task()
    {
        if ($this->session->userdata('logged_in')) {
            $action = $this->input->post('action');
            $jobId = $this->input->post('sid');
            $trigger = $this->input->post('place');
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $company_ppj_status = $data['session']['company_detail']['per_job_listing_charge'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $jobs_approval_module_status = get_job_approval_module_status($company_id);
            $has_rights = 0;

            if (!is_admin($employer_id) && $jobs_approval_module_status == 1) {
                $employee_approval_module_status = get_job_approval_module_status($employer_id);

                if ($employee_approval_module_status == 1) {
                    $has_rights = 1;
                }
            } else {
                $has_rights = 1;
            }

            $insert_record = array();
            $insert_record['company_sid'] = $company_id;
            $insert_record['company_name'] = $data['session']['company_detail']['CompanyName'];

            if ($has_rights) {
                if ($action == 'delete') {
                    $jobId = explode(',', $jobId);
                    foreach ($jobId as $id) {
                        $this->dashboard_model->add_delete_job_listings($id);
                    }

                    if (is_array($jobId)) { //$this->dashboard_model->delete($jobId);
                        foreach ($jobId as $id) {
                            $this->job_listings_visibility_model->DeleteExistingVisibilityGroup($id);
                        }
                    } else {
                        $this->job_listings_visibility_model->DeleteExistingVisibilityGroup($jobId);
                    }

                    echo 'Selected job(s) deleted.';
                } elseif ($action == 'active') {
                    $this->dashboard_model->active($jobId);
                    // make sure to always have an
                    // array of job ids
                    $newJobIds = is_array($jobId) ? $jobId : [$jobId];
                    // load the indeed model
                    $this->load->model(
                        "Indeed_model",
                        "indeed_model"
                    );
                    // call the cron handler
                    // $this
                    //     ->indeed_model
                    //     ->checkAndActivateJobs(
                    //         $newJobIds,
                    //         $company_id
                    //     );
                    $insert_record['edit_date'] = date('Y-m-d H:i:s');
                    $insert_record['edit_by_name'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
                    $insert_record['edit_by_sid'] = $data['session']['employer_detail']['sid'];

                    if ($trigger == 'dropdown') {
                        $insert_record['edit_place'] = 'Activated From Job Listing Drop Down';
                    }

                    if ($trigger == 'checkbox') {
                        $insert_record['edit_place'] = 'Activated From Job Listing Multiple Checkbox';
                    }

                    $insert_record['active'] = 1;
                    $insert_record['activation_date'] = date('Y-m-d H:i:s');

                    if (is_array($jobId)) {
                        foreach ($jobId as $jid) {
                            $insert_record['portal_job_listings_sid'] = $jid;
                            $this->dashboard_model->insert_jobs_records($insert_record);

                            if (!$company_ppj_status) {
                                $listing_data = array();
                                $listing_data['approval_status'] = 'pending';
                                $listing_data['approval_status_by'] = 0;
                                $listing_data['approval_status_change_datetime'] = '0000-00-00 00:00:00';
                                $this->dashboard_model->update_listing($jid, $listing_data);
                            }
                        }
                    } else {
                        $insert_record['portal_job_listings_sid'] = $jobId;
                        $this->dashboard_model->insert_jobs_records($insert_record);

                        if (!$company_ppj_status) {
                            $listing_data = array();
                            $listing_data['approval_status'] = 'pending';
                            $listing_data['approval_status_by'] = 0;
                            $listing_data['approval_status_change_datetime'] = '0000-00-00 00:00:00';
                            $this->dashboard_model->update_listing($jobId, $listing_data);
                        }
                    }

                    $this->load->model("Job_sync_api_model");
                    $this->Job_sync_api_model->checkAndAddJobs($newJobIds);
                    //send job status to remarket
                    $url = REMARKET_PORTAL_BASE_URL . "/activate_deactivate_jobs/" . REMARKET_PORTAL_KEY;
                    $remarket_listing_data['activated_jobs'] = $jobId;
                    send_settings_to_remarket($url, $remarket_listing_data);

                    echo 'Selected job(s) Activated.';
                } elseif ($action == 'deactive') {
                    $this->dashboard_model->deactive($jobId);
                    // make sure to always have an
                    // array of job ids
                    $newJobIds = is_array($jobId) ? $jobId : [$jobId];
                    // load the indeed model
                    $this->load->model(
                        "Indeed_model",
                        "indeed_model"
                    );
                    // $this->load->model("Job_sync_api_model");
                    // $this->Job_sync_api_model->checkAndAddJobs($newJobIds);
                    // call the cron handler
                    // $this
                    //     ->indeed_model
                    //     ->checkAndDeactivateJobs(
                    //         $newJobIds
                    //     );

                    $insert_record['edit_date'] = date('Y-m-d H:i:s');
                    $insert_record['edit_by_name'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
                    $insert_record['edit_by_sid'] = $data['session']['employer_detail']['sid'];
                    $insert_record['active'] = 0;
                    $insert_record['deactive_by_name'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
                    $insert_record['deactive_by_sid'] = $data['session']['employer_detail']['sid'];
                    $insert_record['deactive_date'] = date('Y-m-d H:i:s');

                    if ($trigger == 'dropdown') {
                        $insert_record['edit_place'] = 'Deactivated From Job Listing Drop Down';
                    }

                    if ($trigger == 'checkbox') {
                        $insert_record['edit_place'] = 'Deactivated From Job Listing Multiple Checkbox';
                    }

                    if (is_array($jobId)) {
                        foreach ($jobId as $jid) {
                            $insert_record['portal_job_listings_sid'] = $jid;
                            $this->dashboard_model->insert_jobs_records($insert_record);

                            if (!$company_ppj_status) {
                                $listing_data = array();
                                $listing_data['approval_status'] = 'pending';
                                $listing_data['approval_status_by'] = 0;
                                $listing_data['approval_status_change_datetime'] = '0000-00-00 00:00:00';
                                $this->dashboard_model->update_listing($jid, $listing_data);
                            }
                        }
                    } else {
                        $insert_record['portal_job_listings_sid'] = $jobId;
                        $this->dashboard_model->insert_jobs_records($insert_record);

                        if (!$company_ppj_status) {
                            $listing_data = array();
                            $listing_data['approval_status'] = 'pending';
                            $listing_data['approval_status_by'] = 0;
                            $listing_data['approval_status_change_datetime'] = '0000-00-00 00:00:00';
                            $this->dashboard_model->update_listing($jobId, $listing_data);
                        }
                    }
                    $this->load->model("Job_sync_api_model");
                    $this->Job_sync_api_model->checkAndAddJobs($newJobIds);
                    //send job status to remarket
                    $url = REMARKET_PORTAL_BASE_URL . "/activate_deactivate_jobs/" . REMARKET_PORTAL_KEY;
                    $remarket_listing_data['deactivated_jobs'] = $jobId;
                    send_settings_to_remarket($url, $remarket_listing_data);

                    echo 'Selected job(s) deactivated.';
                }
            } else { // set flash message
                echo 'customerror';
                $this->session->set_flashdata('message', '<b>Error:</b> You are not authorized to perform the action. Please contact your company admin');
            }

            if ($action == 'delete_img') {
                $this->dashboard_model->delete_img($jobId);
            }
        }
    }

    public function unique_job_title()
    {
        if ($this->session->userdata('logged_in')) {
            if ($this->input->post('Title')) {
                $jobTitle = $this->input->post('Title');
                $data['session'] = $this->session->userdata('logged_in');
                $companyId = $data["session"]["company_detail"]["sid"];
                $result = $this->dashboard_model->isUniqueJobTitle($jobTitle, $companyId);

                if ($result > 0) {
                    $this->form_validation->set_message('unique_job_title', 'Job with same title already exits, Please use unique listing name.');
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        }
    }

    public function validate_youtube($str)
    {
        if ($this->session->userdata('logged_in')) {
            if ($str != "") {
                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $str, $matches);
                if (!isset($matches[0])) { //if validation not passed
                    $this->form_validation->set_message('validate_youtube', 'Invalid youtube video url.');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Youtube video URL');
                    return FALSE;
                } else { //if validation passed
                    return TRUE;
                }
            } else {
                return true;
            }
        }
    }

    public function validate_vimeo($str)
    {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return TRUE;
                    } else {
                        $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                        $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                        return FALSE;
                    }
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    return FALSE;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    return FALSE;
                }
            }
        } else {
            return true;
        }
    }

    public function vimeo_get_id($str)
    {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return $response['video_id'];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return $response['video_id'];
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    public function validate_vimeo_url($str)
    {
        if ($str != "") {
            $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
            $response = @file_get_contents($api_url);

            if (!empty($response)) {
                $response = json_decode($response, true);

                if (isset($response['video_id'])) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL'); //hererere
                    return FALSE;
                }
            } else {
                $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function validate_vimeo_curl($str)
    {
        if ($str != "") {
            $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
            $cSession = curl_init();
            curl_setopt($cSession, CURLOPT_URL, $api_url);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_HEADER, false);
            $response = curl_exec($cSession);
            curl_close($cSession);
            $response = json_decode($response, true); //$response = @file_get_contents($api_url);

            if (isset($response['video_id'])) {
                return TRUE;
            } else {
                $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL'); //hererere
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    function add_listing_advertise($jobId = NULL)
    { // pay per job modifications at advertise level // Hassan Working Area
        if ($this->session->has_userdata('logged_in')) { //cheking the user is login
            if ($jobId != NULL) { // checking the job id Exists
                $data['session']                                                = $this->session->userdata('logged_in');
                $security_sid                                                   = $data['session']['employer_detail']['sid'];
                $security_details                                               = db_get_access_level_details($security_sid);
                $data['security_details']                                       = $security_details;
                check_access_permissions($security_details, 'my_listings', 'add_listing_advertise'); // Param2: Redirect URL, Param3: Function Name
                $company_id                                                     = $data['session']['company_detail']['sid'];
                $employer_sid                                                   = $data['session']['employer_detail']['sid'];
                $jobCheck                                                       = $this->dashboard_model->checkJobId($company_id, $jobId);
                $ppjl_charge                                                    = $this->dashboard_model->get_pay_per_job_status($company_id);
                $career_site_listings_only                                      = $ppjl_charge['career_site_listings_only'];
                $per_job_listing_charge                                         = $ppjl_charge['per_job_listing_charge'];
                $data['per_job_listing_charge']                                 = $per_job_listing_charge;
                $data['career_site_listings_only']                              = $career_site_listings_only;

                if ($per_job_listing_charge == 1 && $career_site_listings_only == 0) {
                    $enable_advertise                                           = TRUE;
                    $product_type                                                = 'pay-per-job';
                } else if ($per_job_listing_charge == 0 && $career_site_listings_only == 0) {
                    $enable_advertise                                           = TRUE;
                    $product_type                                               = 'job-board';
                } else {
                    $enable_advertise                                           = FALSE;
                }

                $data['enable_advertise']                                       = false;

                if ($jobCheck == 1 && $enable_advertise) { //cart checkout functionality starts
                    $formpost = $this->input->post(NULL, TRUE);

                    if (isset($formpost['action']) && $formpost['action'] == 'cart_checkout') { //inserting data in Order Table Starts
                        $orderData['total']                                     = $formpost['total'];
                        $orderData['order_status']                              = 'paid';
                        $orderData['employer_sid']                              = $employer_sid;
                        $orderData['purchased_date']                            = date('Y-m-d H:i:s');
                        $orderData['company_sid']                               = $company_id;
                        $product_ids                                            = $formpost['product_id'];
                        $jobData['job_sid']                                     = $jobId;
                        $jobData['employer_sid']                                = $employer_sid;
                        $jobData['purchased_date']                              = date('Y-m-d H:i:s');

                        foreach ($product_ids as $productId) {
                            $result = $this->dashboard_model->productDetail($productId);
                            $orderProductData['product_sid'] = $productId;
                            $orderProductData['product_qty'] = $result[0]['number_of_postings'];
                            $orderProductData['product_remaining_qty'] = $result[0]['number_of_postings'] - 1;
                            $orderProductData['product_price'] = $result[0]['price'];
                            $orderProductData['product_total'] = $result[0]['price'];
                            $orderProductData['company_sid'] = $company_id;
                            $jobData['product_sid'] = $productId;
                        }
                    }

                    $data['title']                                              = 'Add listing advertise';
                    $data['job_sid']                                            = $jobId;
                    $jobResult                                                  = $this->dashboard_model->getJobDetail($jobId);
                    $data['jobDetail']                                          = $jobResult[0];
                    $data['purchasedProducts']                                  = $this->dashboard_model->getPurchasedProducts($company_id, $product_type);
                    //checking if job is already being posted on a particular job board Starts
                    $data['activeProductsOnJob']                                = $this->dashboard_model->active_products($jobId);
                    //checking if job is already being posted on a particular job board End
                    //Check Products Already in Queue
                    $products_pending_approval                                  = $this->dashboard_model->get_feed_activation_requests($company_id, $jobId);
                    $pending_approval_products                                  = array();

                    foreach ($products_pending_approval as $product) {
                        $product_brand                                          = $product['product_brand'];
                        $productIdOfSameBrands                                  = $this->dashboard_model->getProductsOfSameBrand($product_brand);  //getting all products of same barnd

                        foreach ($productIdOfSameBrands as $sameBrand) {
                            $pending_approval_products[]                        = $sameBrand['sid'];
                        }
                    }

                    $data['pending_approval_products']                          = $pending_approval_products;
                    $i                                                          = 0;
                    $purchasedProductArray[$i]                                  = '';

                    foreach ($data['activeProductsOnJob'] as $product) {
                        $productBrand                                           = $this->dashboard_model->getProductBrand($product['product_sid']); //getting product brand
                        $productIdOfSameBrands                                  = $this->dashboard_model->getProductsOfSameBrand($productBrand);  //getting all products of same barnd

                        foreach ($productIdOfSameBrands as $sameProductBrand) {
                            $purchasedProductArray[$i]                          = $sameProductBrand['sid'];
                            $i++;
                        }
                    }

                    $data['purchasedProductArray']                              = $purchasedProductArray;
                    $i                                                          = 0;
                    $product_ids                                                = NULL;

                    if (!empty($data['purchasedProducts'])) {
                        foreach ($data['purchasedProducts'] as $product) {
                            $product_ids[$i]                                    = $product['product_sid'];
                            $i++;
                        }
                    }

                    $data['notPurchasedProducts']                               = $this->dashboard_model->notPurchasedProducts($product_ids, $product_type);
                    $data['job_sid']                                            = $jobId;
                    $data['employer_sid']                                       = $employer_sid;
                    $this->load->view('main/header', $data);
                    $this->load->view('manage_employer/add_listing_advertise');
                    $this->load->view('main/footer');
                } else { //Job doesnt owned by this company
                    $this->session->set_flashdata('message', '<b>Error:</b> You are not authorized to advertise this job.');
                    redirect('my_listings/all');
                }
            } else { //No job id given
                $this->session->set_flashdata('message', '<b>Error:</b> Please select a valid job to advertise.');
                redirect('my_listings/all');
            }
        } else {
            redirect(base_url('login'));
        } //else end for session check fail
    }

    function add_listing_share($jobId = NULL)
    {
        if (!$this->session->has_userdata('logged_in')) { //cheking the user is 
            return redirect(base_url('login'));
        }
        //
        if ($jobId == NULL) { // checking the job id Exists
            $this->session->set_flashdata('message', '<b>Error:</b> Please select a valid job to advertise.');
            return redirect('add_listing');
        }
        //
        $data['session'] = $this->session->userdata('logged_in');
        $ems_status = $data['session']['company_detail']['ems_status'];
        //
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'my_listings', 'add_listing_share'); // Param2: Redirect URL, Param3: Function Name
        $company_id = $data["session"]["company_detail"]["sid"];
        $employer_sid = $data["session"]["employer_detail"]["sid"];
        $jobCheck = $this->dashboard_model->checkJobId($company_id, $jobId);
        $company_name = $data['session']['company_detail']['CompanyName'];
        $sub_domain_url = db_get_sub_domain($company_id);
        $portal_job_url = STORE_PROTOCOL . $sub_domain_url . '/job_details/' . $jobId;

        if ($jobCheck != 1) { //getting job detials
            $this->session->set_flashdata('message', '<b>Error:</b> You are not authorized to advertise this job.');
            return redirect('add_listing');
        }
        $jobData = $this->dashboard_model->getJobDetailWithPortalDetail($jobId);
        $jobDetail = $this->dashboard_model->getJobDetail($jobId);
        $btn_facebook = '';
        $btn_google = '';
        $btn_twitter = '';
        $btn_linkedin = '';
        $btn_job_link = '';
        $links = '';
        $jobAd = '';
        $email_header_footer = message_header_footer($company_id, ucwords($company_name));

        if (!empty($jobDetail)) {
            $jobDetail = $jobDetail[0];
            $sub_domain_url = strpos(current_url(), 'localhost') ? 'localhost/ahr/manage_portal' : db_get_sub_domain($company_id);
            $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($portal_job_url) . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-2.png"></a>';
            $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($jobDetail['Title']) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-3.png"></a>';
            $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&amp;title=' . urlencode($jobDetail['Title']) . '&amp;summary=' . urlencode($jobDetail['Title']) . '&amp;source=' . urlencode(base_url('/job_details/' . $jobDetail['sid'])) . '"><img alt="" src="' . STORE_PROTOCOL . $sub_domain_url . '/assets/theme-1/images/social-4.png"></a>';
            $btn_job_link = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $portal_job_url . '">' . ucwords($jobDetail['Title']) . '</a>';
            $jobAd .= '<div style="float:left; width: 100%; margin-bottom:20px; border-radius: 4px; border: 1px solid #d8d8d8; background-color: white; padding: 20px; opacity: 0.75;">';
            $jobAd .= '<h3><strong>' . ucwords($jobDetail['Title']) . '</strong></h3>';
            $jobAd .= '<h3>' . 'Job Description' . '</h3>';
            $jobAd .= '<p style="word-wrap: break-word;">' . ucwords($jobDetail['JobDescription']) . '</p>';
            $jobAd .= '<h3>' . 'Job Requirements' . '</h3>';
            $jobAd .= '<p style="word-wrap: break-word;">' . ucwords($jobDetail['JobRequirements']) . '</p>';
            $jobAd .= '</div><hr />';
            $links .= '<ul style="float:left; width:100%; padding:0; list-style: none">';
            $links .= '<li style="float: left; margin-right: 10px;">' . $btn_google . '</li>';
            $links .= '<li style="float: left; margin-right: 10px;">' . $btn_facebook . '</li>';
            $links .= '<li style="float: left; margin-right: 10px;">' . $btn_linkedin . '</li>';
            $links .= '<li style="float: left; margin-right: 10px;">' . $btn_twitter . '</li>';
            $links .= '</ul><hr />';
            $data['share_links'] = $links;
        }

        if ($_POST) {
            // _e($_POST,true,true);
            $performAction = $this->input->post('perform_action');

            if ($performAction == 'email_job_info_to_users') {

                $this->form_validation->set_rules('employees[]', 'Employees', 'trim');
            } elseif ($performAction == 'email_job') {
                $this->form_validation->set_rules('email_address', 'Email Address', 'valid_email|required|trim');
                $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim');
            }
        }
        //
        $data['ems_status'] = $ems_status;
        //
        if ($this->form_validation->run() == false) {
            $data['jobDetail'] = $jobData[0];
            $data['title'] = "Add listing share";
            $data['job_sid'] = $jobId;
            $data['active_users'] = $this->dashboard_model->GetAllActiveUsers($company_id);
            $data['departments'] = $this->hr_documents_management_model->getDepartments($company_id);
            $data['teams'] = $this->hr_documents_management_model->getTeams($company_id, $data['departments']);

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/add_listing_share');
            $this->load->view('main/footer');
        } else {


            $performAction = $this->input->post('perform_action');
            //
            $selectedUsers = array();
            //  
            if ($this->input->post('employees')) {
                $selectedUsers = $this->input->post('employees');
            }
            // Only execute when EMS is enabled
            if ($ems_status == 1) {
                $selected_departments = $this->input->post('selected_departments');
                $selected_teams = $this->input->post('selected_teams');
                //
                if (!empty($selected_departments)) {
                    $s = $this->hr_documents_management_model->getEmployeesFromDepartment($selected_departments, $company_id);
                    //
                    if (!empty($s)) {
                        $selectedUsers = array_merge($selectedUsers, $s);
                    }
                }
                //
                if (!empty($selected_teams)) {
                    $s = $this->hr_documents_management_model->getEmployeesFromTeams($selected_teams, $company_id);
                    //
                    if (!empty($s)) {
                        $selectedUsers = array_merge($selectedUsers, $s);
                    }
                }
            }

            //
            if (empty($selectedUsers) && $performAction == 'email_job_info_to_users') {
                $this->session->set_flashdata('message', '<b>Error:</b> Please select at least one employee.');
                redirect('add_listing_share/' . $jobId);
            }
            //
            $selectedUsers = array_unique($selectedUsers);
            $usersInformation = array();

            if (!empty($selectedUsers) && $performAction == 'email_job_info_to_users') {
                foreach ($selectedUsers as $selectedUser) {
                    $userInfo = $this->dashboard_model->GetSingleActiveUser($company_id, $selectedUser);
                    if (!empty($userInfo)) {
                        $usersInformation[] = $userInfo[0];
                    }
                }

                $insert_data = array();


                foreach ($usersInformation as $userInformation) {
                    $email = $userInformation['email'];
                    $userFullName = $userInformation['first_name'] . ' ' . $userInformation['last_name'];
                    $replacement_array = array();
                    $replacement_array['employee-name'] = ucwords($userFullName);
                    $replacement_array['company-name'] = ucwords($company_name);
                    $replacement_array['job-link'] = $btn_job_link;
                    $replacement_array['job-ad'] = $jobAd;
                    $replacement_array['share-links'] = $links;
                    $replacement_array['job-title'] = $jobDetail['Title'];
                    $insert_data['coworker_sid'] = $userInformation['sid'];
                    $insert_data['company_sid'] = $company_id;
                    $insert_data['referral_sid'] = $employer_sid;
                    $insert_data['referral_name'] = ucwords($data["session"]["employer_detail"]['first_name'] . ' ' . $data["session"]["employer_detail"]['last_name']);
                    $insert_data['referral_email'] = ucwords($data["session"]["employer_detail"]['email']);
                    $insert_data['date_time'] = date('Y-m-d H:i:s');
                    $insert_data['type'] = 'coworker';
                    $insert_data['job_sid'] = $jobId;
                    $this->dashboard_model->insert_share_record($insert_data);
                    log_and_send_templated_email(JOB_LISTING_SHARE_TO_EMPLOYEES, $email, $replacement_array, $email_header_footer);
                }

                $this->session->set_flashdata('message', '<b>Notification: ' . $jobDetail['Title'] . ' - ' . 'has been shared with ' . count($selectedUsers) . ' users!' . ' </b>');
                redirect('my_listings', 'refresh');
            } elseif ($performAction == 'email_job') {
                // die('emailsfsdf');
                $email = $this->input->post('email_address');
                $userFullName = $this->input->post('full_name');
                $replacement_array = array();
                $replacement_array['employee-name'] = ucwords($userFullName);
                $replacement_array['company-name'] = ucwords($company_name);
                $replacement_array['job-link'] = $btn_job_link;
                $replacement_array['job-ad'] = $jobAd;
                $replacement_array['share-links'] = $links;
                $replacement_array['job-title'] = $jobDetail['Title'];
                $insert_data['company_sid'] = $company_id;
                $insert_data['referral_sid'] = $employer_sid;
                $insert_data['share_name'] = ucwords($userFullName);
                $insert_data['share_email'] = $email;
                $insert_data['referral_name'] = ucwords($data["session"]["employer_detail"]['first_name'] . ' ' . $data["session"]["employer_detail"]['last_name']);
                $insert_data['referral_email'] = ucwords($data["session"]["employer_detail"]['email']);
                $insert_data['date_time'] = date('Y-m-d H:i:s');
                $insert_data['type'] = 'via_email';
                $insert_data['job_sid'] = $jobId;
                $this->dashboard_model->insert_share_record($insert_data);
                log_and_send_templated_email(JOB_LISTING_SHARE_TO_EMAIL_ADDRESS, $email, $replacement_array, $email_header_footer);
                $this->session->set_flashdata('message', '<b>Notification: ' . $jobDetail['Title'] . ' - ' . 'has been shared with ' . ucwords($userFullName) . '!' . ' </b>');
                redirect('my_listings', 'refresh');
            }
        }
    }

    function preview_listing($sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            //echo file_get_contents('http://automotosocial.com/display-job/9399/');
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['title'] = 'Preivew Listing';
            $company_id = $data['session']['company_detail']['sid'];
            $logged_in_user_sid = $data['session']['employer_detail']['sid'];
            $data['logged_in_user_sid'] = $logged_in_user_sid;
            $job_details = $this->dashboard_model->get_listing($sid, $company_id);

            if (!empty($job_details)) {
                $career_website = $this->dashboard_model->get_career_website($company_id);
                $theme_name = $this->dashboard_model->get_active_theme_name($company_id);

                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                    $job_url = $career_website['sub_domain'] . '/job_details/' . $sid;
                    $job_url = 'https://devsupport.automotohr.com/preview_job/8949/';
                } else {
                    $job_url = 'https://' . $career_website['sub_domain'] . '/preview_job/' . $sid;
                }

                $contents = '';
                $data['job_url'] = $job_url;
                $data['theme_name'] = $theme_name;
                $preview_link = base_url('preview_listing_iframe') . '/' . $sid;
                $data['preview_link'] = $preview_link;

                if ($theme_name == 'theme-4') {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $job_url);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $contents = curl_exec($ch);

                    if (curl_errno($ch)) {
                        echo curl_error($ch);
                        echo "\n<br />";
                        $contents = '';
                    } else {
                        curl_close($ch);
                    }

                    if (!is_string($contents) || !strlen($contents)) {
                        if ($_SERVER['HTTP_HOST'] == 'localhost') {
                            $contents = file_get_contents('https://devsupport.automotohr.com/preview_job/8949/');
                        } else {
                            $contents = 'Preview Not Available';
                        }
                    }
                }

                $data['career_website'] = $career_website;
                $data['preview_content'] = $contents;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/preview_listing');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', '<strong>Error</strong> Preview not available');
                redirect('my_listings', 'refresh');
            }
        }
    }

    function preview_listing_iframe($sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            //echo file_get_contents('http://automotosocial.com/display-job/9399/');
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['title'] = 'Preivew Listing';
            $company_id = $data['session']['company_detail']['sid'];
            $logged_in_user_sid = $data['session']['employer_detail']['sid'];
            $data['logged_in_user_sid'] = $logged_in_user_sid;
            $job_details = $this->dashboard_model->get_listing($sid, $company_id);

            if (!empty($job_details)) {
                $career_website = $this->dashboard_model->get_career_website($company_id);

                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                    $job_url = $career_website['sub_domain'] . '/job_details/' . $sid;
                    $job_url = 'https://devsupport.automotohr.com/preview_job/8949/';
                } else {
                    $job_url = 'https://' . $career_website['sub_domain'] . '/preview_job/' . $sid;
                }

                $data['career_website'] = $career_website;
                $data['job_url'] = $job_url;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $job_url);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $contents = curl_exec($ch);

                if (curl_errno($ch)) {
                    echo curl_error($ch);
                    echo "\n<br />";
                    $contents = '';
                } else {
                    curl_close($ch);
                }

                if (!is_string($contents) || !strlen($contents)) {

                    if ($_SERVER['HTTP_HOST'] == 'localhost') {
                        $contents = file_get_contents('https://devsupport.automotohr.com/preview_job/8949/');
                    } else {
                        $contents = 'Preview Not Available';
                    }
                }

                $data['preview_content'] = $contents;
                $this->load->view('manage_employer/preview_listing_iframe', $data);
            } else {
                $this->session->set_flashdata('message', '<strong>Error</strong> Preview not available');
                redirect('my_listings', 'refresh');
            }
        }
    }

    private function log_and_send_email_with_attachment($from, $to, $subject, $body, $senderName, $file_path)
    {
        $CI = &get_instance();

        if (base_url() == STAGING_SERVER_URL) {
            $emailData = array(
                'date' => date('Y-m-d H:i:s'),
                'subject' => $subject,
                'email' => $to,
                'message' => $body,
                'username' => $senderName,
            );

            save_email_log_common($emailData);
        } else {
            $emailData = array(
                'date' => date('Y-m-d H:i:s'),
                'subject' => $subject,
                'email' => $to,
                'message' => $body,
                'username' => $senderName,
            );

            save_email_log_common($emailData);
            sendMailWithAttachmentRealPath($from, $to, $subject, $body, $senderName, $file_path);
        }
    }

    private function send_email_notification($event_sid, $is_update = false)
    {
        $session = $this->session->userdata('logged_in');
        $company_sid = $session["company_detail"]["sid"];
        $company_name = $session["company_detail"]["CompanyName"];
        $event = $this->dashboard_model->get_event_details($event_sid);
        $participants = $this->dashboard_model->get_event_participants($event_sid);
        $message_hf = message_header_footer($company_sid, $company_name);
        $destination = APPPATH . '../assets/ics_files/';
        $filePath = generate_ics_file_for_event($destination, $event_sid, true);

        foreach ($participants as $participant) {
            $to_name = ucwords($participant['first_name'] . ' ' . $participant['last_name']);
            $to_email = $participant['email'];
            $from_name = STORE_NAME . ' - Events';
            $from_email = FROM_EMAIL_EVENTS;

            if (!empty($event)) {
                $event_status = $event['event_status'];
                if ($event_status == 'cancelled') {
                    //Cancelled Event
                    //This is Already Handled in Event Cancellation Code @ line 1393
                } else {
                    if ($is_update == false) { //Create Event
                        $subject = ucfirst($event['category']) . " schedule notification";
                    } else { //Update Event
                        $subject = ucfirst($event['category']) . " schedule update notification";
                    }
                }
            }
        }
    }

    function alpha_dash_space($str)
    {
        if ($str != "") {
            if (!preg_match("/^([-0-9])+$/i", $str)) {
                $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain numeric characters and dashes.');
                return FALSE;
            } else {
                return TRUE;
            }
        } else
            return TRUE;
    }

    function save_jobs_to_feed()
    {
        if ($this->input->post()) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $formpost = $this->input->post(NULL, TRUE);

            foreach ($formpost['product_sid'] as $key => $productIdWithDay) {
                $explodedArray = explode(",", $productIdWithDay);
                $formpost['product_sid'][$key] = $explodedArray[0];
                $formpost['no_of_days'][$key] = $explodedArray[1];
            }

            $product_type = 'job-board';

            if (!empty($formpost['product_sid'])) {
                $productCounter = $this->dashboard_model->checkPurchasedProductQty($formpost['product_sid'], $company_id, $product_type);
                //Start=>> checking that the applied products still have counter greater than 1?
                foreach ($productCounter as $product) {
                    if ($product['remaining_qty'] <= 0) {
                        echo "error";
                        exit;
                    }
                }
                //End=>> checking that the applied products still have counter greater than 1?
                $jobData['job_sid'] = $formpost['job_sid'];
                $jobData['employer_sid'] = $formpost['employer_sid'];
                $jobData['purchased_date'] = date('Y-m-d H:i:s');

                foreach ($formpost['product_sid'] as $key => $productId) {
                    $no_of_days = $formpost['no_of_days'][$key];
                    $jobData['product_sid'] = $productId;
                    //                    $invoice_price = $this->dashboard_model->get_product_budget($productId, $company_id, $no_of_days);
                    $result = $this->dashboard_model->productDetail($productId);
                    $jobData['budget'] = $result[0]['price'];
                    $jobData['company_sid'] = $company_id;
                    //New Scenario to Set the Expiry Date from Super Admin Upon Activation instead store Number of Days
                    //$jobData['expiry_date'] = date('Y-m-d H:i:s', strtotime("+" . $no_of_days . " days"));
                    $jobData['no_of_days'] = $no_of_days;
                    $this->dashboard_model->insertJobFeed($jobData);
                    //deduct purchased product from order table
                    $this->dashboard_model->deduct_product_qty($productId, $company_id, $no_of_days);
                    //New Job Products Tracking
                    $this->dashboard_model->mark_product_as_used($productId, $company_id, $employer_id, $jobData['job_sid']);
                }
                echo "success";
            } else {
                echo "error";
            }
        }
    }

    function pay_per_job()
    {
        if ($this->session->has_userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'add_listing'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $logged_in_user_sid = $data['session']['employer_detail']['sid'];
            $job_listing_template_group = $data['session']['company_detail']['job_listing_template_group'];
            $career_site_listings_only = $data['session']['company_detail']['career_site_listings_only'];
            $per_job_listing_charge = $data['session']['company_detail']['per_job_listing_charge'];
            $product_type = 'pay-per-job';
            $data['title'] = 'Add listing';
            $purchasedProducts = $this->dashboard_model->getPurchasedProducts($company_id, $product_type);

            echo '<pre>';
            print_r($purchasedProducts);
            $data['purchasedProducts'] = $purchasedProducts;
            $i = 0;
            $product_ids = NULL;

            if (!empty($data['purchasedProducts'])) {
                foreach ($data['purchasedProducts'] as $product) {
                    $product_ids[$i] = $product['product_sid'];
                    $i++;
                }
            }

            echo '<hr>';
            print_r($product_ids);

            $notPurchasedProducts = $this->dashboard_model->notPurchasedProducts($product_ids, $product_type);
            $data['notPurchasedProducts'] = $notPurchasedProducts;

            echo '<hr>';
            print_r($notPurchasedProducts);
            exit;
            // ***************************************************\\\\\\\\\\\\\////////////////////////

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/pay_per_click_not_purchased_products');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    /**
     * Create or update xml
     * Created on: 05-08-2019
     *
     * @param $jobSid      Integer
     * @param $employeeId  Integer
     * @param $addIt       Bool     Optional
     * Default is 'true'
     *
     * @return VOID
     */
    function addUpdateXML($jobSid, $employeeId, $addIt = true)
    {
        $formpost = $this->input->post(NULL, TRUE);
        $deleteXmlJob = FALSE;
        // Organic is 0 then delete job row
        if (
            (int)$formpost['organic_feed'] === 0 ||
            !$this->job_listings_visibility_model->isMainCompany($jobSid)
        ) $deleteXmlJob = TRUE;
        //
        $uid = $jobSid;
        $companySid = $this->job_listings_visibility_model->getCompanyIdByJobSid($jobSid);
        // Check for Approval Job Management check
        if ((int)$this->job_listings_visibility_model->isApprovalManagementActive($companySid) === 1 && (int)$this->job_listings_visibility_model->isJobApproved($jobSid) != 1) $deleteXmlJob = TRUE;
        // Delete the job from XML
        if ($deleteXmlJob) {
            $this->job_listings_visibility_model->deleteXMlJobById($jobSid);
            return;
        }
        //
        $formpost['publish_date'] = $this->job_listings_visibility_model->getJobColumnById($jobSid, 'activation_date');
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
        $this->indeedOrganicDB($formpost, $uid, $companySid, $companyPortal, $jobSid, $employeeId, $addIt);
        $this->zipRecruiterOrganicDB($formpost, $uid, $companySid, $companyPortal, $jobSid, $employeeId, $addIt);
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
     * @param $employeeId       Integer
     * @param $addIt            Bool
     *
     * @return VOID
     */
    private function indeedOrganicDB(
        $formpost,
        $uid,
        $companySid,
        $companyPortal,
        $jobSid,
        $employeeId,
        $addIt
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
            $insertDataArray = array('job_sid' => $jobSid, 'company_sid' => $companySid, 'is_indeed_job' => 1);
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
     * @param $employeeId       Integer
     * @param $addIt            Bool
     *
     * @return VOID
     */
    private function zipRecruiterOrganicDB(
        $formpost,
        $uid,
        $companySid,
        $companyPortal,
        $jobSid,
        $employeeId,
        $addIt
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
            $insertDataArray = array('job_sid' => $jobSid, 'company_sid' => $companySid, 'is_ziprecruiter_job' => 1);
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

    //
    function resp()
    {
        header('Content-type: application/json');
        echo json_encode($this->res);
        exit(0);
    }
    //
    function handler()
    {
        //
        if (!$this->input->is_ajax_request()) $this->resp();
        //
        $formpost = $this->input->post(NULL, TRUE);
        //
        if (!sizeof($formpost)) $this->resp();
        if (!isset($formpost['action'])) $this->resp();

        // _e($formpost, true);
        //
        switch (strtolower($formpost['action'])) {
            case 'add_job_budget':
                $insertSid = $this->indeed_model->insertJobBudget($formpost);
                //
                if (!$insertSid) {
                    $this->res['Response'] = 'Oops! Something went wrong while sponsoring the job with Indeed.';
                    $this->resp();
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Job has been sent to indeed.';
                $this->resp();
                break;
            case 'edit_job_budget':
                // Check the budget
                $budgetExists = $this->indeed_model->isBudgetExists($formpost);
                //
                if (!$budgetExists) {
                    $this->res['Response'] = 'Oops! Failed to verify budget details.';
                    $this->resp();
                }
                //
                if ($formpost['budget'] < $budgetExists) {
                    $this->res['Response'] = 'Oops! New budget can not be less than old budget ($' . ($budgetExists) . ').';
                    $this->resp();
                }
                $this->indeed_model->updateBudget($formpost);
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Indeed sponsor details are updated.';
                $this->resp();
                break;
        }

        $this->resp();
    }


    private function checkAndUpdateIndeedBudget($jobSid)
    {
        return $this->indeed_model->getJobBudgetAndExpireOldBudget($jobSid);
    }
    private function sendJobDetailsToRemarket($listing_data, $jobId, $company_details, $oldCategories = NULL)
    {
        $url = REMARKET_PORTAL_BASE_URL . "/job_listing/" . REMARKET_PORTAL_KEY;
        $remarket_listing_data = $listing_data;
        $remarket_listing_data['sid'] = $jobId;
        $sub_domain = $this->dashboard_model->get_portal_detail($company_details['sid']);

        $remarket_listing_data['sub_domain'] = isset($sub_domain['sub_domain']) ? $sub_domain['sub_domain'] : '';
        $remarket_listing_data['company_name'] = $company_details['CompanyName'];
        if (($oldCategories != NULL && $oldCategories != $remarket_listing_data['JobCategory']) || $oldCategories == NULL) {
            $categories_ids = explode(",", $remarket_listing_data['JobCategory']);
            $remarket_listing_data['categories_names'] = $this->multi_table_data->getJobCategoriesByIds($categories_ids);
        }
        send_settings_to_remarket($url, $remarket_listing_data);
    }
}
