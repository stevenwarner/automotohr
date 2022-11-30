<?php defined('BASEPATH') || exit('No direct script access allowed');

class Employee_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }

    /**
     * Get employee profile history data
     *
     * @param int     $employeeId
     * @param boolean $count
     * @return array
     */
    public function getProfileHistory(
        $employeeId,
        $count = false
    )
    {
        //
        $this->db
        ->from('profile_history')
        ->where('user_sid', $employeeId);
        //
        if ($count) {
            return $this->db->count_all_results();
        }
        //
        return
        $this->db
        ->select('profile_data, created_at')
        ->order_by('sid', 'DESC')
        ->get()
        ->result_array();
    }

    /**
     * Get states
     *
     * @return array
     */
    public function getStates()
    {
        //
        return $this->db
        ->select('sid, state_name')
        ->from('states')
        ->get()
        ->result_array();
    }
}
