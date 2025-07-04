<?php defined('BASEPATH') or exit('No direct script access allowed');
class Resource_center extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('resource_center_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'resource_center_panel'); // Param2: Redirect URL, Param3: Function Name
            $data['title']                                                      = 'Resource Center';

            if (!$data['session']['company_detail']['enable_resource_center']) {
                $this->session->set_flashdata('message', 'Not Access able');
                redirect('dashboard', "location");
            }
            $main_menu_cat_tree                                                 = $this->generate_menu_tree();
            $data['cat_tree']                                                   = $main_menu_cat_tree;
            $data['main_menu']                                                  = $main_menu_cat_tree['main'];
            $data['sub_menu']                                                   = $main_menu_cat_tree['sub'];
            $main_menu_url                                                      = $main_menu_cat_tree['main_menu_url'];
            $sub_menu_url                                                       = $main_menu_cat_tree['sub_menu_url'];
            $data['main_menu_url']                                              = $main_menu_url;
            $data['sub_menu_url']                                               = $sub_menu_url;
            $sub_menu_chain                                                     = $main_menu_cat_tree['sub_menu_chain'];
            $main_menu_segment                                                  = '';
            $sub_menu_segment                                                   = '';
            $generated_doc_segment                                              = '';
            $segment1                                                           = $this->uri->segment(2);
            $segment2                                                           = $this->uri->segment(3);
            $segment3                                                           = $this->uri->segment(4);
            $left_navigation                                                    = 'parent';
            $left_menu                                                          = array();
            $left_menu_parent                                                   = '';
            $active_link                                                        = 'resource_center';
            $intro_main_fa_icon                                                 = 'fa-align-left';
            $main_menu_id                                                       = 0;
            $sub_menu_id                                                        = 0;
            $gen_menu_id                                                        = 0;
            $page_type                                                          = '';
            $page_content                                                       = array();
            $page_sub_menus                                                     = array();
            $copied_record                                                      = array();

            if (!empty($segment1)) {
                $main_menu_segment                                              = urldecode($segment1);
                $left_navigation                                                = 'main';
                $left_menu                                                      = $sub_menu_chain[$main_menu_segment];
                $left_menu_parent                                               = $main_menu_url[$main_menu_segment];
                $active_link                                                    = $main_menu_segment;

                if (empty($segment2)) { // redirect it to first child of segment 1
                    if (!empty($left_menu)) {
                        $segment2 = $left_menu[0]['url_code'];
                    }
                }
            }

            if (!empty($segment2)) {
                $sub_menu_segment                                               = urldecode($segment2);
                //                echo $sub_menu_segment;
                $left_navigation                                                = 'sub';
                $active_link                                                    = $sub_menu_segment;
            }

            if (!empty($segment3)) { // hassan working area
                $generated_doc_segment                                          = urldecode($segment3);
                //                $left_navigation                                                = 'generated_doc';
            }


            if (!empty($main_menu_segment) || !empty($sub_menu_segment)) { // it is submenu. Fetch its data.
                //step 1: Verify both segments and get their data
                $main_menu_segment                                              = strtolower($main_menu_segment);
                $sub_menu_segment                                               = strtolower($sub_menu_segment);

                switch ($main_menu_segment) {
                    case    'topics':
                        $main_menu_id                                       = 1;
                        $intro_main_fa_icon                                 = 'fa-align-left';
                        break;
                    case    'laws':
                        $main_menu_id                                       = 2;
                        $intro_main_fa_icon                                 = 'fa-gavel';
                        break;
                    case    'learning':
                        $main_menu_id                                       = 3;
                        $intro_main_fa_icon                                 = 'fa-graduation-cap';
                        break;
                    case    'tools':
                        $main_menu_id                                       = 4;
                        $intro_main_fa_icon                                 = 'fa-gears';
                        break;
                    case    'documents':
                        $main_menu_id                                       = 5;
                        $intro_main_fa_icon                                 = 'fa-file-text-o';
                        break;
                    case    'hr-on-demand':
                        $main_menu_id                                       = 6;
                        $intro_main_fa_icon                                 = 'fa fa-wechat';
                        break;
                }

                //$main_menu_id = $this->resource_center_model->get_recource_id($main_menu_segment, 'main_menu');
                $sub_menu_id                                                    = $this->resource_center_model->get_recource_id($sub_menu_segment, 'sub_menu', $main_menu_id);

                if ($left_navigation == 'sub') {
                    if ($main_menu_id == 0 || $sub_menu_id == 0) {               // link not found in database, generate error message
                        $this->session->set_flashdata('message', '<b>Error:</b> Page not found!');
                        redirect(base_url('resource_center'), 'refresh');
                    } else {
                        if (!empty($generated_doc_segment)) {

                            $generated_doc_segment                              = strtolower($generated_doc_segment);
                            $page_content                                       = $this->resource_center_model->get_generated_doc_content($generated_doc_segment);
                            //                            $gen_menu_id                                        = $page_content['sid'];

                            if (empty($page_content)) {                                // page status is either changed to offline or does not exists
                                $this->session->set_flashdata('message', '<b>Error:</b> Page not found!');
                                redirect(base_url('resource_center'), 'refresh');
                            }
                            $copied_record                                      = $this->resource_center_model->get_copied_record($page_content['sid'], $company_sid);

                            $page_type                                          = 'generated_doc';
                        } else {
                            $page_content                                       = $this->resource_center_model->get_page_content($main_menu_id, $sub_menu_id);

                            if (empty($page_content)) {                                // page status is either changed to offline or does not exists
                                $this->session->set_flashdata('message', '<b>Error:</b> Document not found!');
                                redirect(base_url('resource_center') . '/' . $main_menu_segment . '/' . $sub_menu_segment, 'refresh');
                            }

                            $page_sub_menus                                     = $this->resource_center_model->get_page_submenu($sub_menu_id);
                            $page_type                                          = 'submenu';
                        }
                    }
                }
            }
            //            echo '<pre>'; print_r($page_content); exit;
            $data['left_navigation']                                            = $left_navigation;
            $data['left_menu']                                                  = $left_menu;
            $data['left_menu_parent']                                           = $left_menu_parent;
            $data['active_link']                                                = $active_link;
            $data['segment1']                                                   = $segment1;
            $data['page_content']                                               = $page_content;
            $data['copied_record']                                              = $copied_record;
            $data['page_sub_menus']                                             = $page_sub_menus;
            $data['intro_main_fa_icon']                                         = $intro_main_fa_icon;
            $data['main_menu_segment']                                          = $main_menu_segment;
            $data['sub_menu_segment']                                           = $sub_menu_segment;
            $data['generated_doc_segment']                                      = $generated_doc_segment;
            $data['page_type']                                                  = $page_type;

            $this->load->view('main/header', $data);
            $this->load->view('resource_center/index');
            $this->load->view('main/footer');
        } else {
            redirect('login', 'refresh');
        }
    }

    public function view_detail()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $data['title']                                                      = 'Resource Center';

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('resource_center/view_detail');
                $this->load->view('main/footer');
            } else {
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function generate_menu_tree()
    {
        $parent_menu                                                            = $this->resource_center_model->get_parent_menu_tree();
        $main_menu                                                              = array();
        $sub_menu                                                               = array();
        $main_menu_url                                                          = array();
        $sub_menu_url                                                           = array();
        $sub_menu_chain                                                         = array();

        for ($i = 0; $i < count($parent_menu); $i++) {
            $main_sid                                                           = $parent_menu[$i]['main_sid'];
            $url_code                                                           = $parent_menu[$i]['url_code'];
            $fa_icon                                                            = $parent_menu[$i]['fa_icon'];

            $main_menu[$main_sid] = array(
                'main_sid'                          => $parent_menu[$i]['main_sid'],
                'name'                              => $parent_menu[$i]['name'],
                'code'                              => $url_code,
                'fa_icon'                           => $fa_icon,
                'parent_sort_order'                 => $parent_menu[$i]['parent_sort_order'],
                'description'                       => $parent_menu[$i]['parent_description']
            );
            //            }
            $main_menu_url[$url_code] = array(
                'sid'                             => $parent_menu[$i]['main_sid'],
                'name'                            => $parent_menu[$i]['name'],
                'code'                            => $url_code,
                'fa_icon'                         => $fa_icon,
                'link'                            => base_url('resource_center') . '/' . $url_code
            );

            $sid                                                                = $parent_menu[$i]['sid'];
            $status                                                             = $parent_menu[$i]['status'];

            if ($sid > 0) {
                if ($status > 0) {
                    $sub_url_code                                               = $parent_menu[$i]['sub_url_code'];
                    $title                                                      = $parent_menu[$i]['title'];
                    $anchor_type                                                = $parent_menu[$i]['type'];
                    $anchor_href                                                = $parent_menu[$i]['anchor_href'];

                    if ($anchor_type == 'content') {
                        $link                                                   = base_url('resource_center') . '/' . $url_code . '/' . $sub_url_code;
                    } else {
                        $parsed                                                 = parse_url($anchor_href);

                        if (empty($parsed['scheme'])) {
                            $anchor_href                                        = 'http://' . ltrim($anchor_href, '/');
                        }

                        $link                                                   = $anchor_href;
                    }

                    if ($sub_url_code == NULL) {
                        $sub_url_code                                           = strtolower(clean($title));
                        $this->resource_center_model->update_url_code($sub_url_code, $sid, 'document_library_sub_menu');
                    }

                    $sub_menu[$main_sid][] = array(
                        'sid'                       => $sid,
                        'name'                      => $parent_menu[$i]['title'],
                        'url_code'                  => $sub_url_code,
                        'description'               => $parent_menu[$i]['description'],
                        'type'                      => $parent_menu[$i]['type'],
                        'parent_type'               => $parent_menu[$i]['parent_type'],
                        'video'                     => $parent_menu[$i]['video'],
                        'video_status'              => $parent_menu[$i]['video_status'],
                        'video_type'                => $parent_menu[$i]['video_type'],
                        'banner_status'             => $parent_menu[$i]['banner_status'],
                        'banner_url'                => $parent_menu[$i]['banner_url'],
                        'anchor_href'               => $parent_menu[$i]['anchor_href'],
                        'link'                      => $link
                    );

                    $sub_menu_chain[$url_code][] = array(
                        'name'              => $parent_menu[$i]['title'],
                        'url_code'          => $sub_url_code,
                        'link'              => $link
                    );

                    $sub_menu_url[$sub_url_code] = array(
                        'sid'                  => $sid,
                        'name'                 => $parent_menu[$i]['title'],
                        'type'                 => $parent_menu[$i]['type'],
                        'parent_sid'           => $parent_menu[$i]['main_sid'],
                        'parent_name'          => $parent_menu[$i]['name'],
                        'parent_code'          => $url_code,
                        'link'                 => $link
                    );
                }
            } else {
                $sub_menu[$main_sid]                                            = array();
                $sub_menu_chain[$url_code]                                      = array();
            }
        }

        $return_array = array(
            'main'                                            => $main_menu,
            'sub'                                             => $sub_menu,
            'main_menu_url'                                   => $main_menu_url,
            'sub_menu_url'                                    => $sub_menu_url,
            'sub_menu_chain'                                  => $sub_menu_chain
        );
        //        echo '<pre>'; print_r($return_array); exit;
        return $return_array;
    }

    function resource_center_search()
    {
        $key                                                                    = trim(urldecode($_GET['key']));
        $data['session']                                                        = $this->session->userdata('logged_in');
        $security_sid                                                           = $data['session']['employer_detail']['sid'];
        $security_details                                                       = db_get_access_level_details($security_sid);
        $data['security_details']                                               = $security_details;
        $data['title']                                                          = 'Resource Center Search';
        $main_menu_cat_tree                                                     = $this->generate_menu_tree();
        $data['cat_tree']                                                       = $main_menu_cat_tree;
        $data['main_menu']                                                      = $main_menu_cat_tree['main'];
        $data['sub_menu']                                                       = $main_menu_cat_tree['sub'];
        $main_menu_url                                                          = $main_menu_cat_tree['main_menu_url'];
        $sub_menu_url                                                           = $main_menu_cat_tree['sub_menu_url'];
        $data['main_menu_url']                                                  = $main_menu_url;
        $data['sub_menu_url']                                                   = $sub_menu_url;
        $data['key']                                                            = $key;
        $data['left_navigation']                                                = 'parent';
        $data['active_link']                                                    = 'resource_center';
        $data['segment1']                                                       = '';
        $submenu_urlcodes                                                       = array();
        $types                                                                  = array(
            1 => 'topics',
            2 => 'laws',
            3 => 'learning',
            4 => 'tools',
            5 => 'documents',
            6 => 'hr-on-demand'
        );

        if (empty($key)) {
            $this->session->set_flashdata('message', '<b>Error:</b> Empty search keyword!');
            redirect(base_url('resource_center'), 'refresh');
        }

        $search_result = $this->resource_center_model->search_result($key);
        $search_files = $search_result['files'];

        if (!empty($search_files)) {
            $submenu_urlcodes = $this->resource_center_model->get_submenu_urlcodes();
        }

        //        echo '<pre>'; print_r($submenu_urlcodes); echo '</pre>'; exit;
        $data['search_count'] = count($search_result['content']) + count($search_files);
        $data['search_content'] = $search_result['content'];
        $data['search_files'] = $search_files;
        $data['submenu_urlcodes'] = $submenu_urlcodes;
        $data['parent_urlcodes'] = $types;

        $this->load->view('main/header', $data);
        $this->load->view('resource_center/search_result');
        $this->load->view('main/footer');
    }

    function update_document_library_files()
    { // it updates the file_url_code in document_library_files table
        //        $this->resource_center_model->update_document_library_files();
        $this->resource_center_model->document_library_files_parent_id();
    }

    public function copy_to_DMS()
    {
        $file_sid = $this->input->post('file_sid');
        $file_data = $this->resource_center_model->get_library_file_by_sid($file_sid);
        $data['session'] = $this->session->userdata('logged_in');
        $employer_sid = $data['session']['employer_detail']['sid'];
        $copied_by_name = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
        $company_sid = $data['session']['company_detail']['sid'];
        $companyName = $data['session']['company_detail']['CompanyName'];
        $data_to_insert = array();
        $history_data = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['employer_sid'] = $employer_sid;
        $data_to_insert['document_title'] = $file_data[0]['file_name'];
        $data_to_insert['document_description'] = $file_data[0]['word_content'];
        $data_to_insert['unique_key'] = generateRandomString(32);
        $data_to_insert['onboarding'] = 0;
        $data_to_insert['acknowledgment_required'] = 0;
        $data_to_insert['download_required'] = 0;
        $data_to_insert['signature_required'] = 0;
        $data_to_insert['copied_doc_sid'] = $file_sid;

        if ($file_data[0]['doc_type'] == 'Related') {


            $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;

            $file_name = $file_data[0]['file_code'];

            $temp_file_path = $temp_path . $file_name;

            if (file_exists($temp_file_path)) {
                unlink($temp_file_path);
            }

            $this->load->library('aws_lib');
            $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $file_name, $temp_file_path);
            $new_file_name = '';
            if (file_exists($temp_file_path)) {

                $file_name = trim($file_data[0]['file_name'] . '-' . $employer_sid);
                $file_name = str_replace(" ", "_", $file_name);
                $file_name = strtolower($file_name);
                $prefix = str_pad($company_sid, 4, '0', STR_PAD_LEFT);
                $new_file_name = $prefix . '-' . $file_name . '-' . generateRandomString(3) . '.' . $file_data[0]['type'];
                $options = [
                    'Bucket' => AWS_S3_BUCKET_NAME,
                    'Key' => $new_file_name,
                    'Body' => file_get_contents($temp_file_path),
                    'ACL' => 'public-read',
                    'ContentType' => $file_data[0]['type']
                ];

                $this->aws_lib->put_object($options);
                unlink($temp_file_path);
            }


            $data_to_insert['document_type'] = 'uploaded';
            $data_to_insert['uploaded_document_extension'] = $file_data[0]['type'];
            $data_to_insert['uploaded_document_original_name'] = $file_data[0]['file_name'];
            $data_to_insert['uploaded_document_s3_name'] = $new_file_name;


            $history_data['document_type'] = 'uploaded';
            $history_data['uploaded_document_extension'] = $file_data[0]['type'];
            $history_data['uploaded_document_original_name'] = $file_data[0]['file_name'];
            $history_data['uploaded_document_s3_name'] = $new_file_name;
        } else  if ($file_data[0]['doc_type'] == 'Hybrid') {


            $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;

            $file_name = $file_data[0]['file_code'];

            $temp_file_path = $temp_path . $file_name;

            if (file_exists($temp_file_path)) {
                unlink($temp_file_path);
            }

            $this->load->library('aws_lib');
            $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $file_name, $temp_file_path);
            $new_file_name = '';
            if (file_exists($temp_file_path)) {

                $file_name = trim($file_data[0]['file_name'] . '-' . $employer_sid);
                $file_name = str_replace(" ", "_", $file_name);
                $file_name = strtolower($file_name);
                $prefix = str_pad($company_sid, 4, '0', STR_PAD_LEFT);
                $new_file_name = $prefix . '-' . $file_name . '-' . generateRandomString(3) . '.' . $file_data[0]['type'];
                $options = [
                    'Bucket' => AWS_S3_BUCKET_NAME,
                    'Key' => $new_file_name,
                    'Body' => file_get_contents($temp_file_path),
                    'ACL' => 'public-read',
                    'ContentType' => $file_data[0]['type']
                ];

                $this->aws_lib->put_object($options);
                unlink($temp_file_path);
            }


            $data_to_insert['document_type'] = 'hybrid_document';
            $data_to_insert['uploaded_document_extension'] = $file_data[0]['type'];
            $data_to_insert['uploaded_document_original_name'] = $file_data[0]['file_name'];
            $data_to_insert['uploaded_document_s3_name'] = $new_file_name;


            $history_data['document_type'] = 'uploaded';
            $history_data['uploaded_document_extension'] = $file_data[0]['type'];
            $history_data['uploaded_document_original_name'] = $file_data[0]['file_name'];
            $history_data['uploaded_document_s3_name'] = $new_file_name;
        } else {
            $data_to_insert['document_type'] = 'generated';
            $history_data['document_type'] = 'generated';
        }

        $insert_id = $this->resource_center_model->insert_document_record($data_to_insert);
        // Tracking History For New Inserted Doc
        $history_data['documents_management_sid'] = $insert_id;
        $history_data['company_sid'] = $company_sid;
        $history_data['document_title'] = $file_data[0]['file_name'];
        $history_data['document_description'] = $file_data[0]['word_content'];
        $history_data['date_created'] = date('Y-m-d H:i:s');
        $history_data['update_by_sid'] = $employer_sid;
        $history_data['acknowledgment_required'] = 0;
        $history_data['download_required'] = 0;
        $history_data['signature_required'] = 0;
        $history_data['copied_doc_sid'] = $file_sid;
        $this->resource_center_model->save_doc_history($history_data);
        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Copied!');

        $copied_record = array(
            'company_sid'                =>    $company_sid,
            'company_name'               =>    $companyName,
            'document_library_sid'       =>    $file_sid,
            'copied_by'                  =>    $employer_sid,
            'copy_date'                  =>    date('Y-m-d H:i:s'),
            'copied_by_name'             =>    $copied_by_name
        );
        $this->resource_center_model->record_copied_document($copied_record);
    }
}
