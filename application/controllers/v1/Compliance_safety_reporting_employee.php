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

    /**
     * overview
     */
    public function overview(string $mode = "reports")
    {
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
}
