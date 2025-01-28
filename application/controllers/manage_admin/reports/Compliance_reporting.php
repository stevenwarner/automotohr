<?php

use Aws\DynamoDb\Model\BatchRequest\DeleteRequest;

defined('BASEPATH') or exit('No direct script access allowed');

class Compliance_reporting extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/compliance_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
    }

    public function index()
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'compliance_reporting';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Safety Reporting - Safety Types";
        $compliance_types = $this->compliance_report_model->get_all_compliance_types();

        $this->data['compliance_types'] = $compliance_types;
        $this->render('manage_admin/compliance_reporting/index');
    }

    public function add_new_type($id = '')
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'compliance_reporting';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Safety Reporting - Add New Compliance Type";
        $this->data['name']   = '';
        $this->data['ins']    = '';
        $this->data['rsn']    = '';
        $this->data['status'] = 1;
        // $managerlists = $this->compliance_report_model->get_manager_list();
        $this->data['managerlists'] = []; //$managerlists;
        $this->data['form'] = 'add';



        $compliance_types = $this->compliance_report_model->get_all_compliance_incidents();
        $this->data['compliance_incidents_types'] = $compliance_types;

        $mapedData = $this->compliance_report_model->get_compliance_incident_map($id);
        $this->data['mapedIncidents'] = array_column($mapedData, 'compliance_incident_sid');



        if ($id != '') {
            $this->data['page_title'] = "Compliance Safety Reporting - Update Compliance Type";
            $inc_type = $this->compliance_report_model->get_compliance_type($id);

            if (sizeof($inc_type) > 0) {
                $this->data['name']   = ucfirst($inc_type[0]['compliance_name']);
                $this->data['status'] = $inc_type[0]['status'];
                $this->data['ins'] = ucfirst($inc_type[0]['instructions']);
                $this->data['rsn'] = $inc_type[0]['reasons'];
                $this->data['safety_checklist'] = $inc_type[0]['safety_checklist'];
                $this->data['is_safety_incident'] = $inc_type[0]['is_safety_incident'];
                $this->data['tab_color'] = $inc_type[0]['tab_color'];

                // $this->data['fillable_by'] = $inc_type[0]['fillable_by'];
                $this->data['parent_sid'] = $inc_type[0]['parent_sid'];
            }

            $this->data['form'] = 'update';
        }

        if (isset($_POST['form-submit']) && ($_POST['form-submit'] == 'Add' || $_POST['form-submit'] == 'Update')) {
            if ($_POST['form-submit'] == 'Add') {
                unset($_POST['form-submit']);

                $inserData = [];
                $inserData['compliance_name'] = $_POST['compliance_name'];
                $inserData['status'] = $_POST['status'];
                $inserData['instructions'] = $_POST['instructions'];
                $inserData['reasons'] = $_POST['reasons'];

                $complianceTypeId = $this->compliance_report_model->add_compliance_type($inserData);

                //Attach incident to compliance type
                if ($_POST['incidents']) {
                    foreach ($_POST['incidents'] as $incident) {
                        $inserData = [];
                        $inserData['compliance_type_sid'] = $complianceTypeId;
                        $inserData['compliance_incident_sid'] = $incident;
                        $inserData['created_at'] = getSystemDate();
                        $this->compliance_report_model->add_compliance_incident_map($inserData);
                    }
                }
            } else {
                unset($_POST['form-submit']);

                $mapedData = $this->compliance_report_model->get_compliance_incident_map($id);
                $mapedIncidents = array_column($mapedData, 'compliance_incident_sid');

                if ($_POST['incidents']) {
                    if (empty($mapedIncidents)) {
                        foreach ($_POST['incidents'] as $incident) {
                            $inserData = [];
                            $inserData['compliance_type_sid'] = $id;
                            $inserData['compliance_incident_sid'] = $incident;
                            $inserData['created_at'] = getSystemDate();
                            $this->compliance_report_model->add_compliance_incident_map($inserData);
                        }
                    } else {

                        foreach ($mapedIncidents as $incident) {
                            if (!in_array($incident, $_POST['incidents'])) {
                                $this->compliance_report_model->delete_compliance_incident_map($id, $incident);
                            }
                        }

                        //add
                        foreach ($_POST['incidents'] as $incident) {
                            if (!in_array($incident, $mapedIncidents)) {
                                $inserData = [];
                                $inserData['compliance_type_sid'] = $id;
                                $inserData['compliance_incident_sid'] = $incident;
                                $inserData['created_at'] = getSystemDate();
                                $this->compliance_report_model->add_compliance_incident_map($inserData);
                            }
                        }
                    }
                } else {
                    $this->compliance_report_model->delete_compliance_incident_map($id);
                }

                unset($_POST['incidents']);
                $this->compliance_report_model->update_compliance_type($id, $_POST);
            }

            redirect('manage_admin/reports/compliance_reporting');
        }

        $this->render('manage_admin/compliance_reporting/add_new_type');
    }

    public function enable_disable_type($id)
    {
        $data = array('status' => $this->input->get('status'));
        $this->compliance_report_model->update_compliance_type($id, $data);
        print_r(json_encode(array('message' => 'updated')));
    }

    public function enable_disable_question($id)
    {
        $data = array('status' => $this->input->get('status'));
        $this->compliance_report_model->update_compliance_question($id, $data);
        print_r(json_encode(array('message' => 'updated')));
    }

    public function view_compliance_questions($id)
    {
        $name = $this->compliance_report_model->fetch_compliance_name($id);
        $compliance_name = $name[0]['compliance_name'];
        $this->data['safety_checklist'] = $name[0]['safety_checklist'];
        $this->data['page_title'] = "Compliance Safety Reporting  - " . $compliance_name;
        $this->data['inc_id'] = $id;
        $questions = $this->compliance_report_model->fetch_questions($id);
        $this->data['questions'] = $questions;
        // $this->data
        $this->render('manage_admin/compliance_reporting/list_questions');
    }

    public function add_new_question($id)
    {
        $name = $this->compliance_report_model->fetch_compliance_name($id);
        $complaince_name = $name[0]['compliance_name'];
        $this->data['sub_title'] = $complaince_name;
        $this->data['page_title'] = "Compliance Safety Reporting  - Add Compliance Questions";
        $this->data['fields'] = array('text', 'textarea', 'radio', 'single select', 'multi select');
        $this->data['status'] = 1;
        $this->data['form'] = 'add';
        $this->data['inc_id'] = $id;
        $this->data['radio_questions'] = $this->compliance_report_model->get_all_radio_questions($id);

        if (isset($_POST['form-submit']) || isset($_POST['more'])) {
            if (isset($_POST['form-submit'])) {
                unset($_POST['form-submit']);
                $link = 'manage_admin/reports/compliance_reporting/view_compliance_questions/' . $id;
            } elseif (isset($_POST['more'])) {
                unset($_POST['more']);
                $link = 'manage_admin/reports/compliance_reporting/add_new_question/' . $id;
            }

            if ($_POST['question_type'] == 'text' || $_POST['question_type'] == 'textarea' || $_POST['question_type'] == 'radio') {
                $_POST['options'] = '';
            } else {
                $_POST['options'] = implode(',', array_filter($_POST['options']));
            }

            $this->compliance_report_model->add_new_question($_POST);
            redirect($link);
        }

        $this->render('manage_admin/compliance_reporting/add_new_question');
    }

    public function edit_question($id)
    {
        $this->data['page_title'] = "Compliance Safety Reporting - Update Compliance Questions";
        $this->data['fields'] = array('text', 'textarea', 'radio', 'single select', 'multi select');
        $question = $this->compliance_report_model->get_question($id);
        $compliance_type_id = $question[0]['compliance_type_id'];
        $name = $this->compliance_report_model->fetch_compliance_name($compliance_type_id);
        $compliance_name = $name[0]['compliance_name'];
        $this->data['sub_title'] = $compliance_name;
        $this->data['radio_questions'] = $this->compliance_report_model->get_all_radio_questions($compliance_type_id);
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
            $this->compliance_report_model->update_compliance_question($id, $_POST);
            redirect('manage_admin/reports/compliance_reporting/view_compliance_questions/' . $question[0]['compliance_type_id']);
        }

        $this->data['question'] = $question;
        $this->render('manage_admin/compliance_reporting/update_question');
    }

    public function reported_incidents()
    {
        $this->data['page_title'] = "Incident Reporting System - Reported Incidents";
        $incidents = $this->compliance_report_model->get_all_incidents();
        $this->data['incidents'] = $incidents;

        $this->render('manage_admin/incident_reporting/reported_incidents');
    }

    public function view_incident($id)
    {
        $this->data['page_title'] = "Incident Reporting System - View Incident";
        $incident = $this->compliance_report_model->get_specific_incident($id);

        if (sizeof($incident) > 0) {
            $cid = $incident[0]['company_sid'];
        }

        $com_emp = $this->compliance_report_model->get_com_and_emp_name($id);
        $this->data['que_ans'] = $incident;
        $this->data['com_emp'] = $com_emp;
        $files = $this->compliance_report_model->incident_related_documents($id);
        $this->data['files'] = $files;
        $comments = $this->compliance_report_model->get_incident_comments($id);
        $this->data['comments'] = $comments;
        $this->render('manage_admin/incident_reporting/view_incident');
    }

    public function configure_incident($cid, $inc_id)
    {
        $redirect_url = 'manage_admin/reports';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['cid'] = $cid;
        $this->data['all_employees'] = $this->compliance_report_model->get_company_employees($cid);
        $this->data['page_title'] = 'Incident Configuration';
        $configured_employees = $this->compliance_report_model->get_configured_employees($cid, $inc_id);
        $company_incident_name = $this->compliance_report_model->get_company_incident_name($cid, $inc_id);
        $this->data['company_incident_name'] = $company_incident_name;
        $checked_employees = array();

        if (sizeof($configured_employees) > 0) {
            foreach ($configured_employees as $con) {
                $checked_employees[] = $con['employer_id'];
            }
        }

        $this->data['configured_employees'] = $checked_employees;

        if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
            $employees = isset($_POST['checklist']) ? $_POST['checklist'] : array();
            $conf = array();
            $conf['company_id'] = $cid;
            $conf['incident_type_id'] = $inc_id;

            if (is_array($employees)) {
                foreach ($employees as $employee) {
                    if (!in_array($employee, $checked_employees)) {
                        $conf['employer_id'] = $employee;
                        $this->compliance_report_model->insert_incident_configuration($conf);
                    }
                }
            }

            if (sizeof($configured_employees) > 0) {
                foreach ($configured_employees as $con) {
                    if (!in_array($con['employer_id'], $employees)) {
                        $this->compliance_report_model->delete_incident_configuration($con['employer_id'], $inc_id);
                    }
                }
            }

            $this->data['configured_employees'] = $employees;
            $this->session->set_flashdata('message', '<strong>Success</strong> : Incident Configuration Changed!');
        }
        $this->render('manage_admin/incident_reporting/assign_employees_incident');
    }


    //
    public function add_new_compliance_type($id = '')
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'compliance_reporting';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Incident  - Add New Compliance Incident Type";
        $this->data['name']   = '';
        $this->data['ins']    = '';
        $this->data['rsn']    = '';
        $this->data['status'] = 1;
        // $managerlists = $this->compliance_report_model->get_manager_list();
        $this->data['managerlists'] = []; //$managerlists;
        $this->data['form'] = 'add';

        if ($id != '') {
            $this->data['page_title'] = "Compliance Incident Reporting - Update Compliance Incident Type";
            $inc_type = $this->compliance_report_model->get_compliance_incident_type($id);

            //_e($inc_type,true,true);

            if (sizeof($inc_type) > 0) {
                $this->data['name']   = ucfirst($inc_type[0]['incident_name']);
                $this->data['status'] = $inc_type[0]['status'];
                $this->data['ins'] = ucfirst($inc_type[0]['description']);
                $this->data['code'] = ucfirst($inc_type[0]['code']);
                $this->data['priority'] = ucfirst($inc_type[0]['priority']);
            }

            $this->data['form'] = 'update';
        }

        if (isset($_POST['form-submit']) && ($_POST['form-submit'] == 'Add' || $_POST['form-submit'] == 'Update')) {
            if ($_POST['form-submit'] == 'Add') {
                unset($_POST['form-submit']);
                $this->compliance_report_model->add_compliance_incident_type($_POST);
            } else {
                unset($_POST['form-submit']);
                $this->compliance_report_model->update_compliance_incident_type($id, $_POST);
            }

            redirect('manage_admin/reports/compliance_reporting/incident_list');
        }

        $this->render('manage_admin/compliance_reporting/add_new_incident_type');
    }



    //
    public function incident_list()
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'compliance_reporting';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Compliance Safety Reporting - Complianc Incidents";
        $compliance_types = $this->compliance_report_model->get_all_compliance_incidents();

        $this->data['compliance_incidents_types'] = $compliance_types;
        $this->render('manage_admin/compliance_reporting/incident_list');
    }


    public function enable_disable_incident_type($id)
    {
        $data = array('status' => $this->input->get('status'));
        $this->compliance_report_model->update_incident_type($id, $data);
        print_r(json_encode(array('message' => 'updated')));
    }


    //
    public function ckImageUpload()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['upload'])) {
            $file = $_FILES['upload'];
            $uploadDir = 'assets/uploaded_videos/compliance_incident/';
            $uploadFile = $uploadDir . basename($file['name']);
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {

                echo json_encode([
                    'uploaded' => 1,
                    'fileName' => basename($file['name']),
                    'url' => base_url($uploadFile)
                ]);
            } else {
                echo json_encode(['error' => 'Image upload failed']);
            }
        }
    }
}
