<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Interview_questionnaires extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/interview_questionnaires_model');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'interview_questionnaires';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

        if ($this->form_validation->run() == false) {
            $questionnaires = $this->interview_questionnaires_model->get_questionnaires();
            $this->data['title'] = 'Interview Questionnaires';
            $this->data['questionnaires'] = $questionnaires;
            $this->render('manage_admin/interview_questionnaires/index');
        } else {
            $perform_action = $this->input->post('perform_action');
            switch($perform_action){
                case 'delete_questionnaire':
                    $questionnaire_sid = $this->input->post('questionnaire_sid');
                    $this->interview_questionnaires_model->delete_questionnaire($questionnaire_sid);
                    break;
            }
        }
    }

    public function add_questionnaire() {
        $redirect_url       = 'manage_admin/interview_questionnaires';
        $function_name      = 'add_questionnaire';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('title', 'Title', 'required|xss_clean|trim');
        $this->form_validation->set_rules('short_description', 'Short Description', 'xss_clean|trim');
        $this->form_validation->set_rules('status', 'Status', 'xss_clean|trim');

        if ($this->form_validation->run() == false) {
                $this->data['title'] = 'Interview Questionnaire';
                $this->data['subtitle'] = 'Add New Interview Questionnaire';
                $this->data['submit_btn_text'] = 'Save Questionnaire';
                $this->render('manage_admin/interview_questionnaires/add_questionnaire');
        } else {
            $title = $this->input->post('title');
            $short_description = $this->input->post('short_description');
            $status = $this->input->post('status');
            $data_to_insert = array();
            $data_to_insert['title'] = $title;
            $data_to_insert['short_description'] = $short_description;
            $data_to_insert['status'] = $status;
            $data_to_insert['created_date'] = date('Y-m-d H:i:s');
            $this->interview_questionnaires_model->insert_questionnaire($data_to_insert);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Interview Questionnaire Added!');
            redirect('manage_admin/interview_questionnaires/', 'refresh');
        }
    }

    public function edit_questionnaire($questionnaire_sid = null) {
        if ($questionnaire_sid != null) {
            $redirect_url       = 'manage_admin/interview_questionnaires';
            $function_name      = 'add_questionnaire';
            $admin_id = $this->ion_auth->user()->row()->id;
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
            $this->form_validation->set_rules('title', 'Title', 'required|xss_clean|trim');
            $this->form_validation->set_rules('short_description', 'Short Description', 'xss_clean|trim');
            $this->form_validation->set_rules('status', 'Status', 'xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $this->data['title'] = 'Interview Questionnaire';
                $this->data['subtitle'] = 'Edit Interview Questionnaire';
                $this->data['submit_btn_text'] = 'Update Questionnaire';
                $questionnaire = $this->interview_questionnaires_model->get_questionnaire($questionnaire_sid);
                $this->data['questionnaire'] = $questionnaire;
                $this->render('manage_admin/interview_questionnaires/add_questionnaire');
            } else {
                $title = $this->input->post('title');
                $short_description = $this->input->post('short_description');
                $status = $this->input->post('status');
                $data_to_insert = array();
                $data_to_insert['title'] = $title;
                $data_to_insert['short_description'] = $short_description;
                $data_to_insert['status'] = $status;
                $data_to_insert['modified_date'] = date('Y-m-d H:i:s');
                $data_to_insert['modified_by'] = $admin_id;
                $this->interview_questionnaires_model->update_questionnaire($questionnaire_sid, $data_to_insert);
                $this->session->set_flashdata('message', '<strong>Success:</strong> Interview Questionnaire Updated!');
                redirect('manage_admin/interview_questionnaires/', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Questionnaire Not Found!');
            redirect('manage_admin/interview_questionnaires');
        }
    }

    public function manage_questionnaire($questionnaire_sid = null) {
        if ($questionnaire_sid != null) {
            $redirect_url       = 'manage_admin/interview_questionnaires';
            $function_name      = 'manage_questionnaire';
            $admin_id = $this->ion_auth->user()->row()->id;
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
            if ($this->form_validation->run() == false) {
                $this->data['title'] = 'Interview Questionnaire';
                $this->data['subtitle'] = 'Manage Interview Questionnaire';
                $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
                $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid);
                $this->data['questionnaire'] = $questionnaire;
                $this->render('manage_admin/interview_questionnaires/manage_questionnaire');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'delete_question':
                        $question_sid = $this->input->post('question_sid');
                        $this->interview_questionnaires_model->delete_questionnaire_section_question($question_sid);
                        break;
                    case 'delete_section':
                        $section_sid = $this->input->post('section_sid');
                        $this->interview_questionnaires_model->delete_questionnaire_section($section_sid);
                        break;
                }
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Questionnaire Not Found!');
            redirect('manage_admin/interview_questionnaires');
        }
    }

    public function manage_default_questions() {
        $redirect_url       = 'manage_admin/interview_questionnaires';
        $function_name      = 'manage_default_questions';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

        if($this->form_validation->run() == false){
            $this->data['title'] = 'Interview Questionnaire';
            $this->data['subtitle'] = 'Manage Default Questions';
            $this->data['submit_btn_text'] = 'Update Questionnaire';
            $default_questions = $this->interview_questionnaires_model->get_default_questions_categorized();
            $this->data['default_questions'] = $default_questions;
            $this->render('manage_admin/interview_questionnaires/manage_default_questions');
        } else {
            $perform_action = $this->input->post('perform_action');
            switch($perform_action){
                case 'delete_default_question':
                    $question_sid = $this->input->post('question_sid');
                    $this->interview_questionnaires_model->delete_default_question($question_sid);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Default Question Successfully Deleted.');
                    redirect('manage_admin/interview_questionnaires/manage_default_questions', 'refresh');
                    break;
            }
        }
    }

    public function add_default_question() {
        $redirect_url       = 'manage_admin/interview_questionnaires';
        $function_name      = 'manage_default_questions';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('question_text', 'Question Text', 'required|xss_clean|trim');
        $this->form_validation->set_rules('question_category', 'Question Text', 'required|xss_clean|trim');

        if($this->form_validation->run() == false){
            $this->data['title'] = 'Interview Questionnaire';
            $this->data['subtitle'] = 'Add Default Questions';
            $this->data['submit_btn_text'] = 'Add Question';
            $this->render('manage_admin/interview_questionnaires/add_default_question');
        } else {
            $question_text = $this->input->post('question_text');
            $question_category = $this->input->post('question_category');
            $this->interview_questionnaires_model->insert_default_question($question_text, $question_category);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Default Question Successfully Added.');
            redirect('manage_admin/interview_questionnaires/manage_default_questions', 'refresh');
        }        
    }

    public function edit_default_question($question_sid = null){
        if($question_sid != null) {
            $redirect_url       = 'manage_admin/interview_questionnaires';
            $function_name      = 'manage_default_questions';
            $admin_id = $this->ion_auth->user()->row()->id;
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
            $this->form_validation->set_rules('question_text', 'Question Text', 'required|xss_clean|trim');
            $this->form_validation->set_rules('question_sid', 'question_sid', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                    $this->data['title'] = 'Interview Questionnaire';
                    $this->data['subtitle'] = 'Edit Default Questions';
                    $this->data['submit_btn_text'] = 'Update Question';
                    $question = $this->interview_questionnaires_model->get_default_question($question_sid);
                    $this->data['default_question'] = $question;
                    $this->render('manage_admin/interview_questionnaires/add_default_question');
            } else {
                $question_sid = $this->input->post('question_sid');
                $question_text = $this->input->post('question_text');
                $question_category = $this->input->post('question_category');
                $this->interview_questionnaires_model->update_default_question($question_sid, $question_text, $question_category);
                $this->session->set_flashdata('message', '<strong>Success:</strong> Default Question Successfully Updated.');
                redirect('manage_admin/interview_questionnaires/manage_default_questions', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error: </strong> Question Not Found.');
            redirect('manage_admin/interview_questionnaires/manage_default_questions', 'refresh');
        }
    }

    public function ajax_responder(){
        $admin_id = $this->session->userdata('user_id');
        $perform_action = $this->input->post('perform_action');
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

        switch($perform_action){
            case 'add_questionnaire_section':
                break;
            case 'insert_questionnaire_section':
                $this->form_validation->set_rules('title', 'Title', 'required|xss_clean|trim');
                $this->form_validation->set_rules('short_description', 'Short Description', 'xss_clean|trim');
                $this->form_validation->set_rules('status', 'Status', 'xss_clean|trim');
                break;
            case 'update_questionnaire_section':
                $this->form_validation->set_rules('questionnaire_section_sid', 'questionnaire_section_sid', 'required|xss_clean|trim');
                $this->form_validation->set_rules('questionnaire_sid', 'questionnaire_sid', 'required|xss_clean|trim');
                $this->form_validation->set_rules('title', 'Title', 'required|xss_clean|trim');
                $this->form_validation->set_rules('short_description', 'Short Description', 'xss_clean|trim');
                $this->form_validation->set_rules('status', 'Status', 'xss_clean|trim');
                break;
            case 'get_questionnaire_section_question_form':
                break;
            case 'insert_questionnaire_section_question':
                //$this->form_validation->set_rules('question_for', 'Question For', 'required|xss_clean|trim');
                $this->form_validation->set_rules('question_text', 'Question Text', 'required|xss_clean|trim');
                $this->form_validation->set_rules('answer_required', 'Answer Required', 'required|xss_clean|trim');
                $answer_required = $this->input->post('answer_required');

                if ($answer_required == 1) {
                    $this->form_validation->set_rules('answer_type', 'Answer Type', 'required|xss_clean|trim');
                }
                break;
            case 'update_questionnaire_section_question':
                //$this->form_validation->set_rules('question_for', 'Question For', 'required|xss_clean|trim');
                $this->form_validation->set_rules('question_text', 'Question Text', 'required|xss_clean|trim');
                $this->form_validation->set_rules('answer_required', 'Answer Required', 'required|xss_clean|trim');
                $answer_required = $this->input->post('answer_required');

                if ($answer_required == 1) {
                    $this->form_validation->set_rules('answer_type', 'Answer Type', 'required|xss_clean|trim');
                }
                break;
            case 'get_default_question_form':
                break;
            case 'add_default_questions':
                $this->form_validation->set_rules('questionnaire_sid', 'questionnaire_sid', 'required|xss_clean|trim');
                $this->form_validation->set_rules('questionnaire_section_sid', 'questionnaire_section_sid', 'required|xss_clean|trim');
                $this->form_validation->set_rules('questions[]', 'questions', 'required|xss_clean|trim');
                break;
        }

        if($this->form_validation->run() == false){
            // do nothing
        } else {
            $perform_action = $this->input->post('perform_action');
            switch($perform_action){
                case 'add_questionnaire_section':
                    $questionnaire_sid = $this->input->post('questionnaire_sid');
                    $questionnaire_section_sid = $this->input->post('section_sid');
                    $my_data = array();
                    $questionnaire = $this->interview_questionnaires_model->get_questionnaire($questionnaire_sid);
                    $my_data['questionnaire'] = $questionnaire;

                    if(intval($questionnaire_section_sid) > 0){
                        $questionnaire_section = $this->interview_questionnaires_model->get_questionnaire_section($questionnaire_section_sid);
                        $my_data['questionnaire_section'] = $questionnaire_section;
                    } else {
                        $my_data['questionnaire_section'] = array('hellow');
                    }

                    $my_data['submit_btn_text'] = 'Save Questionnaire Section';
                    $view_html = $this->load->view('manage_admin/interview_questionnaires/add_questionnaire_section', $my_data, true);
                    $my_return = array();
                    $my_return['html'] = $view_html;
                    $my_return['title'] = 'Add Interview Questionnaire';
                    header('Content-Type: application/json');
                    echo json_encode($my_return);
                    break;
                case 'insert_questionnaire_section':
                    $questionnaire_sid = $this->input->post('questionnaire_sid');
                    $title = $this->input->post('title');
                    $sort_order = $this->input->post('sort_order');
                    $short_description = $this->input->post('short_description');
                    $status = $this->input->post('status');
                    $data_to_insert = array();
                    $data_to_insert['questionnaire_sid'] = $questionnaire_sid;
                    $data_to_insert['title'] = $title;
                    $data_to_insert['sort_order'] = $sort_order;
                    $data_to_insert['short_description'] = $short_description;
                    $data_to_insert['status'] = $status;
                    $data_to_insert['created_date'] = date('Y-m-d H:i:s');
                    $this->interview_questionnaires_model->insert_questionnaire_section($data_to_insert);
                    $return_data = array();
                    header('Content-Type: application/json');
                    $return_data['status'] = 'success';
                    echo json_encode($return_data);
                    break;
                case 'update_questionnaire_section':
                    $questionnaire_sid = $this->input->post('questionnaire_sid');
                    $questionnaire_section_sid = $this->input->post('questionnaire_section_sid');
                    $title = $this->input->post('title');
                    $sort_order = $this->input->post('sort_order');
                    $short_description = $this->input->post('short_description');
                    $status = $this->input->post('status');
                    $data_to_insert = array();
                    $data_to_insert['questionnaire_sid'] = $questionnaire_sid;
                    $data_to_insert['title'] = $title;
                    $data_to_insert['sort_order'] = $sort_order;
                    $data_to_insert['short_description'] = $short_description;
                    $data_to_insert['status'] = $status;
                    $data_to_insert['modified_date'] = date('Y-m-d H:i:s');
                    $data_to_insert['modified_by'] = $admin_id;
                    $this->interview_questionnaires_model->update_questionnaire_section($questionnaire_section_sid, $data_to_insert);
                    $return_data = array();
                    header('Content-Type: application/json');
                    $return_data['status'] = 'success';
                    echo json_encode($return_data);
                    break;
                case 'get_questionnaire_section_question_form':
                    $questionnaire_sid = $this->input->post('questionnaire_sid');
                    $questionnaire_section_sid = $this->input->post('section_sid');
                    $questionnaire_section_question_sid = $this->input->post('question_sid');
                    $view_data = array();
                    
                    if($questionnaire_sid > 0) {
                        $questionnaire = $this->interview_questionnaires_model->get_questionnaire($questionnaire_sid);

                        if (!empty($questionnaire)) {
                            $view_data['questionnaire'] = $questionnaire;

                            if($questionnaire_section_sid > 0) {
                                $questionnaire_section = $this->interview_questionnaires_model->get_questionnaire_section($questionnaire_section_sid);

                                if (!empty($questionnaire_section)) {
                                    $view_data['questionnaire_section'] = $questionnaire_section;

                                    if($questionnaire_section_question_sid > 0){
                                        $question = $this->interview_questionnaires_model->get_questionnaire_section_question($questionnaire_section_question_sid);
                                        $view_data['question'] = $question;
                                    }
                                } else {
                                    $view_data['questionnaire_section'] = array();
                                }
                            }
                        } else {
                            $view_data['questionnaire'] = array();
                        }
                    }

                    $view_data['submit_btn_text'] = 'Save Questionnaire Section Question';
                    $view_html = $this->load->view('manage_admin/interview_questionnaires/add_questionnaire_section_question', $view_data, true);
                    $my_return = array();
                    $my_return['html'] = $view_html;
                    $my_return['title'] = 'Add Question';
                    header('Content-Type: application/json');
                    echo json_encode($my_return);
                    break;
                case 'insert_questionnaire_section_question':
                    //$question_for = $this->input->post('question_for');
                    $question_text = $this->input->post('question_text');
                    $sort_order = $this->input->post('sort_order');
                    $answer_required = $this->input->post('answer_required');
                    $answer_type = $this->input->post('answer_type');
                    $answer_options = $this->input->post('answer_options');
                    $answer_options = implode(',', $answer_options);
                    $questionnaire_sid = $this->input->post('questionnaire_sid');
                    $questionnaire_section_sid = $this->input->post('questionnaire_section_sid');
                    $data_to_insert = array();
                    //$data_to_insert['question_for'] = $question_for;
                    $data_to_insert['question_text'] = $question_text;
                    $data_to_insert['sort_order'] = $sort_order;
                    $data_to_insert['answer_required'] = $answer_required;
                    $data_to_insert['answer_type'] = $answer_type;
                    $data_to_insert['answer_options'] = $answer_options;
                    $data_to_insert['created_date'] = date('Y-m-d H:i:s');
                    $data_to_insert['questionnaire_sid'] = $questionnaire_sid;
                    $data_to_insert['questionnaire_section_sid'] = $questionnaire_section_sid;
                    $data_to_insert['status'] = 'active';
                    $this->interview_questionnaires_model->insert_questionnaire_section_question($data_to_insert);
                    $return_data = array();
                    header('Content-Type: application/json');
                    $return_data['status'] = 'success';
                    echo json_encode($return_data);
                    break;
                case 'update_questionnaire_section_question':
                    //$question_for = $this->input->post('question_for');
                    $question_text = $this->input->post('question_text');
                    $sort_order = $this->input->post('sort_order');
                    $answer_required = $this->input->post('answer_required');
                    $answer_type = $this->input->post('answer_type');
                    $answer_options = $this->input->post('answer_options');
                    $answer_options = implode(',', $answer_options);
                    $questionnaire_sid = $this->input->post('questionnaire_sid');
                    $questionnaire_section_sid = $this->input->post('questionnaire_section_sid');
                    $questionnaire_section_question_sid = $this->input->post('questionnaire_section_question_sid');
                    $data_to_update = array();
                    //$data_to_update['question_for'] = $question_for;
                    $data_to_update['question_text'] = $question_text;
                    $data_to_update['sort_order'] = $sort_order;
                    $data_to_update['answer_required'] = $answer_required;
                    $data_to_update['answer_type'] = $answer_type;
                    $data_to_update['answer_options'] = $answer_options;
                    $data_to_update['questionnaire_sid'] = $questionnaire_sid;
                    $data_to_update['questionnaire_section_sid'] = $questionnaire_section_sid;
                    $data_to_update['modified_by'] = $admin_id;
                    $data_to_update['modified_date'] = date('Y-m-d H:i:s');
                    $data_to_insert['status'] = 'active';
                    $this->interview_questionnaires_model->update_questionnaire_section_question($questionnaire_section_question_sid, $data_to_update);
                    $return_data = array();
                    header('Content-Type: application/json');
                    $return_data['status'] = 'success';
                    echo json_encode($return_data);
                    break;
                case 'get_default_question_form':
                    $questionnaire_sid = $this->input->post('questionnaire_sid');
                    $questionnaire_section_sid = $this->input->post('section_sid');
                    $view_data = array();
                    
                    if($questionnaire_sid > 0) {
                        $questionnaire = $this->interview_questionnaires_model->get_questionnaire($questionnaire_sid);

                        if (!empty($questionnaire)) {
                            $view_data['questionnaire'] = $questionnaire;

                            if($questionnaire_section_sid > 0) {
                                $questionnaire_section = $this->interview_questionnaires_model->get_questionnaire_section($questionnaire_section_sid);

                                if (!empty($questionnaire_section)) {
                                    $view_data['questionnaire_section'] = $questionnaire_section;
                                } else {
                                    $view_data['questionnaire_section'] = array();
                                }
                            }
                        } else {
                            $view_data['questionnaire'] = array();
                        }
                    }

                    $view_data['submit_btn_text'] = 'Add Selected Question(s)';
                    $questions = $this->interview_questionnaires_model->get_default_questions_categorized('active');
                    $view_data['questions'] = $questions;
                    $view_html = $this->load->view('manage_admin/interview_questionnaires/add_default_question_form', $view_data, true);
                    $my_return = array();
                    $my_return['html'] = $view_html;
                    $my_return['title'] = 'Add Question';
                    header('Content-Type: application/json');
                    echo json_encode($my_return);
                    break;
                case 'add_default_questions':
                    $questions = $this->input->post('questions');
                    $questionnaire_sid = $this->input->post('questionnaire_sid');
                    $questionnaire_section_sid = $this->input->post('questionnaire_section_sid');
                    $this->interview_questionnaires_model->clone_default_questions($questionnaire_sid, $questionnaire_section_sid, $questions, $company_sid = 0);
                    header('Content-Type: application/json');
                    $return_data['status'] = 'success';
                    echo json_encode($return_data);
                    break;
            }
            exit;
        }
    }
}