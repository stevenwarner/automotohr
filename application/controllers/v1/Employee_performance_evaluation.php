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
     * @param string $userType
     * @return json
     */
    public function loadSection(
        int $employeeId,
        string $section,
        string $userType
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
                $userInfo = db_get_employee_profile($employeeId);
                //
                $data['section_1']['epe_employee_name'] = $userInfo[0]['first_name'] . ' ' . $userInfo[0]['last_name'];
                $data['section_1']['epe_job_title'] = $userInfo[0]['job_title'];
                $data['section_1']['epe_department'] = getDepartmentNameBySID($employeeId);
                $data['section_1']['epe_manager'] = $this->loggedInEmployeeSession['first_name'] . ' ' . $this->loggedInEmployeeSession['last_name'];
                $data['section_1']['epe_hire_date'] = formatDateToDB(getUserStartDate($employeeId, true), DB_DATE, 'm-d-Y');
                $data['section_1']['epe_start_date'] = formatDateToDB(getUserStartDate($employeeId, true), DB_DATE, 'm-d-Y');
            }
            //
            $data['employees'] = $this
                ->employee_performance_evaluation_model
                ->getCompanyActiveEmployees(
                    $this->loggedInCompanySession["sid"]
                );
            //
            $data['verification_managers'] = $this
                ->employee_performance_evaluation_model
                ->getVerificationManagers(
                    $employeeId,
                    1
                );    
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
        } else if ($section == "three") {
            $sectionData = $this
                ->employee_performance_evaluation_model
                ->getEmployeeDocumentSection(
                    $employeeId,
                    'section_3_json'
                );
            //
            if ($sectionData['section_3_json']) {
                $data['section_3'] = json_decode($sectionData['section_3_json'], true)['data'];
                $data['section_3_status'] = 'completed';
            } else {
                $data['section_3'] = [];
                $data['section_3_status'] = 'uncompleted';
            }
        } else if ($section == "four") {
            //
            $sectionData = $this
                ->employee_performance_evaluation_model
                ->getEmployeeSectionFourData(
                    $employeeId
                );
            //
            $data['companyName'] = $this->loggedInCompanySession["CompanyName"];
            $data['signDate'] = formatDateToDB(date('Y-m-d'), DB_DATE, 'm-d-Y');
            //
            if ($userType == "employee") {
                $data['section_2'] = json_decode($sectionData['section_2_json'], true)['data'];
                $data['section_3'] = json_decode($sectionData['section_3_json'], true)['data'];
                $data['user_type'] = "employee";
                //
                if (json_decode($sectionData['section_4_json'], true)['employee_signature_at']) {
                    $data['signDate'] = formatDateToDB(json_decode($sectionData['section_4_json'], true)['employee_signature_at'], DB_DATE_WITH_TIME, 'm-d-Y');
                }
                //
                $data['signature'] = $sectionData['employee_signature'];
            } else if ($userType == "manager") {
                $data['section_1'] = json_decode($sectionData['section_1_json'], true)['data'];
                $data['section_2'] = json_decode($sectionData['section_2_json'], true)['data'];
                $data['section_3'] = json_decode($sectionData['section_3_json'], true)['data'];
                $data['user_type'] = 'employer';
                //
                if (json_decode($sectionData['section_4_json'], true)['manager_signature_at']) {
                    $data['signDate'] = formatDateToDB(json_decode($sectionData['section_4_json'], true)['manager_signature_at'], DB_DATE_WITH_TIME, 'm-d-Y');
                }
                //
                $data['signature'] = $sectionData['manager_signature'];
            }
            //
        } else if ($section == "five") {
            $sectionData = $this
                ->employee_performance_evaluation_model
                ->getEmployeeDocumentSection(
                    $employeeId,
                    'section_5_json'
                );
            //
            if ($sectionData['section_5_json']) {
                $info = json_decode($sectionData['section_5_json'], true);
                //
                $data['current_pay'] = $info['current_pay'];
                $data['recommended_pay'] = $info['recommended_pay'];
                $data['companyName'] = $this->loggedInCompanySession["CompanyName"];
                $data['section_5_status'] = $info['is_completed'] == 1 ? 'completed' : 'uncompleted';
                $data['section_5_employee_type'] = 'HR_manager';
            } else {
                //
                $currentPayRate = $this
                    ->employee_performance_evaluation_model
                    ->getEmployeeCurrentPayRate(
                        $employeeId,
                    );   
                //
                $data['section_5'] = [];
                $data['section_5']['current_pay'] = $currentPayRate;
                $data['section_5_status'] = 'uncompleted';
                $data['section_5_employee_type'] = 'manager';
                $data['employees'] = $this
                    ->employee_performance_evaluation_model
                    ->getCompanyActiveEmployees(
                        $this->loggedInCompanySession["sid"]
                    );
            }
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
        $extra = [];
        //
        if ($section == "one") {
            $this->employee_performance_evaluation_model
                ->saveEmployeeDocumentSectionOne(
                    $employeeId,
                    [
                        "data" => $_POST,
                        "verified_at" => '',
                        "verified_by" => '',
                        "completed_at" => date('Y-m-d H:i:s'),
                        "completed_by" => $this->loggedInEmployeeSession["sid"]
                    ]
                );
            //
            $message = "The performance evaluation in section one has been saved successfully.";
            //
            $extra['verification_managers'] = $this
                ->employee_performance_evaluation_model
                ->getVerificationManagers(
                    $employeeId,
                    1
                );
        } else if ($section == "two") {
            $this->employee_performance_evaluation_model
                ->saveEmployeeDocumentSectionTwo(
                    $employeeId,
                    [
                        "data" => $_POST,
                        "completed_at" => date('Y-m-d H:i:s'),
                        "completed_by" => $this->loggedInEmployeeSession["sid"]
                    ]
                );
            //
            $message = 'The performance evaluation data has been saved successfully.';
        } else if ($section == "three") {
            $this->employee_performance_evaluation_model
                ->saveEmployeeDocumentSectionThree(
                    $employeeId,
                    [
                        "data" => $_POST,
                        "completed_at" => date('Y-m-d H:i:s'),
                        "completed_by" => $this->loggedInEmployeeSession["sid"]
                    ]
                );
            //
            $message = 'The performance evaluation in section three has been saved successfully.';
        } else if ($section == "four") {
            //
            $signature = get_e_signature(
                $this->loggedInCompanySession["sid"],
                $this->loggedInEmployeeSession["sid"],
                "employee"
            );
            //
            $sectionData = $this
                ->employee_performance_evaluation_model
                ->getEmployeeDocumentSection(
                    $employeeId,
                    'section_4_json'
                );
            //
            if (!$sectionData['section_4_json']) {
                //
                $this->employee_performance_evaluation_model
                    ->saveSignature(
                        $employeeId,
                        $_POST['user_type'],
                        $signature['signature_bas64_image']
                    );
                //
                $dataToUpdate = [];
                //
                $dataToUpdate['manager_signature_at'] = $_POST['user_type'] == "manager" ? date('Y-m-d H:i:s') : '';
                $dataToUpdate['manager_signature_by'] = $_POST['user_type'] == "manager" ? $this->loggedInEmployeeSession["sid"] : '';
                $dataToUpdate['employee_signature_at'] = $_POST['user_type'] == "employee" ? date('Y-m-d H:i:s') : '';
                $dataToUpdate['employee_signature_by'] = $_POST['user_type'] == "employee" ? $this->loggedInEmployeeSession["sid"] : '';
                $dataToUpdate['completed_at'] = '';
                $dataToUpdate['is_completed'] = 0;
                //
                $this->employee_performance_evaluation_model
                    ->saveEmployeeDocumentSectionFour(
                        $employeeId,
                        $dataToUpdate
                    );
            } else {
                $dataToUpdate = json_decode($sectionData['section_4_json'], true);
                //
                if ($_POST['user_type'] == "manager" && !$dataToUpdate['manager_signature_at']) {
                    //
                    $this->employee_performance_evaluation_model
                        ->saveSignature(
                            $employeeId,
                            $_POST['user_type'],
                            $signature['signature_bas64_image']
                        );
                    //
                    $dataToUpdate['manager_signature_at'] = date('Y-m-d H:i:s');
                    $dataToUpdate['manager_signature_by'] = $this->loggedInEmployeeSession["sid"];
                    $dataToUpdate['is_completed'] = 1;
                    $dataToUpdate['completed_at'] = date('Y-m-d H:i:s');
                } else if ($_POST['user_type'] == "employee" && !$dataToUpdate['employee_signature_at']) {
                    //
                    $this->employee_performance_evaluation_model
                        ->saveSignature(
                            $employeeId,
                            $_POST['user_type'],
                            $signature['signature_bas64_image']
                        );
                    //
                    $dataToUpdate['employee_signature_at'] = date('Y-m-d H:i:s');
                    $dataToUpdate['employee_signature_by'] = $this->loggedInEmployeeSession["sid"];
                    $dataToUpdate['is_completed'] = 1;
                    $dataToUpdate['completed_at'] = date('Y-m-d H:i:s');
                }
                //
                $this->employee_performance_evaluation_model
                    ->saveEmployeeDocumentSectionFour(
                        $employeeId,
                        $dataToUpdate
                    );
            }
            //
            $message = 'The performance evaluation in section four has been saved successfully.';
        } else if ($section == "five") {
            //
            if ($_POST['user_type'] == "manager") {
                $this->employee_performance_evaluation_model
                    ->saveEmployeeDocumentSectionFive(
                        $employeeId,
                        [
                            "current_pay" => $_POST['current_pay'],
                            "recommended_pay" => $_POST['recommended_pay'],
                            "manager_completed_at" => date('Y-m-d H:i:s'),
                            "manager_completed_by" => $this->loggedInEmployeeSession["sid"]
                        ]
                    );
            } else if ($_POST['user_type'] == "HR_manager") {
                //
                $sectionData = $this
                    ->employee_performance_evaluation_model
                    ->getEmployeeDocumentSection(
                        $employeeId,
                        'section_5_json'
                    );
                // 
                $info = json_decode($sectionData['section_5_json'], true);
                //
                $this->employee_performance_evaluation_model
                    ->saveEmployeeDocumentSectionFive(
                        $employeeId,
                        [
                            "current_pay" => $info['current_pay'],
                            "recommended_pay" => $info['recommended_pay'],
                            "approved_amount" => $_POST['approved_amount'],
                            "effective_increase_date" => $_POST['effective_increase_date'],
                            "manager_completed_at" => $info['manager_completed_at'],
                            "manager_completed_by" => $info['manager_completed_by'],
                            "hr_manager_completed_at" => date('Y-m-d H:i:s'),
                            "hr_manager_completed_by" => $this->loggedInEmployeeSession["sid"],
                            "is_completed" => 1
                        ]
                    );
                //
                $signature = get_e_signature(
                    $this->loggedInCompanySession["sid"],
                    $this->loggedInEmployeeSession["sid"],
                    "employee"
                );
                //
                $this->employee_performance_evaluation_model
                    ->saveSignature(
                        $employeeId,
                        $_POST['user_type'],
                        $signature['signature_bas64_image']
                    );
            }

            //
            $message = 'The performance evaluation in section five has been saved successfully.';
        }
        //
        return SendResponse(
            200,
            [
                "success" => true,
                "message" => $message,
                "extra" => $extra
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
    public function handleDocumentAction(
        $employeeId,
        $userType,
        $action
    ) {
        // check for protected route
        $this->protectedRoute();
        //
        $data = [];
        $data['action'] = $action;
        $data['userType'] = $userType;

        //
        $sectionData = $this
            ->employee_performance_evaluation_model
            ->getEmployeePerformanceDocumentData(
                $employeeId,
                [
                    'section_1_json',
                    'section_2_json',
                    'section_3_json',
                    'section_4_json',
                    'section_5_json',
                    'employee_signature',
                    'manager_signature',
                    'hr_signature'
                ]
            );
        //
        if (!$sectionData['section_1_json']) {
            //
            $userInfo = db_get_employee_profile($employeeId);
            //
            $data['defaultData']['epe_employee_name'] = $userInfo[0]['first_name'] . ' ' . $userInfo[0]['last_name'];
            $data['defaultData']['epe_job_title'] = $userInfo[0]['job_title'];
            $data['defaultData']['epe_department'] = getDepartmentNameBySID($employeeId);
            $data['defaultData']['epe_manager'] = $this->loggedInEmployeeSession['first_name'] . ' ' . $this->loggedInEmployeeSession['last_name'];
            $data['defaultData']['epe_hire_date'] = formatDateToDB(getUserStartDate($employeeId, true), DB_DATE, 'm-d-Y');
            $data['defaultData']['epe_start_date'] = formatDateToDB(getUserStartDate($employeeId, true), DB_DATE, 'm-d-Y');
        }
        $data['sectionData'] = $sectionData;
        $data['companyName'] = $this->loggedInCompanySession["CompanyName"];
        //
        $this->load->view('employee_performance_evaluation/pd_performance_page_new', $data);
    }

    /**
     * print and download
     *
     * @param int $employeeId
     * @return json
     *
     */
    function sendVerificationRequest($employeeId, $section)
    {
        // check for protected route
        $this->protectedRoute();
        //
        if ($_POST['employees']) {
            //
            $this
                ->employee_performance_evaluation_model
                ->deleteSectionVerificationManagers($employeeId, $section);
            //
            $employees = array_column($_POST['employees'], "employee_sid");
            //
            $message_hf = message_header_footer(
                $this->loggedInCompanySession["sid"],
                $this->loggedInCompanySession["CompanyName"]
            );
            //
            foreach ($employees as $managerId) {
                //
                $managerEmail = get_employee_profile_info($managerId)['email'];
                $button = getButton(
                    [
                        '{{url}}' => base_url('fillable/epe/verification_request/' . $employeeId . '/' . $managerId . '/1/approved'),
                        '{{color}}' => '#4CBB17',
                        '{{text}}' => 'Approve Performance Document'
                    ]
                );
                //
                $message_body = '';
                $message_body .= $message_hf['header'];
                $message_body .= '<p>' . 'Dear <strong>' . getUserNameBySID($managerId) . '</strong>,' . '</p>';
                $message_body .= '<p>' . 'A new performance review has been assigned to you with the following details' . '</p>';
                $message_body .= '<p>' . 'Payment Details are as Follows: ' . '</p>';
                $message_body .= '<p><u><strong>Details:</strong></u></p>';
                $message_body .= '<p>' . 'Employee Name: ' .  getUserNameBySID($employeeId) . '</p>';
                $message_body .= '<p>' . $button . '</p>';
                $message_body .= '<p>' . STORE_NAME . '</p>';
                $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
                $message_body .= $message_hf['footer'];
                //
                log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $managerEmail, "Verification Performance Evaluation Document", $message_body, FROM_STORE_NAME);
                //
                $dataToInsert = [];
                $dataToInsert['company_sid'] = $this->loggedInCompanySession["sid"];
                $dataToInsert['employee_sid'] = $employeeId;
                $dataToInsert['manager_sid'] = $managerId;
                $dataToInsert['section'] = $section;
                $dataToInsert['created_at'] = date('Y-m-d H:i:s');
                $dataToInsert['created_by'] = $this->loggedInEmployeeSession["sid"];
                //
                $this
                    ->employee_performance_evaluation_model
                    ->saveVerificationManager(
                        $dataToInsert
                    );
                //
            }
        }

        return SendResponse(
            200,
            [
                "success" => true,
                "message" => "The verification email was successfully sent to the manager for performance evaluation."
            ]
        );
    }

    function pendingVerificationDocuments()
    {
        // // check for protected route
        $this->protectedRoute();

        $companyId = $this->loggedInCompanySession["sid"];
        $employeeId = $this->loggedInEmployeeSession["sid"];
        $securityDetails = db_get_access_level_details($employeeId);
        getCompanyEmsStatusBySid($companyId);
        //
        // For verification documents
        $pendingDocuments = $this
            ->employee_performance_evaluation_model
            ->getAssignedPendingVerificationDocuments(
                $employeeId
            );
        // 
        //
        $data['pendingDocuments'] = $pendingDocuments;
        $data['session'] = $this->currentSession;
        $data['company_sid'] = $companyId;
        $data['security_details'] = $securityDetails;
        $data['title'] = 'Pending Performance Verification Section';
        //
        $data['employee'] = $this->loggedInEmployeeSession;
        $this->load->view('onboarding/on_boarding_header', $data);
        $this->load->view('employee_performance_evaluation/pending_verification_document_ems');
        $this->load->view('onboarding/on_boarding_footer');
    }

    function getPendingVerificationDocument($employeeId, $section)
    {
        // // check for protected route
        $this->protectedRoute();

        $companyId = $this->loggedInCompanySession["sid"];
        $employerId = $this->loggedInEmployeeSession["sid"];
        $securityDetails = db_get_access_level_details($employerId);
        getCompanyEmsStatusBySid($companyId);
        //
        // For verification documents
        if ($section == 1) {
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
                $data['section_1'] = [];
            }
        }
        //
        $data['pendingDocuments'] = $pendingDocuments;
        $data['session'] = $this->currentSession;
        $data['company_sid'] = $companyId;
        $data['employeeId'] = $employeeId;
        $data['managerId'] = $employerId;
        $data['security_details'] = $securityDetails;
        $data['title'] = 'Pending Performance Verification Section';
        //
        $data['employee'] = $this->loggedInEmployeeSession;
        $this->load->view('onboarding/on_boarding_header', $data);
        $this->load->view('employee_performance_evaluation/pending_verification_document');
        $this->load->view('onboarding/on_boarding_footer');
    }

    public function completeVerificationRequest(
        $employeeId,
        $managerId,
        $section,
        $status
    ) {
        // // check for protected route
        $this->protectedRoute();
        //
        if ($section == 1 && $status == 'approved') {
            $sectionData = $this
                ->employee_performance_evaluation_model
                ->getEmployeeDocumentSection(
                    $employeeId,
                    'section_1_json'
                );


            //
            $sectionOne = json_decode($sectionData['section_1_json'], true);
            //
            $this->employee_performance_evaluation_model
                ->saveEmployeeDocumentSectionOne(
                    $employeeId,
                    [
                        "data" => $sectionOne['data'],
                        "verified_at" => date('Y-m-d H:i:s'),
                        "verified_by" => $managerId,
                        "completed_at" => $sectionOne['completed_at'],
                        "completed_by" => $sectionOne['completed_by']
                    ]
                );
            // 
            $this->employee_performance_evaluation_model
                ->updateVerificationManagers(
                    $employeeId,
                    $section
                );
            //
            redirect("fillable/epe/verification/documents", "location");
        }
    }

    function getEmployeeSignature()
    {
        // check for protected route
        $this->protectedRoute();

        $companyId = $this->loggedInCompanySession["sid"];
        $employeeId = $this->loggedInEmployeeSession["sid"];
        $signature = get_e_signature($companyId, $employeeId, "employee");
        //
        $return_data = array();
        //
        if (!empty($signature)) {

            //
            return SendResponse(
                200,
                [
                    "success" => true,
                    "message" => "Signature found.",
                    "signature_base64" => $signature['signature_bas64_image']
                ]
            );
        } else {
            //
            return SendResponse(
                200,
                [
                    "success" => false,
                    "message" => "Please save your signature first."
                ]
            );
        }
    }
}
