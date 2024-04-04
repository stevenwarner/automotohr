<?php defined('BASEPATH') or exit('No direct script access allowed');

class Job_listings_auto_deactivation extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('job_listings_auto_deactivation_model');
    }

    public function index($verification_key = NULL)
    {
        if ($verification_key == 'dw6btPzuoHI9d5TEIKBKDGWwNoGEUlRuSidW8wQ4zSUHIl9gBxRx18Z3Aqk4HV7ZNCbu2ZfkjFVLHWINnz5uzMkUfIiINdZ19nJi') {
            sendMail('notifications@automotohr.com', 'dev@automotohr.com', 'auto deactivate cron executed', 'it is auto executed', 'AutomotoHR', 'dev@automotohr.com');
            $today_start_obj = new DateTime();
            $today_start_obj->setTime(00, 00, 00);
            $today_start_str = $today_start_obj->format('Y-m-d H:i:s');
            $today_end_obj = new DateTime();
            $today_end_obj->setTime(23, 59, 59);
            $today_end_str = $today_end_obj->format('Y-m-d H:i:s');
            //$jobs = $this->job_listings_auto_deactivation_model->get_all_expiring_jobs($today_start_str, $today_end_str);
            $jobs = $this->job_listings_auto_deactivation_model->active_jobs_with_expiry($today_end_str);
            //        echo $this->db->last_query();
            $expiring_job_sids = array();
            $message_text = '';
            //    echo '<pre>'; print_r($jobs); 

            if (!empty($jobs)) {
                // load the indeed model
                $this->load->model("Indeed_model", "indeed_model");
                foreach ($jobs as $key => $job) {
                    $sid = $job['sid'];
                    $company_sid = $job['user_sid'];
                    $job_title = $job['Title'];
                    $expiration_date = $job['expiration_date'];
                    $expiring_job_sids[] = $sid;
                    // Send job status alert to AC
                    $this->sendJobDetailsToRemarket(
                        array_merge($job, ['active' => 0]),
                        $job['sid'],
                        [
                            'sid' => $job['user_sid'],
                            'CompanyName' => $job['CompanyName']
                        ]
                    );
                    // expire the job on Indeed
                    $this->indeed_model->expireJobToQueue(
                        $job["sid"]
                    );

                    $message_text .= '<li> ==== START ====</li>';
                    $message_text .= '<li>Job ID:' . $sid . ' - Title: ' . $job_title . '</li>';
                    //                $has_applicant_approval_rights = $job['has_applicant_approval_rights'];
                    $notifications_email_status = $this->job_listings_auto_deactivation_model->get_notifications_status($company_sid, 'approval_rights_notifications'); // check if Notification email management against the company and get all emails of approval right management
                    $company_sms_notification_status                                = get_company_sms_status($this, $company_sid);
                    if ($notifications_email_status > 0) { // Notification emails for approval right is enabled. Get the emails
                        $notifications_emails = getNotificationContacts($company_sid, 'approval_management', 'approval_rights_notifications');

                        if (!empty($notifications_emails)) {
                            $contact_name = '';
                            $email = '';
                            $company_name = $this->job_listings_auto_deactivation_model->get_company_name($company_sid);
                            $message_text .= '<li> Company Name' . $company_name . '</li>';
                            //                       print_r($notifications_emails);
                            foreach ($notifications_emails as $notification_email) {
                                $contact_name = $notification_email['contact_name'];
                                $email = $notification_email['email'];
                                $replacement_array['contact-name'] = $contact_name;
                                $replacement_array['title'] = $job_title;
                                $replacement_array['job-title'] = $job_title;
                                $replacement_array['company-name'] = $company_name;
                                log_and_send_templated_email(JOBS_AUTO_EXPIRATION_NOTIFICATION, $email, $replacement_array);
                                //                            log_and_send_templated_email(JOBS_AUTO_EXPIRATION_NOTIFICATION, 'dev@automotohr.com', $replacement_array);
                                $message_text .= '<li> Notification Sent to: ' . $contact_name . ' -- [EMAIL] = ' . $email . ' </li>';

                                //Send SMS Also
                                $sms_notify = 0;
                                $contact_no = 0;
                                if ($company_sms_notification_status) {
                                    if ($notification_email['employer_sid'] != 0) {
                                        $notify_by = get_employee_sms_status($this, $notification_email['employer_sid']);
                                        if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                            $contact_no = $notify_by['PhoneNumber'];
                                            $sms_notify = 1;
                                        }
                                    } else {
                                        if (!empty($notification_email['contact_no'])) {
                                            $contact_no = $notification_email['contact_no'];
                                            $sms_notify = 1;
                                        }
                                    }
                                    if ($sms_notify) {
                                        $this->load->library('Twilioapp');
                                        // Send SMS
                                        sendSMS(
                                            $contact_no,
                                            'The Following Job with the Title ' . $job_title . ' has automatically expired.',
                                            trim(ucwords(strtolower($notification_email['contact_name']))),
                                            $notification_email['email'],
                                            $this,
                                            $sms_notify,
                                            $company_sid
                                        );
                                    }
                                }
                            }
                        }
                    }
                    $message_text .= '<li> ==== END ====</li>';
                }
            }

            $subject = 'Jobs Automatically Deactivated!';
            $message = '';
            $message .= '<ol>';

            if (!empty($expiring_job_sids)) {
                $this->job_listings_auto_deactivation_model->set_active_status($expiring_job_sids, 0);
                $message .= $message_text;
            } else {
                $message .= '<li>No Jobs Expiring Today ' . date(Y) . '</li>';
            }

            $message .= '</ol>';

            if (base_url() != 'http://localhost/automotoCI/') {
                sendMail('notifications@automotohr.com', 'dev@automotohr.com', $subject, $message, 'AutomotoHR', 'dev@automotohr.com');
                //            echo $message;
            } else {
                echo $message;
            }
        }
    } /// herererererer


    private function sendJobDetailsToRemarket($listing_data, $jobId, $company_details, $oldCategories = NULL)
    {
        $url = REMARKET_PORTAL_BASE_URL . "/job_listing/" . REMARKET_PORTAL_KEY;
        $remarket_listing_data = $listing_data;
        $remarket_listing_data['sid'] = $jobId;
        $this->load->model('dashboard_model');
        $sub_domain = $this->dashboard_model->get_portal_detail($company_details['sid']);

        $remarket_listing_data['sub_domain'] = isset($sub_domain['sub_domain']) ? $sub_domain['sub_domain'] : '';
        $remarket_listing_data['company_name'] = $company_details['CompanyName'];

        send_settings_to_remarket($url, $remarket_listing_data);
        usleep(100);
    }
}
