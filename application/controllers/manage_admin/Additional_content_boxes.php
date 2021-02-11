<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Additional_content_boxes extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/company_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid) {
        $redirect_url = 'manage_admin';
        $function_name = 'list_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $additional_boxes = $this->company_model->get_additional_content_boxes($company_sid);
        $this->data['additional_boxes'] = $additional_boxes;
        $this->data['company_sid'] = $company_sid;
//        echo '<pre>';
//        print_r($additional_boxes);
//        exit;
        $this->data['page_title'] = 'Add Additional Content Boxes';
        $this->render('manage_admin/company/additional_content_boxes_view', 'admin_master');
    } 
    
    function add($company_sid) {
        $redirect_url = 'manage_admin';
        $function_name = 'list_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Add Additional Content Boxes';
        $this->data['company_sid'] = $company_sid;

        if(isset($_POST['box_submit']) && $_POST['box_submit']=='Save'){
            unset($_POST['box_submit']);
            $pictures = upload_file_to_aws('image', $company_sid, 'image', '', AWS_S3_BUCKET_NAME);

            if (!empty($pictures) && $pictures != 'error') {
                $_POST['image'] = $pictures;
            }
            $newArray = array_map(function($v){
                return trim(strip_tags($v));
            }, $_POST);
            $this->company_model->add_additional_content_boxes($newArray);
            redirect(base_url('manage_admin/additional_content_boxes/'.$company_sid),'refresh');
        }
        $this->render('manage_admin/company/add_additional_boxes', 'admin_master');
    }

    function edit($box_sid){
        $redirect_url = 'manage_admin';
        $function_name = 'list_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Edit Additional Content Boxes';
        $box = $this->company_model->get_additional_box($box_sid);
        $this->data['box'] = $box[0];

        if(isset($_POST['box_submit']) && $_POST['box_submit']=='Update'){
            unset($_POST['box_submit']);
            $pictures = upload_file_to_aws('image', $box[0]['company_sid'], 'image', '', AWS_S3_BUCKET_NAME);

            if(!empty($pictures) && $pictures != 'error') {
                $_POST['image'] = $pictures;
            }
            $newArray = array_map(function($v){
                return trim(strip_tags($v));
            }, $_POST);
            $this->company_model->update_additional_content_boxes($box_sid,$newArray);
            redirect(base_url('manage_admin/additional_content_boxes/'.$box[0]['company_sid']),'refresh');
        }
        $this->render('manage_admin/company/edit_additional_boxes', 'admin_master');
    }

    function delete($sid,$company_sid){
        $redirect_url = 'manage_admin';
        $function_name = 'list_companies';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->company_model->delete_additional_content_boxes($sid);

        redirect(base_url('manage_admin/additional_content_boxes/'.$company_sid),'refresh');
    }
}