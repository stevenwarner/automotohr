<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Settings extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/settings_model');
//        $this->load->model('manage_admin/message_model');
//        $this->load->model('Application_status_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        $redirect_url = 'manage_admin';
        $function_name = 'system_settings';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'System Settings';
        $settings = $this->settings_model->get_all_settings();
        $this->data['data'] = $settings[0];
        $this->form_validation->set_rules('admin_email', 'Admin Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('mail_send_from', 'Mail Send From', 'trim|required');
        $this->form_validation->set_rules('mail_send_email', 'Mail Send Email', 'trim|required|valid_email');
        
        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/settings/listing_view');
            //$this->session->set_flashdata('message','Error Save! There was validation error in your form, Please fix and try again!');
        } else {
            $site_title = $this->input->post('site_title');
            $admin_email = $this->input->post('admin_email');
            $payment_to = $this->input->post('payment_to');
            $mail_send_from = $this->input->post('mail_send_from');
            $mail_send_email = $this->input->post('mail_send_email');
            $mail_signature = $this->input->post('mail_signature');
            $data = array('site_title' => $site_title, 'admin_email' => $admin_email, 'payment_to' => $payment_to, 'mail_Send_from' => $mail_send_from, 'mail_send_email' => $mail_send_email, 'mail_signature' => $mail_signature);
            $this->settings_model->save_setting($settings[0]['sid'], $data);
            //$this->session->set_flashdata('message',$msg);
            $this->render('manage_admin/settings/listing_view');
        }
    }

    public function social_settings() {
        $redirect_url = 'manage_admin';
        $function_name = 'social_settings';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Social Setting';
        $settings = $this->settings_model->get_all_settings();
        $this->data['data'] = $settings[0];
        $this->form_validation->set_rules('facebook_url', 'Facebook Link', 'trim');
        $this->form_validation->set_rules('twitter_url', 'Twitter Link', 'trim');
        $this->form_validation->set_rules('google_plus_url', 'Google+ Link', 'trim');
        $this->form_validation->set_rules('linkedin_url', 'LinkedIn Link', 'trim');
        $this->form_validation->set_rules('glassdoor_url', 'Glass Door Link', 'trim');
        $this->form_validation->set_rules('youtube_url', 'Youtube Link', 'trim');
        $this->form_validation->set_rules('instagram_url', 'Instagram Link', 'trim');

        if ($this->form_validation->run() != FALSE) {
            $formpost = $_POST;
            $this->settings_model->save_setting($settings[0]['sid'], $formpost);
        }
        
        $this->render('manage_admin/settings/social_setting');
    }

     public function demo_affiliate_configurations()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'demo_affiliate_configurations';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
         check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        $this->data['page_title'] = 'Demo & Affilate Page Configurations';
        
        
        $configurations = $this->settings_model->get_affiliate_demo_page_configurations();
        $this->data['configurations'] = $configurations;

        $this->render('manage_admin/settings/demo_affiliate_listing');
        
    }
    
    function edit_demo_affiliate_configurations($configuration_sid = NULL) {
        $redirect_url = 'manage_admin';
        $function_name = 'edit_affiliate_config';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        
        if ($configuration_sid == 1 || $configuration_sid == 2 || $configuration_sid == 3 || $configuration_sid == 4) {

            $page_data = $this->settings_model->get_page_configurations($configuration_sid);
            $this->data['page_title'] = $page_data[0]['page_name'];

            if(!empty($page_data)) {
                $this->data['data'] = $page_data[0];
                
        
                if ($page_data[0]['youtube_video'] == '' || $page_data[0]['youtube_video'] == '0') {
                    $page_data[0]['youtube_video'] = '';
                    
                } else {
                    $page_data[0]['youtube_video'] = 'https://www.youtube.com/watch?v='.$page_data[0]['youtube_video'];
                } 
                
                if ($page_data[0]['vimeo_video'] == '' || $page_data[0]['vimeo_video'] == '0') {
                    $page_data[0]['vimeo_video'] = '';
                    
                }else{
                    
                    $page_data[0]['vimeo_video'] = 'https://vimeo.com/'.$page_data[0]['vimeo_video'];
                }
            }
        
        
            $this->form_validation->set_rules('action', 'action', 'trim');
        
            if ($this->form_validation->run() === FALSE) {
                $this->data['youtube'] =$page_data[0]['youtube_video'];
                $this->data['vimeo'] =$page_data[0]['vimeo_video'];

                $this->render('manage_admin/settings/demo_affiliate');
                //$this->session->set_flashdata('message','Error Save! There was validation error in your form, Please fix and try again!');
            } else {
                
                $video_source = $this->input->post('video_source');
                $video_sid = $this->input->post('sid');
                $video_id;
                $data_to_update = array();
                
                if(!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {  
                    
                    $random = generateRandomString(5); 
                    $target_file_name = basename($_FILES["video_upload"]["name"]);
                    
                    $file_name = strtolower('/'.$random.'_'.$target_file_name); 

                    $target_dir = "assets/uploaded_videos/super_admin";
                    $target_file = $target_dir . $file_name;
                    
                    $filename = $target_dir;

                    if (!file_exists($filename)){
                        mkdir($filename);
                    }
                
                    if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {
                        
                        $this->session->set_flashdata('message', '<strong>The file '. basename( $_FILES["video_upload"]["name"]). ' has been uploaded.');
                    } else {
                        
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('manage_admin/settings/demo_affiliate_configurations', 'refresh');
                    }
                    
                    $data_to_update['uploaded_video'] = $file_name;

                    $video_source_name = $this->settings_model->get_demo_affiliate_videos($video_sid);
                    $video_url = 'assets/uploaded_videos/super_admin' . $video_source_name[0]['uploaded_video'];
                    unlink($video_url);            
                }

                
                

                if($video_source == 'youtube_video'){
                    $vimeo_video_id = $this->input->post('vimeo_video');
                    $vimeo_video_id = $this->vimeo_get_id($vimeo_video_id);

                    $youtube_video_id = $this->input->post('youtube_video');
                    $url_prams = array();
                    parse_str(parse_url($youtube_video_id, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $youtube_video_id = $url_prams['v'];
                    } else {
                        $youtube_video_id = '';
                    }

                    $data_to_update['youtube_video'] = $youtube_video_id;
                    $data_to_update['vimeo_video'] = $vimeo_video_id;
                    

                } elseif ($video_source == 'vimeo_video') {
                    $vimeo_video_id = $this->input->post('vimeo_video');
                    $vimeo_video_id = $this->vimeo_get_id($vimeo_video_id);

                    $youtube_video_id = $this->input->post('youtube_video');
                    $url_prams = array();
                    parse_str(parse_url($youtube_video_id, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $youtube_video_id = $url_prams['v'];
                    } else {
                        $youtube_video_id = '';
                    }

                    $data_to_update['youtube_video'] = $youtube_video_id;
                    $data_to_update['vimeo_video'] = $vimeo_video_id;

                } else {
                    $vimeo_video_id = $this->input->post('vimeo_video');
                    $vimeo_video_id = $this->vimeo_get_id($vimeo_video_id);

                    $youtube_video_id = $this->input->post('youtube_video');
                    $url_prams = array();
                    parse_str(parse_url($youtube_video_id, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $youtube_video_id = $url_prams['v'];
                    } else {
                        $youtube_video_id = '';
                    }

                    $data_to_update['youtube_video'] = $youtube_video_id;
                    $data_to_update['vimeo_video'] = $vimeo_video_id;

                }
                
                if(!empty($video_source)){
                    $data_to_update['video_source'] = $video_source;
                }

                $data_to_update['status'] = $this->input->post('status');

                if ($configuration_sid == 3 || $configuration_sid == 4) {
                    $data_to_update['column_type'] = $this->input->post('column_type');
                    $data_to_update['title'] = $this->input->post('title');
                    $data_to_update['content'] = $this->input->post('content');
                    
                }

                $this->settings_model->update_demo_affiliate_videos($video_sid, $data_to_update);
                $this->session->set_flashdata('message', '<strong>Success: </strong> Configuration updated!');
                redirect('manage_admin/settings/demo_affiliate_configurations', 'refresh');
            }

        } else {
            $this->session->set_flashdata('message', '<strong>Error: </strong> Record Not Found!');
            redirect('manage_admin/settings/demo_affiliate_configurations', 'refresh');
        }
    }

    function validate_vimeo() {
        $str = $this->input->post('url');
        
        if ($str != "") {
            if($_SERVER['HTTP_HOST']=='localhost'){
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if(!empty($response)){
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
