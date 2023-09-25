<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Resources extends Admin_Controller
{
    //
    private $resp = [];

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/resources_model');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        //
        $this->resp = [
            'Status' => false,
            'Response' => 'Reqest Not Authorized'
        ];
    }


    public function index()
    {
        $this->load->library('pagination');
        $redirect_url = 'manage_admin';
        $admin_id = $this->ion_auth->user()->row()->id;
        $this->data['page_title'] = 'Resources';
        $page_number = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

        $offset           = 0;
        $records_per_page = 50;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $records_per_page;
        }
        $pagination_base = base_url('manage_admin/resources');
        $pages_data = $this->resources_model->get_resources($records_per_page, $offset);
        $total_records = $this->resources_model->get_resources(null, null, true);

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $this->uri->total_segments();
        $config["num_links"] = 8;
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_records'] = $total_records;
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $offset + $records_per_page;
        $this->data['groups'] = $this->ion_auth->groups()->result();

        $this->data['pages_data'] = $pages_data;
        $this->render('manage_admin/resources/index');
    }


    //
    public function edit_resource($sid)
    {

        $this->load->helper('url');
        $redirect_url = 'manage_admin';
        $admin_id = $this->ion_auth->user()->row()->id;
        $this->data['page_title'] = 'Resource';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $page_data = $this->resources_model->get_resourcesById($sid);

        $this->form_validation->set_rules('meta_title', 'Meta Title', 'required|trim|xss_clean');
        $this->form_validation->set_rules('meta_description', 'Meta Description', 'required|trim|xss_clean');
        $this->form_validation->set_rules('meta_key_word', 'Meta Key Word', 'required|trim|xss_clean');
        $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
        $this->form_validation->set_rules('slug', 'Slug', 'required|trim|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->data['page_data'] = $page_data;
            $this->render('manage_admin/resources/edit_resource');
        } else {


            $dataInsert['meta_title'] = $this->input->post('meta_title');
            $dataInsert['meta_description'] = $this->input->post('meta_description');
            $dataInsert['meta_key_word'] = $this->input->post('meta_key_word');
            $dataInsert['title'] = $this->input->post('title');
            $dataInsert['slug'] = $this->input->post('slug');
            $dataInsert['description'] = $this->input->post('description');
            $dataInsert['status'] = $this->input->post('status');
            if (!empty($_POST['resourcesfile'])) {
                $dataInsert['resources'] = $this->input->post('resourcesfile');
            }
            if (!empty($_POST['feature_imagefile'])) {
                $dataInsert['feature_image'] = $this->input->post('feature_imagefile');
            }

            if ($this->input->post('resourcetype')) {
                $resourceType = $this->input->post('resourcetype');
                $dataInsert['resource_type'] = implode(',', $resourceType);
            }


            if ($sid == 0) {

                $this->resources_model->add_resources($dataInsert);
                $this->session->set_flashdata('message', '<strong>Success:</strong> Resource added successfully.');
                redirect('manage_admin/edit_resource/0', 'refresh');
            } else {

                $this->resources_model->update_resources($sid, $dataInsert);
                $this->session->set_flashdata('message', '<strong>Success:</strong> Resource Updated successfully.');
                redirect('manage_admin/edit_resource/' . $sid, 'refresh');
            }
        }
    }




    //
    public function view_resource($sid)
    {

        $this->load->helper('url');
        $redirect_url = 'manage_admin';
        $admin_id = $this->ion_auth->user()->row()->id;
        $this->data['page_title'] = 'Resource';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $page_data = $this->resources_model->get_resourcesById($sid);
        $this->data['page_data'] = $page_data;
        $this->render('manage_admin/resources/view_resource');
    }






    public function upload_file_ajax_handler()
    {

        $original_name = $_FILES['document']['name'];

        $document_title = $this->input->post('document_title');
        $original_name = $_FILES['document']['name'];

        $valid_extension = array('jpg', 'jpeg', 'png', 'gif', 'pdf');
        $valid_videow_extension = array('mp4', 'm4a', 'm4v', 'f4v', 'f4a', 'm4b', 'm4r', 'f4b', 'mov');

        //
        $file_info = pathinfo($original_name);
        $extension = strtolower($file_info['extension']);

        //
        if (in_array($extension, $valid_videow_extension)) {

            $target_file_name = basename($_FILES["document"]["name"]);
            $random = generateRandomString(7);
            $file_name = strtolower($random . '_' . $target_file_name);
            $target_dir = "assets/uploaded_videos/resourses/";
            $target_file = $target_dir . $file_name;

            if (!file_exists($target_dir)) {
                mkdir($target_dir);
            }

            if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {

                $return_data['upload_status'] = 'success';
                $return_data['document_url'] = $file_name;
                $return_data['original_name'] = '';
                $return_data['extension'] = $extension;
                echo json_encode($return_data);
                exit(0);
            } else {

                $return_data['upload_status'] =  'error';
                $return_data['reason'] =  'Something went wrong, Please try again!';
                echo json_encode($return_data);
                exit(0);
            }
        }


        //
        if (in_array($extension, $valid_extension)) {
            $uploadedDocument = upload_file_to_aws('document', '0', str_replace(' ', '_', $document_title), '1', AWS_S3_BUCKET_NAME);

            if (!empty($uploadedDocument) && $uploadedDocument != 'error') {
                $return_data['upload_status'] = 'success';
                $return_data['document_url'] = $uploadedDocument;
                $return_data['original_name'] = $original_name;
                $return_data['extension'] = $extension;

                echo json_encode($return_data);
            } else {
                $return_data['upload_status'] =  'error';
                $return_data['reason'] =  'Something went wrong, Please try again!';
                echo json_encode($return_data);
            }
        } else {
            $return_data['upload_status'] =  'error';
            $return_data['reason'] =  'Upload document type is not valid';
            echo json_encode($return_data);
        }
    }




    //
    function upload_file_to_aws($file_input_id, $company_sid, $document_name, $suffix = '', $bucket_name = AWS_S3_BUCKET_NAME)
    {
        require_once(APPPATH . 'libraries/aws/aws.php');

        if (isset($_FILES[$file_input_id]) && $_FILES[$file_input_id]['name'] != '') {
            $last_index_of_dot = strrpos($_FILES[$file_input_id]["name"], '.') + 1;
            $file_ext = substr($_FILES[$file_input_id]["name"], $last_index_of_dot, strlen($_FILES[$file_input_id]["name"]) - $last_index_of_dot);
            $file_name = trim($document_name . '-' . $suffix);
            $file_name = str_replace(" ", "_", $file_name);
            $file_name = strtolower($file_name);
            $prefix = str_pad($company_sid, 4, '0', STR_PAD_LEFT);
            $new_file_name = $prefix . '-' . $file_name . '-' . generateRandomString(3) . '.' . $file_ext;

            if ($_FILES[$file_input_id]['size'] == 0) {
                $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                return 'error';
            }

            $aws = new AwsSdk();
            $aws->putToBucket($new_file_name, $_FILES[$file_input_id]['tmp_name'], $bucket_name);
            return $new_file_name;
        } else {
            return 'error';
        }
    }
}
