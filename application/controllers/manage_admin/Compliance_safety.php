<?php defined('BASEPATH') || exit('No direct script access allowed');

class Compliance_safety extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/compliance_safety_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
    }

    public function dashboard()
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'compliance_safety_dashboard';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Safety - Dashboard";
        $this->data['report_types'] = $this->compliance_safety_model->getAllReportTypes();
        $this->data['incident_types'] = $this->compliance_safety_model->getAllIncidentTypes();
        $this->render('manage_admin/compliance_safety/dashboard');
    }

    public function reportAdd()
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'compliance_safety_report_add';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Safety - Add Report type";
        // add rules
        $this->form_validation->set_rules('report_name', 'Report Name', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('instructions', 'Instructions', 'required');
        //
        if (!$this->form_validation->run()) {
            // get all the incident types
            $this->data["incident_types"] = $this->compliance_safety_model->getActiveIncidentTypes();
            $this->render('manage_admin/compliance_safety/reports/add');
        } else {
            // get the sanitized post
            $post = $this->input->post(null, true);
            //
            $todayDateTime = getSystemDate();
            //
            $this
                ->db
                ->insert("compliance_report_types", [
                    "compliance_report_name" => $post["report_name"],
                    "status" => $post["status"],
                    "instructions" => $post["instructions"],
                    "reasons" => $post["reasons"],
                    "created_at" => $todayDateTime,
                    "updated_at" => $todayDateTime,
                ]);
            //
            $reportTypeId = $this->db->insert_id();

            if ($reportTypeId && $post["incident_types"]) {
                foreach ($post["incident_types"] as $type) {
                    $this
                        ->db
                        ->insert("compliance_report_incident_types_mapping", [
                            "report_sid" => $reportTypeId,
                            "incident_sid" => $type,
                            "created_at" => $todayDateTime,
                        ]);
                }
            }
            //
            $this->session->set_flashdata('message', '<strong>Success</strong> Report type added.');
            return redirect(
                "manage_admin/compliance_safety/dashboard"
            );
        }
    }

    public function reportEdit(int $reportTypeId)
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'compliance_safety_report_edit';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // get the report type
        $this->data["report_type"] = $this->compliance_safety_model->getReportTypeById($reportTypeId);

        $this->data['page_title'] = "Compliance Safety Report Type - " . $this->data["report_type"]["compliance_report_name"];
        // add rules
        $this->form_validation->set_rules('report_name', 'Report Name', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('instructions', 'Instructions', 'required');
        //
        if (!$this->form_validation->run()) {
            $this->data["incident_types"] = $this->compliance_safety_model->getActiveIncidentTypes();
            $this->render('manage_admin/compliance_safety/reports/edit');
        } else {
            // get the sanitized post
            $post = $this->input->post(null, true);
            //
            $todayDateTime = getSystemDate();
            //
            $this
                ->db
                ->where("id", $reportTypeId)
                ->update("compliance_report_types", [
                    "compliance_report_name" => $post["report_name"],
                    "status" => $post["status"],
                    "instructions" => $post["instructions"],
                    "reasons" => $post["reasons"],
                    "updated_at" => $todayDateTime,
                ]);

            $this
                ->db
                ->where("report_sid", $reportTypeId)
                ->delete("compliance_report_incident_types_mapping");

            if ($post["incident_types"]) {
                foreach ($post["incident_types"] as $type) {
                    $this
                        ->db
                        ->insert("compliance_report_incident_types_mapping", [
                            "report_sid" => $reportTypeId,
                            "incident_sid" => $type,
                            "created_at" => $todayDateTime,
                        ]);
                }
            }
            //
            $this->session->set_flashdata('message', '<strong>Success</strong> Report type updated.');
            return redirect(
                "manage_admin/compliance_safety/dashboard"
            );
        }
    }


    public function incidentTypeAdd()
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'compliance_safety_incident_add';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Safety - Add Report Incident type";
        // add rules
        $this->form_validation->set_rules('compliance_incident_type_name', 'Report Incident Name', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        //
        if (!$this->form_validation->run()) {
            $this->render('manage_admin/compliance_safety/incidents/add');
        } else {
            // get the sanitized post
            $post = $this->input->post(null, true);
            //
            $todayDateTime = getSystemDate();
            //
            $this
                ->db
                ->insert("compliance_incident_types", [
                    "compliance_incident_type_name" => $post["compliance_incident_type_name"],
                    "status" => $post["status"],
                    "description" => $post["description"],
                    "code" => $post["code"],
                    "priority" => $post["priority"],
                    "created_at" => $todayDateTime,
                    "updated_at" => $todayDateTime,
                ]);
            //
            $this->session->set_flashdata('message', '<strong>Success</strong> Report Incident Type added.');
            return redirect(
                "manage_admin/compliance_safety/dashboard"
            );
        }
    }

    public function incidentTypeEdit(int $id)
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'compliance_safety_incident_edit';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // get the report type
        $this->data["report_type"] = $this->compliance_safety_model->getIncidentById($id);

        $this->data['page_title'] = "Compliance Safety Report Incident Type - " . $this->data["report_type"]["compliance_incident_type_name"];
        $this->data['page_title'] = "Compliance Safety - Edit Report Incident type";
        // add rules
        $this->form_validation->set_rules('compliance_incident_type_name', 'Report Incident Name', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        //
        if (!$this->form_validation->run()) {
            $this->render('manage_admin/compliance_safety/incidents/edit');
        } else {
            // get the sanitized post
            $post = $this->input->post(null, true);
            //
            $todayDateTime = getSystemDate();
            //
            $this
                ->db
                ->where("id", $id)
                ->update("compliance_incident_types", [
                    "compliance_incident_type_name" => $post["compliance_incident_type_name"],
                    "status" => $post["status"],
                    "description" => $post["description"],
                    "code" => $post["code"],
                    "priority" => $post["priority"],
                    "updated_at" => $todayDateTime,
                ]);
            //
            $this->session->set_flashdata('message', '<strong>Success</strong> Report Incident Type updated.');
            return redirect(
                "manage_admin/compliance_safety/dashboard"
            );
        }
    }


    public function handleStatus(int $id)
    {
        // get the sanitized post
        $post = $this->input->post(null, true);
        // update
        $this
            ->db
            ->where("id", $post["id"])
            ->update(
                $post["type"] === "report" ? "compliance_report_types" : "compliance_incident_types",
                [
                    "status" => $post["status"] === "off" ? 0 : 1
                ]
            );
        //
        return SendResponse(
            200,
            [
                "message" => "You have successfully " . ($post["status"] === "off" ?  "Deactivated" : "Activated") . " the " . ($post["type"] === "report" ? "Report Type" : "Report Incident Type") . "."
            ]
        );
    }
}
