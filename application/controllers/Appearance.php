<?php defined('BASEPATH') or exit('No direct script access allowed');

class Appearance extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('appearance_model');
        $this->load->model('customize_appearance_model');
        $this->load->model('testimonials_model');
        $this->load->model('themes_pages_model');
        $this->load->model('manage_admin/company_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index()
    {
        header("X-XSS-Protection: 0");
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'appearance');
            $data['title'] = "Themes & Appearance";
            $employer_id = $data["session"]["company_detail"]["sid"];
            $themes = $this->appearance_model->get_themes($employer_id);
            $current_date_time = date('Y-m-d H:i:s');
            $theme_available = 0;
            $default_theme_id = $this->appearance_model->GetThemeId('theme-1', $employer_id);

            foreach ($themes as $key => $theme) {
                $purchase_date = $theme['purchase_date'];
                $expiry_date = $theme['expiry_date'];

                if ($theme['is_paid'] == 1) {
                    if ($theme['purchased'] == 1) {
                        if ($expiry_date != null) {
                            if ($purchase_date != null) {
                                if ($purchase_date < $current_date_time && $expiry_date > $current_date_time) {
                                    $theme_available = 1;
                                    $theme['theme_available'] = $theme_available;
                                } else {
                                    $theme_available = 0;
                                    $theme['theme_available'] = $theme_available;
                                }
                            }

                            if ($theme_available == 0) {
                                if ($theme['theme_status'] == 1) {
                                    $theme['theme_status'] = 0;
                                    $this->appearance_model->active_theme($default_theme_id, $employer_id);
                                    $this->appearance_model->SetPurchasedStatus($theme['sid'], 0);
                                    $this->session->set_flashdata('message', '<b>Error:</b>' . strtoupper($theme['theme_name'] . ' subscription has expired, reseting to default Theme-1.'));
                                }
                            }
                        } else {
                            $theme_available = 1;
                            $theme['theme_available'] = $theme_available;
                        }
                    } else {
                        $theme_available = 0;
                        $theme['theme_available'] = $theme_available;
                    }

                    $themes[$key] = $theme;
                } else {
                    $theme['theme_available'] = 1;
                    $themes[$key] = $theme;
                }
            }

            if ($data['session']['company_detail']['expiry_date'] != NULL) {
                $data['company_expiry_days'] = get_company_expiry_days($data['session']['company_detail']['expiry_date']);
                $data['company_upgrade'] = 'normal';
            } else {
                $data['company_expiry_days'] = get_company_expiry_days($data['session']['company_detail']['expiry_date']);
                $data['company_upgrade'] = 'email';
            }

            $data['themes'] = $themes;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/appearance_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function enterprise_theme_activation()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $data['employer_sid'] = $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'appearance');
            $company_expiry_days = get_company_expiry_days($data['session']['company_detail']['expiry_date']);

            if ($company_expiry_days > 0) {
                $data['title'] = "Enterprise Theme Activation";
                $data['companyDetail'] = $data['session']['company_detail']['sid'];
                $this->load->model('account_activation_model');
                $data['enterprise_products'] = $this->appearance_model->get_enterprise_theme_product(); //Getting Account package products from DB
                $data['enterprise_products'][0]['expiry_days'] = $company_expiry_days;
                $data['enterprise_products'][0]['price'] = $company_expiry_days * $data['enterprise_products'][0]['price'];
                $this->load->view('manage_employer/enterprise_theme_activation', $data);
            } else {
                redirect(base_url('appearance'));
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function customize_appearance($theme_id = 0)
    {
        header("X-XSS-Protection: 0");
        if ($this->session->userdata('logged_in')) {
            if ($theme_id == 0) { // theme not found - redirect to appreance
                $this->session->set_flashdata('message', '<b>Error:</b> Theme not found!');
                redirect('appearance', 'refresh');
            }

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'customize_appearance');
            $company_id = $data['session']['company_detail']['sid'];
            $data['title'] = 'Customize Appearance';
            $job_fair_configuration = $this->appearance_model->job_fair_configuration($company_id);
            $data['job_fair_configuration'] = $job_fair_configuration;
            $data['theme_id'] = $theme_id;
            $job_fair_multiple_forms = array();

            if ($job_fair_configuration == 1) {
                $job_fair_multiple_forms = $this->appearance_model->get_all_job_fair_forms($company_id); //get default form data from main table and custom form data from secondary table
            }

            $data['job_fair_data'] = array('title' => 'N/A');
            $data['job_fair_multiple_forms'] = $job_fair_multiple_forms;
            $jobs_detail_page_banner = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'jobs_detail', 'jobs_detail_page_banner');


            if (isset($_POST["action"]) && $_POST["action"] == "update") {
                $theme_name = $this->input->post('theme_name');

                $sid = $this->input->post('sid');
                $job_fair_homepage_page_url = !empty($this->input->post('job_fair_homepage_page_url')) ? implode(',', $this->input->post('job_fair_homepage_page_url')) : '';
                $theme4_enable_job_fair_homepage = 0;

                if (isset($_POST['job_fair'])) {
                    $theme4_enable_job_fair_homepage = 1;
                }

                if ($theme_name == 'theme-1') {
                    $heading_color = $_POST['heading_color'];
                    $hf_bgcolor = $_POST['hf_bgcolor'];
                    $title_color = $_POST['title_color'];
                    $f_bgcolor = $_POST['f_bgcolor'];
                    $font_color = $this->input->post('font_color');

                    if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                        $file = explode(".", $_FILES['pictures']['name']);
                        $file_name = str_replace(" ", "-", $file[0]);
                        $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                        generate_image_compressed($_FILES['pictures']['tmp_name'], 'images/' . $pictures);
                        $aws = new AwsSdk();
                        $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                        $this->customize_appearance_model->theme1_update_with_image($hf_bgcolor, $title_color, $f_bgcolor, $pictures, $sid, $font_color, $heading_color, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url);
                    } else {
                        $this->customize_appearance_model->theme1_update_without_image($hf_bgcolor, $title_color, $f_bgcolor, $sid, $font_color, $heading_color, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url);
                    }
                } else if ($theme_name == 'theme-2') {
                    $body_bgcolor = $this->input->post('body_bgcolor');
                    $hf_bgcolor = $this->input->post('hf_bgcolor');
                    $font_color = $this->input->post('font_color');
                    $heading_color = $this->input->post('heading_color');
                    $title_color = $this->input->post('title_color');

                    if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                        $file = explode(".", $_FILES["pictures"]["name"]);
                        $file_name = str_replace(" ", "-", $file[0]);
                        $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                        generate_image_compressed($_FILES['pictures']['tmp_name'], 'images/' . $pictures);
                        $aws = new AwsSdk();
                        $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                        $this->customize_appearance_model->theme2_update_with_image($body_bgcolor, $hf_bgcolor, $pictures, $sid, $font_color, $heading_color, $title_color, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url);
                    } else {
                        $this->customize_appearance_model->theme2_update_without_image($body_bgcolor, $hf_bgcolor, $sid, $font_color, $heading_color, $title_color, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url);
                    }
                } else if ($theme_name == 'theme-3') {
                    $font_color = $_POST['font_color'];
                    $body_bgcolor = $_POST['body_bgcolor'];
                    $hf_bgcolor = $_POST['hf_bgcolor'];
                    $title_color = $_POST['title_color'];
                    $f_bgcolor = $_POST['f_bgcolor'];
                    $heading_color = $this->input->post('heading_color');

                    if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                        $file = explode(".", $_FILES['pictures']['name']);
                        $file_name = str_replace(" ", "-", $file[0]);
                        $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                        $aws = new AwsSdk();
                        generate_image_compressed($_FILES['pictures']['tmp_name'], 'images/' . $pictures);
                        $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                        $this->customize_appearance_model->theme3_update_with_image($body_bgcolor, $pictures, $sid, $font_color, $hf_bgcolor, $title_color, $heading_color, $f_bgcolor, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url);
                    } else {
                        $this->customize_appearance_model->theme3_update_without_image($body_bgcolor, $sid, $font_color, $hf_bgcolor, $title_color, $heading_color, $f_bgcolor, $theme4_enable_job_fair_homepage, $job_fair_homepage_page_url);
                    }
                }

                $_SESSION['add_job_success'] = 'success';
                $this->session->set_flashdata('message', '<b>Success:</b> Theme updated successfully');
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_image_section_01') {
                $image_name = put_file_on_aws('image_section_01');

                if ($image_name != 'error') {
                    $theme_name = $_POST["theme_name"];
                    $page_name = $_POST["page_name"];
                    $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_01');
                    $update_section_meta = $this->generate_page_section_meta_array($image_name, '', '', '', '', 0, '');
                    $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                    $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_01', $dataToStore);
                }
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_site_configuration') {
                //
                $postData = $this->input->post(null, true);
                $theme_name = $postData["theme_name"];
                $page_name = $postData["page_name"];
                $enableHeaderBG = $postData['enable_header_bg'];
                $enableHeaderOverlay = $postData['enable_header_overlay'];


                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'site_settings', [
                    'enable_header_bg' => $enableHeaderBG ? 1 : 0,
                    'enable_header_overlay' => $enableHeaderOverlay ? 1 : 0,
                ]);
                $this->session->set_flashdata('message', '<b>Success:</b> You have successfully updated the site settings!');
                redirect('customize_appearance/' . $theme_id, 'refresh');
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_config_section_01') {
                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];
                $title = $_POST['title_section_01'];
                $tag_line = $_POST['tag_line_section_01'];
                $show_img_vdo = 'image';
                // Added on: 10-03-2019
                $do_capitalize = $this->input->post('show_capitalize_section_01', TRUE);

                if (isset($_POST['show_img_vdo_section_01'])) {
                    $show_img_vdo = $_POST['show_img_vdo_section_01'];
                }

                $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_01');
                $update_section_meta = $this->generate_page_section_meta_array('', '', $title, $tag_line, '', 0, $show_img_vdo, '', $do_capitalize);

                $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                // Added on: 10-03-2019
                // Overwrite tagline
                if ($tag_line == '') $dataToStore['tag_line'] = NULL;
                if ($title == '') $dataToStore['title'] = '';
                $dataToStore['do_capitalize'] = $do_capitalize == 'on' ? 1 : 0;

                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_01', $dataToStore);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_section_01_video') {
                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];
                $video = $_POST['video_section_01'];
                $youtube_video = explode('v=', $video, 2);
                $youtube_video_id = $youtube_video[1];
                $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_01');
                $update_section_meta = $this->generate_page_section_meta_array('', $youtube_video_id, '', '', '', 0, '');
                $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_01', $dataToStore);
            }


            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_section_01_vimeo_video') {
                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];
                $video = $_POST['vimeo_video_section_01'];
                $vimeo_video = explode('vimeo.com/', $video, 2);
                $vimeo_video_id = $vimeo_video[1];
                $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_01');
                $update_section_meta = $this->generate_page_section_meta_array('', '', '', '', '', 0, '', '', '', $vimeo_video_id);

                $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);

                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_01', $dataToStore);
            }


            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_section_01_uploaded_video') {
                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];

                if (!empty($_FILES) && isset($_FILES['uploaded_video_section_01']) && $_FILES['uploaded_video_section_01']['size'] > 0) {

                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["uploaded_video_section_01"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["uploaded_video_section_01"]["tmp_name"], $target_file)) {
                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["uploaded_video_section_01"]["name"]) . ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('customize_appearance/' . $theme_id, 'refresh');
                    }

                    $video = $file_name;
                }


                $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_01');
                $update_section_meta = $this->generate_page_section_meta_array('', '', '', '', '', 0, '', '', '', '', $video);

                $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);

                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_01', $dataToStore);
            }


            //
            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_config_section_xx') {
                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];
                $title =  $_POST['title'];
                $content = $_POST['content'];
                $video = $_POST['video'];
                $column_type = $_POST['column_type'];
                $show_video_or_image = $_POST['show_video_or_image'];
                $section_id = $_POST['section_id'];
                $status = 1;

                $vimeo_video = $_POST['vimeo_video'];

                if (isset($_POST['status'])) {
                    $status = $_POST['status'];
                }

                $image = '';
                $aws_file_name = upload_file_to_aws('image', $company_id, 'theme_4_section_image', '', AWS_S3_BUCKET_NAME);

                if (!empty($aws_file_name) && $aws_file_name != 'error') {
                    $image = $aws_file_name;
                }

                $video_id = '';
                $url_prams = array();
                parse_str(parse_url($video, PHP_URL_QUERY), $url_prams);

                if (isset($url_prams['v'])) {
                    $video_id = $url_prams['v'];
                }
                //
                $vimeo_video_id = '';
                if (!empty($vimeo_video)) {
                    $vimeo_video = explode('vimeo.com/', $vimeo_video, 2);
                    $vimeo_video_id = $vimeo_video[1];
                }


                if (!empty($_FILES) && isset($_FILES['uploaded_video_section_02']) && $_FILES['uploaded_video_section_02']['size'] > 0) {

                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["uploaded_video_section_02"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["uploaded_video_section_02"]["tmp_name"], $target_file)) {
                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["uploaded_video_section_02"]["name"]) . ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('customize_appearance/' . $theme_id, 'refresh');
                    }

                    $video = $file_name;
                }

                //
                if ($_FILES['uploaded_video_section_02']['size'] == 0) {
                    $video  = $_POST['uploaded_video_section_02_old'];
                }


                $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, $section_id);
                $update_section_meta = $this->generate_page_section_meta_array($image, $video_id, $title, '', $content, $status, $show_video_or_image, $column_type, '', $vimeo_video_id, $video);
                //$data_to_store = merge_arrays_override_key_values($current_section_meta, $update_section_meta);

                $data_to_store = array();
                foreach ($current_section_meta as $key => $value) {
                    $data_to_store[$key] = $value;
                }

                foreach ($update_section_meta as $key => $value) {
                    if (!in_array($key, ['image', 'video'])) {
                        $data_to_store[$key] = $update_section_meta[$key];
                        // if (array_key_exists($key, $data_to_store)) {
                        // }
                    } else {
                        if (isset($update_section_meta[$key]) && !empty($update_section_meta[$key])) {
                            $data_to_store[$key] = $update_section_meta[$key];
                        }
                    }
                }
                //
                $data_to_store['do_show_image'] = isset($_POST['do_show_image']) ? 'on' : 'off';

                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, $section_id, $data_to_store);
                redirect('customize_appearance/' . $theme_id, 'refresh');
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_image_section_02') {
                $image_name = put_file_on_aws('image_section_02');

                if ($image_name != 'error') {
                    $theme_name = $_POST["theme_name"];
                    $page_name = $_POST["page_name"];
                    $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_02');
                    $update_section_meta = $this->generate_page_section_meta_array($image_name, '', '', '', '', 0, '');
                    $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                    $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_02', $dataToStore);
                }
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_config_section_03') {
                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];
                $title = $_POST['title_section_03'];
                $content = $_POST['content_section_03'];
                $status = 1;

                if (isset($_POST['status_section_03'])) {
                    $status = $_POST['status_section_03'];
                }

                $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_03');
                //$update_section_me = $this->generate_page_section_meta_array($image, $video, $title, $tag_line, $content, $status, $show_video_or_image)
                $update_section_meta = $this->generate_page_section_meta_array('', '', $title, '', $content, $status, 'image');
                $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_03', $dataToStore);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_image_section_03') {
                $image_name = put_file_on_aws('image_section_03');

                if ($image_name != 'error') {
                    $theme_name = $_POST["theme_name"];
                    $page_name = $_POST["page_name"];
                    $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_03');
                    $update_section_meta = $this->generate_page_section_meta_array($image_name, '', '', '', '', 0, '');
                    $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                    $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_03', $dataToStore);
                }
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_section_04_video') {

                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];
                $status = 1;

                $videoSource = $_POST['video_source_section_04'];
                $video = $_POST['yt_vm_video_url_section_04'];

                if (isset($_POST['status_section_04'])) {
                    $status = $_POST['status_section_04'];
                }

                //
                $youtube_video_id = ' ';
                if ($videoSource == 'youtube') {
                    $youtube_video = explode('v=', $video, 2);
                    $youtube_video_id = $youtube_video[1];
                }

                //
                $vimeo_video_id = ' ';
                if ($videoSource == 'vimeo') {
                    $vimeo_video = explode('vimeo.com/', $video, 2);
                    $vimeo_video_id = $vimeo_video[1];
                }

                //
                $upload_video_id = ' ';
                if ($videoSource == 'upload') {
                    if (!empty($_FILES) && isset($_FILES['video_upload_section_04']) && $_FILES['video_upload_section_04']['size'] > 0) {

                        $random = generateRandomString(5);
                        $company_id = $data['session']['company_detail']['sid'];
                        $target_file_name = basename($_FILES["video_upload_section_04"]["name"]);
                        $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/";
                        $target_file = $target_dir . $file_name;
                        $filename = $target_dir . $company_id;

                        if (!file_exists($filename)) {
                            mkdir($filename);
                        }

                        if (move_uploaded_file($_FILES["video_upload_section_04"]["tmp_name"], $target_file)) {
                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload_section_04"]["name"]) . ' has been uploaded.');
                        } else {
                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect('customize_appearance/' . $theme_id, 'refresh');
                        }
                        $upload_video_id = $file_name;
                    }
                }

                $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_04');
                $update_section_meta = $this->generate_page_section_meta_array('', $youtube_video_id, '', '', '', $status, '', '', '', $vimeo_video_id, $upload_video_id);
                $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_04', $dataToStore);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_config_section_05') {
                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];
                $title = $_POST['title_section_05'];
                $status = 1;

                if (isset($_POST['status_section_05'])) {
                    $status = $_POST['status_section_05'];
                }

                $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_05');
                //$update_section_me = $this->generate_page_section_meta_array($image, $video, $title, $tag_line, $content, $status, $show_video_or_image)
                $update_section_meta = $this->generate_page_section_meta_array('', '', $title, '', '', $status, 'image');
                $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_05', $dataToStore);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_config_section_06') {
                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];
                $title = $_POST['title_section_06'];
                $tag_line = $_POST['tag_line_section_06'];
                $status = 1;

                if (isset($_POST['status_section_06'])) {
                    $status = $_POST['status_section_06'];
                }

                $current_section_meta = get_page_section_meta($company_id, $theme_name, $page_name, 'section_06');
                //$update_section_me = $this->generate_page_section_meta_array($image, $video, $title, $tag_line, $content, $status, $show_video_or_image)
                $update_section_meta = $this->generate_page_section_meta_array('', '', $title, $tag_line, '', $status, 'image');
                $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'section_06', $dataToStore);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_partner') {
                $pictures = put_file_on_aws('file_partner_logo');

                if ($pictures != 'error') {
                    $theme_name = $_POST["theme_name"];
                    $page_name = $_POST["page_name"];
                    $partners = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'partners');
                    $partner = array(
                        'txt_partner_name' => $_POST['txt_partner_name'],
                        'txt_partner_url' => $_POST['txt_partner_url'],
                        'file_partner_logo' => $pictures
                    );

                    $partners[] = $partner;
                    $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, 'home', 'partners', $partners);
                }
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_testimonial') {
                $pictures = '';

                if (isset($_FILES['file_image']) && $_FILES['file_image']['name'] != '') {
                    $file = explode(".", $_FILES["file_image"]["name"]);
                    $file_name = str_replace(" ", "-", $file[0]);
                    $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                    generate_image_compressed($_FILES['file_image']['tmp_name'], 'images/' . $pictures);
                    $aws = new AwsSdk();
                    //$aws->putToBucket($pictures, $_FILES["pictures"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                }

                $author = $_POST['txt_author_name'];
                $short_description = $_POST['txt_short_description'];
                $full_description = $_POST['txt_full_description'];
                $youtube_video_id = '';

                if (isset($_POST['txt_youtube_video'])) {
                    if (!empty($_POST['txt_youtube_video'])) {
                        $youtube_video = explode('v=', $_POST['txt_youtube_video'], 2);
                        $youtube_video_id = $youtube_video[1];
                    }
                }

                $record_id = null;

                if (isset($_POST['sid'])) {
                    $record_id = $_POST['sid'];
                }

                $imageName = '';

                if ($_POST['image'] == '') {
                    $imageName = $pictures;
                } else {
                    $imageName = $_POST['image'];
                }

                $this->testimonials_model->Save($record_id, $author, '', $short_description, 'image', 1, $company_id, $imageName, $full_description, $youtube_video_id);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_page_data') {
                $sid = $_POST['sid'];
                $company_id = $company_id;
                $theme_name = $_POST['theme_name'];
                $page_name = $_POST['page_name'];
                $page_title = $_POST['page_title'];
                $page_content = $_POST['page_content'];
                $page_status = 0;
                $job_opportunities = 0;

                if ($job_fair_configuration == 0) {
                    $job_fair = 2;
                } else {
                    $job_fair = 0;
                }

                $job_opportunities_text = 'View Job Opportunities';
                //Generate Page Name From Title Field - Start
                //$page_name = strtolower($page_title);
                $page_name = strtolower(str_replace('{{company_name}}', '', $page_title));
                $page_name = trim($page_name);
                $page_name = str_replace(' ', '-', $page_name); // Replaces all spaces with hyphens.
                $page_name = str_replace('&', '-and-', $page_name);
                $page_name = preg_replace('/[^A-Za-z0-9\-]/', '', $page_name); // Removes special chars.
                $page_name = preg_replace('/-+/', '-', $page_name); // Replaces multiple hyphens with single one.
                $page_name = preg_replace('/_+/', '-', $page_name); // Replaces multiple unserscores with single one.

                //Generate Page Name From Title Field - End

                if (isset($_POST['job_opportunities'])) {
                    if ($_POST['job_opportunities'] == 'on') {
                        $job_opportunities = 1;
                    }
                }

                if (isset($_POST['page_status'])) {
                    if ($_POST['page_status'] == 'on') {
                        $page_status = 1;
                    }
                }

                if (isset($_POST['job_fair']) && $job_fair_configuration == 1) {
                    if ($_POST['job_fair'] == 'on') {
                        $job_fair = 1;
                    }
                }

                if (isset($_POST['job_opportunities_text'])) {
                    $job_opportunities_text = $_POST['job_opportunities_text'];
                }

                if (isset($_POST['sid'])) {
                    $record_id = $_POST['sid'];
                }

                $job_fair_page_url = !empty($_POST['job_fair_page_url']) ? implode(',', $_POST['job_fair_page_url']) : '';
                $this->themes_pages_model->Save($sid, $company_id, $theme_name, $page_name, $page_title, $page_content, $page_status, $job_opportunities, $job_opportunities_text, $job_fair, $job_fair_page_url);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_page_banner') {
                $sid = $_POST['sid'];
                $pictures = put_file_on_aws('file_page_banner');

                if ($pictures != 'error') {
                    $this->themes_pages_model->UpdateBannerImage($sid, $pictures);
                }

                $pageBannerStatus = 0;

                if (isset($_POST['page_banner_status'])) {
                    if ($_POST['page_banner_status'] == 'on') {
                        $pageBannerStatus = 1;
                    }
                }

                $this->themes_pages_model->MarkBannerAsActiveInActive($sid, $pageBannerStatus);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_page_youtube_video') {


                $videoSource = $_POST['video_source_section_04'];
                $video = $_POST['yt_vm_video_url_section_04'];

                //
                $youtube_video_id = ' ';
                if ($videoSource == 'youtube') {
                    $youtube_video = explode('v=', $video, 2);
                    $youtube_video_id = $youtube_video[1];
                }

                //
                $vimeo_video_id = ' ';
                if ($videoSource == 'vimeo') {
                    $vimeo_video = explode('vimeo.com/', $video, 2);
                    $youtube_video_id = $vimeo_video[1];
                }

                //
                $upload_video_id = ' ';
                if ($videoSource == 'upload') {
                    if (!empty($_FILES) && isset($_FILES['video_upload_section_04']) && $_FILES['video_upload_section_04']['size'] > 0) {

                        $random = generateRandomString(5);
                        $company_id = $data['session']['company_detail']['sid'];
                        $target_file_name = basename($_FILES["video_upload_section_04"]["name"]);
                        $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/";
                        $target_file = $target_dir . $file_name;
                        $filename = $target_dir . $company_id;

                        if (!file_exists($filename)) {
                            mkdir($filename);
                        }

                        if (move_uploaded_file($_FILES["video_upload_section_04"]["tmp_name"], $target_file)) {
                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload_section_04"]["name"]) . ' has been uploaded.');
                        } else {
                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect('customize_appearance/' . $theme_id, 'refresh');
                        }
                        $youtube_video_id = $file_name;
                    }
                }


                $video_location = $_POST['video_location'];
                $sid = $_POST['sid'];
                $this->themes_pages_model->UpdateYoutubeVideo($sid, $youtube_video_id, $video_location, $videoSource);
                $youtube_video_status = 0;

                if (isset($_POST['page_youtube_video_status'])) {
                    if ($_POST['page_youtube_video_status'] == 'on') {
                        $youtube_video_status = 1;
                    }
                }

                $this->themes_pages_model->MarkYoutubeVideoAsActiveInActive($sid, $youtube_video_status);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_jobs_banner') {
                if (isset($_FILES['jobs_page_banner']) && $_FILES['jobs_page_banner']['name'] != '') {
                    $file = explode(".", $_FILES["jobs_page_banner"]["name"]);
                    $file_name = str_replace(" ", "-", $file[0]);
                    $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                    generate_image_compressed($_FILES['jobs_page_banner']['tmp_name'], 'images/' . $pictures);
                    $aws = new AwsSdk();
                    //$aws->putToBucket($pictures, $_FILES["pictures"]["tmp_name"], AWS_S3_BUCKET_NAME);
                    $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                    $theme_name = $_POST["theme_name"];
                    $page_name = $_POST["page_name"];
                    $jobsPageBannerImage = array(
                        'jobs_page_banner' => $pictures
                    );

                    $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'jobs_page_banner', $jobsPageBannerImage);
                }
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_jobs_detail_banner') {
                $jobsPageBannerImage = array();
                $theme_name = $_POST["theme_name"];
                $page_name = $_POST["page_name"];
                if (isset($_FILES['jobs_detail_page_banner']) && $_FILES['jobs_detail_page_banner']['name'] != '') {
                    $file = explode(".", $_FILES["jobs_detail_page_banner"]["name"]);
                    $file_name = str_replace(" ", "-", $file[0]);
                    $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                    generate_image_compressed($_FILES['jobs_detail_page_banner']['tmp_name'], 'images/' . $pictures);
                    $aws = new AwsSdk();
                    $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                    $jobsPageBannerImage['jobs_detail_page_banner'] = $pictures;
                }
                if (isset($_POST['job_detail_banner_type'])) {
                    if (empty($jobsPageBannerImage) && isset($jobs_detail_page_banner) && !empty($jobs_detail_page_banner['jobs_detail_page_banner'])) {
                        $jobsPageBannerImage['jobs_detail_page_banner'] = $jobs_detail_page_banner['jobs_detail_page_banner'];
                    }
                    $jobsPageBannerImage['banner_type'] = $_POST['job_detail_banner_type'];
                    $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'jobs_detail_page_banner', $jobsPageBannerImage);
                    $this->session->set_flashdata('message', '<b>Success:</b> Job Detail Banner Updated Successfully');
                }
                redirect(current_url(), 'refresh');
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_home_page_youtube_video') {
                $youtube_video_id = '';
                $theme_name = $_POST['theme_name'];
                $page_name = $_POST['page_name'];

                if (isset($_POST['home_page_youtube_video'])) {
                    if (!empty($_POST['home_page_youtube_video'])) {
                        $youtube_video = explode('v=', $_POST['home_page_youtube_video'], 2);
                        $youtube_video_id = $youtube_video[1];
                    }
                }

                $youtube_video_status = 0;

                if (isset($_POST['home_page_youtube_video_status'])) {
                    if ($_POST['home_page_youtube_video_status'] == 'on') {
                        $youtube_video_status = 1;
                    }
                }

                $dataToStore = array(
                    'video' => $youtube_video_id,
                    'status' => $youtube_video_status
                );

                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'home_page_youtube_video', $dataToStore);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_footer_content') {
                $page_name = $_POST['page_name'];
                $theme_name = $_POST['theme_name'];
                $footer_title = $_POST['footer_title'];
                $footer_content = $_POST['footer_content'];
                $dataToStore = array(
                    'title' => $footer_title,
                    'content' => $footer_content
                );

                $this->customize_appearance_model->fSaveThemeMetaData($company_id, $theme_name, $page_name, 'footer_content', $dataToStore);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_font_configurations') {
                $font_customization = $_POST['font_customization'];
                $google_fonts = $_POST['google_fonts'];
                $web_fonts = $_POST['web_fonts'];
                $theme4_btn_bgcolor = $_POST['theme4_btn_bgcolor'];
                $theme4_btn_txtcolor = $_POST['theme4_btn_txtcolor'];
                $theme4_heading_color = $_POST['theme4_heading_color'];
                $theme4_heading_color_span = $_POST['theme4_heading_color_span'];
                $theme4_search_container_bgcolor = $_POST['theme4_search_container_bgcolor'];
                $theme4_search_btn_bgcolor = $_POST['theme4_search_btn_bgcolor'];
                $theme4_search_btn_color = $_POST['theme4_search_btn_color'];
                $theme4_banner_text_l1_color = $_POST['theme4_banner_text_l1_color'];
                $theme4_banner_text_l2_color = $_POST['theme4_banner_text_l2_color'];
                $theme4_job_title_color = $_POST['theme4_job_title_color'];

                $dataToStore = array(
                    'font_customization' => $font_customization,
                    'google_fonts_sid' => $google_fonts,
                    'web_fonts_sid' => $web_fonts,
                    'theme4_btn_bgcolor' => $theme4_btn_bgcolor,
                    'theme4_btn_txtcolor' => $theme4_btn_txtcolor,
                    'theme4_heading_color' => $theme4_heading_color,
                    'theme4_heading_color_span' => $theme4_heading_color_span,
                    'theme4_search_container_bgcolor' => $theme4_search_container_bgcolor,
                    'theme4_search_btn_bgcolor' => $theme4_search_btn_bgcolor,
                    'theme4_search_btn_color' => $theme4_search_btn_color,
                    'theme4_banner_text_l1_color' => $theme4_banner_text_l1_color,
                    'theme4_banner_text_l2_color' => $theme4_banner_text_l2_color,
                    'theme4_job_title_color' => $theme4_job_title_color,
                );

                $this->customize_appearance_model->update_font_configurations($company_id, $dataToStore);
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'Save Section') {

                //  _e($_POST, true, true);

                $box_sid = $this->input->post('box-sid');
                $theme_name = $this->input->post("theme_name");
                $page_name = $this->input->post("page_name");
                $title = $this->input->post('title');
                $content = $this->input->post('content');
                $video = $this->input->post('video');
                $column_type = $this->input->post('column_type');
                $show_video_or_image = $this->input->post('show_video_or_image');
                $section_id = $this->input->post('section_id');
                $status = $this->input->post('status');
                $status = !empty($status) && !is_null($status) ? $status : 0;
                $image = '';


                $vimeo_video = $_POST['vimeo_video'];

                $aws_file_name = upload_file_to_aws('image', $company_id, 'theme_4_section_image', '', AWS_S3_BUCKET_NAME);

                if (!empty($aws_file_name) && $aws_file_name != 'error') {
                    $image = $aws_file_name;
                }

                //Video
                $video_id = '';
                $url_prams = array();
                parse_str(parse_url($video, PHP_URL_QUERY), $url_prams);

                if (isset($url_prams['v'])) {
                    $video_id = $url_prams['v'];
                }

                //
                $vimeo_video_id = '';
                if (!empty($vimeo_video)) {
                    $vimeo_video = explode('vimeo.com/', $vimeo_video, 2);
                    $vimeo_video_id = $vimeo_video[1];
                }

                $data_to_store = array();
                $data_to_store['title'] = $title;
                $data_to_store['content'] = $content;
                $data_to_store['show_video_or_image'] = $show_video_or_image;

                if (!empty($image)) {
                    $data_to_store['image'] = $image;
                }
                if (!empty($video_id)) {
                    $data_to_store['video'] = $video_id;
                }


                if (!empty($vimeo_video_id)) {
                    $data_to_store['video'] = $vimeo_video_id;
                }

                if (!empty($_FILES) && isset($_FILES['uploaded_video_section_02']) && $_FILES['uploaded_video_section_02']['size'] > 0) {

                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["uploaded_video_section_02"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["uploaded_video_section_02"]["tmp_name"], $target_file)) {
                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["uploaded_video_section_02"]["name"]) . ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('customize_appearance/' . $theme_id, 'refresh');
                    }

                    $video = $file_name;
                    $data_to_store['video'] = $video;
                }

                //

                $data_to_store['column_type'] = $column_type;
                $data_to_store['status'] = $status;
                $data_to_store['company_sid'] = $company_id;
                $data_to_store['created_date'] = date('Y-m-d H:i:s');

                $this->customize_appearance_model->update_additional_sections($box_sid, $data_to_store);
                redirect('customize_appearance/' . $theme_id, 'refresh');
            }

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'save_home_job_opportunity') {
                $theme4_enable_home_job_opportunity = $_POST['job_button_customization'];
                $theme4_home_job_opportunity_text = $_POST['job_opportunities_text'];
                $theme4_enable_job_fair_homepage = 0;
                $job_fair_homepage_page_url = !empty($_POST['job_fair_homepage_page_url']) ? implode(',', $_POST['job_fair_homepage_page_url']) : '';

                if (isset($_POST['job_fair'])) {
                    $theme4_enable_job_fair_homepage = 1;
                }

                $dataToStore = array(
                    'theme4_enable_home_job_opportunity' => $theme4_enable_home_job_opportunity,
                    'theme4_home_job_opportunity_text' => $theme4_home_job_opportunity_text,
                    'theme4_enable_job_fair_homepage' => $theme4_enable_job_fair_homepage,
                    'job_fair_homepage_page_url' => $job_fair_homepage_page_url
                );

                $this->customize_appearance_model->update_font_configurations($company_id, $dataToStore);
            }

            $theme_info = $this->customize_appearance_model->get_theme($theme_id);
            $data['additional_boxes'] = $this->customize_appearance_model->get_additional_sections($company_id);

            if (empty($theme_info)) { // theme not found!
                $this->session->set_flashdata('message', '<b>Error:</b> Theme not found!');
                redirect('appearance', 'refresh');
            } else { // theme data found
                if ($company_id != $theme_info['user_sid']) { // verify theme belongs to same company
                    $this->session->set_flashdata('message', '<b>Error:</b> Theme not found!');
                    redirect('appearance', 'refresh');
                }
            }

            $data['theme'] = $theme_info;
            $is_paid = $data['theme']['is_paid'];
            $is_purchased = $theme_info['purchased'];

            if ($is_paid) { // only fetch the details of if it is paid theme
                if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'update_jobs_page_title') { //Save Jobs Page Title
                    $company_sid = $company_id; //$_POST['company_sid'];
                    $jobs_page_title = $_POST['jobs_page_title'];
                    // $jobs_page_title = strtolower(str_replace('{{company_name}}', '', $jobs_page_title));
                    $jobs_page_title = str_replace('{{company_name}}', '', $jobs_page_title);
                    $jobs_page_title = trim($jobs_page_title);
                    $jobs_page_title = str_replace('  ', ' ', $jobs_page_title);
                    //  $jobs_page_title = str_replace(' ', '-', $jobs_page_title);
                    $jobs_page_title = str_replace('\`', '', $jobs_page_title);
                    $jobs_page_title = str_replace('"', '', $jobs_page_title);
                    $jobs_page_title = str_replace('\'', '', $jobs_page_title);
                    //$jobs_page_title = str_replace('-', '_', $jobs_page_title);
                    $jobs_page_title = str_replace('%', '', $jobs_page_title);
                    $jobs_page_title = str_replace('&', '-and-', $jobs_page_title);
                    $jobs_page_title = str_replace('%', '', $jobs_page_title);
                    $jobs_page_title = str_replace('^', '', $jobs_page_title);
                    $jobs_page_title = str_replace('$', '', $jobs_page_title);
                    $jobs_page_title = str_replace('*', '', $jobs_page_title);
                    $jobs_page_title = str_replace('__', '-', $jobs_page_title);
                    $theme_name = $_POST['theme_name'];
                    $page_name = 'jobs';
                    $this->customize_appearance_model->fSaveThemeMetaData($company_sid, $theme_name, $page_name, 'jobs_page_title', $jobs_page_title);
                    $theme4_enable_job_fair_careerpage = 0;
                    $job_fair_career_page_url = !empty($_POST['job_fair_career_page_url']) ? implode(',', $_POST['job_fair_career_page_url']) : '';

                    if (isset($_POST['job_fair'])) {
                        $theme4_enable_job_fair_careerpage = 1;
                    }

                    $dataToStore = array(
                        'theme4_enable_job_fair_careerpage' => $theme4_enable_job_fair_careerpage,
                        'job_fair_career_page_url' => $job_fair_career_page_url
                    );

                    $this->customize_appearance_model->update_font_configurations($company_sid, $dataToStore);
                    $theme_info = $this->customize_appearance_model->get_theme($theme_id);
                    $data['theme'] = $theme_info;
                }

                $pages = $this->themes_pages_model->GetAllCompanySpecific($company_id);
                $testimonials = $this->testimonials_model->GetAllCompanySpecific($company_id);
                $site_settings = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'site_settings', 'site_settings');
                $section_01_meta = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'section_01');
                $section_02_meta = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'section_02');
                $section_03_meta = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'section_03');
                $section_04_meta = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'section_04');
                $section_05_meta = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'section_05');
                $section_06_meta = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'section_06');
                //$advanced_section_01_meta = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'advanced_section_01');
                // replacing {{company_name}} with company name from session array for 6 sections
                $section_01_meta['title'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_01_meta['title']);
                $section_01_meta['content'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_01_meta['content']);
                $section_02_meta['title'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_02_meta['title']);
                $section_02_meta['content'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_02_meta['content']);
                $section_03_meta['title'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_03_meta['title']);
                $section_03_meta['content'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_03_meta['content']);
                $section_04_meta['title'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_04_meta['title']);
                $section_04_meta['content'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_04_meta['content']);
                $section_05_meta['title'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_05_meta['title']);
                $section_05_meta['content'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_05_meta['content']);
                $section_06_meta['title'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_06_meta['title']);
                $section_06_meta['content'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $section_06_meta['content']);
                $partners = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'partners');
                $jobs_page_banner = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'jobs', 'jobs_page_banner');
                $jobs_page_title = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'jobs', 'jobs_page_title');
                //                $advanced_section_01_meta['title'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $advanced_section_01_meta['title']);
                //                $advanced_section_01_meta['content'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $advanced_section_01_meta['content']);

                if (is_array($jobs_page_title)) {
                    $jobs_page_title = '';
                }

                $footer_content = $this->customize_appearance_model->fGetThemeMetaData($company_id, 'theme-4', 'home', 'footer_content');
                $footer_content['title'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $footer_content['title']);
                $footer_content['content'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $footer_content['content']);

                //Handle column_type for theme 4 home page section 2 and 3
                if (!isset($section_02_meta['column_type'])) {
                    $section_02_meta['column_type'] = 'right_left';
                }

                if (!isset($section_03_meta['column_type'])) {
                    $section_03_meta['column_type'] = 'left_right';
                }
                //Pass data to view
                $data['site_settings'] = $site_settings;
                $data['section_01_meta'] = $section_01_meta;
                $data['section_02_meta'] = $section_02_meta;
                $data['section_03_meta'] = $section_03_meta;
                $data['section_04_meta'] = $section_04_meta;
                $data['section_05_meta'] = $section_05_meta;
                $data['section_06_meta'] = $section_06_meta;
                $data['partners'] = $partners;
                $data['testimonials'] = $testimonials;
                $data['advanced_section_01_meta'] = ''; //$advanced_section_01_meta;
                $counter = 0;

                foreach ($data['testimonials'] as $testimonial) {
                    $data['testimonials'][$counter]['author_name'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $data['testimonials'][$counter]['author_name']);
                    $data['testimonials'][$counter]['short_description'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $data['testimonials'][$counter]['short_description']);
                    $data['testimonials'][$counter]['full_description'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $data['testimonials'][$counter]['full_description']);
                    $counter++;
                }


                $data['pages'] = $pages;
                $counter = 0;

                foreach ($data['pages'] as $page) {
                    $data['pages'][$counter]['page_title'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $data['pages'][$counter]['page_title']);
                    $data['pages'][$counter]['page_content'] = str_replace("{{company_name}}", $data['session']['company_detail']['CompanyName'], $data['pages'][$counter]['page_content']);
                    $counter++;
                }

                $data['jobs_page_banner'] = $jobs_page_banner;
                $data['jobs_detail_page_banner'] = $jobs_detail_page_banner;
                //                echo '<pre>';
                //                print_r($jobs_detail_page_banner);

                $data['jobs_page_title'] = $jobs_page_title;
                $data['footer_content'] = $footer_content;
                $google_fonts = $this->customize_appearance_model->get_google_fonts(); // get list of all the google font family
                $data['google_fonts'] = $google_fonts;
                $web_fonts = $this->customize_appearance_model->get_web_fonts();
                $data['web_fonts'] = $web_fonts;
            }

            $data['company_sid'] = $company_id;
            $customize_career_site = $this->company_model->get_customize_career_site_data($company_id);
            $data['inactive_pages'] = $customize_career_site['inactive_pages'];
            $data['show_checkbox'] = $customize_career_site['status'];
            $data['theme_name'] = $theme_info['theme_name'];
            $this->load->view('main/header', $data);

            if (intval($is_paid) == 0) { //Check if Theme is Paid; for Customization Panel.
                $this->load->view('manage_employer/customize_appearance');
            } else {
                if ($is_purchased == 1) {
                    $this->load->view('appearance/customize_appearance/customize_appearance_paid');
                } else {
                    redirect('appearance', 'refresh');
                }
            }

            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function theme_status()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_id = $data["session"]["company_detail"]["sid"];
            $theme_id = $this->input->post("id");
            $this->appearance_model->active_theme($theme_id, $employer_id);
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function restore_default()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_id = $data["session"]["company_detail"]["sid"];

            if (isset($_POST["action"]) && $_POST["action"] == "restore") {
                $theme_name = $this->input->post('theme_name');
                $sid = $this->input->post('sid');

                if ($theme_name == 'theme-1') {
                    $hf_bgcolor = '#b3c211';
                    $f_bgcolor = '#0e0e0e';
                    $title_color = '#b3c211';
                    $font_color = '#0099ff';
                    $pictures = 'theme_1-EaOtK.jpg'; // theme_1_default_image-RPBc4.jpg
                    $heading_color = '#000000';
                    $this->customize_appearance_model->theme1_update_with_image($hf_bgcolor, $title_color, $f_bgcolor, $pictures, $sid, $font_color, $heading_color);
                } else if ($theme_name == 'theme-2') {
                    $body_bgcolor = '#00cccc';
                    $hf_bgcolor = '#00b6b6';
                    $font_color = '#000000';
                    $heading_color = '#b3c211';
                    $title_color = '#0099ff';
                    $pictures = 'theme_2-X6glS.jpg'; // theme-2-banner-5LHO6.jpg
                    $this->customize_appearance_model->theme2_update_with_image($body_bgcolor, $hf_bgcolor, $pictures, $sid, $font_color, $heading_color, $title_color);
                } else if ($theme_name == 'theme-3') {
                    $body_bgcolor = '#b39ddb';
                    $font_color = '#3f51b5 ';
                    $hf_bgcolor = '#b3c211';
                    $pictures = 'theme_3-A8KRT.jpg'; // theme3_banner-sUVHc.jpg
                    $title_color = '#0099ff';
                    $heading_color = '#a087ce';
                    $f_bgcolor = '#ffffff';
                    $this->customize_appearance_model->theme3_update_with_image($body_bgcolor, $pictures, $sid, $font_color, $hf_bgcolor, $title_color, $heading_color, $f_bgcolor);
                }

                $_SESSION['theme_success'] = "success";
                $this->session->set_flashdata('message', '<b>Success: </b> Theme restored to default settings.');
                exit();
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    // Theme Meta Data
    public function ajax_responder()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $data['title'] = "Themes";
            $employer_id = $data["session"]["company_detail"]["sid"];

            if (array_key_exists('perform_action', $_POST)) {
                $perform_action = strtoupper($_POST['perform_action']);

                switch ($perform_action) {
                    case 'SAVE-ABOUT-US':
                        $theme_name = $_POST["theme_name"];
                        $page_name = $_POST["page_name"];
                        $meta_key = "about-us";
                        $meta_value = array(
                            'txt_about_us_heading' => $_POST['txt_about_us_heading'],
                            'txt_about_us_text' => $_POST['txt_about_us_text'],
                            'chk_enabled' => $_POST['chk_enabled']
                        );

                        try {
                            $this->customize_appearance_model->fSaveThemeMetaData($employer_id, $theme_name, $page_name, $meta_key, $meta_value);
                            echo 'success';
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                        break;
                    case 'DELETE_PARTNER':
                        $theme_name = $_POST["theme_name"];
                        $page_name = $_POST["page_name"];
                        $partner_array_index = intval($_POST['partner_array_index']);
                        $partners = $this->customize_appearance_model->fGetThemeMetaData($employer_id, $theme_name, $page_name, 'partners');
                        unset($partners[$partner_array_index]);
                        $partners = array_values($partners);

                        try {
                            $this->customize_appearance_model->fSaveThemeMetaData($employer_id, $theme_name, $page_name, 'partners', $partners);
                            echo 'success';
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                        break;
                    case 'DELETE_TESTIMONIAL':
                        $sid = $_POST['sid'];
                        $this->testimonials_model->Delete($sid);
                        echo 'success';
                        break;
                    case 'SWITCH_TESTIMONIAL_STATUS':
                        $sid = intval($_POST['sid']);
                        $newStatus = intval($_POST['new_status']);
                        $this->testimonials_model->MarkAsActiveInActive($sid, $newStatus);
                        echo 'success';
                        break;
                    case 'RESTORE_DEFAULT_BANNER':
                        switch (strtoupper($_POST['page'])) {
                            case 'HOME':
                                $banner = $_POST['banner'];
                                $def_value = $_POST['def_image'];
                                $page_name = $_POST['page'];
                                $theme_name = $_POST['theme'];

                                if (strtoupper($banner) == 'MAIN') {
                                    $mainBannerImage = array(
                                        'main_banner_image' => $def_value
                                    );

                                    $this->customize_appearance_model->fSaveThemeMetaData($employer_id, $theme_name, $page_name, 'main_banner_image', $mainBannerImage);
                                    echo 'success';
                                } else if (strtoupper($banner) == 'ABOUT_US') {
                                    $aboutusBannerImage = array(
                                        'about_us_banner_image' => $def_value
                                    );

                                    $this->customize_appearance_model->fSaveThemeMetaData($employer_id, $theme_name, $page_name, 'about_us_banner_image', $aboutusBannerImage);
                                    echo 'success';
                                }
                                break;
                            case 'JOBS':
                                $banner = $_POST['banner'];
                                $def_value = $_POST['def_image'];
                                $page_name = $_POST['page'];
                                $theme_name = $_POST['theme'];
                                $jobsPageBannerImage = array(
                                    'jobs_page_banner' => $def_value
                                );

                                $this->customize_appearance_model->fSaveThemeMetaData($employer_id, $theme_name, $page_name, 'jobs_page_banner', $jobsPageBannerImage);
                                echo 'success';
                                break;
                            default:
                                $banner = $_POST['banner'];
                                $def_value = $_POST['def_image'];
                                $page_name = $_POST['page'];
                                $theme_name = $_POST['theme'];
                                $sid = $_POST['pageid'];
                                $this->themes_pages_model->UpdateBannerImage($sid, $def_value);
                                echo 'success';
                                break;
                        }
                        break;
                    case 'RESTORE_DEFAULT_IMAGE_FOR_SECTION':
                        $def_value = $_POST['def_image'];
                        $page_name = $_POST['page'];
                        $theme_name = $_POST['theme'];
                        $section = $_POST['section'];
                        $current_section_meta = get_page_section_meta($employer_id, $theme_name, $page_name, 'section_0' . intval($section));
                        //$update_section_me = $this->generate_page_section_meta_array($image, $video, $title, $tag_line, $content, $status, $show_video_or_image)
                        $update_section_meta = $this->generate_page_section_meta_array($def_value, '', '', '', '', '', 'image');
                        $dataToStore = merge_arrays_override_key_values($current_section_meta, $update_section_meta);
                        $this->customize_appearance_model->fSaveThemeMetaData($employer_id, $theme_name, $page_name, 'section_0' . intval($section), $dataToStore);
                        echo 'success';
                        break;
                    default:
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function enterprise_theme_email()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data["session"]["company_detail"]["sid"];
            $company_name = $data["session"]["company_detail"]["CompanyName"];
            $emailTemplateData = get_email_template(ENTERPRISE_THEME_ACTIVATION_TO_ADMIN);
            $emailTemplateBody = $emailTemplateData['text'];
            $emailTemplateBody = str_replace('{{company_id}}', $company_id, $emailTemplateBody);
            $emailTemplateBody = str_replace('{{company_name}}', $company_name, $emailTemplateBody);
            $from = $emailTemplateData['from_email'];
            //$to = TO_EMAIL_STEVEN;
            $subject = $emailTemplateData['subject'];
            $from_name = $emailTemplateData['from_name'];
            $body = EMAIL_HEADER
                . $emailTemplateBody
                . EMAIL_FOOTER;

            //Send Emails Through System Notifications Email - Start
            $system_notification_emails = get_system_notification_emails('company_account_expiration_emails');

            if (!empty($system_notification_emails)) {
                foreach ($system_notification_emails as $system_notification_email) {
                    sendMail($from, $system_notification_email['email'], $subject, $body, STORE_NAME);

                    $emailData = array(
                        'date' => date('Y-m-d H:i:s'),
                        'subject' => $subject,
                        'email' => $system_notification_email['email'],
                        'message' => $body,
                    );

                    save_email_log_common($emailData);
                }
            }

            //Send Emails Through System Notifications Email - End
            //sendMail($from, $to, $subject, $body, $from_name);
            //saving emial to email log
            $this->session->set_flashdata('message ', '<b>Success:</b> Mail sent successfully.');
        }
    }

    public function career_logo_management()
    {
        if ($this->session->userdata('logged_in')) {
            $data['title'] = "Career Page Logo Management";
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'career_logo_management');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $company_logo_data = $this->appearance_model->get_career_page_logo_record($company_sid);
                $data['company_logo_data'] = $company_logo_data;
                $data['company_sid'] = $company_sid;
                $this->load->view('main/header', $data);
                $this->load->view('appearance/career_logo_management');
                $this->load->view('main/footer');
            } else {
                $company_sid = $this->input->post('company_sid');
                $aspect_ratio = $this->input->post('aspect_ratio');
                $logo_status = $this->input->post('logo_status');
                $logo_location = $this->input->post('logo_location');
                $logo_image = upload_file_to_aws('logo_image', $company_sid, 'career_page_logo');
                $company_logo_data = $this->appearance_model->get_career_page_logo_record($company_sid);
                $new_logo_data = array();
                $new_logo_data['company_sid'] = $company_sid;
                $new_logo_data['logo_aspect_ratio'] = $aspect_ratio;
                $new_logo_data['logo_status'] = $logo_status;
                $new_logo_data['logo_location'] = $logo_location;

                if ($logo_image != 'error') {
                    $new_logo_data['logo_image'] = $logo_image;
                }

                if (empty($company_logo_data)) {
                    $this->appearance_model->insert_career_page_logo_record($new_logo_data);
                } else {
                    unset($new_logo_data['company_sid']);
                    $this->appearance_model->update_career_page_logo_record($company_sid, $new_logo_data);
                }

                $this->session->set_flashdata('message', '<strong>Success:</strong> Logo Updated!');
                redirect('appearance/career_logo_management', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function get_pages_name()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $sid = $this->input->get('sid');
        $company_id = $data["session"]["company_detail"]["sid"];
        $result = $this->themes_pages_model->GetAllPagesNameCompanySpecific($company_id, $sid);
        echo json_encode($result);
        exit(0);
    }

    public function add_additional_sections($theme_id)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'customize_appearance');
            $company_id = $data['session']['company_detail']['sid'];
            $data['title'] = 'Add Additional Section';
            $data['theme'] = $theme_id;

            if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'Save Section') {
                unset($_POST['perform_action']);
                $video = $this->input->post('video');
                unset($_POST['video']);
                $video_id = '';
                $url_prams = array();

                $vimeo_video = $_POST['vimeo_video'];

               if($video!='' && $_POST['show_video_or_image']=='video' )
                parse_str(parse_url($video, PHP_URL_QUERY), $url_prams);

                if (isset($url_prams['v'])) {
                    $video_id = $url_prams['v'];
                }
                

                $pictures = upload_file_to_aws('image', $company_id, 'image', '', AWS_S3_BUCKET_NAME);
                $_POST['company_sid'] = $company_id;

                if (!empty($pictures) && $pictures != 'error' && $_POST['show_video_or_image']=='image') {
                    $_POST['image'] = $pictures;
                }

                $newArray = array_map(function ($v) {
                    return trim(strip_tags($v));
                }, $_POST);

                //
                $vimeo_video_id = '';
                if (!empty($vimeo_video) && $_POST['show_video_or_image']=='vimeo_video') {
                    $vimeo_video = explode('vimeo.com/', $vimeo_video, 2);
                    $vimeo_video_id = $vimeo_video[1];
                }

                if (!empty($video_id)) {
                    $newArray['video'] = $video_id;
                }

                if (!empty($vimeo_video_id)) {
                    $newArray['video'] = $vimeo_video_id;
                }

                if (!empty($_FILES) && isset($_FILES['uploaded_video_section_02']) && $_FILES['uploaded_video_section_02']['size'] > 0) {

                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["uploaded_video_section_02"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["uploaded_video_section_02"]["tmp_name"], $target_file)) {
                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["uploaded_video_section_02"]["name"]) . ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('customize_appearance/' . $theme_id, 'refresh');
                    }

                    $video = $file_name;
                    $newArray['video'] = $video;
                }

                unset($newArray['vimeo_video']);
                
                $this->customize_appearance_model->add_additional_content_boxes($newArray);
                redirect(base_url('customize_appearance/' . $theme_id), 'refresh');
            }

            $this->load->view('main/header', $data);
            $this->load->view('appearance/customize_appearance/add_additional_sections_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_change_page_status()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $inactive_page = strtolower($_POST['page_name']);
            $is_published = $_POST['is_published'];
            $customize_career_site = $this->company_model->get_customize_career_site_data($company_sid);
            $inactive_pages = $customize_career_site['inactive_pages'];

            if ($is_published) {
                if (in_array($inactive_page, $inactive_pages)) {
                    if (($key = array_search($inactive_page, $inactive_pages)) !== false) {
                        unset($inactive_pages[$key]);
                    }
                }
            } else {
                if (!in_array($inactive_page, $inactive_pages)) {
                    $inactive_pages[] = $inactive_page;
                }
            }
            $customize_career_site['inactive_pages'] = json_encode(array_values($inactive_pages));
            $this->company_model->update_customize_career_site($company_sid, $customize_career_site);
            echo "success";
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
    private function generate_page_section_meta_array($image, $video, $title, $tag_line, $content, $status, $show_video_or_image, $column_type = 'left_right', $do_capitalize = 0, $vimeo_video = '', $uploaded_video = '')
    {
        $dataToSave = array(
            'image' => $image,
            'video' => $video,
            'title' => $title,
            'tag_line' => $tag_line,
            'content' => $content, //htmlentities($content),
            'status' => $status,
            'do_capitalize' => $do_capitalize,
            'show_video_or_image' => $show_video_or_image,
            'column_type' => $column_type,
            'vimeo_video' => $vimeo_video,
            'uploaded_video' => $uploaded_video,
        );

        return $dataToSave;
    }
}
