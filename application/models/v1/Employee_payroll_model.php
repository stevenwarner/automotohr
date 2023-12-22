<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('v1/Payroll_model', 'Payroll_model');
/**
 * Employee payroll model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Employee_payroll_model extends Payroll_model
{
    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
    }


    public function syncForms(string $gustoEmployeeId)
    {
        // _e($gustoEmployeeId, true, true);
    }
}
