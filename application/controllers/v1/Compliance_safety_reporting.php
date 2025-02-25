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
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Edit ' . $this->data["report"]["title"];
        $this->data['pageJs'][] = 'csp/edit_report';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid")
            );
        // get the report incident types
        $this->data["incidentTypes"] = $this
            ->compliance_report_model
            ->getReportMapping(
                $this->data["report"]["report_type_sid"]
            );
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
        //
        $this->data["report"]["description"] = convertCSPTags($this->data["report"]["description"]);

        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Edit ' . $this->data["report"]["title"];
        $this->data['pageJs'][] = 'csp/edit_incident';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid")
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
}
