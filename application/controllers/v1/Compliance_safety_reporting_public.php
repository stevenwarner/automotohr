<?php defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'controllers/csp/Base_csp.php';

/**
 * Indeed controller to handle all new
 * events
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 */
class Compliance_safety_reporting_public extends Base_csp
{
    public function __construct()
    {
        parent::__construct(false);
    }

    /**
     * check and set the proper data session
     */
    public function view(string $code)
    {
        // Verify token and get the record id
        if (!$this->compliance_report_model->verifyToken($code)) {
            return redirect("/");
        }
        // get the details by token
        $tokenDetails = $this->compliance_report_model->getTokenDetails($code);

        // Set session data
        $this->session->set_userdata('tokenDetails', $tokenDetails);

        // get the specific report or incident
        if ((int) $tokenDetails["csp_report_incident_sid"] !== 0) {
            //
            return $this->editReportIncident($tokenDetails["csp_reports_sid"], $tokenDetails["csp_report_incident_sid"]);
        } else {
            return $this->edit($tokenDetails["csp_reports_sid"]);
        }
    }

    public function dashboard()
    {

        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Dashboard';
        // load JS
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts-more.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/exporting.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/export-data.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/accessibility.js';
        $this->data['pageJs'][] = 'csp/dashboard';
        // get filter
        $this->data["filter"] = [
            "severity_level" => $this->input->get("severityLevel", true) ?? "-1",
            "incident" => $this->input->get("incidentType", true) ?? "-1",
            "status" => $this->input->get("status", true) ?? "pending",
        ];

        // get all the incidents
        $this->data["incidents"] = $this
            ->compliance_report_model
            ->getAllEmployeeIncidentsWithReportsPublic(
                $this->getPublicSessionData("company_sid"),
                $this->getPublicSessionData("is_external_employee") == 1
                ? $this->getPublicSessionData("external_email")
                : $this->getPublicSessionData("employee_sid")
            );

        // get the reports
        $this->data["reports"] = $this
            ->compliance_report_model
            ->getAllEmployeeItemsWithIncidentsCPAPublic(
                $this->getPublicSessionData("company_sid"),
                $this->getPublicSessionData("is_external_employee") == 1
                ? $this->getPublicSessionData("external_email")
                : $this->getPublicSessionData("employee_sid"),
                $this->data["filter"]
            );
        //
        $this->renderView('compliance_safety_reporting/public/dashboard');
    }


