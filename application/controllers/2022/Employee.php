<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 *
 */
class Employee extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //
        $this->load->model('2022/User_model', 'em');
    }

    /**
     *
     */
    public function employeeProfileReport()
    {
        // Check for session
        if (!$this->session->userdata('logged_in')) {
            return redirect('/login');
        }
        //
        $data = [];
        //
        $data['session'] = $this->session->userdata('logged_in');
        //
        $employeeId = $data['session']['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $data['title'] = "Employee / Team Members Profile";
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $data['session']['employer_detail'];
        //
        $data['PageScripts'] = [
            ['1.0.1', '2022/js/employee/change/main']
        ];
        //
        $this->load
        ->view('main/header_2022', $data)
        ->view('2022/employee/profile_change_report')
        ->view('main/footer_2022');
    }


    /**
     * Get the employee profile history
     *
     * @param int $employeeId
     * @return json
     */
    public function getProfileHistory(int $employeeId)
    {
        //
        $records = $this->em->getProfileHistory($employeeId);
        //
        if ($records) {
            $states = $this->em->getStates();
            //
            if ($states) {
                //
                $tmp = [];
                //
                foreach ($states as $state) {
                    $tmp[$state['sid']] = $state['state_name'];
                }
                //
                $states = $tmp;
            }
        }
        //
        return SendResponse(200, [
            'history' => $records,
            'states' => $states
        ]);
    }
}
