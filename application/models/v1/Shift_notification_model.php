<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Shift notification model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Clock
 */
class Shift_notification_model extends CI_Model
{
    /**
     * Main entry point
     */
    public function __construct()
    {
        // inherit parent
        parent::__construct();
    }

    /**
     * send out email notifications to selected employees
     *
     * @param array $employeeIds
     */
    public function sendEmployeesNotificationsWithUpcomingShifts(
        array $employeeIds = []
    ) {
        // todo
        // send email notification per day for upcoming shifts
    }
}
