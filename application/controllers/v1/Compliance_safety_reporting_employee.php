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
        // get filter
        $this->data["filter"] = [
            "severity_level" => $this->input->get("severityLevel", true) ?? "-1",
            "incident" => $this->input->get("incidentType", true) ?? "-1",
            "status" => $this->input->get("status", true) ?? "-1",
        ];
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
}
