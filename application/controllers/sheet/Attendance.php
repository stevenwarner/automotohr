<?php defined('BASEPATH') || exit('No direct script access allowed');

class Attendance extends Public_Controller {

    private $companyId = 0;
    private $ses = [];
    private $employerId = 0;
    private $employeeId = 0;

    /**
     * 
     */
    public function __construct() {
        //
        parent::__construct();
        //
        if ($this->session->userdata('logged_in')) {
            $this->ses = $this->session->userdata('logged_in');
            $this->companyId = $this->ses["company_detail"]["sid"];
            $this->employeeId = $this->employerId = $this->ses["employer_detail"]["sid"];
        }
    }


    /**
     * 
     */
    public function MyAttendance(){
        //
        _e($this->ses);
    }

}