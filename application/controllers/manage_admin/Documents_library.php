<?php defined('BASEPATH') or exit('No direct script access allowed');

class Documents_library extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/documents_library_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'documents_library';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $documents_library_types = $this->documents_library_model->get_all_library_types();

        //        echo '<pre>'; print_r($documents_library_types); exit();
        $this->data['page_title'] = 'Documents Library';
        $this->data['documents_library_types'] = $documents_library_types;
        $this->render('manage_admin/documents_library/index');
    }

    public function view_details($sid = null)
    {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;

        if ($sid == null) {
            $this->session->set_flashdata('message', 'Document type not found!');
            redirect(base_url('manage_admin/documents_library'));
        }

        $this->data['page_title'] = "Document Library Management - Library Sub Menu";
        $this->data['lib_id']  = $sid;
        $this->data['parent_name']  = $this->documents_library_model->get_parent_name($sid);
        $document_type_details = $this->documents_library_model->get_type_details($sid);
        $this->data['document_type_details'] = $document_type_details;
        $this->render('manage_admin/documents_library/list_sub_menu');
    }

    public function add_new_menu($menu_id)
    {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        $this->data['page_title']                                               = 'Add New Sub Menu';
        $this->data['form']                                                     = 'add';
        $this->data['loc_id']                                                   = $menu_id;
        $this->data['parent_type']                                              = 'sub_menu';
        $company_sid                                                            = $this->ion_auth->user()->row()->id;
        $data_countries                                                         = db_get_active_countries();
        $this->data['parent_name']                                              = $this->documents_library_model->get_parent_name($menu_id);

        foreach ($data_countries as $value) {
            $data_states[$value['sid']]                                         = db_get_active_states($value['sid']);

            if ($value['sid'] == 38) {
                $data_states[$value['sid']][]                                  = array('sid' => '0', 'state_name' => 'All Canada States');
            } else {
                $data_states[$value['sid']][]                                  = array('sid' => '0', 'state_name' => 'All US States');
            }

            $total                                                              = count($data_states[$value['sid']]);
            $first                                                              = $data_states[$value['sid']][0];
            $data_states[$value['sid']][0]                                      = $data_states[$value['sid']][$total - 1];
            $data_states[$value['sid']][$total - 1]                               = $first;
        }

        $data_states_encode                                                     = htmlentities(json_encode($data_states));
        $this->data['active_countries']                                         = $data_countries;
        $this->data['active_states']                                            = $data_states;
        $this->data['states']                                                   = $data_states_encode;

        if (isset($_POST['form-submit'])) {
            $pre_id                                                             = $_POST['pre_id'];
            unset($_POST['form-submit']);
            unset($_POST['pre_id']);
            unset($_POST['docs']);
            unset($_POST['country']);
            unset($_POST['states']);
            unset($_POST['sort_order']);
            unset($_POST['federal_check']);
            unset($_POST['file_name']);
            unset($_POST['banner_image']);
            unset($_POST['word_editor']);

            if ($_POST['type'] == 'content') {
                unset($_POST['anchor-title']);
                unset($_POST['anchor_href']);
            } else {
                $_POST['description']                                           = '';
                $_POST['video']                                                 = '';
                $_POST['title']                                                 = $_POST['anchor-title'];
                unset($_POST['anchor-title']);
            }

            $_POST['sub_url_code']                                              = strtolower(clean($_POST['title']));
            $link                                                               = 'manage_admin/documents_library/view_details/' . $menu_id;
            $pictures                                                           = upload_file_to_aws('banner_image', $company_sid, 'banner_image', '', AWS_S3_BUCKET_NAME);

            if (!empty($pictures) && $pictures != 'error') {
                $_POST['banner_url']                                            = $pictures;
            }

            $video_source = $_POST['video_source'];
            $video_id = '';

            if ($video_source != 'no_video') {
                if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                    $random = generateRandomString(5);
                    $target_file_name = basename($_FILES["upload_video"]["name"]);
                    $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $upload_video_file_name;
                    $filename = $target_dir . $company_sid;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                    } else {

                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('learning_center/online_videos', 'refresh');
                    }

                    $video_id = $upload_video_file_name;
                } else {
                    $video_id = $_POST['yt_vm_video_url'];

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        } else {
                            $video_id = '';
                        }
                    } else if ($video_source == 'vimeo') {
                        $video_id = $this->vimeo_get_id($video_id);
                    }
                }
            }

            $_POST['video'] = $video_id;
            $_POST['video_type'] = $_POST['video_source'];
            unset($_POST['yt_vm_video_url']);
            unset($_POST['video_source']);

            if ($pre_id != 0) {
                $this->documents_library_model->edit_sub_menu($pre_id, $_POST);
            } else {
                $this->documents_library_model->add_new_sub_menu($_POST);
            }

            redirect($link);
        } elseif (isset($_POST['more'])) {
            $pre_id = $_POST['pre_id'];
            unset($_POST['more']);
            unset($_POST['pre_id']);
            unset($_POST['docs']);
            unset($_POST['country']);
            unset($_POST['states']);
            unset($_POST['sort_order']);
            unset($_POST['federal_check']);
            unset($_POST['file_name']);
            unset($_POST['banner_image']);
            unset($_POST['word_editor']);

            if ($_POST['type'] == 'content') {
                unset($_POST['anchor-title']);
                unset($_POST['anchor_href']);
            } else {
                $_POST['description']                                           = '';
                $_POST['video']                                                 = '';
                $_POST['title']                                                 = $_POST['anchor-title'];
                unset($_POST['anchor-title']);
            }

            $_POST['sub_url_code']                                              = strtolower(clean($_POST['title']));
            $link                                                               = 'manage_admin/documents_library/add_new_menu/' . $menu_id;
            $pictures                                                           = upload_file_to_aws('banner_image', $company_sid, 'banner_image', '', AWS_S3_BUCKET_NAME);

            if (!empty($pictures) && $pictures != 'error') {
                $_POST['banner_url']                                            = $pictures;
            }

            $video_source = $_POST['video_source'];
            $video_id = '';

            if ($video_source != 'no_video') {
                if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                    $random = generateRandomString(5);
                    $target_file_name = basename($_FILES["upload_video"]["name"]);
                    $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $upload_video_file_name;
                    $filename = $target_dir . $company_sid;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                    } else {

                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('learning_center/online_videos', 'refresh');
                    }

                    $video_id = $upload_video_file_name;
                } else {
                    $video_id = $_POST['vyt_vm_video_url'];

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        } else {
                            $video_id = '';
                        }
                    } else if ($video_source == 'vimeo') {
                        $video_id = $this->vimeo_get_id($video_id);
                    }
                }
            }

            $_POST['video'] = $video_id;
            $_POST['video_type'] = $_POST['video_source'];
            unset($_POST['yt_vm_video_url']);
            unset($_POST['video_source']);

            if ($pre_id != 0) {
                $this->documents_library_model->edit_sub_menu($pre_id, $_POST);
            } else {
                $this->documents_library_model->add_new_sub_menu($_POST);
            }

            redirect($link);
        }
        $this->render('manage_admin/documents_library/add_new_sub_menu');
    }

    public function add_new_heading($lib_id, $menu_id)
    {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        $this->data['page_title']                                               = 'Add New Sub Heading';
        $this->data['form']                                                     = 'heading';
        $this->data['menu_id']                                                  = $menu_id;
        $this->data['lib_id']                                                   = $lib_id;
        $this->data['parent_type']                                              = 'sub_heading';
        $company_sid                                                            = $this->ion_auth->user()->row()->id;
        $data_countries                                                         = db_get_active_countries();

        $this->data['parent_name']  = $this->documents_library_model->get_tree($lib_id, $menu_id, 'menu');

        foreach ($data_countries as $value) {
            $data_states[$value['sid']]                                         = db_get_active_states($value['sid']);

            if ($value['sid'] == 38) {
                $data_states[$value['sid']][]                                  = array('sid' => '0', 'state_name' => 'All Canada States');
            } else {
                $data_states[$value['sid']][]                                  = array('sid' => '0', 'state_name' => 'All US States');
            }

            $total                                                              = count($data_states[$value['sid']]);
            $first                                                              = $data_states[$value['sid']][0];
            $data_states[$value['sid']][0]                                      = $data_states[$value['sid']][$total - 1];
            $data_states[$value['sid']][$total - 1]                               = $first;
        }

        $data_states_encode                                                     = htmlentities(json_encode($data_states));
        $this->data['active_countries']                                         = $data_countries;
        $this->data['active_states']                                            = $data_states;
        $this->data['states']                                                   = $data_states_encode;

        if (isset($_POST['form-submit'])) {
            $pre_id = $_POST['pre_id'];
            unset($_POST['form-submit']);
            unset($_POST['pre_id']);
            unset($_POST['docs']);
            unset($_POST['country']);
            unset($_POST['states']);
            unset($_POST['sort_order']);
            unset($_POST['file_name']);
            unset($_POST['federal_check']);
            unset($_POST['banner_image']);
            unset($_POST['word_editor']);

            if ($_POST['type'] == 'content') {
                unset($_POST['anchor-title']);
                unset($_POST['anchor_href']);
            } else {
                $_POST['description'] = '';
                $_POST['video'] = '';
                $_POST['title'] = $_POST['anchor-title'];
                unset($_POST['anchor-title']);
            }

            $link = 'manage_admin/documents_library/view_sub_heading/' . $lib_id . '/' . $menu_id;
            $pictures = upload_file_to_aws('banner_image', $company_sid, 'banner_image', '', AWS_S3_BUCKET_NAME);

            if (!empty($pictures) && $pictures != 'error') {
                $_POST['banner_url'] = $pictures;
            }

            if ($pre_id != 0) {

                $video_source = $_POST['video_source'];
                $video_id = '';

                if ($video_source != 'no_video') {
                    if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                        $random = generateRandomString(5);
                        $target_file_name = basename($_FILES["upload_video"]["name"]);
                        $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/";
                        $target_file = $target_dir . $upload_video_file_name;
                        $filename = $target_dir . $company_sid;

                        if (!file_exists($filename)) {
                            mkdir($filename);
                        }

                        if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                        } else {

                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect('learning_center/online_videos', 'refresh');
                        }

                        $video_id = $upload_video_file_name;
                    } else {
                        $video_id = $_POST['yt_vm_video_url'];

                        if ($video_source == 'youtube') {
                            $url_prams = array();
                            parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $video_id = $url_prams['v'];
                            } else {
                                $video_id = '';
                            }
                        } else if ($video_source == 'vimeo') {
                            $video_id = $this->vimeo_get_id($video_id);
                        } else if ($video_source == 'uploaded') {
                            $video_id = $_POST['pre_upload_video_url'];
                        }
                    }
                }

                $_POST['video'] = $video_id;
                $_POST['video_type'] = $_POST['video_source'];
                unset($_POST['yt_vm_video_url']);
                unset($_POST['video_source']);
                unset($_POST['pre_upload_video_url']);

                $this->documents_library_model->edit_sub_menu($pre_id, $_POST);
            } else {

                $video_source = $_POST['video_source'];
                $video_id = '';

                if ($video_source != 'no_video') {
                    if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                        $random = generateRandomString(5);
                        $target_file_name = basename($_FILES["upload_video"]["name"]);
                        $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/";
                        $target_file = $target_dir . $upload_video_file_name;
                        $filename = $target_dir . $company_sid;

                        if (!file_exists($filename)) {
                            mkdir($filename);
                        }

                        if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                        } else {

                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect('learning_center/online_videos', 'refresh');
                        }

                        $video_id = $upload_video_file_name;
                    } else {
                        $video_id = $_POST['yt_vm_video_url'];

                        if ($video_source == 'youtube') {
                            $url_prams = array();
                            parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $video_id = $url_prams['v'];
                            } else {
                                $video_id = '';
                            }
                        } else if ($video_source == 'vimeo') {
                            $video_id = $this->vimeo_get_id($video_id);
                        }
                    }
                }

                $_POST['video'] = $video_id;
                $_POST['video_type'] = $_POST['video_source'];
                unset($_POST['yt_vm_video_url']);
                unset($_POST['video_source']);
                $this->documents_library_model->add_new_sub_menu($_POST);
            }

            redirect($link);
        } else if (isset($_POST['more'])) {
            $pre_id = $_POST['pre_id'];
            unset($_POST['more']);
            unset($_POST['pre_id']);
            unset($_POST['docs']);
            unset($_POST['country']);
            unset($_POST['states']);
            unset($_POST['sort_order']);
            unset($_POST['file_name']);
            unset($_POST['federal_check']);
            unset($_POST['banner_image']);
            unset($_POST['word_editor']);

            if ($_POST['type'] == 'content') {
                unset($_POST['anchor-title']);
                unset($_POST['anchor_href']);
            } else {
                $_POST['description'] = '';
                $_POST['video'] = '';
                $_POST['title'] = $_POST['anchor-title'];
                unset($_POST['anchor-title']);
            }

            $link = 'manage_admin/documents_library/add_new_heading/' . $lib_id . '/' . $menu_id;
            $pictures = upload_file_to_aws('banner_image', $company_sid, 'banner_image', '', AWS_S3_BUCKET_NAME);

            if (!empty($pictures) && $pictures != 'error') {
                $_POST['banner_url'] = $pictures;
            }

            if ($pre_id != 0) {

                $video_source = $_POST['video_source'];
                $video_id = '';

                if ($video_source != 'no_video') {
                    if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                        $random = generateRandomString(5);
                        $target_file_name = basename($_FILES["upload_video"]["name"]);
                        $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/";
                        $target_file = $target_dir . $upload_video_file_name;
                        $filename = $target_dir . $company_sid;

                        if (!file_exists($filename)) {
                            mkdir($filename);
                        }

                        if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                        } else {

                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect('learning_center/online_videos', 'refresh');
                        }

                        $video_id = $upload_video_file_name;
                    } else {
                        $video_id = $_POST['yt_vm_video_url'];

                        if ($video_source == 'youtube') {
                            $url_prams = array();
                            parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $video_id = $url_prams['v'];
                            } else {
                                $video_id = '';
                            }
                        } else if ($video_source == 'vimeo') {
                            $video_id = $this->vimeo_get_id($video_id);
                        } else if ($video_source == 'uploaded') {
                            $video_id = $_POST['pre_upload_video_url'];
                        }
                    }
                }

                $_POST['video'] = $video_id;
                $_POST['video_type'] = $_POST['video_source'];
                unset($_POST['yt_vm_video_url']);
                unset($_POST['video_source']);
                unset($_POST['pre_upload_video_url']);

                $this->documents_library_model->edit_sub_menu($pre_id, $_POST);
            } else {
                $video_source = $_POST['video_source'];
                $video_id = '';

                if ($video_source != 'no_video') {
                    if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                        $random = generateRandomString(5);
                        $target_file_name = basename($_FILES["upload_video"]["name"]);
                        $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                        $target_dir = "assets/uploaded_videos/";
                        $target_file = $target_dir . $upload_video_file_name;
                        $filename = $target_dir . $company_sid;

                        if (!file_exists($filename)) {
                            mkdir($filename);
                        }

                        if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                            $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                        } else {

                            $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                            redirect('learning_center/online_videos', 'refresh');
                        }

                        $video_id = $upload_video_file_name;
                    } else {
                        $video_id = $_POST['yt_vm_video_url'];

                        if ($video_source == 'youtube') {
                            $url_prams = array();
                            parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                            if (isset($url_prams['v'])) {
                                $video_id = $url_prams['v'];
                            } else {
                                $video_id = '';
                            }
                        } else if ($video_source == 'vimeo') {
                            $video_id = $this->vimeo_get_id($video_id);
                        }
                    }
                }

                $_POST['video'] = $video_id;
                $_POST['video_type'] = $_POST['video_source'];
                unset($_POST['yt_vm_video_url']);
                unset($_POST['video_source']);

                $this->documents_library_model->add_new_sub_menu($_POST);
            }

            redirect($link);
        }

        $this->render('manage_admin/documents_library/add_new_sub_menu');
    }

    public function edit_sub_menu($type_id, $sid)
    {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        $this->data['page_title']                                               = 'Edit Sub Menu';
        $this->data['form']                                                     = 'edit';
        $this->data['menu_id']                                                  = $type_id;
        $this->data['parent_type']                                              = 'sub_menu';
        $company_sid                                                            = $this->ion_auth->user()->row()->id;
        $pre_sub_menu                                                           = $this->documents_library_model->fetch_sub_menu($sid);
        $files                                                                  = $this->documents_library_model->fetch_sub_menu_files($sid, 'Related');
        $word_doc                                                               = $this->documents_library_model->fetch_sub_menu_files($sid, 'Generated');
        $this->data['pre_sub_menu']                                             = sizeof($pre_sub_menu) > 0 ? $pre_sub_menu[0] : array();
        $this->data['files']                                                    = sizeof($files) > 0 ? $files : array();
        $this->data['word_doc']                                                 = sizeof($word_doc) > 0 ? $word_doc : array();
        $data_countries                                                         = db_get_active_countries();
        $this->data['parent_name']                                              = $this->documents_library_model->get_tree($type_id, $sid, 'menu');

        foreach ($data_countries as $value) {
            $data_states[$value['sid']]                                         = db_get_active_states($value['sid']);

            if ($value['sid'] == 38) {
                $data_states[$value['sid']][]                                  = array('sid' => '0', 'state_name' => 'All Canada States');
            } else {
                $data_states[$value['sid']][]                                  = array('sid' => '0', 'state_name' => 'All US States');
            }

            $total                                                              = count($data_states[$value['sid']]);
            $first                                                              = $data_states[$value['sid']][0];
            $data_states[$value['sid']][0]                                      = $data_states[$value['sid']][$total - 1];
            $data_states[$value['sid']][$total - 1]                               = $first;
        }

        $data_states_encode                                                     = htmlentities(json_encode($data_states));
        $this->data['active_countries']                                         = $data_countries;
        $this->data['active_states']                                            = $data_states;
        $this->data['states']                                                   = $data_states_encode;

        if (isset($_POST['form-submit'])) {
            unset($_POST['form-submit']);
            unset($_POST['pre_id']);
            unset($_POST['docs']);
            unset($_POST['country']);
            unset($_POST['states']);
            unset($_POST['sort_order']);
            unset($_POST['federal_check']);
            unset($_POST['file_name']);
            unset($_POST['word_editor']);

            if ($_POST['type'] == 'content') {
                unset($_POST['anchor-title']);
                unset($_POST['anchor_href']);
            } else {
                $_POST['description']                                           = '';
                $_POST['video']                                                 = '';
                $_POST['title']                                                 = $_POST['anchor-title'];
                unset($_POST['anchor-title']);
            }
            if (!isset($_POST['video_status'])) {
                $_POST['video_status'] = 0;
            }
            $_POST['sub_url_code']                                              = strtolower(clean($_POST['title']));
            $pictures                                                           = upload_file_to_aws('banner_image', $company_sid, 'banner_image', '', AWS_S3_BUCKET_NAME);

            if (!empty($pictures) && $pictures != 'error') {
                $_POST['banner_url']                                            = $pictures;
            } else {
                $_POST['banner_url']                                            = $pre_sub_menu[0]['banner_url'];
            }

            $video_source = $_POST['video_source'];
            $video_id = '';

            if ($video_source != 'no_video') {
                if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                    $random = generateRandomString(5);
                    $target_file_name = basename($_FILES["upload_video"]["name"]);
                    $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $upload_video_file_name;
                    $filename = $target_dir . $company_sid;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                    } else {

                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('learning_center/online_videos', 'refresh');
                    }

                    $video_id = $upload_video_file_name;
                } else {
                    $video_id = $_POST['yt_vm_video_url'];

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        } else {
                            $video_id = '';
                        }
                    } else if ($video_source == 'vimeo') {
                        $video_id = $this->vimeo_get_id($video_id);
                    } else if ($video_source == 'uploaded') {
                        $video_id = $_POST['pre_upload_video_url'];
                    }
                }
            }

            $_POST['video'] = $video_id;
            $_POST['video_type'] = $_POST['video_source'];
            unset($_POST['yt_vm_video_url']);
            unset($_POST['video_source']);
            unset($_POST['pre_upload_video_url']);

            // if(!empty($_POST['video'])) {
            //     $youtube_video                                                  = explode('v=', $_POST['video'], 2);
            //     $_POST['video']                                                 = $youtube_video[1];
            // }

            if (!isset($_POST['banner_status'])) {
                $_POST['banner_status']                                         = 0;
            }

            // echo '<pre>';
            // print_r($_POST);
            // die();
            $this->documents_library_model->edit_sub_menu($sid, $_POST);
            $this->session->set_flashdata('message', 'Sub menu updated successfully!');
            redirect(base_url('manage_admin/documents_library/view_details/' . $type_id));
        }

        $this->render('manage_admin/documents_library/edit_sub_menu');
    }

    public function edit_sub_heading($category, $type_id, $sid)
    {
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        $this->data['page_title']                                               = 'Edit Sub Heading';
        $this->data['form']                                                     = 'edit';
        $this->data['menu_id']                                                  = $type_id;
        $this->data['lib_id']                                                   = $category;
        $this->data['head_id']                                                  = $sid;
        $this->data['parent_type']                                              = 'sub_heading';
        $company_sid                                                            = $this->ion_auth->user()->row()->id;
        $pre_sub_menu                                                           = $this->documents_library_model->fetch_sub_menu($sid);

        $files                                                                  = $this->documents_library_model->fetch_sub_menu_files($sid, 'Related');
        $word_doc                                                               = $this->documents_library_model->fetch_sub_menu_files($sid, 'Generated');

        $filesHybrid                                                                  = $this->documents_library_model->fetch_sub_menu_files($sid, 'Hybrid');

        // _e($filesHybrid, true);

        $this->data['pre_sub_menu']                                             = sizeof($pre_sub_menu) > 0 ? $pre_sub_menu[0] : array();
        $this->data['files']                                                    = sizeof($files) > 0 ? $files : array();
        $this->data['word_doc']                                                 = sizeof($word_doc) > 0 ? $word_doc : array();
        $this->data['filesHybrid']                                              = sizeof($filesHybrid) > 0 ? $filesHybrid : array();

        $data_countries                                                         = db_get_active_countries();
        $this->data['parent_name']                                              = $this->documents_library_model->get_tree($type_id, $sid, 'heading');
        foreach ($data_countries as $value) {
            $data_states[$value['sid']]                                         = db_get_active_states($value['sid']);

            if ($value['sid'] == 38) {
                $data_states[$value['sid']][]                                  = array('sid' => '0', 'state_name' => 'All Canada States');
            } else {
                $data_states[$value['sid']][]                                  = array('sid' => '0', 'state_name' => 'All US States');
            }

            $total                                                              = count($data_states[$value['sid']]);
            $first                                                              = $data_states[$value['sid']][0];
            $data_states[$value['sid']][0]                                      = $data_states[$value['sid']][$total - 1];
            $data_states[$value['sid']][$total - 1]                               = $first;
        }

        $data_states_encode                                                     = htmlentities(json_encode($data_states));
        $this->data['active_countries']                                         = $data_countries;
        $this->data['active_states']                                            = $data_states;
        $this->data['states']                                                   = $data_states_encode;

        if (isset($_POST['form-submit'])) {
            unset($_POST['form-submit']);
            unset($_POST['pre_id']);
            unset($_POST['docs']);
            unset($_POST['country']);
            unset($_POST['states']);
            unset($_POST['sort_order']);
            unset($_POST['federal_check']);
            unset($_POST['file_name']);
            unset($_POST['word_editor']);

            if ($_POST['type'] == 'content') {
                unset($_POST['anchor-title']);
                unset($_POST['anchor_href']);
            } else {
                $_POST['description'] = '';
                $_POST['video']                                                 = '';
                $_POST['title']                                                 = $_POST['anchor-title'];
                unset($_POST['anchor-title']);
            }

            $pictures                                                           = upload_file_to_aws('banner_image', $company_sid, 'banner_image', '', AWS_S3_BUCKET_NAME);

            if (!empty($pictures) && $pictures != 'error') {
                $_POST['banner_url']                                            = $pictures;
            } else {
                $_POST['banner_url']                                            = $pre_sub_menu[0]['banner_url'];
            }

            $video_source = $_POST['video_source'];
            $video_id = '';

            if ($video_source != 'no_video') {
                if (isset($_FILES['upload_video']) && !empty($_FILES['upload_video']['name'])) {
                    $random = generateRandomString(5);
                    $target_file_name = basename($_FILES["upload_video"]["name"]);
                    $upload_video_file_name = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $upload_video_file_name;
                    $filename = $target_dir . $company_sid;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["upload_video"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["upload_video"]["name"]) . ' has been uploaded.');
                    } else {

                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('learning_center/online_videos', 'refresh');
                    }

                    $video_id = $upload_video_file_name;
                } else {
                    $video_id = $_POST['yt_vm_video_url'];

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        } else {
                            $video_id = '';
                        }
                    } else if ($video_source == 'vimeo') {
                        $video_id = $this->vimeo_get_id($video_id);
                    } else if ($video_source == 'uploaded') {
                        $video_id = $_POST['pre_upload_video_url'];
                    }
                }
            }

            $_POST['video'] = $video_id;
            $_POST['video_type'] = $_POST['video_source'];
            unset($_POST['yt_vm_video_url']);
            unset($_POST['video_source']);
            unset($_POST['pre_upload_video_url']);

            if (!isset($_POST['banner_status'])) {
                $_POST['banner_status'] = 0;
            }

            if (!isset($_POST['video_status'])) {
                $_POST['video_status'] = 0;
            }

            $_POST['sub_url_code']                                              = strtolower(clean($_POST['title']));
            $this->documents_library_model->edit_sub_menu($sid, $_POST);
            $this->session->set_flashdata('message', 'Sub heading updated successfully!');
            redirect(base_url('manage_admin/documents_library/view_sub_heading/' . $category . '/' . $type_id));
        }

        $this->render('manage_admin/documents_library/edit_sub_menu');
    }

    public function enable_disable_type($sid)
    {
        $data = array('status' => $this->input->get('status'));
        $this->documents_library_model->update_incident_type($sid, $data);
        print_r(json_encode(array('message' => 'updated')));
    }

    public function enable_disable_sub_menu($sid)
    {
        $data = array('status' => $this->input->get('status'));
        $this->documents_library_model->edit_sub_menu($sid, $data);
        print_r(json_encode(array('message' => 'updated')));
    }

    public function view_sub_heading($lib_id, $sid)
    {
        if ($sid == null) {
            $this->session->set_flashdata('message', 'Document type not found!');
            redirect(base_url('manage_admin/documents_library'));
        }

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;

        $this->data['page_title']                                               = 'Library Sub Headings';
        $this->data['menu_id']                                                  = $sid;
        $this->data['lib_id']                                                   = $lib_id;
        $this->data['parent_name']                                              = $this->documents_library_model->get_tree($lib_id, $sid, 'menu');
        $document_sub_heading_details                                           = $this->documents_library_model->get_sub_heading_details($sid);
        $this->data['document_sub_heading_details']                             = $document_sub_heading_details;
        $this->render('manage_admin/documents_library/list_sub_heading');
    }

    public function ajax_handler()
    {
        $company_sid = $this->ion_auth->user()->row()->id;
        $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
        //        $pictures = 'MyFile.asd';
        if (!empty($pictures) && $pictures != 'error') {

            if (isset($_POST['pre_id']) && $_POST['pre_id'] != 0) {
                $insert_id = $_POST['pre_id'];
            } else {
                $insert['status'] = 0;
                $insert[$_POST['parent_type']] = $_POST['id'];
                $insert_id = $this->documents_library_model->add_new_sub_menu($insert);
            }

            $docs = array();
            $states = explode(',', $_POST['states']);

            if (in_array(0, $states)) {
                $_POST['states'] = 0;
            }
            if ($_POST['parent_type'] == 'category_id') {
                $docs['parent_id'] = $_POST['id'];
                $docs['sub_menu_id'] = $insert_id;
            } elseif ($_POST['parent_type'] == 'menu_id') {
                $docs['parent_id'] = $_POST['parent_id'];
                $docs['sub_menu_id'] = $_POST['id'];
                $docs['sub_heading_id'] = $insert_id;
            }
            $last_index_of_dot = strrpos($_FILES["docs"]["name"], '.') + 1;
            $file_ext = substr($_FILES["docs"]["name"], $last_index_of_dot, strlen($_FILES["docs"]["name"]) - $last_index_of_dot);
            $docs['file_name'] = $_POST['file_name'];
            $docs['sort_order'] = $_POST['sort_order'];
            $docs['country'] = $_POST['country'];
            $docs['states'] = $_POST['states'];
            $docs['federal_check'] = $_POST['federal_check'];
            $docs['word_content'] = $_POST['word_content'];
            $docs['type'] = $file_ext;
            $docs['file_code'] = $pictures;
            $docs['upload_date'] = date('Y-m-d H:i:s');
            $docs['menu_id'] = $insert_id;
            $docs['file_url_code'] = strtolower(clean($_POST['file_name']));
            if (isset($_POST['doc_type'])) {
                $docs['doc_type'] = $_POST['doc_type'];
            }

            $this->documents_library_model->insert_library_docs($docs);
            echo $insert_id;
        } else {
            echo 'error';
        }
    }

    public function delete_file_ajax()
    {
        $file_id = $this->input->post('id');
        $action = $this->input->post('action');
        $data = array();

        if ($action == 'disable') {
            $data['status'] = 0;
        } else {
            $data['status'] = 1;
        }

        $this->documents_library_model->edit_file($file_id, $data);
        echo 'Edited';
    }

    public function edit_form_ajax_handler()
    {
        $company_sid = $this->ion_auth->user()->row()->id;
        //        $pictures = 'Myfile2.com';
        $pictures = upload_file_to_aws('edit-file', $company_sid, 'edit-file', '', AWS_S3_BUCKET_NAME);
        $id = $_POST['id'];

        if (!empty($pictures) && $pictures != 'error') {
            $docs = array();
            $last_index_of_dot = strrpos($_FILES["edit-file"]["name"], '.') + 1;
            $docs['file_code'] = $pictures;
            $file_ext = substr($_FILES["edit-file"]["name"], $last_index_of_dot, strlen($_FILES["edit-file"]["name"]) - $last_index_of_dot);
            $docs['type'] = $file_ext;
        }

        $states = explode(',', $_POST['states']);

        if (in_array(0, $states)) {
            $_POST['states'] = 0;
        }

        $docs['file_name'] = $_POST['file_name'];
        $docs['sort_order'] = $_POST['sort_order'];
        $docs['country'] = $_POST['country'];
        $docs['states'] = $_POST['states'];
        $docs['federal_check'] = $_POST['federal_check'];
        $docs['word_content'] = $_POST['word_content'];
        $docs['upload_date'] = date('Y-m-d H:i:s');
        $docs['file_url_code'] = strtolower(clean($_POST['file_name'])) . '-v' . $id;
        $this->documents_library_model->update_library_docs($docs, $id);
        echo $id;
    }

    public function generate_ajax_handler()
    {
        if (isset($_POST['pre_id']) && $_POST['pre_id'] != 0) {
            $insert_id = $_POST['pre_id'];
        } else {
            $insert['status'] = 0;
            $insert[$_POST['parent_type']] = $_POST['id'];
            $insert_id = $this->documents_library_model->add_new_sub_menu($insert);
        }

        $docs = array();
        $states = explode(',', $_POST['states']);

        if (in_array(0, $states)) {
            $_POST['states'] = 0;
        }
        if ($_POST['parent_type'] == 'category_id') {
            $docs['parent_id'] = $_POST['id'];
            $docs['sub_menu_id'] = $insert_id;
        } elseif ($_POST['parent_type'] == 'menu_id') {
            $docs['parent_id'] = $_POST['parent_id'];
            $docs['sub_menu_id'] = $_POST['id'];
            $docs['sub_heading_id'] = $insert_id;
        }
        $docs['file_name'] = $_POST['file_name'];
        $docs['sort_order'] = $_POST['sort_order'];
        $docs['country'] = $_POST['country'];
        $docs['states'] = $_POST['states'];
        $docs['federal_check'] = $_POST['federal_check'];
        $docs['word_content'] = $_POST['word_content'];
        $docs['upload_date'] = date('Y-m-d H:i:s');
        $docs['doc_type'] = 'Generated';
        $docs['menu_id'] = $insert_id;
        $docs['file_url_code'] = strtolower(clean($_POST['file_name']));
        $this->documents_library_model->insert_library_docs($docs);
        echo $insert_id;
    }

    public function generate_edit_file()
    {
        $id = $_POST['id'];
        $docs = array();
        $states = explode(',', $_POST['states']);

        if (in_array(0, $states)) {
            $_POST['states'] = 0;
        }

        $docs['file_name'] = $_POST['file_name'];
        $docs['sort_order'] = $_POST['sort_order'];
        $docs['country'] = $_POST['country'];
        $docs['states'] = $_POST['states'];
        $docs['federal_check'] = $_POST['federal_check'];
        $docs['word_content'] = $_POST['word_content'];
        $docs['file_url_code'] = strtolower(clean($_POST['file_name'])) . '-v' . $id;
        $this->documents_library_model->update_library_docs($docs, $id);
        echo $id;
    }

    public function delete_record_ajax()
    {
        $file_id = $this->input->post('id');

        $this->documents_library_model->delete_file($file_id);
        echo 'Deleted';
    }

    public function delete_banner()
    {
        $sid = $this->input->post('id');
        $update_array = array(
            'banner_url'    =>  NULL,
            'banner_status' =>  0
        );
        $this->documents_library_model->edit_sub_menu($sid, $update_array);
        echo 'updated';
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
}
