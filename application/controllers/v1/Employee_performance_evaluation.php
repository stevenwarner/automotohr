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
        return SendResponse(
            200,
            [
                "view" => $this
                    ->load
                    ->view(
                        "employee_performance_evaluation/sections/{$section}",
                        [],
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
}
