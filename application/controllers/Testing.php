<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
    }

    /**
     * 
     */
    public function redirectToComply(int $employeeId = 0)
    {
        // check if we need to read from session
        if ($employeeId === 0) {
            $employeeId = $this->session->userdata('logged_in')['employer_detail']['sid'];
        }
        // if employee is not found
        if ($employeeId == 0) {
            return redirect('/dashboard');
        }
        // generate link
        $complyLink = getComplyNetLink(0, $employeeId);
        //
        if (!$complyLink) {
            return redirect('/dashboard');
        }
        redirect($complyLink);
    }


    public function test($employeeSid)
    {

        $employeeSid = '49332';


        $this->db->select('
            departments_management.sid as department_sid,
            departments_management.name as department_name,
            departments_team_management.sid,
            departments_team_management.name
            ');

        $this->db->join(
            'departments_team_management',
            'departments_team_management.sid = departments_employee_2_team.team_sid',
            'inner'
        );
        $this->db->join(
            'departments_management',
            'departments_management.sid = departments_team_management.department_sid',
            'inner'
        );

        $this->db->where('departments_management.is_deleted', 0);
        $this->db->where('departments_team_management.is_deleted', 0);
        $this->db->where('departments_employee_2_team.employee_sid', $employeeSid);

        $result = $this->db->get('departments_employee_2_team')->result_array();
        //
        $returnArray = [
            'departments' => [],
            'teams' => [],
        ];
        //
        if (!$result) {
            return $returnArray;
        }

        foreach ($result as $data_row) {
            $returnArray['departments'][$data_row['department_sid']] =
                array('sid' => $data_row['department_sid'], 'name' => $data_row['department_name']);
            $returnArray['teams'][] = array('department_sid' => $data_row['department_sid'], 'sid' => $data_row['sid'], 'name' => $data_row['name']);
        }
        //
        _e($returnArray, true, true);
    }
}
