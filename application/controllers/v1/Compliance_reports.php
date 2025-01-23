<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Indeed controller to handle all new
 * events
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 */
class Compliance_reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('v1/compliance_report_model');
    }

    /**
     * Main Add Compliance Report
     *
     * @param string $filingType
     * @param int $typeId
     * @return void
     */
    public function index(string $file, int $id)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['employee'] = $data['session']['employer_detail'];
            $incident_details = $this->compliance_report_model->fetch_reports_user_guide($id);

            $report_type = 'anonymous';

            if ($file == 'c') {
                $report_type = 'confidential';
            }

            $data['title'] = 'Reporting ' . $incident_details[0]['incident_name'];
            $data['id'] = $id;
            $data['report_type'] = $report_type;
            $questions = $this->compliance_report_model->fetch_all_question($id);
            
            $e_signature_data = get_e_signature($company_sid, $employer_sid, 'employee');
            $data['e_signature_data'] = $e_signature_data;
            $load_view = check_blue_panel_status(false, 'self');
            //
            $data['load_view'] = 1;
            $data['questions'] = $questions;
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['employees'] = $this->compliance_report_model->getAllCompanyEmployeesForComplianceSafety($company_sid);
            //
            $data['employees_new'] = $this->compliance_report_model->get_all_employees($company_sid);

            $this->form_validation->set_rules('submit', 'Form Submit', 'trim');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('compliance_report/add_compliance_report');
                $this->load->view('main/footer');
            } else {
                if (isset($_POST) && isset($_POST['submit'])) {

                    $update_id = $_POST['inc-id'];
                    $review_manager = $_POST['review_manager'];
                    $complianceSafetyTitle = $_POST['compliance_safety_title'];
                    //
                    unset($_POST['submit']);
                    unset($_POST['inc-id']);
                    unset($_POST['review_manager']);
                    unset($_POST['compliance_safety_title']);
                    //
                    $reply_url = '';
                    $incidentId = 0;
                    //
                    //
                    if ($update_id != 0) {
                        $update['report_type'] = $report_type;
                        $update['incident_name'] = $incident_details[0]['incident_name'];
                        $this->compliance_report_model->update_incident_report($update_id, $update);
                        $incident = $update_id;
                        $video_to_update = array();
                        $video_to_update['is_incident_reported'] = 1;
                        $this->compliance_report_model->update_incident_related_video($update_id, $video_to_update);
                        //
                        $incidentId = $update_id;
                    } else {
                        //
                        $insert = array();
                        $insert['company_sid'] = $company_sid;
                        $insert['employer_sid'] = $employer_sid;
                        $insert['current_date'] = date('Y-m-d H:i:s');
                        $insert['incident_type_id'] = $id;
                        $insert['report_type'] = $report_type;
                        $insert['incident_name'] = $incident_details[0]['incident_name'];
                        $insert['on_behalf_employee_sid'] =  $employer_sid;
                        $insert['compliance_safety_title'] =  $complianceSafetyTitle;
                        //
                        $incident = $this->compliance_report_model->insert_incident_reporting($insert);
                        //
                        $incidentId = $incident;
                    }

                    //
                    unset($_POST['witnesses']);
                    unset($_POST['video_source']);
                    unset($_POST['video_title']);
                    unset($_POST['document_title']);
                    unset($_POST['video_id']);
                    //
                    $insert = array();

                    foreach ($_POST as $key => $val) {
                        $exp = explode('_', $key);
                        // echo '<pre>'; print_r($exp); exit;
                        if (sizeof($exp) > 1 && !empty($val)) {
                            $insert['question'] = $this->compliance_report_model->get_specific_question($exp[1]);

                            if ($exp[0] == 'multi-list') {
                                $val = serialize($val);
                            }

                            $insert['answer'] = strip_tags($val);
                            $insert['incident_reporting_id'] = $incidentId;
                            $this->compliance_report_model->insert_inc_que_ans($insert);
                        } elseif (sizeof($exp) == 1 && !empty($val) && $exp[0] == 'signature') {
                            $insert['question'] = $exp[0];
                            $insert['answer'] = strip_tags($val);
                            $insert['incident_reporting_id'] = $incidentId;
                            $this->compliance_report_model->insert_inc_que_ans($insert);
                        }
                    }

                    $replacement_array = array();
                    $replacement_array['company_name'] = ucwords($data['session']['company_detail']['CompanyName']);
                    $replacement_array['company-name'] = ucwords($data['session']['company_detail']['CompanyName']);

                    foreach ($review_manager as $manager) {
                        $assigned_emp = array(
                            'company_sid' => $company_sid,
                            'employer_sid' => $manager,
                            'assigned_date' => date('Y-m-d H:i:s'),
                            'incident_sid' => $incidentId
                        );

                        $this->compliance_report_model->assign_incident_to_emp($assigned_emp);     //Add the employees who are gonna receive this incident from employee reporting
                        $emp = $this->compliance_report_model->fetch_employee_name_by_sid($manager);

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

    public function validate_vimeo_video()
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

    public function add_compliance_video()
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
                $incident_sid = $this->compliance_report_model->insert_incident_reporting($insert);
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

            $insert_video_sid = $this->compliance_report_model->insert_incident_video_reccord($video_to_insert);

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

    function update_compliance_video()
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

            $prevous_video_info = $this->compliance_report_model->get_single_incident_video($video_sid);
            $prevous_video_source = $prevous_video_info['video_type'];

            if ($prevous_video_source == 'upload_video' || $prevous_video_source == 'upload_audio') {
                $prevous_media_url = $prevous_video_info['video_url'];
                $is_attach = $this->compliance_report_model->is_it_email_attachment($prevous_media_url);

                if ($is_attach == 0) {
                    $remove_media = 'assets/uploaded_videos/incident_videos/' . $prevous_media_url;
                    unlink($remove_media);
                }
            }

            $this->compliance_report_model->update_incident_video($video_sid, $incident_sid, $video_to_update);

            if ($user_type == 'employee') {
                $incident_video = $this->compliance_report_model->get_single_incident_video($video_sid);
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

    public function add_compliance_document()
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
                $incident_sid = $this->compliance_report_model->insert_incident_reporting($insert);
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
                    $insert_document_sid = $this->compliance_report_model->insert_incident_docs($document_to_insert);

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

    function update_compliance_document()
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

            $this->compliance_report_model->update_incident_document($document_sid, $incident_sid, $data_to_update);

            if ($user_type == 'employee') {
                $incident_document = $this->compliance_report_model->get_single_incident_document($document_sid);
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
                $incident = $this->compliance_report_model->insert_incident_reporting($insert);
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
            $this->compliance_report_model->insert_incident_docs($docs);
            echo $incident;
        } else {
            echo 'error';
        }
    }


    
}