    /**
     * process edit
     * 
     * @param int $reportId
     * @param int $incidentId
     */
    public function processNotes(int $reportId, int $incidentId)
    {
        $this->form_validation->set_rules('type', 'Note Type', 'required');
        $this->form_validation->set_rules('content', 'Note', 'required');

        if (!$this->form_validation->run()) {
            return sendResponse(
                400,
                ["errors" => explode("\n", validation_errors())]
            );
        }
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $noteId = $this->compliance_report_model->addNotesToReport(
            $reportId,
            $incidentId,
            $this->getPublicSessionData("employee_sid")
            ? $this->getPublicSessionData("employee_sid")
            : 0,
            $post
        );
        //
        if ($this->getPublicSessionData("is_external_employee") == 1 && $post["type"] == 'employee') {
            // Save log on add note
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => $incidentId,
                    'incidentItemId' => 0,
                    'type' => 'notes',
                    'userType' => 'external',
                    'userId' => $this->getPublicSessionData("external_email"),
                    'jsonData' => [
                        'action' => 'create',
                        'type' => 'employee_note',
                        'noteId' => $noteId,
                        'dateTime' => getSystemDate()
                    ]
                ]
            );
        }
        // return the success
        return sendResponse(
            200,
            ["message" => "Notes added successfully."]
        );
    }

    public function processFiles(int $reportId, int $incidentId, string $type)
    {
        //
        $post = $this->input->post(null, true);

        if ($this->getPublicSessionData("employee_sid")) {
            $main = [
                "first_name" => $this->getPublicSessionData("first_name"),
                "last_name" => $this->getPublicSessionData("last_name"),
                "middle_name" => $this->getPublicSessionData("middle_name"),
                "job_title" => $this->getPublicSessionData("job_title"),
                "access_level" => $this->getPublicSessionData("access_level"),
                "access_level_plus" => $this->getPublicSessionData("access_level_plus"),
                "is_executive_admin" => $this->getPublicSessionData("is_executive_admin"),
                "pay_plan_flag" => $this->getPublicSessionData("pay_plan_flag"),
            ];
        } else {
            $main = [
                "first_name" => $this->getPublicSessionData("external_name"),
                "last_name" => "",
                "middle_name" => "",
                "job_title" => "",
                "access_level" => "",
                "access_level_plus" => "",
                "is_executive_admin" => "",
                "pay_plan_flag" => "",
            ];
        }

        if ($post["link"]) {
            $id = $this
                ->compliance_report_model
                ->addFilesLinkToReport(
                    $reportId,
                    $incidentId,
                    0,
                    $this->getPublicSessionData("employee_sid")
                    ? $this->getPublicSessionData("employee_sid")
                    : 0,
                    $post["link"],
                    $post["type"],
                    $this->input->post("title")
                );
            $sd = [
                "sid" => $id,
                "title" => $this->input->post("title"),
                "created_at" => getSystemDate(),
            ];
            $sd = array_merge($sd, $main);
            //
            if ($this->getPublicSessionData("is_external_employee") == 1) {
                //
                $loggedInEmployeeName = getManualUserNameByEmailId(
                    $reportId,
                    $incidentId,
                    $this->getPublicSessionData("external_email")
                );
                //
                $dataToUpdate = [
                    "last_modified_by" => $loggedInEmployeeName
                ];
                //
                if ($reportId != 0 && $incidentId == 0) {
                    $this->compliance_report_model->addManualUserEmail(
                        $reportId,
                        $dataToUpdate,
                        'csp_reports'
                    );
                }

                if ($incidentId != 0 && $itemId == 0) {
                    $this->compliance_report_model->addManualUserEmail(
                        $incidentId,
                        $dataToUpdate,
                        'csp_reports_incidents'
                    );
                }
                //
                $this->compliance_report_model->addManualUserEmail(
                    $id,
                    [
                        'manual_email' => $this->getPublicSessionData("external_email")
                    ],
                    'csp_reports_files'
                );
                // Save log on Add file Link
                $this->compliance_report_model->saveComplianceSafetyReportLog(
                    [
                        'reportId' => $reportId,
                        'incidentId' => $incidentId,
                        'incidentItemId' => 0,
                        'type' => 'files',
                        'userType' => 'external',
                        'userId' => $this->getPublicSessionData("external_email"),
                        'jsonData' => [
                            'action' => 'create',
                            'type' => 'link',
                            'title' => $this->input->post("title"),
                            'fileId' => $id,
                            'dateTime' => getSystemDate()
                        ]
                    ]
                );
            }
            // return the success
            return sendResponse(
                200,
                [
                    "message" => "File is attached successfully.",
                    "view" => $this->load->view(
                        'compliance_safety_reporting/partials/file',
                        [
                            "document" => $sd,
                            "public" => true
                        ],
                        true
                    ),
                ]
            );
        } else {
            // sanitize the name
            $fileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $_FILES['file']['name']);
            $fileName = str_replace(' ', '_', $fileName);
            $fileName = time() . '_' . $fileName;
            $fileName = strtolower($fileName);

            $this->load->library('aws_lib');
            //
            try {
                $options = [
                    'Bucket' => AWS_S3_BUCKET_NAME,
                    'Key' => $fileName,
                    'Body' => file_get_contents($_FILES["file"]["tmp_name"]),
                    'ACL' => 'public-read',
                    'ContentType' => $_FILES["file"]["type"]
                ];
                //
                $this->aws_lib->put_object($options);
                $id = $this
                    ->compliance_report_model
                    ->addFilesToReport(
                        $reportId,
                        $incidentId,
                        0,
                        $this->getPublicSessionData("employee_sid")
                        ? $this->getPublicSessionData("employee_sid")
                        : 0,
                        $fileName,
                        $_FILES["file"]["name"],
                        $type,
                        $this->input->post("title")
                    );
                //
                $sd = [
                    "sid" => $id,
                    "title" => $this->input->post("title"),
                    "created_at" => getSystemDate(),
                ];
                $sd = array_merge($sd, $main);
                //
                if ($this->getPublicSessionData("is_external_employee") == 1) {
                    //
                    $loggedInEmployeeName = getManualUserNameByEmailId(
                        $reportId,
                        $incidentId,
                        $this->getPublicSessionData("external_email")
                    );
                    //
                    $dataToUpdate = [
                        "last_modified_by" => $loggedInEmployeeName
                    ];
                    //
                    if ($reportId != 0 && $incidentId == 0) {
                        $this->compliance_report_model->addManualUserEmail(
                            $reportId,
                            $dataToUpdate,
                            'csp_reports'
                        );
                    }

                    if ($incidentId != 0 && $itemId == 0) {
                        $this->compliance_report_model->addManualUserEmail(
                            $incidentId,
                            $dataToUpdate,
                            'csp_reports_incidents'
                        );
                    }
                    //
                    $this->compliance_report_model->addManualUserEmail(
                        $id,
                        [
                            'manual_email' => $this->getPublicSessionData("external_email")
                        ],
                        'csp_reports_files'
                    );
                    // Save log on Add file
                    $this->compliance_report_model->saveComplianceSafetyReportLog(
                        [
                            'reportId' => $reportId,
                            'incidentId' => $incidentId,
                            'incidentItemId' => 0,
                            'type' => 'files',
                            'userType' => 'external',
                            'userId' => $this->getPublicSessionData("external_email"),
                            'jsonData' => [
                                'action' => 'create',
                                'type' => $type,
                                'title' => $this->input->post("title"),
                                'fileId' => $id,
                                'dateTime' => getSystemDate()
                            ]
                        ]
                    );
                }
                // return the success
                return sendResponse(
                    200,
                    [
                        "message" => "File is attached successfully.",
                        "view" => $this->load->view(
                            'compliance_safety_reporting/partials/file',
                            [
                                "document" => $sd,
                                "public" => true
                            ],
                            true
                        ),
                    ]
                );
                //
            } catch (Exception $exception) {
                return sendResponse(
                    400,
                    ["errors" => ["Failed to upload file to AWS S3"]]
                );
            }
        }
    }

    /**
     * download file
     *
     * @param int $fileId
     */
    public function downloadFile(int $fileId)
    {
        // Get the file details from the database
        $file = $this->compliance_report_model->getFileById($fileId, [
            "s3_file_value",
            "file_value",
            "csp_reports_sid",
            "csp_incident_type_sid",
            "csp_reports_incidents_items_sid"
        ]);
        //
        if (!$file) {
            return redirect()->back();
        }
        // Get the file from S3
        $this->load->library('aws_lib');
        //
        $fileContent = $this->aws_lib->get_object(
            AWS_S3_BUCKET_NAME,
            $file['s3_file_value']
        );
        //
        if (!$fileContent) {
            return redirect()->back();
        }
        //
        //
        $logType = 'main';
        //
        if ($file['csp_reports_sid'] != 0 && $file['csp_reports_incidents_items_sid'] != 0) {
            $logType = 'incidents';
        } else if ($file['csp_reports_sid'] != 0 && $file['csp_reports_incidents_items_sid'] != 0 && $file['csp_reports_incidents_items_sid'] != 0) {
            $logType = 'incident_item';
        }
        //
        $userId = '';
        $userType = '';
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $userId = $this->getPublicSessionData("external_email");
            $userType = 'external';
        } else {
            $userId = $this->getPublicSessionData("employee_sid");
            $userType = 'employee';
        }
        //
        // Save log on view file
        $this->compliance_report_model->saveComplianceSafetyReportLog(
            [
                'reportId' => $file['csp_reports_sid'],
                'incidentId' => $file['csp_incident_type_sid'],
                'incidentItemId' => $file['csp_reports_incidents_items_sid'],
                'type' => $logType,
                'userType' => $userType,
                'userId' => $userId,
                'jsonData' => [
                    'action' => 'download ' . $file['file_type'],
                    'file_id' => $fileId,
                    'file_name' => $file['title'],
                    'dateTime' => getSystemDate()
                ]
            ]
        );
        //
        header("Content-Type: {$fileContent->get("ContentType")}");
        header("Content-Disposition: attachment; filename=\"" . basename($file['file_value']) . "\"");
        header("Content-Length: " . strlen($fileContent['Body']));
        echo $fileContent['Body'];
    }

    /**
     * download file
     *
     * @param int $fileId
     */
    public function viewFile(int $fileId)
    {
        // Get the file details from the database
        $file = $this->compliance_report_model->getFileById($fileId, [
            "s3_file_value",
            "title",
            "file_type",
            "file_value",
            "csp_reports_sid",
            "csp_incident_type_sid",
            "csp_reports_incidents_items_sid"
        ]);
        //
        if (!$file) {
            return SendResponse(400, ["errors" => "Failed to verify the file."]);
        }
        //
        $logType = 'main';
        //
        if ($file['csp_reports_sid'] != 0 && $file['csp_reports_incidents_items_sid'] != 0) {
            $logType = 'incidents';
        } else if ($file['csp_reports_sid'] != 0 && $file['csp_reports_incidents_items_sid'] != 0 && $file['csp_reports_incidents_items_sid'] != 0) {
            $logType = 'incident_item';
        }
        //
        $userId = '';
        $userType = '';
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $userId = $this->getPublicSessionData("external_email");
            $userType = 'external';
        } else {
            $userId = $this->getPublicSessionData("employee_sid");
            $userType = 'employee';
        }
        //
        // Save log on view file
        $this->compliance_report_model->saveComplianceSafetyReportLog(
            [
                'reportId' => $file['csp_reports_sid'],
                'incidentId' => $file['csp_incident_type_sid'],
                'incidentItemId' => $file['csp_reports_incidents_items_sid'],
                'type' => $logType,
                'userType' => $userType,
                'userId' => $userId,
                'jsonData' => [
                    'action' => 'view ' . $file['file_type'],
                    'file_id' => $fileId,
                    'file_name' => $file['title'],
                    'dateTime' => getSystemDate()
                ]
            ]
        );
        //
        return sendResponse(
            200,
            [
                "view" => $this->load->view(
                    'compliance_safety_reporting/partials/view',
                    [
                        "file" => $file
                    ],
                    true
                ),
                "data" => $file
            ]
        );
    }

    //
    private function getPublicSessionData($index)
    {
        $sessionData = $this->session->userdata('tokenDetails');
        return $sessionData[$index];
    }

    //
    private function checkPublicSession()
    {
        $sessionData = $this->session->userdata('tokenDetails');
        if (!$sessionData) {
            if ($this->input->is_ajax_request()) {
                return sendResponse(404, ["errors" => ["Session not found."]]);
            } else {
                return redirect("/");
            }
        }

        $this->data['template'] = message_header_footer(
            $this->getPublicSessionData("company_sid"),
            getCompanyColumnById($this->getPublicSessionData("company_sid"), "CompanyName")["CompanyName"]
        );
    }

    public function sendComplianceReportEmail()
    {
        //
        $this->checkPublicSession();
        $companyId = $this->getPublicSessionData("company_sid");
        $companyName = getCompanyNameBySid($companyId);
        $employeeId = 0;
        $employeeType = '';
        $employeeEmail = '';
        $employeeName = '';
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $employeeType = 'external';
            $employeeEmail = $this->getPublicSessionData("external_email");
            $employeeName = $this->getPublicSessionData("external_name");
        } else {
            $employeeType = 'internal';
            $employeeId = $this->getPublicSessionData("employee_sid");
            $employeeName = getEmployeeOnlyNameBySID($employeeId);
        }
        //
        //
        $send_email_type = $_POST['send_type'];
        $attachments = isset($_POST['attach_files']) ? explode(',', $_POST['attach_files']) : [];
        $reportId = $_POST['report_id'];
        $incidentId = $_POST['incident_id'];
        $itemId = $_POST['item_id'];
        //
        $email_hf = message_header_footer_domain($companyId, $companyName);
        //
        if ($send_email_type == 'manual') {
            //
            $manual_email = $_POST['manual_email'];
            //
            $receiver_name = '';
            $conversation_key = '';
            //
            $isEmployee = $this->compliance_report_model->checkManualUserIsAnEmployee($manual_email, $companyId);
            $receiverId = '';
            //
            if ($isEmployee) {
                $manualUserInfo = $this->compliance_report_model->getUserInfoByEmail($manual_email, $companyId);
                $conversation_key = $reportId . '/' . $incidentId . '/' . $itemId . '/' . $manualUserInfo['sid'] . '/' . $employeeId;
                $receiver_name = $manualUserInfo['first_name'] . ' ' . $manualUserInfo['last_name'];
                $receiverId = $manualUserInfo['sid'];
            } else {
                $conversation_key = $reportId . '/' . $incidentId . '/' . $itemId . '/' . $manual_email . '/' . $employeeId;
                $name = explode("@", $manual_email);
                $receiver_name = $name[0];
            }
            //
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            //
            $manual_email_to_insert = array();
            $manual_email_to_insert['csp_reports_sid'] = $reportId;
            $manual_email_to_insert['csp_incident_type_sid'] = $incidentId;
            $manual_email_to_insert['csp_reports_incidents_items_sid'] = $itemId;
            //
            if ($isEmployee) {
                $manual_email_to_insert['receiver_sid'] = $receiverId;
            } else {
                $manual_email_to_insert['manual_email'] = $manual_email;
                $manual_email_to_insert['receiver_sid'] = 0;
            }
            //
            $manual_email_to_insert['sender_sid'] = $employeeId;
            $manual_email_to_insert['subject'] = $subject;
            $manual_email_to_insert['message_body'] = $message;
            //
            $inserted_email_sid = $this->compliance_report_model->addComplianceReportEmail($manual_email_to_insert);
            //
            if (!empty($attachments)) {
                //
                foreach ($attachments as $attachmentId) {

                    $insert_attachment = array();
                    $insert_attachment['csp_reports_email_sid'] = $inserted_email_sid;
                    $insert_attachment['csp_reports_file_sid'] = $attachmentId;
                    $insert_attachment['attached_by'] = $employeeId;
                    $insert_attachment['attached_date'] = date('Y-m-d H:i:s');

                    $this->compliance_report_model->addComplianceEmailAttachment($insert_attachment);
                }
            }
            //
            $url = base_url('compliance_safety_reporting_public/view_compliance_safety_report_email/' . $conversation_key);
            $from_name = $employeeName;
            //
            $emailTemplateBody = 'Dear ' . $receiver_name . ', <br>';
            $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email about compliance safety report.</p>' . '<br>';
            $emailTemplateBody = $emailTemplateBody . '<p>Please click on the following link to reply.</p>' . '<br>';
            //
            if ($isEmployee) {
                $userKey = $this->compliance_report_model->checkAndGetComplianceSafetyReportUserKey($receiverId, $reportId, $incidentId);
                //
            } else {
                $userKey = $this->compliance_report_model->checkAndGetComplianceSafetyReportUserKey($manual_email, $reportId, $incidentId);
                //
                // $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $approval_public_link_accept . '">View Compliance Report</a>' . '<br>';
                // $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
            }
            $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Reply to this Email</a>' . '<br>';
            $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
            $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email: Please Do Not reply!</strong>' . '<br>';
            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';

            $from = FROM_EMAIL_NOTIFICATIONS;
            $to = $manual_email;

            $body = $email_hf['header']
                . $emailTemplateBody
                . $email_hf['footer'];

            log_and_sendEmail($from, $to, $subject, $body, $from_name);
            //
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => $incidentId,
                    'incidentItemId' => $itemId,
                    'type' => 'emails',
                    'userType' => $employeeType,
                    'userId' => $employeeType == 'external' ? $employeeEmail : $employeeId,
                    'jsonData' => [
                        'action' => 'send email',
                        'dateTime' => getSystemDate(),
                        'receiver' => $to,
                        'subject' => $_POST['subject'],
                        'message' => $_POST['message']
                    ]
                ]
            );
            //
        } else if ($send_email_type == 'system') {
            //
            $receivers = explode(',', $_POST['receivers']);
            $subject = $_POST['subject'];
            $from_name = $employeeName;
            $email_hf = message_header_footer_domain($companyId, $companyName);
            //
            foreach ($receivers as $key => $receiver_id) {
                //
                $conversation_key = '';
                $message_body = $_POST['message'];

                $data_to_insert = array();
                $data_to_insert['csp_reports_sid'] = $reportId;
                $data_to_insert['csp_incident_type_sid'] = $incidentId;
                $data_to_insert['csp_reports_incidents_items_sid'] = $itemId;
                //
                if ($employeeType == 'internal') {
                    $data_to_insert['sender_sid'] = $employeeId;
                    $conversation_key = $reportId . '/' . $incidentId . '/' . $itemId . '/' . $receiver_id . '/' . $employeeId;
                } else if ($employeeType == 'external') {
                    $data_to_insert['sender_sid'] = 0;
                    $data_to_insert['manual_email'] = $employeeEmail;
                    $conversation_key = $reportId . '/' . $incidentId . '/' . $itemId . '/' . $receiver_id . '/' . $employeeEmail;
                }
                //
                $data_to_insert['sender_sid'] = $employeeId;
                $data_to_insert['subject'] = $subject;
                $data_to_insert['message_body'] = $message_body;
                //
                $manager_info = db_get_employee_profile($receiver_id);
                $receiver_email = $manager_info[0]['email'];
                $receiver_name = $manager_info[0]['first_name'] . ' ' . $manager_info[0]['last_name'];
                $data_to_insert['receiver_sid'] = $receiver_id;
                //
                $inserted_email_sid = $this->compliance_report_model->addComplianceReportEmail($data_to_insert);

                if (!empty($attachments)) {
                    //
                    foreach ($attachments as $attachmentId) {

                        $insert_attachment = array();
                        $insert_attachment['csp_reports_email_sid'] = $inserted_email_sid;
                        $insert_attachment['csp_reports_file_sid'] = $attachmentId;
                        $insert_attachment['attached_by'] = $employeeId;
                        $insert_attachment['attached_date'] = date('Y-m-d H:i:s');

                        $this->compliance_report_model->addComplianceEmailAttachment($insert_attachment);
                    }
                }

                $url = base_url('compliance_safety_reporting_public/view_compliance_safety_report_email/' . $conversation_key);
                $userKey = $this->compliance_report_model->checkAndGetComplianceSafetyReportUserKey($receiver_id, $reportId, $incidentId);
                //
                $emailTemplateBody = 'Dear ' . $receiver_name . ', <br>';
                $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email about compliance report.</p>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<p>Please click on the following link to reply.</p>' . '<br>';
                if ($employeeType != "out_sider") {
                    //
                    // $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Compliance Report</a>' . '<br>';
                    // $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                }
                $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Reply to this Email</a>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email: Please Do Not reply!</strong>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';

                $from = FROM_EMAIL_NOTIFICATIONS;
                $to = $receiver_email;

                $body = $email_hf['header']
                    . $emailTemplateBody
                    . $email_hf['footer'];

                log_and_sendEmail($from, $to, $subject, $body, $from_name);
                //
            }
            //
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => $incidentId,
                    'incidentItemId' => $itemId,
                    'type' => 'emails',
                    'userType' => $employeeType,
                    'userId' => $employeeType == 'external' ? $employeeEmail : $employeeId,
                    'jsonData' => [
                        'action' => 'send email',
                        'dateTime' => getSystemDate(),
                        'receiver' => $receivers,
                        'subject' => $_POST['subject'],
                        'message' => $_POST['message']
                    ]
                ]
            );
        }
        //
        return sendResponse(
            200,
            ["message" => "Email send successfully."]
        );
    }

    public function saveEmailManualAttachment()
    {
        $this->checkPublicSession();
        $companyId = $this->getPublicSessionData("company_sid");
        $employeeId = 0;
        $employeeType = '';
        $employeeEmail = '';
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $employeeType = 'external';
            $employeeEmail = $this->getPublicSessionData("external_email");
        } else {
            $employeeType = 'internal';
            $employeeId = $this->getPublicSessionData("employee_sid");
        }
        //
        $item_title = $_POST['attachment_title'];
        $report_sid = $_POST['report_sid'];
        $incident_sid = $_POST['incident_sid'];
        $item_source = $_POST['file_type'];
        //
        if ($item_source == 'upload_document') {
            if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0) {

                $file_name = $_POST['file_name'];
                $file_extension = $_POST['file_ext'];
                $upload_incident_doc = upload_file_to_aws('file', $companyId, 'file', '', AWS_S3_BUCKET_NAME);

                if (!empty($upload_incident_doc) && $upload_incident_doc != 'error') {
                    //
                    $insert_document_sid = 0;
                    //
                    if ($employeeType == 'internal') {
                        $insert_document_sid = $this
                            ->compliance_report_model
                            ->addFilesLinkToReport(
                                $report_sid,
                                $incident_sid,
                                $employeeId,
                                $upload_incident_doc,
                                "document",
                                $item_title
                            );
                    } else if ($employeeType == 'external') {
                        $insert_document_sid = $this
                            ->compliance_report_model
                            ->addExternalEmployeeFilesLinkToReport(
                                $report_sid,
                                $incident_sid,
                                $employeeEmail,
                                $upload_incident_doc,
                                "document",
                                $item_title
                            );
                    }
                    //
                    $return_data = array();
                    $return_data['item_sid'] = $insert_document_sid;
                    $return_data['item_title'] = $item_title;
                    $return_data['item_type'] = 'Document';
                    $return_data['item_source'] = strtoupper($file_extension);

                    echo json_encode($return_data);
                }
            } else {
                echo 'error';
            }
        } else {
            $video_id = '';
            $fileType = 'link';

            if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0 && $item_source == 'upload_video') {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["file"]["name"]);
                $file_name = strtolower($companyId . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/incident_videos/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $companyId;

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

                $video_id = $file_name;
                $fileType = 'video';
            } else if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0 && $item_source == 'upload_audio') {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["file"]["name"]);
                $file_name = strtolower($companyId . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/incident_videos/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $companyId;

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

                $video_id = $file_name;
                $fileType = 'audio';
            } else {
                $video_id = $_POST['social_url'];
                $item_source = 'link';
            }
            //
            $insert_video_sid = 0;
            //
            if ($employeeType == 'internal') {
                $insert_document_sid = $this
                    ->compliance_report_model
                    ->addFilesLinkToReport(
                        $report_sid,
                        $incident_sid,
                        $employeeId,
                        $video_id,
                        $fileType,
                        $item_title
                    );
            } else if ($employeeType == 'external') {
                $insert_document_sid = $this
                    ->compliance_report_model
                    ->addExternalEmployeeFilesLinkToReport(
                        $report_sid,
                        $incident_sid,
                        $employeeEmail,
                        $video_id,
                        $fileType,
                        $item_title
                    );
            }

            $return_data = array();
            $return_data['item_sid'] = $insert_video_sid;
            $return_data['item_title'] = $item_title;
            $return_data['item_type'] = 'Media';
            $return_data['item_source'] = $item_source;

            echo json_encode($return_data);
        }
    }

    public function validateVimeoVideoLink()
    {
        $str = $this->input->post('url');
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        echo TRUE;
                    } else {
                        $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                        $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                        echo FALSE;
                    }
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    echo FALSE;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    echo TRUE;
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    echo FALSE;
                }
            }
        } else {
            echo FALSE;
        }
    }

    function updateEmailReadFlag()
    {
        $email_sid = $_POST['email_sid'];

        $data_to_update = array();
        $data_to_update['is_read'] = 1;
        $this->compliance_report_model->updateEmailReadFlag($email_sid, $data_to_update);
        //
        if (isset($_POST['receiver_sid'])) {
            $receiver_sid = $_POST['receiver_sid'];
            $sender_info = $this->compliance_report_model->getEmailSenderInfo($email_sid);

            $reportId = $sender_info['csp_reports_sid'];
            $incidentId = $sender_info['csp_incident_type_sid'];
            $senderId = $sender_info['sender_sid'] == 0 ? $sender_info['manual_email'] : $sender_info['sender_sid'];

            $log_in_user_status = $this->compliance_report_model->isUserHaveNewEmail($receiver_sid, $reportId, $incidentId);
            $status_one = 0;
            if ($log_in_user_status > 0) {
                $status_one = 1;
            }

            $current_user_status = $this->compliance_report_model->isUserHaveUnreadMessage($receiver_sid, $senderId, $reportId, $incidentId);
            $status_two = 0;
            if ($current_user_status > 0) {
                $status_two = 1;
            }

            if (filter_var($senderId, FILTER_VALIDATE_EMAIL)) {
                $split_email = explode('@', $senderId);
                $senderId = $split_email[0];
            }

            $return_data = array();
            $return_data['status_one'] = $status_one;
            $return_data['status_two'] = $status_two;
            $return_data['senderId'] = $senderId;

            echo json_encode($return_data);
        } else {
            echo 'success';
        }
    }

    public function viewComplianceSafetyReportEmail($reportId, $incidentId, $itemId, $receiverId, $senderId)
    {
        //
        $manual_user = 'no';
        //
        if (!filter_var($receiverId, FILTER_VALIDATE_EMAIL) && !filter_var($senderId, FILTER_VALIDATE_EMAIL)) {
            // Fetch All Emails IF Both Sender-SID And Receiver_SID is Integers 
            $emails = $this->compliance_report_model->getComplianceSafetyReportEmails($receiverId, $senderId, $reportId, $incidentId, $itemId);
        } else {
            if (filter_var($receiverId, FILTER_VALIDATE_EMAIL)) {
                // Fetch All Emails IF Both Sender-SID is Integer And Receiver_SID is Email Address
                $emails = $this->compliance_report_model->getComplianceSafetyReportEmailsByEmailAddress($receiverId, $senderId, $reportId, $incidentId, $itemId);
            } else if (filter_var($senderId, FILTER_VALIDATE_EMAIL)) {
                // Fetch All Emails IF Both Sender-SID is Email Address And Receiver_SID is Integer
                $emails = $this->compliance_report_model->getComplianceSafetyReportEmailsByEmailAddress($senderId, $receiverId, $reportId, $incidentId, $itemId);
            }

            $manual_user = 'yes';
        }

        if (!empty($emails)) {
            $user_email = '';
            $user_first_name = '';
            $user_last_name = '';
            $user_picture = '';
            $user_phone = '';
            $user_type = '';
            $sender_type = '';
            $company_sid = '';
            $sender_email = '';
            $manual_user_name = '';
            $incident_users = array();
            $to_sid = $senderId;
            $from_sid = $receiverId;
            //
            if (filter_var($senderId, FILTER_VALIDATE_EMAIL)) {
                $sender_type = 'manual';
            } else {
                $sender_type = 'employee';
            }
            //
            if ($manual_user == 'yes' && filter_var($receiverId, FILTER_VALIDATE_EMAIL)) {
                $name = explode("@", $receiverId);
                $manual_user_name = $name[0];
                $user_first_name = 'N/';
                $user_last_name = 'A';
                $user_phone = 'N/A';
                $user_email = $receiverId;
                $user_type = 'manual';
                $company_sid = $this->compliance_report_model->getCompanyIDByComplianceSafetyReportID($reportId);
            } else {
                $user_info = $this->compliance_report_model->get_employee_info_by_id($receiverId);
                $company_sid = $user_info['parent_sid'];
                $user_email = $user_info['email'];
                $user_picture = $user_info['profile_picture'];
                $user_phone = $user_info['PhoneNumber'];
                $user_type = 'employee';

                $user_first_name = isset($user_info['first_name']) ? $user_info['first_name'] : '';
                $user_last_name = isset($user_info['last_name']) ? $user_info['last_name'] : '';
            }
            //
            $tokenDetails = [
                'company_sid' => $company_sid,
                'csp_reports_sid' => $reportId,
                'csp_report_incident_sid' => $incidentId,
                'employee_sid' => $manual_user == 'no' ? $receiverId : 0,
                'is_external_employee' => $manual_user == 'yes' ? 1 : 0,
                'external_name' => $manual_user_name,
                'external_email' => $manual_user == 'yes' ? $receiverId : 0,
            ];
            //
            $this->session->set_userdata('tokenDetails', $tokenDetails);
            //
            if ($manual_user == 'yes' && filter_var($senderId, FILTER_VALIDATE_EMAIL)) {
                $sender_email = $to_sid;
            } else {
                $sender_info = $this->compliance_report_model->get_employee_info_by_id($senderId);
                $sender_email = $sender_info['email'];
            }

            $company_name = $this->compliance_report_model->get_company_name_by_sid($company_sid);

            // Fetch Document For Media
            $libraryItems = $this->compliance_report_model->getComplianceReportFiles($reportId, $incidentId, $itemId);

            $this->data['emails'] = $emails;
            $this->data['title'] = 'Compliance Safety Emails';
            $this->data['company_sid'] = $company_sid;
            $this->data['company_name'] = $company_name;
            $this->data['user_first_name'] = $user_first_name;
            $this->data['user_last_name'] = $user_last_name;
            $this->data['user_email'] = $user_email;
            $this->data['user_picture'] = $user_picture;
            $this->data['user_phone'] = $user_phone;
            $this->data['user_type'] = $user_type;
            $this->data['sender_email'] = $sender_email;
            $this->data['user_sid'] = $from_sid;
            $this->data['incident_users'] = $incident_users;
            $this->data['current_user'] = $from_sid;
            $this->data['receiver_user'] = $to_sid;
            $this->data['reportId'] = $reportId;
            $this->data['incidentId'] = $incidentId;
            $this->data['itemId'] = $itemId;
            $this->data['libraryItems'] = $libraryItems;
            $this->data['senderType'] = $sender_type;
            $this->data['pageJs'][] = 'csp/send_email_view';
            //
            $this->data['template'] = message_header_footer(
                $company_sid,
                $company_name
            );
            //
            $this->renderView('compliance_safety_reporting/partials/files/view_report_emails');
        } else {
            $this->load->view('onboarding/onboarding_error');
        }
    }

    /**
     * process edit
     * 
     * @param int $reportTypeId
     */
    public function updateAttachedItem(int $reportId, int $incidentId)
    {
        $this->form_validation->set_rules('id', 'Id', 'required');

        if (!$this->form_validation->run()) {
            return sendResponse(
                400,
                ["errors" => explode("\n", validation_errors())]
            );
        }
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $this->compliance_report_model->updateAttachedItem(
            $reportId,
            $incidentId,
            $this->getPublicSessionData("employee_sid"),
            $post
        );
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            //
            $loggedInEmployeeName = getManualUserNameByEmailId(
                $reportId,
                $incidentId,
                $this->getPublicSessionData("external_email")
            );
            //
            $dataToUpdate = [
                "last_modified_by" => $loggedInEmployeeName
            ];
            //
            $this->compliance_report_model->addManualUserEmail(
                $post["id"],
                $dataToUpdate,
                'csp_reports_incidents_items'
            );
            //
            // Save log on update report
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => $incidentId,
                    'incidentItemId' => 0,
                    'type' => 'incidents',
                    'userType' => 'external',
                    'userId' => $this->getPublicSessionData("external_email"),
                    'jsonData' => [
                        'action' => 'update',
                        'type' => 'items',
                        'item_id' => $post["id"],
                        'dateTime' => getSystemDate(),
                        'fields' => [
                            'dynamicInput' => $post["dynamicInput"],
                            'dynamicCheckbox' => $post['dynamicCheckbox'],
                            'status' => $post['status'],
                            'level' => $post['level'],
                        ],
                    ]
                ]
            );
        }
        // return the success
        return sendResponse(
            200,
            ["message" => "Item successfully updated."]
        );
    }

    public function downloadCSPReport($reportId)
    {
        //
        $userId = '';
        $userType = '';
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $userId = $this->getPublicSessionData("external_email");
            $userType = 'external';
        } else {
            $userId = $this->getPublicSessionData("employee_sid");
            $userType = 'employee';
        }
        //
        $haveAccess = $this->compliance_report_model->checkEmployeeHaveReportAccess($userId, $reportId, 0);
        //
        if ($haveAccess == 'access_report') {
            // get types
            $this->data["report"] = $this
                ->compliance_report_model
                ->getCSPReportByIdForDownload(
                    $reportId,
                    [
                        "csp_reports.sid",
                        "csp_reports.title",
                        "csp_reports.report_date",
                        "csp_reports.disable_answers",
                        "csp_reports.report_type_sid",
                        "csp_reports.completion_date",
                        "csp_reports.status",
                        "csp_reports.updated_at",
                        "compliance_report_types.compliance_report_name",
                        "users.first_name",
                        "users.last_name",
                        "users.middle_name",
                        "users.access_level",
                        "users.access_level_plus",
                        "users.pay_plan_flag",
                        "users.job_title",
                        "users.is_executive_admin",
                        "users.email",
                        "users.PhoneNumber",
                        "users.parent_sid"
                    ]
                );
            //
            $companyId = $this->getPublicSessionData("company_sid");
            $employeeName = '';
            //
            if ($this->getPublicSessionData("is_external_employee") == 1) {
                $employeeName = $this->getPublicSessionData("external_name");
            } else {
                $employeeId = $this->getPublicSessionData("employee_sid");
                $employeeName = getEmployeeOnlyNameBySID($employeeId);
            }
            //
            $this->data['report_sid'] = $reportId;
            $this->data['company_name'] = $this->compliance_report_model->get_company_name_by_sid($companyId);
            $this->data['action_date'] = 'Downloaded Date';
            $this->data['action_by'] = "Downloaded By";
            $this->data['action'] = "download";
            $this->data['action_by_name'] = $employeeName;
            //
            // Save log on download report
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => 0,
                    'incidentItemId' => 0,
                    'type' => 'main',
                    'userType' => $userType,
                    'userId' => $userId,
                    'jsonData' => [
                        'action' => 'download report',
                        'dateTime' => getSystemDate()
                    ]
                ]
            );
            //
            $this->load->view('compliance_safety_reporting/download_compliance_safety_report', $this->data);
        } else {
            return redirect("dashboard");
        }
    }


    public function downloadCSPIncident($reportId, $incidentId)
    {
        //
        $userId = '';
        $userType = '';
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $userId = $this->getPublicSessionData("external_email");
            $userType = 'external';
        } else {
            $userId = $this->getPublicSessionData("employee_sid");
            $userType = 'employee';
        }
        //
        $haveAccess = $this->compliance_report_model->checkEmployeeHaveReportAccess($userId, $reportId, $incidentId);
        //
        if ($haveAccess == 'access_report' || $haveAccess == 'access_incident') {
            $this->data["incidentDetail"] = $this
                ->compliance_report_model
                ->getCSPIncidentByIdForDownload(
                    $reportId,
                    $incidentId,
                    true
                );
            //
            $companyId = $this->getPublicSessionData("company_sid");
            $employeeName = '';
            //
            if ($this->getPublicSessionData("is_external_employee") == 1) {
                $employeeName = $this->getPublicSessionData("external_name");
            } else {
                $employeeId = $this->getPublicSessionData("employee_sid");
                $employeeName = getEmployeeOnlyNameBySID($employeeId);
            }
            //
            $this->data['report_sid'] = $reportId;
            $this->data['company_name'] = $this->compliance_report_model->get_company_name_by_sid($companyId);
            $this->data['action_date'] = 'Downloaded Date';
            $this->data['action_by'] = "Downloaded By";
            $this->data['action'] = "download";
            $this->data['action_by_name'] = $employeeName;
            //
            // Save log on download incident
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => $incidentId,
                    'incidentItemId' => 0,
                    'type' => 'incidents',
                    'userType' => $userType,
                    'userId' => $userId,
                    'jsonData' => [
                        'action' => 'download incident',
                        'dateTime' => getSystemDate()
                    ]
                ]
            );
            //
            $this->load->view('compliance_safety_reporting/download_compliance_safety_report_incident', $this->data);
        } else {
            return redirect("/");
        }
    }

    public function getUploadAttachmentView()
    {
        return sendResponse(
            200,
            [
                "view" => $this->load->view(
                    'compliance_safety_reporting/partials/files/item_attachment',
                    [],
                    true
                )
            ]
        );
    }

    public function uploadAttachmentItemFile()
    {
        //
        $post = $this->input->post(null, true);
        //
        $currentUser = '';
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $currentUser = $this->getPublicSessionData("external_email");
        } else {
            $currentUser = $this->getPublicSessionData("employee_sid");
        }
        //
        if ($post["link"]) {
            $linkId = $this
                ->compliance_report_model
                ->addFilesLinkToIncidentItem(
                    $post['reportId'],
                    $post['incidentId'],
                    $post['itemId'],
                    $currentUser,
                    $post["link"],
                    $post["type"],
                    $post['title']
                );
            //
            if ($this->getPublicSessionData("is_external_employee") == 1) {
                //
                $loggedInEmployeeName = getManualUserNameByEmailId(
                    $post['reportId'],
                    $post['incidentId'],
                    $this->getPublicSessionData("external_email")
                );
                //
                $dataToUpdate = [
                    "last_modified_by" => $loggedInEmployeeName
                ];
                //
                $this->compliance_report_model->addManualUserEmail(
                    $post['itemId'],
                    $dataToUpdate,
                    'csp_reports_incidents_items'
                );
                //
                $this->compliance_report_model->addManualUserEmail(
                    $linkId,
                    [
                        'manual_email' => $this->getPublicSessionData("external_email")
                    ],
                    'csp_reports_files'
                );
                // Save log on Add file
                $this->compliance_report_model->saveComplianceSafetyReportLog(
                    [
                        'reportId' => $post['reportId'],
                        'incidentId' => $post['incidentId'],
                        'incidentItemId' => $post['itemId'],
                        'type' => 'files',
                        'userType' => 'external',
                        'userId' => $this->getPublicSessionData("external_email"),
                        'jsonData' => [
                            'action' => 'create',
                            'type' => 'link',
                            'title' => $post['title'],
                            'fileId' => $linkId,
                            'dateTime' => getSystemDate()
                        ]
                    ]
                );
            }
            // return the success
            return sendResponse(
                200,
                [
                    "message" => "File is attached successfully."
                ]
            );
        } else {
            // sanitize the name
            $fileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $_FILES['file']['name']);
            $fileName = str_replace(' ', '_', $fileName);
            $fileName = time() . '_' . $fileName;
            $fileName = strtolower($fileName);

            $this->load->library('aws_lib');
            //
            try {
                $options = [
                    'Bucket' => AWS_S3_BUCKET_NAME,
                    'Key' => $fileName,
                    'Body' => file_get_contents($_FILES["file"]["tmp_name"]),
                    'ACL' => 'public-read',
                    'ContentType' => $_FILES["file"]["type"]
                ];
                //
                $this->aws_lib->put_object($options);
                //
                $fileId = $this
                    ->compliance_report_model
                    ->addFilesToIncidentItem(
                        $post['reportId'],
                        $post['incidentId'],
                        $post['itemId'],
                        $currentUser,
                        $fileName,
                        $_FILES["file"]["name"],
                        $post["type"],
                        $post['title']
                    );
                //
                if ($this->getPublicSessionData("is_external_employee") == 1) {
                    $loggedInEmployeeName = getManualUserNameByEmailId(
                        $post['reportId'],
                        $post['incidentId'],
                        $this->getPublicSessionData("external_email")
                    );
                    //
                    $dataToUpdate = [
                        "last_modified_by" => $loggedInEmployeeName
                    ];
                    //
                    $this->compliance_report_model->addManualUserEmail(
                        $post['itemId'],
                        $dataToUpdate,
                        'csp_reports_incidents_items'
                    );
                    //
                    $this->compliance_report_model->addManualUserEmail(
                        $fileId,
                        [
                            'manual_email' => $this->getPublicSessionData("external_email")
                        ],
                        'csp_reports_files'
                    );
                    // Save log on Add file
                    $this->compliance_report_model->saveComplianceSafetyReportLog(
                        [
                            'reportId' => $post['reportId'],
                            'incidentId' => $post['incidentId'],
                            'incidentItemId' => $post['itemId'],
                            'type' => 'files',
                            'userType' => 'external',
                            'userId' => $this->getPublicSessionData("external_email"),
                            'jsonData' => [
                                'action' => 'create',
                                'type' => $post["type"],
                                'title' => $post['title'],
                                'fileId' => $fileId,
                                'dateTime' => getSystemDate()
                            ]
                        ]
                    );
                }
                // return the success
                return sendResponse(
                    200,
                    [
                        "message" => "File is attached successfully."
                    ]
                );
                //
            } catch (Exception $exception) {
                return sendResponse(
                    400,
                    ["errors" => ["Failed to upload file to AWS S3"]]
                );
            }
        }
    }

    public function manageIncidentItem($reportId, $incidentId, $itemId)
    {
        // get types
        $this->data["report"] = $this
            ->compliance_report_model
            ->getCSPIncidentItemInfo(
                $reportId,
                $incidentId,
                $itemId
            );
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $this->data['employee_type'] = 'external';
            $currentUser = $this->getPublicSessionData("external_email");
        } else {
            $this->data['employee_type'] = 'internal';
            $currentUser = $this->getPublicSessionData("employee_sid");
        }
        //
        $this->data["report"]["emails"] = $this->compliance_report_model->getComplianceEmails($reportId, $incidentId, $currentUser);
        //
        if ($this->data["report"]["notes"]) {
            foreach ($this->data["report"]["notes"] as $k0 => $v0) {
                if ($this->data['employee_type'] == 'external') {
                    if ($v0["note_type"] === "personal" && $v0["manual_email"] != $currentUser) {
                        unset($this->data["report"]["notes"][$k0]);
                    }
                } else {
                    if ($v0["note_type"] === "personal" && $v0["created_by"] != $currentUser) {
                        unset($this->data["report"]["notes"][$k0]);
                    }
                }
            }
        }
        //
        // set the title
        $this->data['title'] = 'Compliance Safety Incident Item Management';
        $this->data['pageJs'][] = 'csp/manage_incident_item_public';
        $this->data['pageJs'][] = 'csp/send_email_public';

        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getPublicSessionData("company_sid"),
                0
            );
        //
        $this->data["reportId"] = $reportId;
        $this->data["incidentId"] = $incidentId;
        $this->data["itemId"] = $itemId;
        $this->data['pageType'] = 'public';
        $this->data['segments'] = [
            "reportId" => $reportId,
            "incidentId" => $incidentId,
            "itemId" => $itemId
        ];

        //
        $this->renderView('compliance_safety_reporting/public/edit_item_public');
    }

    public function updateIssueProgress()
    {
        $reportId = $this->input->post("reportId", true);
        $incidentId = $this->input->post("incidentId", true);
        $itemId = $this->input->post("itemId", true);
        $status = $this->input->post("status", true);
        $completedAt = $this->input->post("completionDate", true);
        // update the status
        if ($this->compliance_report_model->updateIncidentItemStatus($reportId, $incidentId, $itemId, $this->getPublicSessionData("employee_sid"), $status, $completedAt)) {
            echo SendResponse(200, [
                "status" => "success",
                "message" => "Status updated successfully",
            ]);
        } else {
            echo SendResponse(400, [
                "status" => "error",
                "message" => "Failed to update status",
            ]);
        }
    }

    /**
     * process edit incident item
     * 
     * @param int $reportId
     * @param int $incidentId
     * @param int $itemId
     */
    public function processIncidentItem(int $reportId, int $incidentId, $itemId)
    {
        // get the post
        $post = $this->input->post(null, true);
        //
        $employeeId = $this->getPublicSessionData("employee_sid")
            ? $this->getPublicSessionData("employee_sid")
            : 0;
        //allowed_internal_system_count
        $fileId = $this->compliance_report_model->editIncidentItem(
            $reportId,
            $incidentId,
            $itemId,
            $employeeId,
            $post
        );
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $loggedInEmployeeName = getManualUserNameByEmailId(
                $reportId,
                $incidentId,
                $this->getPublicSessionData("external_email")
            );
            //
            $dataToUpdate = [
                "last_modified_by" => $loggedInEmployeeName
            ];
            //
            $this->compliance_report_model->addManualUserEmail(
                $itemId,
                $dataToUpdate,
                'csp_reports_incidents_items'
            );
            //
            $emailId = $this->getPublicSessionData("external_email");
            //
            $this->compliance_report_model->addExternalUser(
                $fileId,
                $emailId,
                'csp_reports_files'
            );
        }
        // return the success
        return sendResponse(
            200,
            ["message" => "Incident item updated successfully."]
        );
    }

    /**
     * process add note to incident item
     * 
     * @param int $reportId
     * @param int $incidentId
     * @param int $incidentId
     */
    public function processIncidentItemNotes(int $reportId, int $incidentId, int $itemId = 0)
    {
        $this->form_validation->set_rules('type', 'Note Type', 'required');
        $this->form_validation->set_rules('content', 'Note', 'required');

        if (!$this->form_validation->run()) {
            return sendResponse(
                400,
                ["errors" => explode("\n", validation_errors())]
            );
        }
        // get the post
        $post = $this->input->post(null, true);
        $employeeId = $this->getPublicSessionData("employee_sid")
            ? $this->getPublicSessionData("employee_sid")
            : 0;
        //allowed_internal_system_count
        $noteId = $this->compliance_report_model->addNotesToIncidentItem(
            $reportId,
            $incidentId,
            $itemId,
            $employeeId,
            $post
        );
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $emailId = $this->getPublicSessionData("external_email");
            //
            $this->compliance_report_model->addExternalUser(
                $noteId,
                $emailId,
                'csp_reports_notes'
            );
        }
        // return the success
        return sendResponse(
            200,
            ["message" => "Notes added successfully."]
        );
    }

    public function downloadCSPIncidentItem($reportId, $incidentId, $itemId)
    {
        //
        $userId = '';
        $userType = '';
        //
        if ($this->getPublicSessionData("is_external_employee") == 1) {
            $userId = $this->getPublicSessionData("external_email");
            $userType = 'external';
        } else {
            $userId = $this->getPublicSessionData("employee_sid");
            $userType = 'employee';
        }
        //
        $haveAccess = $this->compliance_report_model->checkEmployeeHaveReportAccess($userId, $reportId, $incidentId);
        //
        if ($haveAccess == 'access_report' || $haveAccess == 'access_incident') {
            $this->data["itemDetail"] = $this
                ->compliance_report_model
                ->getCSPIncidentItemByIdForDownload(
                    $reportId,
                    $incidentId,
                    $itemId,
                    true
                );
            //
            $companyId = $this->getPublicSessionData("company_sid");
            $employeeName = '';
            //
            if ($this->getPublicSessionData("is_external_employee") == 1) {
                $employeeName = $this->getPublicSessionData("external_name");
            } else {
                $employeeId = $this->getPublicSessionData("employee_sid");
                $employeeName = getEmployeeOnlyNameBySID($employeeId);
            }
            //
            $this->data['report_sid'] = $reportId;
            $this->data['company_name'] = $this->compliance_report_model->get_company_name_by_sid($companyId);
            $this->data['action_date'] = 'Downloaded Date';
            $this->data['action_by'] = "Downloaded By";
            $this->data['action'] = "download";
            $this->data['action_by_name'] = $employeeName;
            //
            // Save log on download incident item
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => $incidentId,
                    'incidentItemId' => $itemId,
                    'type' => 'incident_item',
                    'userType' => $userType,
                    'userId' => $userId,
                    'jsonData' => [
                        'action' => 'download incident item',
                        'dateTime' => getSystemDate()
                    ]
                ]
            );
            //
            $this->load->view('compliance_safety_reporting/download_compliance_safety_report_incident_item', $this->data);
        } else {
            return redirect("/");
        }
    }

}
