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

    /**Get pending notification survey
     * 
     * @param string $table
     * @param array $insertArray
     * 
     * @return Array
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

    /**
     *  Get prnding survey respondents
     * 
     * @param integer $survey_id
     * 
     * @return Array
     */
    public function getPendingSurveyRespondents($survey_id) {
        //
        $this->db->select('employee_sid');
        $this->db->where('survey_sid', $survey_id);
        $this->db->where('is_archived', 0);
        $this->db->where('is_completed', 0);
        return $this->db->get('employee_surveys_respondents')->result_array();
    }

    /**
     *  Update survey Notification
     * 
     * @param integer $survey_id
     * @param string $type
     * 
     */
    function updateSurveyNotification($survey_id, $type)
    {
        $this->db->where('survey_sid', $survey_id);
        $this->db->where('notification_type', $type);

        $this->db->set('updated_at', date('Y-m-d H:i:s'));
        $this->db->set('is_process', 1);

        $this->db->update('employee_surveys_notification_info');
    }

    /**
     *  Get Respondent Profile info
     * 
     * @param integer $respondent_id
     * 
     */
    function getRespondentProfileInfo ($respondent_id) {
        $this->db->select('first_name, last_name, email, parent_sid');
        $this->db->where('sid', $respondent_id);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        return $this->db->get('users')->row_array();
    }

    /**
     * 
     * Get Respondents Pending Surveys Count
     * 
     * @param {Integer} respondentId  
     * @param {string} date   
     * 
     * @returns 
     */
    function getRespondentPendingCount ($respondentId) {
        //
        $this->db->where('tb1.employee_sid', $respondentId);
        $this->db->where('tb1.is_completed', 0);
        $this->db->where('tb1.is_archived', 0);
        $this->db->where('tb2.is_completed', 0);
        $this->db->where('tb2.is_archived', 0);
        $this->db->where('tb2.is_draft', 0);
        $this->db->where('tb2.start_date <= "' . date('Y-m-d') . '"');
        $this->db->where('tb2.end_date >= "' . date('Y-m-d') . '"');
        $this->db->join('employee_surveys as tb2', 'tb2.sid = tb1.survey_sid');
        return $this->db->from('employee_surveys_respondents as tb1')->count_all_results();
    }

}