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


    public function test()
    {
        //
        $companyId = '8578';

        $this->load->library('Complynet/Complynet_lib', '', 'clib');

        $complyNetcompanyData = $this->db
            ->select('complynet_company_sid,complynet_location_sid')
            ->where('company_sid', $companyId)
            ->get('complynet_companies')
            ->row_array();

        $complyCompanyUsersData = [];
        if (!empty($complyNetcompanyData)) {
            $complyCompanyUsersData = $this->clib->getComplyNetCompanyUsers(
                $complyNetcompanyData['complynet_company_sid']
            );
        }

        $complynetUserData = [];

        if (!empty($complyCompanyUsersData)) {
            foreach ($complyCompanyUsersData as $key => $rowData) {
                if ($rowData['LocationId'] == $complyNetcompanyData['complynet_location_sid']) {
                    $complynetUserData[$rowData['UserId']] = $rowData;
                }
            }
        }

        //
        $complyNetEmployeeData = $this->db
            ->select('complynet_employees.sid,complynet_employees.alt_id,complynet_employees.complynet_employee_sid,complynet_employees.complynet_company_sid,complynet_employees.complynet_location_sid,complynet_employees.complynet_department_sid,complynet_employees.complynet_job_role_sid,complynet_employees.employee_sid,complynet_employees.email,complynet_employees.complynet_json,users.first_name,users.last_name,users.active,users.terminated_status')
            ->where('complynet_employees.company_sid', $companyId)
            ->join('users', 'users.sid = complynet_employees.employee_sid')
            ->get('complynet_employees')
            ->result_array();

        // _e($complyNetEmployeeData, true, true);
        //
        $userData = [];
        $missingData = [];
        foreach ($complyNetEmployeeData as $row) {
            if ($complynetUserData[$row['complynet_employee_sid']]) {
                $missingfield = array();

                $complynetUserDataRow = $complynetUserData[$row['complynet_employee_sid']];

                if ($complynetUserDataRow['AltId'] != $row['alt_id']) {
                    $missingfield[] = array('complynet_altId' => $complynetUserDataRow['AltId'], 'ahr_altId' => $row['alt_id']);
                }
                //
                if ($complynetUserDataRow['UserId'] != $row['complynet_employee_sid']) {
                    $missingfield[] = array('complynet_employee_sid' => $complynetUserDataRow['UserId'], 'ahr_complynet_employee_sid' => $row['complynet_employee_sid']);
                }
                //
                if ($complynetUserDataRow['CompanyId'] != $row['complynet_company_sid']) {
                    $missingfield[] = array('complynet_company_sid', $complynetUserDataRow['CompanyId'], 'ahr_complynet_company_sid', $row['complynet_company_sid']);
                }
                //
                if ($complynetUserDataRow['LocationId'] != $row['complynet_location_sid']) {
                    $missingfield[] = array('complynet_location_sid' => $complynetUserDataRow['LocationId'], 'ahr_complynet_location_sid' => $row['complynet_location_sid']);
                }
                //
                if ($complynetUserDataRow['DepartmentId'] != $row['complynet_department_sid']) {
                    $missingfield[] = array('complynet_department_sid' => $complynetUserDataRow['DepartmentId'], 'ahr_complynet_department_sid' => $row['complynet_department_sid']);
                }
                //
                if ($complynetUserDataRow['JobRoleId'] != $row['complynet_job_role_sid']) {
                    $missingfield[] = array('complynet_job_role_sid' => $complynetUserDataRow['JobRoleId'], 'ahr_complynet_job_role_sid' => $row['complynet_job_role_sid']);
                }
                //
                if ($complynetUserDataRow['Email'] != $row['email']) {
                    $missingfield[] = array('complynet_email' => $complynetUserDataRow['Email'], 'ahr_email' => $row['email']);
                }

                //
                $status = 0;
                if ($row['active'] == 1 && $row['terminated_status'] == 0) {
                    $status = 1;
                }


                if ($complynetUserDataRow['Status'] != $status) {
                    $missingfield[] = array('complynet_status' => $complynetUserDataRow['Status'], 'ahr_status' => $row['active'], 'ahr_terminated_status' => $row['terminated_status']);
                }

                // $complynet_json = json_decode($row['complynet_json'], true);

                $missingData['missingdata'] = $missingfield;
                $datadetail['complynet_name'] = $complynetUserDataRow['FirstName'] . ' ' . $complynetUserDataRow['LastName'];
                $datadetail['ahr_name'] = $row['first_name'] . ' ' . $row['last_name'];
                $datadetail['ahr_company_sid'] = $row['company_sid'];
                $datadetail['missingdata'] = $missingData['missingdata'];

                $userData[] = $datadetail;
            }
        }
        // return $userData;
        _e($userData, true);
    }
}
