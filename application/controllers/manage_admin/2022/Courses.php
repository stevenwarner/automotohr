<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/2022/Courses_model', 'couses_model');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'courses_index';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $this->data['page_title'] = 'Default Courses';
        $companies = $this->couses_model->get_all_companies();
        $this->data['standard_companies'] = $companies;
        //
        $courses = array();
        $courses[0]['sid'] = 1; 
        $courses[0]['title'] = "Work Place Eathic"; 
        $courses[0]['status'] = 1; 
        $courses[1]['sid'] = 2; 
        $courses[1]['title'] = "Time off Policies"; 
        $courses[1]['status'] = 1;
        $courses[2]['sid'] = 3; 
        $courses[2]['title'] = "Basic Work Enviourment"; 
        $courses[2]['status'] = 0;
        $this->data['courses'] = $courses;
        //
        $this->render('manage_admin/courses/index');
    }

    public function add() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'add';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        //
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
        $this->form_validation->set_rules('job_title', 'Job Title', 'required|trim|xss_clean');
        $this->form_validation->set_rules('type', 'Course Type', 'trim|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            $this->data['page_title'] = 'Add Course';
            $job_titles = $this->couses_model->GetAllActiveTemplates();
            $this->data['job_titles'] = $job_titles;
            $this->render('manage_admin/courses/add');
        } else {

        }
    }

    public function edit($sid) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'courses_index';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $this->data['page_title'] = 'Default Courses';
        $companies = $this->couses_model->get_all_companies();
        $this->data['standard_companies'] = $companies;
        //
        $courses = array();
        $courses[0]['sid'] = 1; 
        $courses[0]['title'] = "Work Place Eathic"; 
        $courses[0]['status'] = 1; 
        $courses[1]['sid'] = 2; 
        $courses[1]['title'] = "Time off Policies"; 
        $courses[1]['status'] = 1;
        $courses[2]['sid'] = 3; 
        $courses[2]['title'] = "Basic Work Enviourment"; 
        $courses[2]['status'] = 0;
        $this->data['courses'] = $courses;
        //
        $this->render('manage_admin/courses/index');
    }

    public function preview($sid) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'courses_index';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $this->data['page_title'] = 'Default Courses';
        $companies = $this->couses_model->get_all_companies();
        $this->data['standard_companies'] = $companies;
        //
        $courses = array();
        $courses[0]['sid'] = 1; 
        $courses[0]['title'] = "Work Place Eathic"; 
        $courses[0]['status'] = 1; 
        $courses[1]['sid'] = 2; 
        $courses[1]['title'] = "Time off Policies"; 
        $courses[1]['status'] = 1;
        $courses[2]['sid'] = 3; 
        $courses[2]['title'] = "Basic Work Enviourment"; 
        $courses[2]['status'] = 0;
        $this->data['courses'] = $courses;
        //
        $this->render('manage_admin/courses/index');
    }

    public function manage($sid) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'manage';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $this->data['page_title'] = 'Manage Courses';
        //
        $courses = array();
        $courses[0]['sid'] = 1; 
        $courses[0]['title'] = "Work Place Eathic"; 
        $courses[0]['status'] = 1; 
        $courses[1]['sid'] = 2; 
        $courses[1]['title'] = "Time off Policies"; 
        $courses[1]['status'] = 1;
        $this->data['courses'] = $courses;
        //
        $this->render('manage_admin/courses/manage');
    }
}
