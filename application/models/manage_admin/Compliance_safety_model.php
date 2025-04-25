<?php defined('BASEPATH') || exit('No direct script access allowed');

class Compliance_safety_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getSeverityLevelsObject()
    {
        $records = $this
            ->db
            ->order_by("sid", "ASC")
            ->get("compliance_severity_levels")
            ->result_array();
        if (!$records) {
            return [];
        }
        $tmp = [];
        foreach ($records as $record) {
            $tmp[$record["sid"]] = $record;
        }
        return $tmp;
    }


    public function getAllReportTypes()
    {
        return $this
            ->db
            ->select([
                "id",
                "compliance_report_name",
                "color_code",
                "bg_color_code",
                "updated_at",
                "status",
            ])
            ->order_by("status", "DESC")
            ->order_by("id", "DESC")
            ->get("compliance_report_types")
            ->result_array();
    }

    public function getSeverityLevels()
    {
        return $this
            ->db
            ->order_by("sid", "ASC")
            ->get("compliance_severity_levels")
            ->result_array();
    }

    public function getIncidentItems($incidentId)
    {
        return $this
            ->db
            ->select("sid, title, description, severity_level_sid")
            ->where("compliance_report_incident_sid", $incidentId)
            ->order_by("sid", "DESC")
            ->get("compliance_report_incident_types")
            ->result_array();
    }

    public function getReportTypeById(int $reportTypeId)
    {
        $record = $this
            ->db
            ->select([
                "compliance_report_name",
                "color_code",
                "bg_color_code",
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
        $records = $this
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


    public function fetchIncidentTypeName($id)
    {
        $this->db->select('compliance_incident_type_name');
        $this->db->where('id', $id);
        return $this->db->get('compliance_incident_types')->result_array();
    }

    public function fetchQuestions($id)
    {
        $this->db->select('compliance_incident_types_questions.*', 'compliance_incident_types.compliance_incident_type_name');
        $this->db->where('compliance_incident_types_id', $id);
        $this->db->join('compliance_incident_types', 'compliance_incident_types_questions.compliance_incident_types_id = compliance_incident_types.id', 'left');
        $questions = $this->db->get('compliance_incident_types_questions')->result_array();
        return $questions;
    }

    public function getAllRadioQuestions($incident_type_id = 0)
    {
        $this->db->select('label,id');
        $this->db->where('compliance_incident_types_id', $incident_type_id);
        $this->db->where('question_type', 'radio');
        $result = $this->db->get('compliance_incident_types_questions')->result_array();

        return $result;
    }

    public function get_question($id)
    {
        $this->db->where('id', $id);
        $result = $this->db->get('compliance_incident_types_questions')->result_array();
        return $result;
    }

    public function add_new_question($data)
    {
        $this->db->insert('compliance_incident_types_questions', $data);
        return $this->db->insert_id();
    }

    function update_incident_question($id, $data)
    {
        $this->db->where('id', $id);
        $type = $this->db->update('compliance_incident_types_questions', $data);
        return $type;
    }
}
