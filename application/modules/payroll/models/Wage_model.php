<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('payroll/Payrolls_model', 'Payroll_model');
/**
 * Copy payroll model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Wage_model extends Payroll_model
{
    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
    }

    /**
     * Calculate the employee wages withing range
     * 
     * @param int $employeeId
     * @param string $periodStartDate Y-m-d
     * @param string $periodEndDate Y-m-d
     * @return array
     */
    public function calculateEmployeeWage(
        int $employeeId,
        string $periodStartDate,
        string $periodEndDate
    ): array {
        // load the clock model
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
        // get the employee worked shifts
        $clockArray = $this->clock_model->calculateTimeWithinRange(
            $employeeId,
            $periodStartDate,
            $periodEndDate
        );

        _e($clockArray);

        return [];
    }
}
