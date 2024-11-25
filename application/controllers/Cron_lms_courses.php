<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron_lms_courses extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("cron_email_model");
    }

    // public function sendPendingCoursesEmails(string $key)
    // {
    //     if ($key != getCreds()->AHR->VerifyToken) {
    //         $this->load->model('cron_lms_model');
    //         //
    //         $pendingCoursesEmployees = $this->cron_lms_model->getEmployeesWithPendingCourses();
    //         //
    //         if (!empty($pendingCoursesEmployees)) {
    //             $this->sendEmailReminder($pendingCoursesEmployees, DUE_SOON_COURSES_REMINDER_NOTIFICATION);
    //         }
    //     }
    // }

    // public function sendTodayAssignedCoursesEmails(string $key)
    // {
    //     if ($key != getCreds()->AHR->VerifyToken) {
    //         $this->load->model('cron_lms_model');
    //         //
    //         $assignedCoursesEmployees = $this->cron_lms_model->getEmployeesWithTodayAssignedCourses();
    //         //
    //         if (!empty($assignedCoursesEmployees)) {
    //             $this->sendEmailReminder($assignedCoursesEmployees, ASSIGNED_COURSES_REMINDER_NOTIFICATION);
    //         }
    //     }
    // }

    // public function sendEmailReminder($companiesEmployees, $template)
    // {
    //     //
    //     foreach ($companiesEmployees as $companyId => $companyInfo) {
    //         //
    //         $companyName = getCompanyNameBySid($companyId);
    //         // 
    //         foreach ($companyInfo['employees'] as $employee) {
    //             //
    //             $replaceArray = [];
    //             $replaceArray['first_name'] = ucwords($employee['first_name']);
    //             $replaceArray['last_name'] = ucwords($employee['last_name']);
    //             $replaceArray['company_name'] = $companyName;
    //             $replaceArray['pending_count'] = $employee['pendingCount'];
    //             $replaceArray['my_courses_link'] = '<a href="' . base_url("lms/courses/my") . '" target="_blank" style="padding: 8px 12px; border: 1px solid #fd7a2a;background-color:#fd7a2a;border-radius: 6px;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; margin-right: 10px;">My Courses</a>';
    //             //
    //             log_and_send_templated_email(
    //                 $template,
    //                 $employee['email'],
    //                 $replaceArray,
    //                 message_header_footer($companyId, $companyName)
    //             );
    //         }
    //     }
    // }

    /**
     * Send email notification every two weeks.
     */
    public function sendCourseReminderEmails()
    {
        $this->cron_email_model->sendCourseReminderEmails();
    }

    /**
     * Send email notification every two weeks.
     */
    public function sendFirstTimeAvailableCourseEmailToEmployees()
    {

        $this->cron_email_model->sendFirstTimeAvailableCourseEmailToEmployees();
    }
}
