<?php defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'controllers/csp/Base_csp.php';

/**
 * Indeed controller to handle all new
 * events
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 */
class Compliance_safety_reporting extends Base_csp
{
    public function __construct()
    {
        parent::__construct(true);
    }

    /**
     * overview
     */
    public function overview()
    {
        if (!isMainAllowedForCSP()) {
            return redirect("dashboard");
        }
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Overview';
        // get types
        $this->data["pendingReports"] = $this
            ->compliance_report_model
            ->getCSPReport(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid"),
                "pending"
            );
        // get types
        $this->data["completedReports"] = $this
            ->compliance_report_model
            ->getCSPReport(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid"),
                "completed"
            );
        $this->data["onHoldReports"] = $this
            ->compliance_report_model
            ->getCSPReport(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid"),
                "on_hold"
            );
        // load JS
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts-more.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/exporting.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/export-data.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/accessibility.js';
        $this->data['pageJs'][] = 'csp/overview';
        //
        $this->renderView('compliance_safety_reporting/overview');
    }

    /**
     * Report incidents
     *
     * @param int $reportId
     */
    public function reportIncidents(int $reportId)
    {
        // get types
        $this->data["report"] = $this
            ->compliance_report_model
            ->getCSPReportById(
                $reportId,
                [
                    "csp_reports.sid",
                    "csp_reports.title",
                    "csp_reports.report_date",
                    "csp_reports.completion_date",
                    "csp_reports.status",
                ]
            );
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Incidents of report ' . $this->data["report"]["title"];
        // get types
        $this->data["pendingReports"] = $this
            ->compliance_report_model
            ->getCSPReportIncidents($reportId, [
                "compliance_incident_types.compliance_incident_type_name",
                "csp_reports_incidents.sid",
                "csp_reports_incidents.completed_at",
                "csp_reports_incidents.status",
            ], "pending");
        // get types
        $this->data["completedReports"] = $this
            ->compliance_report_model
            ->getCSPReportIncidents($reportId, [
                "compliance_incident_types.compliance_incident_type_name",
                "csp_reports_incidents.sid",
                "csp_reports_incidents.completed_at",
                "csp_reports_incidents.status",
            ], "completed");
        $this->data["onHoldReports"] = $this
            ->compliance_report_model
            ->getCSPReportIncidents($reportId, [
                "compliance_incident_types.compliance_incident_type_name",
                "csp_reports_incidents.sid",
                "csp_reports_incidents.completed_at",
                "csp_reports_incidents.status",
            ], "on_hold");
        // load JS
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts-more.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/exporting.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/export-data.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/accessibility.js';
        $this->data['pageJs'][] = 'csp/overview_incidents';
        //
        $this->renderView('compliance_safety_reporting/reports/incidents');
    }

    /**
     * listing
     */
    public function listing()
    {
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting';
        // get types
        $this->data["types"] = $this
            ->compliance_report_model
            ->getAllReportTypes(
                $this->getLoggedInCompany("sid")
            );
        //
        $this->renderView('compliance_safety_reporting/listings');
    }

