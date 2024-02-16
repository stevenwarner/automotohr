<?php defined('BASEPATH') or exit('No direct script access allowed');

class Job_fair_configuration extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('job_fair_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'job_fair_config'); // Param2: Redirect URL, Param3: Function Name
            $company_sid = $data['session']['company_detail']['sid'];
            $job_fair_data = $this->job_fair_model->get_job_fair_data($company_sid);

            if (empty($job_fair_data)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Page not found!');
                redirect("my_settings", "location");
            }

            $data['employees'] = $this->job_fair_model->getAllEmployees($company_sid);
            $data['job_fair_data'] = $job_fair_data[0];
            $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
            $this->form_validation->set_rules('content', 'Content', 'required|trim');
            $this->form_validation->set_rules('picture_or_video', 'Show image or video', 'trim|xss_clean|required');

            if ($this->form_validation->run() == false) {
                $data['title'] = 'Job Fair Configuration';
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/job_fair_configuration');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $title = $formpost['title'];
                $content = $this->input->post('content', false);
                $picture_or_video = $formpost['picture_or_video'];
                $button_background_color = $formpost['button_background_color'];
                $button_text_color = $formpost['button_text_color'];
                $video_source = $formpost['video_source'];
                $visibility = $formpost['visibility'];

                if (isset($_FILES['banner_image']) && $_FILES['banner_image']['name'] != '') {
                    $result = put_file_on_aws('banner_image');
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
                        redirect('job_fair_configuration', 'refresh');
                    }

                    $video_id = $file_name;
                } else {
                    $video_id = $formpost['yt_vm_video_url'];

                    if (!empty($video_id)) {
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
                    } elseif (empty($video_id) && $video_source == 'upload') {
                        $video_id = $this->input->post('old_upload_video');
                    }
                }

                $insert_array = array();
                $insert_array['company_sid'] = $company_sid;
                $insert_array['title'] = $title;
                $insert_array['content'] = $content;
                $insert_array['video_type'] = $video_source;
                $insert_array['video_id'] = $video_id;
                $insert_array['picture_or_video'] = $picture_or_video;
                $insert_array['button_background_color'] = $button_background_color;
                $insert_array['button_text_color'] = $button_text_color;
                $insert_array['visibility_employees'] = implode(',', $visibility);

                if (isset($result) && $result != 'error' && $result != '') {
                    $insert_array['banner_image'] = $result;
                }

                $this->job_fair_model->save_job_fair($insert_array);
                $this->session->set_flashdata('message', '<strong>Success:</strong> Job Fair configuration updated successfully.');
                redirect('job_fair_configuration');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    function customize_form_listing()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_settings'); // Param2: Redirect URL, Param3: Function Name
            $company_sid = $data['session']['company_detail']['sid'];
            $job_fair_data = $this->job_fair_model->get_job_fair_data($company_sid);

            if (empty($job_fair_data)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Page not found!');
                redirect("my_settings", "location");
            }

            $data['job_fair_data'] = $job_fair_data[0];
            $job_fair_forms = $this->job_fair_model->fetch_job_fair_forms($company_sid, $job_fair_data[0]['sid']);
            $data['job_fair_forms'] = $job_fair_forms;
            $data['title'] = 'Job Fair Multiple Forms Listing';

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/job_fair_configuration_listing');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    function view_edit($sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            if ($sid == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Job Fair Form Not Found!');
                redirect('job_fair_configuration', "refresh");
            }

            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_settings'); // Param2: Redirect URL, Param3: Function Name
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $form_data                                                          = $this->job_fair_model->fetch_main_form_data($company_sid, $sid);
            $custom_temp = $this->job_fair_model->get_custom_templates($company_sid);
            $data['custom_temp'] = $custom_temp;

            if (empty($form_data)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Form not found!');
                redirect('job_fair_configuration/customize_form_listing', 'refresh');
            }

            $video_resume_default_field                                         = $this->job_fair_model->get_default_video_form_fields($company_sid, $sid);

            if (empty($video_resume_default_field)) {
                $default_optional_field = array(
                    'job_fairs_forms_sid' => $sid,
                    'company_sid' => $company_sid,
                    'field_id' => 'video_resume',
                    'field_name' => 'Allow Applicant to Upload an MP3, MP4, Youtube or Vimeo video in their application',
                    'field_type' => 'default',
                    'field_display_status' => 0,
                    'is_required' => 0,
                    'question_type' => 'custom_video_resume',
                    'sort_order' => 11
                );
                $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
            }

            $form_default_fields                                                = $this->job_fair_model->fetch_default_form_fields($company_sid, $sid, 'custom', 'default');
            $form_custom_fields                                                 = $this->job_fair_model->fetch_default_form_fields($company_sid, $sid, 'custom', 'custom');

            if (!empty($form_custom_fields)) {
                $form_custom_questions_options                                  = $this->job_fair_model->fetch_job_fairs_forms_questions_option($sid);
                $data['form_custom_questions_options']                          = $form_custom_questions_options;
            }



            $data['job_fair_data']                                              = $form_data[0];
            $data['form_name']                                                  = $form_data[0]['form_name'];
            $data['view_type']                                                  = 'edit';
            $data['id']                                                         = $sid;
            $data['form_default_fields']                                        = $form_default_fields;
            $data['form_custom_fields']                                         = $form_custom_fields;
            $data['title']                                                      = "Edit -'" . $data['form_name'] . "'";
            $data['submit_name']                                                = 'Update Form';
            $data['action_url']                                                 = base_url('job_fair_configuration/view_edit/' . $sid);
            $this->form_validation->set_rules('perform_action', 'action', 'required|trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $data['employees'] = $this->job_fair_model->getAllEmployees($company_sid);
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/job_fair_add_new');
                $this->load->view('main/footer');
            } else {
                $formpost                                                       = $this->input->post(NULL, TRUE);
                // Country, State, Resume are disabled. So fetch these from previous record and pass for further process
                $formpost['country']                                            = $form_default_fields[3]['field_name'];
                $formpost['state']                                              = $form_default_fields[4]['field_name'];
                $formpost['resume']                                             = $form_default_fields[8]['field_name'];
                $formpost['profile_picture']                                    = $form_default_fields[11]['field_name'];
                $form_name                                                      = $formpost['form_name'];
                $title                                                          = $formpost['title'];
                $content                                                        = $this->input->post('content', false);
                $picture_or_video                                               = $formpost['picture_or_video'];
                $button_background_color                                        = $formpost['button_background_color'];
                $button_text_color                                              = $formpost['button_text_color'];
                $temp_status                                                    = $formpost['template-status'];
                $video_source                                                   = $formpost['video_source'];
                $visibility                                                   = $formpost['visibility'];
                $temp_id                                                        = isset($formpost['template-id']) ? $formpost['template-id'] : $form_data[0]['template_sid'];

                if (isset($_FILES['banner_image']) && $_FILES['banner_image']['name'] != '') {
                    $result = put_file_on_aws('banner_image');
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
                        redirect('job_fair_configuration', 'refresh');
                    }

                    $video_id = $file_name;
                } else {
                    $video_id = $formpost['yt_vm_video_url'];

                    if (!empty($video_id)) {
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
                    } elseif (empty($video_id) && $video_source == 'upload') {
                        $video_id = $this->input->post('old_upload_video');
                    }
                }

                if ($picture_or_video == 'none') {
                    $video_source = 'youtube';
                }

                $data = array(
                    'form_name'                                     => $form_name,
                    'company_sid'                                   => $company_sid,
                    'form_type'                                     => 'custom',
                    'title'                                         => $title,
                    'content'                                       => $content,
                    'picture_or_video'                              => $picture_or_video,
                    'video_type'                                    => $video_source,
                    'video_id'                                      => $video_id,
                    'button_background_color'                       => $button_background_color,
                    'button_text_color'                             => $button_text_color,
                    'template_sid'                                  => $temp_id,
                    'visibility_employees'                                  => implode(',', $visibility),
                    'template_status'                               => $temp_status
                );

                if (isset($result) && $result != 'error' && $result != '') {
                    $data['banner_image']                                       = $result;
                }

                $this->job_fair_model->update_job_fairs_forms($sid, $data);
                $this->add_edit_default_questions($formpost, 'update_form', $sid, $company_sid);

                if (!empty($form_custom_fields)) {
                    foreach ($form_custom_fields as $key => $field) {
                        $custom_field                                           = $field['field_id'];
                        $custom_field_status_id                                 = $custom_field . '-display_status';
                        $custom_field_is_required_id                            = $custom_field . '-is_required';
                        $custom_field_status_value                              = 0;
                        $custom_field_is_required_value                         = 0;

                        if (isset($formpost[$custom_field_status_id])) {
                            $custom_field_status_value                          = 1;
                        }

                        if (isset($formpost[$custom_field_is_required_id])) {
                            $custom_field_is_required_value                     = 1;
                        }

                        $custom_field_name                                      = $formpost[$custom_field];
                        $default_custom_field                                   = array(
                            'field_display_status' => $custom_field_status_value,
                            'is_required' => $custom_field_is_required_value,
                            'field_name' => $custom_field_name
                        );
                        if ($field['question_type'] != 'list' && $field['question_type'] != 'multilist') {
                            $result_id = $this->job_fair_model->update_form_data($default_custom_field, 'job_fairs_forms_questions', $sid, $company_sid, $custom_field);
                        }
                    }
                }

                $this->session->set_flashdata('message', '<b>Success:</b> Form updated succesfully');
                redirect('job_fair_configuration/view_edit/' . $sid, 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function add_new()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_settings'); // Param2: Redirect URL, Param3: Function Name
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $default_fair_form                                                  = $this->job_fair_model->fetch_default_fair_form($company_sid);
            $default_form_sid                                                   = $default_fair_form['sid'];
            $job_fairs_recruitment_sid                                          = $default_fair_form['job_fairs_recruitment_sid'];
            $form_default_fields                                                = $this->job_fair_model->fetch_default_form_fields($company_sid, $default_form_sid, 'default', 'default');
            $data['form_default_fields']                                        = $form_default_fields;
            $data['form_custom_fields']                                         = array();
            // echo '<pre>'; print_r($form_default_fields); exit;
            $data['title']                                                      = 'Create New Job Fair Form';
            $data['form_name']                                                  = '';
            $data['view_type']                                                  = 'new';
            $data['id']                                                         = '';
            $data['submit_name']                                                = 'Save Form';
            $data['action_url']                                                 = base_url('job_fair_configuration/add_new');
            $this->form_validation->set_rules('perform_action', 'action', 'required|trim|xss_clean');
            $default_job_fairs_recruitment                                      = $this->job_fair_model->get_job_fair_data($company_sid);
            $data['job_fair_data']                                              = $default_job_fairs_recruitment[0];
            $custom_temp = $this->job_fair_model->get_custom_templates($company_sid);
            $data['custom_temp'] = $custom_temp;


            if ($this->form_validation->run() === FALSE) {
                $data['employees'] = $this->job_fair_model->getAllEmployees($company_sid);
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/job_fair_add_new');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $formpost['country']                                            = $form_default_fields[3]['field_name'];
                $formpost['state']                                              = $form_default_fields[4]['field_name'];
                $formpost['resume']                                             = $form_default_fields[9]['field_name'];
                $formpost['profile_picture']                                    = $form_default_fields[11]['field_name'];
                $title                                                          = $formpost['title'];
                $content                                                        = $this->input->post('content', false);
                $video_id                                                       = isset($formpost['video_id']) ? $formpost['video_id'] : '';
                $picture_or_video                                               = $formpost['picture_or_video'];
                $button_background_color                                        = $formpost['button_background_color'];
                $button_text_color                                              = $formpost['button_text_color'];
                $temp_status                                                    = $formpost['template-status'];
                $visibility                                                     = $formpost['visibility'];
                $temp_id                                                        = isset($formpost['template-id']) ? $formpost['template-id'] : 0;

                if (isset($_FILES['banner_image']) && $_FILES['banner_image']['name'] != '') {
                    $result                                                     = put_file_on_aws('banner_image');
                }

                if (isset($video_id) && $video_id != '' && $video_id != NULL) {
                    $video_id                                                   = substr($video_id, strpos($video_id, '=') + 1, strlen($video_id));
                }

                $form_name = $formpost['form_name'];
                $form_data = array(
                    'form_name'                                  => $form_name,
                    'job_fairs_recruitment_sid'                  => $job_fairs_recruitment_sid,
                    'company_sid'                                => $company_sid,
                    'form_type'                                  => 'custom',
                    'title'                                      => $title,
                    'content'                                    => $content,
                    'picture_or_video'                           => $picture_or_video,
                    'video_id'                                   => $video_id,
                    'button_background_color'                    => $button_background_color,
                    'button_text_color'                          => $button_text_color,
                    'template_sid'                               => $temp_id,
                    'visibility_employees'                       => implode(',', $visibility),
                    'template_status'                            => $temp_status
                );

                if (isset($result) && $result != 'error' && $result != '') {
                    $form_data['banner_image']                                  = $result;
                }

                $this->job_fair_model->inactivate_all_fair_form($company_sid); // step one - Make all the current forms of the company as in-active
                $job_fairs_forms_sid                                            = $this->job_fair_model->add_new_data($form_data, 'job_fairs_forms');

                $this->add_edit_default_questions($formpost, 'new_form', $job_fairs_forms_sid, $company_sid);
                $this->session->set_flashdata('message', '<b>Success:</b> New form added succesfully');
                redirect('job_fair_configuration/customize_form_listing', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function add_custom_field($sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            if ($sid == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Job Fair Form Not Found!');
                redirect('job_fair_configuration', "refresh");
            }

            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_settings'); // Param2: Redirect URL, Param3: Function Name
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $form_data                                                          = $this->job_fair_model->fetch_main_form_data($company_sid, $sid);

            if (empty($form_data)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Form not found!');
                redirect('job_fair_configuration/customize_form_listing', 'refresh');
            }

            $form_default_fields                                                = $this->job_fair_model->fetch_default_form_fields($company_sid, $sid, 'custom', 'default');
            $data['form_name']                                                  = $form_data[0]['form_name'];
            $data['view_type']                                                  = 'edit';
            $data['form_default_fields']                                        = $form_default_fields;
            $data['title']                                                      = "Add Custom Field for -'" . $data['form_name'] . "'";
            $data['submit_name']                                                = 'Add Custom Field';
            $data['action_url']                                                 = base_url('job_fair_configuration/add_custom_field/' . $sid);
            $data['id']                                                         = $sid;
            $this->form_validation->set_rules('perform_action', 'action', 'required|trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/job_fair_add_custom_field');
                $this->load->view('main/footer');
            } else {
                $formpost                                                       = $this->input->post(NULL, TRUE);
                $form_name                                                      = $formpost['form_name'];
                $field_name                                                     = $formpost['caption'];
                $is_required                                                    = 0;
                $question_type                                                  = $formpost['question_type'];
                $field_id                                                       = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '_', $field_name));

                if (isset($formpost['is_required'])) {
                    $is_required = 1;
                }

                $data_job_fairs_forms_questions = array(
                    'company_sid'           => $company_sid,
                    'job_fairs_forms_sid'   => $sid,
                    'field_id'              => $field_id,
                    'field_name'            => $field_name,
                    'field_type'            => 'custom',
                    'field_priority'        => 'optional',
                    'field_display_status'  => 1,
                    'is_required'           => $is_required,
                    'question_type'         => $question_type
                );

                $questions_sid                                                  = $this->job_fair_model->add_new_data($data_job_fairs_forms_questions, 'job_fairs_forms_questions');

                if ($question_type == 'multilist') {
                    $multilist_value                                            = $formpost['multilist_value'];

                    for ($i = 0; $i < count($multilist_value); $i++) {
                        $multi_value                                            = $multilist_value[$i];
                        $insert_data = array(
                            'job_fairs_forms_sid'              => $sid,
                            'questions_sid'                     => $questions_sid,
                            'value'                             => $multi_value
                        );

                        $this->job_fair_model->add_new_data($insert_data, 'job_fairs_forms_questions_option');
                    }
                }

                if ($question_type == 'list') {
                    $singlelist_value                                           = $formpost['singlelist_value'];

                    for ($i = 0; $i < count($singlelist_value); $i++) {
                        $singlelist                                             = $singlelist_value[$i];
                        $insert_data = array(
                            'job_fairs_forms_sid'              => $sid,
                            'questions_sid'                     => $questions_sid,
                            'value'                             => $singlelist
                        );
                        $this->job_fair_model->add_new_data($insert_data, 'job_fairs_forms_questions_option');
                    }
                }

                $this->session->set_flashdata('message', '<b>Success:</b> Form updated succesfully');
                redirect('job_fair_configuration/view_edit/' . $sid, 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    function edit_custom_field($form_sid = NULL, $que_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            if ($form_sid == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Job Fair Form Not Found!');
                redirect('job_fair_configuration', "refresh");
            }

            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_settings'); // Param2: Redirect URL, Param3: Function Name
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $form_data                                                          = $this->job_fair_model->fetch_main_form_data($company_sid, $form_sid);

            if (empty($form_data)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Form not found!');
                redirect('job_fair_configuration/customize_form_listing', 'refresh');
            }

            $field_options                                                      = $this->job_fair_model->fetch_custom_fields($que_sid);
            $data['custom_field_values']                                        = $field_options;
            $data['form_name']                                                  = $form_data[0]['form_name'];
            $data['view_type']                                                  = 'edit';
            $data['title']                                                      = "Edit Custom Field for -'" . $data['form_name'] . "'";
            $data['submit_name']                                                = 'Edit Custom Field';
            $data['action_url']                                                 = base_url('job_fair_configuration/edit_custom_field/' . $form_sid . '/' . $que_sid);
            $data['id']                                                         = $form_sid;
            $this->form_validation->set_rules('perform_action', 'action', 'required|trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/job_fair_edit_custom_field');
                $this->load->view('main/footer');
            } else {
                $formpost                                                       = $this->input->post(NULL, TRUE);
                $form_name                                                      = $formpost['form_name'];
                $field_name                                                     = $formpost['caption'];
                $is_required                                                    = 0;
                $question_type                                                  = $formpost['question_type'];
                $field_id                                                       = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '_', $field_name));

                if (isset($formpost['is_required'])) {
                    $is_required = 1;
                }

                $data_job_fairs_forms_questions = array(
                    'company_sid'           => $company_sid,
                    'job_fairs_forms_sid'   => $form_sid,
                    'field_id'              => $field_id,
                    'field_name'            => $field_name,
                    'field_type'            => 'custom',
                    'field_priority'        => 'optional',
                    'field_display_status'  => 1,
                    'is_required'           => $is_required,
                    'question_type'         => $question_type
                );

                $this->job_fair_model->update_question_data($data_job_fairs_forms_questions, $que_sid, 'job_fairs_forms_questions');
                $this->job_fair_model->delete_previous_option($que_sid);

                if ($question_type == 'multilist') {
                    $multilist_value                                            = $formpost['multilist_value'];

                    for ($i = 0; $i < count($multilist_value); $i++) {
                        $multi_value                                            = $multilist_value[$i];
                        $insert_data                                            = array(
                            'job_fairs_forms_sid' => $form_sid,
                            'questions_sid' => $que_sid,
                            'value' => $multi_value
                        );
                        $this->job_fair_model->add_new_data($insert_data, 'job_fairs_forms_questions_option');
                    }
                }

                if ($question_type == 'list') {
                    $singlelist_value                                           = $formpost['singlelist_value'];

                    for ($i = 0; $i < count($singlelist_value); $i++) {
                        $singlelist = $singlelist_value[$i];

                        $insert_data                                            = array(
                            'job_fairs_forms_sid' => $form_sid,
                            'questions_sid' => $que_sid,
                            'value' => $singlelist
                        );

                        $this->job_fair_model->add_new_data($insert_data, 'job_fairs_forms_questions_option');
                    }
                }

                $this->session->set_flashdata('message', '<b>Success:</b> Field updated succesfully');
                redirect('job_fair_configuration/view_edit/' . $form_sid, 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    function add_edit_default_questions($formpost, $post_type, $job_fairs_forms_sid, $company_sid)
    {
        $country_display_status                                             = 0;
        $country_is_required                                                = 0;
        $state_display_status                                               = 0;
        $state_is_required                                                  = 0;
        $city_display_status                                                = 0;
        $city_is_required                                                   = 0;
        $phone_number_display_status                                        = 0;
        $phone_number_is_required                                           = 0;
        $desired_job_title_display_status                                   = 0;
        $desired_job_title_is_required                                      = 0;
        $college_university_name_display_status                             = 0;
        $college_university_name_is_required                                = 0;
        $resume_display_status                                              = 0;
        $resume_is_required                                                 = 0;
        $job_interest_text_display_status                                   = 0;
        $job_interest_text_is_required                                      = 0;
        $video_display_status                                               = 0;
        $video_is_required                                                  = 0;
        $profile_picture_display_status                                     = 0;
        $profile_picture_is_required                                        = 0;

        if (isset($formpost['country-display_status'])) {
            $country_display_status                                         = $formpost['country-display_status'];
        }

        if (isset($formpost['country-is_required'])) {
            $country_is_required                                            = $formpost['country-is_required'];
        }

        if (isset($formpost['state-display_status'])) {
            $state_display_status                                           = $formpost['state-display_status'];
        }

        if (isset($formpost['state-is_required'])) {
            $state_is_required                                              = $formpost['state-is_required'];
        }

        if (isset($formpost['city-display_status'])) {
            $city_display_status                                            = $formpost['city-display_status'];
        }

        if (isset($formpost['city-is_required'])) {
            $city_is_required                                               = $formpost['city-is_required'];
        }

        if (isset($formpost['phone_number-display_status'])) {
            $phone_number_display_status                                    = $formpost['phone_number-display_status'];
        }

        if (isset($formpost['phone_number-is_required'])) {
            $phone_number_is_required                                       = $formpost['phone_number-is_required'];
        }

        if (isset($formpost['desired_job_title-display_status'])) {
            $desired_job_title_display_status                               = $formpost['desired_job_title-display_status'];
        }

        if (isset($formpost['desired_job_title-is_required'])) {
            $desired_job_title_is_required                                  = $formpost['desired_job_title-is_required'];
        }

        if (isset($formpost['college_university_name-display_status'])) {
            $college_university_name_display_status                         = $formpost['college_university_name-display_status'];
        }

        if (isset($formpost['college_university_name-is_required'])) {
            $college_university_name_is_required                            = $formpost['college_university_name-is_required'];
        }

        if (isset($formpost['resume-display_status'])) {
            $resume_display_status                                          = $formpost['resume-display_status'];
        }

        if (isset($formpost['resume-is_required'])) {
            $resume_is_required                                             = $formpost['resume-is_required'];
        }

        if (isset($formpost['job_interest_text-display_status'])) {
            $job_interest_text_display_status                               = $formpost['job_interest_text-display_status'];
        }

        if (isset($formpost['job_interest_text-is_required'])) {
            $job_interest_text_is_required                                  = $formpost['job_interest_text-is_required'];
        }

        if (isset($formpost['video_resume-display_status'])) {
            $video_display_status                                           = $formpost['video_resume-display_status'];
        }

        if (isset($formpost['video_resume-is_required'])) {
            $video_is_required                                              = $formpost['video_resume-is_required'];
        }

        if (isset($formpost['profile_picture-display_status'])) {
            $profile_picture_display_status                                  = $formpost['profile_picture-display_status'];
        }

        if (isset($formpost['profile_picture-is_required'])) {
            $profile_picture_is_required                                     = $formpost['profile_picture-is_required'];
        }

        $country                                                            = $formpost['country'];
        $state                                                              = $formpost['state'];
        $city                                                               = $formpost['city'];
        $phone_number                                                       = $formpost['phone_number'];
        $college_university_name                                            = $formpost['college_university_name'];
        $resume                                                             = $formpost['resume'];
        $job_interest_text                                                  = $formpost['job_interest_text'];
        $video_resume_text                                                  = $formpost['video_resume'];
        $profile_picture_text                                               = $formpost['profile_picture'];

        // optional fields which will always be part of form *** start ***
        if ($post_type == 'new_form') {
            $default_optional_field = array();
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'first_name',
                'field_name' => 'First Name',
                'field_type' => 'default',
                'field_priority' => 'mandatory',
                'field_display_status' => 1,
                'is_required' => 1,
                'question_type' => 'string',
                'sort_order' => 0
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');

            $default_optional_field = array();
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'last_name',
                'field_name' => 'Last Name',
                'field_type' => 'default',
                'field_priority' => 'mandatory',
                'field_display_status' => 1,
                'is_required' => 1,
                'question_type' => 'string',
                'sort_order' => 1
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');

            $default_optional_field = array();
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'email',
                'field_name' => 'Email Address',
                'field_type' => 'default',
                'field_priority' => 'mandatory',
                'field_display_status' => 1,
                'is_required' => 1,
                'question_type' => 'string',
                'sort_order' => 2
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        }

        $default_optional_field = array();

        if ($post_type == 'new_form') {
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'country',
                'field_name' => $country,
                'field_type' => 'default',
                'field_display_status' => $country_display_status,
                'is_required' => $country_is_required,
                'question_type' => 'list',
                'sort_order' => 3
            );
            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        } else {
            $default_optional_field = array(
                'field_name' => $country,
                'field_display_status' => $country_display_status,
                'is_required' => $country_is_required
            );
            $result_id = $this->job_fair_model->update_form_data($default_optional_field, 'job_fairs_forms_questions', $job_fairs_forms_sid, $company_sid, 'country');
        }

        $default_optional_field = array();

        if ($post_type == 'new_form') {
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'state',
                'field_name' => $state,
                'field_type' => 'default',
                'field_display_status' => $state_display_status,
                'is_required' => $state_is_required,
                'question_type' => 'list',
                'sort_order' => 4
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        } else {
            $default_optional_field = array(
                'field_name' => $state,
                'field_display_status' => $state_display_status,
                'is_required' => $state_is_required
            );

            $result_id = $this->job_fair_model->update_form_data($default_optional_field, 'job_fairs_forms_questions', $job_fairs_forms_sid, $company_sid, 'state');
        }

        $default_optional_field = array();

        if ($post_type == 'new_form') {
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'city',
                'field_name' => $city,
                'field_type' => 'default',
                'field_display_status' => $city_display_status,
                'is_required' => $city_is_required,
                'question_type' => 'string',
                'sort_order' => 5
            );
            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        } else {
            $default_optional_field = array(
                'field_name' => $city,
                'field_display_status' => $city_display_status,
                'is_required' => $city_is_required
            );
            $result_id = $this->job_fair_model->update_form_data($default_optional_field, 'job_fairs_forms_questions', $job_fairs_forms_sid, $company_sid, 'city');
        }

        $default_optional_field = array();

        if ($post_type == 'new_form') {
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'phone_number',
                'field_name' => $phone_number,
                'field_type' => 'default',
                'field_display_status' => $phone_number_display_status,
                'is_required' => $phone_number_is_required,
                'question_type' => 'string',
                'sort_order' => 6
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        } else {
            $default_optional_field = array(
                'field_name' => $phone_number,
                'field_display_status' => $phone_number_display_status,
                'is_required' => $phone_number_is_required
            );
            $result_id = $this->job_fair_model->update_form_data($default_optional_field, 'job_fairs_forms_questions', $job_fairs_forms_sid, $company_sid, 'phone_number');
        }

        $default_optional_field = array();

        if ($post_type == 'new_form') {
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'college_university_name',
                'field_name' => $college_university_name,
                'field_type' => 'default',
                'field_display_status' => $college_university_name_display_status,
                'is_required' => $college_university_name_is_required,
                'question_type' => 'string',
                'sort_order' => 8
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        } else {
            $default_optional_field = array(
                'field_name' => $college_university_name,
                'field_display_status' => $college_university_name_display_status,
                'is_required' => $college_university_name_is_required
            );

            $result_id = $this->job_fair_model->update_form_data($default_optional_field, 'job_fairs_forms_questions', $job_fairs_forms_sid, $company_sid, 'college_university_name');
        }

        $default_optional_field = array();

        if ($post_type == 'new_form') {
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'resume',
                'field_name' => $resume,
                'field_type' => 'default',
                'field_display_status' => $resume_display_status,
                'is_required' => $resume_is_required,
                'question_type' => 'file',
                'sort_order' => 9
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        } else {
            $default_optional_field = array(
                'field_name' => $resume,
                'field_display_status' => $resume_display_status,
                'is_required' => $resume_is_required
            );

            $result_id = $this->job_fair_model->update_form_data($default_optional_field, 'job_fairs_forms_questions', $job_fairs_forms_sid, $company_sid, 'resume');
        }

        $default_optional_field = array();

        if ($post_type == 'new_form') {
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'job_interest_text',
                'field_name' => $job_interest_text,
                'field_type' => 'default',
                'field_display_status' => $job_interest_text_display_status,
                'is_required' => $job_interest_text_is_required,
                'question_type' => 'string',
                'sort_order' => 10
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        } else {
            $default_optional_field = array(
                'field_name' => $job_interest_text,
                'field_display_status' => $job_interest_text_display_status,
                'is_required' => $job_interest_text_is_required
            );

            $result_id = $this->job_fair_model->update_form_data($default_optional_field, 'job_fairs_forms_questions', $job_fairs_forms_sid, $company_sid, 'job_interest_text');
        }

        if ($post_type == 'new_form') {
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'video_resume',
                'field_name' => $video_resume_text,
                'field_type' => 'default',
                'field_display_status' => $video_display_status,
                'is_required' => $video_is_required,
                'question_type' => 'custom_video_resume',
                'sort_order' => 11
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        } else {
            $default_optional_field = array(
                'field_name' => $video_resume_text,
                'field_display_status' => $video_display_status,
                'is_required' => $video_is_required
            );
            $result_id = $this->job_fair_model->update_form_data($default_optional_field, 'job_fairs_forms_questions', $job_fairs_forms_sid, $company_sid, 'video_resume');
        }

        if ($post_type == 'new_form') {
            $default_optional_field = array(
                'job_fairs_forms_sid' => $job_fairs_forms_sid,
                'company_sid' => $company_sid,
                'field_id' => 'profile_picture',
                'field_name' => $profile_picture_text,
                'field_type' => 'default',
                'field_display_status' => $profile_picture_display_status,
                'is_required' => $profile_picture_is_required,
                'question_type' => 'file',
                'sort_order' => 12
            );

            $result_id = $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
        } else {
            $default_optional_field = array(
                'field_name' => $profile_picture_text,
                'field_display_status' => $profile_picture_display_status,
                'is_required' => $profile_picture_is_required
            );

            $result_id = $this->job_fair_model->update_form_data($default_optional_field, 'job_fairs_forms_questions', $job_fairs_forms_sid, $company_sid, 'profile_picture');
        }
        // optional fields which will always be part of form **** End ****
    }

    public function update_status()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $sid = $this->input->post('sid');
            $id = $this->job_fair_model->change_status($company_sid, $sid);
            echo $id;
        } else {
            echo '';
        }
    }

    public function delete_field()
    {
        if ($this->session->userdata('logged_in')) {
            $sid = $this->input->post('sid');
            $this->job_fair_model->delete_custom_field($sid);
            $this->session->set_flashdata('message', '<b>Success:</b> Field deleted succesfully');
            echo 'deleted';
        } else {
            echo '';
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



    //
    function job_clone($sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {

            $sid = $this->input->post('jobId', TRUE);

            if ($sid == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Job Fair Form Not Found!');
                redirect('job_fair_configuration', "refresh");
            }

            //
            $data['session']                                                    = $this->session->userdata('logged_in');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $job_data                                                         = $this->job_fair_model->fetch_main_form_data($company_sid, $sid);

            //
            $form_data = array(
                'form_name'                                  => $job_data[0]['form_name'],
                'job_fairs_recruitment_sid'                  => $job_data[0]['job_fairs_recruitment_sid'],
                'company_sid'                                => $job_data[0]['company_sid'],
                'form_type'                                  => $job_data[0]['form_type'],
                'title'                                      => $job_data[0]['title'],
                'content'                                    => $job_data[0]['content'],
                'picture_or_video'                           => $job_data[0]['picture_or_video'],
                'video_id'                                   => $job_data[0]['video_id'],
                'button_background_color'                    => $job_data[0]['button_background_color'],
                'button_text_color'                          => $job_data[0]['button_text_color'],
                'template_sid'                               => $job_data[0]['template_sid'],
                'visibility_employees'                       => $job_data[0]['visibility_employees'],
                'template_status'                            => $job_data[0]['template_status'],
                'status'                            => $job_data[0]['status'],
                'video_type'                            => $job_data[0]['video_type'],
                'banner_image'                            => $job_data[0]['banner_image'],
                'page_url'                            => $job_data[0]['page_url']
            );


            $job_fairs_forms_sid = $this->job_fair_model->add_new_data($form_data, 'job_fairs_forms');
            $video_resume_default_field = $this->job_fair_model->get_default_video_form_fields($company_sid, $sid);

            if (!empty($video_resume_default_field)) {
                $default_optional_field = array(
                    'job_fairs_forms_sid' => $job_fairs_forms_sid,
                    'company_sid' => $company_sid,
                    'field_id' => $video_resume_default_field[0]['field_id'],
                    'field_name' => $video_resume_default_field[0]['field_name'],
                    'field_type' => $video_resume_default_field[0]['field_type'],
                    'field_display_status' => $video_resume_default_field[0]['field_display_status'],
                    'is_required' => $video_resume_default_field[0]['is_required'],
                    'question_type' => $video_resume_default_field[0]['question_type'],
                    'sort_order' => $video_resume_default_field[0]['sort_order']
                );
                $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
            }

            //
            $form_default_fields = $this->job_fair_model->fetch_default_form_fields($company_sid, $sid, 'custom', 'default');
            $form_custom_fields  = $this->job_fair_model->fetch_default_form_fields($company_sid, $sid, 'custom', 'custom');

            //
            foreach ($form_default_fields as  $form_default_fields_row) {
                $default_optional_field = array();
                $default_optional_field = array(
                    'job_fairs_forms_sid' => $job_fairs_forms_sid,
                    'company_sid' => $form_default_fields_row['company_sid'],
                    'field_id' => $form_default_fields_row['field_id'],
                    'field_name' => $form_default_fields_row['field_name'],
                    'field_type' => $form_default_fields_row['field_type'],
                    'field_priority' => $form_default_fields_row['field_priority'],
                    'field_display_status' => $form_default_fields_row['field_display_status'],
                    'is_required' => $form_default_fields_row['is_required'],
                    'question_type' => $form_default_fields_row['question_type'],
                    'sort_order' => $form_default_fields_row['sort_order']
                );

                $this->job_fair_model->add_new_data($default_optional_field, 'job_fairs_forms_questions');
            }

            //
            foreach ($form_custom_fields as  $form_custom_fields_row) {
                $custom_optional_field = array();
                $custom_optional_field = array(
                    'job_fairs_forms_sid' => $job_fairs_forms_sid,
                    'company_sid' => $form_custom_fields_row['company_sid'],
                    'field_id' => $form_custom_fields_row['field_id'],
                    'field_name' => $form_custom_fields_row['field_name'],
                    'field_type' => $form_custom_fields_row['field_type'],
                    'field_priority' => $form_custom_fields_row['field_priority'],
                    'field_display_status' => $form_custom_fields_row['field_display_status'],
                    'is_required' => $form_custom_fields_row['is_required'],
                    'question_type' => $form_custom_fields_row['question_type'],
                    'sort_order' => $form_custom_fields_row['sort_order']
                );

                $question_id = $this->job_fair_model->add_new_data($custom_optional_field, 'job_fairs_forms_questions');
                $optionsData = $this->job_fair_model->fetch_job_fairs_forms_question_option_byid($form_custom_fields_row['sid'], $form_custom_fields_row['job_fairs_forms_sid']);

                if (!empty($optionsData)) {
                    foreach ($optionsData as  $options_row) {
                        $custom_options_field = array();
                        $custom_options_field = array(
                            'job_fairs_forms_sid' => $job_fairs_forms_sid,
                            'questions_sid' => $question_id,
                            'value' => $options_row['value']
                        );

                        $this->job_fair_model->add_new_data($custom_options_field, 'job_fairs_forms_questions_option');
                    }
                }
            }
        }
    }


    //
    public function updateSortOrder()
    {
        //
        $newSortKey = $this->input->post(null, true);
        foreach ($newSortKey['sortOrders'] as $key => $value) {
            $data['sort_order'] = $key;
            $this->job_fair_model->update_question_data($data, $value, 'job_fairs_forms_questions');
        }
        //
        $msg = "Sort Order Updated Successfully.";
        return SendResponse(200, [
            "msg" => $msg
        ]);
    }
}
