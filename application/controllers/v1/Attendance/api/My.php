<?php defined("BASEPATH") || exit("Access is denied.");
/**
 * API - Main
 * 
 * @author AutomotoHR Dev Team <www.automotohr.com>
 * @version 1.0
 * @package Shift & Clock
 */
class My extends Public_Controller
{
    /**
     * set logged in session
     * @var array
     */
    protected $appSession;

    /**
     * set logged in person details
     * @var array
     */
    protected $loggedInEmployee;

    /**
     * set logged in company details
     * @var array
     */
    protected $loggedInCompany;

    /**
     * main entry point
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
        // check wether the use is logged in or not
        $this->appSession = checkAndGetSession("all");
        $this->loggedInEmployee = $this->appSession["employer_detail"];
        $this->loggedInCompany = $this->appSession["company_detail"];
        // load libraries
        $this->load->library(
            "Attendance_lib",
            "attendance_lib"
        );
        // load models
        $this->load->model(
            "v1/Attendance/My_clock_model",
            "my_clock_model"
        );
    }

    /**
     * get the clock state with job sites
     */
    public function getClockWithState()
    {
        $this->my_clock_model->getClockWithState(
            $this->loggedInCompany["sid"],
            $this->loggedInEmployee["sid"],
        );
    }

    /**
     * mark attendance
     */
    public function markAttendance()
    {
        // convert to post
        $_POST = json_decode(file_get_contents("php://input"), true);
        // set rules
        $this->form_validation->set_rules("type", "Mark type", "xss_clean|required");
        $this->form_validation->set_rules("latitude", "Latitude", "xss_clean|required");
        $this->form_validation->set_rules("longitude", "Longitude", "xss_clean|required");
        // run validation
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        //
        $post = $this->input->post(null, true);
        $latLon = $this->attendance_lib->getRandomLatLon();
        //
        if (isDevServer()) {
            // set lat long for demo purposes
            $post["latitude"] = $latLon["lat"];
            $post["longitude"] = $latLon["lng"];
        }
        //
        $this->my_clock_model->markAttendance(
            $this->loggedInCompany["sid"],
            $this->loggedInEmployee["sid"],
            $post
        );
    }
}
