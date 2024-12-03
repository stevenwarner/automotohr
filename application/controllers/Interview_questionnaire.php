<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Interview_questionnaire extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/interview_questionnaires_model');
        $this->form_validation->set_error_delimiters('<span class="error_message text-left"><i class="fa fa-exclamation-circle"></i>', '</span>');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'interview_questionnaire'); // Param2: Redirect URL, Param3: Function Name  
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $data['title'] = 'Interview Questionnaire';
                $questionnaires_default = $this->interview_questionnaires_model->get_questionnaires();
                $data['questionnaires_default'] = $questionnaires_default;
                $questionnaires_company_specific = $this->interview_questionnaires_model->get_questionnaires($company_sid);
                $data['questionnaires_company_specific'] = $questionnaires_company_specific;
                $this->load->view('main/header', $data);
                $this->load->view('interview_questionnaires/index');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'delete_questionnaire':
                        $questionnaire_sid = $this->input->post('questionnaire_sid');
                        $this->interview_questionnaires_model->delete_questionnaire($questionnaire_sid, $company_sid);

                        $this->session->set_flashdata('message', '<strong>Success :</strong> Interview Questionnaire Deleted!');
                        redirect('interview_questionnaire', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_questionnaire() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'interview_questionnaire', 'add_edit_delete_questionnaire');
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $this->form_validation->set_message('check_unique_title_by_company', 'Questionnaire title already exists, Please use Unique name!');
            $this->form_validation->set_rules('title', 'Title', 'required|xss_clean|trim|callback_check_unique_title_by_company');
            $this->form_validation->set_rules('short_description', 'Short Description', 'xss_clean|trim');
            $this->form_validation->set_rules('status', 'Status', 'xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $data['title'] = 'Interview Questionnaire';
                $data['subtitle'] = 'Add New Interview Questionnaire';
                $data['submit_btn_text'] = 'Save Questionnaire';
                $this->load->view('main/header', $data);
                $this->load->view('interview_questionnaires/add_questionnaire');
                $this->load->view('main/footer');
            } else {
                $title = $this->input->post('title');
                $short_description = $this->input->post('short_description');
                $status = 'active';
                $data_to_insert = array();
                $data_to_insert['title'] = $title;
                $data_to_insert['short_description'] = $short_description;
                $data_to_insert['status'] = $status;
                $data_to_insert['created_date'] = date('Y-m-d H:i:s');
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['employer_sid'] = $employer_sid;
                $this->interview_questionnaires_model->insert_questionnaire($data_to_insert);
                $this->session->set_flashdata('message', '<strong>Success:</strong> Interview Questionnaire Added!');
                redirect('interview_questionnaire', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function check_unique_title_by_company($title) {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->interview_questionnaires_model->check_unique_title($title, $company_sid);
        
        if ($result == 'not_allowed') {
            $this->session->set_flashdata('message', 'Error: Questionnaire title already exists, Please use Unique name!');
            return false;
        } else {
            return true;
        }
    }

    public function edit_questionnaire($questionnaire_sid = null) {
        if ($this->session->userdata('logged_in')) {
            if ($questionnaire_sid != null) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'interview_questionnaire', 'add_edit_delete_questionnaire');
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $company_sid = $data["session"]["company_detail"]["sid"];
                $data['title'] = 'Interview Questionnaire';
                $data['subtitle'] = 'Edit Interview Questionnaire';
                $data['submit_btn_text'] = 'Update Questionnaire';
                $questionnaire = $this->interview_questionnaires_model->get_questionnaire($questionnaire_sid, $company_sid);

                if (!empty($questionnaire)) {
                    $title = $this->input->post('title');

                    $data['questionnaire'] = $questionnaire;
                    $this->form_validation->set_rules('short_description', 'Short Description', 'xss_clean|trim');
                    if ($title == $questionnaire['title']) {
                        $this->form_validation->set_rules('title', 'Title', 'required|xss_clean|trim');
                    } else {
                        $this->form_validation->set_message('check_unique_title_by_company', 'Questionnaire title already exists, Please use Unique name!');
                        $this->form_validation->set_rules('title', 'Title', 'required|xss_clean|trim|callback_check_unique_title_by_company');
                    }

                    if ($this->form_validation->run() == false) {
                        $this->load->view('main/header', $data);
                        $this->load->view('interview_questionnaires/add_questionnaire');
                        $this->load->view('main/footer');
                    } else {
                        // $title = $this->input->post('title');
                        $short_description = $this->input->post('short_description');
                        $status = 'active';
                        $data_to_update = array();
                        $data_to_update['title'] = $title;
                        $data_to_update['short_description'] = $short_description;
                        $data_to_update['status'] = $status;
                        $data_to_update['modified_date'] = date('Y-m-d H:i:s');
                        $data_to_update['modified_by'] = $employer_sid;
                        $data_to_update['company_sid'] = $company_sid;
                        $data_to_update['employer_sid'] = $employer_sid;
                        $this->interview_questionnaires_model->update_questionnaire($questionnaire_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Interview Questionnaire Updated!');
                        redirect('interview_questionnaire', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Questionnaire Not Found!');
                    redirect('interview_questionnaire');
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Questionnaire Not Found!');
                redirect('interview_questionnaire');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function clone_questionnaire($questionnaire_sid = null) {
        if ($this->session->userdata('logged_in')) {
            if ($questionnaire_sid != null) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'interview_questionnaire', 'add_edit_delete_questionnaire');
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $company_sid = $data["session"]["company_detail"]["sid"];
                $data['title'] = 'Interview Questionnaire';
                $data['subtitle'] = 'Clone Interview Questionnaire';
                $data['submit_btn_text'] = 'Clone Questionnaire';
                $questionnaire = $this->interview_questionnaires_model->get_questionnaire($questionnaire_sid);

                if (!empty($questionnaire)) {
                    $title = $this->input->post('title');
                    $data['questionnaire'] = $questionnaire;
                    $this->form_validation->set_rules('short_description', 'Short Description', 'xss_clean|trim');
                    $this->form_validation->set_message('check_unique_title_by_company', 'Questionnaire title already exists, Please use Unique name!');
                    $this->form_validation->set_rules('title', 'Title', 'required|xss_clean|trim|callback_check_unique_title_by_company');

                    if ($this->form_validation->run() == false) {
                        $this->load->view('main/header', $data);
                        $this->load->view('interview_questionnaires/add_questionnaire');
                        $this->load->view('main/footer');
                    } else {
                        $title = $this->input->post('title');
                        $short_description = $this->input->post('short_description');
                        $this->interview_questionnaires_model->clone_questionnaire($title, $short_description, $questionnaire_sid, $company_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Interview Questionnaire Cloned!');
                        redirect('interview_questionnaire', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Questionnaire Not Found!');
                    redirect('interview_questionnaire');
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Questionnaire Not Found!');
                redirect('interview_questionnaire');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function manage_questionnaire($questionnaire_sid = 0) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'interview_questionnaire', 'manage_questionnaire');
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $data['title'] = 'Manage Interview Questionnaire';
                $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid, $company_sid);

                if (!empty($questionnaire)) {
                    $data['questionnaire'] = $questionnaire;
                    $data['is_manage'] = 1;
                    $data['is_preview'] = 0;
                    $data['is_already_scored'] = 0;
                    $this->load->view('main/header', $data);
                    $this->load->view('interview_questionnaires/manage_questionnaire');
                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error :</strong> Questionnaire Not Found.');
                    redirect('interview_questionnaire', "refresh");
                }
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
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_responder() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
            $perform_action = $this->input->post('perform_action');
            
            switch ($perform_action) {
                case 'clone_questionnaire':
                    $this->form_validation->set_rules('questionnaire_sid', 'questionnaire_sid', 'required|xss_clean|trim');
                    break;
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
                case 'preview_questionnaire':
                    $this->form_validation->set_rules('questionnaire_sid', 'questionnaire_sid', 'required|xss_clean|trim');
                    break;
                case 'preview_questionnaire_clone':
                    $this->form_validation->set_rules('questionnaire_sid', 'questionnaire_sid', 'required|xss_clean|trim');
                    break;
                case 'calculate_and_evaluate_score':
                    break;
                case 'validate_clone_title':
                    $this->form_validation->set_rules('questionnaire_sid', 'questionnaire_sid', 'required|xss_clean|trim');
                    break;
            }

            if ($this->form_validation->run() == false) {
                //do nothing
            } else {
                header('Content-Type: application/json');
                switch ($perform_action) {
                    case 'validate_clone_title':
                        $questionnaire_sid = $this->input->post('questionnaire_sid');
                        $title = $this->input->post('title');
                        $result = $this->interview_questionnaires_model->check_unique_title($title, $company_sid);
                        echo json_encode($result);
                        break;
                    case 'clone_questionnaire':
                        $questionnaire_sid = $this->input->post('questionnaire_sid');
                        $this->interview_questionnaires_model->clone_questionnaire($questionnaire_sid, $company_sid);
                        $my_return = array();
                        $my_return['status'] = 'success';
                        echo json_encode($my_return);
                        break;
                    case 'add_questionnaire_section':
                        $questionnaire_sid = $this->input->post('questionnaire_sid');
                        $questionnaire_section_sid = $this->input->post('section_sid');
                        $my_data = array();
                        $questionnaire = $this->interview_questionnaires_model->get_questionnaire($questionnaire_sid);
                        $my_data['questionnaire'] = $questionnaire;

                        if (intval($questionnaire_section_sid) > 0) {
                            $questionnaire_section = $this->interview_questionnaires_model->get_questionnaire_section($questionnaire_section_sid);
                            $my_data['questionnaire_section'] = $questionnaire_section;
                        } else {
                            $my_data['questionnaire_section'] = array('hellow');
                        }

                        $my_data['submit_btn_text'] = 'Save Section';
                        $view_html = $this->load->view('interview_questionnaires/add_questionnaire_section', $my_data, true);
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
                        $data_to_insert['modified_by'] = $employer_sid;
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

                        if ($questionnaire_sid > 0) {
                            $questionnaire = $this->interview_questionnaires_model->get_questionnaire($questionnaire_sid);
                            if (!empty($questionnaire)) {
                                $view_data['questionnaire'] = $questionnaire;
                                if ($questionnaire_section_sid > 0) {
                                    $questionnaire_section = $this->interview_questionnaires_model->get_questionnaire_section($questionnaire_section_sid);
                                    if (!empty($questionnaire_section)) {
                                        $view_data['questionnaire_section'] = $questionnaire_section;
                                        if ($questionnaire_section_question_sid > 0) {

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

                        $view_data['submit_btn_text'] = 'Save Question';
                        $view_html = $this->load->view('interview_questionnaires/add_questionnaire_section_question', $view_data, true);
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
                        $data_to_update['modified_by'] = $employer_sid;
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

                        if ($questionnaire_sid > 0) {
                            $questionnaire = $this->interview_questionnaires_model->get_questionnaire($questionnaire_sid);
                            if (!empty($questionnaire)) {
                                $view_data['questionnaire'] = $questionnaire;
                                if ($questionnaire_section_sid > 0) {
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
                        $view_html = $this->load->view('interview_questionnaires/add_default_question_form', $view_data, true);
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
                    case 'preview_questionnaire_clone':
                        $questionnaire_sid = $this->input->post('questionnaire_sid');
                        $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data_simple($questionnaire_sid);
                        echo json_encode($questionnaire);
                        break;
                    case 'preview_questionnaire':
                        $questionnaire_sid = $this->input->post('questionnaire_sid');
                        $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid);
                        $view_data = array();
                        $view_data['questionnaire'] = $questionnaire;
                        $view_data['is_already_scored'] = true;
                        $view_data['is_preview'] = 1;
                        $view_data['is_manage'] = 0;
                        $view_html = $this->load->view('interview_questionnaires/manage_questionnaire_partial', $view_data, true);
                        $view_html .= $this->load->view('interview_questionnaires/questionnaire_evaluation_partial', array(), true);
                        $my_return = array();
                        $my_return['html'] = $view_html;
                        $my_return['title'] = 'Preview Questionnaire';
                        header('Content-Type: application/json');
                        echo json_encode($my_return);
                        break;
                    case 'calculate_and_evaluate_score':
                        $questionnaire_sid = $this->input->post('questionnaire_sid');
                        $questionnaire_form = $this->input->post('questionnaire_form');
                        $evaluation_form = $this->input->post('evaluation_form');
                        $job_sid = $this->input->post('job_sid');
                        $questionnaire_form = json_decode($questionnaire_form, true);
                        $evaluation_form = json_decode($evaluation_form, true);

//                        print_r($questionnaire_form);
//                        print_r($evaluation_form);
//                        die();
                        $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid);
                        $keys_to_exclude = array();
                        $keys_to_exclude[] = 'cand_know_skill_abill_general';
                        $keys_to_exclude[] = 'cand_know_skill_abill_unique';
                        $keys_to_exclude[] = 'star_rating';
                        $candidate_keys = array();
                        $candidate_keys[] = 'comm_cand';
                        $candidate_keys[] = 'pros_decm_cand';
                        $candidate_keys[] = 'build_trst_cand';
                        $candidate_keys[] = 'conf_res_cand';
                        $candidate_keys[] = 'team_wrk_cand';
                        $candidate_keys[] = 'cs_orient_cand';
                        $candidate_keys[] = 'work_exp_cand';
                        $candidate_keys[] = 'edu_back_cand';
                        $candidate_keys[] = 'tech_qual_cand';
                        $candidate_keys[] = 'commitment_cand';
                        $job_relevancy_keys = array();
                        $job_relevancy_keys[] = 'comm_job';
                        $job_relevancy_keys[] = 'pros_decm_job';
                        $job_relevancy_keys[] = 'build_trst_job';
                        $job_relevancy_keys[] = 'conf_res_job';
                        $job_relevancy_keys[] = 'team_wrk_job';
                        $job_relevancy_keys[] = 'cs_orient_job';
                        $job_relevancy_keys[] = 'work_exp_job';
                        $job_relevancy_keys[] = 'edu_back_job';
                        $job_relevancy_keys[] = 'tech_qual_job';
                        $job_relevancy_keys[] = 'commitment_job';
                        $candidate_score = 0.0;
                        $job_relevancy_score = 0.0;

                        foreach ($evaluation_form as $key => $score_value) {
                            if (!in_array($key, $keys_to_exclude)) {
                                if (in_array($key, $candidate_keys)) {
                                    //Add to Candidate Score
                                    $candidate_score = $candidate_score + $score_value;
                                } elseif (in_array($key, $job_relevancy_keys)) {
                                    //Add to job Relevancy Score
                                    $job_relevancy_score = $job_relevancy_score + $score_value;
                                }
                            }
                        }

                        $candidate_sid = $evaluation_form['candidate_sid'];
                        $candidate_type = $evaluation_form['candidate_type'];

                        $desired_job_title = '';
                        if($job_sid == 0 && $candidate_type == 'applicant'){
                            $candidate_info = $this->interview_questionnaires_model->get_applicant_information($company_sid, $candidate_sid);
                            $desired_job_title = $candidate_info['desired_job_title'];
                            if($desired_job_title == null){
                                $desired_job_title = '';
                            }
                        }

                        $candidate_overall_score = $evaluation_form['overall_assessment_candidate'];
//                        $job_relevancy_overall_score = $evaluation_form['overall_assessment_job'];
                        $questionnaire_form = serialize($questionnaire_form);
                        $evaluation_form = serialize($evaluation_form);
                        $questionnaire = serialize($questionnaire);
//                        $star_rating = round(( $candidate_overall_score + $job_relevancy_overall_score ) / 4, 2);
                        $star_rating = round($candidate_overall_score / 2,2);
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['employer_sid'] = $employer_sid;
                        $data_to_insert['candidate_sid'] = $candidate_sid;
                        $data_to_insert['candidate_type'] = $candidate_type;
                        $data_to_insert['star_rating'] = $star_rating;
                        $data_to_insert['questionnaire_form'] = $questionnaire_form;
                        $data_to_insert['evaluation_form'] = $evaluation_form;
                        $data_to_insert['questionnaire'] = $questionnaire;
                        $data_to_insert['candidate_score'] = $candidate_score;
                        $data_to_insert['job_relevancy_score'] = $job_relevancy_score;
                        $data_to_insert['candidate_overall_score'] = $candidate_overall_score;
//                        $data_to_insert['job_relevancy_overall_score'] = $job_relevancy_overall_score; because relevancy column is commented so no need to store relevancy score
                        $data_to_insert['created_date'] = date('Y-m-d H:i:s');
                        $data_to_insert['job_sid'] = $job_sid;
                        $data_to_insert['questionnaire_sid'] = $questionnaire_sid;
                        $data_to_insert['desired_job_title'] = $desired_job_title;


                        $this->interview_questionnaires_model->insert_questionnaire_score_data($data_to_insert, $company_sid, $employer_sid, $candidate_type, $candidate_sid, $questionnaire_sid);

                        header('Content-Type: application/json');
                        $return_data['status'] = 'success';

                        echo json_encode($return_data);
                        break;
                }
                exit;
            }
        } else {
            exit;
        }
    }

    public function launch($user_type = '', $user_sid = 0, $job_sid = 0, $employer_sid = 0) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            if($employer_sid == 0) {
                $employer_sid = $data["session"]["employer_detail"]["sid"];
            }

            $company_sid = $data["session"]["company_detail"]["sid"];
            $logged_in_employer_sid = $data["session"]["employer_detail"]["sid"];

            $candidate_info = array();

            switch ($user_type) {
                case 'employee':
                    //todo future advanced implementation
                    redirect('employee_management', 'refresh');
                    break;
                case 'applicant':
                    if ($user_sid > 0) {
                        $candidate_info = $this->interview_questionnaires_model->get_applicant_information($company_sid, $user_sid);
                        
                        if (!empty($candidate_info)) {
                            check_access_permissions($security_details, 'applicant_profile/' . $user_sid, 'applicant_emergency_contacts'); // Param2: Redirect URL, Param3: Function Name
                            $data = applicant_right_nav($user_sid);
                            $data['title'] = 'Interview Questionnaire';
                            $left_navigation = 'manage_employer/profile_right_menu_applicant';
                            $reload_location = 'emergency_contacts/applicant/' . $user_sid;
                            $data["return_title_heading"] = "Applicant Profile";
                            $data["return_title_heading_link"] = base_url() . 'applicant_profile/' . $user_sid;
                            $data['left_navigation'] = $left_navigation;
                            $data["employer"] = $candidate_info;
                            $data['candidate_type'] = $user_type;
                            $data['candidate_sid'] = $user_sid;
                            $job_info = $this->interview_questionnaires_model->get_job_information($company_sid, $job_sid);
                            if (!empty($job_info)) {
                                $data['job_info'] = $job_info;
                                $questionnaire_sid = $job_info['interview_questionnaire_sid'];
                                $questionnaire_score_data = $this->interview_questionnaires_model->get_questionnaire_score_data($company_sid, $employer_sid, $user_sid, $user_type, $job_sid, 0);
                                //Handle Previous Scores - Start
                                $candidate_scores = $this->interview_questionnaires_model->get_questionnaire_score_data_candidate_specific($company_sid, $user_sid, $user_type, $job_sid, 0);
                                $candidate_total_score = 0;
                                $job_relevancy_total_score = 0;
                                $overall_score = 0;
                                $total_star_rating = 0;
                                $candidate_average_score = 0;
                                $job_relevancy_average_score = 0;
                                $overall_average_score = 0;
                                $average_star_rating = 0;

                                if (!empty($candidate_scores)) {
                                    foreach ($candidate_scores as $key => $score) {
                                        $already_scored_by = $score['employer_sid'];

                                        if ($logged_in_employer_sid == $already_scored_by) {
                                            //unset($candidate_scores[$key]);
                                            $candidate_scores[$key]['scored_by']['first_name'] = 'Your';
                                            $candidate_scores[$key]['scored_by']['last_name'] = 'Score';
                                        }

                                        $candidate_total_score += $score['candidate_score'];
                                        $job_relevancy_total_score += $score['job_relevancy_score'];
                                        $overall_score += $score['candidate_overall_score'] + $score['job_relevancy_overall_score'];
                                        $total_star_rating += $score['star_rating'];
                                    }
                                }

                                $score_count = count($candidate_scores);

                                if ($score_count > 0) {
                                    $candidate_average_score = $candidate_total_score / $score_count;
                                    $job_relevancy_average_score = $job_relevancy_total_score / $score_count;
                                    $overall_average_score = (($overall_score * 10) / 2) / $score_count;
                                    $average_star_rating = $total_star_rating / $score_count;
                                }

                                $data['candidate_average_score'] = $candidate_average_score;
                                $data['job_relevancy_average_score'] = $job_relevancy_average_score;
                                $data['overall_average_score'] = $overall_average_score;
                                $data['average_star_rating'] = $average_star_rating;
                                $data['candidate_scores'] = $candidate_scores;
                                //Handle Previous Scores - End
                                $questionnaire = array();

                                if (!empty($questionnaire_score_data)) {
                                    $data['is_already_scored'] = 1;
                                    $data['questionnaire_score'] = $questionnaire_score_data;
                                    $questionnaire = unserialize($questionnaire_score_data['questionnaire']);
                                    $data['questionnaire_form'] = unserialize($questionnaire_score_data['questionnaire_form']);
                                    $data['evaluation_form'] = unserialize($questionnaire_score_data['evaluation_form']);
                                    $data['interview_conducted_by'] = $questionnaire_score_data['interview_conducted_by'];
                                } else {
                                    $data['is_already_scored'] = 0;
                                    $data['questionnaire_score'] = array();
                                    $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid);                                    
                                    $data['questionnaire_form'] = array();
                                    $data['evaluation_form'] = array();
                                }

                                $data['is_manage'] = 0;
                                $data['is_preview'] = 0;
                                $data['employer_sid'] = $employer_sid;
                                $data['logged_in_employer_sid'] = $logged_in_employer_sid;
                                $data['job_sid'] = $job_sid;
                                if (!empty($questionnaire)) {
                                    $data['questionnaire'] = $questionnaire;
                                    $data['is_print'] = 0;
                                    $data['employer_sid'] = $employer_sid;
                                    $data['applicant_sid'] = $user_sid;
                                    $data['questionnaire_sid'] = $questionnaire_sid;

                                    $this->load->view('main/header', $data);
                                    $this->load->view('interview_questionnaires/launch');
                                    $this->load->view('main/footer');
                                } else {
                                    $this->session->set_flashdata('message', '<strong>Error :</strong> Cannot find a questionnaire linked to the Job, Please assign a questionnaire by editing the job.');
                                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                                }
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error :</strong> Please assign a job to this candidate in order to process Interview Questionnaire.');
                                redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                            }
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error :</strong> Applicant Does not Exist');
                            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                        }
                    }
                    break;
                case 'print_questionnaire':
                    if ($user_sid > 0) {                     
                        $candidate_info = $this->interview_questionnaires_model->get_applicant_information($company_sid, $user_sid);
                        $left_navigation = 'manage_employer/profile_right_menu_applicant';
                        $data['left_navigation'] = $left_navigation;
                        $data["employer"] = $candidate_info;
                        $data['candidate_sid'] = $user_sid;
                        
                        if (!empty($candidate_info)) {
                            $job_info = $this->interview_questionnaires_model->get_job_information($company_sid, $job_sid);
                            $data['job_info'] = $job_info;
                            $questionnaire_sid = $job_info['interview_questionnaire_sid'];
                            $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid, $company_sid);
                            
                            if (!empty($questionnaire)) {
                                    $data['questionnaire'] = $questionnaire;
                                    $data['title'] = 'Print Interview Questionnaire';                                    
                                    //$this->load->view('main/header', $data);
                                    $this->load->view('interview_questionnaires/print_form', $data);
                                    //$this->load->view('main/footer');
                                } else {
                                    $this->session->set_flashdata('message', '<strong>Error :</strong> Cannot find a questionnaire linked to the Job, Please assign a questionnaire by editing the job.');
                                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                                }
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error :</strong> Applicant Does not Exist');
                            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                        }
                    } else {
                            $this->session->set_flashdata('message', '<strong>Error :</strong> Applicant Does not Exist');
                            redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                    }
                    break;
                
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function launch_interview($applicant_sid, $questionnaire_sid,  $employer_sid = 0){
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'applicant_profile/' . $applicant_sid, 'applicant_emergency_contacts');

            if($employer_sid == 0) {
                $employer_sid = $data["session"]["employer_detail"]["sid"];
            }

            $company_sid = $data["session"]["company_detail"]["sid"];
            $logged_in_employer_sid = $data["session"]["employer_detail"]["sid"];

            $candidate_info = $this->interview_questionnaires_model->get_applicant_information($company_sid, $applicant_sid);

            $data = applicant_right_nav($applicant_sid);
            $data['title'] = 'Interview Questionnaire';
            $left_navigation = 'manage_employer/profile_right_menu_applicant';
            $reload_location = 'emergency_contacts/applicant/' . $applicant_sid;

            $data["return_title_heading"] = "Applicant Profile";
            $data["return_title_heading_link"] = base_url() . 'applicant_profile/' . $applicant_sid;
            $data['left_navigation'] = $left_navigation;
            $data["employer"] = $candidate_info;
            $data['candidate_type'] = 'applicant';
            $data['candidate_sid'] = $applicant_sid;


            $job_sid = 0;

            $questionnaire_score_data = $this->interview_questionnaires_model->get_questionnaire_score_data($company_sid, $employer_sid, $applicant_sid, 'applicant', $job_sid, $questionnaire_sid);

            //Handle Previous Scores - Start
            $candidate_scores = $this->interview_questionnaires_model->get_questionnaire_score_data_candidate_specific($company_sid, $applicant_sid, 'applicant', $job_sid, $questionnaire_sid);
            $candidate_total_score = 0;
            $job_relevancy_total_score = 0;
            $overall_score = 0;
            $total_star_rating = 0;
            $candidate_average_score = 0;
            $job_relevancy_average_score = 0;
            $overall_average_score = 0;
            $average_star_rating = 0;

            if (!empty($candidate_scores)) {
                foreach ($candidate_scores as $key => $score) {
                    $already_scored_by = $score['employer_sid'];

                    if ($logged_in_employer_sid == $already_scored_by) {
                        //unset($candidate_scores[$key]);
                        $candidate_scores[$key]['scored_by']['first_name'] = 'Your';
                        $candidate_scores[$key]['scored_by']['last_name'] = 'Score';
                    }

                    $candidate_total_score += $score['candidate_score'];
                    $job_relevancy_total_score += $score['job_relevancy_score'];
                    $overall_score += $score['candidate_overall_score'];
//                    $overall_score += $score['candidate_overall_score'] + $score['job_relevancy_overall_score'];
                    $total_star_rating += $score['star_rating'];
                }
            }

            $score_count = count($candidate_scores);

            if ($score_count > 0) {
                $candidate_average_score = $candidate_total_score / $score_count;
                $job_relevancy_average_score = $job_relevancy_total_score / $score_count;
                $overall_average_score = ($overall_score * 10) / $score_count;
//                $overall_average_score = (($overall_score * 10) / 2) / $score_count;
                $average_star_rating = $total_star_rating / $score_count;
            }

            $data['candidate_average_score'] = $candidate_average_score;
            $data['job_relevancy_average_score'] = $job_relevancy_average_score;
            $data['overall_average_score'] = $overall_average_score;
            $data['average_star_rating'] = $average_star_rating;
            $data['candidate_scores'] = $candidate_scores;
            //Handle Previous Scores - End


            if (!empty($questionnaire_score_data)) {
                $data['is_already_scored'] = 1;
                $data['questionnaire_score'] = $questionnaire_score_data;
                $questionnaire = unserialize($questionnaire_score_data['questionnaire']);
                $data['questionnaire_form'] = unserialize($questionnaire_score_data['questionnaire_form']);
                $data['evaluation_form'] = unserialize($questionnaire_score_data['evaluation_form']);
                $data['interview_conducted_by'] = $questionnaire_score_data['interview_conducted_by'];
            } else {
                $data['is_already_scored'] = 0;
                $data['questionnaire_score'] = array();
                $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid);
                $data['questionnaire_form'] = array();
                $data['evaluation_form'] = array();
            }

            $data['is_manage'] = 0;
            $data['is_preview'] = 0;
            $data['is_print'] = 0;
            $data['employer_sid'] = $employer_sid;
            $data['applicant_sid'] = $applicant_sid;
            $data['questionnaire_sid'] = $questionnaire_sid;
            $data['logged_in_employer_sid'] = $logged_in_employer_sid;
            $data['job_sid'] = $job_sid;

           //die($data['job_sid'].'s');

            $data['questionnaire'] = $questionnaire;
            $this->load->view('main/header', $data);
            $this->load->view('interview_questionnaires/launch');
            $this->load->view('main/footer');


        } else {
            redirect('login', "refresh");
        }
    }

    public function print_interview($applicant_sid, $questionnaire_sid,  $employer_sid = 0){
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'applicant_profile/' . $applicant_sid, 'applicant_emergency_contacts');

            if($employer_sid == 0) {
                $employer_sid = $data["session"]["employer_detail"]["sid"];
            }

            $company_sid = $data["session"]["company_detail"]["sid"];
            $logged_in_employer_sid = $data["session"]["employer_detail"]["sid"];

            $candidate_info = $this->interview_questionnaires_model->get_applicant_information($company_sid, $applicant_sid);

            $data = applicant_right_nav($applicant_sid);
            $data['title'] = 'Interview Questionnaire';
            $left_navigation = 'manage_employer/profile_right_menu_applicant';
            $reload_location = 'emergency_contacts/applicant/' . $applicant_sid;

            $data["return_title_heading"] = "Applicant Profile";
            $data["return_title_heading_link"] = base_url() . 'applicant_profile/' . $applicant_sid;
            $data['left_navigation'] = $left_navigation;
            $data["employer"] = $candidate_info;
            $data['candidate_type'] = 'applicant';
            $data['candidate_sid'] = $applicant_sid;

            $job_sid = 0;

            $questionnaire_score_data = $this->interview_questionnaires_model->get_questionnaire_score_data($company_sid, $employer_sid, $applicant_sid, 'applicant', $job_sid, $questionnaire_sid);

            //Handle Previous Scores - Start
            $candidate_scores = $this->interview_questionnaires_model->get_questionnaire_score_data_candidate_specific($company_sid, $applicant_sid, 'applicant', $job_sid, $questionnaire_sid);
            $candidate_total_score = 0;
            $job_relevancy_total_score = 0;
            $overall_score = 0;
            $total_star_rating = 0;
            $candidate_average_score = 0;
            $job_relevancy_average_score = 0;
            $overall_average_score = 0;
            $average_star_rating = 0;

            if (!empty($candidate_scores)) {
                foreach ($candidate_scores as $key => $score) {
                    $already_scored_by = $score['employer_sid'];

                    if ($logged_in_employer_sid == $already_scored_by) {
                        //unset($candidate_scores[$key]);
                        $candidate_scores[$key]['scored_by']['first_name'] = 'Your';
                        $candidate_scores[$key]['scored_by']['last_name'] = 'Score';
                    }

                    $candidate_total_score += $score['candidate_score'];
                    $job_relevancy_total_score += $score['job_relevancy_score'];
                    $overall_score += $score['candidate_overall_score'] + $score['job_relevancy_overall_score'];
                    $total_star_rating += $score['star_rating'];
                }
            }

            $score_count = count($candidate_scores);

            if ($score_count > 0) {
                $candidate_average_score = $candidate_total_score / $score_count;
                $job_relevancy_average_score = $job_relevancy_total_score / $score_count;
                $overall_average_score = (($overall_score * 10) / 2) / $score_count;
                $average_star_rating = $total_star_rating / $score_count;
            }

            $data['candidate_average_score'] = $candidate_average_score;
            $data['job_relevancy_average_score'] = $job_relevancy_average_score;
            $data['overall_average_score'] = $overall_average_score;
            $data['average_star_rating'] = $average_star_rating;
            $data['candidate_scores'] = $candidate_scores;
            //Handle Previous Scores - End


            if (!empty($questionnaire_score_data)) {
                $data['is_already_scored'] = 1;
                $data['questionnaire_score'] = $questionnaire_score_data;
                $questionnaire = unserialize($questionnaire_score_data['questionnaire']);
                $data['questionnaire_form'] = unserialize($questionnaire_score_data['questionnaire_form']);
                $data['evaluation_form'] = unserialize($questionnaire_score_data['evaluation_form']);
                $data['interview_conducted_by'] = $questionnaire_score_data['interview_conducted_by'];
            } else {
                $data['is_already_scored'] = 0;
                $data['questionnaire_score'] = array();
                $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid, $company_sid);
                
                if(empty($questionnaire)){
                    $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid);
                }
                
                $data['questionnaire_form'] = array();
                $data['evaluation_form'] = array();
            }



            $data['is_manage'] = 0;
            $data['is_preview'] = 0;
            $data['is_print'] = 1;
            $data['employer_sid'] = $employer_sid;
            $data['applicant_sid'] = $applicant_sid;
            $data['questionnaire_sid'] = $questionnaire_sid;
            $data['logged_in_employer_sid'] = $logged_in_employer_sid;
            $data['job_sid'] = $job_sid;

            $candidate_info = $this->interview_questionnaires_model->get_applicant_information($company_sid, $applicant_sid);
            $left_navigation = 'manage_employer/profile_right_menu_applicant';
            $data['left_navigation'] = $left_navigation;
            $data["employer"] = $candidate_info;
            $data['candidate_sid'] = $applicant_sid;


            $job_info = $this->interview_questionnaires_model->get_job_information($company_sid, $job_sid);
            $data['job_info'] = $job_info;
            //$questionnaire_sid = $job_info['interview_questionnaire_sid'];
            $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid, $company_sid);
                
            if(empty($questionnaire)){
                $questionnaire = $this->interview_questionnaires_model->get_questionnaire_data($questionnaire_sid);
            }


            $data['questionnaire'] = $questionnaire;
            $data['title'] = 'Print Interview Questionnaire';



            //$this->load->view('main/header_print', $data);
            $this->load->view('interview_questionnaires/print_form', $data);
            //$this->load->view('main/footer_print');


        } else {
            redirect('login', "refresh");
        }
    }
    
}