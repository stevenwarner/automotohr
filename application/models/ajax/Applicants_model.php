<?php
/**
 * Handles all AJAX calls for applicants
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Mubashir   <mubashar@automotohr.com>
 * @version 1.0
 */
class Applicants_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    /**
     * Get the table data
     * 
     * @param string  $tableName
     * @param array   $columnArray
     * @param array   $whereArray
     * @param array   $orderBy
     * @param boolean $count
     * 
     * @return array|number
     */
    public function getDataFromTable(
        $tableName,
        $columnArray,
        $whereArray,
        $orderBy = ['sid', 'desc'],
        $count = false
    ){
        //
        $this->db
        ->select($columnArray)
        ->from($tableName)
        ->where($whereArray)
        ->order_by($orderBy[0], $orderBy[1]);
        //
        if($count){
            return $this->db->count_all_results();
        }
        //
        $q = $this->db->get();
        //
        return $q->result_array();
    }

    /**
     * Revert the applicant's onboard
     * 
     * @param number $applicantId
     */
    public function revertApplicantOnboard(
        $applicantId
    ){
        // Update is onboarding flag
        $this->db
        ->where([
            'sid' => $applicantId
        ])
        ->update(
            'portal_job_applications', [
                'is_onboarding' => 0
            ]
        );
        // Remove entry from onboard table
        $this->db
        ->where([
            'applicant_sid' => $applicantId
        ])
        ->delete('onboarding_applicants');
    }
}
