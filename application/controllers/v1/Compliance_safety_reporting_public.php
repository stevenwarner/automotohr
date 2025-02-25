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
        if ((int)$tokenDetails["csp_report_incident_sid"] !== 0) {
            //
            return $this->editReportIncident($tokenDetails["csp_reports_sid"], $tokenDetails["csp_report_incident_sid"]);
        } else {
            return $this->edit($tokenDetails["csp_reports_sid"]);
        }
    }

    /**
     * overview
     */
    public function overview()
    {
        $this->checkPublicSession();
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Overview';
        // get types
        $this->data["pendingReports"] = $this
            ->compliance_report_model
            ->getCSPReportPublic(
                $this->getPublicSessionData("company_sid"),
                $this->getPublicSessionData("is_external_employee") == 1
                    ? $this->getPublicSessionData("external_email")
                    : $this->getPublicSessionData("employee_sid"),
                "pending"
            );
        // get types
        $this->data["completedReports"] = $this
            ->compliance_report_model
            ->getCSPReportPublic(
                $this->getPublicSessionData("company_sid"),
                $this->getPublicSessionData("is_external_employee") == 1
                    ? $this->getPublicSessionData("external_email")
                    : $this->getPublicSessionData("employee_sid"),
                "completed"
            );
        $this->data["onHoldReports"] = $this
            ->compliance_report_model
            ->getCSPReportPublic(
                $this->getPublicSessionData("company_sid"),
                $this->getPublicSessionData("is_external_employee") == 1
                    ? $this->getPublicSessionData("external_email")
                    : $this->getPublicSessionData("employee_sid"),
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
        $this->renderView('compliance_safety_reporting/public/report_overview');
    }

    /**
     * Report incidents
     *
     * @param int $reportId
     */
    public function overviewIncidents()
    {
        $this->checkPublicSession();

        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Incidents';
        // get types
        $this->data["pendingReports"] = $this
            ->compliance_report_model
            ->getCSPAllowedIncidentsPublic(
                $this->getPublicSessionData("is_external_employee") == 1
                    ? $this->getPublicSessionData("external_email")
                    : $this->getPublicSessionData("employee_sid"),
                [
                    "compliance_incident_types.compliance_incident_type_name",
                    "csp_reports.title",
                    "csp_reports.sid as reportId",
                    "csp_reports_incidents.sid",
                    "csp_reports_incidents.completed_at",
                    "csp_reports_incidents.status",
                ],
                "pending"
            );
        // get types
        $this->data["completedReports"] = $this
            ->compliance_report_model
            ->getCSPAllowedIncidentsPublic(
                $this->getPublicSessionData("is_external_employee") == 1
                    ? $this->getPublicSessionData("external_email")
                    : $this->getPublicSessionData("employee_sid"),
                [
                    "csp_reports.title",
                    "csp_reports.sid as reportId",
                    "compliance_incident_types.compliance_incident_type_name",
                    "csp_reports_incidents.sid",
                    "csp_reports_incidents.completed_at",
                    "csp_reports_incidents.status",
                ],
                "completed"
            );
        $this->data["onHoldReports"] = $this
            ->compliance_report_model
            ->getCSPAllowedIncidentsPublic(
                $this->getPublicSessionData("is_external_employee") == 1
                    ? $this->getPublicSessionData("external_email")
                    : $this->getPublicSessionData("employee_sid"),
                [
                    "compliance_incident_types.compliance_incident_type_name",
                    "csp_reports.title",
                    "csp_reports.sid as reportId",
                    "csp_reports_incidents.sid",
                    "csp_reports_incidents.completed_at",
                    "csp_reports_incidents.status",
                ],
                "on_hold"
            );
        // load JS
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts-more.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/exporting.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/export-data.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/accessibility.js';
        $this->data['pageJs'][] = 'csp/overview_incidents';
        //
        $this->renderView('compliance_safety_reporting/public/incident_overview');
    }

    /**
     * Report incidents
     *
     * @param int $reportId
     */
    public function reportIncidents(int $reportId)
    {
        $this->checkPublicSession();
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
     * edit
     *
     * @param int $reportId
     */
    public function edit(int $reportId)
    {
        $this->checkPublicSession();

        // get types
        $this->data["report"] = $this
            ->compliance_report_model
            ->getCSPReportById(
                $reportId,
                [
                    "csp_reports.sid",
                    "csp_reports.title",
                    "csp_reports.company_sid",
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

        if ($this->data["report"]["notes"]) {
            foreach ($this->data["report"]["notes"] as $k0 => $v0) {
                if ($v0["note_type"] === "personal" && $v0["created_by"] != $this->getPublicSessionData("employee_sid")) {
                    unset($this->data["report"]["notes"][$k0]);
                }
            }
        }

        // set the title
        $this->data['segments'] = [
            "reportId" => $this->data["report"]["sid"],
            "incidentId" => 0
        ];
        $this->data['title'] = 'Compliance Safety Reporting | Edit ' . $this->data["report"]["title"];
        $this->data['pageJs'][] = 'csp/edit_report_public';
        $this->data['template'] = message_header_footer(
            $this->data["report"]["company_sid"],
            getCompanyColumnById($this->data["report"]["company_sid"], "CompanyName")["CompanyName"]
        );
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->data["report"]["company_sid"],
                0
            );
        // get the report incident types
        $this->data["incidentTypes"] = $this
            ->compliance_report_model
            ->getReportMapping(
                $this->data["report"]["report_type_sid"]
            );
        //
        $this->renderView('compliance_safety_reporting/public/edit_report');
    }

    /**
     * edit
     *
     * @param int $reportId
     */
    public function editReportIncident(int $reportId, int $incidentId)
    {
        $this->checkPublicSession();

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
        $this->data['segments'] = [
            "reportId" => $reportId,
            "incidentId" => $incidentId
        ];
        $this->data["report"]["description"] = convertCSPTags($this->data["report"]["description"]);

        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Edit ' . $this->data["report"]["title"];
        $this->data['pageJs'][] = 'csp/edit_incident_public';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getPublicSessionData("company_sid"),
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
        $this->renderView('compliance_safety_reporting/public/edit_incident');
    }

    /**
     * process edit
     * 
     * @param int $reportTypeId
     */
    public function processEdit(int $reportId)
    {
        $this->checkPublicSession();
        //
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
            $this->getPublicSessionData("employee_sid")
                ? $this->getPublicSessionData("employee_sid")
                : 0,
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
            $this->getPublicSessionData("employee_sid")
                ?  $this->getPublicSessionData("employee_sid")
                : 0,
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
            $this->getPublicSessionData("employee_sid")
                ? $this->getPublicSessionData("employee_sid")
                : 0,
            $post
        );
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
}
