<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Employee Survey model
 * 
 * Holds all the company survey interactions
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Aleem Shaukat <ashaukat@egenienext.com>
 * @version 1.0 
 * 
 */
class Employee_survey_model extends CI_Model {

    /**
     * Entry point
     */
    function __construct() {
        // Inherit parent class properties and methods
        parent::__construct();
    }

    /**
     * Add company data to table
     * 
     * @param string $table
     * @param array $insertArray
     * 
     * @return int
     */
    public function getTodayPendingNotifications() {
        //
        $this->db->select('tb2.sid, tb2.title, tb1.notification_type');
        $this->db->where('tb2.is_archived', 0);
        $this->db->where('tb1.is_process', 0);
        $this->db->where('tb1.notification_date = "' . date('Y-m-d') . '"');
        $this->db->join('employee_surveys as tb2', 'tb2.sid = tb1.survey_sid');
        return $this->db->get('employee_surveys_notification_info as tb1')->result_array();
    }

}