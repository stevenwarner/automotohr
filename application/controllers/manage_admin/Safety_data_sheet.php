<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Safety_data_sheet extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/safety_data_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
    }

    public function index(){
        $redirect_url       = 'manage_admin';
        $function_name      = 'safety_sheet';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title'] = "Safety Data Sheet - Safety Hazard";

        $sheet_data = $this->safety_data_model->get_all_safety_data();
        $this->data['sheet_data'] = $sheet_data;
        $this->render('manage_admin/safety_data_sheet/index');
    }

    public function add_new_category(){
        $redirect_url       = 'manage_admin';
        $function_name      = 'add_safety_category';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Safety Data Sheet - Category Management";
        $config = array(
            array(
                'field' => 'category_name',
                'label' => 'Category Name',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            $this->render('manage_admin/safety_data_sheet/add_new_category');
        } else{
            $formpost = $this->input->post(NULL, TRUE);
            $insert_array = array();
            $insert_array['name'] = $formpost['category_name'];
            $insert_array['sort_order']  = $formpost['sort_order'];
            $insert_array['status']  = $formpost['status'];
            $insert_array['created_date'] = date('Y-m-d H:i:s');
            $this->safety_data_model->add_category($insert_array);

            redirect(base_url('manage_admin/safety_data_sheet/category_management'));
        }
    }

    public function add_safety_sheet(){
        $redirect_url       = 'manage_admin';
        $function_name      = 'add_safety_sheet';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Safety Data Sheet - Category Management";
        $config = array(
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);
        $safety_category = $this->safety_data_model->get_all_category(1);
        $this->data['safety_category'] = $safety_category;
        if ($this->form_validation->run() == FALSE) {
            $this->render('manage_admin/safety_data_sheet/add_new_sheet');
        } else{
            $formpost = $this->input->post(NULL, TRUE);
            // print_r($formpost);
            // die();
            $insert_array = array();
            $insert_array['title']   = $formpost['title'];
            $insert_array['notes']   = $formpost['notes'];
            $insert_array['status']  = 1;
            $pre_id                  = $formpost['pre_id'];
            $cat_sids                = $formpost['cat_sid'];

            if($pre_id!=0){
                $this->safety_data_model->edit_safety_data_sheet($pre_id,$insert_array);
            } else {
                $insert_array['created_date']  = date('Y-m-d H:i:s');
                $pre_id = $this->safety_data_model->add_safety_data_sheet($insert_array);
            }
            foreach($cat_sids as $sid){
                $data = array('sheet_sid' => $pre_id, 'category_sid' => $sid);
                $this->safety_data_model->sheet_to_category($data);
            }
            redirect(base_url('manage_admin/safety_data_sheet'));
        }
    }

    public function edit_category($sid){
        $redirect_url    = 'manage_admin';
        $function_name   = 'edit_safety_category';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Safety Data Sheet - Category Management";
        $config = array(
            array(
                'field' => 'category_name',
                'label' => 'Category Name',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);

        $category = $this->safety_data_model->get_category($sid);
        $this->data['category'] = $category[0];
        if ($this->form_validation->run() == FALSE) {
            $this->render('manage_admin/safety_data_sheet/add_new_category');
        } else{
            $formpost = $this->input->post(NULL, TRUE);
            $update_array = array();
            $update_array['name'] = $formpost['category_name'];
            $update_array['sort_order']  = $formpost['sort_order'];
            $update_array['status']  = $formpost['status'];
            $this->safety_data_model->update_category($sid,$update_array);

            redirect(base_url('manage_admin/safety_data_sheet/category_management'));
        }
    }

    public function edit_safety_sheet($sid){

        $redirect_url       = 'manage_admin';
        $function_name      = 'edit_safety_sheet';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = "Safety Data Sheet - Category Management";
        $config = array(
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);
        $safety_category = $this->safety_data_model->get_all_category(1);
        $this->data['safety_category'] = $safety_category;
        $sheet_data = $this->safety_data_model->get_data_sheet_by_id($sid);
        $sheet_files = $this->safety_data_model->get_sheet_files($sid);
        $this->data['sheet_data'] = $sheet_data[0];
        $this->data['sheet_files'] = $sheet_files;
        if(sizeof($sheet_data)==0){
            $this->session->set_flashdata('message', 'No Data Found');
            redirect(base_url('manage_admin/safety_data_sheet'));
        }

        if ($this->form_validation->run() == FALSE) {
            $this->render('manage_admin/safety_data_sheet/add_new_sheet');
        } else{
            $formpost = $this->input->post(NULL, TRUE);
            $insert_array = array();
            $insert_array['title']   = $formpost['title'];
            $insert_array['notes']   = $formpost['notes'];
            $pre_id                  = $formpost['pre_id'];
            $cat_sids                = $formpost['cat_sid'];
            $this->safety_data_model->edit_safety_data_sheet($pre_id,$insert_array);

            foreach($cat_sids as $sid){
                if(!in_array($sid,$sheet_data[0]['categories'])){
                    $data = array('sheet_sid' => $pre_id, 'category_sid' => $sid);
                    $this->safety_data_model->sheet_to_category($data);
                }
            }
            foreach($sheet_data[0]['categories'] as $cat_sid){
                if(!in_array($cat_sid,$cat_sids)){
                    $this->safety_data_model->delete_sheet_to_category($cat_sid,$pre_id);
                }
            }
            redirect(base_url('manage_admin/safety_data_sheet'));
        }
    }

    public function category_management(){
        $redirect_url       = 'manage_admin';
        $function_name      = 'category_management';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title'] = "Safety Data Sheet - Category Management";

        $safety_category = $this->safety_data_model->get_all_category();
        $this->data['safety_category'] = $safety_category;
        $this->render('manage_admin/safety_data_sheet/category_management');
    }

    public function enable_disable_type($id){
        $data = array('status'=>$this->input->get('status'));
        $flag = $this->input->get('flag');
        if($flag == 'cat'){
            $this->safety_data_model->update_category($id,$data);
        } else{
            $this->safety_data_model->update_sheet($id,$data);
        }
        print_r(json_encode(array('message'=>'updated')));
    }

    public function ajax_handler() {
        $company_sid = $this->ion_auth->user()->row()->id;
        if($_SERVER['HTTP_HOST']=='localhost'){
            //$pictures = 'MyFile.asd';
            $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
        } else{
            $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
        }
        if (!empty($pictures) && $pictures != 'error') {

            if(isset($_POST['pre_id']) && $_POST['pre_id']!=0){
                $insert_id = $_POST['pre_id'];
            } else {
                $insert['status'] = 2;
                $insert['created_date'] = date('Y-m-d H:i:s');
                $insert_id = $this->safety_data_model->add_safety_data_sheet($insert);
            }

            $last_index_of_dot = strrpos($_FILES["docs"]["name"], '.') + 1;
            $file_ext = substr($_FILES["docs"]["name"], $last_index_of_dot, strlen($_FILES["docs"]["name"]) - $last_index_of_dot);
            $docs['file_name'] = $_FILES["docs"]["name"];
            $docs['type'] = $file_ext;
            $docs['file_code'] = $pictures;
            $docs['upload_date'] = date('Y-m-d H:i:s');
            $docs['sheet_sid'] = $insert_id;
            $inserted_file_id = $this->safety_data_model->safety_data_sheet_files($docs);

            $return_data = array();
            $return_data['sheet_sid'] = $insert_id;
            $return_data['file_sid'] = $inserted_file_id;
            $return_data['file_code'] = $pictures;

            echo json_encode($return_data);
        } else {
            echo 'error';
        }
    }

    public function delete_record_ajax(){
        $file_id = $this->input->post('id');
        $this->safety_data_model->delete_file($file_id);
        echo 'Deleted';
    }
}
