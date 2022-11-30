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
        $this->load->model('2022/employee_model', 'em');
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
