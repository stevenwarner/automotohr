<?php defined("BASEPATH") || exit("Access is denied.");
// load the base controller
loadUpController("v1/Attendance/Base");
/**
 * Payroll
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Attendance
 */
class Payroll extends Base
{
    public function __construct()
    {
        parent::__construct();
        // load the main model
        $this->load->model("v1/Attendance/Dashboard_model", "dashboard_model");
    }

   
}
