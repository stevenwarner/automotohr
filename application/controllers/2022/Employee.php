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
        $company_sid = $data['session']['company_detail']['sid'];

        getCompanyEmsStatusBySid($company_sid, true);

        $employeeId = $data['session']['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $data['title'] = "Employee / Team Members Profile";
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $data['session']['employer_detail'];
        //
        $data['PageScripts'] = [
            [getAssetTag('1.0.3'), '2022/js/employee/change/main'],
            [getAssetTag('1.0.7'), '2022/js/employee_profile/main'],
        ];
        // Get employees list
        $data['employeesList'] = $this->em->getCompanyEmployees($data['session']['company_detail']['sid']);
        // set filter
        $data['employeeIds'] = $employeeIds = $this->input->get('employeeIds', true) ?? [];
        $data['startDate'] = $startDate = $this->input->get('startDate', true) ?? date('m/01/Y', strtotime('now'));
        $data['endDate'] = $endDate = $this->input->get('endDate', true) ?? date('m/t/Y', strtotime('now'));
        //
        $data['records'] = $this->em->getEmployeeChanges(
            $data['session']['company_detail']['sid'],
            $employeeIds,
            formatDateToDB($startDate, SITE_DATE, DB_DATE),
            formatDateToDB($endDate, SITE_DATE, DB_DATE)
        );

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
