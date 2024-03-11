<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Handles the CRON actions, reminders
 * and notifications
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 * @package Attendance
 */
class Cron_clock extends CI_Controller
{
    /**
     * holds the verification token
     * @var string
     */
    private $verificationToken;

    /**
     * main entry point
     */
    public function __construct()
    {
        // call parent constructor
        parent::__construct();
        // load the CRON model
        $this->load->model("v1/Attendance/Cron_clock_model", "cron_clock_model");
        // set the verification token
        $this->verificationToken = getCreds('AHR')->VerifyToken;
    }

    /**
     * Main entry point for events
     * The CRON will auto clocks out employees
     * based on the company auto clock settings
     * @method autoClockOut
     * @method dailyLimitBreachEmailToEmployers
     * @param string $verificationToken
     * @param string $event
     * autoClockOut
     * dailyLimitBreachEmailToEmployers
     */
    public function main(
        string $verificationToken,
        string $event
    ) {
        // get the companies with attendance module enabled
        $companies = $this
            ->cron_clock_model
            ->getCompaniesWithActivatedModuleWithSettings();
        //
        if (!$companies) {
            exit("No companies found.");
        }
        //
        foreach ($companies as $v0) {
            // call the event
            $this->$event($v0);
        }
        //
        exit("Job finished");
    }

    /**
     * Auto clock out employees
     * Main entry point for events
     * The CRON will auto clocks out employees
     * based on the company auto clock settings
     * @param array $companySettings
     */
    private function autoClockOut(array $companySettings)
    {
        // load clock out model
        $this->load->model(
            "v1/Attendance/Auto_clock_out_model",
            "auto_clock_out_model"
        );
        // check if auto clock needs to be applied
        if ($companySettings["settings_json"]["general"]["auto_clock_out"]["status"] == "0") {
            return _e("Auto clock out is off");
        }
        //
        $this->auto_clock_out_model->startProcess(
            $companySettings["company_sid"],
            (string)$companySettings["settings_json"]["general"]["auto_clock_out"]["value"],
            getSystemDate(DB_DATE)
        );
    }

    /**
     * Send daily  limit breach email
     * Send the daily time limit breach email to employers
     * @param array $companySettings
     */
    private function dailyLimitBreachEmailToEmployers(array $companySettings)
    {
        // load clock out model
        $this->load->model(
            "v1/Attendance/Clock_notification_model",
            "clock_notification_model"
        );
        // check if auto clock needs to be applied
        if ($companySettings["settings_json"]["general"]["daily_limit"]["status"] == "0") {
            return _e("Daily limit is off");
        }
        //
        $this->clock_notification_model
            ->checkAndSendDailyLimitBreachEmailToEmployers(
                $companySettings["company_sid"],
                (string)$companySettings["settings_json"]["general"]["daily_limit"]["value"],
                getSystemDate(DB_DATE)
            );
    }
}
