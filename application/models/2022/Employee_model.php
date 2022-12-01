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
        $records =
        $this->db
        ->select('
            profile_history.profile_data,
            profile_history.created_at,
            users.first_name,
            users.last_name,
            users.middle_name,
            users.access_level,
            users.access_level_plus,
            users.is_executive_admin,
            users.job_title,
            users.timezone
        ')
        ->join('users', 'users.sid = profile_history.employer_sid', 'left')
        ->order_by('profile_history.sid', 'DESC')
        ->get()
        ->result_array();
        //
        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $records[$key]['full_name'] = remakeEmployeeName($record);
                //
                unset(
                    $records[$key]['first_name'],
                    $records[$key]['last_name'],
                    $records[$key]['middle_name'],
                    $records[$key]['access_level'],
                    $records[$key]['access_level_plus'],
                    $records[$key]['is_executive_admin'],
                    $records[$key]['job_title'],
                    $records[$key]['timezone']
                );
            }
        }
        //
        return $records;
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
