<?php defined('BASEPATH') || exit('No direct script access allowed');

class Compliance_safety_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllReportTypes()
    {
        return $this
            ->db
            ->select([
                "id",
                "compliance_report_name",
                "updated_at",
                "status",
            ])
            ->order_by("status", "DESC")
            ->order_by("id", "DESC")
            ->get("compliance_report_types")
            ->result_array();
    }

    public function getReportTypeById(int $reportTypeId)
    {
        $record =  $this
            ->db
            ->select([
                "compliance_report_name",
                "instructions",
                "reasons",
                "updated_at",
                "status",
            ])
            ->where("id", $reportTypeId)
            ->get("compliance_report_types")
            ->row_array();
        if (!$record) {
            return [];
        }
        //
        $record["incident_type_ids"] = $this->getReportMapping($reportTypeId);

        return $record;
    }

    public function getReportMapping(int $reportTypeId)
    {
        $records =  $this
            ->db
            ->select([
                "incident_sid",
            ])
            ->where("report_sid", $reportTypeId)
            ->get("compliance_report_incident_types_mapping")
            ->result_array();

        return $records
            ? array_column($records, "incident_sid")
            : [];
    }

    public function getAllIncidentTypes()
    {
        return $this
            ->db
            ->select([
                "id",
                "compliance_incident_type_name",
                "updated_at",
                "code",
                "status",
            ])
            ->order_by("priority", "ASC")
            ->get("compliance_incident_types")
            ->result_array();
    }

    public function getActiveIncidentTypes()
    {
        return $this
            ->db
            ->select([
                "id",
                "compliance_incident_type_name",
                "code",
            ])
            ->where("status", 1)
            ->order_by("priority", "ASC")
            ->get("compliance_incident_types")
            ->result_array();
    }

    public function getIncidentById(int $id)
    {
        return $this
            ->db
            ->select([
                "compliance_incident_type_name",
                "description",
                "code",
                "priority",
                "updated_at",
                "status",
            ])
            ->where("id", $id)
            ->get("compliance_incident_types")
            ->row_array();
    }
}
