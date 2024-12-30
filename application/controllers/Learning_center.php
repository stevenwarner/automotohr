<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Learning_center extends Public_Controller {
    private $limit;
    private $list_size;
    public function __construct() {
        parent::__construct();

        $this->limit     = 100;
        $this->list_size = 5;

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->model('learning_center_model');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {

            if (!checkIfAppIsEnabled('learningcenter')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'Learning_center'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['title'] = 'Learning Management System';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('learning_center/index');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function online_videos() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'online_video');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['title'] = 'Learning Management System - Online Videos';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            if ($this->form_validation->run() == false) {
                $videos = $this->learning_center_model->get_all_online_videos($company_sid);
                $data['videos'] = $videos;
                $this->load->view('main/header', $data);
                $this->load->view('learning_center/online_videos');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'delete_online_video':
                        $video_sid = $this->input->post('video_sid');
                        $video_source = $this->learning_center_model->get_video_status($video_sid);
                        $this->learning_center_model->delete_training_video($video_sid);

                        if ($video_source[0]['video_source'] == 'upload') {
                            $video_url = 'assets/uploaded_videos/' . $video_source[0]['video_id'];
                            unlink($video_url);
                        }

                        $this->session->set_flashdata('message', '<strong>Success </strong> Learning video deleted!');
                        redirect('learning_center/online_videos/', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function add_online_video() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'add_online_videos');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['title'] = 'Learning Management System - Add Online Videos';

            $config = array(
                array(
                    'field' => 'video_title',
                    'label' => 'Video Title',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'perform_action',
                    'label' => 'perform_action',
                    'rules' => 'xss_clean|trim|required'
                )
            );

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);
            $data['screening_questions'] = $this->learning_center_model->getScreeningQuestionnaires($company_sid);
            $data['attachments'] = array();
            if ($this->form_validation->run() == false) {
                $employees = $this->learning_center_model->get_all_employees($company_sid);
                $data['employees'] = $employees;
                $departments = $this->learning_center_model->getActiveDepartments($company_sid);
                $data['departments'] = $departments;
                $applicants = $this->learning_center_model->get_all_onboarding_applicants($company_sid);
                $data['applicants'] = $applicants;
                $data['selected_employees'] = array();
                $data['selected_applicants'] = array();
                $video = $this->learning_center_model->get_empty_video_record($employer_sid);
                $video_url = '';
                $data['questionnaire_sid'] = 0;
                //
                if(!empty($video)){
                    if (isset($video['video_source'])) {
                        if ($video['video_source'] == 'youtube' && !empty($video['video_id'])) {
                            $video_url = $video['video_id'];
                        } else if ($video['video_source'] == 'vimeo' && !empty($video['video_id'])) {
                            $video_url = $video['video_id'];
                        } else if ($video['video_source'] == 'upload' && !empty($video['video_id'])) {
                            $video_url = base_url() . 'assets/uploaded_videos/' . $video['video_id'];
                        }
                    }
                    $attachments = $this->learning_center_model->get_attached_document($video['sid']);
                    $data['attachments'] = $attachments;
                    $data['video_sid'] = $video['sid'];

                    $data['video'] = $video;
                    $data['old_upload_video'] = $video['video_id'];
                    $data['video_source'] = $video['video_source'];
                    $data['questionnaire_sid'] = $video['screening_questionnaire_sid'];
                }
                $data['video_url'] = $video_url;
                $this->load->view('main/header', $data);
                $this->load->view('learning_center/online_videos_add');
                $this->load->view('main/footer');
            } else {

                $post = $this->input->post(NULL, TRUE);

                $company_sid = $this->input->post('company_sid');
                $created_by = $this->input->post('employer_sid');
                $video_title = $this->input->post('video_title');
                $video_description = $this->input->post('video_description');
                $video_source = $this->input->post('video_source');
                $exist_video_sid = $this->input->post('video_sid');

                if (!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {
                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["video_upload"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                    } else {

                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('learning_center/add_online_video', 'refresh');
                    }

                    $video_id = $file_name;
                } else {
                    $video_id = $this->input->post('video_id');

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        } else {
                            $video_id = '';
                        }
                    } else {
                        $video_id = $this->vimeo_get_id($video_id);
                    }
                }

                $employees_assigned_to      = $this->input->post('employees_assigned_to');
                $employees_assigned_sid     = $this->input->post('employees_assigned_sid');
                $applicants_assigned_to     = $this->input->post('applicants_assigned_to');
                $applicants_assigned_sid    = $this->input->post('applicants_assigned_sid');
                $questionnaire_sid          = $this->input->post('questionnaire_sid');
                //
                if (empty($questionnaire_sid)) {
                    $questionnaire_sid = 0;
                }
                //
                $employees_assigned_sid = $employees_assigned_sid == null || empty($employees_assigned_sid) ? null : $employees_assigned_sid;
                $applicants_assigned_sid = $applicants_assigned_sid == null || empty($applicants_assigned_sid) ? null : $applicants_assigned_sid;
                //
                $data_to_insert = array();
                //
                if($employees_assigned_to == "none"){
                    $post['departments_assigned_sid'] = 
                    $applicants_assigned_sid = $employees_assigned_sid  = null;
                }else{
                    if(isset($post['departments_assigned_sid'])){
                        $data_to_insert['department_sids'] = array_search('-1', $post['departments_assigned_sid']) !== false || $post['departments_assigned_sid'] == 'all' ? 'all' : implode($post['departments_assigned_sid'],',');
                    } else{ 
                        $data_to_insert['department_sids'] = NULL;
                    }
                }
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['created_by_sid'] = $created_by;
                $data_to_insert['video_title'] = $video_title;
                $data_to_insert['video_description'] = $video_description;
                $data_to_insert['video_source'] = $video_source;
                $data_to_insert['video_id'] = $video_id;
                //
                if (isset($_POST['video_start_date']) && !empty($_POST['video_start_date'])) {
                    $data_to_insert['video_start_date'] = DateTime::createfromformat('m-d-Y', $_POST['video_start_date'])->format('Y-m-d');
                }
                //
                if ($_POST['is_video_expired'] == 'yes') {
                    $data_to_insert['expired_number'] = $_POST['expired_number'];
                    $data_to_insert['expired_type'] = $_POST['expired_type'];
                    $data_to_insert['is_video_expired'] = $_POST['is_video_expired'];
                    $data_to_insert['expired_start_date'] = date('Y-m-d', strtotime($data_to_insert['video_start_date']. '+'.($data_to_insert['expired_number']).' '.$data_to_insert['expired_type'] ));
                } else {
                    $data_to_insert['is_video_expired'] = "no";
                    $data_to_insert['expired_number'] = null;
                    $data_to_insert['expired_type'] = null;
                    $data_to_insert['expired_start_date'] = null;
                }
                //
                $data_to_insert['employees_assigned_sid'] = !empty($employees_assigned_sid) ? implode(',', $employees_assigned_sid) : NULL;
                $data_to_insert['applicants_assigned_sid'] = !empty($applicants_assigned_sid) ? implode(',', $applicants_assigned_sid) : NULL;

                $data_to_insert['employees_assigned_to'] = $employees_assigned_to == "none" ? "specific" : $employees_assigned_to;
                $data_to_insert['applicants_assigned_to'] = $applicants_assigned_to == "none" ? "specific" : $applicants_assigned_to;
                $data_to_insert['sent_email'] = $post['send_email'] == 'yes' ? 1 : 0;
                $data_to_insert['screening_questionnaire_sid'] = $questionnaire_sid;       
                //
                
                if($exist_video_sid > 0){
                    $video_sid = $exist_video_sid;
                    $this->learning_center_model->update_training_video($video_sid, $data_to_insert);
                }else{ 
                    $video_sid = $this->learning_center_model->insert_training_video($data_to_insert);
                }

                $last_active_assignments = $this->learning_center_model->get_last_active_video_assignments($video_sid);
                $this->learning_center_model->set_online_videos_assignment_status($video_sid);

                if (!empty($employees_assigned_sid) && $employees_assigned_to == "specific") {
                    foreach ($employees_assigned_sid as $sid) {
                        $last_active_assignment = $this->get_assignment_record($last_active_assignments, 'employee', $sid);
                        $data_to_insert = array();
                        $data_to_insert['learning_center_online_videos_sid'] = $video_sid;
                        $data_to_insert['user_type'] = 'employee';
                        $data_to_insert['user_sid'] = $sid;
                        $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                        $data_to_insert['status'] = 1;

                        if (!empty($last_active_assignment)) {
                            $data_to_insert['watched'] = $last_active_assignment['watched'] = '' ? 0 : $last_active_assignment['watched'];
                            $data_to_insert['date_watched'] = $last_active_assignment['date_watched'];
                        }

                        $this->learning_center_model->insert_online_videos_assignments_record($data_to_insert);
                    }
                }

                if (!empty($applicants_assigned_sid) && $applicants_assigned_to == 'specific') {
                    foreach ($applicants_assigned_sid as $sid) {
                        $last_active_assignment = $this->get_assignment_record($last_active_assignments, 'applicant', $sid);
                        $data_to_insert = array();
                        $data_to_insert['learning_center_online_videos_sid'] = $video_sid;
                        $data_to_insert['user_type'] = 'applicant';
                        $data_to_insert['user_sid'] = $sid;
                        $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                        $data_to_insert['status'] = 1;

                        if (!empty($last_active_assignment)) {
                            $data_to_insert['watched'] = $last_active_assignment['watched'];
                            $data_to_insert['date_watched'] = $last_active_assignment['date_watched'];
                        }

                        $this->learning_center_model->insert_online_videos_assignments_record($data_to_insert);
                    }
                }


                // Check email
                if($post['send_email'] == 'yes' && $employees_assigned_to != 'none'){
                    //
                    $employeesList = array();
                    
                    if($post['employees_assigned_to'] == 'all'){
                        // Get all employees
                        $employeesList = $this->learning_center_model->getActiveEmployees(
                            $company_sid
                        );
                    } else {
                        $specific_assign_employees = array();
                        if ($post['employees_assigned_to'] == 'specific') {
                            $specific_assign_employees = $this->learning_center_model->getActiveEmployees(
                                $company_sid,
                                $employees_assigned_sid
                            );
                        }

                        $selected_department_employees = array();
                        // Get selected department employees
                        if (isset($post['departments_assigned_sid']) && !empty($post['departments_assigned_sid'])) {
                            $selected_department_employees = $this->learning_center_model->getDepartmentEmployeesList(
                                $company_sid,
                                $post['departments_assigned_sid']
                            );
                        }  

                        $employeesMergeList = array_merge($specific_assign_employees, $selected_department_employees);  
                        $employeesList = array_unique($employeesMergeList, SORT_REGULAR);
                    }

                    if(sizeof($employeesList)){
                        $mf = message_header_footer_domain(
                            $company_sid,
                            $data['session']['company_detail']['CompanyName']
                        );
                        foreach ($employeesList as $k => $v) {
                            $replacement_array = array();
                            $replacement_array['contact-name'] = ucwords($v['first_name'] . ' ' . $v['last_name']);
                            $replacement_array['company_name'] = ucwords($data['session']['company_detail']['CompanyName']);
                            $replacement_array['firstname'] = $v['first_name'];
                            $replacement_array['lastname'] = $v['last_name'];
                            $replacement_array['first_name'] = $v['first_name'];
                            $replacement_array['last_name'] = $v['last_name'];
                            $replacement_array['baseurl'] = base_url();
                            $replacement_array['url'] = base_url('learning_center/my_learning_center');

                            log_and_send_templated_email(VIDEO_TEMPLATE_SID, $v['email'], $replacement_array, $mf);
                        }
                    }

                    // Send Email
                    // // VIDEO_TEMPLATE_SID
                }

                $this->session->set_flashdata('message', '<strong>Success: </strong> Video Successfully Saved');
                redirect('learning_center/online_videos', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function edit_online_video($video_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'edit_online_video');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['title'] = 'Learning Center - Edit Online Videos';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');
            $data['screening_questions'] = $this->learning_center_model->getScreeningQuestionnaires($company_sid);

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            if ($this->form_validation->run() == false) {
                $employees = $this->learning_center_model->get_all_employees($company_sid);
                $data['employees'] = $employees;

                $departments = $this->learning_center_model->getActiveDepartments($company_sid);
                $data['departments'] = $departments;
                $applicants = $this->learning_center_model->get_all_onboarding_applicants($company_sid);
                $data['applicants'] = $applicants;
                $video = $this->learning_center_model->get_online_video($video_sid);
                $data['video'] = $video;
                $selected_employees = array();
                $data['questionnaire_sid'] = $video['screening_questionnaire_sid'];

                if (isset($video['employees'])) {
                    foreach ($video['employees'] as $employee) {
                        if (!in_array($employee['user_sid'], $selected_employees)) {
                            $selected_employees[] = $employee['user_sid'];
                        }
                    }
                }

                $data['selected_employees'] = $selected_employees;
                if (!empty($video['department_sids'])) {
                    $data['selected_departments'] = explode(',', $video['department_sids']);
                }
                
                // _e($data['selected_departments'], true, true);
                // _e($data['selected_departments'], true, true);
                $selected_applicants = array();

                if (isset($video['applicants'])) {
                    foreach ($video['applicants'] as $applicant) {
                        if (!in_array($applicant['user_sid'], $selected_applicants)) {
                            $selected_applicants[] = $applicant['user_sid'];
                        }
                    }
                }

                $attachments = $this->learning_center_model->get_attached_document($video_sid);
                $data['attachments'] = $attachments;
                $data['selected_applicants'] = $selected_applicants;
                $data['video_sid'] = $video_sid;
                $video_url = '';

                if (isset($video['video_source'])) {
                    if ($video['video_source'] == 'youtube' && !empty($video['video_id'])) {
                        $video_url = $video['video_id'];
                    } else if ($video['video_source'] == 'vimeo' && !empty($video['video_id'])) {
                        $video_url = $video['video_id'];
                    } else if ($video['video_source'] == 'upload' && !empty($video['video_id'])) {
                        $video_url = base_url() . 'assets/uploaded_videos/' . $video['video_id'];
                    }
                }

                $data['old_upload_video'] = $video['video_id'];
                $data['video_source'] = $video['video_source'];
                $data['video_url'] = $video_url;
                $this->load->view('main/header', $data);
                $this->load->view('learning_center/online_videos_add');
                $this->load->view('main/footer');
            } else {
                
                $post = $this->input->post(NULL, TRUE);

                $perform_action = $this->input->post('perform_action');
                $video_sid = $this->input->post('video_sid');
                $company_sid = $this->input->post('company_sid');
                $created_by = $this->input->post('employer_sid');
                $video_title = $this->input->post('video_title');
                $video_description = $this->input->post('video_description');
                $video_source = $this->input->post('video_source');
                $video_id = $this->input->post('video_id');
                $employees_assigned_to = $this->input->post('employees_assigned_to');
                $employees_assigned_sid = $this->input->post('employees_assigned_sid');
                $applicants_assigned_to = $this->input->post('applicants_assigned_to');
                $applicants_assigned_sid = $this->input->post('applicants_assigned_sid');
                $questionnaire_sid = $this->input->post('questionnaire_sid');


                if (empty($questionnaire_sid)) {
                    $questionnaire_sid = 0;
                }

                $data_to_update = array();
                $remove_flag = false;

                if ($employees_assigned_to == "none") {
                    $this->learning_center_model->delete_all_assign_video_user($video_sid);
                    $dts = $data_to_update['department_sids'] = $employees_assigned_sid = $applicants_assigned_sid = NULL;
                } else{
                    $dts = $data_to_update['department_sids'] = isset($post['departments_assigned_sid']) ? 
                    (array_search('-1', $post['departments_assigned_sid']) !== false || $post['departments_assigned_sid'] == 'all' ? 'all' : implode($post['departments_assigned_sid'],',')) : NULL;
                }

                if (!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {
                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["video_upload"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('learning_center/edit_online_video'.'/'.$video_sid, 'refresh');
                    }

                    $data_to_update['video_id'] = $file_name;
                    $remove_flag = true;
                } else {
                    $video_id = $this->input->post('video_id');

                    if (!empty($video_id)) {
                        if ($video_source == 'youtube') {
                            $url_prams = array();
                            parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $data_to_update['video_id'] = $url_prams['v'];
                            } else {
                                $data_to_update['video_id'] = '';
                            }
                        } else {
                            $data_to_update['video_id'] = $this->vimeo_get_id($video_id);
                        }

                        $remove_flag = true;
                    } elseif (empty($video_id) && $video_source == 'upload') {
                        $old_video_id = $this->input->post('old_upload_video');
                        $data_to_update['video_id'] = $old_video_id;
                        $remove_flag = false;
                    }
                }

                if ($remove_flag == true) {
                    $video_source_name = $this->learning_center_model->get_video_status($video_sid);
                    $previous_source = $video_source_name[0]['video_source'];

                    if ($previous_source == 'upload') {
                        $video_url = 'assets/uploaded_videos/' . $video_source_name[0]['video_id'];
                        unlink($video_url);
                    }
                }
                $data_to_update['video_start_date'] =  DateTime::createfromformat('m-d-Y', $_POST['video_start_date'])->format('Y-m-d');

                if ($_POST['is_video_expired'] == 'yes') {
                    $data_to_update['expired_number'] = $_POST['expired_number'];
                    $data_to_update['expired_type'] = $_POST['expired_type'];
                    $data_to_update['is_video_expired'] = $_POST['is_video_expired'];
                    $data_to_update['expired_start_date'] = date('Y-m-d', strtotime($data_to_update['video_start_date']. '+'.($data_to_update['expired_number']).' '.$data_to_update['expired_type'] ));
                } else {
                    $data_to_update['is_video_expired'] = "no";
                    $data_to_update['expired_number'] = null;
                    $data_to_update['expired_type'] = null;
                    $data_to_update['expired_start_date'] = null;
                }

                $data_to_update['company_sid'] = $company_sid;
                $data_to_update['created_by_sid'] = $created_by;
                $data_to_update['video_title'] = $video_title;
                $data_to_update['video_description'] = $video_description;
                if ($video_source != 'do_not_change') {
                    $data_to_update['video_source'] = $video_source;
                }
                $data_to_update['employees_assigned_to'] = $employees_assigned_to == "none" ? "specific" : $employees_assigned_to;
                $data_to_update['applicants_assigned_to'] = $applicants_assigned_to == "none" ? "specific" : $applicants_assigned_to;
                $data_to_update['employees_assigned_sid'] = !empty($employees_assigned_sid) ? implode(',', $employees_assigned_sid) : '';
                $data_to_update['screening_questionnaire_sid'] = $questionnaire_sid;
                $data_to_update['sent_email'] = $post['send_email'] == 'yes' ? 1 : 0;

                $this->learning_center_model->update_training_video($video_sid, $data_to_update);
                $last_active_assignments = $this->learning_center_model->get_last_active_video_assignments($video_sid);

                if (!empty($employees_assigned_sid) && $employees_assigned_to == "specific") {
                    
                    $removed_users = $this->learning_center_model->get_all_remove_user($video_sid, $employees_assigned_sid);
                    
                    if(!empty($removed_users)) {
                        $assign_video = $this->learning_center_model->get_user_assign_online_video($video_sid);
                        //
                        foreach ($removed_users as $r_key => $removed_user) {
                            $user_question = $this->learning_center_model->get_user_question_record($video_sid, $removed_user['sid']);
                            if (!empty($user_question)) {
                                $removed_user['questionnaire_name'] = $user_question['questionnaire_name'];
                                $removed_user['questionnaire'] = $user_question['questionnaire'];
                                $removed_user['questionnaire_result'] = $user_question['questionnaire_result'];
                                $removed_user['questionnaire_attend_timestamp'] = $user_question['attend_timestamp'];
                            }

                            $this->learning_center_model->change_user_assign_video_status($removed_user['user_sid'], $video_sid);

                            unset($removed_user['sid']);
                            //
                            $removed_user['video_title']        = $assign_video['video_title'];
                            $removed_user['video_url']          = $assign_video['video_id'];
                            $removed_user['video_source']       = $assign_video['video_source'];
                            $removed_user['video_start_date']   = $assign_video['video_start_date'];
                            //
                            $this->learning_center_model->save_user_assign_video_history($removed_user);
                            
                        }
                    }

                    $already_assigned_users = $this->learning_center_model->get_already_assign_user($video_sid, $employees_assigned_sid);
                    $alreadyAssignedUsers = array_column($already_assigned_users, 'user_sid');

                    foreach ($employees_assigned_sid as $sid) {
                        if (!in_array($sid, $alreadyAssignedUsers)) {
                            $data_to_insert = array();
                            $data_to_insert['learning_center_online_videos_sid'] = $video_sid;
                            $data_to_insert['user_type'] = 'employee';
                            $data_to_insert['user_sid'] = $sid;
                            $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                            $data_to_insert['status'] = 1;

                            $this->learning_center_model->insert_online_videos_assignments_record($data_to_insert);
                        }    
                    }
                }

                if (!empty($applicants_assigned_sid) && $applicants_assigned_to == "specific") {
                    foreach ($applicants_assigned_sid as $sid) {
                        $last_active_assignment = $this->get_assignment_record($last_active_assignments, 'applicant', $sid);
                        $data_to_insert = array();
                        $data_to_insert['learning_center_online_videos_sid'] = $video_sid;
                        $data_to_insert['user_type'] = 'applicant';
                        $data_to_insert['user_sid'] = $sid;
                        $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                        $data_to_insert['status'] = 1;

                        if (!empty($last_active_assignment)) {
                            $data_to_insert['watched'] = $last_active_assignment['watched'];
                            $data_to_insert['date_watched'] = $last_active_assignment['date_watched'];
                        }

                        $this->learning_center_model->insert_online_videos_assignments_record($data_to_insert);
                    }
                }

                 // Check email
                if($post['send_email'] == 'yes' && $employees_assigned_to != 'none'){
                    //
                    $employeesList = array();
                    
                    if($post['employees_assigned_to'] == 'all'){
                        // Get all employees
                        $employeesList = $this->learning_center_model->getActiveEmployees(
                            $company_sid
                        );
                    } else {
                        $specific_assign_employees = array();
                        if ($post['employees_assigned_to'] == 'specific') {
                            $specific_assign_employees = $this->learning_center_model->getActiveEmployees(
                                $company_sid,
                                $employees_assigned_sid
                            );
                        }

                        $selected_department_employees = array();
                        // Get selected department employees
                        if (isset($post['departments_assigned_sid']) && !empty($post['departments_assigned_sid'])) {
                            $selected_department_employees = $this->learning_center_model->getDepartmentEmployeesList(
                                $company_sid,
                                $post['departments_assigned_sid']
                            );
                        }  

                        $employeesMergeList = array_merge($specific_assign_employees, $selected_department_employees);  
                        $employeesList = array_unique($employeesMergeList, SORT_REGULAR);
                    }

                    if(sizeof($employeesList)){
                        $mf = message_header_footer_domain(
                            $company_sid,
                            $data['session']['company_detail']['CompanyName']
                        );
                        foreach ($employeesList as $k => $v) {
                            $replacement_array = array();
                            $replacement_array['contact-name'] = ucwords($v['first_name'] . ' ' . $v['last_name']);
                            $replacement_array['company_name'] = ucwords($data['session']['company_detail']['CompanyName']);
                            $replacement_array['firstname'] = $v['first_name'];
                            $replacement_array['lastname'] = $v['last_name'];
                            $replacement_array['first_name'] = $v['first_name'];
                            $replacement_array['last_name'] = $v['last_name'];
                            $replacement_array['baseurl'] = base_url();
                            $replacement_array['url'] = base_url('learning_center/my_learning_center');

                            log_and_send_templated_email(VIDEO_TEMPLATE_SID, $v['email'], $replacement_array, $mf);
                        }
                    }

                    // Send Email
                    // // VIDEO_TEMPLATE_SID
                }

                $this->session->set_flashdata('message', '<strong>Success: </strong> Video Successfully Saved');
                redirect('learning_center/online_videos', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    private function get_assignment_record($assignments, $user_type, $user_sid) {
        if (is_array($assignments)) {
            foreach ($assignments as $assignment) {
                if ($assignment['user_type'] == $user_type && $assignment['user_sid'] == $user_sid) {
                    return $assignment;
                }
            }
        }

        return array();
    }

    /**
     * Show all training sessions
     * Updated on: 10-05-2019
     *
     * @param @arg String
     *
     * @return VOID
     */
    function training_sessions($arg = 'pending') {
        // Redirect if not logged in
        if (!$this->session->userdata('logged_in')) redirect(base_url('login'), "refresh");
        //
        $data['session'] = $this->session->userdata('logged_in');
        // Check for EMS check
        if (!check_company_ems_status($data['session']['company_detail']['sid'])) {
            $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
            redirect(base_url('dashboard'), "refresh");
        }
        //
        $data['security_details'] = $security_details = db_get_access_level_details($data['session']['employer_detail']['sid']);
        check_access_permissions($security_details, 'learning_center', 'training_sessions'); // no need to check in this Module as Dashboard will be available to all
        $data['company_sid'] = $company_sid = $data['session']['company_detail']['sid'];
        $data['employer_sid'] = $employer_sid = $data['session']['employer_detail']['sid'];
        //
        $data['title'] = 'Learning Center - Training Sessions';
        //
        $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            //
            if(!in_array(strtolower($arg), ['scheduled','confirmed','completed','cancelled']) || $arg == 'scheduled')
                $arg = 'pending';
            $data['activeTab'] = $arg;
            // $data['sessions'] = $this->learning_center_model->get_all_training_sessions($company_sid);
            $this->load->view('main/header', $data);
            $this->load->view('learning_center/training_sessions_ajax');
            // $this->load->view('learning_center/training_sessions');
            $this->load->view('main/footer');
            return;
        }


        // POST
        if($this->input->post('perform_action') == 'delete_training_session') {
            $session_sid = $this->input->post('session_sid');
            $this->learning_center_model->delete_training_session($session_sid);
            $this->learning_center_model->delete_event_by_training_session_id($session_sid);
            //
            $this->session->set_flashdata('message', '<strong>Success:</strong> Training Session Deleted!');
            redirect('learning_center/training_sessions', 'refresh');
        }
    }

    function add_training_session() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'add_training_session');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['title'] = 'Learning Center - Add Training Session';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            $employees = $this->learning_center_model->get_all_employees($company_sid);
            $videos = $this->learning_center_model->get_all_online_videos($company_sid);
            $data['videos'] = $videos;

            if ($this->form_validation->run() == false) {
                $data['employees'] = $employees;
                $applicants = $this->learning_center_model->get_all_onboarding_applicants($company_sid);
                $data['applicants'] = $applicants;
                $data['selected_employees'] = array();
                $data['selected_applicants'] = array();
                $data['selected_videos'] = array();
                $this->load->view('main/header', $data);
                $this->load->view('learning_center/training_session_add_ajax');
                // $this->load->view('learning_center/training_session_add');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                $company_sid = $this->input->post('company_sid');
                $employer_sid = $this->input->post('employer_sid');
                $session_topic = $this->input->post('session_topic');
                $session_description = $this->input->post('session_description');
                $session_location = $this->input->post('session_location');
                $session_date = $this->input->post('session_date');
                $session_start_time = $this->input->post('session_start_time');
                $session_end_time = $this->input->post('session_end_time');
                $employees_assigned_to = $this->input->post('employees_assigned_to');
                $employees_assigned_sid = $this->input->post('employees_assigned_sid');
                $applicants_assigned_to = $this->input->post('applicants_assigned_to');
                $applicants_assigned_sid = $this->input->post('applicants_assigned_sid');
                $online_video_sid = $this->input->post('online_video_sid');
                $video_sids = $online_video_sid;
                $online_video_sid = implode(',', $online_video_sid != '' ? $online_video_sid : array());
                $startam = date('h:i A', strtotime($session_start_time));
                $endam = date('h:i A', strtotime($session_end_time));
                //
                if($employees_assigned_to == 'none'){
                    $employees_assigned_sid = null;
                    $applicants_assigned_to = null;
                }
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['created_by'] = $employer_sid;
                $data_to_insert['session_topic'] = $session_topic;
                $data_to_insert['session_description'] = $session_description;
                $data_to_insert['session_location'] = $session_location;
                $data_to_insert['session_date'] = DateTime::createFromFormat('m-d-Y', $session_date)->format('Y-m-d');
                $data_to_insert['session_start_time'] = $session_start_time;
                $data_to_insert['session_end_time'] = $session_end_time;
                $data_to_insert['employees_assigned_to'] = $employees_assigned_to == 'none' ? 'specific' : $employees_assigned_to;
                $data_to_insert['applicants_assigned_to'] = $applicants_assigned_to;
                $data_to_insert['online_video_sid'] = $online_video_sid;
                $this->learning_center_model->insert_training_session_record($data_to_insert);
                $session_sid = $this->db->insert_id();
                $last_active_assignments = $this->learning_center_model->get_last_active_training_session_assignments($session_sid);
                $this->learning_center_model->set_training_session_assignment_status($session_sid);
                $event_details = array();

                if ($employees_assigned_to == 'all') {
                    foreach ($employees as $employee) {
                        $event_data = array();
                        $event_data['companys_sid'] = $company_sid;
                        $event_data['employers_sid'] = $employer_sid;
                        $event_data['applicant_email'] = $employee['email'];
                        $event_data['applicant_job_sid'] = $employee['sid'];
                        $event_data['users_type'] = 'employee';
                        $event_data['title'] = $session_topic;
                        $event_data['category'] = 'training-session';
                        $event_data['date'] = DateTime::createFromFormat('m-d-Y', $session_date)->format('Y-m-d');
                        $event_data['description'] = $session_description;
                        $event_data['eventstarttime'] = $startam;
                        $event_data['eventendtime'] = $endam;
                        $event_data['interviewer'] = NULL;
                        $event_data['address'] = $session_location;
                        $event_data['created_on'] = date('Y-m-d H:i:s');
                        $event_data['learning_center_training_sessions'] = $session_sid;
                        $insert_sid = $this->learning_center_model->insert_schedule_event($event_data);
                        $event_details[$insert_sid] = 'training-session';
                        $replacement_array['message'] = 'Hi ' . ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ', New training session has been assigned to you. Following are the details.';
                        $replacement_array['subject'] = $data['session']['company_detail']['CompanyName'] . ' - New Training Session';
                        $replacement_array['company_name'] = $data['session']['company_detail']['CompanyName'];
                        $replacement_array['session_name'] = $session_topic;
                        $replacement_array['session_description'] = $session_description;
                        $replacement_array['session_location'] = $session_location;
                        $replacement_array['session_date'] = $session_date;
                        $replacement_array['session_start_time'] = $startam;
                        $replacement_array['session_end_time'] = $endam;
                        log_and_send_templated_email(TRAINING_SESSION_EMAIL_TEMPLATE, $employee['email'], $replacement_array);
                    }
                } else {
                    if (!empty($employees_assigned_sid)) {
                        foreach ($employees_assigned_sid as $sid) {
                            $employee = $this->learning_center_model->get_specific_employee($sid);
                            $event_data = array();
                            $event_data['companys_sid'] = $company_sid;
                            $event_data['employers_sid'] = $employer_sid;
                            $event_data['applicant_email'] = $employee[0]['email'];
                            $event_data['applicant_job_sid'] = $employee[0]['sid'];
                            $event_data['users_type'] = 'employee';
                            $event_data['title'] = $session_topic;
                            $event_data['category'] = 'training-session';
                            $event_data['date'] = DateTime::createFromFormat('m-d-Y', $session_date)->format('Y-m-d');
                            $event_data['description'] = $session_description;
                            $event_data['eventstarttime'] = $startam;
                            $event_data['eventendtime'] = $endam;
                            $event_data['interviewer'] = NULL;
                            $event_data['address'] = $session_location;
                            $event_data['created_on'] = date('Y-m-d H:i:s');
                            $event_data['learning_center_training_sessions'] = $session_sid;
                            $insert_sid = $this->learning_center_model->insert_schedule_event($event_data);
                            $event_details[$insert_sid] = 'training-session';
                            //to-do Make template for email
                            $replacement_array['message'] = 'Hi ' . ucwords($employee[0]['first_name'] . ' ' . $employee[0]['last_name']) . ', New training session has been assigned to you. Following are the details.';
                            $replacement_array['subject'] = ucwords($data['session']['company_detail']['CompanyName']) . ' - New Training Session';
                            $replacement_array['company_name'] = $data['session']['company_detail']['CompanyName'];
                            $replacement_array['session_name'] = $session_topic;
                            $replacement_array['session_description'] = $session_description;
                            $replacement_array['session_location'] = $session_location;
                            $replacement_array['session_date'] = $session_date;
                            $replacement_array['session_start_time'] = $startam;
                            $replacement_array['session_end_time'] = $endam;
                            log_and_send_templated_email(TRAINING_SESSION_EMAIL_TEMPLATE, $employee[0]['email'], $replacement_array);
                        }
                    }

                    if (!in_array($employer_sid, $employees_assigned_sid)) { // For logged in person.
                        $event_data = array();
                        $event_data['companys_sid'] = $company_sid;
                        $event_data['employers_sid'] = $employer_sid;
                        $event_data['applicant_email'] = $data['session']['employer_detail']['email'];
                        $event_data['applicant_job_sid'] = $employer_sid;
                        $event_data['users_type'] = 'employee';
                        $event_data['title'] = $session_topic;
                        $event_data['category'] = 'training-session';
                        $event_data['date'] = DateTime::createFromFormat('m-d-Y', $session_date)->format('Y-m-d');
                        $event_data['description'] = $session_description;
                        $event_data['eventstarttime'] = $startam;
                        $event_data['eventendtime'] = $endam;
                        $event_data['interviewer'] = NULL;
                        $event_data['address'] = $session_location;
                        $event_data['created_on'] = date('Y-m-d H:i:s');
                        $event_data['learning_center_training_sessions'] = $session_sid;
                        $insert_sid = $this->learning_center_model->insert_schedule_event($event_data);
                        $event_details[$insert_sid] = 'training-session';
                        $replacement_array['message'] = 'Hi ' . ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']) . ', New training session has been assigned to you. Following are the details.';
                        $replacement_array['subject'] = ucwords($data['session']['company_detail']['CompanyName']) . ' - New Training Session';
                        $replacement_array['company_name'] = $data['session']['company_detail']['CompanyName'];
                        $replacement_array['session_name'] = $session_topic;
                        $replacement_array['session_description'] = $session_description;
                        $replacement_array['session_location'] = $session_location;
                        $replacement_array['session_date'] = $session_date;
                        $replacement_array['session_start_time'] = $startam;
                        $replacement_array['session_end_time'] = $endam;
                        log_and_send_templated_email(TRAINING_SESSION_EMAIL_TEMPLATE, $data['session']['employer_detail']['email'], $replacement_array);
                    }
                }

                $this->learning_center_model->update_training_session_record($session_sid, array('portal_schedule_event_details' => serialize($event_details)));

                if (!empty($employees_assigned_sid)) {

                    if (!in_array($employer_sid, $employees_assigned_sid)) {
                        $employees_assigned_sid[] = $employer_sid;
                    }

                    foreach ($employees_assigned_sid as $sid) {
                        $last_active_assignment = $this->get_assignment_record($last_active_assignments, 'employee', $sid);
                        $data_to_insert = array();
                        $data_to_insert['training_session_sid'] = $session_sid;
                        $data_to_insert['user_type'] = 'employee';
                        $data_to_insert['user_sid'] = $sid;
                        $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                        $data_to_insert['status'] = 1;

                        if (!empty($last_active_assignment)) {
                            $data_to_insert['attended'] = $last_active_assignment['attended'];
                            $data_to_insert['date_attended'] = $last_active_assignment['date_attended'];
                            $data_to_insert['attend_status'] = $last_active_assignment['attend_status'];
                        }
                        $this->learning_center_model->check_and_assigned_video_record($video_sids, 'employee', $sid, $company_sid); //Add Videos Record for this training session (if video assigned)

                        $this->learning_center_model->insert_training_session_assignment_record($data_to_insert);
                    }
                }

                if (!empty($applicants_assigned_sid)) {
                    foreach ($applicants_assigned_sid as $sid) {
                        $last_active_assignment = $this->get_assignment_record($last_active_assignments, 'applicant', $sid);
                        $data_to_insert = array();
                        $data_to_insert['training_session_sid'] = $session_sid;
                        $data_to_insert['user_type'] = 'applicant';
                        $data_to_insert['user_sid'] = $sid;
                        $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                        $data_to_insert['status'] = 1;

                        if (!empty($last_active_assignment)) {
                            $data_to_insert['attended'] = $last_active_assignment['attended'];
                            $data_to_insert['date_attended'] = $last_active_assignment['date_attended'];
                            $data_to_insert['attend_status'] = $last_active_assignment['attend_status'];
                        }

                        $this->learning_center_model->insert_training_session_assignment_record($data_to_insert);
                    }
                }

                $this->session->set_flashdata('message', '<strong>Success: </strong> Training Session Successfully Saved');
                redirect('learning_center/training_sessions', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function edit_training_session($session_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'edit_training_session'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['title'] = 'Learning Center - Edit Training Session';

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            $training_session = $this->learning_center_model->get_training_session($session_sid);

            if (sizeof($training_session) == 0) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Session not found');
                redirect(base_url('learning_center/training_sessions'), "refresh");
            } else {
                if ($training_session['company_sid'] != $company_sid) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> Unauthorized Access!');
                    redirect(base_url('learning_center/training_sessions'), "refresh");
                }
            }

            $data['training_session'] = $training_session;
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');
            $employees = $this->learning_center_model->get_all_employees($company_sid);
            $videos = $this->learning_center_model->get_all_online_videos($company_sid);
            $data['videos'] = $videos;
            $selected_employees = array();
            $data['selected_videos'] = array();

            if ($training_session['online_video_sid'] != NULL) {
                $data['selected_videos'] = explode(',', $training_session['online_video_sid']);
            }

            if (isset($training_session['employees'])) {
                foreach ($training_session['employees'] as $employee) {
                    if (!in_array($employee['user_sid'], $selected_employees)) {
                        $selected_employees[] = $employee['user_sid'];
                    }
                }
            }

            if ($this->form_validation->run() == false) {
                $data['employees'] = $employees;
                $applicants = $this->learning_center_model->get_all_onboarding_applicants($company_sid);
                $data['applicants'] = $applicants;
                $data['selected_employees'] = $selected_employees;
                $selected_applicants = array();

                if (isset($training_session['applicants'])) {
                    foreach ($training_session['applicants'] as $applicant) {
                        if (!in_array($applicant['user_sid'], $selected_applicants)) {
                            $selected_applicants[] = $applicant['user_sid'];
                        }
                    }
                }

                $data['selected_applicants'] = $selected_applicants;
                $data['session_sid'] = $session_sid;
                $this->load->view('main/header', $data);
                $this->load->view('learning_center/training_session_add_ajax');
                // $this->load->view('learning_center/training_session_add');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                $company_sid = $this->input->post('company_sid');
                $employer_sid = $this->input->post('employer_sid');
                $session_topic = $this->input->post('session_topic');
                $session_description = $this->input->post('session_description');
                $session_location = $this->input->post('session_location');
                $session_date = $this->input->post('session_date');
                $session_start_time = $this->input->post('session_start_time');
                $session_end_time = $this->input->post('session_end_time');
                $employees_assigned_to = $this->input->post('employees_assigned_to');
                $employees_assigned_sid = $this->input->post('employees_assigned_sid');
                $applicants_assigned_to = $this->input->post('applicants_assigned_to');
                $applicants_assigned_sid = $this->input->post('applicants_assigned_sid');
                $form_change = $this->input->post('form-change');
                $online_video_sid = $this->input->post('online_video_sid');
                $online_video_sid = implode(',', $online_video_sid);
                $startam = date('h:i A', strtotime($session_start_time));
                $endam = date('h:i A', strtotime($session_end_time));
                $data_to_update = array();
                $data_to_update['company_sid'] = $company_sid;
                $data_to_update['created_by'] = $employer_sid;
                $data_to_update['session_topic'] = $session_topic;
                $data_to_update['session_description'] = $session_description;
                $data_to_update['session_location'] = $session_location;
                $data_to_update['session_date'] = DateTime::createFromFormat('m-d-Y', $session_date)->format('Y-m-d');
                $data_to_update['session_start_time'] = $session_start_time;
                $data_to_update['session_end_time'] = $session_end_time;
                $data_to_update['employees_assigned_to'] = $employees_assigned_to;
                $data_to_update['applicants_assigned_to'] = $applicants_assigned_to;
                $data_to_update['online_video_sid'] = $online_video_sid;
                $this->learning_center_model->update_training_session_record($session_sid, $data_to_update);
                $last_active_assignments = $this->learning_center_model->get_last_active_training_session_assignments($session_sid);
                $this->learning_center_model->set_training_session_assignment_status($session_sid);
                $event_details = unserialize($training_session['portal_schedule_event_details']);
                $event_ids = array_keys($event_details);
                $updated_event_ids = array();

                if ($employees_assigned_to == 'all') {
                    foreach ($employees as $employee) {
                        $event_data = array();
                        $event_data['companys_sid'] = $company_sid;
                        $event_data['employers_sid'] = $employer_sid;
                        $event_data['applicant_email'] = $employee['email'];
                        $event_data['applicant_job_sid'] = $employee['sid'];
                        $event_data['users_type'] = 'employee';
                        $event_data['title'] = $session_topic;
                        $event_data['category'] = 'training-session';
                        $event_data['date'] = DateTime::createFromFormat('m-d-Y', $session_date)->format('Y-m-d');
                        $event_data['description'] = $session_description;
                        $event_data['eventstarttime'] = $startam;
                        $event_data['eventendtime'] = $endam;
                        $event_data['address'] = $session_location;
                        $event_data['learning_center_training_sessions'] = $session_sid;
                        $return_id = $this->learning_center_model->update_schedule_event($event_ids, $employee['sid'], $event_data);
                        $updated_event_ids[$return_id['id']] = $return_id['category'];

                        if ($employees_assigned_to != $training_session['employees_assigned_to']) {

                            if (!in_array($employee['sid'], $selected_employees)) { //New Employee Added
                                $replacement_array['message'] = 'Hi ' . ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ', New training session has been assigned to you. Following are the details.';
                                $replacement_array['subject'] = ucwords($data['session']['company_detail']['CompanyName']) . ' - Training Session';
                                $replacement_array['company_name'] = $data['session']['company_detail']['CompanyName'];
                                $replacement_array['session_name'] = $session_topic;
                                $replacement_array['session_description'] = $session_description;
                                $replacement_array['session_location'] = $session_location;
                                $replacement_array['session_date'] = $session_date;
                                $replacement_array['session_start_time'] = $startam;
                                $replacement_array['session_end_time'] = $endam;
                                log_and_send_templated_email(TRAINING_SESSION_EMAIL_TEMPLATE, $employee['email'], $replacement_array);
                            } else { //Old One So Send Updated Event Details Only
                                if ($form_change == 1) {
                                    $replacement_array['message'] = 'Hi ' . ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ', ' . $session_topic . ' has been changed.';
                                    $replacement_array['subject'] = ucwords($data['session']['company_detail']['CompanyName']) . ' - Training Session';
                                    $replacement_array['company_name'] = $data['session']['company_detail']['CompanyName'];
                                    $replacement_array['session_name'] = $session_topic;
                                    $replacement_array['session_description'] = $session_description;
                                    $replacement_array['session_location'] = $session_location;
                                    $replacement_array['session_date'] = $session_date;
                                    $replacement_array['session_start_time'] = $startam;
                                    $replacement_array['session_end_time'] = $endam;
                                    log_and_send_templated_email(TRAINING_SESSION_EMAIL_TEMPLATE, $employee['email'], $replacement_array);
                                }
                            }
                        } else { //Old One So Send Updated Event Details Only
                            if ($form_change == 1) {
                                $replacement_array['message'] = 'Hi ' . ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ', ' . $session_topic . ' has been changed.';
                                $replacement_array['subject'] = ucwords($data['session']['company_detail']['CompanyName']) . ' - Training Session';
                                $replacement_array['company_name'] = $data['session']['company_detail']['CompanyName'];
                                $replacement_array['session_name'] = $session_topic;
                                $replacement_array['session_description'] = $session_description;
                                $replacement_array['session_location'] = $session_location;
                                $replacement_array['session_date'] = $session_date;
                                $replacement_array['session_start_time'] = $startam;
                                $replacement_array['session_end_time'] = $endam;
                                log_and_send_templated_email(TRAINING_SESSION_EMAIL_TEMPLATE, $employee['email'], $replacement_array);
                            }
                        }
                    }
                } else {
                    if (!empty($employees_assigned_sid)) {
                        foreach ($employees_assigned_sid as $id) {
                            $employee = $this->learning_center_model->get_specific_employee($id);
                            $event_data = array();
                            $event_data['companys_sid'] = $company_sid;
                            $event_data['employers_sid'] = $employer_sid;
                            $event_data['applicant_email'] = $employee[0]['email'];
                            $event_data['applicant_job_sid'] = $employee[0]['sid'];
                            $event_data['users_type'] = 'employee';
                            $event_data['title'] = $session_topic;
                            $event_data['category'] = 'training-session';
                            $event_data['date'] = DateTime::createFromFormat('m-d-Y', $session_date)->format('Y-m-d');
                            $event_data['description'] = $session_description;
                            $event_data['eventstarttime'] = $startam;
                            $event_data['eventendtime'] = $endam;
                            $event_data['address'] = $session_location;
                            $event_data['learning_center_training_sessions'] = $session_sid;
                            $return_id = $this->learning_center_model->update_schedule_event($event_ids, $id, $event_data);
                            $updated_event_ids[$return_id['id']] = $return_id['category'];

                            if (!in_array($id, $selected_employees)) {
                                $last_active_assignment = $this->get_assignment_record($last_active_assignments, 'employee', $id);
                                $data_to_insert = array();
                                $data_to_insert['training_session_sid'] = $session_sid;
                                $data_to_insert['user_type'] = 'employee';
                                $data_to_insert['user_sid'] = $id;
                                $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                                $data_to_insert['status'] = 1;

                                if (!empty($last_active_assignment)) {
                                    $data_to_insert['attended'] = $last_active_assignment['attended'];
                                    $data_to_insert['date_attended'] = $last_active_assignment['date_attended'];
                                    $data_to_insert['attend_status'] = $last_active_assignment['attend_status'];
                                }

                                $this->learning_center_model->insert_training_session_assignment_record($data_to_insert);
                                //Check if previous assign was all then don't send new applicant email, only send updated event email
                                if ($employees_assigned_to != $training_session['employees_assigned_to']) {

                                    if ($form_change == 1) {
                                        $replacement_array['message'] = 'Hi ' . ucwords($employee[0]['first_name'] . ' ' . $employee[0]['last_name']) . ', ' . $session_topic . ' has been changed.';
                                        $replacement_array['subject'] = ucwords($data['session']['company_detail']['CompanyName']) . ' - Training Session';
                                        $replacement_array['company_name'] = $data['session']['company_detail']['CompanyName'];
                                        $replacement_array['session_name'] = $session_topic;
                                        $replacement_array['session_description'] = $session_description;
                                        $replacement_array['session_location'] = $session_location;
                                        $replacement_array['session_date'] = $session_date;
                                        $replacement_array['session_start_time'] = $startam;
                                        $replacement_array['session_end_time'] = $endam;
                                        log_and_send_templated_email(TRAINING_SESSION_EMAIL_TEMPLATE, $employee[0]['email'], $replacement_array);
                                    }
                                } else {
                                    $replacement_array['message'] = 'Hi ' . ucwords($employee[0]['first_name'] . ' ' . $employee[0]['last_name']) . ', New training session has been assigned to you. Following are the details.';
                                    $replacement_array['subject'] = ucwords($data['session']['company_detail']['CompanyName']) . ' - Training Session';
                                    $replacement_array['company_name'] = $data['session']['company_detail']['CompanyName'];
                                    $replacement_array['session_name'] = $session_topic;
                                    $replacement_array['session_description'] = $session_description;
                                    $replacement_array['session_location'] = $session_location;
                                    $replacement_array['session_date'] = $session_date;
                                    $replacement_array['session_start_time'] = $startam;
                                    $replacement_array['session_end_time'] = $endam;
                                    log_and_send_templated_email(TRAINING_SESSION_EMAIL_TEMPLATE, $employee[0]['email'], $replacement_array);
                                }
                            } else {
                                $this->learning_center_model->update_notInList_session_assignment($session_sid, $id);

                                if ($form_change == 1) {
                                    $replacement_array['message'] = 'Hi ' . ucwords($employee[0]['first_name'] . ' ' . $employee[0]['last_name']) . ', ' . $session_topic . ' has been changed.';
                                    $replacement_array['subject'] = ucwords($data['session']['company_detail']['CompanyName']) . ' - Training Session';
                                    $replacement_array['company_name'] = $data['session']['company_detail']['CompanyName'];
                                    $replacement_array['session_name'] = $session_topic;
                                    $replacement_array['session_description'] = $session_description;
                                    $replacement_array['session_location'] = $session_location;
                                    $replacement_array['session_date'] = $session_date;
                                    $replacement_array['session_start_time'] = $startam;
                                    $replacement_array['session_end_time'] = $endam;
                                    log_and_send_templated_email(TRAINING_SESSION_EMAIL_TEMPLATE, $employee[0]['email'], $replacement_array);
                                }
                            }
                        }
                    }
                }

                foreach ($event_ids as $id) {
                    if (!in_array($id, array_keys($updated_event_ids))) {
                        $this->learning_center_model->delete_schedule_event($id);
                    }
                }

                $this->learning_center_model->update_training_session_record($session_sid, array('portal_schedule_event_details' => serialize($updated_event_ids)));

                if (!empty($applicants_assigned_sid)) {
                    foreach ($applicants_assigned_sid as $sid) {
                        $last_active_assignment = $this->get_assignment_record($last_active_assignments, 'applicant', $sid);
                        $data_to_insert = array();
                        $data_to_insert['training_session_sid'] = $session_sid;
                        $data_to_insert['user_type'] = 'applicant';
                        $data_to_insert['user_sid'] = $sid;
                        $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                        $data_to_insert['status'] = 1;

                        if (!empty($last_active_assignment)) {
                            $data_to_insert['attended'] = $last_active_assignment['attended'];
                            $data_to_insert['date_attended'] = $last_active_assignment['date_attended'];
                            $data_to_insert['attend_status'] = $last_active_assignment['attend_status'];
                        }

                        $this->learning_center_model->insert_training_session_assignment_record($data_to_insert);
                    }
                }

                $this->session->set_flashdata('message', '<strong>Success: </strong> Training Session Successfully Updated');
                redirect('learning_center/training_sessions', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function ajax_responder() {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

        if ($this->form_validation->run() == false) {
            //Handle Get
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'update_session_status':
                    $session_sid = $this->input->post('session_sid');
                    $session_status = $this->input->post('session_status');
                    $this->learning_center_model->set_training_session_status($session_sid, $session_status);
                    $this->learning_center_model->set_event_status_by_lc($session_sid, $session_status);
                    echo 'success';
                break;
                case 'delete_training_session':
                    // POST
                    $session_sid = $this->input->post('session_sid');
                    $this->learning_center_model->delete_training_session($session_sid);
                    $this->learning_center_model->delete_event_by_training_session_id($session_sid);
                    //
                    echo 'success';
                break;
            }
        }
    }

    function my_learning_center($app_id = NULL, $job_list_sid = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'learning_center', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            if ($app_id != NULL && $job_list_sid != NULL) {
                $ats_params = $this->session->userdata('ats_params');
                $data = applicant_right_nav($app_id, $job_list_sid, $ats_params);
                $employer_id = $data['applicant_info']['employer_sid'];

                if ($company_sid != $employer_id) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Applicant not found!');
                    redirect('application_tracking_system/active/all/all/all/all');
                }

                $employer_sid = $app_id;
                $data['left_navigation'] = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['watch_url'] = '/' . $app_id . '/' . $job_list_sid;
                $data['top_view'] = false;
                $load_view = check_blue_panel_status(true, 'self');
                $user_type = 'applicant';
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($app_id, 'applicant');
            } elseif ($app_id != NULL) {
                $this->load->model('dashboard_model');
                $employer_sid = $app_id;
                $data = employee_right_nav($employer_sid);
                $data['top_view'] = true;
                $data['employer'] = $this->dashboard_model->get_company_detail($employer_sid);
                $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['watch_url'] = '/' . $app_id;
                $load_view = check_blue_panel_status(true, 'self');
                $user_type = 'employee';
            } else {
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;
                $data['top_view'] = true;
                $data['employer'] = $data["session"]["employer_detail"];
                $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['watch_url'] = '';
                $data['employee'] = $data['session']['employer_detail'];
                $load_view = check_blue_panel_status(false, 'self');
                $user_type = 'employee';
            }

            $data['user_type'] = $user_type;
            $data['employer_sid'] = $employer_sid;

            $data['title'] = 'Learning Center';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            if (!$this->form_validation->run()) {
                $data['video_list'] = $this->learning_center_model->get_video_list($company_sid);
                $videos = $this->learning_center_model->get_my_all_online_videos($user_type, $employer_sid, $company_sid, $app_id == NULL ? false : true);
                //
                $pendingVideo = 0;
                //
                foreach ($videos as $video) {
                    if ($video['video_watched_status'] == 'pending') {
                        $pendingVideo++;
                    }
                }
                //
                $data['pendingVideo'] = $pendingVideo;
                $data['videos'] = $videos;
                $data['history'] = $this->learning_center_model->get_video_history($user_type, $employer_sid, $company_sid, $app_id == NULL ? false : true);
                $assigned_sessions = $this->learning_center_model->get_assigned_all_training_sessions($user_type, $employer_sid, $company_sid);
                $pendingSessions = 0;
                //
                foreach ($assigned_sessions as $session) {
                    if ($session['session_status'] == 'pending') {
                        $pendingSessions++;
                    }
                }
                $data['load_view'] = $load_view;
                $data['assigned_sessions'] = $assigned_sessions;
                $data['pendingSessions'] = $pendingSessions;
                $this->load->view('main/header', $data);
                $this->load->view('learning_center/my_learning_center');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'mark_attend_status':
                        $session_assignment_sid = $this->input->post('session_assignment_sid');
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        $attend_status = $this->input->post('attend_status');
                        $this->learning_center_model->update_attend_status($user_type, $user_sid, $session_assignment_sid, $attend_status);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Status Updated!');
                        redirect('learning_center/my_learning_center', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    function watch_video($video_sid = NULL, $app_id = NULL, $job_list_sid = NULL) {
        if ($video_sid != NULL) {
            if ($this->session->userdata('logged_in')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                //check_access_permissions($security_details, 'learning_center', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;

                if (!check_company_ems_status($company_sid)) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                    redirect(base_url('dashboard'), 'refresh');
                }

                if ($app_id != NULL && $job_list_sid != NULL) {
                    $ats_params = $this->session->userdata('ats_params');
                    $data = applicant_right_nav($app_id, $job_list_sid, $ats_params);
                    $employer_id = $data['applicant_info']['employer_sid'];

                    if ($company_sid != $employer_id) {
                        $this->session->set_flashdata('message', '<b>Error:</b> Applicant not found!');
                        redirect('application_tracking_system/active/all/all/all/all');
                    }

                    $employer_sid = $app_id;
                    $data['left_navigation'] = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $data['back_url'] = base_url('learning_center/my_learning_center/') . '/' . $app_id . '/' . $job_list_sid;
                    $data['watch_url'] = '/' . $app_id . '/' . $job_list_sid;
                    $data['top_view'] = false;
                    $load_view = check_blue_panel_status(true, 'self');
                    $user_type = 'applicant';
                } elseif ($app_id != NULL) {
                    $this->load->model('dashboard_model');
                    $employer_sid = $app_id;
                    $data = employee_right_nav($employer_sid);
                    $data['top_view'] = true;
                    $data['employer'] = $this->dashboard_model->get_company_detail($employer_sid);
                    $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data['back_url'] = base_url('learning_center/my_learning_center/') . '/' . $app_id;
                    $data['watch_url'] = '/' . $app_id;
                    $load_view = check_blue_panel_status(true, 'self');
                    $user_type = 'employee';
                } else {
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;
                    $data['top_view'] = true;
                    $data['employer'] = $data['session']['employer_detail'];
                    $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_personal';
                    $data['back_url'] = base_url('learning_center/my_learning_center/');
                    $data['watch_url'] = '';
                    $data['employee'] = $data['session']['employer_detail'];
                    $load_view = check_blue_panel_status(false, 'self');
                    $user_type = 'employee';
                }

                $data['title'] = 'My Learning Center - Watch Video';
                $data['employee'] = $data['session']['employer_detail'];
                $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');
                $video = $this->learning_center_model->get_single_online_video($video_sid, $company_sid);
                $assignment = $this->learning_center_model->get_video_assignment($user_type, $employer_sid, $video_sid, $company_sid);
                $data['attempt_status'] = $assignment['attempt_status'];

                if ($assignment['attempt_status']) {
                    $data['attempted_questionnaire'] = $attempted_questionnaire = $this->learning_center_model->get_video_questionnaire_attempt($video_sid, $assignment['sid']);
                    $data['attempted_questionnaire_timestamp'] = $attempted_questionnaire[0]['attend_timestamp'];
                    $data['questionnaire_result'] = $attempted_questionnaire[0]['questionnaire_result'];
                }

                if ($this->form_validation->run() == false) {
                    $data['job_details'] = array();

                    if ($video['screening_questionnaire_sid'] > 0) {
                        $portal_screening_questionnaires = $this->learning_center_model->get_screening_questionnaire_by_id($video['screening_questionnaire_sid']);
                        $questionnaire_name = $portal_screening_questionnaires[0]['name'];
                        $list['q_name'] = $portal_screening_questionnaires[0]['name'];
                        $list['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                        $list['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                        $list['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                        $list['q_pass_text'] = ''; //$portal_screening_questionnaires[0]['email_text_pass'];
                        $list['q_fail_text'] = ''; //$portal_screening_questionnaires[0]['email_text_fail'];
                        $list['my_id'] = 'q_question_' . $video['screening_questionnaire_sid'];
                        $screening_questions_numrows = $this->learning_center_model->get_screenings_count_by_id($video['screening_questionnaire_sid']);

                        if ($screening_questions_numrows > 0) {
                            $screening_questions = $this->learning_center_model->get_screening_questions_by_id($video['screening_questionnaire_sid']);

                            foreach ($screening_questions as $qkey => $qvalue) {
                                $questions_sid = $qvalue['sid'];
                                $list['q_question_' . $video['screening_questionnaire_sid']][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                $screening_answers_numrows = $this->learning_center_model->get_screening_answer_count_by_id($questions_sid);

                                if ($screening_answers_numrows) {
                                    $screening_answers = $this->learning_center_model->get_screening_answers_by_id($questions_sid);

                                    foreach ($screening_answers as $akey => $avalue) {
                                        $list['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                    }
                                }
                            }
                        }

                        $data['job_details'] = $list;
                    }
                    //
                    $data['answers_given'] = [];
                    //
                    if(!empty($data['attempted_questionnaire'] )){
                        foreach($data['attempted_questionnaire'] as $dd){
                            $data['answers_given'][] = unserialize($dd['questionnaire']);
                        }
                    }

                    $data['video'] = $video;

                    if ($video['video_source'] = 'upload') {
                        $video_url = base_url() . 'assets/uploaded_videos/' . $video['video_id'];
                        $data['video_url'] = $video_url;
                    }

                    if ($assignment == 0) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Video not found!');
                        redirect('learning_center/my_learning_center', 'refresh');
                    }

                    $attachments = $this->learning_center_model->get_attached_document($video_sid);
                    $data['supported_documents'] = $attachments;
                    $data['assignment'] = $assignment;
                    $data['load_view'] = $load_view;
                    $data['user_type'] = $user_type;
                    $data['employer_sid'] = $employer_sid;
                    $this->load->view('main/header', $data);
                    $this->load->view('learning_center/watch_video');
                    $this->load->view('main/footer');
                } else {
                    if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'questionnaire') {
                        $post_screening_questionnaires = $this->learning_center_model->get_screening_questionnaire_by_id($video['screening_questionnaire_sid']);
                        $array_questionnaire = array();
                        $questionnaire_name = $post_screening_questionnaires[0]['name'];
                        $q_name = $post_screening_questionnaires[0]['name'];
                        $q_send_pass = $post_screening_questionnaires[0]['auto_reply_pass'];
                        $q_pass_text = $post_screening_questionnaires[0]['email_text_pass'];
                        $q_send_fail = $post_screening_questionnaires[0]['auto_reply_fail'];
                        $q_fail_text = $post_screening_questionnaires[0]['email_text_fail'];
                        $all_questions_ids = $_POST['all_questions_ids'];
                        $questionnaire_serialize = '';
                        $total_score = 0;
                        $total_questionnaire_score = 0;
                        $q_passing = 0;
                        $array_questionnaire = array();
                        $overall_status = 'Pass';
                        $is_string = 0;
                        $screening_questionnaire_results = array();

                        foreach ($all_questions_ids as $key => $value) {
                            $q_passing = 0;
                            $post_questions_sid = $value;
                            $caption = 'caption' . $value;
                            $type = 'type' . $value;
                            $answer = $_POST[$type] . $value;
                            $questions_type = $_POST[$type];
                            $my_question = '';
                            $individual_score = 0;
                            $individual_passing_score = 0;
                            $individual_status = 'Pass';
                            $result_status = array();

                            if (isset($_POST[$caption])) {
                                $my_question = $_POST[$caption];
                            }

                            $my_answer = NULL;

                            if (isset($_POST[$answer])) {
                                $my_answer = $_POST[$answer];
                            }

                            if ($questions_type != 'string') { // get the question possible score
                                $q_passing = $this->learning_center_model->get_possible_score_of_questions($post_questions_sid, $questions_type);
                            }

                            if ($my_answer != NULL) { // It is required question
                                if (is_array($my_answer)) {
                                    $answered = array();
                                    $answered_result_status = array();
                                    $answered_question_score = array();
                                    $total_questionnaire_score += $q_passing;
                                    $is_string = 1;

                                    foreach ($my_answer as $answers) {
                                        $result = explode('@#$', $answers);
                                        $a = $result[0];
                                        $answered_question_sid = $result[1];
                                        $question_details = $this->learning_center_model->get_individual_question_details($answered_question_sid);

                                        if (!empty($question_details)) {
                                            $questions_score = $question_details['score'];
                                            $questions_result_status = $question_details['result_status'];
                                            $questions_result_value = $question_details['value'];
                                        }

                                        $score = $questions_score;
                                        $total_score += $questions_score;
                                        $individual_score += $questions_score;
                                        $individual_passing_score = $q_passing;
                                        $answered[] = $a;
                                        $result_status[] = $questions_result_status;
                                        $answered_result_status[] = $questions_result_status;
                                        $answered_question_score[] = $questions_score;
                                    }
                                } else { // hassan WORKING area
                                    // http://localhost/automotoCI/Job_screening_questionnaire/dbNfdVmyKEIy4f4iCBajfcSNHMaJWumS9uzLzGHi7iAzBnUKwTyZQaYGdGmmxCsm0Vg4BGKZ
                                    $result = explode('@#$', $my_answer);
                                    $total_questionnaire_score += $q_passing;
                                    $a = $result[0];
                                    $answered = $a;
                                    $answered_result_status = '';
                                    $answered_question_score = 0;

                                    if (isset($result[1])) {
                                        $answered_question_sid = $result[1];
                                        $question_details = $this->learning_center_model->get_individual_question_details($answered_question_sid);

                                        if (!empty($question_details)) {
                                            $questions_score = $question_details['score'];
                                            $questions_result_status = $question_details['result_status'];
                                            $questions_result_value = $question_details['value'];
                                        }

                                        $is_string = 1;
                                        $score = $questions_score;
                                        $total_score += $questions_score;
                                        $individual_score += $questions_score;
                                        $individual_passing_score = $q_passing;
                                        $result_status[] = $questions_result_status;
                                        $answered_result_status = $questions_result_status;
                                        $answered_question_score = $questions_score;
                                    }
                                }

                                if (!empty($result_status)) {
                                    if (in_array('Fail', $result_status)) {
                                        $individual_status = 'Fail';
                                        $overall_status = 'Fail';
                                    }
                                }
                            } else { // it is optional question
                                $answered = '';
                                $individual_passing_score = $q_passing;
                                $individual_score = 0;
                                $individual_status = 'Candidate did not answer the question';
                                $answered_result_status = '';
                                $answered_question_score = 0;
                            }

                            $array_questionnaire[$my_question] = array('answer' => $answered,
                                'passing_score' => $individual_passing_score,
                                'score' => $individual_score,
                                'status' => $individual_status,
                                'answered_result_status' => $answered_result_status,
                                'answered_question_score' => $answered_question_score);
                            //echo '<pre>'; print_r($array_questionnaire); echo '</pre><hr>';
                        } // here

                        $questionnaire_result = $overall_status;
                        $datetime = date('Y-m-d H:i:s');
                        $remote_addr = getUserIP();
                        $user_agent = $_SERVER['HTTP_USER_AGENT'];
                        $array_questionnaire_serialize = serialize($array_questionnaire);
                        //echo '<pre>'; print_r($questionnaire_data); echo '</pre><hr>';
                        $screening_questionnaire_results = array('video_sid' => $video_sid,
                            'video_assign_sid' => $assignment['sid'],
                            'video_title' => $video['video_title'],
                            'company_sid' => $company_sid,
                            'questionnaire_name' => $questionnaire_name,
                            'questionnaire' => $array_questionnaire_serialize,
                            'questionnaire_result' => $questionnaire_result,
                            'attend_timestamp' => $datetime,
                            'questionnaire_ip_address' => $remote_addr,
                            'questionnaire_user_agent' => $user_agent);
                        //echo '<br>update_questionnaire_result: '.$this->db->last_query();
                        $this->learning_center_model->insert_questionnaire_result($screening_questionnaire_results);
                        $this->learning_center_model->update_video_attempt_status($user_type, $employer_sid, $video_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Questionnaire Saved Successfully');
                        if(isset($_POST['session_sid']) && $_POST['session_sid']){
                            redirect('learning_center/view_training_session/' . $_POST['session_sid']);
                        }
                        redirect('learning_center/watch_video/' . $video_sid);
                    }
                    //
                    $post = $this->input->post(NULL, TRUE);
                    //
                    if($post['perform_action'] == 'mark_video_as_watched'){
                        $this->learning_center_model->setVideoAsWatched(
                            $post['user_type'],
                            $post['user_sid'],
                            $post['video_sid']
                        );
                    }
                    //
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Video marked as watched!');
                    redirect($data['back_url']);
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Warning:</strong> Please Login to your account to continue!');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Video not found!');
            redirect('learning_center/my_learning_center', 'refresh');
        }
    }

    function track_youtube($str) {
        $user_sid = $this->input->post('id');
        $user_type = $this->input->post('type');
        $video_sid = $this->input->post('v_id');
        $video_duration = $this->input->post('v_duration');
        $video_completed = $this->input->post('v_completed');
        $isWatched = $this->learning_center_model->get_video_assignment($user_type, $user_sid, $video_sid);

        if ($isWatched['watched'] == 0 && $isWatched['completed'] == 0) {
            $this->learning_center_model->update_video_completed_status($user_type, $user_sid, $video_sid, $video_duration);
            return date('m-d-Y h:i A');
        } elseif ($isWatched['watched'] == 1 && $isWatched['completed'] == 0 && $video_completed == 0) {
            $this->learning_center_model->update_video_watched_duration($user_type, $user_sid, $video_sid, $video_duration);
        } elseif ($isWatched['watched'] == 1 && $isWatched['completed'] == 0 && $video_completed == 1) {
            $this->learning_center_model->update_video_watched_completed($user_type, $user_sid, $video_sid, $video_duration);
        }
    }

    function validate_youtube($str) {
        if ($this->session->userdata('logged_in')) {
            if ($str != "") {
                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $str, $matches);
                if (!isset($matches[0])) { //if validation not passed
                    $this->form_validation->set_message('validate_youtube', 'Invalid youtube video url.');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Youtube video URL');
                    return FALSE;
                } else { //if validation passed
                    return TRUE;
                }
            } else {
                return true;
            }
        }
    }

    function validate_vimeo() {
        $str = $this->input->post('url');
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        echo TRUE;
                    } else {
                        $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                        $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                        echo FALSE;
                    }
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    echo FALSE;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    echo TRUE;
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    echo FALSE;
                }
            }
        } else {
            echo FALSE;
        }
    }

    public function vimeo_get_id($str) {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return $response['video_id'];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return $response['video_id'];
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    public function view_training_session($session_id, $app_id = NULL, $job_list_sid = NULL) {
        if ($session_id != NULL) {
            if ($this->session->userdata('logged_in')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                //check_access_permissions($security_details, 'learning_center', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;

                if (!check_company_ems_status($company_sid)) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                    redirect(base_url('dashboard'), 'refresh');
                }

                $assignment = $this->learning_center_model->get_training_assignment('employee', $employer_sid, $session_id, $company_sid);

                if (sizeof($assignment) == 0) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> Training Session not found!');
                    redirect(base_url('learning_center/my_learning_center'), "refresh");
                } else {
                    if ($assignment[0]['company_sid'] != $company_sid) {
                        // echo '<pre>';
                        // print_r($assignment);
                        // exit;
                        $this->session->set_flashdata('message', '<b>Warning:</b>  Training Session not found!');
                        redirect(base_url('learning_center/my_learning_center'), "refresh");
                    } elseif ($assignment[0]['employees_assigned_to'] == 'specific' && $assignment[0]['status'] == 0) {
                        // echo '<pre>';
                        // print_r($assignment);
                        // echo '</pre>';
                        // exit;
                        $this->session->set_flashdata('message', '<b>Warning:</b> Training Session not found!');
                        redirect(base_url('learning_center/my_learning_center'), "refresh");
                    }
                }

                if ($app_id != NULL && $job_list_sid != NULL) {
                    $ats_params = $this->session->userdata('ats_params');
                    $data = applicant_right_nav($app_id, $job_list_sid, $ats_params);
                    $employer_id = $data['applicant_info']['employer_sid'];

                    if ($company_sid != $employer_id) {
                        $this->session->set_flashdata('message', '<b>Error:</b> Applicant not found!');
                        redirect('application_tracking_system/active/all/all/all/all');
                    }

                    $employer_sid = $app_id;
                    $data['left_navigation'] = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $data['back_url'] = base_url('learning_center/my_learning_center/') . '/' . $app_id . '/' . $job_list_sid;
                    $data['watch_url'] = '/' . $app_id . '/' . $job_list_sid;
                    $data['top_view'] = false;
                    $load_view = check_blue_panel_status(true, 'self');
                    $user_type = 'applicant';
                } elseif ($app_id != NULL) {
                    $this->load->model('dashboard_model');
                    $employer_sid = $app_id;
                    $data = employee_right_nav($employer_sid);
                    $data['top_view'] = true;
                    $data['employer'] = $this->dashboard_model->get_company_detail($app_id);
                    $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data['back_url'] = base_url('learning_center/my_learning_center/') . '/' . $app_id;
                    $data['watch_url'] = '/' . $app_id;
                    $load_view = check_blue_panel_status(true, 'self');
                    $user_type = 'employee';
                } else {
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;
                    $data['top_view'] = true;
                    $data['employer'] = $data['session']['employer_detail'];
                    $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_personal';
                    $data['back_url'] = base_url('learning_center/my_learning_center/');
                    $data['watch_url'] = '';
                    $data['employee'] = $data['session']['employer_detail'];
                    $load_view = check_blue_panel_status(false, 'self');
                    $user_type = 'employee';
                }

                $data['title'] = 'My Learning Center - Training Session';
                $data['employee'] = $data['session']['employer_detail'];
                $data['session_id'] = $session_id;
                $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

                if ($this->form_validation->run() == false) {
                    $video = $this->learning_center_model->get_single_training_session($session_id);
                    $data['video'] = $video;
                    $attend_status = $assignment[0]['attend_status'];

                    switch ($attend_status) {
                        case 'attended':
                        case 'unable_to_attend':
                            $assignment[0]['uta_btn_status'] = 'disabled';
                            $assignment[0]['wa_btn_status'] = 'disabled';
                            $assignment[0]['a_btn_status'] = 'disabled';
                            break;
                        case 'will_attend':
                            $assignment[0]['uta_btn_status'] = 'disabled';
                            $assignment[0]['wa_btn_status'] = 'disabled';
                            $assignment[0]['a_btn_status'] = 'enabled';
                            break;
                        default:
                            $assignment[0]['uta_btn_status'] = 'enabled';
                            $assignment[0]['wa_btn_status'] = 'enabled';
                            $assignment[0]['a_btn_status'] = 'enabled';
                            break;
                    }

                    if (!empty($assignment[0]['online_video_sid'])) {
                        $online_video_sid = explode(',', $assignment[0]['online_video_sid']);
                        $assignment[0]['online_video_sid'] = array();

                        foreach ($online_video_sid as $key => $vid_sid) {
                            $title = $this->learning_center_model->get_video_title($vid_sid);
                            $assignment[0]['online_video_sid'][$vid_sid] = $title;
                        }
                    }

                    $data['assignment'] = $assignment[0];
                    $data['load_view'] = $load_view;
                    $data['user_type'] = $user_type;
                    $data['employer_sid'] = $employer_sid;
                    $this->load->view('main/header', $data);
                    $this->load->view('learning_center/view_training_new');
                    $this->load->view('main/footer');
                } else {
                    $perform_action = $this->input->post('perform_action');

                    switch ($perform_action) {
                        case 'mark_attend_status':
                            $session_assignment_sid = $this->input->post('session_assignment_sid');
                            $user_type = $this->input->post('user_type');
                            $user_sid = $this->input->post('user_sid');
                            $attend_status = $this->input->post('attend_status');
                            $this->learning_center_model->update_attend_status($user_type, $user_sid, $session_assignment_sid, $attend_status);


                            //Portal Event History For status show in calender
                            if($attend_status == 'will_attend'){
                                $attend_status = 'confirmed';
                            }elseif($attend_status == 'unable_to_attend'){
                                $attend_status = 'cannotattend';
                            }else{
                                $attend_status = 'attended';
                            }

                            $user_detail = $this->learning_center_model->get_specific_employee($user_sid);
                            $event_sid = $this->learning_center_model->get_event_sid($session_id);
                            $insert_array = array();
                            $insert_array['event_type'] = $attend_status;
                            $insert_array['event_sid'] = $event_sid;
                            $insert_array['user_sid']  = $user_sid;
                            $insert_array['user_name']  = ucwords($user_detail[0]['first_name'].' '. $user_detail[0]['last_name']);
                            $insert_array['user_email'] = $user_detail[0]['email'];
                            $insert_array['user_type'] = 'interviewer';
                            $insert_array['created_at'] = date('Y-m-d H:i:s');
                            if(isset($assignment[0]['session_date'])){
                                $insert_array['event_date'] = reset_datetime(array(
                                    'datetime' => $assignment[0]['session_date'],
                                    'from_format' => 'Y-m-d',
                                    'format' => 'Y-m-d',
                                    '_this' => $this,
                                    'from_timezone' => $this->input->post('timezone'),
                                    'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
                                ));
                            }


                            if(isset($assignment[0]['session_start_time'])){
                                $insert_array['event_start_time'] = reset_datetime(array(
                                    'datetime' => $assignment[0]['session_start_time'],
                                    'from_format' => 'H:i:s',
                                    'format' => 'h:iA',
                                    '_this' => $this,
                                    'from_timezone' => $this->input->post('timezone'),
                                    'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
                                ));
                            }

                            if(isset($assignment[0]['session_end_time'])){
                                $insert_array['event_end_time'] = reset_datetime(array(
                                    'datetime' => $assignment[0]['session_end_time'],
                                    'from_format' => 'H:i:s',
                                    'format' => 'h:iA',
                                    '_this' => $this,
                                    'from_timezone' => $this->input->post('timezone'),
                                    'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
                                ));
                            }
                            $this->learning_center_model->add_event_history($insert_array);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Status Updated!');
                            redirect(base_url('learning_center/my_learning_center' . $data['watch_url']), 'refresh');
                            break;
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Warning:</strong> Please Login to your account to continue!');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Session not found!');
            redirect('learning_center/my_learning_center', 'refresh');
        }
    }

    function training_session_watch_video($video_sid = NULL, $session_id = NULL) {
        if ($session_id != NULL && $video_sid != NULL) {
            if ($this->session->userdata('logged_in')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                //check_access_permissions($security_details, 'learning_center', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;

                if (!check_company_ems_status($company_sid)) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                    redirect(base_url('dashboard'), 'refresh');
                }

                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;
                $data['top_view'] = true;
                $data['employer'] = $data['session']['employer_detail'];
                $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['back_url'] = base_url('learning_center/my_learning_center/');
                $data['watch_url'] = '';
                $data['employee'] = $data['session']['employer_detail'];
                $load_view = check_blue_panel_status(false, 'self');
                $user_type = 'employee';
                $data['title'] = 'Training Session - Watch Video';
                $data['employee'] = $data['session']['employer_detail'];
                $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

                if ($this->form_validation->run() == false) {
                    $video = $this->learning_center_model->get_single_online_video($video_sid, $company_sid);
                    $data['video'] = $video;

                    if ($video['video_source'] == 'upload') {
                        $video_url = base_url() . 'assets/uploaded_videos/' . $video['video_id'];
                        $data['video_url'] = $video_url;
                        $data_new['video_url'] = $video_url;
                        $data_new['video_id'] = $video['video_id'];
                        $data_new['video_source'] = $video['video_source'];
                        $data_new['video_sid'] = $video['sid'];
                        $data_new['video_title'] = $video['video_title'];
                        $data_new['video_description'] = $video['video_description'];
                    } else {
                        $video_url = '';
                        $data_new['video_id'] = $video['video_id'];
                        $data_new['video_source'] = $video['video_source'];
                        $data_new['video_sid'] = $video['sid'];
                        $data_new['video_title'] = $video['video_title'];
                        $data_new['video_description'] = $video['video_description'];
                    }

                    $assignment = $this->learning_center_model->get_session_video_assignment('employee', $employer_sid, $video_sid, $company_sid, $session_id);

                    if ($assignment == 0) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Video not found!');
                        redirect('learning_center/my_learning_center', 'refresh');
                    }

                    //Added By Adil
                    $data_new['job_details'] = array();

                    if ($video['screening_questionnaire_sid'] > 0) {
                        $portal_screening_questionnaires = $this->learning_center_model->get_screening_questionnaire_by_id($video['screening_questionnaire_sid']);
                        $questionnaire_name = $portal_screening_questionnaires[0]['name'];
                        $list['q_name'] = $portal_screening_questionnaires[0]['name'];
                        $list['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                        $list['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                        $list['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                        $list['q_pass_text'] = ''; //$portal_screening_questionnaires[0]['email_text_pass'];
                        $list['q_fail_text'] = ''; //$portal_screening_questionnaires[0]['email_text_fail'];
                        $list['my_id'] = 'q_question_' . $video['screening_questionnaire_sid'];
                        $screening_questions_numrows = $this->learning_center_model->get_screenings_count_by_id($video['screening_questionnaire_sid']);

                        if ($screening_questions_numrows > 0) {
                            $screening_questions = $this->learning_center_model->get_screening_questions_by_id($video['screening_questionnaire_sid']);

                            foreach ($screening_questions as $qkey => $qvalue) {
                                $questions_sid = $qvalue['sid'];
                                $list['q_question_' . $video['screening_questionnaire_sid']][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                $screening_answers_numrows = $this->learning_center_model->get_screening_answer_count_by_id($questions_sid);

                                if ($screening_answers_numrows) {
                                    $screening_answers = $this->learning_center_model->get_screening_answers_by_id($questions_sid);

                                    foreach ($screening_answers as $akey => $avalue) {
                                        $list['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                    }
                                }
                            }
                        }

                        $data_new['job_details'] = $list;
                    }

                    $attachments = $this->learning_center_model->get_attached_document($video_sid);
                    $data_new['supported_documents'] = $attachments;
                    $data['attempt_status'] = $assignment['attempt_status'];

                    if ($assignment['attempt_status']) {
                        $attempted_questionnaire = $this->learning_center_model->get_video_questionnaire_attempt($video_sid, $assignment['sid']);
                        $data_new['attempted_questionnaire_timestamp'] = $attempted_questionnaire[0]['attend_timestamp'];
                        $data_new['questionnaire_result'] = $attempted_questionnaire[0]['questionnaire_result'];
                    }
//                    echo '<pre>';
//                    print_r($assignment);
//                    die();
                    //End
                    $data['assignment'] = $assignment;
                    $data['load_view'] = $load_view;
                    $data['user_type'] = $user_type;
                    $data['employer_sid'] = $employer_sid;
                    $data_new['user_type'] = $user_type;
                    $data_new['employer_sid'] = $employer_sid;
                    $data_new['watched'] = $assignment['watched'];
                    $data_new['attempt_status'] = $assignment['attempt_status'];
                    // Updated on: 09-05-2019
                    $data_new['date_watched'] = $data_new['watched'] != 0 ? DateTime::createFromFormat('Y-m-d H:i:s', $assignment['date_watched'])->format('m-d-Y h:i A') : '';
                    echo json_encode($data_new);
                } else {
                    $video_sid = $this->input->post('video_sid');
                    $user_type = $this->input->post('user_type');
                    $user_sid = $this->input->post('user_sid');
                    $this->learning_center_model->update_video_watched_status($user_type, $user_sid, $video_sid);
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Video marked as watched!');
                    redirect('learning_center/watch_video/' . $video_sid);
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Warning:</strong> Please Login to your account to continue!');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Video not found!');
            redirect('learning_center/my_learning_center', 'refresh');
        }
    }

    function mark_as_watched() {
        $user_sid = $this->input->post('id');
        $user_type = $this->input->post('type');
        $video_sid = $this->input->post('v_id');
        $this->learning_center_model->update_video_watched_status($user_type, $user_sid, $video_sid);
        $date = $this->learning_center_model->get_watched_video_date($user_type, $user_sid, $video_sid);
        $watched_on = DateTime::createFromFormat('Y-m-d H:i:s', $date['date_watched'])->format('m-d-Y h:i A');
        echo json_encode($watched_on);
    }

    function ajax_handler() {
        $company_sid = $_POST['company_sid'];
        $attached_by = $_POST['attached_by'];
        $file_title = $_POST['upload_title'];
        $file_extension = $_POST['file_ext'];
        $attached_date = date('Y-m-d H:i:s');
        $pdf_doc = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
//        $pdf_doc = '0003-docs--Q5D.pdf';

        if (!empty($pdf_doc) && $pdf_doc != 'error') {
            $data_to_insert = array();
            $video_data_to_insert = array();
            if (isset($_POST['video_sid']) && $_POST['video_sid'] != 0) {
                $video_sid = $_POST['video_sid'];
            } else {
                $video_data_to_insert['company_sid'] = 0;
                $video_data_to_insert['created_by_sid'] = $attached_by;
                $video_data_to_insert['created_date'] = date('Y-m-d H:i:s');
                $video_data_to_insert['video_title'] = $_POST['video_title'];
                $video_data_to_insert['video_description'] = $_POST['video_description'];
                $employees_assigned_to = $this->input->post('assigned_employee');
                $questionnaire_sid = $this->input->post('questionnaire_sid');
                $video_source = $this->input->post('video_source');
                $video_data_to_insert['employees_assigned_to'] = $employees_assigned_to;
                $video_data_to_insert['screening_questionnaire_sid'] = $questionnaire_sid;
                if (!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {
                    $random = generateRandomString(5);
                    $target_file_name = basename($_FILES["video_upload"]["name"]);
                    $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_sid;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                    } else {

                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('learning_center/online_videos', 'refresh');
                    }

                    $video_id = $file_name;
                }else{
                    $video_id = $this->input->post('video_id');

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        } else {
                            $video_id = '';
                        }
                    } else {
                        $video_id = $this->vimeo_get_id($video_id);
                    }
                }
                $video_data_to_insert['video_source'] = $video_source;
                $video_data_to_insert['video_id'] = $video_id;
                $video_sid = $this->learning_center_model->insert_training_video($video_data_to_insert);
            }

            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['attached_by'] = $attached_by;
            $data_to_insert['video_sid'] = $video_sid;
            $data_to_insert['upload_file_title'] = $file_title;
            $data_to_insert['upload_file_name'] = $pdf_doc;
            $data_to_insert['upload_file_extension'] = $file_extension;
            $data_to_insert['attached_date'] = $attached_date;
            $data_to_insert['status'] = 1;
            $document_sid = $this->learning_center_model->insert_attached_document($data_to_insert);
            $data_to_return = array();
            $data_to_return['document_sid'] = $document_sid;
            $data_to_return['active_btn'] = 'active-btn-'.$document_sid;
            $data_to_return['upload_file_title'] = $file_title;
            $data_to_return['video_sid'] = $video_sid;
            $data_to_return['attached_date'] = my_date_format($attached_date);
            $data_to_return['delete_url'] = base_url('learning_center/delete_attachment_document/' . $document_sid . '/' . $video_sid);
            $data_to_return['update_url'] = base_url('learning_center/update_supporting_document/' . $document_sid . '/' . $video_sid);
            
            header('Content-type: application/json');
            echo json_encode($data_to_return);
            exit(0);
        } else {
            echo 'error';
        }
    }

    function delete_attachment_document($sid, $video_sid) {
        $attachment_sid = $sid;
        $current_video_sid = $video_sid;
        $session = $this->session->userdata('logged_in');
        $employer_sid = $session['employer_detail']['sid'];
        $data_to_update = array();
        $data_to_update['status'] = 0;
        $data_to_update['deleted_by'] = $employer_sid;
        $data_to_update['deleted_date'] = date('Y-m-d H:i:s');
        $this->learning_center_model->delete_attach_document($sid, $data_to_update);
        $this->session->set_flashdata('message', '<strong>Success </strong> Attached document deleted!');
        redirect('learning_center/edit_online_video/' . $current_video_sid, 'refresh');
    }

    function view_supported_attachment_document($sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $load_view = 1;//check_blue_panel_status(false, 'self');

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), 'refresh');
            }

            $supporting_document = $this->learning_center_model->get_attach_document($sid, $company_sid);

            if(empty($supporting_document)){
                $this->session->set_flashdata('message', '<strong>Error:</strong> Document not found!');
                redirect('learning_center/my_learning_center', 'refresh');
            }

            $video_sid = $supporting_document['video_sid'];
            $video = $this->learning_center_model->get_single_online_video($video_sid, $company_sid);

            $data['load_view'] = $load_view;
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['security_details'] = $security_details;
            $data['title'] = $video['video_title'].' - Supported Document';
            $data['employee'] = $data['session']['employer_detail'];
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $tracking_document = '';
                $tracking_document = $this->learning_center_model->get_document_tracking($company_sid, $employer_sid, 'employee', $sid);

                if (empty($tracking_document)) {
                    $data_to_insert = array();
                    $data_to_insert['company_sid'] = $company_sid;
                    $data_to_insert['user_sid'] = $employer_sid;
                    $data_to_insert['user_type'] = 'employee';
                    $data_to_insert['document_sid'] = $sid;
                    $data_to_insert['is_preview'] = 1;
                    $data_to_insert['preview_date'] = date('Y-m-d H:i:s');
                    $this->learning_center_model->insert_preview_attach_document($data_to_insert);
                    $tracking_document = $this->learning_center_model->get_document_tracking($company_sid, $employer_sid, 'employee', $sid);
                }

                if ($tracking_document['is_preview'] == 1) {
                    $preview_status = '<strong class="text-success">Document Status:</strong> You have previewed this document';
                    $preview_date = my_date_format($tracking_document['preview_date']);
                    $preview_on = '<b>Previewed On: ' . $preview_date . '</b>';
                    $data['preview_status'] = $preview_status;
                    $data['preview_on'] = $preview_on;
                }

                if ($tracking_document['is_downloaded'] == 1) {
                    $download_status = '<strong class="text-success">Document Status:</strong> You have downloaded this document';
                    $download_date = my_date_format($tracking_document['downloaded_date']);
                    $download_on = '<b>download On: ' . $download_date . '</b>';
                    $data['download_status'] = $download_status;
                    $data['download_on'] = $download_on;
                    $data['download_button_css'] = 'btn-warning';
                    $data['download_button_text'] = 'Re-Download';
                } else {
                    $download_status = '<strong class="text-success">Document Status:</strong> You have not yet downloaded this document';
                    $data['download_status'] = $download_status;
                    $data['download_button_css'] = 'btn-success';
                    $data['download_button_text'] = 'Download';
                }

                $back_url = base_url('learning_center/watch_video/' . $supporting_document['video_sid']);
                $download_button_action = base_url('learning_center/download_supported_document/' . $sid);
                $data['download_button_action'] = $download_button_action;
                $data['supporting_document'] = $supporting_document;
                $data['tracking_document'] = $tracking_document;
                $data['back_url'] = $back_url;

                $this->load->view('main/header', $data);
                $this->load->view('learning_center/supporting_document_preview');
                $this->load->view('main/footer');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Warning:</strong> Please Login to your account to continue!');
            redirect('login');
        }
    }

    public function download_supported_document($document_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $load_view = check_blue_panel_status(false, 'self');

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), 'refresh');
            }

            $data['load_view'] = $load_view;
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['security_details'] = $security_details;
            $data['title'] = 'Learning Center - Supported Document';
            $data['employee'] = $data['session']['employer_detail'];

            if ($this->form_validation->run() == false) {
                $document = $this->learning_center_model->get_attach_document($document_sid, $company_sid);
                $data['document'] = $document;
                $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                $file_name = $document['upload_file_name'];
                $temp_file_path = $temp_path . $file_name;

                if (file_exists($temp_file_path)) {
                    unlink($temp_file_path);
                }

                $this->load->library('aws_lib');
                $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document['upload_file_name'], $temp_file_path);

                if (file_exists($temp_file_path)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $file_name . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($temp_file_path));
                    $handle = fopen($temp_file_path, 'rb');
                    $buffer = '';

                    while (!feof($handle)) {
                        $buffer = fread($handle, 4096);
                        echo $buffer;
                        ob_flush();
                        flush();
                    }

                    fclose($handle);
                    unlink($temp_file_path);
                }

                $data_to_update = array();
                $data_to_update['is_downloaded'] = 1;
                $data_to_update['downloaded_date'] = date('Y-m-d H:i:s');
                $this->learning_center_model->update_download_status($company_sid, $employer_sid, 'employee', $document_sid, $data_to_update);
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function active_diactive_document() {
        $company_sid = $_POST['company_sid'];
        $video_sid = $_POST['video_sid'];
        $document_sid = $_POST['document_sid'];
        $active_status = $_POST['active_status'];

        if ($active_status == 1) {
            $data_to_update = array();
            $data_to_update['active'] = 0;
            $document_sid = $this->learning_center_model->update_supporting_document_state($document_sid, $company_sid, $video_sid, $data_to_update);

            echo json_encode($data_to_update);
        } else {
            $data_to_update = array();
            $data_to_update['active'] = 1;
            $document_sid = $this->learning_center_model->update_supporting_document_state($document_sid, $company_sid, $video_sid, $data_to_update);

            echo json_encode($data_to_update);
        }
    }

    function update_supporting_document($document_sid, $video_sid) {
        if ($this->session->userdata('logged_in')) {
            $document = $this->learning_center_model->get_supported_document($document_sid, $video_sid);


            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                echo json_encode($document);
            } else {

                $document_sid = $_POST['document_sid'];
                $video_sid = $_POST['video_sid'];
                $file_title = $_POST['upload_title'];
                $file_status = $_POST['status'];
                $company_sid = $_POST['company_sid'];

                $data_to_update = array();

                if (!empty($_FILES) && isset($_FILES['docs']) && $_FILES['docs']['size'] > 0) {

                    $pdf_doc = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
//                    $pdf_doc = '0003-docs--Q5D.pdf';
                    if (!empty($pdf_doc) && $pdf_doc != 'error') {

                        $file_extension = $_POST['file_ext'];
                        $data_to_update['upload_file_name'] = $pdf_doc;
                        $data_to_update['upload_file_extension'] = $file_extension;
                    } else {
                        echo 'error';
                    }
                }

                $data_to_update['upload_file_title'] = $file_title;
                $data_to_update['active'] = $file_status;

                $this->learning_center_model->update_supporting_document($document_sid, $video_sid, $data_to_update);

                echo 'success';
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    //Resource Center Learning Center

    function resource_learning_center(){
        if ($this->session->userdata('logged_in')) {

            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['title'] = 'Learning Center';
            check_access_permissions($security_details, 'learning_center', 'customize_appearance');
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');

            $resource_category = $this->learning_center_model->fetch_resource_category(3);
            $data['resource_category'] = $resource_category;
            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('learning_center/resource_learning_center');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }



    /**
     * Fetch training sesion by status
     *
     * @accept GET
     *
     * @param $page Integer
     * @param $status String
     *
     * @return JSON
     */
    function get_training_sessions($page, $status, $add = false){
        // Redirect if not logged in
        if(!$this->input->is_ajax_request() || !$this->session->userdata('logged_in'))
            redirect(base_url('login'), "refresh");

        if ($this->input->server('REQUEST_METHOD') != 'GET')
            redirect('calendar/my_events', 'refresh');
        //
        $session = $this->session->userdata('logged_in');
        check_access_permissions(db_get_access_level_details($session['employer_detail']['sid']), 'learning_center', 'training_sessions'); // no need to check in this Module as Dashboard will be available to all
        $company_sid = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //

        $results = $this->learning_center_model
        ->get_training_sessions(
            $company_sid,
            $page,
            $this->limit,
            $status,
            $employeeId,
            $add
        );

        $return_array['Status'] = FALSE;
        $return_array['Response'] = 'No records found.';

        if(!sizeof($results)){
            header('Content-Type: application/json');
            echo json_encode($return_array); exit(0);
        }

        $return_array['Status'] = TRUE;
        $return_array['Limit']  = $this->limit;
        $return_array['ListSize'] = $this->list_size;
        if(isset($results['TotalRecords'])){
            $return_array['Total']    = $results['TotalRecords'];
            $return_array['Records']  = $results['Records'];
            $return_array['Expired']  = $results['TotalExpired'];
            $return_array['Cancelled']  = $results['TotalCancelled'];
            $return_array['Confirmed']  = $results['TotalConfirmed'];
            $return_array['Pending']  = $results['TotalPending'];
            $return_array['Completed']  = $results['TotalCompleted'];
        }else
            $return_array['Records']  = $results;

        header('Content-Type: application/json');
        echo json_encode($return_array); exit(0);
    }


    //
    function video_access(){
        //
        $post = $this->input->post(NULL, TRUE);
        //
        if(empty($post)){
            echo 'error';
            exit(0);
        }
        //
        $session = $this->session->userdata('logged_in');
        //
        if($post['action'] == 'revoke'){
            // Get the video assigned details
            $videoDetails = $this->learning_center_model->getVideoAssignedDetails(
                $session['company_detail']['sid'],
                $post['userId'],
                $post['userType'],
                $post['videoId']
            );
            //
            $user_question = $this->learning_center_model->get_user_question_record($post['videoId'], $post['userId']);
            //
            if (!empty($user_question)) {
                $videoDetails['questionnaire_name'] = $user_question['questionnaire_name'];
                $videoDetails['questionnaire'] = $user_question['questionnaire'];
                $videoDetails['questionnaire_result'] = $user_question['questionnaire_result'];
                $videoDetails['questionnaire_attend_timestamp'] = $user_question['attend_timestamp'];
            }
            //
            $assign_video_row_sid = $videoDetails['sid'];
            $videoDetails['video_url'] = $videoDetails['video_id'];
            //
            unset($videoDetails['sid']);
            unset($videoDetails['video_id']);
            unset($videoDetails['deleted_at']);
            //
            $this->learning_center_model->save_user_assign_video_history($videoDetails);
            $this->learning_center_model->removeUserFromVideo($assign_video_row_sid);
            echo 'success';
            exit(0);
        }
        //
        if($post['action'] == 'assign'){
            //
            foreach($post['ids'] as $videoId){
                //
                $this->learning_center_model->addUserFromVideo([
                    'learning_center_online_videos_sid' => $videoId,
                    'user_type' => $post['userType'],
                    'user_sid' => $post['userId'],
                    'date_assigned' => date('Y-m-d H:i:s', strtotime('now')),
                    'watched' => 0,
                    'status' => 1,
                    'attempt_status' => 0,
                    'completed' => 0
                ]);
            }
            echo 'success';
            exit(0);
        }
    }

    function get_department_employee () {
        $company_sid    = $_POST['company_sid'];

        $department_sid = explode(',', $_POST['department_sid']);

        $selected_department_employees = $this->learning_center_model->getDepartmentEmployeesList(
            $company_sid,
            $department_sid
        );

        $employeesList = array_column($selected_department_employees, 'sid');  
        $employeesList = array_unique($employeesList, SORT_REGULAR);

        header('Content-type: application/json');
        echo json_encode($employeesList);
        exit(0);
    }

}