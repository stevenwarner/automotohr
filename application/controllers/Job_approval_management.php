<?php defined('BASEPATH') or exit('No direct script access allowed');

class Job_approval_management extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('job_approval_rights_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            // Check Access Permission is disabled as any employee can have access permission to approve job
            //check_access_permissions($security_details, 'dashboard', 'approval_rights_management'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $company_has_job_approval_rights                                    = $this->job_approval_rights_model->GetModuleStatus($company_sid);
            $users_with_approval_rights                                         = $this->job_approval_rights_model->GetUsersWithApprovalRights($company_sid);
            $user_ids                                                           = array();

            // Check if the employee has  access for the Job Approval Management
            // if not then redirect to dashboard only for access level employee
            if (in_array(strtolower($data['session']['employer_detail']['access_level']), array('employee', 'hiring manager', 'manager'))) {
                if ($company_has_job_approval_rights == 0)
                    redirect('dashboard');

                if ($this->job_approval_rights_model->check_employee_has_approval_rights($company_sid, $employer_sid) == 0)
                    redirect('dashboard');
            }

            foreach ($users_with_approval_rights as $user) {
                $user_ids[$user['sid']]                                     = true;
            }

            $all_unapproved_jobs                                                = array();
            $all_approved_jobs                                                  = array();
            $all_rejected_jobs                                                  = array();

            if ($company_has_job_approval_rights == 1) {
                if (isset($user_ids[$employer_sid])) {
                    $all_unapproved_jobs                                        = $this->job_approval_rights_model->GetAllJobsCompanyAndEmployerSpecific($company_sid, ($data['session']['employer_detail']['access_level'] == 'Admin' ? 0 : $employer_sid), 'pending', '');
                    $all_approved_jobs                                          = $this->job_approval_rights_model->GetAllJobsCompanyAndEmployerSpecific($company_sid, ($data['session']['employer_detail']['access_level'] == 'Admin' ? 0 : $employer_sid), 'approved', '');
                    $all_rejected_jobs                                          = $this->job_approval_rights_model->GetAllJobsCompanyAndEmployerSpecific($company_sid, ($data['session']['employer_detail']['access_level'] == 'Admin' ? 0 : $employer_sid), 'rejected', '');
                }
            }

            foreach ($all_unapproved_jobs as $key => $job) {
                if ($job['approval_status_by'] != 0) {
                    $all_unapproved_jobs[$key]['approval_status_by']            = $this->job_approval_rights_model->GetUserFullName($job['approval_status_by']);
                } else {
                    $all_unapproved_jobs[$key]['approval_status_by']            = STORE_NAME;
                }
            }

            foreach ($all_approved_jobs as $key => $job) {
                if ($job['approval_status_by'] != 0) {
                    $all_approved_jobs[$key]['approval_status_by']              = $this->job_approval_rights_model->GetUserFullName($job['approval_status_by']);
                } else {
                    $all_approved_jobs[$key]['approval_status_by']              = STORE_NAME;
                }
            }

            foreach ($all_rejected_jobs as $key => $job) {
                if ($job['approval_status_by'] != 0) {
                    $all_rejected_jobs[$key]['approval_status_by']              = $this->job_approval_rights_model->GetUserFullName($job['approval_status_by']);
                } else {
                    $all_rejected_jobs[$key]['approval_status_by']              = STORE_NAME;
                }
            }

            $data['company_has_job_approval_rights']                            = $company_has_job_approval_rights;
            $data['all_unapproved_jobs']                                        = $all_unapproved_jobs;
            $data['all_approved_jobs']                                          = $all_approved_jobs;
            $data['all_rejected_jobs']                                          = $all_rejected_jobs;
            $data['title']                                                      = 'Job Listing Approvals Management';

            $this->load->view('main/header', $data);
            $this->load->view('job_approval_management/index');
            $this->load->view('main/footer');
        } else {
            redirect('login', 'refresh');
        }
    }

    public function ajax_responder()
    {
        $data['session']                                                        = $this->session->userdata('logged_in');
        $company_sid                                                            = $data['session']['company_detail']['sid'];
        $employer_sid                                                           = $data['session']['employer_detail']['sid'];
        $employer_name                                                          = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];

        if (array_key_exists('perform_action', $_POST)) {
            $perform_action                                                     = strtoupper($_POST['perform_action']);

            switch ($perform_action) {
                case 'UPDATE_JOB_APPROVAL_STATUS':
                    if ($_POST) {
                        $status = $_POST['status'];
                        $jobId = $_POST['jobid'];

                        $job_listing_data = $this->job_approval_rights_model->GetJobData($jobId);

                        if ($job_listing_data["organic_feed"] == 1) {
                            // load the indeed model
                            $this->load->model("Indeed_model", "indeed_model");
                        }
                        $ppj_id = $job_listing_data['ppj_product_id'];
                        $ppj_expiry_days = $job_listing_data['ppj_expiry_days'];
                        $ppj_activation_date = $job_listing_data['ppj_activation_date'];
                        $job_title = $job_listing_data['Title'];
                        $approval_status = $job_listing_data['approval_status'];

                        $ppjl_charge = $this->job_approval_rights_model->get_pay_per_job_status($company_sid);
                        $per_job_listing_charge = $ppjl_charge['per_job_listing_charge'];
                        $update_data = array();
                        if ($per_job_listing_charge == 1) {
                            if ($status == 'approved' && $ppj_id > 0 && $ppj_id != NULL && ($ppj_expiry_days == 0 || $ppj_activation_date == NULL)) {
                                $update_data['ppj_activation_date'] = date('Y-m-d H:i:s');
                                if ($ppj_expiry_days == 0) { //Insert ppj_expiry_days also if job approved after rejection. we make ppj_expiry_days = 0 if job rejected
                                    $products_details = db_get_products_details($ppj_id);
                                    $update_data['ppj_expiry_days'] = $products_details['expiry_days'];
                                }
                                $this->job_approval_rights_model->update_job_listings($jobId, $update_data);
                            } elseif ($status == 'rejected' && $ppj_id > 0 && $ppj_id != NULL) { // Make expiry days 0 and activation_date today-1 for organic listing
                                $update_data = array('ppj_expiry_days' => 0, 'ppj_activation_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' - 1 days ')));
                                $this->job_approval_rights_model->update_job_listings($jobId, $update_data);
                                if ($ppj_activation_date == NULL && $approval_status == 'pending') { // Refund if Job is not activated
                                    $this->job_approval_rights_model->refund_pay_per_job($job_title, $ppj_id);
                                }
                            }
                        }
                        //change job status to approved on remarket
                        $url = REMARKET_PORTAL_BASE_URL . "/job_listing/" . REMARKET_PORTAL_KEY;
                        $remarket_listing_data = $update_data;
                        $remarket_listing_data['sid'] = $jobId;
                        $remarket_listing_data['approval_status'] = $status;
                        send_settings_to_remarket($url, $remarket_listing_data);

                        if ($this->job_approval_rights_model->UpdateApprovalStatus($employer_sid, $jobId, $status, $company_sid)) {
                            $userIds                                            = $this->job_approval_rights_model->GetUserIdsToWhomJobIsVisible($jobId);
                            $jobData                                            = $this->job_approval_rights_model->GetJobData($jobId);
                            $notifications_status                               = get_notifications_status($company_sid);
                            $approval_management_email_notification             = 0;

                            if ($job_listing_data["organic_feed"] == 1 && $status === "approved") {
                                $this
                                    ->indeed_model
                                    ->updateJobToQueue(
                                        $jobId,
                                        $company_sid,
                                        $status
                                    );
                            } elseif ($job_listing_data["organic_feed"] == 1 && $status === "rejected") {
                                $this
                                    ->indeed_model
                                    ->expireJobToQueue(
                                        $jobId
                                    );
                            }

                            if (!empty($notifications_status)) {
                                $approval_management_email_notification         = $notifications_status['approval_rights_notifications'];
                            }

                            if (!empty($userIds) && $approval_management_email_notification == 1) {
                                $approval_management_email_contacts             = get_notification_email_contacts($company_sid, 'approval_management');
                                $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                if (!empty($approval_management_email_contacts)) { // someone is registered to receive email. Please verify and send email
                                    $employee_registered_to_receive_emails      = array();

                                    foreach ($approval_management_email_contacts as $key => $value) {
                                        if ($value['employer_sid'] > 0 && $value['employer_sid'] != $employer_sid) {
                                            $employee_registered_to_receive_emails[] = $value['employer_sid'];
                                        }
                                    }

                                    foreach ($userIds as $userId) {
                                        if (in_array($userId, $employee_registered_to_receive_emails)) { // check that the employee has rights to approve jobs and he has registered to recieve email
                                            $userProfile                        = $this->job_approval_rights_model->GetUserProfile($userId);

                                            if (!empty($userProfile) && !empty($jobData)) {
                                                $sms_notify = 0;
                                                $contact_no = 0;
                                                if ($company_sms_notification_status) {
                                                    $notify_by = get_employee_sms_status($this, $userId);
                                                    if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                                        $contact_no = $notify_by['PhoneNumber'];
                                                        $sms_notify = 1;
                                                    }
                                                    if ($sms_notify) {
                                                        $this->load->library('Twilioapp');
                                                        // Send SMS
                                                        sendSMS(
                                                            $contact_no,
                                                            'The Job status for ' . $jobData['Title'] . ' has been updated by ' . ucwords($employer_name),
                                                            trim(ucwords(strtolower($userProfile['first_name'] . ' ' . $userProfile['last_name']))),
                                                            $userProfile['email'],
                                                            $this,
                                                            $sms_notify,
                                                            $company_sid
                                                        );
                                                    }
                                                }
                                                $userFullName                   = ucwords($userProfile['first_name'] . ' ' . $userProfile['last_name']);
                                                $emailTemplateData              = get_email_template(JOB_LISTING_STATUS_NOTIFICATION);
                                                $emailTemplateBody              = $emailTemplateData['text'];
                                                $emailTemplateBody              = str_replace('{{user-name}}', $userFullName, $emailTemplateBody);
                                                $emailTemplateBody              = str_replace('{{job-title}}', $jobData['Title'], $emailTemplateBody);
                                                $emailTemplateBody              = str_replace('{{admin-name}}', ucwords($employer_name), $emailTemplateBody);
                                                $subject                        = $emailTemplateData['subject'];
                                                $subject                        = str_replace('{{job-title}}', $jobData['Title'], $subject);
                                                $from                           = $emailTemplateData['from_email'];
                                                $to                             = $userProfile['email'];
                                                $from_name                      = $emailTemplateData['from_name'];

                                                $body = EMAIL_HEADER
                                                    . $emailTemplateBody
                                                    . EMAIL_FOOTER;
                                                log_and_sendEmail($from, $to, $subject, $body, $from_name);
                                            }
                                        }
                                    }
                                }
                            }
                            echo 'success';
                        } else {
                            echo 'failure';
                        }
                    }

                    break;
                default:
                    break;
            }
        }
    }
}
