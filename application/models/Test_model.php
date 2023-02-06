<?php
define('PJL', 'portal_job_listings');
define('PJA', 'portal_job_applications');
define('PAJL', 'portal_applicant_jobs_list');

class Test_model extends CI_Model
{
    //
    //
    function __construct()
    {
        //
        parent::__construct();
    }


    public function get_merge_employee()
    {

        $this->db->select('secondary_employee_sid');
        $records_obj = $this->db->get('employee_merge_history');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();
        //
        if (!empty($records_arr)) {
            foreach ($records_arr as $row) {
                $this->checkProfileExist($row["secondary_employee_sid"]);
            }
        }
        //
        return $return_data;
    }

    private function checkProfileExist ($employee_sid) {
        $this->db->select('secondary_employee_sid');
        $this->db->where('sid', $employee_sid);
        $this->db->from('users');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            // $this->db->where('sid', $employee_sid);
            // $this->db->delete('users');
            echo "Wants to delete this employee with sid = ".$employee_sid." <br>";
        } else {
            echo "Already deleted this employee with sid = ".$employee_sid." <br>";
        }
    }


    
}
