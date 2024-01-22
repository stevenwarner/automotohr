<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee shifts
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Shifts
 */
class Shift_notification_cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * send out email notification to employees
     * with upcoming shifts
     */
    public function sendEmployeesNotificationsWithUpcomingShifts()
    {
        // load shift notification model
        $this->load->model("v1/Shift_notification_model", "shift_notification_model");
        //
        $response = $this->shift_notification_model
            ->sendEmployeesNotificationsWithUpcomingShifts(
                ["all"]
            );
        _e($response, true);
    }
}
