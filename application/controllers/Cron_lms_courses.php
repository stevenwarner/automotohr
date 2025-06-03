<?php defined('BASEPATH') || exit('No direct script access allowed');

class Cron_lms_courses extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("cron_email_model");
    }

    /**
     * Send email notification every two weeks.
     * set CRON for twice a day
     */
    public function sendCourseReminderEmails()
    {
        $this->cron_email_model->sendCourseReminderEmails();
    }

    /**
     * Send email notification for first time course availability
     * Set CRON for every hour
     */
    public function sendFirstTimeAvailableCourseEmailToEmployees()
    {
        $this->cron_email_model->sendFirstTimeAvailableCourseEmailToEmployees();
    }

    /**
     * Send email notification for first time course availability
     * set CRON for every Monday at 06:00 AM PST
     */
    public function sendCourseReportToManagers()
    {
        $this->cron_email_model->sendCourseReportToManagers();
    }

    /**
     * Summary of sendScheduledDocumentReport
     * @return void
     */
    public function sendDocumentReportToManagers()
    {
        $this->cron_email_model->sendDocumentReportToManagers();
    }
}
