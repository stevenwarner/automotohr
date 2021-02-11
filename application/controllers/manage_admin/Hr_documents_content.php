<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Hr_documents_content extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('hr_document_model');
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'hr_documents_content';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {
            $sections = $this->hr_document_model->get_hr_documents_section_records();
            $this->data['sections'] = $sections;

            $this->render('manage_admin/hr_documents_content/index');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'delete_section':
                    $section_sid = $this->input->post('section_sid');
                    $this->hr_document_model->delete_hr_documents_section($section_sid);

                    $this->session->set_flashdata('message', '<strong>Success: </strong> Section Successfully Deleted!');

                    redirect('manage_admin/hr_documents_content', 'refresh');
                    break;
            }
        }
    }

    public function add_section()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'hr_documents_content_add_section';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
        if ($this->form_validation->run() == false) {

            $this->render('manage_admin/hr_documents_content/add_section');
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

                    if (!empty($video_name)) {
                        $data_to_insert['video'] = $video_name;
                    }

                    $this->hr_document_model->insert_hr_documents_section_record($data_to_insert);

                    redirect('manage_admin/hr_documents_content', 'refresh');
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

            $section = $this->hr_document_model->get_single_documents_section_record($section_sid);
            $this->data['section'] = $section;

            $this->render('manage_admin/hr_documents_content/add_section');
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

                    if (!empty($video_name)) {
                        $data_to_update['video'] = $video_name;
                    }


                    $this->hr_document_model->update_hr_documents_section_record($section_sid, $data_to_update);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Section Updated Successfully');


                    redirect('manage_admin/hr_documents_content', 'refresh');
                    break;
            }

        }
    }

}