<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Resource_page extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/resource_page_model');

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {
        $redirect_url = 'manage_admin';
        $function_name = 'customize_resource_page';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Resource Page';

            $page_data = $this->resource_page_model->get_page_data('resources');
            $this->data['page_data'] = $page_data;

            $this->render('manage_admin/resource_page/index');
        } else {

            $perform_action = $this->input->post('perform_action');
            $page_sid = $this->input->post('page_sid');
            $page_name = $this->input->post('page_name');
            $page_title = $this->input->post('page_title');
            $page_content = $this->input->post('page_content');
            $page_status = $this->input->post('page_status');

            /*
            $page_banner_type = $this->input->post('page_banner_type');
            $page_banner_video = $this->input->post('page_banner_video');

            $query_string = parse_url($page_banner_video, PHP_URL_QUERY);
            parse_str($query_string, $youtube_url_params);

            if (isset($youtube_url_params['v'])) {
                $page_banner_video = $youtube_url_params['v'];
            }
            */


            switch ($perform_action) {
                case 'save_page_data':

                    /*
                    if (isset($_FILES['page_banner_image']) && $_FILES['page_banner_image']['name'] != '') {
                        $image_name = upload_file_to_aws('page_banner_image', 0, 'resources_banner');
                    } else {
                        $image_name = '';
                    }
                    */


                    $page_data = array();
                    $page_data['page_name'] = $page_name;
                    $page_data['page_title'] = $page_title;
                    $page_data['page_content'] = htmlentities($page_content);
                    $page_data['page_status'] = $page_status;

                    /*
                    $page_data['page_banner_type'] = $page_banner_type;
                    $page_data['page_banner_video'] = $page_banner_video;

                    if (!empty($image_name) && $image_name != '') {
                        $page_data['page_banner_image'] = $image_name;
                    }
                    */


                    $this->resource_page_model->save_page_record($page_sid, $page_data);

                    $this->session->set_flashdata('message', '<strong>Success:</strong> Content Updated');

                    redirect('manage_admin/resource_page', 'refresh');
                    break;
                case 'delete_section':
                    $section_sid = $this->input->post('section_sid');
                    $this->resource_page_model->delete_dynamic_pages_section($section_sid);

                    $this->session->set_flashdata('message', '<strong>Success:</strong> Section Deleted!');

                    redirect('manage_admin/resource_page', 'refresh');
                    break;
            }
        }
    }

    public function add_section($dynamic_pages_sid)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'resource_page_add_section';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {

            $this->data['dynamic_pages_sid'] = $dynamic_pages_sid;
            $this->render('manage_admin/resource_page/add_section');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'add_section':
                    $aws_file_name = upload_file_to_aws('video', 0, 'hr_docs_content', '', 'hr-documents-videos');

                    $video_name = '';

                    if ($aws_file_name != 'error') {
                        $video_name = $aws_file_name;
                    }

                    $title = $this->input->post('title');
                    $description = $this->input->post('description');
                    $sort_order = $this->input->post('sort_order');
                    $status = $this->input->post('status');
                    $dynamic_pages_sid = $this->input->post('dynamic_pages_sid');

                    $video_status = $this->input->post('video_status');

                    $youtube_video = $this->input->post('youtube_video');

                    $url_prams = array();
                    parse_str(parse_url($youtube_video, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $youtube_video = $url_prams['v'];
                    } else {
                        $youtube_video = '';
                    }

                    $youtube_video_status = $this->input->post('youtube_video_status');

                    $data_to_insert = array();
                    $data_to_insert['title'] = $title;
                    $data_to_insert['description'] = htmlentities($description);
                    $data_to_insert['sort_order'] = $sort_order;
                    $data_to_insert['status'] = $status;
                    $data_to_insert['video_status'] = $video_status;
                    $data_to_insert['youtube_video'] = $youtube_video;
                    $data_to_insert['youtube_video_status'] = $youtube_video_status;
                    $data_to_insert['dynamic_pages_sid'] = $dynamic_pages_sid;

                    if (!empty($video_name)) {
                        $data_to_insert['video'] = $video_name;
                    }

                    $this->resource_page_model->insert_dynamic_pages_section_record($data_to_insert);

                    redirect('manage_admin/resource_page', 'refresh');
                    break;
            }
        }
    }

    public function edit_section($section_sid)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'hr_documents_content_edit_section';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {

            $section = $this->resource_page_model->get_single_dynamic_pages_section_record($section_sid);
            $this->data['section'] = $section;

            $this->data['dynamic_pages_sid'] = $section['dynamic_pages_sid'];

            $this->render('manage_admin/resource_page/add_section');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'add_section':
                    $aws_file_name = upload_file_to_aws('video', 0, 'hr_docs_content', '', 'hr-documents-videos');

                    $video_name = '';

                    if ($aws_file_name != 'error') {
                        $video_name = $aws_file_name;
                    }

                    $section_sid = $this->input->post('section_sid');
                    $title = $this->input->post('title');
                    $description = $this->input->post('description');
                    $sort_order = $this->input->post('sort_order');
                    $status = $this->input->post('status');

                    $video_status = $this->input->post('video_status');

                    $youtube_video = $this->input->post('youtube_video');

                    $dynamic_pages_sid = $this->input->post('dynamic_pages_sid');

                    $url_prams = array();
                    parse_str(parse_url($youtube_video, PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $youtube_video = $url_prams['v'];
                    } else {
                        $youtube_video = '';
                    }

                    $youtube_video_status = $this->input->post('youtube_video_status');

                    $data_to_update = array();
                    $data_to_update['title'] = $title;
                    $data_to_update['description'] = htmlentities($description);
                    $data_to_update['sort_order'] = $sort_order;
                    $data_to_update['status'] = $status;
                    $data_to_update['video_status'] = $video_status;
                    $data_to_update['youtube_video'] = $youtube_video;
                    $data_to_update['youtube_video_status'] = $youtube_video_status;
                    $data_to_insert['dynamic_pages_sid'] = $dynamic_pages_sid;

                    if (!empty($video_name)) {
                        $data_to_update['video'] = $video_name;
                    }


                    $this->resource_page_model->update_dynamic_pages_section_record($section_sid, $data_to_update);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Section Updated Successfully');


                    redirect('manage_admin/resource_page', 'refresh');
                    break;
            }
        }
    }
}