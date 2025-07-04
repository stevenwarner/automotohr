<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Video_interview_system extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->model('video_interview_system_model');
        $this->load->library("pagination");
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $data['title'] = "Video Interview System";

            /** pagination * */
            $records_per_page = 20; //PAGINATION_RECORDS_PER_PAGE;
            $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $records_per_page;
            }

            $baseUrl = base_url('video_interview_system');
            $uri_segment = 2;
            $config = array();
            $config["base_url"] = $baseUrl;
            $config["total_rows"] = $this->video_interview_system_model->get_video_questionnaires_count($company_sid);
            $config["per_page"] = $records_per_page;
            $config["uri_segment"] = $uri_segment;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = ceil($choice);
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '&laquo; First';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = 'Last &raquo;';
            $config['last_tag_open'] = '<li class="next page">';
            $config['last_tag_close'] = '</li>';
            $config['next_link'] = /* '<i class="fa fa-angle-right"></i>'; */
                '>';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = /* '<i class="fa fa-angle-left"></i>'; */
                '<';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);
            $data["links"] = $this->pagination->create_links();
            /** pagination end * */
            $data["video_questions"] = $this->video_interview_system_model->get_video_questionnaires($company_sid, NULL, $records_per_page, $my_offset);

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/video_interview_system/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function response($question_sid, $applicant_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $data['title'] = "Video Interview Questions - Applicant Response";

            if (empty($question_sid)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Question Not Found.');
                redirect('video_interview_system');
            } else {
                $result = $this->video_interview_system_model->check_valid_question($question_sid, $company_sid);

                if ($result == false) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Question Not Found.');
                    redirect('video_interview_system');
                }
            }

            if (empty($applicant_sid)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found.');
                redirect('application_tracking_system/active/all/all/all/all');
            } else {
                $result = $this->video_interview_system_model->check_valid_applicant($applicant_sid, $company_sid);

                if ($result == false) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found.');
                    redirect('application_tracking_system/active/all/all/all/all');
                }
            }

            $data['video_question'] = $this->video_interview_system_model->get_questionnaire_response($question_sid, $applicant_sid, $company_sid);

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/video_interview_system/response');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    /*public function responses($applicant_sid, $job_list_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');
            $company_sid = $data["session"]["company_detail"]["sid"];

            $ats_params = $this->session->userdata('ats_params');
            $data = applicant_right_nav($applicant_sid, $job_list_sid, $ats_params);

            $data['title'] = "Video Interview System - Applicant Responses";
            $data['applicant_sid'] = $applicant_sid;
            $data['job_list_sid'] = $job_list_sid;
            $data['questions'] = $this->video_interview_system_model->get_answered_video_questionnaires($applicant_sid, $company_sid);
            $data['employer'] = $this->video_interview_system_model->get_applicants_details($applicant_sid);
            $data['questions_sent'] = $this->video_interview_system_model->check_sent_video_questionnaires($applicant_sid, $company_sid);
            $data['questions_answered'] = $this->video_interview_system_model->check_answered_video_questionnaires($applicant_sid, $company_sid);

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/video_interview_system/responses');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }*/

    public function question_responses($applicant_sid, $job_list_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $ats_params = $this->session->userdata('ats_params');
            $data = applicant_right_nav($applicant_sid, $job_list_sid, $ats_params);
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $company_timezone = $data['session']['portal_detail']['company_timezone'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $data['title'] = 'Video Interview Questions';
            $data['subtitle'] = 'View Responses By Candidate';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['company_name'] = $company_name;
            $data['company_timezone'] = $company_timezone;
            $data['applicant_jobs'] = $this->application_tracking_system_model->get_single_applicant_all_jobs($applicant_sid, $company_sid);
            $data['have_status'] = $this->application_tracking_system_model->have_status_records($company_sid);
            $data['questions_sent'] = $this->application_tracking_system_model->check_sent_video_questionnaires($applicant_sid, $company_sid);
            $data['questions_answered'] = $this->application_tracking_system_model->check_answered_video_questionnaires($applicant_sid, $company_sid);
            $data['applicant_sid'] = $applicant_sid;
            $data['job_list_sid'] = $job_list_sid;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $sent_questions = $this->video_interview_system_model->get_applicant_questions($company_sid, $applicant_sid);
                $distinct_questions = $this->video_interview_system_model->get_distinct_questions($company_sid, $applicant_sid);
                //                echo $this->db->last_query().'<br>';


                foreach ($distinct_questions as $d_key => $question) {
                    $sent_q = array();

                    foreach ($sent_questions as $sent_question) {
                        if ($question['sid'] == $sent_question['question_sid']) {
                            $sent_q[] = $sent_question;
                        }
                    }

                    $distinct_questions[$d_key]['sent'] = $sent_q;
                }
                //echo '<pre>'; print_r($distinct_questions); echo '</pre>';
                $data['questions'] = $distinct_questions;
                $app_vq_rating = $this->video_interview_system_model->get_applicant_video_questionnaire_rating($company_sid, $employer_sid, $applicant_sid);
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($applicant_sid, 'applicant');
                $data['app_vq_rating'] = $app_vq_rating;
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/video_interview_system/question_responses');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'save_comment_against_sent_question':
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $applicant_sid = $this->input->post('applicant_sid');
                        $question_sent_sid = $this->input->post('question_sent_sid');
                        $comment = $this->input->post('comment');
                        $job_list_sid = $this->input->post('job_list_sid');

                        $this->video_interview_system_model->save_comment_or_rating($company_sid, $employer_sid, $applicant_sid, $question_sent_sid, $comment, 0, 'comment');

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Comment saved!');
                        redirect('video_interview_system/question_responses/' . $applicant_sid . '/' . $job_list_sid, 'refresh');
                        break;
                    case 'save_applicant_video_question_rating':
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $applicant_sid = $this->input->post('applicant_sid');
                        $rating = $this->input->post('rating');
                        $job_list_sid = $this->input->post('job_list_sid');

                        $this->video_interview_system_model->save_comment_or_rating($company_sid, $employer_sid, $applicant_sid, 0, '', $rating, 'rating');

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Rating saved!');
                        redirect('video_interview_system/question_responses/' . $applicant_sid . '/' . $job_list_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    /*public function send($applicant_sid = null, $job_list_sid = NULL, $template_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $company_name = $data["session"]["company_detail"]["CompanyName"];

            if (empty($applicant_sid)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found.');
                redirect('application_tracking_system/active/all/all/all/all');
            } else {
                $data['employer'] = $this->video_interview_system_model->get_applicants_details($applicant_sid);
                $result = $this->video_interview_system_model->check_valid_applicant($applicant_sid, $company_sid);

                if ($result == false) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found.');
                    redirect('application_tracking_system/active/all/all/all/all');
                }
            }

            $data['title'] = "Send Video Interview Questions";
            $data['applicant_sid'] = $applicant_sid;
            $data['job_list_sid'] = $job_list_sid;
            $data['template_sid'] = $template_sid;

            if ($template_sid != NULL) {
                $data['video_questions'] = $this->video_interview_system_model->get_template_questions($company_sid, $template_sid);
            } else {
                $data["video_questions"] = $this->video_interview_system_model->get_video_questionnaires($company_sid);
            }
            $data["sent_questions"] = $this->video_interview_system_model->get_sent_video_questionnaires($applicant_sid, $company_sid);

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules('questions', 'Questions', 'required|xss_clean|trim');
            $formpost = $this->input->post(NULL, TRUE);

            if ($this->form_validation->run() == FALSE && !isset($formpost['send_question_submit'])) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/video_interview_system/send');
                $this->load->view('main/footer');
            } else {
                if (isset($formpost['questions'])) {
                    foreach ($formpost['questions'] as $question_sid) {
                        if ($question_sid != 'all') {
                            $data['questions'][] = $this->video_interview_system_model->get_video_questionnaire($question_sid, $company_sid);
                        }
                    }
                }

                if (isset($_POST['order']) && !empty($_POST['order'])) {
                    unset($_POST['order']);
                    unset($_POST['send_question_submit']);
                    $question_sids = array();
                    $question_orders = array();

                    foreach ($_POST as $key => $value) {
                        if ($value == '-0') {
                            $value = 0;
                        }

                        $temp = explode('_', $key);
                        if ($temp[1] == 'sid') {
                            $question_sids[] = $value;
                        } else {
                            $question_orders[] = $value;
                        }
                    }

                    $v_key = $this->video_interview_system_model->get_applicant_verification_key($applicant_sid, $company_sid);
                    if (empty($v_key)) {
                        $v_key = 'app' . generateRandomString(24);
                    }
                    $send = 0;
                    $i = 0;

                    foreach ($question_sids as $question_sid) {
                        $insert_array = array();
                        $insert_array['company_sid'] = $company_sid;
                        $insert_array['employer_sid'] = $employer_sid;
                        $insert_array['applicant_sid'] = $applicant_sid;
                        $insert_array['question_sid'] = $question_sid;
                        $insert_array['question_order'] = $question_orders[$i];
                        $insert_array['question_type'] = $this->video_interview_system_model->get_question_type($question_sid);
                        $insert_array['status'] = 'unanswered';
                        $insert_array['verification_key'] = $v_key;
                        $insert_array['sent_date'] = date('Y-m-d H:i:s');

                        $this->video_interview_system_model->send_video_questionnaire($insert_array);
                        $send = 1;
                        $i++;
                    }

                    if ($send == 1) {
                        $applicant_data = $this->video_interview_system_model->get_applicant_data($applicant_sid);

                        $replacement_array = array();
                        $replacement_array['company_name'] = ucwords($company_name);
                        $replacement_array['applicant_name'] = $applicant_data['first_name'] . ' ' . $applicant_data['last_name'];
                        $replacement_array['url'] = '<a href="' . base_url() . 'video_interview_system/record?key=' . $v_key . '" target="_blank">Interview Questionnaires</a>';
                        $message_hf = message_header_footer_domain($applicant_data['employer_sid'], $company_name);

                        log_and_send_templated_email(VIDEO_INTERVIEW_QUESTIONNAIRES, $applicant_data['email'], $replacement_array, $message_hf);
                    }

                    $this->session->set_flashdata('message', '<b>Success:</b> Question(s) sent to Applicant.');
                    redirect('applicant_profile/' . $applicant_sid, 'refresh');
                } else {
                    $this->load->view('main/header', $data);
                    $this->load->view('manage_employer/video_interview_system/order');
                    $this->load->view('main/footer');
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }*/

    public function send_questions($applicant_sid, $job_list_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $ats_params = $this->session->userdata('ats_params');
            $data = applicant_right_nav($applicant_sid, $job_list_sid, $ats_params);
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $company_name = $data["session"]["company_detail"]["CompanyName"];
            $data['title'] = "Video Interview Questions";
            $data['subtitle'] = "Send Video Interview Questions";
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['company_name'] = $company_name;

            if ($applicant_sid > 0) {
                $data['applicant_jobs'] = $this->application_tracking_system_model->get_single_applicant_all_jobs($applicant_sid, $company_sid);
                $data['have_status'] = $this->application_tracking_system_model->have_status_records($company_sid);
                $data['questions_sent'] = $this->application_tracking_system_model->check_sent_video_questionnaires($applicant_sid, $company_sid);
                $data['questions_answered'] = $this->application_tracking_system_model->check_answered_video_questionnaires($applicant_sid, $company_sid);
                $data['applicant_sid'] = $applicant_sid;
                $data['job_list_sid'] = $job_list_sid;
                $this->form_validation->set_rules('applicant_sid', 'Applicant Sid', 'required|xss_clean|trim');

                if ($this->form_validation->run() == false) {
                    $company_question_templates = $this->video_interview_system_model->get_company_video_questionnaire_templates($company_sid, true);
                    $data['company_question_templates'] = $company_question_templates;
                    $company_default_questions = $this->video_interview_system_model->get_company_video_questions($company_sid, 0);
                    $data['company_default_questions'] = $company_default_questions;
                    $this->load->view('main/header', $data);
                    $this->load->view('manage_employer/video_interview_system/send_questions');
                    $this->load->view('main/footer');
                } else {
                    $questions = $this->input->post('questions');
                    $company_sid = $this->input->post('company_sid');
                    $employer_sid = $this->input->post('employer_sid');
                    $applicant_sid = $this->input->post('applicant_sid');
                    $job_list_sid = $this->input->post('job_list_sid');
                    $notification_type = $this->input->post('notification_type');
                    //Get Verification Key
                    $v_key = $this->video_interview_system_model->get_applicant_verification_key($applicant_sid, $company_sid);

                    if (empty($v_key)) {
                        $v_key = 'app' . generateRandomString(24);
                    }

                    $this->video_interview_system_model->set_sent_configuration_status($v_key, 0);
                    $new_config = array();
                    $new_config['company_sid'] = $company_sid;
                    $new_config['employer_sid'] = $employer_sid;
                    $new_config['applicant_sid'] = $applicant_sid;
                    $new_config['public_key'] = $v_key;
                    $new_config['date_sent'] = date('Y-m-d H:i:s');
                    $new_config['status'] = 1;
                    $new_config['notification_type'] = $notification_type;
                    $this->video_interview_system_model->add_sent_configuration($new_config);
                    $new_send_records_added = false;

                    if (!empty($questions)) {
                        foreach ($questions as $question) {
                            if (isset($question['sid'])) {
                                $this->video_interview_system_model->set_sent_question_status($applicant_sid, $question['sid'], 0);
                                $data_to_insert = array();
                                $data_to_insert['company_sid'] = $company_sid;
                                $data_to_insert['employer_sid'] = $employer_sid;
                                $data_to_insert['applicant_sid'] = $applicant_sid;
                                $data_to_insert['question_sid'] = $question['sid'];
                                $data_to_insert['status'] = 'unanswered';
                                $data_to_insert['verification_key'] = $v_key;
                                $data_to_insert['resent_status'] = $question['is_resent'];
                                $data_to_insert['resent_note'] = $question['resent_note'];
                                $data_to_insert['question_order'] = $question['sort_order'];
                                $data_to_insert['question_type'] = $question['question_type'];
                                $data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $data_to_insert['is_latest'] = 1;
                                $this->video_interview_system_model->send_video_questionnaire($data_to_insert);
                                $new_send_records_added = true;
                            }
                        }
                    }

                    if ($new_send_records_added == true) {
                        $applicant_data = $this->video_interview_system_model->get_applicant_data($applicant_sid);
                        $replacement_array = array();
                        $replacement_array['company_name'] = ucwords($company_name);
                        $replacement_array['applicant_name'] = $applicant_data['first_name'] . ' ' . $applicant_data['last_name'];
                        $replacement_array['url'] = '<a href="' . base_url() . 'video_interview_system/record?key=' . $v_key . '" style="' . VIDEO_INTERVIEW_EMAIL_BTN_STYLE . '" target="_blank">Interview Questionnaires</a>';
                        //                      $replacement_array['url'] = '<a href="' . base_url() . 'video_interview_system/record?key=' . $v_key . '" target="_blank">Interview Questionnaires</a>';
                        $message_hf = message_header_footer_domain($applicant_data['employer_sid'], $company_name);
                        log_and_send_templated_email(VIDEO_INTERVIEW_QUESTIONNAIRES, $applicant_data['email'], $replacement_array, $message_hf);
                    }

                    $this->session->set_flashdata('message', '<b>Success:</b> Question(s) sent to Applicant.');
                    redirect('applicant_profile/' . $applicant_sid . '/' . $job_list_sid, 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found.');
                redirect('application_tracking_system/active/all/all/all/all');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add($template_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            if ($template_sid != NULL) {
                $data['template_sid'] = $template_sid;
                $data['title'] = "Create New Template Question";
            } else {
                $data['title'] = "Create New Video Question";
            }

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules('question_type', 'Question Type', 'required|xss_clean|trim');

            $question_type = $this->input->post('question_type');
            if ($question_type == 'text') {
                $this->form_validation->set_rules('question_text', 'Question Text', 'required|xss_clean|trim');
            } else {
                $this->form_validation->set_rules('video_title', 'Video Title', 'required|xss_clean|trim');
            }

            $video_source = $this->input->post('video_source');
            $video_recorded = $this->input->post('video_recorded');

            if ($video_source == 'youtube') {
                $this->form_validation->set_rules('youtube_video', 'YouTube Video', 'required|trim|xss_clean|callback_validate_youtube');
            } else if ($video_source == 'vimeo') {
                $this->form_validation->set_rules('vimeo_video', 'Vimeo Video', 'required|trim|xss_clean|callback_validate_vimeo');
            } else if ($video_source == 'recorded') {
                if ($video_recorded == 'yes') {
                    $this->form_validation->set_rules('video', 'Recording', 'required|xss_clean|trim');
                }
            }

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/video_interview_system/add');
                $this->load->view('main/footer');
            } else {
                $video_source = $this->input->post('video_source');
                $yoututbe_video_url = $this->input->post('youtube_video');
                $vimeo_video_url = $this->input->post('vimeo_video');

                $insert_array = array();
                $insert_array['company_sid'] = $company_sid;
                $insert_array['employer_sid'] = $employer_sid;
                $insert_array['created_date'] = date('Y-m-d H:i:s');
                $insert_array['status'] = 'active';
                $insert_array['question_type'] = $question_type;

                if ($template_sid != NULL) {
                    $insert_array['template_sid'] = $template_sid;
                } else {
                    $insert_array['template_sid'] = 0;
                }

                $video_name = $this->input->post('video');
                $insert_array['video_title'] = $this->input->post('video_title');

                $video_id = '';
                if ($video_source == 'youtube') {
                    $url_prams = array();
                    parse_str(parse_url($yoututbe_video_url, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_id = $url_prams['v'];
                    }
                } elseif ($video_source == 'vimeo') {
                    //                    $video_id = (int)substr(parse_url($vimeo_video_url, PHP_URL_PATH), 1);
                    $vimeo_url = $this->input->post('vimeo_video');
                    $video_id = $this->get_vimeo_id($vimeo_url);
                }

                if (!empty($video_id)) {
                    $insert_array['video_id'] = $video_id;
                }

                $insert_array['video_source'] = $video_source;

                if (!empty($video_name)) {
                    $filePath = FCPATH . "assets/uploads/";
                    $explode = explode('.', $video_name);
                    $aws = new AwsSdk();
                    $aws->putToBucket($video_name, $filePath . $video_name, CLOUD_VIDEO_LIBRARY);
                    $insert_array['video_name'] = $video_name;
                    $insert_array['video_type'] = $explode[1];
                    unlink('assets/uploads/' . $video_name);
                }

                $insert_array['question_text'] = $this->input->post('question_text');
                $result = $this->video_interview_system_model->add_video_questionnaire($insert_array);

                if ($result === 'exists') {
                    $this->session->set_flashdata('message', '<b>Error:</b> Question already exists.');
                } else {
                    if ($template_sid != NULL) {
                        $this->session->set_flashdata('message', '<b>Success:</b> Template Question added successfully.');
                    } else {
                        $this->session->set_flashdata('message', '<b>Success:</b> Video Interview Question added successfully.');
                    }
                }

                if ($template_sid != NULL) {
                    redirect('video_interview_system/manage_template/' . $template_sid);
                } else {
                    redirect('video_interview_system');
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit($question_sid, $template_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            if ($template_sid != NULL) {
                $data['template_sid'] = $template_sid;
                $data['title'] = "Edit Template Question";

                $data['template'] = $this->video_interview_system_model->get_questionnaire_template($company_sid, $template_sid);
            } else {
                $data['title'] = "Edit Video Question";

                $data['template'] = array();
            }

            if (empty($question_sid)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Video Interview Question not found.');
                redirect('video_interview_system');
            } else {
                $question = $this->video_interview_system_model->get_video_questionnaire($question_sid, $company_sid);

                if (empty($question)) {
                    $this->session->set_flashdata('message', '<b>Error:</b> Video Interview Question not found.');
                    redirect('video_interview_system');
                } else {
                    $data['question'] = $question;
                }
            }

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules('question_type', 'Question Type', 'required|xss_clean|trim');

            $question_type = $this->input->post('question_type');
            if ($question_type == 'text') {
                $this->form_validation->set_rules('question_text', 'Question Text', 'required|xss_clean|trim');
            } else {
                $this->form_validation->set_rules('video_title', 'Video Title', 'required|xss_clean|trim');
            }

            $video_source = $this->input->post('video_source');
            $video_recorded = $this->input->post('video_recorded');

            if ($video_source == 'youtube') {
                $this->form_validation->set_rules('youtube_video', 'YouTube Video', 'required|trim|xss_clean|callback_validate_youtube');
            } else if ($video_source == 'vimeo') {
                $this->form_validation->set_rules('vimeo_video', 'Vimeo Video', 'required|trim|xss_clean|callback_validate_vimeo');
            } else if ($video_source == 'recorded') {
                if ($video_recorded == 'yes') {
                    $this->form_validation->set_rules('video', 'Recording', 'required|xss_clean|trim');
                }
            }

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/video_interview_system/edit');
                $this->load->view('main/footer');
            } else {
                $update_array = array();
                $update_array['modified_date'] = date('Y-m-d H:i:s');
                $update_array['modified_by'] = $employer_sid;
                $update_array['question_type'] = $question_type;
                $update_array['video_title'] = $this->input->post('video_title');
                $update_array['video_source'] = $this->input->post('video_source');
                $video_name = $this->input->post('video');

                $video_id = '';
                if ($update_array['video_source'] == 'youtube') {
                    $url_prams = array();
                    parse_str(parse_url($this->input->post('youtube_video'), PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_id = $url_prams['v'];
                    }
                } elseif ($update_array['video_source'] == 'vimeo') {
                    //                    $video_id = (int)substr(parse_url($this->input->post('vimeo_video'), PHP_URL_PATH), 1);
                    $vimeo_url = $this->input->post('vimeo_video');
                    $video_id = $this->get_vimeo_id($vimeo_url);
                }

                if (!empty($video_id)) {
                    $update_array['video_id'] = $video_id;
                }

                if (!empty($video_name)) {
                    $filePath = FCPATH . "assets/uploads/";
                    $explode = explode('.', $video_name);
                    $aws = new AwsSdk();
                    $aws->putToBucket($video_name, $filePath . $video_name, CLOUD_VIDEO_LIBRARY);
                    $update_array['video_name'] = $video_name;
                    $update_array['video_type'] = $explode[1];
                    unlink('assets/uploads/' . $video_name);
                }
                $update_array['question_text'] = $this->input->post('question_text');
                $this->video_interview_system_model->update_video_questionnaire($update_array, $question_sid);

                if ($template_sid != NULL) {
                    $this->session->set_flashdata('message', '<b>Success:</b> Template Question updated successfully.');
                    redirect('video_interview_system/manage_template/' . $template_sid);
                } else {
                    $this->session->set_flashdata('message', '<b>Success:</b> Video Interview Question updated successfully.');
                    redirect('video_interview_system');
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function how_to()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            $data['title'] = 'Video Interview System - How To';

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/video_interview_system/how_to');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_responder()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            if (array_key_exists('perform_action', $_POST)) {
                $perform_action = $_POST['perform_action'];
                switch ($perform_action) {
                    case 'get_default_question_form':
                        $template_sid = $this->input->post('template_sid');
                        $view_data = array();
                        $return_array = array();
                        $view_data['questions'] = $this->video_interview_system_model->get_default_questions_categorized('active');
                        $view_data['submit_btn_text'] = 'Add Selected Question(s)';
                        if (!empty($template_sid)) {
                            $view_data['template_sid'] = $template_sid;
                        }
                        $return_array['html'] = $this->load->view('manage_employer/video_interview_system/default', $view_data, true);
                        $return_array['title'] = 'Add Question';
                        echo json_encode($return_array);
                        break;
                    case 'delete_question':
                        $question_sid = $this->input->post('question_sid');
                        $result = $this->video_interview_system_model->delete_video_questionnaire($question_sid, $company_sid);
                        if ($result) {
                            echo json_encode('success');
                        } else {
                            echo json_encode('failed');
                        }
                        break;
                    case 'delete_question_template':
                        $template_sid = $this->input->post('template_sid');
                        $result = $this->video_interview_system_model->delete_questionnaire_template($template_sid, $company_sid);
                        if ($result) {
                            echo json_encode('success');
                        } else {
                            echo json_encode('failed');
                        }
                        break;
                    case 'deactivate_question':
                        $question_sid = $this->input->post('question_sid');
                        $update_data = array('status' => 'inactive');
                        $result = $this->video_interview_system_model->change_video_questionnaire_status($question_sid, $company_sid, $update_data);
                        if ($result) {
                            echo json_encode('success');
                        } else {
                            echo json_encode('failed');
                        }
                        break;
                    case 'activate_question':
                        $question_sid = $this->input->post('question_sid');
                        $update_data = array('status' => 'active');
                        $result = $this->video_interview_system_model->change_video_questionnaire_status($question_sid, $company_sid, $update_data);
                        if ($result) {
                            echo json_encode('success');
                        } else {
                            echo json_encode('failed');
                        }
                        break;
                    case 'deactivate_question_template':
                        $template_sid = $this->input->post('template_sid');
                        $update_data = array('status' => 'inactive');
                        $result = $this->video_interview_system_model->change_questionnaire_template_status($template_sid, $company_sid, $update_data);
                        if ($result) {
                            echo json_encode('success');
                        } else {
                            echo json_encode('failed');
                        }
                        break;
                    case 'activate_question_template':
                        $template_sid = $this->input->post('template_sid');
                        $update_data = array('status' => 'active');
                        $result = $this->video_interview_system_model->change_questionnaire_template_status($template_sid, $company_sid, $update_data);
                        if ($result) {
                            echo json_encode('success');
                        } else {
                            echo json_encode('failed');
                        }
                        break;

                    default:
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }


    public function record()
    {
        $data['title'] = STORE_NAME;
        $data['invalid'] = false;
        $data['complete'] = false;
        $verification_key = isset($_GET['key']) ? $_GET['key'] : '';
        $result = $this->video_interview_system_model->validate_record_key($verification_key);

        if (!empty($verification_key) && $result != false) {
            $unanswered_total = $this->video_interview_system_model->get_applicant_questionnaire($verification_key, 'count', 1);
            $data['unanswered_total'] = $unanswered_total;
            $answered_total = $this->video_interview_system_model->get_answered_questions_count($verification_key);
            $data['answered_total'] = $answered_total;
            $company_data = $this->video_interview_system_model->get_company_name($verification_key);
            $data['title'] = $company_data['CompanyName'];
            $company_logo = $company_data['Logo'];

            if ($company_logo != '') {
                $data['logo'] = AWS_S3_BUCKET_URL . $company_logo;
            } else {
                $data['logo'] = AWS_S3_BUCKET_URL . 'default_pic-ySWxT.jpg';
            }

            $questionnaire = $this->video_interview_system_model->get_applicant_questionnaire($verification_key, null, 1);

            if (empty($questionnaire)) {
                $data['complete'] = true;
            } else {
                $data['questionnaire'] = $questionnaire;
                $data['complete'] = false;
            }
        } else {
            $data['invalid'] = true;
        }

        $this->form_validation->set_rules('sent_record_sid', 'sent_record_sid', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('manage_employer/video_interview_system/record', $data);
        } else {
            $sent_record_sid = $this->input->post('sent_record_sid');
            $update_array = array();
            $update_array['status'] = 'answered';
            $update_array['answer_date'] = date('Y-m-d H:i:s');

            if ($questionnaire['question_type'] == 'video' && !empty($this->input->post('video'))) {
                $video_name = $this->input->post('video');

                if (!empty($video_name)) {
                    $filePath = FCPATH . "assets/uploads/";
                    $aws = new AwsSdk();
                    $aws->putToBucket($video_name, $filePath . $video_name, CLOUD_VIDEO_LIBRARY);
                    $update_array['video_response'] = $video_name;
                    unlink($filePath . $video_name);
                } else {
                    redirect('video_interview_system/record?key=' . $verification_key);
                }
            } else if ($questionnaire['question_type'] == 'text') {
                $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                $this->form_validation->set_rules('text_response', 'Text Response', 'required|xss_clean|trim');

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('manage_employer/video_interview_system/record', $data);
                } else {
                    $update_array['text_response'] = $this->input->post('text_response');
                }
            }

            $video_source = $this->input->post('video_source');
            $update_array['response_video_source'] = $video_source;

            if ($video_source == 'youtube' || $video_source == 'vimeo') {
                $video_id = '';

                if ($video_source == 'youtube') {
                    $youtube_url = $this->input->post('youtube_video');
                    $url_prams = array();
                    parse_str(parse_url($youtube_url, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_id = $url_prams['v'];
                    }
                } elseif ($video_source == 'vimeo') {
                    $vimeo_url = $this->input->post('vimeo_video');
                    $video_id = $this->get_vimeo_id($vimeo_url);
                }
                if (!empty($video_id)) {
                    $update_array['response_video_id'] = $video_id;
                }
            }

            $this->video_interview_system_model->update_questionnaire_response($sent_record_sid, $update_array, $questionnaire, $verification_key);
            $question_sent_config = $this->video_interview_system_model->get_sent_configuration($verification_key);
            $notification_type = !empty($question_sent_config) && isset($question_sent_config['notification_type']) ? $question_sent_config['notification_type'] : 'group';
            $unanswered_total = $this->video_interview_system_model->get_applicant_questionnaire($verification_key, 'count', 1);

            if (($notification_type == 'group' && $unanswered_total == 0) || ($notification_type == 'individual')) {
                // send notification emails start //
                $notification_list = getNotificationContacts($questionnaire['company_sid'], 'video_interview', 'video_interview_notifications' );
                $company_data = get_company_details($questionnaire['company_sid']);
                $company_name = $company_data['CompanyName'];
                $applicant_data = $this->video_interview_system_model->get_applicant_data($questionnaire['applicant_sid']);

                foreach ($notification_list as $employee) {
                    $replacement_array = array();
                    $replacement_array['company_name'] = ucwords($company_name);
                    $replacement_array['employee_name'] = ucwords($employee['contact_name']);
                    $replacement_array['applicant_name'] = ucwords($applicant_data['first_name'] . ' ' . $applicant_data['last_name']);

                    if ($notification_type == 'group') {
                        $replacement_array['question'] = 'All Questions!';
                    } else {
                        if ($questionnaire['question_type'] == 'video') {
                            $replacement_array['question'] = ucwords($questionnaire['video_title']);
                        } else {
                            $replacement_array['question'] = ucwords($questionnaire['question_text']);
                        }
                    }


                    $message_hf = message_header_footer_domain($questionnaire['company_sid'], $company_name);
                    log_and_send_templated_email(VIDEO_INTERVIEW_QUESTIONNAIRE_RESPONSE, $employee['email'], $replacement_array, $message_hf);
                }
            }
            redirect('video_interview_system/record?key=' . $verification_key);
        }
    }

    public function record_old()
    {
        $data['title'] = STORE_NAME;
        $data['invalid'] = false;
        $data['complete'] = false;

        $verification_key = isset($_GET['key']) ? $_GET['key'] : '';
        $result = $this->video_interview_system_model->validate_record_key($verification_key);

        if (empty($verification_key) || $result == false) {
            $data['invalid'] = true;
            $this->load->view('manage_employer/video_interview_system/record', $data);
        } else {
            $unanswered_total = $this->video_interview_system_model->get_applicant_questionnaire($verification_key, 'count', 1);
            $data['unanswered_total'] = $unanswered_total;
            $data['answered_total'] = $this->video_interview_system_model->get_answered_questions_count($verification_key);
            $questionnaire = $this->video_interview_system_model->get_applicant_questionnaire($verification_key, null, 1);

            $company_data = $this->video_interview_system_model->get_company_name($verification_key);

            $data['title'] = $company_data['CompanyName'];
            $company_logo = $company_data['Logo'];

            if ($company_logo != '') {
                $data['logo'] = AWS_S3_BUCKET_URL . $company_logo;
            } else {
                $data['logo'] = AWS_S3_BUCKET_URL . 'default_pic-ySWxT.jpg';
            }

            if (empty($questionnaire)) {
                $data['complete'] = true;
                $this->load->view('manage_employer/video_interview_system/record', $data);
            } else {
                $data['questionnaire'] = $questionnaire;
                $submit = $this->input->post('submit');
                $video = $this->input->post('video');

                if (!empty($submit)) {
                    $sent_record_sid = $this->input->post('sent_record_sid');

                    $update_array = array();
                    $update_array['status'] = 'answered';
                    $update_array['answer_date'] = date('Y-m-d H:i:s');

                    if ($questionnaire['question_type'] == 'video' && !empty($video)) {
                        $video_name = $this->input->post('video');
                        if (!empty($video_name)) {
                            $filePath = FCPATH . "assets/uploads/";
                            $aws = new AwsSdk();
                            $aws->putToBucket($video_name, $filePath . $video_name, CLOUD_VIDEO_LIBRARY);
                            $update_array['video_response'] = $video_name;
                            unlink('assets/uploads/' . $video_name);
                        } else {
                            redirect('video_interview_system/record?key=' . $verification_key);
                        }
                    } else if ($questionnaire['question_type'] == 'text') {
                        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
                        $this->form_validation->set_rules('text_response', 'Text Response', 'required|xss_clean|trim');

                        if ($this->form_validation->run() == FALSE) {
                            $this->load->view('manage_employer/video_interview_system/record', $data);
                        } else {
                            $update_array['text_response'] = $this->input->post('text_response');
                        }
                    }

                    $video_source = $this->input->post('video_source');
                    $update_array['response_video_source'] = $video_source;
                    if ($video_source == 'youtube' || $video_source == 'vimeo') {
                        $video_id = '';
                        if ($video_source == 'youtube') {
                            $youtube_url = $this->input->post('youtube_video');
                            $url_prams = array();
                            parse_str(parse_url($youtube_url, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $video_id = $url_prams['v'];
                            }
                        } elseif ($video_source == 'vimeo') {
                            $vimeo_url = $this->input->post('vimeo_video');
                            $video_id = $this->get_vimeo_id($vimeo_url);
                        }
                        if (!empty($video_id)) {
                            $update_array['response_video_id'] = $video_id;
                        }
                    }

                    $this->video_interview_system_model->update_questionnaire_response($sent_record_sid, $update_array, $questionnaire, $verification_key);

                    $question_sent_config = $this->video_interview_system_model->get_sent_configuration($verification_key);
                    $notification_type = !empty($question_sent_config) && isset($question_sent_config['notification_type']) ?  $question_sent_config['notification_type'] : 'group';

                    if (($notification_type == 'group' && $unanswered_total == 0) || ($notification_type == 'individual')) {
                        // send notification emails start //
                        $notification_list = $this->video_interview_system_model->get_video_interview_system_notification_list($questionnaire['company_sid'], 'video_interview', 'active');
                        $company_data = get_company_details($questionnaire['company_sid']);
                        $company_name = $company_data['CompanyName'];
                        $applicant_data = $this->video_interview_system_model->get_applicant_data($questionnaire['applicant_sid']);

                        foreach ($notification_list as $employee) {
                            $replacement_array = array();
                            $replacement_array['company_name'] = ucwords($company_name);
                            $replacement_array['employee_name'] = ucwords($employee['contact_name']);
                            $replacement_array['applicant_name'] = ucwords($applicant_data['first_name'] . ' ' . $applicant_data['last_name']);
                            if ($questionnaire['question_type'] == 'video') {
                                $replacement_array['question'] = ucwords($questionnaire['video_title']);
                            } else {
                                $replacement_array['question'] = ucwords($questionnaire['question_text']);
                            }
                            $message_hf = message_header_footer_domain($questionnaire['company_sid'], $company_name);

                            log_and_send_templated_email(VIDEO_INTERVIEW_QUESTIONNAIRE_RESPONSE, $employee['email'], $replacement_array, $message_hf);
                        }
                        // send notification emails end //
                    }

                    redirect('video_interview_system/record?key=' . $verification_key);
                } else {
                    $this->load->view('manage_employer/video_interview_system/record', $data);
                }
            }
        }
    }

    public function candidate_instructions()
    {
        $data = array();
        $data['title'] = 'Candidate Instructions for Video Interview';
        $this->load->view('manage_employer/video_interview_system/candidate_instructions', $data);
    }

    public function upload()
    {
        if (isset($_FILES['data'])) {
            //
            $t = explode('.', $_FILES['data']['name']);
            $ext = $t[count($t) - 1];
            //
            $filename = "ahr_00_" . generateRandomString(15) . "_" . time() . "." . $ext;
            //
            copy($_FILES['data']['tmp_name'], FCPATH . "assets/uploads/" . $filename);
        } else {
            $data = substr($_POST['data'], strpos($_POST['data'], ",") + 1);
            $decodedData = base64_decode($data);
            $filename = "ahr_00_" . generateRandomString(15) . "_" . time() . ".webm";
            $filepath = FCPATH . "assets/uploads/" . $filename;
            $fp = fopen($filepath, 'wb');
            fwrite($fp, $decodedData);
            fclose($fp);
        }
        echo $filename;
    }

    public function rating()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules('comment', 'Comment', 'required|xss_clean|trim');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/video_interview_system/responses');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $formpost['company_sid'] = $company_sid;
                $formpost['employer_sid'] = $employer_sid;
                $formpost['date_added'] = date('Y-m-d H:i:s');

                $this->video_interview_system_model->save_rating($formpost);
                $this->session->set_flashdata('message', '<b>Success:</b> Applicant rating added successfully.');
                redirect('video_interview_system/responses/' . $formpost['applicant_sid']);
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_default()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            $questions = $this->input->post('questions');
            $template_sid = $this->input->post('template_sid');

            foreach ($questions as $question) {
                $insert_array = array();
                $insert_array['company_sid'] = $company_sid;
                $insert_array['employer_sid'] = $employer_sid;
                $insert_array['created_date'] = date('Y-m-d H:i:s');
                $insert_array['status'] = 'active';
                $insert_array['question_type'] = 'video';
                $insert_array['video_title'] = $question;
                if (!empty($template_sid)) {
                    $insert_array['template_sid'] = $template_sid;
                } else {
                    $insert_array['template_sid'] = 0;
                }
                $this->video_interview_system_model->add_video_questionnaire($insert_array, 'default');
            }

            $this->session->set_flashdata('message', '<b>Success:</b> Sample Question(s) added successfully.');
            if (empty($template_sid)) {
                redirect('video_interview_system');
            } else {
                redirect('video_interview_system/manage_template/' . $template_sid);
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function templates($applicant_sid = NULL, $job_list_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            $data['templates'] = $this->video_interview_system_model->get_questionnaire_templates($company_sid);

            if ($applicant_sid != NULL) {
                $data['title'] = "Video Interview System - Question Templates";
                $data['applicant_sid'] = $applicant_sid;
                $data['job_list_sid'] = $job_list_sid;
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/video_interview_system/select_templates');
                $this->load->view('main/footer');
            } else {
                $data['title'] = "Video Interview System - Video Template Management";
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/video_interview_system/templates');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_template()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "New Video Question Template";

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules('title', 'Template title', 'required|xss_clean|trim');
            $this->form_validation->set_rules('description', 'Template description', 'required|xss_clean|trim');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/video_interview_system/add_template');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $formpost['company_sid'] = $company_sid;
                $formpost['employer_sid'] = $employer_sid;
                $formpost['status'] = 'active';
                $formpost['created_date'] = date('Y-m-d H:i:s');
                unset($formpost['add_template_submit']);

                $this->video_interview_system_model->add_questionnaire_template($formpost);
                $this->session->set_flashdata('message', '<b>Success:</b> Question Template added successfully.');
                redirect('video_interview_system');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function manage_template($template_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Video Interview System - Manage Template Questions";
            $data['template_sid'] = $template_sid;

            // get the template title
            $data['template_questions'] = $this->video_interview_system_model->get_template_questions($company_sid, $template_sid);
            $data['template'] = $this->video_interview_system_model->get_questionnaire_template($company_sid, $template_sid);

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/video_interview_system/manage_template');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit_template($template_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'video_interview_system');

            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Video Interview System - Edit Question Template";

            $data['template'] = $this->video_interview_system_model->get_questionnaire_template($company_sid, $template_sid);

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules('title', 'Template title', 'required|xss_clean|trim');
            $this->form_validation->set_rules('description', 'Template description', 'required|xss_clean|trim');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/video_interview_system/edit_template');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $formpost['modified_by'] = $employer_sid;
                $formpost['modified_date'] = date('Y-m-d H:i:s');
                unset($formpost['edit_template_submit']);

                $this->video_interview_system_model->edit_questionnaire_template($formpost, $template_sid);
                $this->session->set_flashdata('message', '<b>Success:</b> Question Template updated successfully.');
                redirect('video_interview_system/templates');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }


    public function validate_youtube($str)
    {
        if ($this->session->userdata('logged_in')) {
            if ($str != "") {
                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $str, $matches);
                if (!isset($matches[0])) { //if validation not passed
                    $this->form_validation->set_message('validate_youtube', 'Invalid youtube video url.');
                    return FALSE;
                } else { //if validation passed
                    return TRUE;
                }
            } else {
                return true;
            }
        }
    }

    public function validate_vimeo($str)
    {
        if ($this->session->userdata('logged_in')) {
            if ($str != "") {
                return (int)substr(parse_url($str, PHP_URL_PATH), 1) > 0;
            } else {
                return true;
            }
        }
    }

    private function get_vimeo_id($url)
    {
        $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($url);

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $api_url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        $response = curl_exec($cSession);
        curl_close($cSession);

        //$response = @file_get_contents($api_url);
        $response = json_decode($response, true);

        if (isset($response['video_id'])) {
            $video_id = $response['video_id'];
        } else {
            $video_id = 0;
        }
        return $video_id;
    }
}
