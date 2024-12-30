<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Screening_questionnaires extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('screening_questionnaires_model');
    }
    
    public function index() { 
        if ($this->session->userdata('logged_in')) { //echo 'here'; exit;

            if (!checkIfAppIsEnabled('candidatequestionnaires')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }
            
            //$medata = $this->screening_questionnaires_model->update_old_questionnaire_records();
            $session_details                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $session_details['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;   
            check_access_permissions($security_details, 'dashboard', 'screening_questionnaires'); // Param2: Redirect URL, Param3: Function Name
            $data['session']                                                    = $this->session->userdata('logged_in');
            $data['title']                                                      = 'Screening Questionnaires';
            $employer_id                                                        = $data['session']['company_detail']['sid'];
            //variable to use
            $success_message                                                    = '';
            $error_message                                                      = '';
            $sub_title                                                          = 'Screening Questionnaires';
            $new_button_title                                                   = 'Create New Questionnaire';
            $add_new_function                                                   = 'add_new()';
            $main_question                                                      = 1;
            $questionnaire_sid                                                  = '';
            $back_button                                                        = '&laquo; Back to Dashboard';
            $back_button_url                                                    = 'dashboard';
            $questions                                                          = array();
            $child_questions                                                    = array();
            $individual_questions                                               = array();
            //----------------1----------//
            if (isset($_POST['action']) && ($_POST['action'] == 'add_question' || $_POST['action'] == 'modify_question')) {
                $formpost                                                       = $this->input->post(NULL, TRUE);
                $action                                                         = $this->input->post('action');
                $name                                                           = addslashes($formpost['name']);
                $auto_reply_pass                                                = $formpost['auto_reply_pass'];
                $auto_reply_fail                                                = $formpost['auto_reply_fail'];
                $email_text_pass                                                = html_entity_decode($formpost['email_text_pass']);
                $email_text_fail                                                = html_entity_decode($formpost['email_text_fail']);
                $employers_sid                                                  = $formpost['employers_sid'];
                $type                                                           = isset($formpost['que_type']) ? $formpost['que_type'] : 'job';

                if ($action == 'add_question') {
                    $insert_data = array(   'name'                              => $name,
                                            'employer_sid'                      => $employers_sid,
                                            'auto_reply_pass'                   => $auto_reply_pass,
                                            'email_text_pass'                   => $email_text_pass,
                                            'auto_reply_fail'                   => $auto_reply_fail,
                                            'email_text_fail'                   => $email_text_fail,
                                            'type'                              => $type,
                                            'creation_date'                     => date('Y-m-d H:i:s')
                                        );
                    $this->screening_questionnaires_model->insert_questionnaires($insert_data);
                    $success_message = "Screening Questionnaire is successfully added!";
                } else {
                    $update_data = array(   'name'                              => $name,
                                            'auto_reply_pass'                   => $auto_reply_pass,
                                            'email_text_pass'                   => $email_text_pass,
                                            'auto_reply_fail'                   => $auto_reply_fail,
                                            'email_text_fail'                   => $email_text_fail,
                                            'type'                              => $type,
                                            'modified_date'                     => date('Y-m-d H:i:s')
                                        );
                    $my_sid                                                     = $formpost['sid'];
                    $this->screening_questionnaires_model->update_questionnaires($my_sid, $update_data);
                    $success_message                                            = "Screening Questionnaire is successfully updated!";
                }
            }
            ///---------------2----------///
            if (isset($_POST['child_action']) && $_POST['child_action'] == 'child_question_add') {
                    $formpost                                                   = $this->input->post(NULL, TRUE);
//                    echo '<pre>';
//                    print_r($formpost);
//                    exit;
                    $action                                                     = $this->input->post('child_action');
                    $caption                                                    = $this->input->post('caption');
                    $is_required                                                = 0;
                    
                    if (isset($_POST['is_required'])) {
                        $is_required                                            = 1;
                    }
                    
                    $question_type                                              = $this->input->post('question_type');
                    $questionnaire_sid                                          = $this->input->post('questionnaire_sid');
                    $employers_sid                                              = $this->input->post('employers_sid');
                    // add data in `portal_questions` table
                    $insert_data = array(
                        'questionnaire_sid' => $questionnaire_sid,
                        'caption' => $caption,
                        'is_required' => $is_required,
                        'question_type' => $question_type
                    );

                $questions_sid = $this->screening_questionnaires_model->insert_questions($insert_data);
//                $portal_questions_query = 'INSERT INTO `portal_questions` (`questionnaire_sid`, `caption`, `is_required`, `question_type`) VALUES ("' . $questionnaire_sid . '", "' . $caption . '", "' . $is_required . '", "' . $question_type . '")';
                if ($questions_sid) {
                    //  $questions_sid = mysql_insert_id();
                    $success_message = "Success: Question is successfully added.";
                    // add data in `portal_question_option` 
                    if ($question_type == 'boolean') {
                        $yes = $_POST['answer_boolean'][0];
                        $no = $_POST['answer_boolean'][1];
                        $yes_status = $_POST['status_boolean'][0];
                        $no_status = $_POST['status_boolean'][1];
                        
                        $insert_data = array(
                                            'questionnaire_sid' => $questionnaire_sid,
                                            'questions_sid' => $questions_sid,
                                            'value' => 'Yes',
                                            'score' => $yes,
                                            'result_status' => $yes_status
                                        );
                        $this->screening_questionnaires_model->insert_question_option($insert_data);
                        //   mysql_query('INSERT INTO `portal_question_option` (`questionnaire_sid`, `questions_sid`, `value`, `score`) VALUES ("' . $questionnaire_sid . '", "' . $questions_sid . '", "Yes", "' . $yes . '")');
                        $insert_data = array(
                                        'questionnaire_sid' => $questionnaire_sid,
                                        'questions_sid' => $questions_sid,
                                        'value' => 'No',
                                        'score' => $no,
                                        'result_status' => $no_status
                                    );
                        //mysql_query('INSERT INTO `portal_question_option` (`questionnaire_sid`, `questions_sid`, `value`, `score`) VALUES ("' . $questionnaire_sid . '", "' . $questions_sid . '", "No", "' . $no . '")');
                        $this->screening_questionnaires_model->insert_question_option($insert_data);
                    }
                    
                    if ($question_type == 'list') {
                        $singlelist_value = $_POST['singlelist_value'];
                        $singlelist_score_value = $_POST['singlelist_score_value'];
                        $singlelist_status_value = $_POST['singlelist_status_value'];
                        
                        for ($i = 0; $i < count($singlelist_value); $i++) {
                            $single_value = $singlelist_value[$i];
                            $single_score = $singlelist_score_value[$i];
                            $single_status = $singlelist_status_value[$i];
                            
                            $insert_data = array(
                                'questionnaire_sid' => $questionnaire_sid,
                                'questions_sid' => $questions_sid,
                                'value' => $single_value,
                                'score' => $single_score,
                                'result_status' => $single_status
                            );
                            $this->screening_questionnaires_model->insert_question_option($insert_data);
                            // mysql_query('INSERT INTO `portal_question_option` (`questionnaire_sid`, `questions_sid`, `value`, `score`) VALUES ("' . $questionnaire_sid . '", "' . $questions_sid . '", "' . $single_value . '", "' . $single_score . '")');
                        }
                    }
                    
                    if ($question_type == 'multilist') {
                        $multilist_value = $_POST['multilist_value'];
                        $multilist_score_value = $_POST['multilist_score_value'];
                        $multilist_status_value = $_POST['multilist_status_value'];
                        
                        for ($i = 0; $i < count($multilist_value); $i++) {
                            $multi_value = $multilist_value[$i];
                            $multi_score = $multilist_score_value[$i];
                            $multi_status = $multilist_status_value[$i];
                            
                            $insert_data = array(
                                'questionnaire_sid' => $questionnaire_sid,
                                'questions_sid' => $questions_sid,
                                'value' => $multi_value,
                                'score' => $multi_score,
                                'result_status' => $multi_status
                            );
                            
                            $this->screening_questionnaires_model->insert_question_option($insert_data);
//                            mysql_query('INSERT INTO `portal_question_option` (`questionnaire_sid`, `questions_sid`, `value`, `score`) VALUES ("' . $questionnaire_sid . '", "' . $questions_sid . '", "' . $multi_value . '", "' . $multi_score . '")');
                        }
                    }
                }
            }
            ///---------------3----------///
            if (isset($_POST['child_action']) && $_POST['child_action'] == 'child_question_edit') {
                $action = $this->input->post('child_action');
                $caption = $this->input->post('caption');
                $is_required = 0;
                if (isset($_POST['is_required'])) {
                    $is_required = 1;
                }
                $question_type = $this->input->post('question_type_edit');
                $questionnaire_sid = $this->input->post('questionnaire_sid');
                $employers_sid = $this->input->post('employers_sid');
                $questions_sid = $this->input->post('child_question_id');

                $update_data = array(
                    'caption' => $caption,
                    'question_type' => $question_type,
                    'is_required' => $is_required
                );
                $this->screening_questionnaires_model->update_questions($questions_sid, $update_data);
                $this->screening_questionnaires_model->delete_question_option($questions_sid);
                // mysql_query('UPDATE `portal_questions` SET `caption` = "' . $caption . '", `question_type` = "' . $question_type . '", `is_required` = "' . $is_required . '" WHERE `sid` = "' . $questions_sid . '" LIMIT 1');
//                mysql_query('DELETE  FROM `portal_question_option` where `questions_sid` = "' . $questions_sid . '"');
                // add data in `portal_question_option` 
                if ($question_type == 'boolean') {
                    $yes = $_POST['answer_boolean_edit'][0];
                    $no = $_POST['answer_boolean_edit'][1];
                    $yes_status = $_POST['status_boolean_edit'][0];
                    $no_status = $_POST['status_boolean_edit'][1];

                    $insert_data = array(
                                        'questionnaire_sid' => $questionnaire_sid,
                                        'questions_sid' => $questions_sid,
                                        'value' => 'Yes',
                                        'score' => $yes,
                                        'result_status' => $yes_status
                                    );
                    $this->screening_questionnaires_model->insert_question_option($insert_data);
                    
                    $insert_data = array(
                                        'questionnaire_sid' => $questionnaire_sid,
                                        'questions_sid' => $questions_sid,
                                        'value' => 'No',
                                        'score' => $no,
                                        'result_status' => $no_status
                                    );
                    $this->screening_questionnaires_model->insert_question_option($insert_data);
                }

                if ($question_type == 'list') {
                    $singlelist_value = $_POST['singlelist_value_edit'];
                    $singlelist_score_value = $_POST['singlelist_score_value_edit'];
                    $singlelist_status_value = $_POST['singlelist_status_value_edit'];
                    
                    for ($i = 0; $i < count($singlelist_value); $i++) {
                        $single_value = $singlelist_value[$i];
                        $single_score = $singlelist_score_value[$i];
                        $single_status = $singlelist_status_value[$i];
                        
                        $insert_data = array(
                                            'questionnaire_sid' => $questionnaire_sid,
                                            'questions_sid' => $questions_sid,
                                            'value' => $single_value,
                                            'score' => $single_score,
                                            'result_status' => $single_status
                                        );
                        $this->screening_questionnaires_model->insert_question_option($insert_data);
                    }
                }

                if ($question_type == 'multilist') {
                    $multilist_value = $_POST['multilist_value_edit'];
                    $multilist_score_value = $_POST['multilist_score_value_edit'];
                    $multilist_status_value = $_POST['multilist_status_value_edit'];
                        
                    for ($i = 0; $i < count($multilist_value); $i++) {
                        $multi_value = $multilist_value[$i];
                        $multi_score = $multilist_score_value[$i];
                        $multi_status = $multilist_status_value[$i];
                            
                        $insert_data = array(
                                            'questionnaire_sid' => $questionnaire_sid,
                                            'questions_sid' => $questions_sid,
                                            'value' => $multi_value,
                                            'score' => $multi_score,
                                            'result_status' => $multi_status
                                        );
                        $this->screening_questionnaires_model->insert_question_option($insert_data);
                    }
                }
                $success_message = "Success: Question is successfully updated.";
            }
            ///---------------4----------///
            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'remove_questionnaire') {
                $my_sid_main = $_REQUEST['sid'];
                $this->screening_questionnaires_model->delete_questionnaires($my_sid_main);
//                mysql_query("DELETE FROM `portal_screening_questionnaires` WHERE `sid` = '" . $my_sid_main . "'");
                $this->screening_questionnaires_model->delete_questions($my_sid_main);
//                mysql_query("DELETE FROM `portal_questions` WHERE `questionnaire_sid` = '" . $my_sid_main . "'");
                $this->screening_questionnaires_model->delete_question_option($my_sid_main);
                //mysql_query("DELETE FROM `portal_question_option` WHERE `questionnaire_sid` = '" . $my_sid_main . "'");
                echo "success!";
                exit;
            }
            ///---------------5----------///
            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'remove_child_question') {
                $my_sid_child = $_REQUEST['sid'];
                $this->screening_questionnaires_model->delete_questions($my_sid_child);
//                mysql_query("DELETE FROM `portal_questions` WHERE `sid` = '" . $my_sid_child . "'");
                $this->screening_questionnaires_model->delete_question_option($my_sid_child);
//                mysql_query("DELETE FROM `portal_question_option` WHERE `questions_sid` = '" . $my_sid_child . "'");
                echo "success!";
                exit;
            }
            ///---------------6----------///
            if (isset($_GET['action']) && $_GET['action'] == 'questions' && (isset($_GET['id']) && !empty($_GET['id']))) {
                ///=========look down ==========///
                $heading_title = 'Add Question(s)';
                $new_button_title = 'Add New Question';
                $add_new_function = 'add_child_question()';
                $back_button = '&laquo; Back to Screening Questionnaires';
                $back_button_url = 'screening_questionnaires';
                $main_question = 0;
                ///=========look above ==========///
                $questionnaire_sid = $_GET['id'];
                $question_data = $this->screening_questionnaires_model->get_all_questionnaires($questionnaire_sid);
                $name = $question_data['name'];
                $sub_title = 'Manage Question for <span style="color: #1f7f05;">"' . $name . '"</span>';
                $child_questions_data_query = $this->screening_questionnaires_model->get_all_questions($questionnaire_sid);
                
                if (count($child_questions_data_query) > 0) {
                    foreach ($child_questions_data_query as $row) {
                        $questions_sid = $row['sid'];
                        $answers_question_query = $this->screening_questionnaires_model->get_all_question_option($questions_sid);
                        
                        if (count($answers_question_query) > 0) {
                            foreach ($answers_question_query as $row2) {
                                $individual_questions[$questions_sid][] = array('sid' => $row2['sid'], 'questions_sid' => $row2['questions_sid'], 'value' => $row2['value'], 'score' => $row2['score'], 'result_status' => $row2['result_status'], 'question_type' => $row['question_type'], 'questionnaire_sid' => $row2['questionnaire_sid']);
                            }
                        } else {
                            $individual_questions[$questions_sid] = array();
                        }
                        $child_questions[] = array('sid' => $row['sid'], 'questionnaire_sid' => $row['questionnaire_sid'], 'caption' => $row['caption'], 'is_required' => $row['is_required'], 'question_type' => $row['question_type']);
                    }
                }
                            //echo '<pre>'; print_r($individual_questions); echo '</pre>';
            } else {
                $questions_data_query = $this->screening_questionnaires_model->get_all_questionnaires_by_employer($employer_id);
//                echo '<pre>';
//                print_r($questions_data_query);
//                die();
                if (count($questions_data_query) > 0) {
                    foreach ($questions_data_query as $row) {
                        $questions[] = array('sid' => $row['sid'], 'employer_sid' => $row['employer_sid'], 'name' => $row['name'], 'auto_reply_pass' => $row['auto_reply_pass'], 'email_text_pass' => $row['email_text_pass'], 'auto_reply_fail' => $row['auto_reply_fail'], 'email_text_fail' => $row['email_text_fail'], 'creation_date' => $row['creation_date'], 'modified_date' => $row['modified_date'], 'type' => $row['type']);
                    }
                }
            }
            ///---------------END OF POST METHOD?/CALLS----------///
            //-----------------------SHOW FRONT END----------------------//
            $data['sub_title'] = $sub_title;
            $data['new_button_title'] = $new_button_title;
            $data['add_new_function'] = $add_new_function;
            $data['questionnaire_sid'] = $questionnaire_sid;
            $data['back_button'] = $back_button;
            $data['back_button_url'] = $back_button_url;
            $data['main_question'] = $main_question;
            $data['questions'] = $questions;
            $data['child_questions'] = $child_questions;
            $data['individual_questions'] = $individual_questions;
            $data['success_message'] = $success_message;
            $data['error_message'] = $error_message;
            $data['user_sid'] = $employer_id;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/screening_questionnaires_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
