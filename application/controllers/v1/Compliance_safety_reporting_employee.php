<?php defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'controllers/csp/Base_csp.php';

/**
 * Indeed controller to handle all new
 * events
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 */
class Compliance_safety_reporting_employee extends Base_csp
{
    public function __construct()
    {
        parent::__construct(true);
        //
        if (!isAllowedForCSP()) {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Hr Access Denied!');
            return redirect(
                "dashboard"
            );
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
        $this->data['pageJs'][] = main_url("public/v1/plugins/daterangepicker/daterangepicker.min.js?v=3.0");
        // load CSS
        $this->data['pageCSS'][] = main_url("public/v1/plugins/daterangepicker/css/daterangepicker.min.css?v=3.0");

        // get filter    
        $this->data["filter"] = [
            "severity_level" => $this->input->get("severityLevel", true) ?? "-1",
            "incident" => $this->input->get("incidentType", true) ?? "-1",
            "status" => $this->input->get("status", true) ?? "-1",
            "title" => $this->input->get("title") ?? "",
            "date_range" => $this->input->get("date_range", true) ?? ""
        ];
        //
        $queryString = $_SERVER['QUERY_STRING'];
        $this->data['CSVUrl'] = base_url('compliance_safety_reporting/export_csv');
        $this->data['downloadUrl'] = base_url('compliance_safety_reporting/employee/download_reports');
        //
        if ($queryString) {
            $this->data['CSVUrl'] = $this->data['CSVUrl'] . '?' . $queryString;
            $this->data['downloadUrl'] = $this->data['downloadUrl'] . '?' . $queryString;
        }
        //
        $this->compliance_report_model->manageAllowedDepartmentsAndTeamsManagers();
        //
        $this->data["severity_levels"] = $this
            ->compliance_report_model
            ->getSeverityLevels();
        // get all the incidents
        $this->data["incidents"] = $this
            ->compliance_report_model
            ->getAllEmployeeIncidentsWithReports(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid")
            );

        // get the reports
        $this->data["reports"] = $this
            ->compliance_report_model
            ->getAllEmployeeItemsWithIncidentsCPA(
                $this->getLoggedInCompany("sid"),
                $this->getLoggedInEmployee("sid"),
                $this->data["filter"]
            );
        //
        $this->renderView('compliance_safety_reporting/employee/dashboard');
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
        $this->data['pageJs'][] = 'csp/manage_incident_item_employee';
        $this->data['pageJs'][] = 'csp/send_email';
        // get the employees
        $this->data["employees"] = $this
            ->compliance_report_model
            ->getActiveEmployees(
                $this->getLoggedInCompany("sid"),
                0
            );
        //
        $this->data["reportId"] = $reportId;
        $this->data["incidentId"] = $incidentId;
        $this->data["itemId"] = $itemId;
        $this->data['pageType'] = 'not_public';
        //
        $this->renderView('compliance_safety_reporting/employee/edit_item');
    }

    /**
     * overview
     */
    public function overview(string $mode = "reports")
    {
        return redirect("compliance_safety_reporting/dashboard");
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Overview';
        //
        $this->data["mode"] = $mode;
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
        $this->renderView('compliance_safety_reporting/employee/reports/overview');
    }

    /**
     * Report incidents
     *
     * @param int $reportId
     */
    public function overviewIncidents()
    {
        return redirect("compliance_safety_reporting/dashboard");
        // set the title
        $this->data['title'] = 'Compliance Safety Reporting | Incidents';
        // get types
        $this->data["pendingReports"] = $this
            ->compliance_report_model
            ->getCSPAllowedIncidents($this->getLoggedInEmployee("sid"), [
                "compliance_incident_types.compliance_incident_type_name",
                "csp_reports.title",
                "csp_reports.sid as reportId",
                "csp_reports_incidents.sid",
                "csp_reports_incidents.completed_at",
                "csp_reports_incidents.status",
            ], "pending");
        // get types
        $this->data["completedReports"] = $this
            ->compliance_report_model
            ->getCSPAllowedIncidents($this->getLoggedInEmployee("sid"), [
                "csp_reports.title",
                "csp_reports.sid as reportId",
                "compliance_incident_types.compliance_incident_type_name",
                "csp_reports_incidents.sid",
                "csp_reports_incidents.completed_at",
                "csp_reports_incidents.status",
            ], "completed");
        $this->data["onHoldReports"] = $this
            ->compliance_report_model
            ->getCSPAllowedIncidents($this->getLoggedInEmployee("sid"), [
                "compliance_incident_types.compliance_incident_type_name",
                "csp_reports.title",
                "csp_reports.sid as reportId",
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
        $this->renderView('compliance_safety_reporting/employee/incidents/overview');
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

    public function downloadReports()
    {
        //
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
            ->getAllSelectedReportIds(
                $this->getLoggedInCompany("sid"),
                $filter
            );
        //
        $this->data['reports'] = $reports;
        $this->data['company_name'] = $this->getLoggedInCompany("CompanyName");
        $this->data['action_date'] = 'Downloaded Date';
        $this->data['action_by'] = "Downloaded By";
        $this->data['action'] = "download";
        $this->data['generatedDate'] = date('Y_m_d-H:i:s');
        $this->data['action_by_name'] = $this->getLoggedInEmployee("first_name") . ' ' . $this->getLoggedInEmployee("last_name");
        //
        // Save log on download report
        $this->compliance_report_model->saveComplianceSafetyReportLog(
            [
                'reportId' => 0,
                'incidentId' => 0,
                'incidentItemId' => 0,
                'type' => 'main',
                'userType' => 'employee',
                'userId' => $this->getLoggedInEmployee("sid"),
                'jsonData' => [
                    'action' => 'download report',
                    'dateTime' => getSystemDate()
                ]
            ]
        );
        //
        $basePath = ROOTPATH . 'assets/compliance_safety_reports/' . strtolower(preg_replace('/\s+/', '_', $this->data['company_name']))."/compliance_reports";
        // //
        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }
        //
        foreach ($reports as $report) {
            //
            $reportPath = $basePath . '/' . strtolower(preg_replace('/\s+/', '_', $report['title']));
            //
            if (!is_dir($reportPath)) {
                mkdir($reportPath, 0777, true);
            }
            //
            if ($report['fileToDownload'])
            {
                foreach ($report['fileToDownload'] as $file) {
                    downloadFileFromAWS($reportPath .'/'. $file['file_name'], $file['link']);
                }
            }
            //
            if ($report['incidents'])
            {
                foreach ($report['incidents'] as $incident) {
                    foreach ($incident['issues'] as $issue) {
                        //
                        $issuePath = $reportPath .'/'. strtolower(preg_replace('/\s+/', '_', $issue['issue_title'])).'/';
                        //
                        if (!is_dir($issuePath)) {
                            mkdir($issuePath, 0777, true);
                        }
                        //
                        if ($issue['fileToDownload'])
                        { 
                            foreach ($issue['fileToDownload'] as $file) {
                                downloadFileFromAWS($issuePath . $file['file_name'], $file['link']);
                            }
                        }
                    }
                }    
            }
        }
        // //
        $this->load->view('compliance_safety_reporting/download_compliance_safety_reports', $this->data);
    }

    public function downloadCSPIncidentItem(int $reportId, int $incidentId, int $itemId, string $action)
    {

        $employeeId = $this->getLoggedInEmployee("sid");
        $haveAccess = $this->compliance_report_model->checkEmployeeHaveReportAccess($employeeId, $reportId, $incidentId, $itemId);

        //
        if ($haveAccess == 'access_report' || $haveAccess == 'access_issue') {
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
            $this->data['action'] = $action;
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
                        'action' => $action == 'download' ? 'download incident issue' : 'print incident issue',
                        'dateTime' => getSystemDate()
                    ]
                ]
            );

            $this->data["severityStatus"] = $this->compliance_report_model->getSeverityLevels();
            //
            if ($action == 'download') {
                //
                $basePath = ROOTPATH . 'assets/compliance_safety_reports/' . strtolower(preg_replace('/\s+/', '_', $this->data['company_name'])) . '/' . strtolower(preg_replace('/\s+/', '_', $this->data['itemDetail']['report_title']));
                //
                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }
                //
                if ($this->data['itemDetail']['fileToDownload'])
                {
                    //
                    $issuePath = $basePath .'/'. strtolower(preg_replace('/\s+/', '_', $this->data['itemDetail']['issue_title'])).'/';
                    //
                    if (!is_dir($issuePath)) {
                        mkdir($issuePath, 0777, true);
                    }
                    //
                    foreach ($this->data['itemDetail']['fileToDownload'] as $file) {
                        downloadFileFromAWS($issuePath . $file['file_name'], $file['link']);
                    }
                }
            }    
            //
            $this->load->view('compliance_safety_reporting/download_compliance_safety_report_incident_item', $this->data);
        } else {
            return redirect("dashboard");
        }
    }
}
