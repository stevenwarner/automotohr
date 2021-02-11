<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Task_management extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('tasks_management_model');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'task_management');
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $company_sid                                                        = $data['session']['company_detail']['sid'];

                $data['title']                                                  = 'Task Assignment Management';
                $applicants_assigned_by_me                                      = $this->tasks_management_model->get_assigned_tasks($employer_sid, 'assigned', 'by_me');
                $applicants_assigned_to_me                                      = $this->tasks_management_model->get_assigned_tasks($employer_sid, 'assigned', 'to_me');
                $data['assigned_applicants']                                    = $applicants_assigned_to_me;
                $data['applicants_assigned_by_me']                              = $applicants_assigned_by_me;
                
                $this->load->view('main/header', $data);
                $this->load->view('tasks_management/index');
                $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function assign_applicant($pre_selected=null) {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'task_management');
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_name                                                      = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
            $company_timezone                                                   = $data['session']['portal_detail']['company_timezone'];
            $company_name                                                       = $data['session']['company_detail']['CompanyName'];

            $config = array(
                            array(
                                'field' => 'applicant_sid[]',
                                'label' => 'Applicant Name required!',
                                'rules' => 'trim|required|xss_clean'
                            ),
                            array(
                                'field' => 'employee_sid',
                                'label' => 'Employee Name required!',
                                'rules' => 'trim|required|xss_clean'
                            ),
                            array(
                                'field' => 'notes',
                                'label' => 'Assignment Notes required!',
                                'rules' => 'trim|required|xss_clean'
                            )
                        );

            $this->form_validation->set_message('required', 'Please provide your %s.');
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
//                $applicants_jobs_list                                           = $this->tasks_management_model->applicants_jobs_list($company_sid); // it is not required in intial built
//                $all_applicants                                                 = $this->tasks_management_model->get_all_applicants($company_sid); // it is not required in intial built
//                $all_ative_jobs                                                 = $this->tasks_management_model->get_all_active_jobs($company_sid);
//                $applicants_details                                             = array();
                /* // it is not required in intial built
                foreach($all_applicants as $applicant_info){
                    $applicant_sid                                              = $applicant_info['applicant_sid'];
                    $first_name                                                 = $applicant_info['first_name'];
                    $last_name                                                  = $applicant_info['last_name'];
                    $email                                                      = $applicant_info['email'];
                    $resume                                                     = $applicant_info['resume'];
                    $cover_letter                                               = $applicant_info['cover_letter'];
                    $list_sid                                                   = $applicant_info['list_sid'];
                    $job_sid                                                    = $applicant_info['job_sid'];
                    $job_title                                                  = $applicant_info['job_title'];
                    
                    if (!array_key_exists($applicant_sid, $applicants_details)) {
                        $applicants_details[$applicant_sid] = array('applicant_sid' => $applicant_sid,
                                                                    'first_name' => $first_name,
                                                                    'last_name' => $last_name,
                                                                    'email' => $email,
                                                                    'resume' => $resume,
                                                                    'cover_letter' => $cover_letter,
                                                                    'job_details' => array(
                                                                                        array ('list_sid' => $list_sid,
                                                                                               'job_sid' => $job_sid,
                                                                                               'job_title' => $job_title
                                                                                              )
                                                                                    )
                                                                    );
                    } else {
                        $applicants_details[$applicant_sid]['job_details'][] = array('list_sid' => $list_sid,
                                                                                     'job_sid' => $job_sid,
                                                                                     'job_title' => $job_title
                                                                                    );
                        
                    }
                } */

