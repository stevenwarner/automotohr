<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Employee Performance Evaluation Document
 *
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package AutomotoHR
 */

class Employee_performance_evaluation extends CI_Controller
{

    /**
     * sessions holder
     * @var array
     */
    private $currentSession;

    /**
     * company session holder
     * @var array
     */
    private $loggedInCompanySession;

    /**
     * employee session holder
     * @var array
     */
    private $loggedInEmployeeSession;

    /**
     * holds the data
     * @var array
     */
    private $data;

    public function __construct()
    {
        // inherit parent
        parent::__construct();
        // load the model
        $this
            ->load
            ->model(
                "v1/Employee_performance_evaluation_model",
                "employee_performance_evaluation_model"
            );
        //
        $this->data = [];
    }

    /**
     * get the form by employee id
     *
     * @param int $employeeId
     * @return json
     */
    public function getEmployeeDocument(int $employeeId)
    {
        // check for protected route
        $this->protectedRoute();
        //
        $response = $this
            ->employee_performance_evaluation_model
            ->getEmployeeDocument(
                $employeeId
            );

        return SendResponse(
            200,
            [
                "success" => true,
                "data" => $response
            ]
        );
    }

    /**
     * handle document assignment
     *
     * @param int $employeeId
     * @return json
     */
    public function handleDocumentAssignment(int $employeeId)
    {
        // check for protected route
        $this->protectedRoute();
        //
        $action = $this->input->post("action", true);
        //
        $response = $this
            ->employee_performance_evaluation_model
            ->handleDocumentAssignment(
                $this->loggedInCompanySession["sid"],
                $this->loggedInEmployeeSession["sid"],
                $employeeId,
                $action
            );
        //
        return SendResponse(
            200,
            $response
        );
    }

    /**
     * load section
     *
     * @param int $employeeId
     * @param string $section
     * @return json
     */
    public function loadSection(
        int $employeeId,
        string $section
    ) {
        // check for protected route
        $this->protectedRoute();
        //
        $data = [];
        //
        if ($section == "one") {
            $sectionData = $this
                ->employee_performance_evaluation_model
                ->getEmployeeDocumentSection(
                    $employeeId,
                    'section_1_json'
                );
            //
            if ($sectionData['section_1_json']) {
                $data['section_1'] = json_decode($sectionData['section_1_json'], true)['data'];
            } else {
                //
                $data['section_1']['epe_employee_name'] = getUserNameBySID($employeeId);
                $data['section_1']['epe_job_title'] = $this->loggedInEmployeeSession['job_title'];
                $data['section_1']['epe_department'] = getDepartmentNameBySID($employeeId);
                $data['section_1']['epe_manager'] = $this->loggedInEmployeeSession['first_name'].' '.$this->loggedInEmployeeSession['last_name'];
                $data['section_1']['epe_hire_date'] = formatDateToDB(getUserStartDate($employeeId, true), DB_DATE, 'm-d-Y');
                $data['section_1']['epe_start_date'] = formatDateToDB(getUserStartDate($employeeId, true), DB_DATE, 'm-d-Y');
                $data['section_1']['epe_review_start'] = formatDateToDB(date('Y-m-d'), DB_DATE, 'm-d-Y');
                $data['section_1']['epe_review_end'] = formatDateToDB(date('Y-m-d'), DB_DATE, 'm-d-Y');
            }
        } else if ($section == "two") {
            $sectionData = $this
                ->employee_performance_evaluation_model
                ->getEmployeeDocumentSection(
                    $employeeId,
                    'section_2_json'
                );
            //
            if ($sectionData['section_2_json']) {
                $data['section_2'] = json_decode($sectionData['section_2_json'], true)['data'];
                $data['section_2_status'] = 'completed';
            } else {
                $data['section_2'] = [];
                $data['section_2_status'] = 'uncompleted';
            }
            //
            $data['companyName'] = $this->loggedInCompanySession['CompanyName'];
        }   
        //
        return SendResponse(
            200,
            [
                "view" => $this
                    ->load
                    ->view(
                        "employee_performance_evaluation/sections/{$section}",
                        $data,
                        true
                    )
            ]
        );
    }

    /**
     * check and set session
     *
     * @return void
     */
    private function protectedRoute()
    {
        // set the main session
        $this->currentSession = checkAndGetSession("all");
        // set company session
        $this->loggedInCompanySession = $this->currentSession["company_detail"];
        // set employee session
        $this->loggedInEmployeeSession = $this->currentSession["employer_detail"];
        // // attach sessions to passing data
        // $this->data['session'] = $this->currentSession;
        // // attach security_details to passing data
        // $this->data['security_details'] = db_get_access_level_details(
        //     $this->loggedInEmployeeSession['sid']
        // );
    }

    /**
     * load section
     *
     * @param int $employeeId
     * @param string $section
     * @return json
     */
    public function saveSectionData(
        int $employeeId,
        string $section
    ) {
        // check for protected route
        $this->protectedRoute();
        //
        $message = '';
        //
        if($section == "one") {
            $this->employee_performance_evaluation_model
                ->saveEmployeeDocumentSectionOne(
                    $employeeId,
                    [
                        "data" => $_POST,
                        "completed_at" => date('Y-m-d'),
                        "completed_by" => $this->loggedInEmployeeSession["sid"]
                    ]
                );
            //
            $message = 'Section one save successfully.';
        } else if ($section == "two") {
            $this->employee_performance_evaluation_model
                ->saveEmployeeDocumentSectionTwo(
                    $employeeId,
                    [
                        "data" => $_POST,
                        "completed_at" => date('Y-m-d'),
                        "completed_by" => $this->loggedInEmployeeSession["sid"]
                    ]
                );
            //
            $message = 'Data save successfully.';
        }
        //
        return SendResponse(
            200,
            [
                "success" => true,
                "message" => $message
            ]
        );
    }

    /**
     * print and download
     *
     * @param int $employeeId
     * @param string $userType
     * @param string $action
     *
     */
    public function handleDocumentAction (
        $employeeId,
        $userType,
        $action
    ) {
        // check for protected route
        $this->protectedRoute();
        //
        $page = '';
        $data = [];
        $data['action'] = $action;
        //
        if ($userType == "employee") {
            $sectionData = $this
                ->employee_performance_evaluation_model
                ->getEmployeeDocumentSection(
                    $employeeId,
                    'section_2_json',
                    'section_3_json'
                );
            //
            if ($sectionData['section_2_json']) {
                $data['section_2'] = json_decode($sectionData['section_2_json'], true)['data'];
            } else {
                $data['section_2'] = [];
            }  
            //
            if ($sectionData['section_3_json']) {
                $data['section_3'] = json_decode($sectionData['section_3_json'], true)['data'];
            } else {
                $data['section_3'] = [];
            } 
            //
            $page = "pd_employee_section";
        }
        //
        $this->load->view('employee_performance_evaluation/'.$page,$data);
    }
}
