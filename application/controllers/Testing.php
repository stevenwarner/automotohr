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

    public function syncComplynetEmployeeStatus(int $executeProcess = 0, bool $doReturn = true)
    {
        // set default response array
        $res = [];
        //
        $columns = "complynet_employees.complynet_location_sid, complynet_employees.email, complynet_employees.complynet_json, users.active, users.terminated_status, users.sid";
        $inactiveEmployees = getComplynetUsers('inactive', $columns);
        //
        // check if employee is already synced with ComplyNet
        if (empty($inactiveEmployees)) {
            $res['errors'][] = 'No inactive synchronized ComplyNet employees found.';
            return $doReturn ? $res : sendResponse(200, $res);
        }
        //
        $employeeResponse = [];
        //
        foreach ($inactiveEmployees as $employee) {
            // decode the json to array
            $jsonToArray = json_decode($employee['complynet_json'], true);
            // set the username of employee on ComplyNet
            $username = isset($jsonToArray[0]['UserName']) ? $jsonToArray[0]['UserName'] : $jsonToArray['UserName'];
            // if username is not email then set it to username
            if (strpos($username, '@') === false) {
                $employee['email'] = $username;
            }
            // Get the employee
            $response = getComplynetUser($employee['email']);
            // if ComplyNet don't have this employee
            $employeeInfo = [
                'EmployeeName' => getUserNameBySID($employee['sid']),
                'ComplynetResponse' => FALSE,
                'ComplynetEmail' => $employee['email'],
                'ComplynetStatus' => '',
                'AutomotoHRStatus' => 'Inactive',
                'AutomotoHRId' => $employee['sid']
            ];
            //
            if ($response) {
                //
                foreach ($response as $value) {
                    if ($value['LocationId'] == $employee['complynet_location_sid']) {
                        //
                        $employeeInfo['ComplynetStatus'] =  $value['Status'] == 1 ? "active" : "deactive";
                        $employeeInfo['ComplynetResponse'] =  TRUE;
                    }
                }
                //
                if ($employeeInfo['ComplynetStatus'] &&  $employeeInfo['ComplynetStatus'] == "active") {
                    //
                    if ($executeProcess == 1) {
                        //
                        updateComplynetUserStatus($employee['email']);
                    }
                }
                $employeeResponse[] = $employeeInfo;
                
            }
        }
        //
        $res['success'][] = "Records Found";
        $res['Data'] = json_encode($employeeResponse);
        return $doReturn ? $res : sendResponse(200, $res);
    }

    public function getComplynetEmployeeMissingData()
    {
        // set default response array
        $res = [];
        //
        $columns = "complynet_employees.complynet_location_sid, complynet_employees.email, complynet_employees.complynet_json, users.sid";
        $activeEmployees = getComplynetUsers('active', $columns);
        //
        // check if employee is already synced with ComplyNet
        if (empty($activeEmployees)) {
            $res['errors'][] = 'No synchronized ComplyNet employees found with missing data.';
            return $doReturn ? $res : sendResponse(200, $res);
        }
        //
        $employeeResponse = [];
        //
        foreach ($activeEmployees as $employee) {
            // decode the json to array
            $jsonToArray = json_decode($employee['complynet_json'], true);
            //
            $userAltId = isset($jsonToArray[0]['AltId']) ? $jsonToArray[0]['AltId'] : $jsonToArray['AltId'];
            //
            if (!$userAltId) {
                // set the username of employee on ComplyNet
                $username = isset($jsonToArray[0]['UserName']) ? $jsonToArray[0]['UserName'] : $jsonToArray['UserName'];
                // if username is not email then set it to username
                if (strpos($username, '@') === false) {
                    $employee['email'] = $username;
                }
                //
                $employeeResponse[] = [
                    'EmployeeName' => getUserNameBySID($employee['sid']),
                    'ComplynetEmail' => $employee['email'],
                    'AutomotoHRId' => "AHR".$employee['sid']
                ];
            }
            
        }
        //
        $res['success'][] = "Records Found";
        $res['Data'] = json_encode($employeeResponse);
        return $doReturn ? $res : sendResponse(200, $res);
    }

    public function syncStatus () {
        _e($this->syncComplynetEmployeeStatus(0),true);
    }

    public function missingData () {
        _e($this->getComplynetEmployeeMissingData(0),true);
    }

}