//                $data['all_applicants']                                         = $all_applicants;
//                $data['all_ative_jobs']                                         = $all_ative_jobs;


                //                echo "<pre>";
                //                print_r($all_employees);
                //                echo "</pre>";


                $data['title']                                                  = 'Assign Applicant to Hiring Managers';
                $access_level                                                   = array('Admin', 'Hiring Manager', 'Manager');
                $primary_applicants_data                                        = $this->tasks_management_model->primary_applicants_data($company_sid);
                $all_employees                                                  = $this->tasks_management_model->get_all_hiring_managers($company_sid, $access_level, $employer_sid);
                $pre_selected                                                   = urldecode($pre_selected);
                $pre_selected                                                   = explode(',', $pre_selected);
                $data['pre_selected']                                           = $pre_selected;
                $data['all_employees']                                          = $all_employees;
                $data['primary_applicants_data']                                = $primary_applicants_data;
                $this->load->view('main/header', $data);
                $this->load->view('tasks_management/assign_applicant');
                $this->load->view('main/footer');
            } else {
                $formpost                                                       = $this->input->post(NULL, TRUE);
                $applicant_sid                                                  = $formpost['applicant_sid'];
                $employee_sid                                                   = $formpost['employee_sid'];
                $assigned_notes                                                 = $formpost['notes'];
                date_default_timezone_set($company_timezone);
                $assigned_date                                                  = date('Y-m-d H:i:s');
                $applicant_names                                                = array();

                foreach($applicant_sid as $app_sid){
                    $applicant_already_assigned                                 = $this->tasks_management_model->check_if_applicant_already_assigned($app_sid, $employee_sid, $employer_sid);
                    $applicant_name                                             = $this->tasks_management_model->get_applicant_name($app_sid);
                    $applicant_names[]                                          = $applicant_name;

                    if($applicant_already_assigned == 0) {
                        $insert_primary = array('company_sid'                   => $company_sid,
                                                'employer_sid'                  => $employee_sid,
                                                'applicant_sid'                 => $app_sid,
                                                'applicant_name'                => $applicant_name,
                                                'assigned_by_sid'               => $employer_sid,
                                                'assigned_by_name'              => $employer_name,
                                                'assigned_date'                 => $assigned_date,
                                                'assigned_notes'                => $assigned_notes,
                                                'task_status'                   => 'unread',
                                                );

                        $assignment_management_sid                              = $this->tasks_management_model->insert_data($insert_primary, 'assignment_management');
                        $insert_notes = array(  'assignment_management_sid'     => $assignment_management_sid,
                                                'company_sid'                   => $company_sid,
                                                'applicant_sid'                 => $app_sid,
                                                'employer_sid'                  => $employee_sid,
                                                'note_txt'                      => $assigned_notes,
                                                'note_datetime'                 => $assigned_date,
                                                'notes_by_sid'                  => $employer_sid,
                                                'notes_by_name'                 => $employer_name
                                                );

                        $notes_sid = $this->tasks_management_model->insert_data($insert_notes, 'assignment_notes');
                    } else {
                        $insert_notes = array(  'assignment_management_sid'     => $applicant_already_assigned,
                                                'company_sid'                   => $company_sid,
                                                'applicant_sid'                 => $app_sid,
                                                'employer_sid'                  => $employee_sid,
                                                'note_txt'                      => $assigned_notes,
                                                'note_datetime'                 => $assigned_date,
                                                'notes_by_sid'                  => $employer_sid,
                                                'notes_by_name'                 => $employer_name
                                            );

                        $notes_sid = $this->tasks_management_model->insert_data($insert_notes, 'assignment_notes');
                    }
                }

                $employee_details                                               = $this->tasks_management_model->get_employer_details($employee_sid);
                //send email notification
                $applicants                                                     = '';
                $applicants                                                     .= '<ol>';
                
                foreach($applicant_names  as $applicant_name) {
                    $applicants                                                 .= '<li><strong>' . $applicant_name . '</strong></li>';
                }
                
                $excutive_admin_check = '';
                
                if($employee_details['is_executive_admin'] == 1) { 
                    $excutive_admin_check = '&nbsp;(Executive Admin)';
                } 
                
                $applicants                                                     .= '</ol>';
                $replacement_array                                              = array();
                $replacement_array['assigned_to_name']                          = $employee_details['first_name'] . ' ' . $employee_details['last_name'].$excutive_admin_check;
                $replacement_array['assigned_by_name']                          = $employer_name.'&nbsp;['.$company_name.']';
                $replacement_array['applicants']                                = $applicants;
                $replacement_array['company_name']                              = $company_name;
                $to_email                                                       = $employee_details['email'];
                log_and_send_templated_email(APPLICANT_ASSIGNMENT_NOTIFICATION, $to_email, $replacement_array);
                $this->session->set_flashdata('message', '<b>Success:</b> Applicant(s) assigned successfully!');
                redirect('task_management');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function details($sid=NULL) {
        if ($sid==NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No record found!');
                redirect('task_management');
            }
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'dashboard', 'task_management');
            $company_timezone                                                   = $data['session']['portal_detail']['company_timezone'];
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_name                                                      = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
            $assignment_details                                                 = $this->tasks_management_model->get_assignment_details($sid, $company_sid);

            if (empty($assignment_details)) {
                $this->session->set_flashdata('message', '<b>Error:</b> No record found!');
                redirect('task_management');
            }
            
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == FALSE) {
                $data['title']                                                  = 'Assignment Details';
                $applicant_sid                                                  = $assignment_details[0]['applicant_sid'];
                $applicant_details                                              = $this->tasks_management_model->get_applicant_details($applicant_sid, $company_sid);
                $data['assignment_details']                                     = $assignment_details;
                $data['applicant_details']                                      = $applicant_details;
                $data['company_sid']                                            = $company_sid;
                $data['employer_sid']                                           = $employer_sid;
                $this->load->view('main/header', $data);
                $this->load->view('tasks_management/assignment_details');
                $this->load->view('main/footer');
            } else {
                $formpost                                                       = $this->input->post(NULL, TRUE);
                $perform_action                                                 = $formpost['perform_action'];
                $note_txt                                                       = $formpost['note_txt'];
                date_default_timezone_set($company_timezone);
                $current_date_time                                              = date('Y-m-d H:i:s');
                
                if($perform_action == 'add_new_note') {
                    $data_to_insert = array (   'assignment_management_sid'     => $assignment_details[0]['assignment_management_sid'],
                                                'company_sid'                   => $assignment_details[0]['company_sid'],
                                                'applicant_sid'                 => $assignment_details[0]['applicant_sid'],
                                                'employer_sid'                  => $assignment_details[0]['employer_sid'],
                                                'note_txt'                      => $note_txt,
                                                'note_datetime'                 => $current_date_time,
                                                'notes_by_sid'                  => $employer_sid,
                                                'notes_by_name'                 => $employer_name
                                            );
                    $notes_sid = $this->tasks_management_model->insert_data($data_to_insert, 'assignment_notes');
                    $this->session->set_flashdata('message', '<b>Success:</b> Your note is added successfully!');
                }
               
                redirect('task_management/details/'.$assignment_details[0]['assignment_management_sid']);                
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }
    
    function perform_action(){
        $action = $this->input->post("action");
        $sid = $this->input->post("sid");
        $this->tasks_management_model->unassign($sid);
    }
}