    /**
     * add
     *
     * @param int $reportId
     */
    public function add(int $reportTypeId)
    {
        if (!isMainAllowedForCSP()) {
            return redirect("dashboard");
        }
        // get types
        $this->data["type"] = $this
            ->compliance_report_model
            ->getReportTypeById(
                $reportTypeId
            );
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Add ' . $this->data["type"]["compliance_report_name"];
        $this->data['pageCSS'][] = 'v1/plugins/ms_uploader/main.min';
        $this->data['pageJs'][] = 'v1/plugins/ms_uploader/main.min';
        $this->data['pageJs'][] = 'csp/add_report.min';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid")
            );
        //
        $this->renderView('compliance_safety_reporting/add_report');
    }

    public function processAdd(int $reportTypeId)
    {
        $this->form_validation->set_rules('report_title', 'Report Title', 'required');
        $this->form_validation->set_rules('report_date', 'Report Date', 'required');

        if (!$this->form_validation->run()) {
            return sendResponse(
                400,
                ["errors" => explode("\n", validation_errors())]
            );
        }
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $reportId = $this->compliance_report_model->addReport(
            $reportTypeId,
            $this->getLoggedInCompany("sid"),
            $this->getLoggedInEmployee("sid"),
            $post
        );

        if (!$reportId) {
            return sendResponse(
                400,
                ["errors" => ["Failed to add the report"]]
            );
        }
        // return the success
        return sendResponse(
            200,
            ["id" => $reportId, "message" => "Report added successfully."]
        );
    }

    /**
     * edit
     *
     * @param int $reportId
     */
    public function edit(int $reportId)
    {
        // get types
        $this->data["report"] = $this
            ->compliance_report_model
            ->getCSPReportById(
                $reportId,
                [
                    "csp_reports.sid",
                    "csp_reports.title",
                    "csp_reports.report_date",
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
                ]
            );
        //
        if (!$this->data["report"]) {
            return redirect("/");
        }
        if ($this->data["report"]["notes"]) {
            foreach ($this->data["report"]["notes"] as $k0 => $v0) {
                if ($v0["note_type"] === "personal" && $v0["created_by"] != $this->getLoggedInEmployee("sid")) {
                    unset($this->data["report"]["notes"][$k0]);
                }
            }
        }
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Edit ' . $this->data["report"]["title"];
        $this->data['pageJs'][] = 'csp/edit_report';
        $this->data['pageJs'][] = 'csp/send_email';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                0
            );
        // get the report incident types
        $this->data["incidentTypes"] = $this
            ->compliance_report_model
            ->getReportMapping(
                $this->data["report"]["report_type_sid"]
            );
        //
        $this->data["reportId"] = $reportId; 
        $this->data["incidentId"] = 0;
        //
        $this->renderView('compliance_safety_reporting/edit_report');
    }

    /**
     * edit
     *
     * @param int $reportId
     */
    public function editReportIncident(int $reportId, int $incidentId)
    {
        // get types
        $this->data["report"] = $this
            ->compliance_report_model
            ->getCSPIncident(
                $reportId,
                $incidentId
            );

        if ($this->data["report"]["notes"]) {
            foreach ($this->data["report"]["notes"] as $k0 => $v0) {
                if ($v0["note_type"] === "personal" && $v0["created_by"] != $this->getLoggedInEmployee("sid")) {
                    unset($this->data["report"]["notes"][$k0]);
                }
            }
        }
        //
        $this->data["report"]["description"] = convertCSPTags($this->data["report"]["description"]);

        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Edit ' . $this->data["report"]["title"];
        $this->data['pageJs'][] = 'csp/edit_incident';
        $this->data['pageJs'][] = 'csp/send_email';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                0
            );
        if ($this->data["report"]["disable_answers"] != 1) {
            // get incident questions and content
            $this->data["questions"] = $this
                ->compliance_report_model
                ->fetchQuestions(
                    $this->data["report"]["incident_type_sid"]
                );
        } else {
            $this->data["questions"] = [];
        }
        //
        $this->data["reportId"] = $reportId; 
        $this->data["incidentId"] = $incidentId;
        //
        $this->renderView('compliance_safety_reporting/edit_incident');
    }

    /**
     * process edit
     * 
     * @param int $reportTypeId
     */
    public function processEdit(int $reportId)
    {
        $this->form_validation->set_rules('report_title', 'Report Title', 'required');
        $this->form_validation->set_rules('report_date', 'Report Date', 'required');

        if (!$this->form_validation->run()) {
            return sendResponse(
                400,
                ["errors" => explode("\n", validation_errors())]
            );
        }
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $this->compliance_report_model->editReport(
            $reportId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Report updated successfully."]
        );
    }

    /**
     * process edit
     * 
     * @param int $reportTypeId
     */
    public function processIncidentEdit(int $reportId, int $incidentId)
    {
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $this->compliance_report_model->editIncidentReport(
            $reportId,
            $incidentId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Incident updated successfully."]
        );
    }

    /**
     * delete external employee
     *
     * @param int $reportId
     * @param int $recordId
     */
    public function deleteExternalEmployee(int $reportId, int $recordId)
    {
        $this->compliance_report_model->deleteExternalEmployee(
            $reportId,
            $recordId
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "External employee removed successfully."]
        );
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
        $this->compliance_report_model->addNotesToReport(
            $reportId,
            $incidentId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Notes added successfully."]
        );
    }


    /**
     * process edit
     * 
     * @param int $reportId
     * @param int $incidentId
     */
    public function addIncidentToReport(int $reportId)
    {
        // get the post
        $post = $this->input->post("incidentId", true);
        //allowed_internal_system_count
        $this->compliance_report_model->attachIncidentToReport(
            $reportId,
            $post["incidentId"],
            $this->getLoggedInEmployee("sid")
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Incident added to report successfully."]
        );
    }

    public function processFiles(int $reportId, int $incidentId, string $type)
    {
        //
        $post = $this->input->post(null, true);

        if ($post["link"]) {
            $id = $this
                ->compliance_report_model
                ->addFilesLinkToReport(
                    $reportId,
                    $incidentId,
                    $this->getLoggedInEmployee("sid"),
                    $post["link"],
                    $post["type"],
                    $this->input->post("title")
                );
            // return the success
            return sendResponse(
                200,
                [
                    "message" => "File is attached successfully.",
                    "view" => $this->load->view(
                        'compliance_safety_reporting/partials/file',
                        [
                            "document" => [
                                "sid" => $id,
                                "title" => $this->input->post("title"),
                                "created_at" => getSystemDate(),
                                "first_name" => $this->getLoggedInEmployee("first_name"),
                                "last_name" => $this->getLoggedInEmployee("last_name"),
                                "middle_name" => $this->getLoggedInEmployee("middle_name"),
                                "job_title" => $this->getLoggedInEmployee("job_title"),
                                "access_level" => $this->getLoggedInEmployee("access_level"),
                                "access_level_plus" => $this->getLoggedInEmployee("access_level_plus"),
                                "is_executive_admin" => $this->getLoggedInEmployee("is_executive_admin"),
                                "pay_plan_flag" => $this->getLoggedInEmployee("pay_plan_flag"),
                            ]
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
                        $this->getLoggedInEmployee("sid"),
                        $fileName,
                        $_FILES["file"]["name"],
                        $type,
                        $this->input->post("title")
                    );
                // return the success
                return sendResponse(
                    200,
                    [
                        "message" => "File is attached successfully.",
                        "view" => $this->load->view(
                            'compliance_safety_reporting/partials/file',
                            [
                                "document" => [
                                    "sid" => $id,
                                    "title" => $this->input->post("title"),
                                    "created_at" => getSystemDate(),
                                    "first_name" => $this->getLoggedInEmployee("first_name"),
                                    "last_name" => $this->getLoggedInEmployee("last_name"),
                                    "middle_name" => $this->getLoggedInEmployee("middle_name"),
                                    "job_title" => $this->getLoggedInEmployee("job_title"),
                                    "access_level" => $this->getLoggedInEmployee("access_level"),
                                    "access_level_plus" => $this->getLoggedInEmployee("access_level_plus"),
                                    "is_executive_admin" => $this->getLoggedInEmployee("is_executive_admin"),
                                    "pay_plan_flag" => $this->getLoggedInEmployee("pay_plan_flag"),
                                ]
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
        ]);
        //
        if (!$file) {
            return SendResponse(400, ["errors" => "Failed to verify the file."]);
        }
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

    public function sendComplianceReportEmail () {
        //
        $send_email_type = $_POST['send_type'];
        $attachments = isset($_POST['attach_files']) ? explode(',', $_POST['attach_files']) : [];
        $reportId = $_POST['report_id'];
        $incidentId = $_POST['incident_id'];
        $companyId = $this->getLoggedInCompany("sid");
        $companyName = $this->getLoggedInCompany("CompanyName");
        $employeeId = $this->getLoggedInEmployee("sid");
        $employeeName = $this->getLoggedInEmployee("first_name") . ' ' . $this->getLoggedInEmployee("last_name");
        //
        if ($send_email_type == 'manual') {
            $manual_email   = $_POST['manual_email'];
            $subject        = $_POST['subject'];
            $message        = $_POST['message'];

            $email_hf = message_header_footer_domain($companyId, $companyName);

            $manual_email_to_insert = array();
            $manual_email_to_insert['csp_reports_sid']          = $reportId;
            $manual_email_to_insert['csp_incident_type_sid']    = $incidentId;
            $manual_email_to_insert['manual_email']             = $manual_email;
            $manual_email_to_insert['sender_sid']               = $employeeId;
            $manual_email_to_insert['receiver_sid']             = 0;
            $manual_email_to_insert['subject']                  = $subject;
            $manual_email_to_insert['message_body']             = $message;

            $inserted_email_sid = $this->compliance_report_model->addComplianceReportEmail($manual_email_to_insert);
            $isEmployee = $this->compliance_report_model->checkManualUserIsAnEmployee($_POST['manual_email'], $companyId);

            if (!empty($attachments)) {
                //
                foreach ($attachments as $attachmentId) {

                    $insert_attachment                      = array();
                    $insert_attachment['csp_reports_email_sid'] = $inserted_email_sid;
                    $insert_attachment['csp_reports_file_sid']  = $attachmentId;
                    $insert_attachment['attached_by']           = $employeeId;
                    $insert_attachment['attached_date']         = date('Y-m-d H:i:s');

                    $this->compliance_report_model->addComplianceEmailAttachment($insert_attachment);
                }
            }
            //
            //
            $receiver_name = '';
            $conversation_key = '';
            //
            if ($isEmployee) {
                $manualUserInfo = $this->compliance_report_model->getUserInfoByEmail($_POST['manual_email'], $companyId);
                $conversation_key = $reportId . '/' . $manualUserInfo['sid'] . '/' . $employeeId;
                $receiver_name = $manualUserInfo['first_name'].' '.$manualUserInfo['last_name'];
            } else {
                $conversation_key = $reportId . '/' . $manual_email . '/' . $employeeId;
                $name = explode("@", $manual_email);
                $receiver_name = $name[0];
            }
            //
            $url = base_url('compliance_safety_reporting/view_compliance_report_email/' . $conversation_key);
            $from_name = $employeeName;
            //
            $emailTemplateBody = 'Dear ' . $receiver_name . ', <br>';
            $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email about compliance report.</p>' . '<br>';
            $emailTemplateBody = $emailTemplateBody . '<p>Please click on the following link to reply.</p>' . '<br>';
            if ($isEmployee) {
                $employeeType = $this->compliance_report_model->isComplianceReportManager($_POST['manual_email'], $companyId, $reportId);
                //
                if ($employeeType != "out_sider") {
                    if ($employeeType == "reporter") {
                        $viewIncident = base_url('compliance_safety_reporting/view_compliance_report/' . $reportId);
                    } else if ($employeeType == "incident_manager") {
                        $viewIncident = base_url('compliance_safety_reporting/view_compliance_report/' . $reportId);
                    }
                    //
                    $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Compliance Report</a>' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                }
            } else {
                //
                //
                // Add outsider user into compliance report outsider user table
                $this->compliance_report_model->checkManualUserExist($_POST['manual_email'], $reportId);
                //
                $this->load->library('encryption');
                //
                $this->encryption->initialize(
                    get_encryption_initialize_array()
                );
                //
                $viewComplianceCode = str_replace(
                    ['/', '+'],
                    ['$$ab$$', '$$ba$$'],
                    $this->encryption->encrypt($conversation_key)
                );
                //
                $approval_public_link_accept = base_url("compliance_safety_reporting/view_compliance_report_public_link") . '/' . $viewComplianceCode;
                //
                $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $approval_public_link_accept . '">View Compliance Report</a>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
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
            
        } else if ('system') {
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
                $data_to_insert['csp_reports_sid']          = $reportId;
                $data_to_insert['csp_incident_type_sid']    = $incidentId;
                $data_to_insert['sender_sid'] = $employeeId;
                $data_to_insert['subject'] = $subject;
                $data_to_insert['message_body'] = $message_body;
                //
                $manager_info = db_get_employee_profile($receiver_id);
                $receiver_email = $manager_info[0]['email'];
                $receiver_name = $manager_info[0]['first_name'] . ' ' . $manager_info[0]['last_name'];
                $conversation_key = $reportId . '/' . $receiver_id . '/' . $employeeId;
                $data_to_insert['receiver_sid'] = $receiver_id;
                //
                $inserted_email_sid = $this->compliance_report_model->addComplianceReportEmail($data_to_insert);

                if (!empty($attachments)) {
                    //
                    foreach ($attachments as $attachmentId) {
    
                        $insert_attachment                      = array();
                        $insert_attachment['csp_reports_email_sid'] = $inserted_email_sid;
                        $insert_attachment['csp_reports_file_sid']  = $attachmentId;
                        $insert_attachment['attached_by']           = $employeeId;
                        $insert_attachment['attached_date']         = date('Y-m-d H:i:s');
    
                        $this->compliance_report_model->addComplianceEmailAttachment($insert_attachment);
                    }
                }

                $url = base_url('compliance_safety_reporting/view_compliance_report_email/' . $conversation_key);
                $employeeType = $this->compliance_report_model->isComplianceReportManager($receiver_email, $companyId, $id);
                //
                $emailTemplateBody = 'Dear ' . $receiver_name . ', <br>';
                $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email about compliance report.</p>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<p>Please click on the following link to reply.</p>' . '<br>';
                if ($employeeType != "out_sider") {
                    if ($employeeType == "reporter") {
                        $viewIncident = base_url('compliance_safety_reporting/view_compliance_report/' . $reportId);
                    } else if ($employeeType == "incident_manager") {
                        $viewIncident = base_url('compliance_safety_reporting/view_compliance_report/' . $reportId);
                    }
                    //
                    $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Compliance Report</a>' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
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
        }
        //
        return sendResponse(
            200,
            ["message" => "Email send successfully."]
        );
    }

    public function save_email_manual_attachment()
    {
        $session = $this->session->userdata('logged_in');
        $employee_sid   = $session["employer_detail"]["sid"];
        $companyId    = $session['company_detail']['sid'];

        $item_title     = $_POST['attachment_title'];
        $companyId    = $_POST['companyId'];
        $report_sid     = $_POST['report_sid'];
        $incident_sid   = $_POST['incident_sid'];
        $item_source    = $_POST['file_type'];
        $uploaded_by    = $_POST['uploaded_by'];
        $user_type      = $_POST['user_type'];

        if ($item_source == 'upload_document') {
            if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0) {

                $file_name          = $_POST['file_name'];
                $file_extension     = $_POST['file_ext'];
                $upload_incident_doc = upload_file_to_aws('file', $companyId, 'file', '', AWS_S3_BUCKET_NAME);

                if (!empty($upload_incident_doc) && $upload_incident_doc != 'error') {
                    //
                    $insert_document_sid = $this
                        ->compliance_report_model
                        ->addFilesLinkToReport(
                            $report_sid,
                            $incident_sid,
                            $this->getLoggedInEmployee("sid"),
                            $upload_incident_doc,
                            "document",
                            $item_title
                        );

                    $return_data                    = array();
                    $return_data['item_sid']        = $insert_document_sid;
                    $return_data['item_title']      = $item_title;
                    $return_data['item_type']       = 'Document';
                    $return_data['item_source']     = strtoupper($file_extension);

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
            $insert_video_sid = $this
                ->compliance_report_model
                ->addFilesLinkToReport(
                    $report_sid,
                    $incident_sid,
                    $this->getLoggedInEmployee("sid"),
                    $video_id,
                    $fileType,
                    $item_title
                );

            $return_data                    = array();
            $return_data['item_sid']        = $insert_video_sid;
            $return_data['item_title']      = $item_title;
            $return_data['item_type']       = 'Media';
            $return_data['item_source']     = $item_source;

            echo json_encode($return_data);
        }
    }

    public function validate_vimeo_video()
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

    public function vimeo_get_id($str)
    {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return $response['video_id'];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
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
                    return $response['video_id'];
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    function update_email_read_flag()
    {
        $email_sid              = $_POST['email_sid'];

        $data_to_update             = array();
        $data_to_update['is_read']  = 1;
        $this->compliance_report_model->update_email_is_read_flag($email_sid, $data_to_update);



        if (isset($_POST['receiver_sid'])) {
            $receiver_sid           = $_POST['receiver_sid'];
            $sender_info            = $this->compliance_report_model->get_email_sender_info($email_sid);

            $incident_sid           = $sender_info['incident_reporting_id'];
            $sender_sid             = $sender_info['sender_sid'] == 0 ? $sender_info['manual_email'] : $sender_info['sender_sid'];

            $log_in_user_status     = is_manager_have_new_email($receiver_sid, $incident_sid);
            $status_one = 0;
            if ($log_in_user_status > 0) {
                $status_one = 1;
            }

            $current_user_status    = is_user_have_unread_message($receiver_sid, $sender_sid, $incident_sid);
            $status_two = 0;
            if ($current_user_status > 0) {
                $status_two = 1;
            }

            if (filter_var($sender_sid, FILTER_VALIDATE_EMAIL)) {
                $split_email = explode('@', $sender_sid);
                $sender_sid = $split_email[0];
            }

            $return_data                = array();
            $return_data['status_one']  = $status_one;
            $return_data['status_two']  = $status_two;
            $return_data['sender_sid']  = $sender_sid;

            echo json_encode($return_data);
        } else {
            echo 'success';
        }
    }
}
