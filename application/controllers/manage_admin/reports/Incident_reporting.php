<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Incident_reporting extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/incident_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
    }

    public function index(){
        $redirect_url       = 'manage_admin';
        $function_name      = 'incident_reporting';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Incident Reporting System - Incident Types";
        $incident_types = $this->incident_report_model->get_all_incident_types();
        $this->data['incident_types'] = $incident_types;
        $this->render('manage_admin/incident_reporting/index');
    }

    public function checklists(){
        $redirect_url       = 'manage_admin';
        $function_name      = 'incident_reporting';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Incident Reporting System - Safety CheckList";
        $incident_types = $this->incident_report_model->get_all_check_list();
        $this->data['incident_types'] = $incident_types;
        $this->render('manage_admin/incident_reporting/index');
    }

    public function add_new_type($id=''){
        $redirect_url       = 'manage_admin';
        $function_name      = 'incident_reporting';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Incident Reporting System - Add New Incident Type";
        $this->data['name']   = '';
        $this->data['ins']    = '';
        $this->data['rsn']    = '';
        $this->data['status'] = 1;
        $managerlists = $this->incident_report_model->get_manager_list();
        $this->data['managerlists'] = $managerlists;
        $this->data['form'] = 'add';
        
        if($id!=''){
            $this->data['page_title'] = "Incident Reporting System - Update Incident Type";
            $inc_type = $this->incident_report_model->get_incident_type($id);
            
            if(sizeof($inc_type)>0){
                $this->data['name']   = ucfirst($inc_type[0]['incident_name']);
                $this->data['status'] = $inc_type[0]['status'];
                $this->data['ins'] = ucfirst($inc_type[0]['instructions']);
                $this->data['rsn'] = $inc_type[0]['reasons'];
                $this->data['safety_checklist'] = $inc_type[0]['safety_checklist'];
                $this->data['is_safety_incident'] = $inc_type[0]['is_safety_incident'];
                $this->data['fillable_by'] = $inc_type[0]['fillable_by'];
                $this->data['parent_sid'] = $inc_type[0]['parent_sid'];
            }
            
            $this->data['form'] = 'update';
        }
        
        if(isset($_POST['form-submit']) && ($_POST['form-submit'] == 'Add' || $_POST['form-submit'] == 'Update')){
            if($_POST['form-submit'] == 'Add'){
                unset($_POST['form-submit']);
                $this->incident_report_model->add_incident_type($_POST);
            } else {
                unset($_POST['form-submit']);
                $this->incident_report_model->update_incident_type($id, $_POST);
            }

            $redirect = $_POST['safety_checklist'];

            if ($redirect == 1) {
                redirect('manage_admin/reports/incident_reporting/checklists');
            } elseif ($redirect == 0) {
                redirect('manage_admin/reports/incident_reporting');
            }
        }
        
        $this->render('manage_admin/incident_reporting/add_new_type');
    }

    public function enable_disable_type($id){
        $data = array('status'=>$this->input->get('status'));
        $this->incident_report_model->update_incident_type($id,$data);
        print_r(json_encode(array('message'=>'updated')));
    }

    public function enable_disable_question($id){
        $data = array('status'=>$this->input->get('status'));
        $this->incident_report_model->update_incident_question($id,$data);
        print_r(json_encode(array('message'=>'updated')));
    }

    public function view_incident_questions($id){
        $name = $this->incident_report_model->fetch_incident_name($id);
        $incident_name = $name[0]['incident_name'];
        $this->data['safety_checklist'] = $name[0]['safety_checklist'];
        $this->data['page_title'] = "Incident Reporting System - ".$incident_name;
        $this->data['inc_id'] = $id;
        $questions = $this->incident_report_model->fetch_questions($id);
        $this->data['questions'] = $questions;
        // $this->data
        $this->render('manage_admin/incident_reporting/list_questions');
    }

    public function add_new_question($id){
        $name = $this->incident_report_model->fetch_incident_name($id);
        $incident_name = $name[0]['incident_name'];
        $this->data['sub_title'] = $incident_name;
        $this->data['page_title'] = "Incident Reporting System - Add Incident Questions";
        $this->data['fields'] = array('text','textarea','radio','single select','multi select');
        $this->data['status'] = 1;
        $this->data['form'] = 'add';
        $this->data['inc_id'] = $id;
        $this->data['radio_questions'] = $this->incident_report_model->get_all_radio_questions($id);

        if(isset($_POST['form-submit']) || isset($_POST['more'])){
            if(isset($_POST['form-submit'])){
                unset($_POST['form-submit']);
                $link = 'manage_admin/reports/incident_reporting/view_incident_questions/'.$id;
            } elseif(isset($_POST['more'])){
                unset($_POST['more']);
                $link = 'manage_admin/reports/incident_reporting/add_new_question/'.$id;
            }
            
            if($_POST['question_type'] == 'text' || $_POST['question_type'] == 'textarea' || $_POST['question_type'] == 'radio'){
                $_POST['options'] = '' ;
            } else {
                $_POST['options'] = implode(',',array_filter($_POST['options']));
            }

            $this->incident_report_model->add_new_question($_POST);
            redirect($link);
        }
        
        $this->render('manage_admin/incident_reporting/add_new_question');

    }

    public function edit_question($id){
        $this->data['page_title'] = "Incident Reporting System - Update Incident Questions";
        $this->data['fields'] = array('text','textarea','radio','single select','multi select');
        $question = $this->incident_report_model->get_question($id);
        $incident_type_id = $question[0]['incident_type_id'];
        $name = $this->incident_report_model->fetch_incident_name($incident_type_id);
        $incident_name = $name[0]['incident_name'];
        $this->data['sub_title'] = $incident_name;
        $this->data['radio_questions'] = $this->incident_report_model->get_all_radio_questions($incident_type_id);
        if(isset($_POST['form-submit']) && ($_POST['form-submit'] == 'Update')) {
            unset($_POST['form-submit']);
            
            if($_POST['question_type']=='text' || $_POST['question_type']=='textarea' || $_POST['question_type']=='radio'){
                $_POST['options'] = '' ;
            } else {
                $_POST['options'] = implode(',',array_filter($_POST['options']));
            }
            
            if(!isset($_POST['is_required'])){
                $_POST['is_required'] = 0;
            }
            $this->incident_report_model->update_incident_question($id, $_POST);
            redirect('manage_admin/reports/incident_reporting/view_incident_questions/'.$question[0]['incident_type_id']);
        }
        
        $this->data['question'] = $question;
        $this->render('manage_admin/incident_reporting/update_question');
    }

    public function reported_incidents(){
        $this->data['page_title'] = "Incident Reporting System - Reported Incidents";
        $incidents = $this->incident_report_model->get_all_incidents();
        $this->data['incidents'] = $incidents;

        $this->render('manage_admin/incident_reporting/reported_incidents');
    }

    public function view_incident($id){
        $this->data['page_title'] = "Incident Reporting System - View Incident";
        $incident = $this->incident_report_model->get_specific_incident($id);
        
        if(sizeof($incident)>0){
            $cid = $incident[0]['company_sid'];
        }

        $com_emp = $this->incident_report_model->get_com_and_emp_name($id);
        $this->data['que_ans'] = $incident;
        $this->data['com_emp'] = $com_emp;
        $files = $this->incident_report_model->incident_related_documents($id);
        $this->data['files'] = $files;
        $comments = $this->incident_report_model->get_incident_comments($id);
        $this->data['comments'] = $comments;
        $this->render('manage_admin/incident_reporting/view_incident');
    }

    public function configure_incident($cid,$inc_id){
        $redirect_url = 'manage_admin/reports';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['cid'] = $cid;
        $this->data['all_employees'] = $this->incident_report_model->get_company_employees($cid);
        $this->data['page_title'] = 'Incident Configuration';
        $configured_employees = $this->incident_report_model->get_configured_employees($cid,$inc_id);
        $company_incident_name = $this->incident_report_model->get_company_incident_name($cid,$inc_id);
        $this->data['company_incident_name'] = $company_incident_name;
        $checked_employees = array();
        
        if(sizeof($configured_employees)>0){
            foreach($configured_employees as $con){
                $checked_employees[] = $con['employer_id'];
            }
        }
        
        $this->data['configured_employees'] = $checked_employees;

        if(isset($_POST['submit']) && $_POST['submit'] == 'Save'){
            $employees = isset($_POST['checklist'])? $_POST['checklist']: array();
            $conf = array();
            $conf['company_id'] = $cid;
            $conf['incident_type_id'] = $inc_id;
            
            if (is_array($employees)) {
                foreach($employees as $employee){
                    if(!in_array($employee,$checked_employees)){
                        $conf['employer_id'] = $employee;
                        $this->incident_report_model->insert_incident_configuration($conf);
                    }
                }
            }
            
            if(sizeof($configured_employees)>0){
                foreach($configured_employees as $con){
                    if(!in_array($con['employer_id'],$employees)){
                        $this->incident_report_model->delete_incident_configuration($con['employer_id'],$inc_id);
                    }
                }
            }
            
            $this->data['configured_employees'] = $employees;
            $this->session->set_flashdata('message', '<strong>Success</strong> : Incident Configuration Changed!');
        }
        $this->render('manage_admin/incident_reporting/assign_employees_incident');
    }
}
