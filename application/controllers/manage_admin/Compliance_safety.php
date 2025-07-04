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
        $redirect_url = 'manage_admin';
        $function_name = 'compliance_safety_dashboard';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Safety - Dashboard";
        $this->data['report_types'] = $this->compliance_safety_model->getAllReportTypes();
        $this->data['incident_types'] = $this->compliance_safety_model->getAllIncidentTypes();
        $this->render('manage_admin/compliance_safety/dashboard');
    }

    public function manageSeverityLevels()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'compliance_safety_manage_severity_levels';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Safety - Severity Levels";
        $this->data['severity_levels'] = $this->compliance_safety_model->getSeverityLevels();
        $this->render('manage_admin/compliance_safety/severity_levels');
    }

    public function reportAdd()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'compliance_safety_report_add';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Safety - Add Report type";
        // add rules
        $this->form_validation->set_rules('report_name', 'Report Name', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('bg_color_code', 'Background Color Code', 'required');
        $this->form_validation->set_rules('color_code', 'Color Code', 'required');
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
                    "color_code" => $post["color_code"],
                    "bg_color_code" => $post["bg_color_code"],
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
        $redirect_url = 'manage_admin';
        $function_name = 'compliance_safety_report_edit';
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
        $this->form_validation->set_rules('color_code', 'Color Code', 'required');
        $this->form_validation->set_rules('bg_color_code', 'Background Color Code', 'required');
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
                    "color_code" => $post["color_code"],
                    "bg_color_code" => $post["bg_color_code"],
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
        $redirect_url = 'manage_admin';
        $function_name = 'compliance_safety_incident_add';
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
            // get the questions from main report and add them to the incident
            $this->setIncidentQuestions();
            //
            $this->session->set_flashdata('message', '<strong>Success</strong> Report Incident Type added.');
            return redirect(
                "manage_admin/compliance_safety/dashboard"
            );
        }
    }

    public function incidentTypeEdit(int $id)
    {
        $redirect_url = 'manage_admin';
        $function_name = 'compliance_safety_incident_edit';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // get the report type
        $this->data["report_type"] = $this->compliance_safety_model->getIncidentById($id);
        $this->data["items"] = $this->compliance_safety_model->getIncidentItems($id);

        $this->data['page_title'] = "Compliance Safety Report Incident Type - " . $this->data["report_type"]["compliance_incident_type_name"];
        $this->data['page_title'] = "Compliance Safety - Edit Report Incident type";
        $this->data['incidentId'] = $id;
        // add rules
        $this->form_validation->set_rules('compliance_incident_type_name', 'Report Incident Name', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        //
        if (!$this->form_validation->run()) {
            $this->data["severity_status"] = $this
                ->compliance_safety_model
                ->getSeverityLevelsObject();
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
            // $this->setIncidentQuestions();

            //
            $this->session->set_flashdata('message', '<strong>Success</strong> Report Incident Type updated.');
            return redirect(
                "manage_admin/compliance_safety/dashboard"
            );
        }
    }

    public function view_incident_questions($id)
    {
        $name = $this->compliance_safety_model->fetchIncidentTypeName($id);
        $incident_name = $name[0]['compliance_incident_type_name'];
        $this->data['page_title'] = "Incident Type Questions - " . $incident_name;
        $this->data['inc_id'] = $id;
        $questions = $this->compliance_safety_model->fetchQuestions($id);
        $this->data['questions'] = $questions;
        // $this->data
        $this->render('manage_admin/compliance_safety/incidents/list_questions');
    }

    public function add_new_question($id)
    {
        $name = $this->compliance_safety_model->fetchIncidentTypeName($id);
        $incident_name = $name[0]['incident_name'];
        $this->data['sub_title'] = $incident_name;
        $this->data['page_title'] = "Incident Types - Add Questions";
        $this->data['fields'] = array('text', 'textarea', 'radio', 'single select', 'multi select');
        $this->data['status'] = 1;
        $this->data['form'] = 'add';
        $this->data['inc_id'] = $id;
        $this->data['radio_questions'] = $this->compliance_safety_model->getAllRadioQuestions($id);

        if (isset($_POST['form-submit']) || isset($_POST['more'])) {
            if (isset($_POST['form-submit'])) {
                unset($_POST['form-submit']);
                $link = 'manage_admin/compliance_safety/incident_types/view_incident_questions/' . $id;
            } elseif (isset($_POST['more'])) {
                unset($_POST['more']);
                $link = 'manage_admin/compliance_safety/incident_types/add_new_question/' . $id;
            }

            if ($_POST['question_type'] == 'text' || $_POST['question_type'] == 'textarea' || $_POST['question_type'] == 'radio') {
                $_POST['options'] = '';
            } else {
                $_POST['options'] = implode(',', array_filter($_POST['options']));
            }

            $this->compliance_safety_model->add_new_question($_POST);
            return redirect($link);
        }

        $this->render('manage_admin/compliance_safety/incidents/add_new_question');
    }

    public function edit_question($id)
    {
        $this->data['page_title'] = "Incident Reporting System - Update Incident Questions";
        $this->data['fields'] = array('text', 'textarea', 'radio', 'single select', 'multi select');
        $question = $this->compliance_safety_model->get_question($id);
        $incident_type_id = $question[0]['compliance_incident_types_id'];
        $name = $this->compliance_safety_model->fetchIncidentTypeName($incident_type_id);
        $incident_name = $name[0]['incident_name'];
        $this->data['sub_title'] = $incident_name;
        $this->data['radio_questions'] = $this->compliance_safety_model->getAllRadioQuestions($incident_type_id);
        if (isset($_POST['form-submit']) && ($_POST['form-submit'] == 'Update')) {
            unset($_POST['form-submit']);

            if ($_POST['question_type'] == 'text' || $_POST['question_type'] == 'textarea' || $_POST['question_type'] == 'radio') {
                $_POST['options'] = '';
            } else {
                $_POST['options'] = implode(',', array_filter($_POST['options']));
            }

            if (!isset($_POST['is_required'])) {
                $_POST['is_required'] = 0;
            }
            $this->compliance_safety_model->update_incident_question($id, $_POST);
            redirect('manage_admin/compliance_safety/incident_types/view_incident_questions/' . $question[0]['compliance_incident_types_id']);
        }

        $this->data['question'] = $question;
        $this->render('manage_admin/compliance_safety/incidents/update_question');
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
                "message" => "You have successfully " . ($post["status"] === "off" ? "Deactivated" : "Activated") . " the " . ($post["type"] === "report" ? "Report Type" : "Report Incident Type") . "."
            ]
        );
    }


    public function handleSeverityLevel(int $id)
    {
        // get the sanitized post
        $post = $this->input->post(null, true);
        if ($post["type"] === "level") {
            $updateArray = [
                "level" => $post["cl"]
            ];
        } else {
            $updateArray = [
                $post["type"] === "bg" ? "bg_color" : "txt_color" => $post["cl"]
            ];
        }

        // update
        $this
            ->db
            ->where("sid", $post["id"])
            ->update(
                "compliance_severity_levels",
                $updateArray
            );
        //
        return SendResponse(
            200,
            [
                "message" => "You have successfully updated the severity level color."
            ]
        );
    }

    public function saveIncidentTypeItem(int $incidentTypeId)
    {
        // get the sanitized post
        $description = $this->input->post("description", false);
        // update
        $this
            ->db
            ->insert(
                "compliance_report_incident_types",
                [
                    "compliance_report_incident_sid" => $incidentTypeId,
                    "title" => $this->input->post("title", true),
                    "severity_level_sid" => $this->input->post("severityLevel", true),
                    "description" => $description,
                    "created_at" => getSystemDate(),
                    "updated_at" => getSystemDate(),
                ]
            );
        //
        return SendResponse(
            200,
            [
                "message" => "You have successfully added an item.",
                "id" => $this->db->insert_id()
            ]
        );
    }

    public function deleteIncidentTypeItem(int $id)
    {
        // update
        $this
            ->db
            ->where("sid", $id)
            ->delete(
                "compliance_report_incident_types"
            );
        //
        return SendResponse(
            200,
            [
                "message" => "You have successfully deleted an item."
            ]
        );
    }

    public function getIncidentTypeItem(int $id)
    {
        // update
        $record = $this
            ->db
            ->where("sid", $id)
            ->get("compliance_report_incident_types")
            ->row_array();
        //
        return SendResponse(
            200,
            $record
        );
    }

    public function updateIncidentTypeItem(int $id)
    {
        // get the sanitized post
        $description = $this->input->post("description", false);
        // update
        $this
            ->db
            ->where("sid", $id)
            ->update(
                "compliance_report_incident_types",
                [
                    "title" => $this->input->post("title", true),
                    "severity_level_sid" => $this->input->post("severityLevel", true),
                    "description" => $description,
                    "updated_at" => getSystemDate()
                ]
            );
        //
        return SendResponse(
            200,
            [
                "message" => "You have successfully updated the item."
            ]
        );
    }

    public function enable_disable_question($id)
    {
        $data = array('status' => $this->input->get('status'));
        $this->compliance_safety_model->update_incident_question($id, $data);
        print_r(json_encode(array('message' => 'updated')));
    }

    private function setIncidentQuestions()
    {
        // get the questions of main
        $questions = $this
            ->db
            ->select([
                "label",
                "question_type",
                "options",
                "placeholder",
                "status",
                "is_required",
                "related_to_question",
            ])
            ->where("compliance_report_types_id", 1)
            ->order_by("id", "ASC")
            ->get("compliance_report_types_questions")
            ->result_array();
        //
        if (!$questions) {
            return false;
        }
        //

        // get all the incident ids
        $incidents =
            $this
                ->db
                ->select([
                    "id"
                ])
                ->get("compliance_incident_types")
                ->result_array();
        //
        if (!$incidents) {
            return false;
        }
        //
        foreach ($incidents as $item) {
            //
            if (
                !$this
                    ->db
                    ->where("compliance_incident_types_id", $item["id"])
                    ->count_all_results("compliance_incident_types_questions")
            ) {
                foreach ($questions as $question) {
                    $question["compliance_incident_types_id"] = $item["id"];
                    $this->db->insert("compliance_incident_types_questions", $question);
                }
            }
        }
    }
}
