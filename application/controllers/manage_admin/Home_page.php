<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home_page extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $this->load->model('manage_admin/home_page_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function customize_home_page() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'customize_page';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        //View data working starts
        $this->data['page_title'] = 'Home Page Customization';

        //getting Homepage customize data
        $this->data['home_page'] = $this->home_page_model->get_home_page_custom_data();

        $this->form_validation->set_rules('header_text', 'Header Text', 'trim|xss_clean|required');
        $this->form_validation->set_rules('hr_video', 'HR and Recruiting Video', 'trim|xss_clean|required');
        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/home_page/customize_home_page');
        } else {
            $formData = $this->input->post(NULL, TRUE);

            if (isset($formData['header_text'])) {
                $dataToSave['header_text'] = $formData['header_text'];
            }

            if (isset($formData['header_sub_text'])) {
                $dataToSave['header_sub_text'] = $formData['header_sub_text'];
            }
            if (isset($formData['banner_1_type'])) {
                $dataToSave['banner_1_type'] = $formData['banner_1_type'];
                if($dataToSave['banner_1_type'] == 'no_video')
                    $dataToSave['why_us_banner_1'] = '';
            }
            if (isset($formData['banner_2_type'])) {
                $dataToSave['banner_2_type'] = $formData['banner_2_type'];
                if($dataToSave['banner_2_type'] == 'no_video')
                    $dataToSave['why_us_banner_2'] = '';
            }
            

            if ($formData['header_flag'] == 'header_video_flag') {//video is selected for home page header
                $youtube_link = $formData['header_video'];
                $youtube_code = substr($youtube_link, strpos($youtube_link, '=') + 1, strlen($youtube_link));

                $dataToSave['header_video'] = $youtube_code;
                $dataToSave['header_video_flag'] = 1;
                $dataToSave['header_banner_flag'] = 0;
            } elseif ($formData['header_flag'] == 'header_banner_flag') {// Image banner is selected for home page header
                if (isset($_FILES['header_banner']) && $_FILES['header_banner']['name'] != '') {
                    $file = explode(".", $_FILES["header_banner"]["name"]);
                    $document = $file[0] . '_' . generateRandomString(10) . '.' . end($file);
                    $aws = new AwsSdk();
                    $aws->putToBucket($document, $_FILES["header_banner"]["tmp_name"], AWS_S3_BUCKET_NAME);
                } else {
                    $document = $formData['old_header_banner'];
                }
                $dataToSave['header_banner'] = $document;
                $dataToSave['header_banner_flag'] = 1;
                $dataToSave['header_video_flag'] = 0;
            }


            if (isset($formData['hr_video'])) {//HR video for home page header
                $youtube_link = $formData['hr_video'];
                $youtube_code = substr($youtube_link, strpos($youtube_link, '=') + 1, strlen($youtube_link));
                $dataToSave['hr_video'] = $youtube_code;
            }
            if (!empty($_FILES) && isset($_FILES['video_upload_1']) && $_FILES['video_upload_1']['size'] > 0) {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["video_upload_1"]["name"]);
                $file_name = strtolower($admin_id . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/manage_admin/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $admin_id;

                if(!is_dir($basePath)) {
                   mkdir($basePath, 0777, true);
                }

                if (move_uploaded_file($_FILES["video_upload_1"]["tmp_name"], $target_file)) {

                    $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload_1"]["name"]) . ' has been uploaded.');
                } else {

                    $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                    redirect(current_url(), 'refresh');
                }

                $dataToSave['why_us_banner_1'] = $target_file;
            }else if (isset($_FILES['why_us_banner_1']) && $_FILES['why_us_banner_1']['name'] != '') {//Why us banner no: 1
                $file = explode(".", $_FILES["why_us_banner_1"]["name"]);
                $dataToSave['why_us_banner_1'] = $file[0] . '_' . generateRandomString(10) . '.' . end($file);
                $aws = new AwsSdk();
                $aws->putToBucket($dataToSave['why_us_banner_1'], $_FILES["why_us_banner_1"]["tmp_name"], AWS_S3_BUCKET_NAME);
            } else if(isset($dataToSave['banner_1_type']) && ($dataToSave['banner_1_type'] == 'youtube' || $dataToSave['banner_1_type'] == 'vimeo')){
                $video_id = $formData['video_url_1'];
                if ($dataToSave['banner_1_type'] == 'youtube') {
                    $url_prams = array();
                    parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_id = $url_prams['v'];
                    } else {
                        $video_id = '';
                    }
                } else if ($dataToSave['banner_1_type'] == 'vimeo') {
                    $video_id = $this->vimeo_get_id($video_id);
                }
                $dataToSave['why_us_banner_1'] = $video_id;
            }
            
            if (!empty($_FILES) && isset($_FILES['video_upload_2']) && $_FILES['video_upload_2']['size'] > 0) {
                $random = generateRandomString(5);
                $target_file_name = basename($_FILES["video_upload_2"]["name"]);
                $file_name = strtolower($admin_id . '/' . $random . '_' . $target_file_name);
                $target_dir = "assets/uploaded_videos/manage_admin/";
                $target_file = $target_dir . $file_name;
                $basePath = $target_dir . $admin_id;

                if(!is_dir($basePath)) {
                   mkdir($basePath, 0777, true);
                }

                if (move_uploaded_file($_FILES["video_upload_2"]["tmp_name"], $target_file)) {

                    $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload_2"]["name"]) . ' has been uploaded.');
                } else {

                    $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                    redirect(current_url(), 'refresh');
                }

                $dataToSave['why_us_banner_2'] = $target_file;
            }
            else if (isset($_FILES['why_us_banner_2']) && $_FILES['why_us_banner_2']['name'] != '') {//Why us banner no: 1
                $file = explode(".", $_FILES["why_us_banner_2"]["name"]);
                $dataToSave['why_us_banner_2'] = $file[0] . '_' . generateRandomString(10) . '.' . end($file);
                $aws = new AwsSdk();
                $aws->putToBucket($dataToSave['why_us_banner_2'], $_FILES["why_us_banner_2"]["tmp_name"], AWS_S3_BUCKET_NAME);
            } else if(isset($dataToSave['banner_2_type']) && ($dataToSave['banner_2_type'] == 'youtube' || $dataToSave['banner_2_type'] == 'vimeo')){
                $video_id = $formData['video_url_2'];
                if ($dataToSave['banner_2_type'] == 'youtube') {
                    $url_prams = array();
                    parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_id = $url_prams['v'];
                    } else {
                        $video_id = '';
                    }
                } else if ($dataToSave['banner_2_type'] == 'vimeo') {
                    $video_id = $this->vimeo_get_id($video_id);
                }
                $dataToSave['why_us_banner_2'] = $video_id;
            }


            if (!isset($this->data['home_page'])) {
                $this->home_page_model->insert_home_page_custom_data($dataToSave);
            } else {
                $this->home_page_model->update_home_page_custom_data($dataToSave);
            }

            $this->session->set_flashdata('message', 'Homepage changes saved successfully.');
            redirect('manage_admin/home_page/customize_home_page', 'refresh');
        }
    }
    public function vimeo_get_id($str) {
        if ($str != "") {
            if($_SERVER['HTTP_HOST']=='localhost'){
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if(!empty($response)){
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

}
