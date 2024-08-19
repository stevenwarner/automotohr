<?php defined("BASEPATH") || exit("Invalid access.");

/**
 * Main payroll controller
 * @version 1.0
 */

class Main extends Payroll_base_controller
{
    /**
     * main entry point
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct(true);
    }

    public function index()
    {
        die("in index");
    }
}
