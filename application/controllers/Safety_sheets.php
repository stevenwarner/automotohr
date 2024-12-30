<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Safety_sheets extends Public_Controller {
    public function __construct() {
        parent::__construct();

        if($this->session->userdata('logged_in')) {
            require_once(APPPATH . 'libraries/aws/aws.php');
            $this->load->library("pagination");
            $this->load->model('safety_sheets_model');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    // Starting Of Blue Panel Functionality

    public function index() {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
//            check_access_permissions($security_details, 'dashboard', 'safety_sheet_portal');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $data['title']                                                      = 'Safety Data Sheets';
//            $default_types                                                      = $this->safety_sheets_model->get_all_categories_for_sheets();
            $company_types                                                      = $this->safety_sheets_model->get_all_categories_for_sheets($company_sid);
//            if(sizeof($company_types)>0){
                $data['types']                                                  = $company_types;
//            } else{
//                $data['types']                                                  = $default_types;
//            }
            $load_view                                                          = check_blue_panel_status(false, 'self');
            $data['load_view']                                                  = $load_view;
            $data['employee']                                                   = $data['session']['employer_detail'];
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_sheets($id){
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
//            check_access_permissions($security_details, 'dashboard', 'incident_reporting_system');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Related Data Sheet";
            $data['employee'] = $data["session"]["employer_detail"];
            $data['cat_id'] = $id;

            $data['category_sheets'] = $this->safety_sheets_model->fetch_sheets_to_category($id);

            if(sizeof($data['category_sheets'])==0 || $company_sid!=$data['category_sheets'][0]['company_sid']){
                $this->session->set_flashdata('message', '<b>Warning:</b> No Sheet found!');
                redirect(base_url('safety_sheets'),'refresh');
            }
            
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/view_details');
            $this->load->view('main/footer');
        }
        else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function examine($id,$cat_id){
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
//            check_access_permissions($security_details, 'dashboard', 'incident_reporting_system');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Examine Data Sheet";
            $data['employee'] = $data["session"]["employer_detail"];
            $data['cat_id'] = $cat_id;

            $data['sheets_details'] = $this->safety_sheets_model->fetch_sheet_details($id);
            if(sizeof($data['sheets_details'])==0 || $data['sheets_details'][0]['company_sid']!=$company_sid){
                $this->session->set_flashdata('message', '<b>Warning:</b> No Sheet found!');
                redirect(base_url('safety_sheets'),'refresh');
            }
            $png = array();
            $pdf = array();
            foreach($data['sheets_details'] as $key => $file){
                if($file['type'] == 'png' || $file['type'] == 'PNG' || $file['type'] == 'jpg' || $file['type'] == 'JPG' || $file['type'] == 'jpeg' || $file['type'] == 'JPEG' || $file['type'] == 'jpe' || $file['type'] == 'JPE'){
                    $png[$key]['file_code'] = $file['file_code'];
                    $png[$key]['file_name'] = $file['file_name'];
                    $png[$key]['type']      = $file['type'];
                } elseif($file['type'] == 'pdf' || $file['type'] == 'PDF'){
                    $pdf[$key]['file_code'] = $file['file_code'];
                    $pdf[$key]['file_name'] = $file['file_name'];
                    $pdf[$key]['type']      = $file['type'];
                }
            }
            $data['pdf']    =   $pdf;
            $data['png']    =   $png;
            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/examine');
            $this->load->view('main/footer');
        }
        else {
            redirect(base_url('login'), "refresh");
        }
    }

    // Ending Of Blue Panel Functionality

    // Starting Of Company Level Safety Sheet Management

    public function manage_safety_sheets(){
        if ($this->session->userdata('logged_in')) {

            if (!checkIfAppIsEnabled('safetysheets')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'safety_sheet_portal');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Manage Safety Sheets';
            $data['load_view'] = false;
            $data['flag'] = true;
            $data['default_flag'] = false;
            $safety_category = $this->safety_sheets_model->get_all_category($company_sid);

            $data['safety_category'] = $safety_category;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/category_management');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_company_sheets($cat_sid){
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];

            check_access_permissions($security_details, 'dashboard', 'view_sheets');
            $data['title'] = "Manage Safety Sheets";
            $sheet_data = $this->safety_sheets_model->get_all_safety_data_company($company_sid,$cat_sid);
            $cat_name = $this->safety_sheets_model->get_category($cat_sid);
            $data['sheet_data'] = $sheet_data;
            $data['cat_name'] = $cat_name[0]['name'];
            $data['cat_sid'] = $cat_sid;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/safety_sheets_list');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function manage_default_categories(){
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            check_access_permissions($security_details, 'dashboard', 'add_default_category');
            $data['title'] = 'Manage Safety Sheets';
            $data['load_view'] = false;
            $data['flag'] = false;
            $data['default_flag'] = true;
            $safety_category = $this->safety_sheets_model->get_all_category();

            $data['safety_category'] = $safety_category;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/category_management');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_to_company($category_sid = 0, $name = ''){
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'System Category';
            $check = $this->safety_sheets_model->check_unique_with_name($company_sid,rawurldecode($name),'cat');
            if($check == 0){
                $category = $this->safety_sheets_model->get_category($category_sid);
                unset($category[0]['sid']);
                $category[0]['company_sid'] = $company_sid;
                $category[0]['status']  = 1;
                $category[0]['created_date'] = date('Y-m-d H:i:s');
                $insert_array = $category[0];
                $this->safety_sheets_model->add_category($insert_array);
            } else{
                $this->session->set_flashdata('message', '<b>Warning:</b> Already Added To Company!');
                redirect(base_url('safety_sheets/manage_default_categories'));
            }
            $this->session->set_flashdata('message', '<b>Success:</b> Successfully Added To Company!');
            redirect(base_url('safety_sheets/manage_safety_sheets'));


        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function clone_to_company($sheet_sid = 0, $name = ''){
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['load_view'] = false;
        $data['clone_flag'] = true;
        $data['title'] = "Default Safety Data Sheet - Safety Hazard";
        $config = array(
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);
        $check = $this->safety_sheets_model->check_unique_with_name($company_sid, rawurldecode($name),'sheet');
        if($check > 0){
            $this->session->set_flashdata('message', '<b>Warning:</b>Already Added To Company!');
            redirect(base_url('safety_sheets/manage_default_sheets'));
        }
        $safety_category = $this->safety_sheets_model->get_all_categories_for_sheets($company_sid);
        $data['safety_category'] = $safety_category;


        $sheet_data = $this->safety_sheets_model->get_data_sheet_for_clone($sheet_sid);
        $sheet_files = $this->safety_sheets_model->get_sheet_files($sheet_sid);
        $data['sheet_data'] = $sheet_data[0];
//        $data['sheet_files'] = $sheet_files;
        if(sizeof($sheet_data)==0){
            $this->session->set_flashdata('message', 'No Data Found');
            redirect(base_url('safety_sheets'),'refresh');
        }
        $current_employees = $this->safety_sheets_model->GetAllUsers($company_sid);
        $data['current_employees'] = $current_employees;
        $data['employeesArray'] = array();
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/add_new_sheet');
            $this->load->view('main/footer');
        } else{
            $formpost = $this->input->post(NULL, TRUE);
            $insert_array = array();
            $insert_array['title']   = $formpost['title'];
            $insert_array['notes']   = $formpost['notes'];
            $insert_array['status']  = 1;
            $insert_array['visible_to'] = $formpost['employees'] != '' ? serialize($formpost['employees']) : 'all';
            $pre_id                  = $formpost['pre_id'];
            $cat_sids                = $formpost['cat_sid'];
            if($pre_id!=0){
                $this->safety_sheets_model->edit_safety_data_sheet($pre_id,$insert_array);
            } else {
                $insert_array['created_date']  = date('Y-m-d H:i:s');
                $insert_array['company_sid']  = $company_sid;
                $pre_id = $this->safety_sheets_model->add_safety_data_sheet($insert_array);
            }
            foreach($cat_sids as $sid){
                $data = array('sheet_sid' => $pre_id, 'category_sid' => $sid);
                $this->safety_sheets_model->sheet_to_category($data);
            }
            if(sizeof($sheet_files)>0){
                foreach($sheet_files as $file){
                    unset($file['sid']);
                    $file['sheet_sid'] = $pre_id;
                    $file['upload_date'] = date('Y-m-d H:i:s');
                    $this->safety_sheets_model->safety_data_sheet_files($file);
                }
            }
            redirect(base_url('safety_sheets/manage_safety_sheets'));
        }
    }

    public function add_new_category(){
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'add_safety_category');
        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['load_view'] = false;
        $data['title'] = "Safety Data Sheet - Category Management";
        $config = array(
            array(
                'field' => 'category_name',
                'label' => 'Category Name',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);
//        $this->form_validation->set_message('unique','Category name should be unique');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/add_new_category');
            $this->load->view('main/footer');
        } else{
            $formpost = $this->input->post(NULL, TRUE);
            $insert_array = array();
            $insert_array['name'] = $formpost['category_name'];
            $insert_array['sort_order']  = $formpost['sort_order'];
            $insert_array['status']  = $formpost['status'];
            $insert_array['created_date'] = date('Y-m-d H:i:s');
            $insert_array['company_sid']  = $company_sid;
            $check = $this->safety_sheets_model->check_unique_with_name($company_sid,$formpost['category_name'],'cat');
            if($check > 0){
                $this->session->set_flashdata('message', '<b>Warning:</b>Already Exists!');
                redirect(base_url('safety_sheets/manage_safety_sheets'));
            }
            $this->safety_sheets_model->add_category($insert_array);

            redirect(base_url('safety_sheets/manage_safety_sheets'));
        }
    }

    public function edit_category($sid){
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'edit_safety_category');
        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['load_view'] = false;
        $data['title'] = "Safety Data Sheet - Category Management";
        $config = array(
            array(
                'field' => 'category_name',
                'label' => 'Category Name',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);

        $category = $this->safety_sheets_model->get_category($sid);
        $data['category'] = $category[0];
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/add_new_category');
            $this->load->view('main/footer');
        } else{
            $formpost = $this->input->post(NULL, TRUE);
            $update_array = array();
            $update_array['name'] = $formpost['category_name'];
            $update_array['sort_order']  = $formpost['sort_order'];
            $update_array['status']  = $formpost['status'];
            $check = $this->safety_sheets_model->check_unique_other_name($company_sid,$formpost['category_name'],'cat',$sid);
            if($check > 0){
                $this->session->set_flashdata('message', '<b>Update Unsuccessful:</b> Category Already Exist!');
                redirect(base_url('safety_sheets/manage_safety_sheets'));
            }
            $this->safety_sheets_model->update_category($sid,$update_array);

            redirect(base_url('safety_sheets/manage_safety_sheets'));
        }
    }

    public function enable_disable_type($id){
        $data = array('status'=>$this->input->get('status'));
        $flag = $this->input->get('flag');
        if($flag == 'cat'){
            $this->safety_sheets_model->update_category($id,$data);
        } else{
            $this->safety_sheets_model->update_sheet($id,$data);
        }
        print_r(json_encode(array('message'=>'updated')));
    }

    public function safety_data_sheets(){
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];

            $data['title'] = "Safety Data Sheet - Safety Hazard";

            $sheet_data = $this->safety_sheets_model->get_all_safety_data($company_sid);
            $data['sheet_data'] = $sheet_data;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/safety_sheets_list');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function manage_default_sheets(){
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            check_access_permissions($security_details, 'dashboard', 'add_default_sheet');
            $data['title'] = "Manage Safety Sheets";
            $data['cat_name'] = 'Default';
            $sheet_data = $this->safety_sheets_model->get_all_safety_data(0);
            $data['sheet_data'] = $sheet_data;
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/safety_sheets_list');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_new_sheet($cat_sid = NULL){
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        $company_sid  = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['load_view'] = false;
        $data['cat_sid'] = $cat_sid;
        $data['title'] = "Safety Data Sheet - Safety Hazard";
        check_access_permissions($security_details, 'dashboard', 'add_safety_sheet');
        $config = array(
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);
//        $this->form_validation->set_message('unique','Title Should be unique');
        $safety_category = $this->safety_sheets_model->get_all_categories_company_specific($company_sid);
        $data['safety_category'] = $safety_category;
        $current_employees = $this->safety_sheets_model->GetAllUsers($company_sid);
        $data['current_employees'] = $current_employees;
        $data['employeesArray'] = array();
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/add_new_sheet');
            $this->load->view('main/footer');
        } else{
            $formpost = $this->input->post(NULL, TRUE);
            $insert_array = array();
            $insert_array['title']      = $formpost['title'];
            $insert_array['notes']      = $formpost['notes'];
            $insert_array['status']     = 1;
            $insert_array['visible_to'] = isset($formpost['employees']) && $formpost['employees'] != '' ? serialize($formpost['employees']) : 'all';
            $pre_id                     = $formpost['pre_id'];
            $cat_sids                   = $formpost['cat_sid'];
            $check = $this->safety_sheets_model->check_unique_with_name($company_sid,$formpost['title'],'sheet');
            
            if($check > 0){
                $this->session->set_flashdata('message', '<b>Warning:</b>Already Exists!');
//                redirect(base_url('safety_sheets/view_company_sheets/'.$cat_sid));
                 redirect(base_url('safety_sheets/manage_safety_sheets/'));
            }
            
            if($pre_id!=0){
                $this->safety_sheets_model->edit_safety_data_sheet($pre_id,$insert_array);
            } else {
                $insert_array['created_date']  = date('Y-m-d H:i:s');
                $insert_array['company_sid']  = $company_sid;
                $pre_id = $this->safety_sheets_model->add_safety_data_sheet($insert_array);
            }
            
            foreach($cat_sids as $sid) {
                $cat_sid = $sid;
                $data = array('sheet_sid' => $pre_id, 'category_sid' => $sid);
                $this->safety_sheets_model->sheet_to_category($data);
            }
            
            $this->session->set_flashdata('message', '<b>Success:</b> Safety Datasheet added successfully!');
            
            if($cat_sid == NULL) {
                redirect(base_url('safety_sheets/manage_safety_sheets/'));
            } else {
                redirect(base_url('safety_sheets/view_company_sheets/'.$cat_sid));
            }
        }
    }

    public function edit_safety_sheet($cat_sid, $sid){

        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['load_view'] = false;
        $data['cat_sid'] = $cat_sid;
        $data['title'] = "Safety Data Sheet - Safety Hazard";
        check_access_permissions($security_details, 'dashboard', 'edit_safety_sheet');
        $config = array(
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'xss_clean|trim|required'
            )
        );
        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);
        $safety_category = $this->safety_sheets_model->get_all_categories_for_sheets($company_sid);
        $data['safety_category'] = $safety_category;
        $current_employees = $this->safety_sheets_model->GetAllUsers($company_sid);
        $data['current_employees'] = $current_employees;
        $sheet_data = $this->safety_sheets_model->get_data_sheet_by_id($sid);
        if(empty($sheet_data)){
            $this->session->set_flashdata('message', '<b>Warning:</b> Sheet Not found!');
            redirect(base_url('safety_sheets/manage_safety_sheets'),'refresh');
        }
        if($sheet_data[0]['company_sid'] != $company_sid){
            $this->session->set_flashdata('message', '<b>Warning:</b> Sheet Not found!');
            redirect(base_url('safety_sheets/manage_safety_sheets'),'refresh');
        }
        $sheet_files = $this->safety_sheets_model->get_sheet_files($sid);
        $data['sheet_data'] = $sheet_data[0];
        $data['employeesArray'] = $sheet_data[0]['visible_to'] != 'all' ? unserialize($sheet_data[0]['visible_to']) : array();
        $data['sheet_files'] = $sheet_files;
        if(sizeof($sheet_data)==0){
            $this->session->set_flashdata('message', 'No Data Found');
            redirect(base_url('safety_sheets'),'refresh');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/safety_sheets/add_new_sheet');
            $this->load->view('main/footer');
        } else{
            $formpost = $this->input->post(NULL, TRUE);
            $insert_array = array();
            $insert_array['title']      = $formpost['title'];
            $insert_array['notes']      = $formpost['notes'];
            $insert_array['visible_to'] = serialize($formpost['employees']);
            $pre_id                     = $formpost['pre_id'];
            $cat_sids                   = $formpost['cat_sid'];
            $check = $this->safety_sheets_model->check_unique_other_name($company_sid,$formpost['title'],'sheet',$sid);
            if($check > 0){
                $this->session->set_flashdata('message', '<b>Update Unsuccessful:</b> Sheet Already Exist!');
                redirect(base_url('safety_sheets/safety_data_sheets'));
            }
            $this->safety_sheets_model->edit_safety_data_sheet($pre_id,$insert_array);

            foreach($cat_sids as $sid){
                if(!in_array($sid,$sheet_data[0]['categories'])){
                    $data = array('sheet_sid' => $pre_id, 'category_sid' => $sid);
                    $this->safety_sheets_model->sheet_to_category($data);
                }
            }
            foreach($sheet_data[0]['categories'] as $cat_sid){
                if(!in_array($cat_sid,$cat_sids)){
                    $this->safety_sheets_model->delete_sheet_to_category($cat_sid,$pre_id);
                }
            }
            redirect(base_url('safety_sheets/view_company_sheets/'.$cat_sid));
        }
    }

    public function ajax_handler() {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];
        if($_SERVER['HTTP_HOST']=='localhost'){
//            $pictures = '0057-profile_picture-58-5Et.jpg';
//            $pictures = '0057-testing_uploaded_doc-58-AAH.docx';
            $pictures = '0057-test_latest_uploaded_document-58-Yo2.pdf';
        } else{
            $pictures = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);
        }
        if (!empty($pictures) && $pictures != 'error') {

            if(isset($_POST['pre_id']) && $_POST['pre_id']!=0){
                $insert_id = $_POST['pre_id'];
            } else {
                $insert['status'] = 2;
                $insert['company_sid'] = $company_sid;
                $insert['created_date'] = date('Y-m-d H:i:s');
                $insert_id = $this->safety_sheets_model->add_safety_data_sheet($insert);
            }

            $last_index_of_dot = strrpos($_FILES["docs"]["name"], '.') + 1;
            $file_ext = substr($_FILES["docs"]["name"], $last_index_of_dot, strlen($_FILES["docs"]["name"]) - $last_index_of_dot);
            $docs['file_name'] = $_FILES["docs"]["name"];
            $docs['type'] = $file_ext;
            $docs['file_code'] = $pictures;
            $docs['upload_date'] = date('Y-m-d H:i:s');
            $docs['sheet_sid'] = $insert_id;
            $this->safety_sheets_model->safety_data_sheet_files($docs);
            echo $insert_id;
        } else {
            echo 'error';
        }
    }

    public function delete_record_ajax(){
        $file_id = $this->input->post('id');
        $this->safety_sheets_model->delete_file($file_id);
        echo 'Deleted';
    }

    public function delete_sheet_ajax(){
        $sheet_id = $this->input->post('id');
        $cat_id = $this->input->post('cat_id');
        $this->safety_sheets_model->delete_sheet($cat_id,$sheet_id);
        echo 'Deleted';
    }
}