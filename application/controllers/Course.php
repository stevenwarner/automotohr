<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends Public_Controller {
    private $limit;
    private $list_size;
    public function __construct() {
        parent::__construct();

        $this->pargs = [];
        // Load helper
        $this->load->helper('course');
        // Load modal
        $this->load->model('course_model');
        // Load user agent
        $this->load->library('user_agent');
        //
        $this->mp = $this->agent->is_mobile() ? '' : '';
        //
        $this->pp = 'course/theme2/';
        //
        $this->pargs['pp'] = $this->pp;
        //
        $this->resp = [
            'Status' => FALSE,
            'Redirect' => TRUE,
            'Response' => 'Invalid request'
        ];
        //
        $this->header = 'main/header';
        $this->footer = 'main/footer';
    }

    function index(){
        if ($this->session->userdata('logged_in')) {
            $this->pargs['session'] = $this->session->userdata('logged_in');
            $security_sid = $this->pargs['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $this->pargs['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'online_video');
            $company_sid = $this->pargs['session']['company_detail']['sid'];
            $employer_sid = $this->pargs['session']['employer_detail']['sid'];
            $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
            $this->pargs['load_view'] = $this->pargs['session']['company_detail']['ems_status'];
            $this->pargs['company_sid'] = $company_sid;
            $this->pargs['employer_sid'] = $employer_sid;
            $this->pargs['title'] = 'Learning Management System';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');
            //
            $employees = $this->course_model->get_all_employees($company_sid);
            $this->pargs['employeesList'] = $employees;
            $departments = $this->course_model->getActiveDepartments($company_sid);
            $this->pargs['departments'] = $departments;
            $teams = $this->course_model->getTeams($this->pargs['company_sid'], $departments);
            $this->pargs['teams'] = $teams;

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            $course_listing = array();
            $course_listing[0]['sid'] = 1;
            $course_listing[0]['title'] = 'EBS System';
            $course_listing[0]['status'] = 1;
            $course_listing[0]['employees'] = 130;
            $course_listing[0]['progress'] = '30%';
            $course_listing[1]['sid'] = 2;
            $course_listing[1]['title'] = 'ABS System';
            $course_listing[1]['status'] = 1;
            $course_listing[1]['employees'] = 130;
            $course_listing[1]['progress'] = '50%';
            $course_listing[2]['sid'] = 2;
            $course_listing[2]['title'] = 'ABS System';
            $course_listing[2]['status'] = 1;
            $course_listing[2]['employees'] = 130;
            $course_listing[2]['progress'] = '50%';
            $this->pargs['course_listing'] = $course_listing;
            $this->pargs['links'] = '';
            $this->pargs['active_tab'] = 'dashboard';
            $this->load->view($this->header, $this->pargs);
            // $this->load->view($this->header, $data);
            $this->load->view("{$this->pp}header");
            $this->load->view("{$this->pp}dashboard");
            $this->load->view("{$this->pp}footer");
            $this->load->view($this->footer);
        } else {
            redirect(base_url('login'), "refresh");
        }
        
        //
        
    }


    function index1 () {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'online_video');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['title'] = 'Learning Management System';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');
            //
            $employees = $this->course_model->get_all_employees($company_sid);
            $data['employeesList'] = $employees;
            $departments = $this->course_model->getActiveDepartments($company_sid);
            $data['departments'] = $departments;
            $teams = $this->course_model->getTeams($data['company_sid'], $departments);
            $data['teams'] = $teams;

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            $course_listing = array();
            $course_listing[0]['sid'] = 1;
            $course_listing[0]['title'] = 'EBS System';
            $course_listing[0]['status'] = 1;
            $course_listing[0]['employees'] = 130;
            $course_listing[0]['progress'] = '30%';
            $course_listing[1]['sid'] = 2;
            $course_listing[1]['title'] = 'ABS System';
            $course_listing[1]['status'] = 1;
            $course_listing[1]['employees'] = 130;
            $course_listing[1]['progress'] = '50%';
            $course_listing[2]['sid'] = 2;
            $course_listing[2]['title'] = 'ABS System';
            $course_listing[2]['status'] = 1;
            $course_listing[2]['employees'] = 130;
            $course_listing[2]['progress'] = '50%';
            $data['course_listing'] = $course_listing;
            $data['links'] = '';
            $data['load_view'] = '';
            $this->load->view('main/header', $data);
            $this->load->view('course/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function handler () {
        // Check for ajax request
        if (!$this->input->is_ajax_request()) $this->resp();
        //
        $post = $this->input->post(NULL, TRUE);
        // Check post size and action
        if (!sizeof($post) || !isset($post['action'])) $this->resp();
        if (!isset($post['company_sid']) || $post['company_sid'] == '') $this->resp();
        if (!isset($post['employer_sid']) || $post['employer_sid'] == '') $this->resp();
        //
        $post['public'] = 0;
        // For expired session
        if ($post['public'] == 0 && empty($this->session->userdata('logged_in'))) {
            $this->resp['Redirect'] = true;
            $this->resp['Response'] = 'Your login session has expired.';
            $this->resp['Code'] = 'SESSIONEXPIRED';
            $this->resp();
        }
        //
        $this->resp['Redirect'] = FALSE;
        //
        switch (strtolower($post['action'])) {
            // Fetch company all policy types
            case 'add_basic_course_info':
                //
                echo '<pre>';
                print_r($post);
                die();
                
                //
                $this->resp['Data'] = $types;
                $this->resp['Status'] = true;
                $this->resp['Count'] = 0;
                $this->resp['Limit'] = 100;
                $this->resp['Response'] = 'Proceed.';
                $this->resp();
            break;

            case 'get_course_info':
                $course_info = array();
                $course_info['sid'] = 1;
                $course_info['title'] = 'EBS System';
                $course_info['course_start_date'] = date("m-d-Y", strtotime('2021-09-23'));
                $course_info['is_course_expired'] = 'no';
                $course_info['course_expired_day'] = 5;
                $course_info['course_expired_type'] = 'day';
                $course_info['course_status'] = 0;
                $course_info['employees'] = 130;
                $course_info['progress'] = '30%';

                $this->resp['Data'] = $course_info;
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'Proceed.';
                $this->resp();
            break;

            case 'add_attachment':
                $company_sid = $post['company_sid'];
                $attached_by = $post['employer_sid'];
                $file_title = $post['upload_title'];
                $file_extension = $post['file_ext'];
                $attached_date = date('Y-m-d H:i:s');
                $pdf_doc = upload_file_to_aws('docs', $company_sid, 'docs', '', AWS_S3_BUCKET_NAME);

                if (!empty($pdf_doc) && $pdf_doc != 'error') {
                    $data_to_insert = array();

                    $data_to_insert['company_sid'] = $company_sid;
                    $data_to_insert['attached_by'] = $attached_by;
                    $data_to_insert['upload_file_title'] = $file_title;
                    $data_to_insert['upload_file_name'] = $pdf_doc;
                    $data_to_insert['upload_file_extension'] = $file_extension;
                    $data_to_insert['attached_date'] = $attached_date;
                    $data_to_insert['status'] = 1;
                    $document_sid = $this->course_model->insert_attached_document($data_to_insert);
                    //
                    $data_to_return = array();
                    $data_to_return['document_sid'] = $document_sid;
                    $data_to_return['active_btn'] = 'active-btn-'.$document_sid;
                    $data_to_return['upload_file_title'] = $file_title;
                    $data_to_return['video_sid'] = $video_sid;
                    $data_to_return['attached_date'] = my_date_format($attached_date);
                    $data_to_return['delete_url'] = base_url('learning_center/delete_attachment_document/' . $document_sid . '/' . $video_sid);
                    $data_to_return['update_url'] = base_url('learning_center/update_supporting_document/' . $document_sid . '/' . $video_sid);
                    
                    header('Content-type: application/json');
                    echo json_encode($data_to_return);
                    exit(0);
                } else {
                    echo 'error';
                }
            break;
        }    
    }

    function courses () {
        if ($this->session->userdata('logged_in')) {
            $this->pargs['session'] = $this->session->userdata('logged_in');
            $security_sid = $this->pargs['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $this->pargs['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'online_video');
            $company_sid = $this->pargs['session']['company_detail']['sid'];
            $employer_sid = $this->pargs['session']['employer_detail']['sid'];
            $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
            $this->pargs['load_view'] = $this->pargs['session']['company_detail']['ems_status'];
            $this->pargs['company_sid'] = $company_sid;
            $this->pargs['employer_sid'] = $employer_sid;
            $this->pargs['title'] = 'Learning Management System';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');
            //
            $employees = $this->course_model->get_all_employees($company_sid);
            $this->pargs['employeesList'] = $employees;
            $this->pargs['company_employees'] = $employees;
            $departments = $this->course_model->getActiveDepartments($company_sid);
            $this->pargs['departments'] = $departments;
            $teams = $this->course_model->getTeams($this->pargs['company_sid'], $departments);
            $this->pargs['teams'] = $teams;

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            $type = $this->input->get('type', true) ? $this->input->get('type', true) : 'active';
            //
            $this->pargs['type'] = $type;

            $course_listing = array();
            $course_listing[0]['sid'] = 1;
            $course_listing[0]['title'] = 'EBS System';
            $course_listing[0]['status'] = 'started';
            $course_listing[0]['employees'] = 130;
            $course_listing[0]['progress'] = '30%';
            $course_listing[0]['is_draft'] = 0;
            $course_listing[0]['course_start_date'] = '2021-09-02';
            $course_listing[0]['is_archived'] = 0;
            $course_listing[1]['sid'] = 2;
            $course_listing[1]['title'] = 'ABS System';
            $course_listing[1]['status'] = 'pending';
            $course_listing[1]['employees'] = 130;
            $course_listing[1]['progress'] = '50%';
            $course_listing[1]['is_draft'] = 0;
            $course_listing[1]['course_start_date'] = '2021-09-22';
            $course_listing[1]['is_archived'] = 0;
            $course_listing[2]['sid'] = 2;
            $course_listing[2]['title'] = 'ABS System';
            $course_listing[2]['status'] = 'started';
            $course_listing[2]['employees'] = 130;
            $course_listing[2]['progress'] = '50%';
            $course_listing[2]['is_draft'] = 0;
            $course_listing[2]['course_start_date'] = '2021-10-08';
            $course_listing[2]['is_archived'] = 0;
            //
            $this->pargs['course_listing'] = $course_listing;
            $this->pargs['links'] = '';
            $this->pargs['active_tab'] = 'courses';
            //
            $this->load->view($this->header, $this->pargs);
            // $this->load->view($this->header, $data);
            $this->load->view("{$this->pp}header");
            $this->load->view("{$this->pp}courses");
            $this->load->view("{$this->pp}footer");
            $this->load->view($this->footer);
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function myCourses () {
        if ($this->session->userdata('logged_in')) {
            $this->pargs['session'] = $this->session->userdata('logged_in');
            $security_sid = $this->pargs['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $this->pargs['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'online_video');
            $company_sid = $this->pargs['session']['company_detail']['sid'];
            $employer_sid = $this->pargs['session']['employer_detail']['sid'];
            $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
            $this->pargs['load_view'] = $this->pargs['session']['company_detail']['ems_status'];
            $this->pargs['company_sid'] = $company_sid;
            $this->pargs['employer_sid'] = $employer_sid;
            $this->pargs['title'] = 'Learning Management System';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');
            //
            $employees = $this->course_model->get_all_employees($company_sid);
            $this->pargs['employeesList'] = $employees;
            $departments = $this->course_model->getActiveDepartments($company_sid);
            $this->pargs['departments'] = $departments;
            $teams = $this->course_model->getTeams($this->pargs['company_sid'], $departments);
            $this->pargs['teams'] = $teams;

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }

            $type = $this->input->get('type', true) ? $this->input->get('type', true) : 'active';
            //
            $this->pargs['type'] = $type;

            $course_listing = array();
            $course_listing[0]['sid'] = 1;
            $course_listing[0]['title'] = 'EBS System';
            $course_listing[0]['status'] = 'started';
            $course_listing[0]['employees'] = 130;
            $course_listing[0]['progress'] = '30%';
            $course_listing[0]['is_draft'] = 0;
            $course_listing[0]['course_start_date'] = '2021-09-02';
            $course_listing[0]['course_end_date'] = '2021-09-12';
            $course_listing[0]['is_archived'] = 0;
            $course_listing[1]['sid'] = 2;
            $course_listing[1]['title'] = 'ABS System';
            $course_listing[1]['status'] = 'pending';
            $course_listing[1]['employees'] = 130;
            $course_listing[1]['progress'] = '50%';
            $course_listing[1]['is_draft'] = 0;
            $course_listing[1]['course_start_date'] = '2021-09-22';
            $course_listing[1]['course_end_date'] = '2021-09-25';
            $course_listing[1]['is_archived'] = 0;
            $course_listing[2]['sid'] = 2;
            $course_listing[2]['title'] = 'ABS System';
            $course_listing[2]['status'] = 'started';
            $course_listing[2]['employees'] = 130;
            $course_listing[2]['progress'] = '50%';
            $course_listing[2]['is_draft'] = 0;
            $course_listing[2]['course_start_date'] = '2021-10-08';
            $course_listing[2]['course_end_date'] = '2021-10-18';
            $course_listing[2]['is_archived'] = 0;
            //
            $this->pargs['course_listing'] = $course_listing;
            $this->pargs['links'] = '';
            $this->pargs['active_tab'] = 'my_courses';
            //
            $this->load->view($this->header, $this->pargs);
            // $this->load->view($this->header, $data);
            $this->load->view("{$this->pp}header");
            $this->load->view("{$this->pp}my_courses/courses");
            $this->load->view("{$this->pp}footer");
            $this->load->view($this->footer);
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function settings () {
        if ($this->session->userdata('logged_in')) {
            $this->pargs['session'] = $this->session->userdata('logged_in');
            $security_sid = $this->pargs['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $this->pargs['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'online_video');
            $company_sid = $this->pargs['session']['company_detail']['sid'];
            $employer_sid = $this->pargs['session']['employer_detail']['sid'];
            $this->pargs['load_view'] = $this->pargs['session']['company_detail']['ems_status'];
            //
            $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
            $this->pargs['company_sid'] = $company_sid;
            $this->pargs['employer_sid'] = $employer_sid;
            // Set title
            $this->pargs['title'] = 'Learning Management System';
            // Set employee information for the blue screen
            $employees = $this->course_model->get_all_employees($company_sid);
            $this->pargs['employeesList'] = $employees;
            //
            $departments = $this->course_model->getActiveDepartments($company_sid);
            $this->pargs['departments'] = $departments;
            //
            $teams = $this->course_model->getTeams($this->pargs['company_sid'], $departments);
            $this->pargs['teams'] = $teams;
            $this->pargs['hide_employer_section'] = 1;
            // Get Settings
            $settings['roles'] = getRoles();
            $settings['departments'] = $departments;
            $settings['teams'] = $teams;
            $settings['employees'] = $employees;
            $this->pargs['settings'] = $settings;
            //
            $this->pargs['company_dt'] = $this->course_model->GetCompanyDepartmentAndTeams($this->pargs['company_sid']);
            // Set company employees
            $this->pargs['company_employees'] = $this->course_model->GetAllEmployees($this->pargs['company_sid']);

            // 
            $this->pargs['active_tab'] = 'setting';
            //
            $this->load->view($this->header, $this->pargs);
            $this->load->view("{$this->pp}header");
            $this->load->view("{$this->pp}settings");
            $this->load->view("{$this->pp}footer");
            $this->load->view($this->footer);
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function view_course ($course_id) {
        if ($this->session->userdata('logged_in')) {
            $this->pargs['session'] = $this->session->userdata('logged_in');
            $security_sid = $this->pargs['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $this->pargs['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'online_video');
            $company_sid = $this->pargs['session']['company_detail']['sid'];
            $employer_sid = $this->pargs['session']['employer_detail']['sid'];
            $this->pargs['load_view'] = $this->pargs['session']['company_detail']['ems_status'];
            //
            $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
            $this->pargs['company_sid'] = $company_sid;
            $this->pargs['employer_sid'] = $employer_sid;
            // Set title
            $this->pargs['title'] = 'Learning Management System';
            // Set employee information for the blue screen
            $employees = $this->course_model->get_all_employees($company_sid);
            $this->pargs['employeesList'] = $employees;
            //
            $departments = $this->course_model->getActiveDepartments($company_sid);
            $this->pargs['departments'] = $departments;
            //
            $teams = $this->course_model->getTeams($this->pargs['company_sid'], $departments);
            $this->pargs['teams'] = $teams;
            $this->pargs['hide_employer_section'] = 1;
            // Get Settings
            $settings['roles'] = getRoles();
            $settings['departments'] = $departments;
            $settings['teams'] = $teams;
            $settings['employees'] = $employees;
            $this->pargs['settings'] = $settings;
            //
            $this->pargs['company_dt'] = $this->course_model->GetCompanyDepartmentAndTeams($this->pargs['company_sid']);
            // Set company employees
            $this->pargs['company_employees'] = $this->course_model->GetAllEmployees($this->pargs['company_sid']);
            //
            $basic_info = array();
            $basic_info['sid'] = 1;
            $basic_info['title'] = 'EBS System';
            $basic_info['course_start_date'] = '2021-09-23';
            $basic_info['is_course_expired'] = 'yes';
            $basic_info['course_expired_day'] = 5;
            $basic_info['course_expired_type'] = 'day';
            $basic_info['course_status'] = 0;
            $basic_info['employees'] = 130;
            $basic_info['progress'] = '30%'; 
            //
            $documents = array();
            $documents[0]['title'] = 'PDF Document with';
            $documents[0]['url'] = '0057-test_latest_uploaded_document-58-Yo2.pdf';
            $documents[0]['ext'] = 'pdf';
            $documents[1]['title'] = 'Manual Upload Image cassette';
            $documents[1]['url'] = 'CV_ONE.doc';
            $documents[1]['ext'] = 'doc';
            //
            $this->pargs['basic_info'] = $basic_info;
            $this->pargs['documents'] = $documents;
            //
            $this->pargs['active_tab'] = 'courses';
            //
            $this->load->view($this->header, $this->pargs);
            $this->load->view("{$this->pp}header");
            $this->load->view("{$this->pp}view_course");
            $this->load->view("{$this->pp}footer");
            $this->load->view($this->footer);
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function add_employees ($course_id) {
        if ($this->session->userdata('logged_in')) {
            $this->pargs['session'] = $this->session->userdata('logged_in');
            $security_sid = $this->pargs['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $this->pargs['security_details'] = $security_details;
            check_access_permissions($security_details, 'learning_center', 'online_video');
            $company_sid = $this->pargs['session']['company_detail']['sid'];
            $employer_sid = $this->pargs['session']['employer_detail']['sid'];
            $this->pargs['load_view'] = $this->pargs['session']['company_detail']['ems_status'];
            //
            $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
            $this->pargs['company_sid'] = $company_sid;
            $this->pargs['employer_sid'] = $employer_sid;
            // Set title
            $this->pargs['title'] = 'Learning Management System';
            // Set employee information for the blue screen
            $employees = $this->course_model->get_all_employees($company_sid);
            $this->pargs['employeesList'] = $employees;
            //
            $departments = $this->course_model->getActiveDepartments($company_sid);
            $this->pargs['departments'] = $departments;
            //
            $teams = $this->course_model->getTeams($this->pargs['company_sid'], $departments);
            $this->pargs['teams'] = $teams;
            $this->pargs['hide_employer_section'] = 1;
            // Get Settings
            $settings['roles'] = getRoles();
            $settings['departments'] = $departments;
            $settings['teams'] = $teams;
            $settings['employees'] = $employees;
            $this->pargs['settings'] = $settings;
            //
            $this->pargs['company_dt'] = $this->course_model->GetCompanyDepartmentAndTeams($this->pargs['company_sid']);
            // Set company employees
            $this->pargs['company_employees'] = $this->course_model->GetAllEmployees($this->pargs['company_sid']);
            //
            $basic_info = array();
            $basic_info['sid'] = 1;
            $basic_info['title'] = 'EBS System';
            $basic_info['course_start_date'] = '2021-09-23';
            $basic_info['is_course_expired'] = 'yes';
            $basic_info['course_expired_day'] = 5;
            $basic_info['course_expired_type'] = 'day';
            $basic_info['course_status'] = 0;
            $basic_info['employees'] = 130;
            $basic_info['progress'] = '30%'; 
            //
            $this->pargs['basic_info'] = $basic_info;
            //
            $this->pargs['active_tab'] = 'courses';
            //
            $this->load->view($this->header, $this->pargs);
            $this->load->view("{$this->pp}header");
            $this->load->view("{$this->pp}add_employee");
            $this->load->view("{$this->pp}footer");
            $this->load->view($this->footer);
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    /**
     * AJAX Responder
     */
    private function resp()
    {
        header('Content-type: application/json');
        echo json_encode($this->resp);
        exit(0);
    }

}