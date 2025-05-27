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
    public function dashboard()
    {
        if (!isMainAllowedForCSP()) {
            return redirect("dashboard");
        }
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Dashboard';
        // load JS
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts-more.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/exporting.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/export-data.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/accessibility.js';
        $this->data['pageJs'][] = 'csp/dashboard';
        $this->data['pageJs'][] = main_url("public/v1/plugins/daterangepicker/daterangepicker.min.js?v=3.0");
        // load CSS
        $this->data['pageCSS'][] = main_url("public/v1/plugins/daterangepicker/css/daterangepicker.min.css?v=3.0");
        // get filter    
        $this->data["filter"] = [
            "severity_level" => $this->input->get("severityLevel", true) ?? "-1",
            "incident" => $this->input->get("incidentType", true) ?? "-1",
            "status" => $this->input->get("status", true) ?? "-1",
            "title" => $this->input->get("title") ?? "",
            "date_range" => $this->input->get("date_range", true) ?? "",
            "departments" => $this->input->get("departments", true) ?? "",
            "teams" => $this->input->get("teams", true) ?? ""
        ];
        //
        $queryString = $_SERVER['QUERY_STRING'];
        $this->data['CSVUrl'] = base_url('compliance_safety_reporting/export_csv');
        //
        if ($queryString) {
            $this->data['CSVUrl'] = $this->data['CSVUrl'] . '?' . $queryString;
        }
        //
        // get all the incidents
        $this->data["incidents"] = $this
            ->compliance_report_model
            ->getAllIncidentsWithReports(
                $this->getLoggedInCompany("sid")
            );
        $this->data["severity_levels"] = $this
            ->compliance_report_model
            ->getSeverityLevels();

        // get the reports
        $this->data["reports"] = $this
            ->compliance_report_model
            ->getAllItemsWithIncidentsCPA(
                $this->getLoggedInCompany("sid"),
                $this->data["filter"]
            );
        //
        // get the severity status
        $this->data["severity_status"] = $this
            ->compliance_report_model
            ->getSeverityLevels();
        //
        $this->data['departments'] = $this->compliance_report_model->getDepartments($this->getLoggedInCompany("sid"));
        $this->data['teams'] = $this->compliance_report_model->getTeams($this->getLoggedInCompany("sid"), $this->data['departments']);
        //
        $this->renderView('compliance_safety_reporting/dashboard');
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
        // get the report questions
        $this->data["questions"] =
            $this
                ->compliance_report_model
                ->getReportQuestionsById(
                    $reportTypeId
                );
        //
        $this->data["report_types"] = $this
            ->compliance_report_model
            ->getAllReportTypes();
        // get the employees
        // $this->data["employees"] = $this
        //     ->compliance_report_model
        //     ->getActiveEmployees(
        //         $this->getLoggedInCompany("sid"),
        //         $this->getLoggedInEmployee("sid")
        //     );
        // get the employees
        $this->data["employees"] = [];
        $this->data['departments'] = $this->compliance_report_model->getDepartments($this->getLoggedInCompany("sid"));
        //
        $this->renderView('compliance_safety_reporting/add_report');
    }

    public function processAdd(int $reportTypeId)
    {
        $this->form_validation->set_rules('report_title', 'Report Title', 'required');
        $this->form_validation->set_rules('report_type', 'Report Type', 'required');
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
            ->getCSPReportByIdNew(
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
                    "csp_reports.last_modified_by",
                    "csp_reports.answers_json",
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
        // //
        if ($this->data["report"]['issuesWithIncident']) {
            foreach ($this->data["report"]['issuesWithIncident'] as $key => $issue) {
                $filesCount = $this
                    ->compliance_report_model
                    ->getAllItemsFilesCount(
                        $issue['sid']
                    );
                //
                $this->data["report"]['issuesWithIncident'][$key]['file_count'] = $filesCount;
            }
        }
        //
        $this->data["report"]["emails"] = $this->compliance_report_model->getComplianceEmails($reportId, 0, $this->getLoggedInEmployee("sid"));
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
        $this->data['pageJs'][] = main_url("public/v1/plugins/daterangepicker/daterangepicker.min.js?v=3.0");
        // load CSS
        $this->data['pageCSS'][] = main_url("public/v1/plugins/daterangepicker/css/daterangepicker.min.css?v=3.0");
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                0
            );
        $this->data["employees"] = [];
        // get the report incident types
        // $this->data["incidentTypes"] = $this
        //     ->compliance_report_model
        //     ->getAllIncidents();
        //
        $this->data["severity_levels"] = $this
            ->compliance_report_model
            ->getSeverityLevels();
        //
        $this->data['departments'] = $this->compliance_report_model->getDepartments($this->getLoggedInCompany("sid"));
        //
        $this->data["reportId"] = $reportId;
        $this->data["incidentId"] = 0;
        $this->data["itemId"] = 0;
        // load JS
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/highcharts-more.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/exporting.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/export-data.js';
        $this->data['pageJs'][] = 'https://code.highcharts.com/modules/accessibility.js';
        $this->data['pageJs'][] = 'csp/dashboard';
        //
        $this->renderView('compliance_safety_reporting/edit_report_new');
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
        $this->data["report"]["emails"] = $this->compliance_report_model->getComplianceEmails($reportId, $incidentId, $this->getLoggedInEmployee("sid"));
        //
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
        // get the severity status
        $this->data["severity_status"] = $this
            ->compliance_report_model
            ->getSeverityLevels();
        //
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
        $this->data["itemId"] = 0;
        $this->data["manageItemUrl"] = base_url('compliance_safety_reporting/incident_item_management/' . $reportId . '/' . $incidentId);
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
    public function attachItemToIncident(int $reportId, int $incidentId)
    {
        $this->form_validation->set_rules('id', 'Id', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('level', 'Level', 'required');

        if (!$this->form_validation->run()) {
            return sendResponse(
                400,
                ["errors" => explode("\n", validation_errors())]
            );
        }
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $this->compliance_report_model->attachItemToIncident(
            $reportId,
            $incidentId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Item successfully updated."]
        );
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
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Item successfully updated."]
        );
    }

    /**
     * process edit
     * 
     * @param int $reportTypeId
     */
    public function markAllItemsOfIncidentsInactive(int $reportId, int $incidentId)
    {
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $this->compliance_report_model->markAllItemsOfIncidentsInactive(
            $reportId,
            $incidentId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Item removed."]
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

    public function deleteFileById($fileId)
    {
        $this->compliance_report_model->deleteAttachedFile(
            $fileId
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "File removed successfully."]
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
            $post,
            $this->getLoggedInEmployee("sid")
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Incident added to report successfully."]
        );
    }

    /**
     * process edit
     * 
     * @param int $reportId
     * @param int $incidentId
     */
    public function detachReportIncidentById(int $incidentId)
    {
        // get the post
        //allowed_internal_system_count
        $this->compliance_report_model->detachReportIncidentById(
            $incidentId
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Incident removed from the report successfully."]
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
                    0,
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
                        0,
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
        // Save log on view file
        $this->compliance_report_model->saveComplianceSafetyReportLog(
            [
                'reportId' => $file['csp_reports_sid'],
                'incidentId' => $file['csp_incident_type_sid'],
                'incidentItemId' => $file['csp_reports_incidents_items_sid'],
                'type' => $logType,
                'userType' => 'employee',
                'userId' => $this->getLoggedInEmployee("sid"),
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
        // Save log on view file
        $this->compliance_report_model->saveComplianceSafetyReportLog(
            [
                'reportId' => $file['csp_reports_sid'],
                'incidentId' => $file['csp_incident_type_sid'],
                'incidentItemId' => $file['csp_reports_incidents_items_sid'],
                'type' => $logType,
                'userType' => 'employee',
                'userId' => $this->getLoggedInEmployee("sid"),
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

    public function sendComplianceReportEmail()
    {
        //
        $send_email_type = $_POST['send_type'];
        $attachments = isset($_POST['attach_files']) ? explode(',', $_POST['attach_files']) : [];
        $reportId = $_POST['report_id'];
        $incidentId = $_POST['incident_id'];
        $itemId = $_POST['item_id'];
        $companyId = $this->getLoggedInCompany("sid");
        $companyName = $this->getLoggedInCompany("CompanyName");
        $employeeId = $this->getLoggedInEmployee("sid");
        $employeeName = $this->getLoggedInEmployee("first_name") . ' ' . $this->getLoggedInEmployee("last_name");
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

            $inserted_email_sid = $this->compliance_report_model->addComplianceReportEmail($manual_email_to_insert);

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
            // if ($employeeType != "out_sider") {
            //     //
            //     // $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Compliance Report</a>' . '<br>';
            //     // $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
            // }
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
                    'userType' => 'employee',
                    'userId' => $employeeId,
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
                $data_to_insert['sender_sid'] = $employeeId;
                $data_to_insert['subject'] = $subject;
                $data_to_insert['message_body'] = $message_body;
                //
                $manager_info = db_get_employee_profile($receiver_id);
                $receiver_email = $manager_info[0]['email'];
                $receiver_name = $manager_info[0]['first_name'] . ' ' . $manager_info[0]['last_name'];
                $conversation_key = $reportId . '/' . $incidentId . '/' . $itemId . '/' . $receiver_id . '/' . $employeeId;
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
                // if ($employeeType != "out_sider") {
                //
                // $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Compliance Report</a>' . '<br>';
                // $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                // }
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
                    'userType' => 'employee',
                    'userId' => $employeeId,
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
        $session = $this->session->userdata('logged_in');
        $employee_sid = $session["employer_detail"]["sid"];
        $companyId = $session['company_detail']['sid'];

        $item_title = $_POST['attachment_title'];
        $companyId = $_POST['companyId'];
        $report_sid = $_POST['report_sid'];
        $incident_sid = $_POST['incident_sid'];
        $item_sid = $_POST['item_sid'];
        $item_source = $_POST['file_type'];
        $uploaded_by = $_POST['uploaded_by'];
        $user_type = $_POST['user_type'];

        if ($item_source == 'upload_document') {
            if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0) {

                $file_name = $_POST['file_name'];
                $file_extension = $_POST['file_ext'];
                $upload_incident_doc = upload_file_to_aws('file', $companyId, 'file', '', AWS_S3_BUCKET_NAME);

                if (!empty($upload_incident_doc) && $upload_incident_doc != 'error') {
                    //
                    $insert_document_sid = $this
                        ->compliance_report_model
                        ->addFilesLinkToReport(
                            $report_sid,
                            $incident_sid,
                            $item_sid,
                            $this->getLoggedInEmployee("sid"),
                            $upload_incident_doc,
                            "document",
                            $item_title
                        );

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
            $insert_video_sid = $this
                ->compliance_report_model
                ->addFilesLinkToReport(
                    $report_sid,
                    $incident_sid,
                    $item_sid,
                    $this->getLoggedInEmployee("sid"),
                    $video_id,
                    $fileType,
                    $item_title
                );

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
        $this->compliance_report_model->update_email_is_read_flag($email_sid, $data_to_update);



        if (isset($_POST['receiver_sid'])) {
            $receiver_sid = $_POST['receiver_sid'];
            $sender_info = $this->compliance_report_model->get_email_sender_info($email_sid);

            $incident_sid = $sender_info['incident_reporting_id'];
            $sender_sid = $sender_info['sender_sid'] == 0 ? $sender_info['manual_email'] : $sender_info['sender_sid'];

            $log_in_user_status = is_manager_have_new_email($receiver_sid, $incident_sid);
            $status_one = 0;
            if ($log_in_user_status > 0) {
                $status_one = 1;
            }

            $current_user_status = is_user_have_unread_message($receiver_sid, $sender_sid, $incident_sid);
            $status_two = 0;
            if ($current_user_status > 0) {
                $status_two = 1;
            }

            if (filter_var($sender_sid, FILTER_VALIDATE_EMAIL)) {
                $split_email = explode('@', $sender_sid);
                $sender_sid = $split_email[0];
            }

            $return_data = array();
            $return_data['status_one'] = $status_one;
            $return_data['status_two'] = $status_two;
            $return_data['sender_sid'] = $sender_sid;

            echo json_encode($return_data);
        } else {
            echo 'success';
        }
    }

    /**
     * process edit
     * 
     * @param int $reportId
     * @param int $incidentId
     */
    public function sendEmailsToReportManagers(int $reportId)
    {
        //allowed_internal_system_count
        $this->compliance_report_model
            ->manageAllowedDepartmentsAndTeamsManagers();
        //
        $this
            ->compliance_report_model
            ->sendEmailsForCSPReport(
                $reportId
            );
        // return the success
        return sendResponse(
            200,
            ["message" => "You have successfully send the emails."]
        );
    }

    /**
     * process edit
     * 
     * @param int $reportId
     * @param int $incidentId
     */
    public function sendEmailsForCSPIncident(int $incidentId)
    {
        //allowed_internal_system_count
        $this
            ->compliance_report_model
            ->sendEmailsForCSPIncident(
                $incidentId
            );
        // return the success
        return sendResponse(
            200,
            ["message" => "You have successfully send the emails."]
        );
    }

    public function downloadCSPReport($reportId)
    {
        //
        $employeeId = $this->getLoggedInEmployee("sid");
        $haveAccess = $this->compliance_report_model->checkEmployeeHaveReportAccess($employeeId, $reportId, 0);
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
            $this->data['report_sid'] = $reportId;
            $this->data['company_name'] = $this->getLoggedInCompany("CompanyName");
            $this->data['action_date'] = 'Downloaded Date';
            $this->data['action_by'] = "Downloaded By";
            $this->data['action'] = "download";
            $this->data['action_by_name'] = $this->getLoggedInEmployee("first_name") . ' ' . $this->getLoggedInEmployee("last_name");
            //
            // Save log on download report
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => 0,
                    'incidentItemId' => 0,
                    'type' => 'main',
                    'userType' => 'employee',
                    'userId' => $employeeId,
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
        $employeeId = $this->getLoggedInEmployee("sid");
        $haveAccess = $this->compliance_report_model->checkEmployeeHaveReportAccess($employeeId, $reportId, $incidentId);
        //
        if ($haveAccess == 'access_report' || $haveAccess == 'access_incident') {
            //
            $this->data["incidentDetail"] = $this
                ->compliance_report_model
                ->getCSPIncidentByIdForDownload(
                    $reportId,
                    $incidentId,
                    true
                );
            //
            $this->data['report_sid'] = $reportId;
            $this->data['company_name'] = $this->getLoggedInCompany("CompanyName");
            $this->data['action_date'] = 'Downloaded Date';
            $this->data['action_by'] = "Downloaded By";
            $this->data['action'] = "download";
            $this->data['action_by_name'] = $this->getLoggedInEmployee("first_name") . ' ' . $this->getLoggedInEmployee("last_name");
            //
            // Save log on download incident
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => $incidentId,
                    'incidentItemId' => 0,
                    'type' => 'incidents',
                    'userType' => 'employee',
                    'userId' => $employeeId,
                    'jsonData' => [
                        'action' => 'download incident',
                        'dateTime' => getSystemDate()
                    ]
                ]
            );
            //
            $this->load->view('compliance_safety_reporting/download_compliance_safety_report_incident', $this->data);
        } else {
            return redirect("dashboard");
        }
    }

    public function saveComplianceSafetyReportPDF()
    {
        $companyName = $this->getLoggedInCompany("CompanyName");
        //
        $form_post = $this->input->post();
        $base64 = $form_post['report_base64'];
        $reportId = $form_post['report_sid'];
        $files = $form_post['file_links'];
        //
        $reportName = $this->compliance_report_model->getReportTitleById($reportId);
        //
        $basePath = ROOTPATH . 'assets/compliance_safety_reports/' . strtolower(preg_replace('/\s+/', '_', $companyName)) . '/' . strtolower(preg_replace('/\s+/', '_', $reportName)) . '/';
        //
        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }
        //
        $handler = fopen($basePath . strtolower(preg_replace('/\s+/', '_', $reportName)) . '.pdf', 'w');
        fwrite($handler, base64_decode(str_replace('data:application/pdf;base64,', '', $base64)));
        fclose($handler);
        //
        if ($files) {
            foreach ($files as $file) {
                // @file_put_contents($basePath . $file['file_name'], @file_get_contents($file['link']));
                downloadFileFromAWS($basePath . $file['file_name'], $file['link']);
            }
        }
        //
        return sendResponse(
            200,
            ["id" => $reportId, "message" => "Report downloaded successfully."]
        );
    }

    public function createAndDownloadZip($reportId)
    {
        //
        $companyName = $this->getLoggedInCompany("CompanyName");
        $reportName = $this->compliance_report_model->getReportTitleById($reportId);
        $basePath = ROOTPATH . 'assets/compliance_safety_reports/' . strtolower(preg_replace('/\s+/', '_', $companyName)) . '/' . strtolower(preg_replace('/\s+/', '_', $reportName)) . '/';
        $zip_name = 'compliance_safety_report.zip';
        //
        ini_set('memory_limit', '-1');
        $this->load->library('zip');
        $this->zip->read_dir(rtrim($basePath, '/'), FALSE);
        $this->zip->archive($basePath);
        deleteFolderWithFiles(ROOTPATH . 'assets/compliance_safety_reports/' . strtolower(preg_replace('/\s+/', '_', $companyName)));
        $this->zip->download($zip_name);
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

        if ($post["link"]) {
            $id = $this
                ->compliance_report_model
                ->addFilesLinkToIncidentItem(
                    $post['reportId'],
                    $post['incidentId'],
                    $post['itemId'],
                    $this->getLoggedInEmployee("sid"),
                    $post["link"],
                    $post["type"],
                    $post['title']
                );
            //
            $file = [
                "title" => $post["title"],
                "file_type" => $post["type"],
                "s3_file_value" => $post["link"],
                "created_at" => getSystemDate(),
                "manual_email" => false,
                "sid" => $id,
                "first_name" => $this->getLoggedInEmployee("first_name"),
                "last_name" => $this->getLoggedInEmployee("last_name"),
                "access_level" => $this->getLoggedInEmployee("access_level"),
                "access_level_plus" => $this->getLoggedInEmployee("access_level_plus"),
                "is_pay_plan" => $this->getLoggedInEmployee("is_pay_plan"),
                "job_title" => $this->getLoggedInEmployee("job_title"),
                "is_executive_admin" => $this->getLoggedInEmployee("is_executive_admin"),
            ];
            // return the success
            return sendResponse(
                200,
                [
                    "message" => "File is attached successfully.",
                    "view" => $this->load->view("compliance_safety_reporting/single_file", [
                        "document" => $file,
                    ], true),
                    "url" => $post["link"]
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
                    ->addFilesToIncidentItem(
                        $post['reportId'],
                        $post['incidentId'],
                        $post['itemId'],
                        $this->getLoggedInEmployee("sid"),
                        $fileName,
                        $_FILES["file"]["name"],
                        $post["type"],
                        $post['title']
                    );
                $file = [
                    "title" => $post["title"],
                    "file_type" => $post["type"],
                    "s3_file_value" => $fileName,
                    "created_at" => getSystemDate(),
                    "manual_email" => false,
                    "sid" => $id,
                    "first_name" => $this->getLoggedInEmployee("first_name"),
                    "last_name" => $this->getLoggedInEmployee("last_name"),
                    "access_level" => $this->getLoggedInEmployee("access_level"),
                    "access_level_plus" => $this->getLoggedInEmployee("access_level_plus"),
                    "is_pay_plan" => $this->getLoggedInEmployee("is_pay_plan"),
                    "job_title" => $this->getLoggedInEmployee("job_title"),
                    "is_executive_admin" => $this->getLoggedInEmployee("is_executive_admin"),
                ];
                // return the success
                return sendResponse(
                    200,
                    [
                        "message" => "File is attached successfully.",
                        "view" => $this->load->view("compliance_safety_reporting/single_file", [
                            "document" => $file,
                        ], true),
                        "url" => AWS_S3_BUCKET_URL . "/" . $fileName
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
        // check if has access
        if (!isMainAllowedForCSP() && !$this->compliance_report_model->isAllowedToAccessIssue($this->getLoggedInEmployee("sid"), $itemId)) {
            return redirect("/dashboard");
        }
        // get types 
        $this->data["report"] = $this
            ->compliance_report_model
            ->getCSPIncidentItemInfo(
                $reportId,
                $incidentId,
                $itemId
            );

        $this->data["report_id"] = $reportId;

        //
        $this->data["report"]["emails"] = $this->compliance_report_model->getComplianceEmails($reportId, $incidentId, $this->getLoggedInEmployee("sid"));
        //
        if ($this->data["report"]["notes"]) {
            foreach ($this->data["report"]["notes"] as $k0 => $v0) {
                if ($v0["note_type"] === "personal" && $v0["created_by"] != $this->getLoggedInEmployee("sid")) {
                    unset($this->data["report"]["notes"][$k0]);
                }
            }
        }
        //
        // set the title
        $this->data['title'] = 'Compliance Safety Incident Item Management';
        $this->data['pageJs'][] = 'csp/manage_incident_item';
        $this->data['pageJs'][] = 'csp/send_email';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                0
            );
        //
        $departments = [];
        $teams = [];
        //
        $departments = $this->compliance_report_model->getActiveDepartments($this->getLoggedInCompany("sid"));
        if ($departments) {
            $teams = $this->compliance_report_model->getTeams($this->getLoggedInCompany("sid"), $departments);
        }
        //
        $this->data['departments'] = $departments;
        $this->data['teams'] = $teams;
        $allDTEmployeeIds = [];
        if ($this->data["report"]["allowed_departments"]) {
            // get department CSP employees
            $allDTEmployeeIds = $this
                ->compliance_report_model
                ->getDepartmentsCSPManagers(
                    $this->data["report"]["allowed_departments"]
                );
        }
        if ($this->data["report"]["allowed_teams"]) {
            // get department CSP employees
            $allDTEmployeeIds = array_merge($allDTEmployeeIds, $this
                ->compliance_report_model
                ->getTeamsCSPManagers(
                    $this->data["report"]["allowed_teams"]
                ));
        }
        //
        $this->data["allDTEmployeeIds"] = array_unique($allDTEmployeeIds);
        $this->data["reportId"] = $reportId;
        $this->data["incidentId"] = $incidentId;
        $this->data["itemId"] = $itemId;
        $this->data['pageType'] = 'not_public';
        //
        $this->renderView('compliance_safety_reporting/edit_incident_item');
    }

    /**
     * process departments and teams
     * 
     * @param int $reportId
     * @param int $incidentId
     * @param int $itemId
     */
    public function addDepartmentsAndTeams(int $reportId, int $incidentId, int $itemId)
    {
        // get the post
        $post = $this->input->post(null, true);
        //
        $this->compliance_report_model->addDepartmentsAndTeams(
            $reportId,
            $incidentId,
            $itemId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Data added successfully."]
        );
    }

    /**
     * process edit
     * 
     * @param int $reportId
     * @param int $incidentId
     * @param int $itemId
     */
    public function processIncidentItem(int $reportId, int $incidentId, int $itemId)
    {
        // get the post
        $post = $this->input->post(null, true);
        //allowed_internal_system_count
        $this->compliance_report_model->editIncidentItem(
            $reportId,
            $incidentId,
            $itemId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
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
        //allowed_internal_system_count
        $this->compliance_report_model->addNotesToIncidentItem(
            $reportId,
            $incidentId,
            $itemId,
            $this->getLoggedInEmployee("sid"),
            $post
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Notes added successfully."]
        );
    }

    public function downloadCSPIncidentItem(int $reportId, int $incidentId, int $itemId)
    {

        $employeeId = $this->getLoggedInEmployee("sid");
        $haveAccess = $this->compliance_report_model->checkEmployeeHaveReportAccess($employeeId, $reportId, $incidentId);

        //
        if ($haveAccess == 'access_report' || $haveAccess == 'access_incident') {
            //
            $this->data["itemDetail"] = $this
                ->compliance_report_model
                ->getCSPIncidentItemByIdForDownload(
                    $reportId,
                    $incidentId,
                    $itemId,
                    true
                );
            //
            $this->data['report_sid'] = $reportId;
            $this->data['company_name'] = $this->getLoggedInCompany("CompanyName");
            $this->data['action_date'] = 'Downloaded Date';
            $this->data['action_by'] = "Downloaded By";
            $this->data['action'] = "download";
            $this->data['action_by_name'] = $this->getLoggedInEmployee("first_name") . ' ' . $this->getLoggedInEmployee("last_name");
            //
            // Save log on download incident item
            $this->compliance_report_model->saveComplianceSafetyReportLog(
                [
                    'reportId' => $reportId,
                    'incidentId' => $incidentId,
                    'incidentItemId' => $itemId,
                    'type' => 'incident_item',
                    'userType' => 'employee',
                    'userId' => $employeeId,
                    'jsonData' => [
                        'action' => 'download incident item',
                        'dateTime' => getSystemDate()
                    ]
                ]
            );
            //
            $this->load->view('compliance_safety_reporting/download_compliance_safety_report_incident_item', $this->data);
        } else {
            return redirect("dashboard");
        }
    }

    public function updateIssueProgress()
    {
        $reportId = $this->input->post("reportId", true);
        $incidentId = $this->input->post("incidentId", true);
        $itemId = $this->input->post("itemId", true);
        $status = $this->input->post("status", true);
        $completedAt = $this->input->post("completionDate", true);
        // update the status
        if ($this->compliance_report_model->updateIncidentItemStatus($reportId, $incidentId, $itemId, $this->getLoggedInEmployee("sid"), $status, $completedAt)) {
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
     * process edit
     * 
     * @param int $reportId
     * @param int $incidentId
     */
    public function sendEmailsForCSPIssues()
    {
        //allowed_internal_system_count
        $issuesIds = $_POST['issuesIds'];
        //
        $this->compliance_report_model
            ->manageAllowedDepartmentsAndTeamsManagers();
        //
        foreach ($issuesIds as $issueId) {
            $this
                ->compliance_report_model
                ->sendEmailsForCSPIssue(
                    $issueId
                );
        }
        // return the success
        return sendResponse(
            200,
            ["message" => "You have successfully send the emails."]
        );
    }

    public function getAllIssues()
    {
        // get the issues
        $issues = $this
            ->compliance_report_model
            ->getAllIssues();
        //
        // also get the severity level
        $severityLevels = $this->compliance_report_model->getSeverityLevels();
        // _e($severityLevels,true,true);
        // return the success
        return SendResponse(
            200,
            [
                "message" => "Issues fetched successfully.",
                "view" => $this->load->view(
                    'compliance_safety_reporting/reporting/issues_modal',
                    [
                        "records" => $issues,
                        "severity_status" => $severityLevels
                    ],
                    true
                )
            ]
        );
    }

    public function getAllIssuesByReportId(int $reportTypeId)
    {
        // get the issues
        $issues = $this
            ->compliance_report_model
            ->getAllIssuesByReportId($reportTypeId);
        //
        // also get the severity level
        $severityLevels = $this->compliance_report_model->getSeverityLevels();
        // _e($severityLevels,true,true);
        // return the success
        return SendResponse(
            200,
            [
                "message" => "Issues fetched successfully.",
                "view" => $this->load->view(
                    'compliance_safety_reporting/reporting/issues_modal',
                    [
                        "records" => $issues,
                        "severity_status" => $severityLevels
                    ],
                    true
                )
            ]
        );
    }

    public function getSingleIssueView($issueId)
    {
        // get the issues
        $issues = $this
            ->compliance_report_model
            ->getIssue($issueId);

        // also get the severity level
        $severityLevels = $this->compliance_report_model->getSeverityLevels();
        // return the success
        return SendResponse(
            200,
            [
                "message" => "Issues fetched successfully.",
                "issues" => $issues,
                "view" => $this->load->view(
                    'compliance_safety_reporting/reporting/issue_modal_single',
                    [
                        "records" => $issues,
                        "severity_status" => $severityLevels
                    ],
                    true
                )
            ]
        );
    }

    public function getEditIssueViewByRecordId($issueId)
    {
        // get the issues
        $issues = $this
            ->compliance_report_model
            ->getIssueByRecordId($issueId);
        //
        $issueType = $this->compliance_report_model
            ->getIssueTypeByRecordId($issues['compliance_report_incident_sid']);
        // also get the severity level
        $severityLevels = $this->compliance_report_model->getSeverityLevels();
        // return the success
        return SendResponse(
            200,
            [
                "message" => "Issues fetched successfully.",
                "issues" => $issues,
                "view" => $this->load->view(
                    'compliance_safety_reporting/reporting/issue_modal_edit',
                    [
                        "records" => $issues,
                        "severity_status" => $severityLevels,
                        "issue_type" => $issueType
                    ],
                    true
                )
            ]
        );
    }

    public function addIssueToReport(int $reportTypeId)
    {
        // get the post
        $post = $this->input->post(null, true);
        $issueId = 0;
        //
        if ($post['type'] == 'default') {
            // Check if the incident is already added
            $cspIncidentId = $this->compliance_report_model->checkIfIncidentExists(
                $post['reportId'],
                $post['incidentId'],
                $this->getLoggedInEmployee("sid")
            );
            //
            $issueId = $this
                ->compliance_report_model
                ->attachIssueWithReport(
                    $post["reportId"],
                    $cspIncidentId,
                    $post["issueId"],
                    $post["severityLevelId"],
                    $post["dynamicCheckbox"] ?? [],
                    $post["dynamicInputs"] ?? [],
                    $this->getLoggedInEmployee("sid"),
                );
        } else if ($post['type'] == 'manual') {
            $cspIncidentId = $this->compliance_report_model->checkIfManualIncidentExists(
                $post['reportId'],
                $this->getLoggedInEmployee("sid")
            );
            //
            $incidentTypesId = $this->compliance_report_model->addComplianceReportType(
                $post["title"],
                $post["description"],
                $post["severityLevelId"]
            );
            //
            $issueId = $this
                ->compliance_report_model
                ->attachManualIssueWithReport(
                    $post['reportId'],
                    $cspIncidentId,
                    $incidentTypesId,
                    $post["severityLevelId"],
                    $this->getLoggedInEmployee("sid"),
                );
        }
        //
        $this->compliance_report_model->processIssueQuestion(
            $issueId,
            $this->getLoggedInEmployee("sid"),
            [
                'report_to_dashboard' => $post["report_to_dashboard"],
                'ongoing_issue' => $post["ongoing_issue"],
                'reported_by' => $post["reported_by"],
                'category_of_issue' => $post["category_of_issue"]
            ]
        );
        //
        return SendResponse(
            200,
            [
                "success" => true,
                "message" => "You have successfully reported a new issue.",
                "issueId" => $issueId,
                "reportId" => $post["reportId"],
                "incidentId" => $cspIncidentId,
                "reloadURL" => base_url("compliance_safety_reporting/edit/") . $post["reportId"] . "?tab=issues"
            ]
        );
    }

    public function editIssueToReport()
    {
        // get the post
        $post = $this->input->post(null, true);
        //
        if ($post['type'] == "default") {
            $this
                ->compliance_report_model
                ->editIssueWithReport(
                    $post["issueId"],
                    $post["severityLevelId"],
                    $post["dynamicCheckbox"] ?? [],
                    $post["dynamicInputs"] ?? [],
                    $this->getLoggedInEmployee("sid"),
                );
        } else if ($post['type'] == "manual") {
            //
            $this->compliance_report_model->editComplianceReportType(
                $post["issueTypeId"],
                $post["title"],
                $post["description"],
                $post["severityLevelId"]
            );
            //
            $this->compliance_report_model
                ->updateManualIssueSeverityLevel(
                    $post["issueId"],
                    $post["severityLevelId"],
                    $this->getLoggedInEmployee("sid"),
                );
        }

        //
        $this->compliance_report_model->processIssueQuestion(
            $post["issueId"],
            $this->getLoggedInEmployee("sid"),
            [
                'report_to_dashboard' => $post["report_to_dashboard"],
                'ongoing_issue' => $post["ongoing_issue"],
                'reported_by' => $post["reported_by"],
                'category_of_issue' => $post["category_of_issue"]
            ]
        );
        //
        return SendResponse(
            200,
            [
                "success" => true,
                "message" => "You have successfully updated a reported issue."
            ]
        );
    }

    public function updateReportBasicInformation()
    {
        // get the post
        $post = $this->input->post(null, true);

        $this
            ->compliance_report_model
            ->updateReportBasicInformation(
                $post["report_id"],
                $post["title"],
                $post["report_date"],
                $post["report_completion_date"],
                $post["report_status"],
                $this->getLoggedInEmployee("sid"),
            );
        //
        return SendResponse(
            200,
            [
                "success" => true,
                "message" => "You have successfully updated report."
            ]
        );
    }

    public function deleteIssueById($issueId)
    {
        $this->compliance_report_model->deleteIssueFromReport(
            $issueId
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Issue removed successfully."]
        );
    }

    public function exportCSVReport()
    {
        //
        if (!isMainAllowedForCSP()) {
            return redirect("dashboard");
        }
        // get filter    
        $filter = [
            "severity_level" => $this->input->get("severityLevel", true) ?? "-1",
            "incident" => $this->input->get("incidentType", true) ?? "-1",
            "status" => $this->input->get("status", true) ?? "-1",
            "title" => $this->input->get("title") ?? "",
            "date_range" => $this->input->get("date_range", true) ?? ""
        ];
        // get the reports
        $reports = $this
            ->compliance_report_model
            ->getAllItemsWithIncidentsCPA(
                $this->getLoggedInCompany("sid"),
                $filter
            );
        //
        $companyName = $this->getLoggedInCompany("CompanyName");
        $employeeName = $this->getLoggedInEmployee("first_name") . ' ' . $this->getLoggedInEmployee("last_name");
        $fileName = 'compliance_safety_report/Company_Name:' . str_replace(" ", "_", $companyName) . "/Generated_By:" . $employeeName . "/Generated_Date:" . date('Y_m_d-H:i:s') . '.csv';
        //
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $output = fopen('php://output', 'w');

        fputcsv($output, array(
            "CompanyName",
            $companyName,
        ));

        fputcsv($output, array(
            "Exported By",
            $employeeName
        ));

        fputcsv($output, array(
            "Export Date",
            date('m/d/Y H:i:s ', strtotime('now')) . STORE_DEFAULT_TIMEZONE_ABBR
        ));

        fputcsv(
            $output,
            array(
                'Report Title',
                'Report Date',
                'Incident Name',
                'Issue',
                'Issue Level',
                'Completion Status',
                'Completed Date',
                'Completed By'
            )
        );
        //
        if ($reports) {
            foreach ($reports as $v0) {
                foreach ($v0["issues"] as $report) {

                    $a = [];
                    $a[] = $report['title'];
                    $a[] = $report['report_date'];
                    $a[] = $report['compliance_incident_type_name'];
                    $a[] = $report['answers_json'] ? strip_tags(convertCSPTags($report['description'], json_decode(
                        $report["answers_json"],
                        true,
                    ), true)) : "";
                    $a[] = $report['level'];
                    $a[] = $report['completion_status'];
                    $a[] = $report['completion_date'];
                    $a[] = checkAndShowUser($report["completed_by"], $report);
                    //
                    fputcsv($output, $a);
                }
            }
        }

        fclose($output);
        exit;
    }

    public function getIssueAttachedFilesViewById(int $reportId, int $incidentId, int $issueId)
    {
        // get the issues
        $files = $this
            ->compliance_report_model
            ->getAllItemsFiles(
                $reportId,
                $incidentId,
                $issueId
            );
        // return the success
        return SendResponse(
            200,
            [
                "message" => "Files fetched successfully.",
                "view" => $this->load->view(
                    'compliance_safety_reporting/partials/files/issue_files',
                    [
                        "files" => $files
                    ],
                    true
                )
            ]
        );
    }

    public function markIssueCompleted(int $issueId)
    {
        // get the issues
        $files = $this
            ->compliance_report_model
            ->markIssueDone(
                $issueId,
                $this->getLoggedInEmployee("sid")
            );
        //
        $html = '
            <label class="btn btn-success" style="border-radius: 5px;">
                Completed
            </label>
                ' . (remakeEmployeeName($this->loggedInEmployee)) . '
                <br />
                ' . (getSystemDate(DATE)) . '
        ';
        // return the success
        return SendResponse(
            200,
            [
                "message" => "Issue marked done.",
                "view" => $html,
            ]
        );
    }

    public function updateItemEmployees(int $reportId, int $incidentId, int $issueId)
    {
        $this->compliance_report_model->updateItemEmployees(
            $reportId,
            $incidentId,
            $issueId,
            $this->input->post("ids", true),
            $this->getLoggedInEmployee("sid")
        );

        return SendResponse(
            200,
            [
                "message" => "You have successfully updated the internal employees."
            ]
        );
    }


    public function updateItemExternalEmployee(int $reportId, int $incidentId, int $issueId)
    {
        $id = $this->compliance_report_model->updateItemExternalEmployee(
            $reportId,
            $incidentId,
            $issueId,
            $this->input->post("external", true),
            $this->getLoggedInEmployee("sid")
        );

        return SendResponse(
            200,
            [
                "message" => "You have successfully updated the internal employees.",
                "id" => $id,
            ]
        );
    }

    public function processIssueQuestion(int $issueId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $this->compliance_report_model->processIssueQuestion(
            $issueId,
            $this->getLoggedInEmployee("sid"),
            $post
        );

        return SendResponse(
            200,
            [
                "message" => "You have successfully updated the issue questions."
            ]
        );
    }

    public function processReportQuestion(int $reportId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $this->compliance_report_model->processReportQuestion(
            $reportId,
            $this->getLoggedInEmployee("sid"),
            $post
        );

        return SendResponse(
            200,
            [
                "message" => "You have successfully updated the issue questions.",
                "reloadURL" => base_url("compliance_safety_reporting/edit/") . $reportId . "?tab=questions"
            ]
        );
    }

    public function deleteIssueDepartmentsAndTeamsById($issueId)
    {
        $this->compliance_report_model->deleteAttachedDepartmentsAndTeams(
            $issueId,
            $this->getLoggedInEmployee("sid")
        );
        // return the success
        return sendResponse(
            200,
            ["message" => "Departments and teams removed successfully."]
        );
    }

}
