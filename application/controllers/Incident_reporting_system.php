<?php defined('BASEPATH') or exit('No direct script access allowed');

class Incident_reporting_system extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->library("pagination");
        $this->load->model('incident_reporting_model');
        $this->load->model('notification_emails_model');
        //
        if (!checkIfAppIsEnabled('incidents')) {
            redirect(base_url('dashboard'), "refresh");
        }
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            if (checkIfAppIsEnabled('incidents')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;

                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $data['title'] = 'Incident Reporting System';
                $types = $this->incident_reporting_model->get_incident_types_company_specific($company_sid);

                foreach ($types as $key => $value) {
                    if ($value['count'] == 0) {
                        unset($types[$key]);
                    }
                }

                $data['types'] = $types;
                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $data['employee'] = $data['session']['employer_detail'];
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/index_old');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('dashboard'), "refresh");
            }  
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_new_incident()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Application Tracking System";
            $data['employee'] = $data["session"]["employer_detail"];
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;

            if (isset($_POST) && isset($_POST['inc-form'])) {
                extract($_POST);
                unset($_POST['inc-form']);
                $_POST['company_sid'] = $company_sid;
                $_POST['employer_sid'] = $employer_sid;
                $this->incident_reporting_model->insert_incident($_POST);
                $this->session->set_flashdata('message', '<b>Success:</b> Incident have been reported successfully!');
                redirect(base_url('dashboard'), "refresh");
            } else {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/add_new_incident');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function report($file, $id)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['employee'] = $data['session']['employer_detail'];
            $incident_details = $this->incident_reporting_model->fetch_reports_user_guide($id);

            if (empty($incident_details)) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Incident found!');
                redirect(base_url('incident_reporting_system'), 'refresh');
            }

            $report_type = 'anonymous';

            if ($file == 'c') {
                $report_type = 'confidential';
            }

            $data['title'] = 'Reporting ' . $incident_details[0]['incident_name'];
            $data['id'] = $id;
            $data['report_type'] = $report_type;
            $questions = $this->incident_reporting_model->fetch_all_question($id);
            $incident_managers = $this->incident_reporting_model->fetch_incident_managers($id, $company_sid);
            $data['incident_managers'] = $incident_managers;
            // echo $report_type.'<pre>'; print_r($questions); echo '</pre>'; die();
            $e_signature_data = get_e_signature($company_sid, $employer_sid, 'employee');
            $data['e_signature_data'] = $e_signature_data;
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] =  $load_view;
            $data['questions'] = $questions;
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['employees'] = $this->incident_reporting_model->fetch_all_company_employees($company_sid);
            //
            $data['employees_new'] = $this->incident_reporting_model->get_all_employees($company_sid);

            $this->form_validation->set_rules('submit', 'Form Submit', 'trim');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/add_new_incident_old');
                $this->load->view('main/footer');
            } else {
                if (isset($_POST) && isset($_POST['submit'])) {

                    $update_id = $_POST['inc-id'];
                    $review_manager = $_POST['review_manager'];
                    $reply_url = '';

                    $on_behalf_employee_sid  = $_POST['incident_employee_id'];

                    unset($_POST['submit']);
                    unset($_POST['inc-id']);
                    unset($_POST['review_manager']);
                    //
                    $incidentId = 0;

                    if ($update_id != 0) {
                        $update['report_type'] = $report_type;
                        $update['incident_name'] = $incident_details[0]['incident_name'];
                        $this->incident_reporting_model->update_incident_report($update_id, $update);
                        $incident = $update_id;
                        $video_to_update = array();
                        $video_to_update['is_incident_reported'] = 1;
                        $this->incident_reporting_model->update_incident_related_video($update_id, $video_to_update);
                        //
                        $incidentId = $update_id;
                    } else {
                        $insert = array();
                        $insert['company_sid'] = $company_sid;
                        $insert['employer_sid'] = $on_behalf_employee_sid;
                        $insert['current_date'] = date('Y-m-d H:i:s');
                        $insert['incident_type_id'] = $id;
                        $insert['report_type'] = $report_type;
                        $insert['incident_name'] = $incident_details[0]['incident_name'];
                        $insert['on_behalf_employee_sid'] =  $employer_sid;
                        $incident = $this->incident_reporting_model->insert_incident_reporting($insert);
                        //
                        $incidentId = $incident;
                    }

                    if (isset($_POST['any_witnesses']) && $_POST['any_witnesses'] == 1) {
                        foreach ($_POST['witnesses'] as $key => $witness) {
                            $witness_to_insert = array();
                            $witness_to_insert['incident_type_id']          = $id;
                            $witness_to_insert['incident_reporting_id']     = $incidentId;
                            $witness_to_insert['company_sid']               = $company_sid;
                            $witness_to_insert['witness_type']              = $witness['type'];
                            $witness_to_insert['witness_name']              = $witness['full_name'];
                            $witness_to_insert['witness_phone']             = $witness['phone'];
                            $witness_to_insert['witness_email']             = $witness['email'];
                            $witness_to_insert['witness_title']             = $witness['title'];
                            $witness_to_insert['can_provide_info']          = $witness['can_provide_info'];
                            $witness_to_insert['reported_date']             = date('Y-m-d H:i:s');
                            $witness_to_insert['added_by']                  = $employer_sid;

                            $this->incident_reporting_model->add_new_witness($witness_to_insert);
                        }
                    }


                    unset($_POST['witnesses']);

                    $insert = array();

                    foreach ($_POST as $key => $val) {
                        $exp = explode('_', $key);
                        // echo '<pre>'; print_r($exp); exit;
                        if (sizeof($exp) > 1 && !empty($val)) {
                            $insert['question'] = $this->incident_reporting_model->get_specific_question($exp[1]);

                            if ($exp[0] == 'multi-list') {
                                $val = serialize($val);
                            }

                            $insert['answer'] = strip_tags($val);
                            $insert['incident_reporting_id'] = $incidentId;
                            $this->incident_reporting_model->insert_inc_que_ans($insert);
                        } elseif (sizeof($exp) == 1 && !empty($val) && $exp[0] == 'signature') {
                            $insert['question'] = $exp[0];
                            $insert['answer'] = strip_tags($val);
                            $insert['incident_reporting_id'] = $incidentId;
                            $this->incident_reporting_model->insert_inc_que_ans($insert);
                        }
                    }

                    $replacement_array = array();
                    $replacement_array['company_name'] = ucwords($data['session']['company_detail']['CompanyName']);
                    $replacement_array['company-name'] = ucwords($data['session']['company_detail']['CompanyName']);
                    // $employees = $this->incident_reporting_model->get_configured_employees($company_sid, $id);

                    foreach ($review_manager as $manager) {
                        $assigned_emp = array(
                            'company_sid' => $company_sid,
                            'employer_sid' => $manager,
                            'assigned_date' => date('Y-m-d H:i:s'),
                            'incident_sid' => $incidentId
                        );

                        $this->incident_reporting_model->assign_incident_to_emp($assigned_emp);     //Add the employees who are gonna receive this incident from employee reporting
                        $emp = $this->incident_reporting_model->fetch_employee_name_by_sid($manager);

                        $reply_url = base_url('incident_reporting_system/view_single_assign') . '/' . $incidentId;
                        $viewIncidentBtn = '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $reply_url . '" target="_blank">View Report</a>';
                        $replacement_array['applicant_name'] = ucwords($emp[0]['first_name'] . ' ' . $emp[0]['last_name']);
                        $replacement_array['applicant-name'] = ucwords($emp[0]['first_name'] . ' ' . $emp[0]['last_name']);
                        $replacement_array['first-name'] = ucwords($emp[0]['first_name']);
                        $replacement_array['last-name'] = ucfirst($emp[0]['last_name']);
                        $replacement_array['firstname'] = ucwords($emp[0]['first_name']);
                        $replacement_array['lastname'] = ucfirst($emp[0]['last_name']);
                        $replacement_array['first_name'] = ucwords($emp[0]['first_name']);
                        $replacement_array['last_name'] = ucfirst($emp[0]['last_name']);
                        $replacement_array['view_button'] = $viewIncidentBtn;
                        //
                        $message_hf = message_header_footer($company_sid, $data['session']['company_detail']['CompanyName']);
                        //
                        log_and_send_templated_email(INCIDENT_REPORT_NOTIFICATION, $emp[0]['email'], $replacement_array, $message_hf);
                    }

                    // Sending incident email to Steven start
                    $viewAdminIncidentBtn = '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url("manage_admin/reports/incident_reporting/view_incident/".$incidentId) . '" target="_blank">View Report</a>';
                    $stevendata = [
                        'first_name' => 'Steven',
                        'last_name' => 'Warner',
                        'email' => FROM_EMAIL_STEVEN,
                        'phone' => '',
                        'firstname' => 'Steven',
                        'lastname' => 'Warner',
                        'company_name' => getCompanyNameBySid($company_sid),
                        'view_button' => $viewAdminIncidentBtn
                    ];

                    log_and_send_templated_email(INCIDENT_REPORT_NOTIFICATION, $stevendata['email'], $stevendata);

                    $this->session->set_flashdata('message', '<b>Success:</b> New ' . ucfirst($report_type) . ' Incident Reported');
                    redirect(base_url('incident_reporting_system/list_incidents'), "refresh");
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function incident_reported()
    {
        if ($this->session->userdata('logged_in')) {
            if (checkIfAppIsEnabled('incidents')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];

                $data['title'] = 'Insident Reported';
                $incidents_reported = $this->incident_reporting_model->get_incident_reported_by_company_specific($company_sid, $employer_sid);
                $data['incidents_reported'] = $incidents_reported;
                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $data['employee'] = $data['session']['employer_detail'];
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/incident_reported');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('dashboard'), "refresh");
            }     
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_incident_reported($id)
    {
        if ($this->session->userdata('logged_in')) {
            if (checkIfAppIsEnabled('incidents')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                $data['employee'] = $data["session"]["employer_detail"];

                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $data['id'] = $id;
                $reported_incident = $this->incident_reporting_model->get_reported_incident($id);
                $reported_by = $this->incident_reporting_model->get_incident_reported_by($id);

                $data['reported_incident'] = $reported_incident;
                $data['reported_by'] = $reported_by[0]['first_name'] . ' ' . $reported_by[0]['last_name'];
                $data['reported_on'] = $reported_by[0]['current_date'];
                $data['sub_title'] = $reported_by[0]['incident_name'];
                $data['report_type'] = $reported_by[0]['report_type'];
                $data['incident_type_id'] = $reported_by[0]['incident_type_id'];
                $data['incident_reporting_sid'] = $reported_by[0]['id'];
                $data['reported_by_sid'] = $reported_by[0]['employer_sid'];
                $data['reported_by'] = $reported_by[0]['first_name'] . ' ' . $reported_by[0]['last_name'];
                $data['reported_date'] = $reported_by[0]['current_date'];
                $data['title'] = 'Reported Incident';

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/view_reported_incident');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('dashboard'), "refresh");
            }      
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function manager_report()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['employee'] = $data['session']['employer_detail'];
            $manager_name = $data['session']['employer_detail']['username'];

            if (isset($_POST) && isset($_POST['submit'])) {
                unset($_POST['submit']);
                $formpost = $this->input->post(NULL, TRUE);
                $insert = array();
                $original_items = array();
                $questions = $this->incident_reporting_model->fetch_all_question($formpost['report_sid']);

                foreach ($questions as $key => $value) {
                    $original_checklist = array(
                        'Question' => $value['label']
                    );

                    array_push($original_items, $original_checklist);
                }

                $insert['manager_response_form'] = serialize($original_items);
                $submitted_form_data = array();

                foreach ($_POST as $key => $value) {
                    $question = str_replace('%0D%0A', '', $key);

                    if ($question != 'incident_reporting_sid' && $question != 'incident_name' && $question != 'reported_by_sid' && $question != 'report_sid' && $question != '0' && $question != 'show_to_reported') {
                        $submitted_checklist = array(
                            'Question' => str_replace('%0D%0A', '', $key),
                            'Answer' => $value
                        );

                        array_push($submitted_form_data, $submitted_checklist);
                    }
                }

                $insert['manager_response_data'] = serialize($submitted_form_data);
                $insert['incident_reporting_sid'] = $formpost['incident_reporting_sid'];
                $insert['incident_name'] = $formpost['incident_name'];
                $insert['reported_by_sid'] = $formpost['reported_by_sid'];
                $insert['show_to_reported'] = $formpost['show_to_reported'];
                $insert['company_sid'] = $company_sid;
                $insert['manager_sid'] = $employer_sid;
                $insert['manager_name'] = $manager_name;
                $insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $insert['ip_address'] = getUserIP();
                $this->incident_reporting_model->insert_manager_report($insert);
                $this->session->set_flashdata('message', '<b>Success:</b> Incident Report Submitted Successfully');
                redirect(base_url('incident_reported'), "refresh");
            } else {
                $incident_parent_sid = $this->incident_reporting_model->fetch_reports_user_guide($_POST['reported_type_sid']);
                $questions = $this->incident_reporting_model->fetch_all_question($incident_parent_sid[0]['parent_sid']);
                $data['title'] = 'Respond To Incident';
                $data['company_sid'] = $company_sid;
                $data['incident_reporting_sid'] = $_POST['incident_reporting_sid'];
                $data['incident_name'] = $_POST['incident_name'];
                $data['reported_by_sid'] = $_POST['reported_by_sid'];
                $data['questions'] = $questions;
                $data['report_sid'] = $incident_parent_sid[0]['parent_sid'];
                $data['reported_on'] = $_POST['reported_date'];
                $data['reported_by'] = $_POST['reported_by'];
            }

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/incident_reporting/incident_manager_reported');
            $this->load->view('main/footer');
        }
    }

    public function view_guide($id = NULL)
    {
        if ($this->session->userdata('logged_in')) {

            if ($id == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Incident found!');
                redirect(base_url('incident_reporting_system'), 'refresh');
            }

            $incident_details = $this->incident_reporting_model->fetch_reports_user_guide($id);

            if (empty($incident_details)) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Incident found!');
                redirect(base_url('incident_reporting_system'), 'refresh');
            }

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['employee'] = $data['session']['employer_detail'];
            $data['id'] = $id;
            $data['guide'] = $incident_details;
            $data['title'] = $incident_details[0]['incident_name'];
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/incident_reporting/guide_old');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_handler()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $employer_sid = $data["session"]["employer_detail"]["sid"];
        $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);

        if (!empty($pictures) && $pictures != 'error') {

            if (isset($_POST['inc_id']) && $_POST['inc_id'] != 0) {
                $incident = $_POST['inc_id'];
            } else {
                $insert['company_sid'] = $company_sid;
                $insert['employer_sid'] = $employer_sid;
                $insert['current_date'] = date('Y-m-d H:i:s');
                $insert['incident_type_id'] = $_POST['id'];
                $incident = $this->incident_reporting_model->insert_incident_reporting($insert);
            }

            $docs = array();
            $last_index_of_dot = strrpos($_FILES["docs"]["name"], '.') + 1;
            $file_ext = substr($_FILES["docs"]["name"], $last_index_of_dot, strlen($_FILES["docs"]["name"]) - $last_index_of_dot);
            $docs['file_name'] = $_FILES["docs"]["name"];
            $docs['type'] = $file_ext;
            $docs['file_code'] = $pictures;
            $docs['company_id'] = $company_sid;
            $docs['employer_id'] = $employer_sid;
            $docs['incident_reporting_id'] = $incident;
            $this->incident_reporting_model->insert_incident_docs($docs);
            echo $incident;
        } else {
            echo 'error';
        }
    }

    public function add_incident_video()
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $employee_sid   = $session["employer_detail"]["sid"];
            $company_sid    = $session['company_detail']['sid'];

            $video_title    = $_POST['video_title'];
            $company_sid    = $_POST['company_sid'];
            $video_source   = $_POST['file_type'];

            if (isset($_POST['incident_sid']) && $_POST['incident_sid'] != 0) {
                $incident_sid = $_POST['incident_sid'];
            } else {
                $insert['company_sid'] = $company_sid;
                $insert['employer_sid'] = $employee_sid;
                $insert['current_date'] = date('Y-m-d H:i:s');
                $insert['incident_type_id'] = $_POST['incident_type_sid'];
                $incident_sid = $this->incident_reporting_model->insert_incident_reporting($insert);
            }

            $video_id = '';

            if (!empty($_FILES) && isset($_FILES['video']) && $_FILES['video']['size'] > 0 && $video_source == 'upload_video') {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["video"]["name"]);
                $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/incident_videos/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $company_sid;

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                move_uploaded_file($_FILES["video"]["tmp_name"], $target_file);

                $video_id = $file_name;
            } else if (!empty($_FILES) && isset($_FILES['audio']) && $_FILES['audio']['size'] > 0 && $video_source == 'upload_audio') {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["audio"]["name"]);
                $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/incident_videos/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $company_sid;

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                move_uploaded_file($_FILES["audio"]["tmp_name"], $target_file);

                $video_id = $file_name;
            } else {
                if ($video_source == 'youtube') {
                    $youtube_video_link = $_POST['youtube_video_link'];
                    $url_prams = array();
                    parse_str(parse_url($youtube_video_link, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_id = $url_prams['v'];
                    } else {
                        $video_id = '';
                    }
                } else {
                    $vimeo_video_link = $_POST['vimeo_video_link'];
                    $video_id = $this->vimeo_get_id($vimeo_video_link);
                }
            }

            $video_to_insert                            = array();
            $video_to_insert['incident_sid']            = $incident_sid;
            $video_to_insert['video_title']             = $video_title;
            $video_to_insert['video_type']              = $video_source;
            $video_to_insert['video_url']               = $video_id;
            $video_to_insert['user_type']               = 'employee';
            $video_to_insert['uploaded_by']             = $employee_sid;
            $video_to_insert['is_incident_reported']    = 0;

            $insert_video_sid = $this->incident_reporting_model->insert_incident_video_reccord($video_to_insert);

            $return_data                    = array();
            $return_data['incident_sid']    = $incident_sid;
            $return_data['video_sid']       = $insert_video_sid;
            $return_data['video_title']     = $video_title;
            $return_data['video_source']    = $video_source;

            if ($video_source == 'upload_video') {
                $video_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video_id;
                $return_data['video_url'] = $video_url;
            } else if ($video_source == 'upload_audio') {
                $audio_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video_id;
                $return_data['video_url'] = $audio_url;
            } else {
                $return_data['video_url'] = $video_id;
            }

            echo json_encode($return_data);
        } else {
            echo 'error';
        }
    }

    public function add_incident_document()
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $employee_sid       = $session["employer_detail"]["sid"];

            $document_title     = $_POST['document_title'];
            $company_sid        = $_POST['company_sid'];
            $file_name          = $_POST['file_name'];
            $file_extension     = $_POST['file_ext'];

            if (isset($_POST['incident_sid']) && $_POST['incident_sid'] != 0) {
                $incident_sid = $_POST['incident_sid'];
            } else {
                $insert['company_sid'] = $company_sid;
                $insert['employer_sid'] = $employee_sid;
                $insert['current_date'] = date('Y-m-d H:i:s');
                $insert['incident_type_id'] = $_POST['incident_type_sid'];
                $incident_sid = $this->incident_reporting_model->insert_incident_reporting($insert);
            }

            if (!empty($_FILES) && isset($_FILES['docs']) && $_FILES['docs']['size'] > 0) {

                $upload_incident_doc = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);

                if (!empty($upload_incident_doc) && $upload_incident_doc != 'error') {

                    $document_to_insert = array();
                    $document_to_insert['document_title']           = $document_title;
                    $document_to_insert['file_name']                = $file_name;
                    $document_to_insert['type']                     = $file_extension;
                    $document_to_insert['file_code']                = $upload_incident_doc;
                    $document_to_insert['uploaded_date']            = date('Y-m-d H:i:s');
                    $document_to_insert['company_id']               = $company_sid;
                    $document_to_insert['employer_id']              = $employee_sid;
                    $document_to_insert['incident_reporting_id']    = $incident_sid;
                    $insert_document_sid = $this->incident_reporting_model->insert_incident_docs($document_to_insert);

                    $return_data                        = array();
                    $return_data['incident_sid']        = $incident_sid;
                    $return_data['document_sid']        = $insert_document_sid;
                    $return_data['document_title']      = $document_title;
                    $return_data['document_url']        = AWS_S3_BUCKET_URL . $upload_incident_doc;
                    $return_data['document_type']       = strtoupper($file_extension);
                    $return_data['document_extension']  = $file_extension;

                    echo json_encode($return_data);
                }
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
    }

    public function list_incidents()
    {
        if ($this->session->userdata('logged_in')) {
            if (checkIfAppIsEnabled('incidents')) {
                $data['session'] = $this->session->userdata('logged_in');
                $data['employee'] = $data["session"]["employer_detail"];
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;

                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $data['title'] = "Your Incidents";
                $incidents = $this->incident_reporting_model->view_incidents($company_sid, $employer_sid);
                $data['incidents'] = $incidents;
                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/list_employee_incidents_old');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('dashboard'), "refresh");
            }    
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_incident($id)
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $security_sid = $session['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);


            $title              = "Your Reported Incident";
            $employer_detail    = $session["employer_detail"];
            $company_sid        = $session["company_detail"]["sid"];
            $employer_sid       = $session["employer_detail"]["sid"];
            $company_name       = $session["company_detail"]['CompanyName'];

            $access = $this->incident_reporting_model->check_view_access($id);

            if ((sizeof($access) != 0) && ($access[0]['employer_sid'] == $employer_sid || $access[0]['on_behalf_employee_sid'] == $employer_sid)) {

                $on_behalf_employee = $this->incident_reporting_model->get_employee_detail($access[0]['on_behalf_employee_sid']);

                $incident_emails = array();
                $load_view = check_blue_panel_status(false, 'self');
                $this->incident_reporting_model->update_comment_read_status($id);

                //fetch incident Type ID
                $incident_id = $this->incident_reporting_model->get_incident_type_id($id, $company_sid);

                // Fetch Incident Name
                $incident_name = $this->incident_reporting_model->get_incident_name_by_id($id, $company_sid);

                // Fetch Incident Question Answer
                $incident = $this->incident_reporting_model->view_specific_incident($id);

                // Fetch Incident Witnesses
                $witnesses = $this->incident_reporting_model->get_reporter_incident_witnesses($id, $incident_id);

                // Fetch Incident Assigned Manager
                $incident_managers = $this->incident_reporting_model->fetch_incident_assigned_managers($id, $company_sid);

                // Fetch Active Video
                $videos = $this->incident_reporting_model->get_incident_videos($id, "0");

                // Fetch Archived Video
                $videos_archived = $this->incident_reporting_model->get_incident_videos($id, '1');

                // Fetch Active Document
                $get_incident_document_active = $this->incident_reporting_model->get_incident_report_docs($id, '0');

                // Fetch Archived Document
                $get_incident_document_archived = $this->incident_reporting_model->get_incident_report_docs($id, '1');

                // Fetch Incident Email between Managers Only
                foreach ($incident_managers as $key => $incident_manager) {
                    $new_emails = '';
                    $sender_sid = '';
                    $receiver_id = $incident_manager['employee_id'];
                    $receiver_name = $incident_manager['employee_name'];

                    if (str_replace('_wid', '', $receiver_id) != $receiver_id) {

                        $witness_id = str_replace('_wid', '', $receiver_id);
                        $witness_info = $this->incident_reporting_model->get_witness_info_by_id($witness_id, $id);

                        if ($witness_info['witness_type'] == 'employee') {
                            $witness_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness_info['witness_email']);
                            $new_emails = $this->incident_reporting_model->get_incident_related_emails($witness_id, $employer_sid, $id);
                            $sender_sid = $witness_id;
                        } else {
                            $new_emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $employer_sid, $id);
                            $sender_sid = $receiver_id;
                        }
                    } else if (str_replace('_inc_rep', '', $receiver_id) != $receiver_id) {

                        $inc_reporter_id = str_replace('_inc_rep', '', $receiver_id);
                        $new_emails = $this->incident_reporting_model->get_incident_related_emails($inc_reporter_id, $employer_sid, $id);
                        $sender_sid = $inc_reporter_id;
                    } else {
                        $new_emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $employer_sid, $id);
                        $sender_sid = $receiver_id;
                    }

                    if (!empty($new_emails)) {
                        $incident_emails[$key]['name']          = $receiver_name;
                        $incident_emails[$key]['user_one']      = $employer_sid;
                        $incident_emails[$key]['user_two']      = $receiver_id;
                        $incident_emails[$key]['sender_sid']    = $sender_sid;
                        $incident_emails[$key]['incident_id']   = $id;
                        $incident_emails[$key]['emails']        = $new_emails;
                    }
                }

                // Fetch Incident Notes
                $comments = $this->incident_reporting_model->get_reporter_incident_comments($id);

                // Fetch Document For Library
                $library_documets = $this->incident_reporting_model->get_user_library_documents($id, $employer_sid, 'employee');

                // Fetch Media For Media
                $library_media = $this->incident_reporting_model->get_user_library_media($id, $employer_sid, 'employee');
                

                $data                                   = array();
                $data['id']                             = $id;
                $data['title']                          = $title;
                $data['videos']                         = $videos;
                $data['session']                        = $session;
                $data['incident']                       = $incident;
                $data['comments']                       = $comments;
                $data['witnesses']                      = $witnesses;
                $data['load_view']                      = $load_view;
                $data['incident_id']                    = $incident_id;
                $data['company_sid']                    = $company_sid;
                $data['current_user']                   = $employer_sid;
                $data['incident_name']                  = $incident_name;
                $data['library_media']                  = $library_media;
                $data['employee']                       = $employer_detail;
                $data['incident_emails']                = $incident_emails;
                $data['videos_archived']                = $videos_archived;
                $data['security_details']               = $security_details;
                $data['library_documets']               = $library_documets;
                $data['incident_managers']              = $incident_managers;
                $data['get_incident_document']          = $get_incident_document_active;
                $data['get_incident_document_archived'] = $get_incident_document_archived;
                $data['on_behalf_employee'] = $on_behalf_employee;
                $data['on_behalf_employee_sid'] = $access[0]['on_behalf_employee_sid'];
                $data['access_employer_sid'] = $access[0]['employer_sid'];
                //
                if ($incident_id == 50) {
                    // Fetch All Active Company Employees For Adding As Witness
                    $data['employees'] = $this->incident_reporting_model->fetch_all_company_employees($company_sid);
                }
                //

                if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
                    $perform_action = $_POST['perform_action'];
                    if ($perform_action == 'send_email') {

                        $managers = $_POST['managers'];
                        $subject = $_POST['subject'];
                        $from_name = $session["employer_detail"]["first_name"] . ' ' . $session["employer_detail"]["last_name"];
                        $email_hf = message_header_footer_domain($company_sid, $company_name);
                        $attachments = isset($_POST['attachment']) ? $_POST['attachment'] : '';

                        foreach ($managers as $key => $manager_id) {
                            $conversation_key = '';
                            $message_body = $_POST['message'];

                            $data_to_insert = array();
                            $data_to_insert['incident_type_id'] = $incident_id;
                            $data_to_insert['incident_reporting_id'] = $id;
                            $data_to_insert['sender_sid'] = $employer_sid;
                            $data_to_insert['subject'] = $subject;
                            $data_to_insert['message_body'] = $message_body;

                            if (str_replace('_wid', '', $manager_id) != $manager_id) {

                                $witness_id = str_replace('_wid', '', $manager_id);
                                $witness_info = $this->incident_reporting_model->get_witness_info_by_id($witness_id, $id);
                                $receiver_email = $witness_info['witness_email'];
                                $receiver_name = $witness_info['witness_name'];

                                if ($witness_info['witness_type'] == 'employee') {
                                    $witness_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness_info['witness_email']);
                                    $data_to_insert['receiver_sid'] = $witness_id;
                                    $conversation_key = $incident_id . '/' . $id . '/' . $witness_id . '/' . $employer_sid;
                                } else {
                                    $data_to_insert['receiver_sid'] = $manager_id;
                                    $conversation_key = $incident_id . '/' . $id . '/' . $manager_id . '/' . $employer_sid;
                                }
                            } else if (str_replace('_inc_rep', '', $manager_id) != $manager_id) {

                                $inc_reporter_id = str_replace('_inc_rep', '', $manager_id);
                                $inc_reporter_info = db_get_employee_profile($inc_reporter_id);
                                $receiver_email = $inc_reporter_info[0]['email'];
                                $receiver_name = $inc_reporter_info[0]['first_name'] . ' ' . $inc_reporter_info[0]['last_name'];
                                $conversation_key = $incident_id . '/' . $id . '/' . $inc_reporter_id . '/' . $employer_sid;
                                $data_to_insert['receiver_sid'] = $inc_reporter_id;
                            } else {

                                $manager_info = db_get_employee_profile($manager_id);
                                $receiver_email = $manager_info[0]['email'];
                                $receiver_name = $manager_info[0]['first_name'] . ' ' . $manager_info[0]['last_name'];
                                $conversation_key = $incident_id . '/' . $id . '/' . $manager_id . '/' . $employer_sid;
                                $data_to_insert['receiver_sid'] = $manager_id;
                            }

                            $inserted_email_sid = $this->incident_reporting_model->insert_incident_email_record($data_to_insert);

                            if (!empty($attachments)) {
                                foreach ($attachments as $key => $attachment) {

                                    $attachment_type = $attachment['item_type'];
                                    $record_sid = $attachment['record_sid'];
                                    $item_title = '';
                                    $item_type = '';
                                    $item_path = '';

                                    if ($attachment_type == 'Media') {
                                        $record_sid = str_replace("m_", "", $record_sid);
                                        $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'media');
                                        $item_title = $item_info['video_title'];
                                        $item_type  = $item_info['video_type'];
                                        $item_path  = $item_info['video_url'];
                                    } else if ($attachment_type == 'Document') {
                                        $record_sid = str_replace("d_", "", $record_sid);
                                        $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'document');
                                        $item_title = $item_info['document_title'];
                                        $item_type  = $item_info['type'];
                                        $item_path  = $item_info['file_code'];
                                    }

                                    $insert_attachment                      = array();
                                    $insert_attachment['incident_sid']      = $id;
                                    $insert_attachment['email_sid']         = $inserted_email_sid;
                                    $insert_attachment['attachment_type']   = $attachment_type;
                                    $insert_attachment['attachment_sid']    = $record_sid;
                                    $insert_attachment['attached_by']       = $employer_sid;
                                    $insert_attachment['attached_date']     = date('Y-m-d H:i:s');
                                    $insert_attachment['item_title']        = $item_title;
                                    $insert_attachment['item_type']         = $item_type;
                                    $insert_attachment['item_path']         = $item_path;

                                    $this->incident_reporting_model->insert_email_attachment($insert_attachment);
                                }
                            }

                            $url = base_url('incident_reporting_system/view_incident_email/' . $conversation_key);
                            $employeeType = $this->incident_reporting_model->isIncidentManager($receiver_email, $company_sid, $id);
                            //
                        

                            $emailTemplateBody = 'Dear ' . $receiver_name . ', <br>';
                            $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email regarding an assigned incident.</p>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '<p>Please click on the following link to reply.</p>' . '<br>';
                            if($employeeType != "out_sider") {
                                if ($employeeType == "reporter") {
                                    $viewIncident = base_url('incident_reporting_system/view_incident/' . $id);
                                } else if ($employeeType == "incident_manager") {
                                    $viewIncident = base_url('incident_reporting_system/view_single_assign/' . $id);
                                }
                                //
                                $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Incident</a>' . '<br>';
                                $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                            }
                            $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Reply to this Email</a>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email: Please Do Not reply!</strong>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';



                            $from = FROM_EMAIL_NOTIFICATIONS;
                            $to = $receiver_email;

                            $body = $email_hf['header']
                                . $emailTemplateBody
                                . $email_hf['footer'];

                            log_and_sendEmail($from, $to, $subject, $body, $from_name);
                        }

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Send Incident Email Successfully!');
                    } else if ($perform_action == 'add_comment') {
                        $insert_data = array();
                        $insert_data['incident_reporting_id'] = $id;
                        $insert_data['employer_sid'] = $employer_sid;
                        $insert_data['comment'] = $_POST['response'];
                        $insert_data['date_time'] = date('Y-m-d h:i:s');
                        $insert_data['response_type'] = 'Response';
                        $this->incident_reporting_model->add_incident_comment($insert_data);
                        $is_incident_respond = $this->incident_reporting_model->is_incident_respond($id);

                        if (!empty($is_incident_respond)) {
                            $incident_status = array('status' => 'RequireFeedback');
                            $this->incident_reporting_model->update_incident_report($id, $incident_status);
                        }
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Send Incident Comment Successfully!');
                    } else if ($perform_action == 'add_video') {
                        $video_id = '';
                        $upload_file_type = 'Video';
                        $video_source = $this->input->post('video_source');
                        $video_title = $this->input->post('video_title');

                        if (!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {
                            $random = generateRandomString(5);
                            $target_file_name = basename($_FILES["video_upload"]["name"]);
                            $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                            $target_dir = "assets/uploaded_videos/incident_videos/";
                            $target_file = $target_dir . $file_name;
                            $basePath = $target_dir . $company_sid;

                            if (!is_dir($basePath)) {
                                mkdir($basePath, 0777, true);
                            }

                            if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {

                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                            } else {

                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                redirect(current_url(), 'refresh');
                            }

                            $video_id = $file_name;
                        } else if (!empty($_FILES) && isset($_FILES['audio_upload']) && $_FILES['audio_upload']['size'] > 0) {
                            $random = generateRandomString(5);
                            $target_file_name = basename($_FILES["audio_upload"]["name"]);
                            $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                            $target_dir = "assets/uploaded_videos/incident_videos/";
                            $target_file = $target_dir . $file_name;
                            $basePath = $target_dir . $company_sid;

                            if (!is_dir($basePath)) {
                                mkdir($basePath, 0777, true);
                            }

                            if (move_uploaded_file($_FILES["audio_upload"]["tmp_name"], $target_file)) {

                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["audio_upload"]["name"]) . ' has been uploaded.');
                            } else {

                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                redirect(current_url(), 'refresh');
                            }

                            $video_id = $file_name;
                            $upload_file_type = 'Audio';
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

                        $video_to_insert                    = array();
                        $video_to_insert['incident_sid']    = $id;
                        $video_to_insert['video_title']     = $video_title;
                        $video_to_insert['video_type']      = $video_source;
                        $video_to_insert['video_url']       = $video_id;
                        $video_to_insert['user_type']       = 'employee';
                        $video_to_insert['uploaded_by']     = $employer_sid;

                        $this->incident_reporting_model->insert_incident_video_reccord($video_to_insert);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> ' . $upload_file_type . ' is save Successfully!');
                    } else if ($perform_action == 'add_document') {
                        if (!empty($_FILES) && isset($_FILES['upload_document']) && $_FILES['upload_document']['size'] > 0) {
                            $incident_document_s3_name = upload_file_to_aws('upload_document', $company_sid, 'incident_document', $employer_sid, AWS_S3_BUCKET_NAME);

                            $last_index_of_dot = strrpos($_FILES["upload_document"]["name"], '.') + 1;
                            $file_ext = substr($_FILES["upload_document"]["name"], $last_index_of_dot, strlen($_FILES["upload_document"]["name"]) - $last_index_of_dot);

                            $document_title = $this->input->post('document_title');

                            $document_to_insert                             = array();
                            $document_to_insert['document_title']           = $document_title;
                            $document_to_insert['file_name']                = $_FILES["upload_document"]["name"];
                            $document_to_insert['type']                     = $file_ext;
                            $document_to_insert['file_code']                = $incident_document_s3_name;
                            $document_to_insert['user_type']                = 'employee';
                            $document_to_insert['employer_id']              = $employer_sid;
                            $document_to_insert['company_id']               = $company_sid;
                            $document_to_insert['incident_reporting_id']    = $id;

                            $this->incident_reporting_model->insert_incident_docs($document_to_insert);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document is Uploaded Successfully!');
                        }
                    }

                    redirect(current_url(), 'refresh');
                }

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/view_incident_old');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> No incident found!');
                redirect(base_url('incident_reporting_system/list_incidents'), 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_change_incident_type($incident_sid)
    {
        if ($this->session->userdata('logged_in')) {

            $data_to_update = array();
            $data_to_update['report_type'] = 'confidential';
            $this->incident_reporting_model->change_incident_type($incident_sid, $data_to_update);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Incident Type Change Successfully!');

            echo 1;
        } else {
            echo 0;
        }
    }

    public function assigned_incidents()
    {
        if ($this->session->userdata('logged_in')) {
            if (checkIfAppIsEnabled('incidents')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;

                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $data['title'] = 'Assigned Incidents';
                $responded = array();
                $closed    = array();
                $read_only = array();
                $pending   = array();
                $assigned_incidents = array();
                $assigned_incidents = $this->incident_reporting_model->assigned_incidents_new_flow($employer_sid, $company_sid);

                if (!empty($assigned_incidents)) {
                    foreach ($assigned_incidents as $incident) {
                        if ($incident['incident_status'] == 'Closed') {
                            $closed[] = $incident;
                        } elseif ($incident['incident_status'] == 'Responded' || $incident['incident_status'] == 'RequireFeedback') {
                            $responded[] = $incident;
                        } elseif ($incident['report_type'] == 'anonymous') {
                            // $read_only[] = $incident;
                            $pending[] = $incident;
                        } elseif ($incident['incident_status'] == 'Pending') {
                            $pending[] = $incident;
                        }
                    }
                }

                $data['assigned_incidents'] = $assigned_incidents;
                $data['closed'] = $closed;
                $data['responded'] = $responded;
                $data['read_only'] = $read_only;
                $data['pending'] = $pending;
                $data['employee'] = $data['session']['employer_detail'];
                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/assigned_incidents');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('dashboard'), "refresh");
            }      
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function view_single_assign($id)
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $security_sid = $session['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);

            $employer_detail    = $session["employer_detail"];
            $company_sid        = $session["company_detail"]["sid"];
            $employer_sid       = $session["employer_detail"]["sid"];
            $company_name       = $session["company_detail"]['CompanyName'];
            $title              = "Assigned Incidents - View Incident Details";

            $as_managers = array();
            $incident_all_emails = array();
            $load_view = check_blue_panel_status(false, 'self');

            // Fetch incident Type ID
            $incident_id = $this->incident_reporting_model->get_incident_type_id($id, $company_sid);

            // Fetch Incident Name
            $incident_name = $this->incident_reporting_model->get_incident_name_by_id($id, $company_sid);

            //fetch incident Type Name
            $incident_type = $this->incident_reporting_model->get_reported_incident_type($id, $company_sid);

            // Fetch Incident Assigned Manager
            $incident_assigned_managers = $this->incident_reporting_model->fetch_incident_assigned_managers($id, $company_sid);

            //Get All Assigned Manager For Getting All Incident Related Email
            $incident_related_managers = $incident_assigned_managers;

            // Replace Current Manager with Incident Reporter
            if (!empty($incident_assigned_managers)) {
                foreach ($incident_assigned_managers as $key => $incident_manager) {
                    if ($incident_manager['employee_id'] == $employer_sid) {
                        // Remove this below first if/else condition when Anonymous employee
                        if ($incident_type != 'anonymous') {
                            $incident_reporter_sid = $this->incident_reporting_model->fetch_incident_reporter($id);
                            $reporter_info = db_get_employee_profile($incident_reporter_sid);
                            $incident_assigned_managers[$key]['employee_id'] = $incident_reporter_sid . '_inc_rep';
                            if ($incident_type == 'anonymous') {
                                $incident_assigned_managers[$key]['employee_name'] = 'Anonymous ( Incident Reporter )';
                            } else if ($incident_type = 'confidential') {
                                $incident_assigned_managers[$key]['employee_name'] = $reporter_info[0]['first_name'] . ' ' . $reporter_info[0]['last_name'] . ' ( Incident Reporter )';
                            }
                        } else {
                            unset($incident_assigned_managers[$key]);
                        }
                    } else {
                        $incident_assigned_managers[$key]['employee_name'] = $incident_manager['employee_name'] . ' ( Manager )';
                    }

                    $as_managers[$key] = $incident_manager['employee_id'];
                }
            }

            if (!in_array($employer_sid, $as_managers)) {
                $this->session->set_flashdata('message', '<b>Error:</b> No assigned incident found!');
                redirect(base_url('incident_reporting_system/list_incidents'), 'refresh');
            }

            // Fetch All Incident Related Manager
            $incident_managers = $this->incident_reporting_model->fetch_incident_managers($incident_id, $company_sid);

            //Remove Incident Managers Those Are not Assigned This Reported Incident
            if (!empty($incident_managers)) {
                foreach ($incident_managers as $key => $incident_manager) {
                    if (in_array($incident_manager['employee_id'], $as_managers)) {
                        unset($incident_managers[$key]);
                    }
                }
            }

            // Fetch All Active Company Employees For Adding As Witness
            $company_employees = $this->incident_reporting_model->fetch_all_company_employees($company_sid);

            // Fetch Incident Question Answer
            $assigned_incidents = $this->incident_reporting_model->view_single_assign($id);

            // Fetch Incident Witnesses
            $witnesses = $this->incident_reporting_model->get_reporter_incident_witnesses($id, $incident_id);

            $count_incident_managers = count($incident_assigned_managers);

            // Combine Assigned Manager And Incident Witnesses
            foreach ($witnesses as $key => $witness) {
                $employee_as_witness_id = 0;
                if ($witness['witness_type'] == 'employee') {
                    $witness_type = 'Company Witness';
                    $employee_as_witness_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness['witness_email']);
                } else if ($witness['witness_type'] == 'others') {
                    $witness_type = 'Other Witness';
                }

                if (!in_array($employee_as_witness_id, $as_managers)) {
                    $incident_assigned_managers[$count_incident_managers + $key]['employee_id'] = $witness['sid'] . '_wid';
                    $incident_assigned_managers[$count_incident_managers + $key]['employee_name'] = $witness['witness_name'] . ' ( ' . $witness_type . ' )';
                }
            }

            // Fetch Active Videos
            $videos = $this->incident_reporting_model->get_incident_videos($id, "0");

            // Fetch Archive Videos
            $videos_archived = $this->incident_reporting_model->get_incident_videos($id, '1');

            // Fetch Active Document
            $get_incident_document_active = $this->incident_reporting_model->get_incident_report_docs($id, '0');

            // Fetch Archive Document
            $get_incident_document_archived = $this->incident_reporting_model->get_incident_report_docs($id, '1');

            // Fetch All System Emails
            foreach ($incident_related_managers as $email_key => $incident_related_manager) {
                $manager_sid = $incident_related_manager['employee_id'];
                $manager_name = $incident_related_manager['employee_name'];

                $incident_manual_emails = $this->incident_reporting_model->get_manual_emails($manager_sid, $id, $manager_name);

                $incident_emails = array();
                foreach ($incident_assigned_managers as $key => $incident_manager) {
                    $new_emails = '';
                    $sender_sid = '';
                    $reverse_check = 0;
                    $receiver_id = $incident_manager['employee_id'];
                    $receiver_name = $incident_manager['employee_name'];

                    if (str_replace('_wid', '', $receiver_id) != $receiver_id) {

                        $witness_id = str_replace('_wid', '', $receiver_id);
                        $witness_info = $this->incident_reporting_model->get_witness_info_by_id($witness_id, $id);

                        if ($witness_info['witness_type'] == 'employee') {
                            $receiver_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness_info['witness_email']);
                            $new_emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $manager_sid, $id);
                            $sender_sid = $receiver_id;
                        } else {
                            $new_emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $manager_sid, $id);
                            $sender_sid = $receiver_id;
                        }
                    } else if (str_replace('_inc_rep', '', $receiver_id) != $receiver_id) {

                        $inc_reporter_id = str_replace('_inc_rep', '', $receiver_id);
                        $new_emails = $this->incident_reporting_model->get_incident_related_emails($inc_reporter_id, $manager_sid, $id);
                        $sender_sid = $inc_reporter_id;
                    } else {
                        if ($receiver_id == $manager_sid) {
                            $receiver_id = $employer_sid;
                            $reverse_check = 1;
                            $receiver_name = $session['employer_detail']['first_name'] . ' ' . $session['employer_detail']['last_name'] . ' ( Manager )';
                        }
                        $new_emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $manager_sid, $id);
                        $sender_sid = $receiver_id;
                    }

                    if (!empty($new_emails)) {
                        $incident_emails[$key]['name']          = $receiver_name;
                        $incident_emails[$key]['user_one']      = $manager_sid;
                        $incident_emails[$key]['user_two']      = $receiver_id;
                        $incident_emails[$key]['sender_sid']    = $sender_sid;
                        $incident_emails[$key]['incident_id']   = $id;
                        $incident_emails[$key]['emails']        = $new_emails;
                        $incident_emails[$key]['reverse_check'] = $reverse_check;
                    }
                }

                if (!empty($incident_emails) || !empty($incident_manual_emails)) {

                    $incident_all_emails[$email_key]['manager_sid']             = $manager_sid;
                    $incident_all_emails[$email_key]['manager_name']            = $manager_name;
                    $incident_all_emails[$email_key]['incident_emails']         = $incident_emails;
                    $incident_all_emails[$email_key]['incident_manual_emails']  = $incident_manual_emails;

                    if ($manager_sid == $employer_sid) {
                        $incident_all_emails[$email_key]['manager_type'] = 'current';
                    } else {
                        $incident_all_emails[$email_key]['manager_type'] = 'other';
                    }
                }
            }

            // Fetch Document For Library
            $library_documets = $this->incident_reporting_model->get_library_documents($id);

            // Fetch Media For Media
            $library_media = $this->incident_reporting_model->get_library_media($id);

            // Fetch Incident Notes
            $comments = $this->incident_reporting_model->get_incident_comments($id);

            if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

                $perform_action = $_POST['perform_action'];
                if ($perform_action == 'send_email') {

                    $send_email_type = $_POST['send_type'];
                    $attachments = isset($_POST['attachment']) ? $_POST['attachment'] : '';

                    if ($send_email_type == 'manual') {
                        $manual_email   = $_POST['manual_email'];
                        $subject        = $_POST['subject'];
                        $message        = $_POST['message'];

                        $email_hf = message_header_footer_domain($company_sid, $company_name);

                        $manual_email_to_insert = array();
                        $manual_email_to_insert['incident_type_id']         = $incident_id;
                        $manual_email_to_insert['incident_reporting_id']    = $id;
                        $manual_email_to_insert['manual_email']             = $manual_email;
                        $manual_email_to_insert['sender_sid']               = $employer_sid;
                        $manual_email_to_insert['receiver_sid']             = 0;
                        $manual_email_to_insert['subject']                  = $subject;
                        $manual_email_to_insert['message_body']             = $message;

                        $inserted_email_sid = $this->incident_reporting_model->insert_incident_email_record($manual_email_to_insert);
                        $isEmployee = $this->incident_reporting_model->checkManualUserIsAnEmployee($_POST['manual_email'], $company_sid);

                        if (!empty($attachments)) {

                            foreach ($attachments as $key => $attachment) {

                                $attachment_type = $attachment['item_type'];
                                $record_sid = $attachment['record_sid'];
                                $item_title = '';
                                $item_type = '';
                                $item_path = '';

                                if ($attachment_type == 'Media') {
                                    $record_sid = str_replace("m_", "", $record_sid);
                                    $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'media');
                                    $item_title = $item_info['video_title'];
                                    $item_type  = $item_info['video_type'];
                                    $item_path  = $item_info['video_url'];
                                } else if ($attachment_type == 'Document') {
                                    $attachment_type = str_replace("d_", "", $record_sid);
                                    $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'document');
                                    $item_title = $item_info['document_title'];
                                    $item_type  = $item_info['type'];
                                    $item_path  = $item_info['file_code'];
                                }

                                $insert_attachment                      = array();
                                $insert_attachment['incident_sid']      = $id;
                                $insert_attachment['email_sid']         = $inserted_email_sid;
                                $insert_attachment['attachment_type']   = $attachment_type;
                                $insert_attachment['attachment_sid']    = $record_sid;
                                $insert_attachment['attached_by']       = $employer_sid;
                                $insert_attachment['attached_date']     = date('Y-m-d H:i:s');
                                $insert_attachment['item_title']        = $item_title;
                                $insert_attachment['item_type']         = $item_type;
                                $insert_attachment['item_path']         = $item_path;

                                $this->incident_reporting_model->insert_email_attachment($insert_attachment);
                            }
                        }

                        $conversation_key = $incident_id . '/' . $id . '/' . $manual_email . '/' . $employer_sid;
                        $url = base_url('incident_reporting_system/view_incident_email/' . $conversation_key);
                        $from_name = $session["employer_detail"]["first_name"] . ' ' . $session["employer_detail"]["last_name"];
                        $name = explode("@", $manual_email);
                        $receiver_name = $name[0];

                        $emailTemplateBody = 'Dear ' . $receiver_name . ', <br>';
                        $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email about incident.</p>' . '<br>';
                        $emailTemplateBody = $emailTemplateBody . '<p>Please click on the following link to reply.</p>' . '<br>';
                        if ($isEmployee) {
                            $employeeType = $this->incident_reporting_model->isIncidentManager($_POST['manual_email'], $company_sid, $id);
                            //
                            if($employeeType != "out_sider") {
                                if ($employeeType == "reporter") {
                                    $viewIncident = base_url('incident_reporting_system/view_incident/' . $id);
                                } else if ($employeeType == "incident_manager") {
                                    $viewIncident = base_url('incident_reporting_system/view_single_assign/' . $id);
                                }
                                //
                                $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Incident</a>' . '<br>';
                                $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                            }
                        }
                        $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Reply to this Email</a>' . '<br>';
                        $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                        $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
                        $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email: Please Do Not reply!</strong>' . '<br>';
                        $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';

                        $from = FROM_EMAIL_NOTIFICATIONS;
                        $to = $manual_email;

                        $body = $email_hf['header']
                            . $emailTemplateBody
                            . $email_hf['footer'];

                        log_and_sendEmail($from, $to, $subject, $body, $from_name);
                    } else if ('system') {
                        $receivers = $_POST['receivers'];
                        $subject = $_POST['subject'];
                        $from_name = $session["employer_detail"]["first_name"] . ' ' . $session["employer_detail"]["last_name"];
                        $email_hf = message_header_footer_domain($company_sid, $company_name);

                        foreach ($receivers as $key => $receiver_id) {
                            $conversation_key = '';
                            $message_body = $_POST['message'];

                            $data_to_insert = array();
                            $data_to_insert['incident_type_id'] = $incident_id;
                            $data_to_insert['incident_reporting_id'] = $id;
                            $data_to_insert['sender_sid'] = $employer_sid;
                            $data_to_insert['subject'] = $subject;
                            $data_to_insert['message_body'] = $message_body;

                            if (str_replace('_wid', '', $receiver_id) != $receiver_id) {

                                $witness_id = str_replace('_wid', '', $receiver_id);
                                $witness_info = $this->incident_reporting_model->get_witness_info_by_id($witness_id, $id);
                                $receiver_email = $witness_info['witness_email'];
                                $receiver_name = $witness_info['witness_name'];

                                if ($witness_info['witness_type'] == 'employee') {
                                    $witness_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness_info['witness_email']);
                                    $data_to_insert['receiver_sid'] = $witness_id;
                                    $conversation_key = $incident_id . '/' . $id . '/' . $witness_id . '/' . $employer_sid;
                                } else {
                                    $data_to_insert['receiver_sid'] = $receiver_id;
                                    $conversation_key = $incident_id . '/' . $id . '/' . $receiver_id . '/' . $employer_sid;
                                }
                            } else if (str_replace('_inc_rep', '', $receiver_id) != $receiver_id) {

                                $inc_reporter_id = str_replace('_inc_rep', '', $receiver_id);
                                $inc_reporter_info = db_get_employee_profile($inc_reporter_id);
                                $receiver_email = $inc_reporter_info[0]['email'];
                                $receiver_name = $inc_reporter_info[0]['first_name'] . ' ' . $inc_reporter_info[0]['last_name'];
                                $conversation_key = $incident_id . '/' . $id . '/' . $inc_reporter_id . '/' . $employer_sid;
                                $data_to_insert['receiver_sid'] = $inc_reporter_id;
                            } else {

                                $manager_info = db_get_employee_profile($receiver_id);
                                $receiver_email = $manager_info[0]['email'];
                                $receiver_name = $manager_info[0]['first_name'] . ' ' . $manager_info[0]['last_name'];
                                $conversation_key = $incident_id . '/' . $id . '/' . $receiver_id . '/' . $employer_sid;
                                $data_to_insert['receiver_sid'] = $receiver_id;
                            }

                            $inserted_email_sid = $this->incident_reporting_model->insert_incident_email_record($data_to_insert);

                            if (!empty($attachments)) {
                                foreach ($attachments as $key => $attachment) {

                                    $attachment_type    = $attachment['item_type'];
                                    $record_sid         = $attachment['record_sid'];
                                    $item_title         = '';
                                    $item_type          = '';
                                    $item_path          = '';

                                    if ($attachment_type == 'Media') {
                                        $record_sid = str_replace("m_", "", $record_sid);
                                        $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'media');
                                        $item_title = $item_info['video_title'];
                                        $item_type  = $item_info['video_type'];
                                        $item_path  = $item_info['video_url'];
                                    } else if ($attachment_type == 'Document') {
                                        $record_sid = str_replace("d_", "", $record_sid);
                                        $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'document');
                                        $item_title = $item_info['document_title'];
                                        $item_type  = $item_info['type'];
                                        $item_path  = $item_info['file_code'];
                                    }

                                    $insert_attachment                      = array();
                                    $insert_attachment['incident_sid']      = $id;
                                    $insert_attachment['email_sid']         = $inserted_email_sid;
                                    $insert_attachment['attachment_type']   = $attachment_type;
                                    $insert_attachment['attachment_sid']    = $record_sid;
                                    $insert_attachment['attached_by']       = $employer_sid;
                                    $insert_attachment['attached_date']     = date('Y-m-d H:i:s');
                                    $insert_attachment['item_title']        = $item_title;
                                    $insert_attachment['item_type']         = $item_type;
                                    $insert_attachment['item_path']         = $item_path;

                                    $this->incident_reporting_model->insert_email_attachment($insert_attachment);
                                }
                            }

                            $url = base_url('incident_reporting_system/view_incident_email/' . $conversation_key);
                            $employeeType = $this->incident_reporting_model->isIncidentManager($receiver_email, $company_sid, $id);
                            //
                           

                            $emailTemplateBody = 'Dear ' . $receiver_name . ', <br>';
                            $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email about incident.</p>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '<p>Please click on the following link to reply.</p>' . '<br>';
                            if($employeeType != "out_sider") {
                                if ($employeeType == "reporter") {
                                    $viewIncident = base_url('incident_reporting_system/view_incident/' . $id);
                                } else if ($employeeType == "incident_manager") {
                                    $viewIncident = base_url('incident_reporting_system/view_single_assign/' . $id);
                                }
                                //
                                $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Incident</a>' . '<br>';
                                $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                            }
                            $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Reply to this Email</a>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email: Please Do Not reply!</strong>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';

                            $from = FROM_EMAIL_NOTIFICATIONS;
                            $to = $receiver_email;

                            $body = $email_hf['header']
                                . $emailTemplateBody
                                . $email_hf['footer'];

                            log_and_sendEmail($from, $to, $subject, $body, $from_name);
                        }
                    }

                    $this->session->set_flashdata('message', '<strong>Success:</strong> Send Incident Email Successfully!');
                } else if ($perform_action == 'add_comment') {
                    $insert_data = array();
                    $insert_data['incident_reporting_id'] = $id;
                    $insert_data['applicant_sid'] = $employer_sid;
                    $insert_data['comment'] = $_POST['response'];
                    $insert_data['date_time'] = date('Y-m-d h:i:s');
                    $insert_data['response_type'] = $_POST['response_type'];
                    $this->incident_reporting_model->add_incident_comment($insert_data);
                    $incident_status = array('status' => 'Responded');
                    $this->incident_reporting_model->update_incident_report($id, $incident_status);

                    $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);

                    if (!empty($pictures) && $pictures != 'error') {

                        if (isset($_POST['inc_id']) && $_POST['inc_id'] != 0) {
                            $incident = $_POST['inc_id'];
                        } else {
                            $insert['company_sid'] = $company_sid;
                            $insert['employer_sid'] = $employer_sid;
                            $insert['current_date'] = date('Y-m-d H:i:s');
                            $insert['incident_type_id'] = $_POST['id'];
                            $incident = $this->incident_reporting_model->insert_incident_reporting($insert);
                        }

                        $docs = array();
                        $last_index_of_dot = strrpos($_FILES["docs"]["name"], '.') + 1;
                        $file_ext = substr($_FILES["docs"]["name"], $last_index_of_dot, strlen($_FILES["docs"]["name"]) - $last_index_of_dot);
                        $docs['file_name'] = $_FILES["docs"]["name"];
                        $docs['type'] = $file_ext;
                        $docs['file_code'] = $pictures;
                        $docs['company_id'] = $company_sid;

                        if (isset($_POST['assign_check'])) {
                            $docs['employer_id'] = NULL;
                            $docs['applicant_id'] = $employer_sid;
                        } else {
                            $docs['employer_id'] = $employer_sid;
                            $docs['applicant_id'] = NULL;
                        }

                        $docs['incident_reporting_id'] = $incident;
                        $this->incident_reporting_model->insert_incident_docs($docs);
                    }

                    $this->session->set_flashdata('message', '<strong>Success:</strong> Send Incident Comment Successfully!');
                } else if ($perform_action == 'add_video') {
                    $video_id = '';
                    $upload_file_type = 'Video';
                    $video_source = $this->input->post('video_source');
                    $video_title = $this->input->post('video_title');

                    if (!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {
                        $random = generateRandomString(5);
                        $target_file_name = basename($_FILES["video_upload"]["name"]);
                        $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/incident_videos/";
                        $target_file = $target_dir . $file_name;
                        $basePath = $target_dir . $company_sid;

                        if (!is_dir($basePath)) {
                            mkdir($basePath, 0777, true);
                        }

                        if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {

                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                        } else {

                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect(current_url(), 'refresh');
                        }

                        $video_id = $file_name;
                    } else if (!empty($_FILES) && isset($_FILES['audio_upload']) && $_FILES['audio_upload']['size'] > 0) {
                        $random = generateRandomString(5);
                        $target_file_name = basename($_FILES["audio_upload"]["name"]);
                        $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/incident_videos/";
                        $target_file = $target_dir . $file_name;
                        $basePath = $target_dir . $company_sid;

                        if (!is_dir($basePath)) {
                            mkdir($basePath, 0777, true);
                        }

                        if (move_uploaded_file($_FILES["audio_upload"]["tmp_name"], $target_file)) {

                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["audio_upload"]["name"]) . ' has been uploaded.');
                        } else {

                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect(current_url(), 'refresh');
                        }

                        $video_id = $file_name;
                        $upload_file_type = 'Audio';
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

                    $video_to_insert                        = array();
                    $video_to_insert['incident_sid']        = $id;
                    $video_to_insert['video_title']         = $video_title;
                    $video_to_insert['video_type']          = $video_source;
                    $video_to_insert['video_url']           = $video_id;
                    $video_to_insert['user_type']           = 'manager';
                    $video_to_insert['uploaded_by']         = $employer_sid;
                    $video_to_insert['is_incident_reported'] = 1;


                    $this->incident_reporting_model->insert_incident_video_reccord($video_to_insert);

                    $this->session->set_flashdata('message', '<strong>Success:</strong> ' . $upload_file_type . ' is save Successfully!');
                } else if ($perform_action == 'add_document') {
                    if (!empty($_FILES) && isset($_FILES['upload_document']) && $_FILES['upload_document']['size'] > 0) {
                        $incident_document_s3_name = upload_file_to_aws('upload_document', $company_sid, 'incident_document', $employer_sid, AWS_S3_BUCKET_NAME);

                        $last_index_of_dot = strrpos($_FILES["upload_document"]["name"], '.') + 1;
                        $file_ext = substr($_FILES["upload_document"]["name"], $last_index_of_dot, strlen($_FILES["upload_document"]["name"]) - $last_index_of_dot);

                        $document_title = $this->input->post('document_title');

                        $document_to_insert = array();
                        $document_to_insert['document_title']           = $document_title;
                        $document_to_insert['file_name']                = $_FILES["upload_document"]["name"];
                        $document_to_insert['type']                     = $file_ext;
                        $document_to_insert['file_code']                = $incident_document_s3_name;
                        $document_to_insert['user_type']                = 'manager';
                        $document_to_insert['employer_id']              = $employer_sid;
                        $document_to_insert['company_id']               = $company_sid;
                        $document_to_insert['incident_reporting_id']    = $id;

                        $this->incident_reporting_model->insert_incident_docs($document_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document is Uploaded Successfully!');
                    }
                } else if ($perform_action == 'add_witness') {

                    $witnesses = $this->input->post('witnesses');
                    foreach ($witnesses as $key => $witness) {
                        $witness_to_insert = array();
                        $witness_to_insert['incident_type_id']          = $incident_id;
                        $witness_to_insert['incident_reporting_id']     = $id;
                        $witness_to_insert['company_sid']               = $company_sid;
                        $witness_to_insert['witness_type']              = $witness['type'];
                        $witness_to_insert['witness_name']              = $witness['full_name'];
                        $witness_to_insert['witness_phone']             = $witness['phone'];
                        $witness_to_insert['witness_email']             = $witness['email'];
                        $witness_to_insert['witness_title']             = $witness['title'];
                        $witness_to_insert['can_provide_info']          = $witness['can_provide_info'];
                        $witness_to_insert['reported_date']             = date('Y-m-d H:i:s');
                        $witness_to_insert['added_by']                  = $employer_sid;

                        $this->incident_reporting_model->add_new_witness($witness_to_insert);
                    }
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Witness is Added Successfully!');
                }

                // Fetch Manager Status About this Current Incident
                $manager_info = $this->incident_reporting_model->get_assign_manager_info($employer_sid, $company_sid, $id);
                if ($manager_info['incident_status'] == "Pending") {
                    $status_to_update = array();
                    $status_to_update['incident_status'] = 'Responded';

                    // If status Is "pending" then Update It To "respond"
                    $this->incident_reporting_model->update_assign_manager_status($manager_info['sid'], $status_to_update);
                }

                redirect(current_url(), 'refresh');
            }

            $data                                   = array();
            $data['id']                             = $id;
            $data['title']                          = $title;
            $data['videos']                         = $videos;
            $data['session']                        = $session;
            $data['comments']                       = $comments;
            $data['load_view']                      = $load_view;
            $data['witnesses']                      = $witnesses;
            $data['company_sid']                    = $company_sid;
            $data['current_user']                   = $employer_sid;
            $data['employee_sid']                   = $employer_sid;
            $data['incident_name']                  = $incident_name;
            $data['library_media']                  = $library_media;
            $data['library_documets']               = $library_documets;
            $data['employee']                       = $employer_detail;
            $data['videos_archived']                = $videos_archived;
            $data['security_details']               = $security_details;
            $data['employees']                      = $company_employees;
            $data['incident_managers']              = $incident_managers;
            $data['assigned_incidents']             = $assigned_incidents;
            $data['current_incident_type']          = $assigned_incidents;
            // $data['incident_manual_emails']         = $incident_mnaual_emails;
            $data['incident_all_emails']            = $incident_all_emails;
            $data['incident_assigned_managers']     = $incident_assigned_managers;
            $data['get_incident_document']          = $get_incident_document_active;
            $data['get_incident_document_archived'] = $get_incident_document_archived;

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/incident_reporting/view_single_assign');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function save_email_manual_attachment()
    {
        $session = $this->session->userdata('logged_in');
        $employee_sid   = $session["employer_detail"]["sid"];
        $company_sid    = $session['company_detail']['sid'];

        $item_title     = $_POST['attachment_title'];
        $company_sid    = $_POST['company_sid'];
        $incident_sid   = $_POST['incident_sid'];
        $item_source    = $_POST['file_type'];
        $uploaded_by    = $_POST['uploaded_by'];
        $user_type      = $_POST['user_type'];

        if ($user_type == "witness") {
            $uploaded_by = str_replace('_wid', '', $uploaded_by);
        }

        if ($item_source == 'upload_document') {
            if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0) {

                $file_name          = $_POST['file_name'];
                $file_extension     = $_POST['file_ext'];
                $upload_incident_doc = upload_file_to_aws('file', $company_sid, 'file', '', AWS_S3_BUCKET_NAME);

                if (!empty($upload_incident_doc) && $upload_incident_doc != 'error') {

                    $document_to_insert = array();
                    $document_to_insert['document_title']           = $item_title;
                    $document_to_insert['file_name']                = $file_name;
                    $document_to_insert['type']                     = $file_extension;
                    $document_to_insert['file_code']                = $upload_incident_doc;
                    $document_to_insert['user_type']                = $user_type;
                    $document_to_insert['uploaded_date']            = date('Y-m-d H:i:s');
                    $document_to_insert['company_id']               = $company_sid;

                    if (filter_var($uploaded_by, FILTER_VALIDATE_EMAIL)) {
                        $document_to_insert['manual_email']         = $uploaded_by;
                        $document_to_insert['employer_id']          = 0;
                    } else {
                        $document_to_insert['employer_id']          = $uploaded_by;
                    }

                    $document_to_insert['file_type']                = 'attach_file';
                    $document_to_insert['incident_reporting_id']    = $incident_sid;
                    $insert_document_sid = $this->incident_reporting_model->insert_incident_docs($document_to_insert);

                    $return_data                    = array();
                    $return_data['item_sid']        = $insert_document_sid;
                    $return_data['item_title']      = $item_title;
                    $return_data['item_type']       = 'Document';
                    $return_data['item_source']     = strtoupper($file_extension);

                    echo json_encode($return_data);
                }
            } else {
                echo 'error';
            }
        } else {
            $video_id = '';

            if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0 && $item_source == 'upload_video') {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["file"]["name"]);
                $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/incident_videos/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $company_sid;

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

                $video_id = $file_name;
            } else if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0 && $item_source == 'upload_audio') {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["file"]["name"]);
                $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/incident_videos/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $company_sid;

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

                $video_id = $file_name;
            } else {
                if ($item_source == 'youtube') {
                    $youtube_video_link = $_POST['social_url'];
                    $url_prams = array();
                    parse_str(parse_url($youtube_video_link, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_id = $url_prams['v'];
                    } else {
                        $video_id = '';
                    }
                } else {
                    $vimeo_video_link = $_POST['social_url'];
                    $video_id = $this->vimeo_get_id($vimeo_video_link);
                }
            }

            $video_to_insert                            = array();
            $video_to_insert['incident_sid']            = $incident_sid;
            $video_to_insert['video_title']             = $item_title;
            $video_to_insert['video_type']              = $item_source;
            $video_to_insert['video_url']               = $video_id;
            $video_to_insert['user_type']               = $user_type;
            if (filter_var($uploaded_by, FILTER_VALIDATE_EMAIL)) {
                $video_to_insert['manual_email']        = $uploaded_by;
                $video_to_insert['uploaded_by']         = 0;
            } else {
                $video_to_insert['uploaded_by']         = $uploaded_by;
            }

            $video_to_insert['file_type']               = 'attach_file';
            $video_to_insert['is_incident_reported']    = 1;

            $insert_video_sid = $this->incident_reporting_model->insert_incident_video_reccord($video_to_insert);

            $return_data                    = array();
            $return_data['item_sid']        = $insert_video_sid;
            $return_data['item_title']      = $item_title;
            $return_data['item_type']       = 'Media';
            $return_data['item_source']     = $item_source;

            echo json_encode($return_data);
        }
    }

    public function reassign_manager()
    {
        if ($this->session->userdata('logged_in')) {
            $session        = $this->session->userdata('logged_in');
            $company_sid    = $session["company_detail"]["sid"];
            $employer_sid   = $session["employer_detail"]["sid"];

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            $this->form_validation->set_rules('incident_sid', 'incident_sid', 'required|trim');

            if ($this->form_validation->run() == false) {
                redirect(base_url('incident_reporting_system/assigned_incidents'), "refresh");
            } else {
                $incident_sid   = $this->input->post('incident_sid');
                $managers       = $this->input->post('reassign_manager');

                foreach ($managers as $key => $manager) {
                    $data_to_insert                     = array();
                    $data_to_insert['company_sid']      = $company_sid;
                    $data_to_insert['employer_sid']     = $manager;
                    $data_to_insert['assigned_date']    = date('Y-m-d H:i:s');
                    $data_to_insert['incident_sid']     = $incident_sid;
                    $data_to_insert['assigned_status']  = 1;
                    $this->incident_reporting_model->insert_new_manager_to_incident($data_to_insert);
                }

                $data_to_update = array();
                $data_to_update['assigned_status'] = 0;
                $this->incident_reporting_model->update_incident_manager($incident_sid, $company_sid, $employer_sid, $data_to_update);

                $assigned_date = $this->incident_reporting_model->get_assigned_date($incident_sid, $company_sid, $employer_sid);
                $history                    = array();
                $history['company_sid']     = $company_sid;
                $history['employee_sid']    = $employer_sid;
                $history['incident_sid']    = $incident_sid;
                $history['assign_date']     = $assigned_date;
                $history['reassign_date']   = date('Y-m-d H:i:s');
                $this->incident_reporting_model->insert_incident_manager_history($history);

                $this->session->set_flashdata('message', '<strong>Success:</strong> Reassigned Incident Successfully!');
                redirect(base_url('incident_reporting_system/assigned_incidents'), "refresh");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_incident_email($inc_type, $inc_reported_id, $receiver_id, $sender_sid)
    {
        $manual_user = 'no';


        if (!filter_var($receiver_id, FILTER_VALIDATE_EMAIL) && !filter_var($sender_sid, FILTER_VALIDATE_EMAIL)) {
            // Fetch All Emails IF Both Sender-SID And Receiver_SID is Integers 
            $emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $sender_sid, $inc_reported_id);
        } else {
            if (filter_var($receiver_id, FILTER_VALIDATE_EMAIL)) {
                // Fetch All Emails IF Both Sender-SID is Integer And Receiver_SID is Email Address
                $emails = $this->incident_reporting_model->get_incident_emails_by_address($receiver_id, $sender_sid, $inc_reported_id);
            } else if (filter_var($sender_sid, FILTER_VALIDATE_EMAIL)) {
                // Fetch All Emails IF Both Sender-SID is Email Address And Receiver_SID is Integer
                $emails = $this->incident_reporting_model->get_incident_emails_by_address($sender_sid, $receiver_id, $inc_reported_id);
            }

            $manual_user = 'yes';
        }

        if (!empty($emails)) {
            $user_email         = '';
            $user_first_name    = '';
            $user_last_name     = '';
            $user_picture       = '';
            $user_phone         = '';
            $user_type          = '';
            $company_sid        = '';
            $sender_email       = '';
            $is_manager         = 0;
            $incident_users     = array();
            $as_managers        = array();
            $assigned_managers  = array();
            $to_sid             = $sender_sid;
            $from_sid           = $receiver_id;
            $redirect_url       = $inc_type . '/' . $inc_reported_id . '/' . $receiver_id . '/' . $sender_sid;

            if ($manual_user == 'yes' && filter_var($receiver_id, FILTER_VALIDATE_EMAIL)) {
                $user_first_name    = 'N/';
                $user_last_name     = 'A';
                $user_phone         = 'N/A';
                $user_email         = $receiver_id;
                $user_type          = 'manual';
                $company_sid        = $this->incident_reporting_model->get_company_sid_by_incident_id($inc_reported_id);
            } else if (str_replace('_wid', '', $receiver_id) != $receiver_id) {

                $receiver_id = str_replace('_wid', '', $receiver_id);
                $user_info = $this->incident_reporting_model->get_witness_info_by_id($receiver_id, $inc_reported_id);
                $company_sid        = $user_info['company_sid'];
                $user_email         = $user_info['witness_email'];
                $user_phone         = $user_info['witness_phone'];
                $user_type          = 'witness';
                $witness_full_name  = explode(" ", $user_info['witness_name']);
                $user_first_name    = isset($witness_full_name[0]) ? $witness_full_name[0] : '';
                $user_last_name     = isset($witness_full_name[1]) ? $witness_full_name[1] : '';
            } else {
                $user_info = $this->incident_reporting_model->get_employee_info_by_id($receiver_id);
                $company_sid        = $user_info['parent_sid'];
                $user_email         = $user_info['email'];
                $user_picture       = $user_info['profile_picture'];
                $user_phone         = $user_info['PhoneNumber'];
                $user_type          = 'employee';
                $user_name          = $user_info['first_name'] . ' ' . $user_info['last_name'];
                $user_first_name    = isset($user_info['first_name']) ? $user_info['first_name'] : '';
                $user_last_name     = isset($user_info['last_name']) ? $user_info['last_name'] : '';

                $is_manager = $this->incident_reporting_model->check_receiver_status($inc_reported_id, $company_sid, $receiver_id);

                if ($is_manager == 1) {
                    $incident_type = $this->incident_reporting_model->get_reported_incident_type($inc_reported_id, $company_sid);
                    $witnesses = $this->incident_reporting_model->get_all_witnesses($inc_reported_id, $company_sid);
                    $assigned_managers = $this->incident_reporting_model->fetch_incident_assigned_managers($inc_reported_id, $company_sid);

                    foreach ($assigned_managers as $key => $assigned_manager) {
                        if ($assigned_manager['employee_id'] == $receiver_id) {
                            $user_type              = 'manager';
                            $incident_reporter_sid  = $this->incident_reporting_model->fetch_incident_reporter($inc_reported_id);
                            $reporter_info          = db_get_employee_profile($incident_reporter_sid);
                            $assigned_managers[$key]['employee_id'] = $incident_reporter_sid . '_inc_rep';
                            if ($incident_type == 'anonymous') {
                                $assigned_managers[$key]['employee_name'] = 'Anonymous ( Incident Reporter )';
                            } else if ($incident_type = 'confidential') {
                                $assigned_managers[$key]['employee_name'] = $reporter_info[0]['first_name'] . ' ' . $reporter_info[0]['last_name'] . ' ( Incident Reporter )';
                            }
                        } else {
                            $assigned_managers[$key]['employee_name'] = $assigned_manager['employee_name'] . ' ( Manager )';
                        }
                        $as_managers[$key] = $assigned_manager['employee_id'];
                    }

                    $count_incident_managers = count($assigned_managers);

                    foreach ($witnesses as $key => $witness) {

                        if ($witness['witness_type'] == 'employee') {
                            $witness_type = 'Company Witness';
                        } else if ($witness['witness_type'] == 'others') {
                            $witness_type = 'Other Witness';
                        }

                        $assigned_managers[$count_incident_managers + $key]['employee_id'] = $witness['sid'] . '_wid';
                        $assigned_managers[$count_incident_managers + $key]['employee_name'] = $witness['witness_name'] . ' ( ' . $witness_type . ' )';
                    }

                    $incident_users = $assigned_managers;
                }
            }

            if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                $sender_sid = str_replace('_wid', '', $sender_sid);
                $sender_info = $this->incident_reporting_model->get_witness_info_by_id($sender_sid, $inc_reported_id);
                $sender_email = $sender_info['witness_email'];
            } else {
                if ($manual_user == 'yes' && filter_var($sender_sid, FILTER_VALIDATE_EMAIL)) {
                    $sender_email = $to_sid;
                } else {
                    $sender_info = $this->incident_reporting_model->get_employee_info_by_id($sender_sid);
                    $sender_email = $sender_info['email'];
                }
            }

            $company_name = $this->incident_reporting_model->get_company_name_by_sid($company_sid);

            if ($user_type == "manager") {
                // Fetch Document For Media
                $library_documets = $this->incident_reporting_model->get_library_documents($inc_reported_id);

                // Fetch Media For Media
                $library_media = $this->incident_reporting_model->get_library_media($inc_reported_id);
            } else {
                if ($user_type == "witness") {
                    $from_sid = str_replace('_wid', '', $from_sid);
                }
                // Fetch Document For Library
                $library_documets = $this->incident_reporting_model->get_user_library_documents($inc_reported_id, $from_sid, $user_type);

                // Fetch Media For Media
                $library_media = $this->incident_reporting_model->get_user_library_media($inc_reported_id, $from_sid, $user_type);
            }


            $data                       = array();
            $data['emails']             = $emails;
            $data['title']              = 'Incident Emails';
            $data['company_sid']        = $company_sid;
            $data['company_name']       = $company_name;
            $data['user_first_name']    = $user_first_name;
            $data['user_last_name']     = $user_last_name;
            $data['user_email']         = $user_email;
            $data['user_picture']       = $user_picture;
            $data['user_phone']         = $user_phone;
            $data['user_type']          = $user_type;
            $data['sender_email']       = $sender_email;
            $data['user_sid']           = $from_sid;
            $data['incident_users']     = $incident_users;
            $data['current_user']       = $from_sid;
            $data['receiver_user']      = $to_sid;
            $data['incident_sid']       = $inc_reported_id;
            $data['library_media']      = $library_media;
            $data['library_documets']   = $library_documets;


            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            $this->form_validation->set_rules('subject', 'subject', 'required|trim');
            $this->form_validation->set_rules('message', 'message', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->load->view('onboarding/onboarding_public_header', $data);
                $this->load->view('onboarding/view_incident_messages');
                $this->load->view('onboarding/onboarding_public_footer');
            } else {

                $send_email_type = 'system';
                $attachments = isset($_POST['attachment']) ? $_POST['attachment'] : '';

                if (isset($_POST['send_type'])) {
                    $send_email_type = $this->input->post('send_type');
                }

                if ($send_email_type == 'manual') {
                    $manual_email   = $this->input->post('manual_email');
                    $subject        = $this->input->post('subject');
                    $message        = $this->input->post('message');

                    $email_hf = message_header_footer_domain($company_sid, $company_name);

                    $manual_email_to_insert = array();
                    $manual_email_to_insert['incident_type_id']         = $inc_type;
                    $manual_email_to_insert['incident_reporting_id']    = $inc_reported_id;
                    $manual_email_to_insert['manual_email']             = $manual_email;
                    $manual_email_to_insert['sender_sid']               = $from_sid;
                    $manual_email_to_insert['receiver_sid']             = 0;
                    $manual_email_to_insert['subject']                  = $subject;
                    $manual_email_to_insert['message_body']             = $message;

                    $inserted_email_sid = $this->incident_reporting_model->insert_incident_email_record($manual_email_to_insert);

                    if (!empty($attachments)) {
                        foreach ($attachments as $key => $attachment) {

                            $attachment_type = $attachment['item_type'];
                            $record_sid = $attachment['record_sid'];
                            $item_title = '';
                            $item_type = '';
                            $item_path = '';

                            if ($attachment_type == 'Media') {
                                $record_sid = str_replace("m_", "", $record_sid);
                                $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'media');
                                $item_title = $item_info['video_title'];
                                $item_type  = $item_info['video_type'];
                                $item_path  = $item_info['video_url'];
                            } else if ($attachment_type == 'Document') {
                                $record_sid = str_replace("d_", "", $record_sid);
                                $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'document');
                                $item_title = $item_info['document_title'];
                                $item_type  = $item_info['type'];
                                $item_path  = $item_info['file_code'];
                            }

                            $insert_attachment                      = array();
                            $insert_attachment['incident_sid']      = $inc_reported_id;
                            $insert_attachment['email_sid']         = $inserted_email_sid;
                            $insert_attachment['attachment_type']   = $attachment_type;
                            $insert_attachment['attachment_sid']    = $record_sid;
                            $insert_attachment['attached_by']       = $from_sid;
                            $insert_attachment['attached_date']     = date('Y-m-d H:i:s');
                            $insert_attachment['item_title']        = $item_title;
                            $insert_attachment['item_type']         = $item_type;
                            $insert_attachment['item_path']         = $item_path;

                            $this->incident_reporting_model->insert_email_attachment($insert_attachment);
                        }
                    }

                    $conversation_key = $inc_type . '/' . $inc_reported_id . '/' . $manual_email . '/' . $from_sid;
                    $url = base_url('incident_reporting_system/view_incident_email/' . $conversation_key);
                    $from_name = $user_first_name . ' ' . $user_last_name;
                    $name = explode("@", $manual_email);
                    $receiver_name = $name[0];
                    //
                    $isEmployee = $this->incident_reporting_model->checkManualUserIsAnEmployee($manual_email, $company_sid);

                    $emailTemplateBody = 'Dear ' . $receiver_name . ', <br>';
                    $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email about incident.</p>' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '<p>Please click on the following link to reply.</p>' . '<br>';
                    if ($isEmployee) {
                        $employeeType = $this->incident_reporting_model->isIncidentManager($manual_email, $company_sid, $inc_reported_id);
                        //
                        if($employeeType != "out_sider") {
                            if ($employeeType == "reporter") {
                                $viewIncident = base_url('incident_reporting_system/view_incident/' . $inc_reported_id);
                            } else if ($employeeType == "incident_manager") {
                                $viewIncident = base_url('incident_reporting_system/view_single_assign/' . $inc_reported_id);
                            }
                            //
                            $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Incident</a>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                        }
                    }
                    $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Reply to this Email</a>' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email: Please Do Not reply!</strong>' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';



                    $from = FROM_EMAIL_NOTIFICATIONS;
                    $to = $manual_email;

                    $body = $email_hf['header']
                        . $emailTemplateBody
                        . $email_hf['footer'];

                    log_and_sendEmail($from, $to, $subject, $body, $from_name);
                } else {

                    $subject        = $this->input->post('subject');
                    $message        = $this->input->post('message');
                    $receivers      = $this->input->post('receivers');

                    if ($is_manager == 0) {
                        $receivers[0] = $to_sid;
                    }

                    if ($manual_user == 'no') {
                        $from_name = $user_first_name . ' ' . $user_last_name;
                    } else {
                        if ($is_manager == 1) {
                            $from_name = $user_first_name . ' ' . $user_last_name;
                        } else {
                            $from_name = $from_sid;
                        }
                    }

                    $email_hf = message_header_footer_domain($company_sid, $company_name);

                    foreach ($receivers as $key => $receiver_id) {
                        $conversation_key = '';
                        $message_body = $message;

                        $data_to_insert = array();
                        $data_to_insert['incident_type_id'] = $inc_type;
                        $data_to_insert['incident_reporting_id'] = $inc_reported_id;

                        if ($manual_user == 'no') {
                            $data_to_insert['sender_sid'] = $from_sid;
                        } else {
                            if ($is_manager == 1) {
                                $data_to_insert['sender_sid'] = $from_sid;
                            } else {
                                $data_to_insert['sender_sid'] = 0;
                                $data_to_insert['manual_email'] = $from_sid;
                            }
                        }
                        $data_to_insert['subject'] = $subject;
                        $data_to_insert['message_body'] = $message_body;

                        if (str_replace('_wid', '', $receiver_id) != $receiver_id) {

                            $witness_id = str_replace('_wid', '', $receiver_id);
                            $witness_info = $this->incident_reporting_model->get_witness_info_by_id($witness_id, $inc_reported_id);
                            $receiver_email = $witness_info['witness_email'];
                            $receiver_name = $witness_info['witness_name'];

                            if ($witness_info['witness_type'] == 'employee') {
                                $witness_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness_info['witness_email']);
                                $data_to_insert['receiver_sid'] = $witness_id;
                                $conversation_key = $inc_type . '/' . $inc_reported_id . '/' . $witness_id . '/' . $from_sid;
                            } else {
                                $data_to_insert['receiver_sid'] = $receiver_id;
                                $conversation_key = $inc_type . '/' . $inc_reported_id . '/' . $receiver_id . '/' . $from_sid;
                            }
                        } else if (str_replace('_inc_rep', '', $receiver_id) != $receiver_id) {

                            $inc_reporter_id = str_replace('_inc_rep', '', $receiver_id);
                            $inc_reporter_info = db_get_employee_profile($inc_reporter_id);
                            $receiver_email = $inc_reporter_info[0]['email'];
                            $receiver_name = $inc_reporter_info[0]['first_name'] . ' ' . $inc_reporter_info[0]['last_name'];
                            $conversation_key = $inc_type . '/' . $inc_reported_id . '/' . $inc_reporter_id . '/' . $from_sid;
                            $data_to_insert['receiver_sid'] = $inc_reporter_id;
                        } else {

                            $manager_info = db_get_employee_profile($receiver_id);
                            $receiver_email = $manager_info[0]['email'];
                            $receiver_name = $manager_info[0]['first_name'] . ' ' . $manager_info[0]['last_name'];
                            $conversation_key = $inc_type . '/' . $inc_reported_id . '/' . $receiver_id . '/' . $from_sid;
                            $data_to_insert['receiver_sid'] = $receiver_id;
                        }

                        $inserted_email_sid = $this->incident_reporting_model->insert_incident_email_record($data_to_insert);

                        if (!empty($attachments)) {
                            foreach ($attachments as $key => $attachment) {

                                $attachment_type = $attachment['item_type'];
                                $record_sid = $attachment['record_sid'];
                                $item_title = '';
                                $item_type = '';
                                $item_path = '';

                                if ($attachment_type == 'Media') {
                                    $record_sid = str_replace("m_", "", $record_sid);
                                    $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'media');
                                    $item_title = $item_info['video_title'];
                                    $item_type  = $item_info['video_type'];
                                    $item_path  = $item_info['video_url'];
                                } else if ($attachment_type == 'Document') {
                                    $record_sid = str_replace("d_", "", $record_sid);
                                    $item_info  = $this->incident_reporting_model->get_attach_file_info($record_sid, 'document');
                                    $item_title = $item_info['document_title'];
                                    $item_type  = $item_info['type'];
                                    $item_path  = $item_info['file_code'];
                                }

                                $insert_attachment                      = array();
                                $insert_attachment['incident_sid']      = $inc_reported_id;
                                $insert_attachment['email_sid']         = $inserted_email_sid;
                                $insert_attachment['attachment_type']   = $attachment_type;
                                $insert_attachment['attachment_sid']    = $record_sid;
                                $insert_attachment['attached_by']       = $from_sid;
                                $insert_attachment['attached_date']     = date('Y-m-d H:i:s');
                                $insert_attachment['item_title']        = $item_title;
                                $insert_attachment['item_type']         = $item_type;
                                $insert_attachment['item_path']         = $item_path;

                                $this->incident_reporting_model->insert_email_attachment($insert_attachment);
                            }
                        }

                        $url = base_url('incident_reporting_system/view_incident_email/' . $conversation_key);
                        $employeeType = $this->incident_reporting_model->isIncidentManager($receiver_email, $company_sid, $inc_reported_id);
                        //

                        $emailTemplateBody = 'Dear ' . $receiver_name . ', <br>';
                        if ($is_manager == 1) {
                            if (in_array($receiver_id, $as_managers)) {
                                $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email regarding an assigned incident.</p>' . '<br>';
                            } else {
                                $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email about incident.</p>' . '<br>';
                            }
                        } else {
                            $emailTemplateBody = $emailTemplateBody . '<p><strong>' . $from_name . '</strong> has sent you a new email regarding an assigned incident.</p>' . '<br>';
                        }
                        $emailTemplateBody = $emailTemplateBody . '<p>Please click on the following link to reply.</p>' . '<br>';
                        if($employeeType != "out_sider") {
                            if ($employeeType == "reporter") {
                                $viewIncident = base_url('incident_reporting_system/view_incident/' . $inc_reported_id);
                            } else if ($employeeType == "incident_manager") {
                                $viewIncident = base_url('incident_reporting_system/view_single_assign/' . $inc_reported_id);
                            }
                            //
                            $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $viewIncident . '">View Incident</a>' . '<br>';
                            $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                        }
                        $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Reply to this Email</a>' . '<br>';
                        $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                        $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
                        $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email: Please Do Not reply!</strong>' . '<br>';
                        $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';

                        $from = FROM_EMAIL_NOTIFICATIONS;
                        $to = $receiver_email;

                        $body = $email_hf['header']
                            . $emailTemplateBody
                            . $email_hf['footer'];

                        log_and_sendEmail($from, $to, $subject, $body, $from_name);
                    }
                }

                if ($is_manager == 1) {
                    // Fetch Manager Status About this Current Incident
                    $manager_info = $this->incident_reporting_model->get_assign_manager_info($from_sid, $company_sid, $inc_reported_id);
                    if ($manager_info['incident_status'] == "Pending") {
                        $status_to_update = array();
                        $status_to_update['incident_status'] = 'Responded';

                        // If status Is "pending" then Update It To "respond"
                        $this->incident_reporting_model->update_assign_manager_status($manager_info['sid'], $status_to_update);
                    }
                }


                redirect(base_url('incident_reporting_system/view_incident_email') . '/' . $redirect_url, "refresh");
            }
        } else {
            $this->load->view('onboarding/onboarding_error');
        }
    }

    public function view_general_guide($inc_id = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['employee'] = $data["session"]["employer_detail"];
            // check_access_permissions($security_details, 'dashboard', 'incident_reporting_system');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            $data['title'] = "Reports General User Guide";

            if ($inc_id == NULL) {
                $data['gen_guide'] = $this->incident_reporting_model->fetch_general_guide($company_sid);
            } else {
                $data['gen_guide'] = $this->incident_reporting_model->fetch_general_guide($company_sid, $inc_id);
            }

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/incident_reporting/general_guide_old');
            $this->load->view('main/footer');
        }
    }

    public function safety_check_list()
    {
        if ($this->session->userdata('logged_in')) {
            if (checkIfAppIsEnabled('incidents')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                // check_access_permissions($security_details, 'dashboard', 'incident_reporting_system');
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $data['title'] = 'Safety CheckList';
                $types = $this->incident_reporting_model->get_incident_types_company_specific_safety_sheets($company_sid);
                $data['checklists'] = $types;
                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $data['employee'] = $data['session']['employer_detail'];
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/sefety_checklist');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('dashboard'), "refresh");
            }    
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function print_and_download_checklist($type, $checklist_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $company_name = $session['company_detail']['CompanyName'];
            $employee_name = $session['employer_detail']['first_name'] . ' ' . $session['employer_detail']['last_name'];

            $incident_details = $this->incident_reporting_model->fetch_reports_user_guide($checklist_sid);

            if (empty($incident_details)) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Safety Checklist found!');
                redirect(base_url('incident_reporting_system/safety_check_list'), 'refresh');
            } else {
                $questions = $this->incident_reporting_model->fetch_all_question($checklist_sid);
                $data['title'] = $incident_details[0]['incident_name'];
                $data['incident_details'] = $incident_details;
                $data['questions'] = $questions;
                $data['company_name'] = $company_name;
                $data['employee_name'] = $employee_name;

                if ($type == 'download') {
                    $data['action'] = $type;
                    $data['action_date'] = 'Downloaded Date';
                    $data['action_by'] = "Downloaded By";
                } else if ($type == 'print') {
                    $data['action'] = $type;
                    $data['action_date'] = 'Printed Date';
                    $data['action_by'] = "Printed By";
                }

                $this->load->view('manage_employer/incident_reporting/print_and_download_checklist', $data);
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_checklist($id)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            // check_access_permissions($security_details, 'dashboard', 'incident_reporting_system');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['employee'] = $data['session']['employer_detail'];
            $incident_details = $this->incident_reporting_model->fetch_reports_user_guide($id);

            if (empty($incident_details)) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Safety Checklist found!');
                redirect(base_url('incident_reporting_system/safety_check_list'), 'refresh');
            }

            $data['title'] = $incident_details[0]['incident_name'];
            $data['id'] = $id;
            $questions = $this->incident_reporting_model->fetch_all_question($id);

            if (isset($_POST) && isset($_POST['submit'])) {
                unset($_POST['submit']);
                $formpost = $this->input->post(NULL, TRUE);
                $insert = array();
                $original_items = array();

                foreach ($questions as $key => $value) {
                    $original_checklist = array(
                        'Question' => $value['label']
                    );

                    array_push($original_items, $original_checklist);
                }

                $insert['original_checklist'] = serialize($original_items);
                $type = $formpost['submittion_type'];

                if ($type == 'digital') {
                    $submitted_items = array();

                    foreach ($_POST as $key => $value) {
                        $question = str_replace('%0D%0A', '', $key);

                        if ($question != 'safety_name' && $question != 'submittion_type' && $question != '0') {
                            $submitted_checklist = array(
                                'Question' => str_replace('%0D%0A', '', $key),
                                'Answer' => $value
                            );

                            array_push($submitted_items, $submitted_checklist);
                        }
                    }

                    $insert['submitted_checklist'] = serialize($submitted_items);
                    $insert['uploaded_checklist'] = NULL;
                    $insert['type'] = 'submitted';
                } elseif ($type == 'analog') {

                    if (isset($_FILES['safety_checklist_docs']) && $_FILES['safety_checklist_docs']['name'] != '') {
                        $upload_checklist = upload_file_to_aws('safety_checklist_docs', $company_sid, 'safety_checklist_docs');

                        if ($upload_checklist != 'error') {
                            $insert['uploaded_checklist'] = $upload_checklist;
                        }

                        $insert['submitted_checklist'] = NULL;
                        $insert['type'] = 'uploaded';
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> Please Upload Safety Checklist');
                        redirect(base_url('incident_reporting_system/safety_check_list'), "refresh");
                    }
                }

                $insert['submitted_time'] = date('Y-m-d H:i:s');
                $insert['submitted_checklist_name'] = $formpost['safety_name'];
                $insert['safety_checklist_sid'] = $id;
                $insert['submitted_by'] = $employer_sid;
                $this->incident_reporting_model->insert_safety_checklist($insert);
                $this->session->set_flashdata('message', '<b>Success:</b> New Safety Checklist Added');
                redirect(base_url('incident_reporting_system/safety_check_list'), "refresh");
            }

            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            $data['questions'] = $questions;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/incident_reporting/view_safety_checklist');
            $this->load->view('main/footer');
        }
    }

    public function safety_checklist()
    {
        if ($this->session->userdata('logged_in')) {
            if (checkIfAppIsEnabled('incidents')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                // check_access_permissions($security_details, 'dashboard', 'incident_reporting_system');
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $data['title'] = 'Safety Checklist';
                $safety_checklists = $this->incident_reporting_model->get_submitted_safety_checklist();
                $data['safety_checklists'] = $safety_checklists;
                $data['employee'] = $data['session']['employer_detail'];
                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/safety_sheets/safety_checklist_listing');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('dashboard'), "refresh");
            }    
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function view_safety_checklist($sid)
    {
        if ($this->session->userdata('logged_in')) {
            if (checkIfAppIsEnabled('incidents')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                // check_access_permissions($security_details, 'dashboard', 'incident_reporting_system');
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $data['title'] = 'Safety Checklist';
                $checklist = $this->incident_reporting_model->view_submitted_safety_checklist($sid);
                $unserialize_checklist = unserialize($checklist[0]['submitted_checklist']);
                $question = array();
                $answer = array();

                if ($checklist[0]['type'] == 'submitted') {
                    foreach ($unserialize_checklist as $key => $value) {
                        if ($value['Question'] == 'full-name') {
                            $userName = $value['Answer'];
                        } else {
                            array_push($question, $value['Question']);
                            array_push($answer, $value['Answer']);
                        }
                    }

                    $data['questions'] = $question;
                    $data['answer'] = $answer;
                    $data['submitted_userName'] = $userName;
                } else {
                    $userName = $this->incident_reporting_model->submitted_checklist_user($checklist[0]['submitted_by']);
                    $data['submitted_userName'] = $userName[0]['username'];
                    $data['uploaded_checklist'] = $checklist[0]['uploaded_checklist'];
                }

                $data['sub_title'] = $checklist[0]['submitted_checklist_name'];
                $data['submitted_time'] = $checklist[0]['submitted_time'];
                $data['submitted_type'] = $checklist[0]['type'];
                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/safety_sheets/view_safety_checklist');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('dashboard'), "refresh");
            }      
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function mark_resolved()
    {
        if ($this->input->is_ajax_request()) {
            //
            //
            $session = $this->session->userdata('logged_in');
            //
            $id = $this->input->post('id');
            $status = array('status' => 'Closed');
            $this->incident_reporting_model->update_incident_report($id, $status);
            //
            $status_to_update = array();
            $status_to_update['incident_status'] = 'Closed';
            $this->db->where('incident_sid', $id);
            $this->db->where('employer_sid', $session['employer_detail']['sid']);
		    $this->db->update('incident_assigned_emp', $status_to_update);
            // If status Is "pending" then Update It To "respond"
            //
            $data_to_insert = array();
            $data_to_insert['incident_id'] = $id;
            $data_to_insert['manager_id'] = $session['employer_detail']['sid'];
            //
            $this->incident_reporting_model->insert_incident_report_log($data_to_insert);
            //
            echo 'Done';
        }
    }

    public function print_and_download($employee_type, $report_type = 0, $section, $action, $incident_sid, $user_one = NULL, $user_two = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $session        = $this->session->userdata('logged_in');
            $employee       = $session['employer_detail'];
            $company_sid    = $session["company_detail"]['sid'];
            $employee_sid   = $session['employer_detail']['sid'];
            $company_name   = $session["company_detail"]['CompanyName'];
            $employee_name  = $session['employer_detail']['first_name'] . ' ' . $session['employer_detail']['last_name'];
            $title          = 'Incident Reported Emails';

            $data = array();
            $data['title']          = $title;
            $data['section']        = $section;
            $data['company_name']   = $company_name;
            $data['current_user']   = $employee_sid;
            $data['incident_sid']   = $incident_sid;
            $data['employee_name']  = $employee_name;

            // Fetch Reported Incident Detail 
            $incident_detail = $this->incident_reporting_model->get_incident_detail($incident_sid);

            // Process Reported Incident Detail
            if (!empty($incident_detail)) {
                // Create Current Incident Info 
                $incident_type          = $incident_detail[0]['report_type'];
                $incident_name          = $incident_detail[0]['incident_name'];
                $incident_reporter_sid  = $incident_detail[0]['employer_sid'];
                $incident_reported_date = $incident_detail[0]['current_date'];

                $data['incident_name']          = $incident_name;
                $data['incident_type']          = $incident_type;
                $data['incident_reporter_sid']  = $incident_reporter_sid;
                $data['incident_reported_date'] = $incident_reported_date;

                // Create Incident Reporter Info 
                if ($incident_type != 'anonymous') {
                    $incident_reporter_info     = db_get_employee_profile($incident_reporter_sid);
                    $incident_reporter_name     = $incident_reporter_info[0]['first_name'] . ' ' . $incident_reporter_info[0]['last_name'];
                    $incident_reporter_email    = $incident_reporter_info[0]['email'];
                    $incident_reporter_phone    = isset($incident_reporter_info[0]['PhoneNumber']) && !empty($incident_reporter_info[0]['PhoneNumber']) ? $incident_reporter_info[0]['PhoneNumber'] : 'N/A';

                    $incident_reporter_title = $this->incident_reporting_model->get_employee_title($incident_reporter_sid);
                    $data['incident_reporter_name']     = $incident_reporter_name;
                    $data['incident_reporter_email']    = $incident_reporter_email;
                    $data['incident_reporter_phone']    = $incident_reporter_phone;
                    $data['incident_reporter_title']    = $incident_reporter_title;
                } else {
                    $data['incident_reporter_name']     = 'Anonymous';
                    $data['incident_reporter_email']    = 'Anonymous';
                    $data['incident_reporter_phone']    = 'Anonymous';
                    $data['incident_reporter_title']    = 'Anonymous';
                }
            }

            // Process Print Or Download Incident
            if ($section == 'single_email') {
                $single_email = $this->incident_reporting_model->get_incident_related_single_email($user_one, $incident_sid);
                $data['single_email'] = $single_email;
            } else if ($section == 'emails') {
                if (!filter_var($user_one, FILTER_VALIDATE_EMAIL) && !filter_var($user_two, FILTER_VALIDATE_EMAIL)) {
                    if (str_replace('_inc_rep', '', $user_two) != $user_two) {
                        $user_two = str_replace('_inc_rep', '', $user_two);
                    }

                    // Fetch System Emails
                    $emails = $this->incident_reporting_model->get_incident_related_emails($user_one, $user_two, $incident_sid);
                    $data['emails'] = $emails;
                } else {

                    if (filter_var($user_one, FILTER_VALIDATE_EMAIL)) {
                        // Fetch Manual Emails
                        $manual_emails = $this->incident_reporting_model->get_incident_emails_by_address($user_one, $user_two, $incident_sid);
                    } else if (filter_var($user_two, FILTER_VALIDATE_EMAIL)) {
                        // Fetch Manual Emails
                        $manual_emails = $this->incident_reporting_model->get_incident_emails_by_address($user_two, $user_one, $incident_sid);
                    }

                    $data['manual_emails'] = $manual_emails;
                }
            } else if ($section == 'all_emails') {

                $assign_managers       = array();
                $incident_all_emails    = array();

                // Fetch Incident Type ID
                $incident_type_id = $this->incident_reporting_model->get_incident_type_id($incident_sid, $company_sid);

                // Fetch Incident Assigned Manager
                $incident_assigned_managers = $this->incident_reporting_model->fetch_incident_assigned_managers($incident_sid, $company_sid);

                $employees_name = array_column($incident_assigned_managers, 'employee_name');

                array_multisort($employees_name, SORT_ASC, $incident_assigned_managers);

                //Get All Assigned Manager For Getting All Incident Related Email
                $incident_related_managers = $incident_assigned_managers;

                // Replace Current Manager with Incident Reporter
                if (!empty($incident_assigned_managers)) {
                    foreach ($incident_assigned_managers as $key => $incident_manager) {
                        if ($incident_manager['employee_id'] == $employee_sid) {
                            $incident_assigned_managers[$key]['employee_id'] = $incident_reporter_sid;
                        }

                        $assign_managers[$key] = $incident_manager['employee_id'];
                    }
                }

                // Fetch Incident Manager
                $incident_managers = $this->incident_reporting_model->fetch_incident_managers($incident_type_id, $company_sid);

                //Remove Incident Managers Those Are not Assigned This Reported Incident
                if (!empty($incident_managers)) {
                    foreach ($incident_managers as $key => $incident_manager) {
                        if (in_array($incident_manager['employee_id'], $assign_managers)) {
                            unset($incident_managers[$key]);
                        }
                    }
                }

                // Fetch Incident Witnesses
                $witnesses = $this->incident_reporting_model->get_incident_related_witnesses($incident_sid);
                $count_incident_managers = count($incident_assigned_managers);

                foreach ($witnesses as $key => $witness) {
                    $employee_as_witness_id = 0;
                    if ($witness['witness_type'] == 'employee') {
                        $employee_as_witness_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness['witness_email']);
                    }

                    if (!in_array($employee_as_witness_id, $assign_managers)) {
                        $incident_assigned_managers[$count_incident_managers + $key]['employee_id'] = $witness['sid'] . '_wid';
                    }
                }

                // Fetch All System Emails
                foreach ($incident_related_managers as $email_key => $incident_related_manager) {
                    $manager_sid = $incident_related_manager['employee_id'];

                    $incident_manual_emails = $this->incident_reporting_model->get_manual_emails($manager_sid, $incident_sid, $employee_name);

                    $incident_emails = array();
                    foreach ($incident_assigned_managers as $key => $incident_manager) {
                        $new_emails = '';
                        $user_one_data = db_get_employee_profile($manager_sid);
                        $user_one_name = $user_one_data[0]['first_name'] . ' ' . $user_one_data[0]['last_name'];
                        $user_two_name = '';

                        $receiver_id   = $incident_manager['employee_id'];

                        if (str_replace('_wid', '', $receiver_id) != $receiver_id) {

                            $witness_id = str_replace('_wid', '', $receiver_id);
                            $witness_info = $this->incident_reporting_model->get_witness_info_by_id($witness_id, $incident_sid);
                            $user_two_name = $witness_info['witness_name'];

                            if ($witness_info['witness_type'] == 'employee') {
                                $witness_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness_info['witness_email']);
                                $new_emails = $this->incident_reporting_model->get_incident_related_emails($witness_id, $manager_sid, $incident_sid);
                            } else {
                                $new_emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $manager_sid, $incident_sid);
                            }
                        } else if (str_replace('_inc_rep', '', $receiver_id) != $receiver_id) {

                            $inc_reporter_id = str_replace('_inc_rep', '', $receiver_id);
                            $new_emails = $this->incident_reporting_model->get_incident_related_emails($inc_reporter_id, $manager_sid, $incident_sid);

                            $user_two_data = db_get_employee_profile($inc_reporter_id);
                            $user_two_name = $user_two_data[0]['first_name'] . ' ' . $user_two_data[0]['last_name'];
                        } else {
                            $new_emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $manager_sid, $incident_sid);

                            $user_two_data = db_get_employee_profile($receiver_id);
                            $user_two_name = $user_two_data[0]['first_name'] . ' ' . $user_two_data[0]['last_name'];
                        }



                        if (!empty($new_emails)) {
                            $incident_emails[$key]['emails']        = $new_emails;
                            $incident_emails[$key]['section_name']  = $user_one_name . ' & ' . $user_two_name;
                        }
                    }

                    $manger_data = db_get_employee_profile($manager_sid);
                    $manger_name = $manger_data[0]['first_name'] . ' ' . $manger_data[0]['last_name'];

                    if (!empty($incident_emails) || !empty($incident_manual_emails)) {
                        $incident_all_emails[$email_key]['manager_sid'] = $manager_sid;
                        $incident_all_emails[$email_key]['manger_name'] = $manger_name;
                        $incident_all_emails[$email_key]['incident_emails'] = $incident_emails;
                        $incident_all_emails[$email_key]['incident_manual_emails'] = $incident_manual_emails;
                    }
                }

                $data['incident_all_emails']        = $incident_all_emails;
            } else if ($section == 'comments') {

                // Fetch Incident Notes
                $comments = $this->incident_reporting_model->get_incident_related_comments($incident_sid);
                $data['comments'] = $comments;
            } else if ($section == 'questions') {

                // Fetch Incident Question Answer
                $questions = $this->incident_reporting_model->view_single_assign($incident_sid);
                $data['questions'] = $questions;

                // Fetch Incident Witnesses
                $witnesses          = $this->incident_reporting_model->get_incident_related_witnesses($incident_sid);
                $data['witnesses']  = $witnesses;
            } else if ($section == 'all') {

                $incident_all_emails    = array();
                $assign_managers        = array();

                // Fetch Incident Type ID
                $incident_type_id = $this->incident_reporting_model->get_incident_type_id($incident_sid, $company_sid);

                // Fetch Incident Assigned Manager
                $incident_assigned_managers = $this->incident_reporting_model->fetch_incident_assigned_managers($incident_sid, $company_sid);

                $employees_name = array_column($incident_assigned_managers, 'employee_name');

                array_multisort($employees_name, SORT_ASC, $incident_assigned_managers);

                //Get All Assigned Manager For Getting All Incident Related Email
                $incident_related_managers = $incident_assigned_managers;

                // Replace Current Manager with Incident Reporter
                if (!empty($incident_assigned_managers)) {
                    foreach ($incident_assigned_managers as $key => $incident_manager) {
                        if ($incident_manager['employee_id'] == $employee_sid) {
                            $incident_assigned_managers[$key]['employee_id'] = $incident_reporter_sid;
                        }

                        $assign_managers[$key] = $incident_manager['employee_id'];
                    }
                }

                // Fetch Incident Manager
                $incident_managers = $this->incident_reporting_model->fetch_incident_managers($incident_type_id, $company_sid);

                //Remove Incident Managers Those Are not Assigned This Reported Incident
                if (!empty($incident_managers)) {
                    foreach ($incident_managers as $key => $incident_manager) {
                        if (in_array($incident_manager['employee_id'], $assign_managers)) {
                            unset($incident_managers[$key]);
                        }
                    }
                }

                // Fetch Incident Question Answer
                $questions = $this->incident_reporting_model->view_single_assign($incident_sid);

                // Fetch Incident Witnesses
                $witnesses = $this->incident_reporting_model->get_incident_related_witnesses($incident_sid);

                // Fetch Youtube/Vemio Videos Only
                $videos = $this->incident_reporting_model->get_incident_related_videos($incident_sid, $report_type);

                $count_incident_managers = count($incident_assigned_managers);

                foreach ($witnesses as $key => $witness) {
                    $employee_as_witness_id = 0;
                    if ($witness['witness_type'] == 'employee') {
                        $employee_as_witness_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness['witness_email']);
                    }

                    if (!in_array($employee_as_witness_id, $assign_managers)) {
                        $incident_assigned_managers[$count_incident_managers + $key]['employee_id'] = $witness['sid'] . '_wid';
                    }
                }

                // Fetch All System Emails
                foreach ($incident_related_managers as $email_key => $incident_related_manager) {
                    $manager_sid = $incident_related_manager['employee_id'];
                    $manager_data = db_get_employee_profile($manager_sid);
                    $manager_name = $manager_data[0]['first_name'] . ' ' . $manager_data[0]['last_name'];

                    $incident_manual_emails = $this->incident_reporting_model->get_manual_emails($manager_sid, $incident_sid, $employee_name);

                    $incident_emails = array();
                    foreach ($incident_assigned_managers as $key => $incident_manager) {
                        $new_emails = '';
                        $user_one_data = db_get_employee_profile($manager_sid);
                        $user_one_name = $user_one_data[0]['first_name'] . ' ' . $user_one_data[0]['last_name'];
                        $user_two_name = '';

                        $receiver_id   = $incident_manager['employee_id'];

                        if (str_replace('_wid', '', $receiver_id) != $receiver_id) {
                            $witness_id = str_replace('_wid', '', $receiver_id);
                            $witness_info = $this->incident_reporting_model->get_witness_info_by_id($witness_id, $incident_sid);
                            $user_two_name = $witness_info['witness_name'];

                            if ($witness_info['witness_type'] == 'employee') {
                                $witness_id =  $this->incident_reporting_model->fetch_company_employee_id($company_sid, $witness_info['witness_email']);
                                $new_emails = $this->incident_reporting_model->get_incident_related_emails($witness_id, $manager_sid, $incident_sid);
                            } else {
                                $new_emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $manager_sid, $incident_sid);
                            }
                        } else if (str_replace('_inc_rep', '', $receiver_id) != $receiver_id) {

                            $inc_reporter_id = str_replace('_inc_rep', '', $receiver_id);
                            $new_emails = $this->incident_reporting_model->get_incident_related_emails($inc_reporter_id, $manager_sid, $incident_sid);

                            $user_two_data = db_get_employee_profile($inc_reporter_id);
                            $user_two_name = $user_two_data[0]['first_name'] . ' ' . $user_two_data[0]['last_name'];
                        } else {
                            $new_emails = $this->incident_reporting_model->get_incident_related_emails($receiver_id, $manager_sid, $incident_sid);

                            $user_two_data = db_get_employee_profile($receiver_id);
                            $user_two_name = $user_two_data[0]['first_name'] . ' ' . $user_two_data[0]['last_name'];
                        }



                        if (!empty($new_emails)) {
                            $incident_emails[$key]['emails']        = $new_emails;
                            $incident_emails[$key]['section_name']  = $user_one_name . ' & ' . $user_two_name;
                        }
                    }

                    if (!empty($incident_emails) || !empty($incident_manual_emails)) {
                        $incident_all_emails[$email_key]['manager_sid'] = $manager_sid;
                        $incident_all_emails[$email_key]['manager_name'] = $manager_name;
                        $incident_all_emails[$email_key]['incident_emails'] = $incident_emails;
                        $incident_all_emails[$email_key]['incident_manual_emails'] = $incident_manual_emails;
                    }
                }

                // Fetch Incident Notes
                $comments                   = $this->incident_reporting_model->get_incident_related_comments($incident_sid);

                $data['comments']               = $comments;
                $data['questions']              = $questions;
                $data['witnesses']              = $witnesses;
                $data['videos']                 = $videos;
                $data['incident_all_emails']    = $incident_all_emails;
            }

            if ($action == 1) {
                $data['action'] = 'print';
                $data['action_date'] = 'Printed Date';
                $data['action_by'] = "Printed By";
                $data['report_type'] = $report_type;
            } else if ($action == 2) {
                $data['action'] = 'download';
                $data['action_date'] = 'Downloaded Date';
                $data['action_by'] = "Downloaded By";
                $data['report_type'] = $report_type;
            }

            $this->load->view('manage_employer/incident_reporting/print_and_download_incident', $data);
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function download_incident_document($document_path)
    {

        $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
        $temp_file_path = $temp_path . $document_path;

        if (file_exists($temp_file_path)) {
            unlink($temp_file_path);
        }

        $this->load->library('aws_lib');
        $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document_path, $temp_file_path);

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
    }

    public function download_incident_media($company_sid, $media_path)
    {

        $incident_directory = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploaded_videos' . DIRECTORY_SEPARATOR . 'incident_videos';
        $media_full_path = $incident_directory . '/' . $company_sid . '/' . $media_path;

        if (file_exists($media_full_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($media_full_path));
            $handle = fopen($media_full_path, 'rb');
            $buffer = '';

            while (!feof($handle)) {
                $buffer = fread($handle, 4096);
                echo $buffer;
                ob_flush();
                flush();
            }

            fclose($handle);
        }
    }

    public function download_incident_all_documents_and_videos($download, $report_type, $incident_sid)
    {
        $companyName = $this->session->userdata('logged_in')['company_detail']['CompanyName'];
        $basePath = ROOTPATH . 'assets/temp_files/' . strtolower(preg_replace('/\s+/', '_', $companyName)) . '/' . $incident_sid . '/';

        if ($download == 'document' || $download == 'both') {
            $documents = $this->incident_reporting_model->get_incident_report_docs_to_download($incident_sid, $report_type);

            if (!empty($documents)) {

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                foreach ($documents as $key => $document) {
                    @file_put_contents($basePath . $document['file_name'], @file_get_contents(AWS_S3_BUCKET_URL . $document['file_code']));
                }
            }
        }


        if ($download == 'media' || $download == 'both') {
            $videos = $this->incident_reporting_model->get_all_related_videos($incident_sid, $report_type);

            if (!empty($videos)) {

                if (!is_dir($basePath)) {
                    mkdir($basePath, 0777, true);
                }

                foreach ($videos as $key => $video) {
                    if ($video['video_type'] == 'upload_audio' || $video['video_type'] == 'upload_video') {
                        $video_url = $video['video_url'];
                        $file_name = explode("/", $video_url);
                        $media_name = $file_name[1];

                        @copy(
                            ROOTPATH . 'assets/uploaded_videos/incident_videos/' . $video_url,
                            $basePath . $media_name
                        );
                    }
                }
            }
        }


        $incident_detail = $this->incident_reporting_model->get_incident_detail($incident_sid);
        $zip_name = '';

        if (!empty($incident_detail)) {
            $incident_type          = $incident_detail[0]['report_type'];
            $incident_reporter_sid  = $incident_detail[0]['employer_sid'];

            if ($incident_type != 'anonymous') {
                $incident_reporter_info = db_get_employee_profile($incident_reporter_sid);
                $incident_reporter_name = $incident_reporter_info[0]['first_name'] . '_' . $incident_reporter_info[0]['last_name'];
                $zip_name = $incident_reporter_name . '_documents_incident' . '.zip';
            } else {
                $zip_name = 'anonymous_documents_incident.zip';
            }
        }

        $fileName = ROOTPATH . 'assets/temp_files' . '/' . $zip_name;

        ini_set('memory_limit', '-1');
        $this->load->library('zip');
        $this->zip->read_dir(rtrim($basePath, '/'), false);
        $this->zip->archive($basePath);
        deleteFolderWithFiles(ROOTPATH . 'assets/temp_files/' . strtolower(preg_replace('/\s+/', '_', $companyName)));
        $this->zip->download($zip_name);
    }

    public function save_incident_pdf()
    {
        $companyName = $this->session->userdata('logged_in')['company_detail']['CompanyName'];
        $form_post = $this->input->post();
        $dase_64 = $form_post['incident_base64'];
        $incident_sid = $form_post['incident_sid'];

        $basePath = ROOTPATH . 'assets/temp_files/' . strtolower(preg_replace('/\s+/', '_', $companyName)) . '/' . $incident_sid . '/';

        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }

        $handler = fopen($basePath . 'reported_incident.pdf', 'w');
        fwrite($handler, base64_decode(str_replace('data:application/pdf;base64,', '', $dase_64)));
        fclose($handler);
    }

    public function validate_vimeo()
    {
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

    public function vimeo_get_id($str)
    {
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

    function watch_video($video_sid, $user_type = null, $incident_sid = null)
    {
        if ($video_sid != NULL) {
            if ($this->session->userdata('logged_in')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;

                $company_sid = $data['session']['company_detail']['sid'];
                $employer_sid = $data['session']['employer_detail']['sid'];
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $employer_sid;

                $data['title'] = 'Incident Reporting System - Watch Video';
                $data['employee'] = $data['session']['employer_detail'];

                $back_url = '';
                if ($user_type == 1) {
                    $back_url = base_url('incident_reporting_system/view_single_assign') . '/' . $incident_sid;
                } else if ($user_type == 2) {
                    $back_url = base_url('incident_reporting_system/view_incident') . '/' . $incident_sid;
                }
                $data['back_url'] = $back_url;

                $incident_video = $this->incident_reporting_model->get_single_incident_video($video_sid);
                $data['incident_video'] = $incident_video;

                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/incident_reporting/watch_incident_video');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', '<strong>Warning:</strong> Please Login to your account to continue!');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Video not found!');
            if ($user_type == 1) {
                redirect(base_url('incident_reporting_system/view_single_assign') . '/' . $incident_sid, 'refresh');
            } else if ($user_type == 2) {
                redirect(base_url('incident_reporting_system/view_incident') . '/' . $incident_sid, 'refresh');
            }
        }
    }

    function handler()
    {
        $formpost = $this->input->post(NULL, TRUE);
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid Request.';
        switch ($formpost['action']) {

            case 'archive_document':
                $this->incident_reporting_model->moveDocumentToArchive($formpost['documentSid']);
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Document moved to archive.';
                break;

            case 'active_document':
                $this->incident_reporting_model->moveDocumentToActive($formpost['documentSid']);
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Document moved to active.';
                break;

            case 'archive_video':
                $this->incident_reporting_model->moveVideoToArchive($formpost['videoSid']);
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Video moved to archive.';
                break;

            case 'active_video':
                $this->incident_reporting_model->moveVideoToActive($formpost['videoSid']);
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Video moved to active.';
                break;
        }

        //
        header("Content-Type: application/json");
        echo json_encode($resp);
        exit(0);
    }

    function update_incident_document()
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $employee_sid       = $session["employer_detail"]["sid"];


            $user_type        = $_POST['user_type'];
            $update_type        = $_POST['update_type'];
            $document_title     = $_POST['document_title'];
            $document_sid       = $_POST['document_sid'];
            $incident_sid       = $_POST['incident_sid'];
            $company_sid        = $_POST['company_sid'];
            $file_name          = $_POST['file_name'];
            $file_extension     = $_POST['file_ext'];

            $data_to_update = array();

            if ($update_type == 'title' || $update_type == 'both') {
                $data_to_update['document_title']    = $document_title;
            }

            if ($update_type == 'document' || $update_type == 'both') {
                if (!empty($_FILES) && isset($_FILES['docs']) && $_FILES['docs']['size'] > 0) {

                    $update_incident_doc = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);

                    if (!empty($update_incident_doc) && $update_incident_doc != 'error') {
                        $data_to_update['file_name']        = $file_name;
                        $data_to_update['type']             = $file_extension;
                        $data_to_update['file_code']        = $update_incident_doc;
                        $data_to_update['uploaded_date']    = date('Y-m-d H:i:s');
                        $data_to_update['employer_id']      = $employee_sid;
                    }
                }
            }

            $this->incident_reporting_model->update_incident_document($document_sid, $incident_sid, $data_to_update);

            if ($user_type == 'employee') {
                $incident_document = $this->incident_reporting_model->get_single_incident_document($document_sid);
                $rep_document_title    = $incident_document['document_title'];
                $rep_document_type   = $incident_document['type'];
                $rep_document_url      = $incident_document['file_code'];

                $return_data                        = array();
                $return_data['document_sid']        = $document_sid;
                $return_data['document_title']      = $rep_document_title;
                $return_data['document_url']        = AWS_S3_BUCKET_URL . $rep_document_url;
                $return_data['document_type']       = strtoupper($rep_document_type);
                $return_data['document_extension']  = $rep_document_type;

                echo json_encode($return_data);
            } else {
                echo 'success';
            }
        } else {
            echo 'error';
        }
    }

    function update_incident_video()
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $employee_sid       = $session["employer_detail"]["sid"];

            $update_type    = $_POST['update_type'];
            $video_sid      = $_POST['video_sid'];
            $video_title    = $_POST['update_title'];
            $incident_sid   = $_POST['incident_sid'];
            $company_sid    = $_POST['company_sid'];
            $video_source   = $_POST['file_type'];
            $user_type      = $_POST['user_type'];

            $video_id = '';
            $video_to_update = array();

            if ($update_type == 'title' || $update_type == 'both') {
                $video_to_update['video_title'] = $video_title;
            }

            if ($update_type == 'video' || $update_type == 'both') {
                if (!empty($_FILES) && isset($_FILES['video']) && $_FILES['video']['size'] > 0 && $video_source == 'upload_video') {
                    $random = generateRandomString(5);
                    $target_file_name = basename($_FILES["video"]["name"]);
                    $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/incident_videos/";
                    $target_file = $target_dir . $file_name;
                    $basePath = $target_dir . $company_sid;

                    if (!is_dir($basePath)) {
                        mkdir($basePath, 0777, true);
                    }

                    move_uploaded_file($_FILES["video"]["tmp_name"], $target_file);

                    $video_id = $file_name;
                } else if (!empty($_FILES) && isset($_FILES['audio']) && $_FILES['audio']['size'] > 0 && $video_source == 'upload_audio') {
                    $random = generateRandomString(5);
                    $target_file_name = basename($_FILES["audio"]["name"]);
                    $file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/incident_videos/";
                    $target_file = $target_dir . $file_name;
                    $basePath = $target_dir . $company_sid;

                    if (!is_dir($basePath)) {
                        mkdir($basePath, 0777, true);
                    }

                    move_uploaded_file($_FILES["audio"]["tmp_name"], $target_file);

                    $video_id = $file_name;
                } else {
                    if ($video_source == 'youtube') {
                        $youtube_video_link = $_POST['youtube_video_link'];
                        $url_prams = array();
                        parse_str(parse_url($youtube_video_link, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        } else {
                            $video_id = '';
                        }
                    } else {
                        $vimeo_video_link = $_POST['vimeo_video_link'];
                        $video_id = $this->vimeo_get_id($vimeo_video_link);
                    }
                }

                $video_to_update['video_type']      = $video_source;
                $video_to_update['video_url']       = $video_id;
                $data_to_update['uploaded_date']    = date('Y-m-d H:i:s');
                $data_to_update['uploaded_by']      = $employee_sid;
            }

            $prevous_video_info = $this->incident_reporting_model->get_single_incident_video($video_sid);
            $prevous_video_source = $prevous_video_info['video_type'];

            if ($prevous_video_source == 'upload_video' || $prevous_video_source == 'upload_audio') {
                $prevous_media_url = $prevous_video_info['video_url'];
                $is_attach = $this->incident_reporting_model->is_it_email_attachment($prevous_media_url);

                if ($is_attach == 0) {
                    $remove_media = 'assets/uploaded_videos/incident_videos/' . $prevous_media_url;
                    unlink($remove_media);
                }
            }

            $this->incident_reporting_model->update_incident_video($video_sid, $incident_sid, $video_to_update);

            if ($user_type == 'employee') {
                $incident_video = $this->incident_reporting_model->get_single_incident_video($video_sid);
                $rep_video_title    = $incident_video['video_title'];
                $rep_video_source   = $incident_video['video_type'];
                $rep_video_url      = $incident_video['video_url'];

                $return_data                    = array();
                $return_data['incident_sid']    = $incident_sid;
                $return_data['video_sid']       = $video_sid;
                $return_data['video_title']     = $rep_video_title;
                $return_data['video_source']    = $rep_video_source;

                if ($video_source == 'upload_video') {
                    $video_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $rep_video_url;
                    $return_data['video_url'] = $video_url;
                } else if ($video_source == 'upload_audio') {
                    $audio_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $rep_video_url;
                    $return_data['video_url'] = $audio_url;
                } else {
                    $return_data['video_url'] = $rep_video_url;
                }

                echo json_encode($return_data);
            } else {
                echo 'success';
            }
        } else {
            echo 'error';
        }
    }

    function print_image($sid)
    {

        $image = $this->incident_reporting_model->get_incident_image($sid);
        $document_file = AWS_S3_BUCKET_URL . $image;
        $data['original_document_description'] = '<img src="' . $document_file . '" style="display: block; max-width: 100%; height: auto;" />';

        $data['print'] = 'original';
        $data['download'] = 'print';
        $data['file_name'] = NULL;
        $this->load->view('hr_documents_management/print_generated_document', $data);
    }

    function download_media_file($incident_sid)
    {
        if ($this->session->userdata('logged_in')) {

            $video = $this->incident_reporting_model->get_incident_related_video($incident_sid);

            if ($video['video_type'] == 'upload_audio' || $video['video_type'] == 'upload_video') {
                $video_url = $video['video_url'];
                $file_name = explode("/", $video_url);
                $media_name = $file_name[1];

                $fileName = ROOTPATH . 'assets/uploaded_videos/incident_videos/57/' . $media_name;

                if (file_exists($fileName)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $media_name . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($fileName));
                    $handle = fopen($fileName, 'rb');
                    $buffer = '';

                    while (!feof($handle)) {
                        $buffer = fread($handle, 4096);
                        echo $buffer;
                        ob_flush();
                        flush();
                    }

                    fclose($handle);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function update_email_read_flag()
    {
        $email_sid              = $_POST['email_sid'];

        $data_to_update             = array();
        $data_to_update['is_read']  = 1;
        $this->incident_reporting_model->update_email_is_read_flag($email_sid, $data_to_update);



        if (isset($_POST['receiver_sid'])) {
            $receiver_sid           = $_POST['receiver_sid'];
            $sender_info            = $this->incident_reporting_model->get_email_sender_info($email_sid);

            $incident_sid           = $sender_info['incident_reporting_id'];
            $sender_sid             = $sender_info['sender_sid'] == 0 ? $sender_info['manual_email'] : $sender_info['sender_sid'];

            $log_in_user_status     = is_manager_have_new_email($receiver_sid, $incident_sid);
            $status_one = 0;
            if ($log_in_user_status > 0) {
                $status_one = 1;
            }

            $current_user_status    = is_user_have_unread_message($receiver_sid, $sender_sid, $incident_sid);
            $status_two = 0;
            if ($current_user_status > 0) {
                $status_two = 1;
            }

            if (filter_var($sender_sid, FILTER_VALIDATE_EMAIL)) {
                $split_email = explode('@', $sender_sid);
                $sender_sid = $split_email[0];
            }

            $return_data                = array();
            $return_data['status_one']  = $status_one;
            $return_data['status_two']  = $status_two;
            $return_data['sender_sid']  = $sender_sid;

            echo json_encode($return_data);
        } else {
            echo 'success';
        }
    }
}
